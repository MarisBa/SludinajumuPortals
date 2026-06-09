<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    /**
     * Step 1: create a Stripe Checkout Session and redirect to the hosted page.
     * The ad already exists at this point (created unpublished/unpaid by
     * AdvertisementController::store), so we just verify ownership and pay.
     */
    public function checkout($id)
    {
        $ad = Advertisement::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Idempotency: if it's already paid, skip Stripe and just send them home.
        if ($ad->payment_status === 'paid') {
            return redirect()->route('ads.index')
                ->with('message', 'Sludinājums jau ir apmaksāts.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'mode'                 => 'payment',
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'quantity'   => 1,
                'price_data' => [
                    'currency'     => 'eur',
                    'unit_amount'  => (int) config('services.stripe.listing_price', 500),
                    'product_data' => [
                        'name'        => 'Sludinājuma publikācija',
                        'description' => $ad->name,
                    ],
                ],
            ]],
            'metadata' => [
                'advertisement_id' => (string) $ad->id,
                'user_id'          => (string) auth()->id(),
            ],
            'success_url' => route('payment.success', $ad->id) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('payment.cancel', $ad->id),
        ]);

        // Persist the session id so the webhook handler can correlate later if needed.
        $ad->update(['stripe_session_id' => $session->id]);

        return redirect()->away($session->url);
    }

    /**
     * Step 2 (redirect path): user came back from Stripe with ?session_id=...
     * Don't trust the redirect — verify the session server-side with Stripe.
     * The webhook is the source of truth, but this path makes the UI responsive
     * for users who don't wait for the webhook to land.
     */
    public function success(Request $request, $id)
    {
        $ad = Advertisement::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('ads.index')
                ->with('error', 'Trūkst maksājuma sesijas identifikatora.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = StripeSession::retrieve($sessionId);
        } catch (\Throwable $e) {
            Log::error('Stripe success: cannot retrieve session: ' . $e->getMessage());
            return redirect()->route('ads.index')
                ->with('error', 'Neizdevās pārbaudīt maksājumu. Mēģini vēlreiz.');
        }

        if ($session->payment_status === 'paid') {
            $this->markPaid($ad);
            return redirect()->route('ads.index')
                ->with('message', 'Paldies! Sludinājums ir publicēts.');
        }

        return redirect()->route('ads.index')
            ->with('error', 'Maksājums vēl nav apstiprināts. Pārbaudi vēlreiz pēc brīža.');
    }

    /**
     * User clicked "back" on Stripe's hosted page. Ad stays unpaid + unpublished;
     * they can re-trigger checkout from the ads index.
     */
    public function cancel($id)
    {
        return redirect()->route('ads.index')
            ->with('error', 'Maksājums atcelts. Sludinājums nav publicēts.');
    }

    /**
     * Webhook: the authoritative path. Stripe POSTs here on every relevant event;
     * we verify the signature, then on checkout.session.completed look up the ad
     * via the metadata.advertisement_id we set in checkout() and mark it paid.
     * Returns 2xx so Stripe stops retrying.
     */
    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook: invalid payload — ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook: invalid signature — ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $adId    = $session->metadata->advertisement_id ?? null;

            if ($adId) {
                $ad = Advertisement::find($adId);
                if ($ad) {
                    $this->markPaid($ad);
                }
            }
        }

        // Acknowledge everything else (subscription events, refunds, etc.) so
        // Stripe doesn't keep retrying — we just don't act on them yet.
        return response('OK', 200);
    }

    /**
     * Idempotent: safe to call from both the success-redirect handler and the
     * webhook (whichever lands first wins, the second is a no-op).
     */
    private function markPaid(Advertisement $ad): void
    {
        if ($ad->payment_status === 'paid') {
            return;
        }

        $ad->update([
            'payment_status' => 'paid',
            'published'      => 1,
            'paid_at'        => now(),
        ]);
    }
}
