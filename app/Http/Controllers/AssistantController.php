<?php

namespace App\Http\Controllers;

use App\Services\BuyingAssistantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssistantController extends Controller
{
    /**
     * Pieņem lietotāja pieprasījumu un atgriež AI ieteiktos sludinājumus
     * JSON formātā, ko frontend čata logrīks attēlo kā kartiņas.
     */
    public function ask(Request $request, BuyingAssistantService $service)
    {
        $apiKey = config('services.anthropic.key');
        \Log::error('DIAG key_length=' . strlen((string)$apiKey) .
            ' key_prefix=' . substr((string)$apiKey, 0, 15));

        try {
            $ping = \Illuminate\Support\Facades\Http::timeout(5)
                ->withHeaders(['x-api-key' => $apiKey])
                ->get('https://api.anthropic.com/v1/models');
            \Log::error('DIAG anthropic_status=' . $ping->status());
        } catch (\Exception $e) {
            \Log::error('DIAG anthropic_unreachable=' . $e->getMessage());
        }

        $validated = $request->validate([
            'query' => 'required|string|min:3|max:300',
        ]);

        try {
            $result = $service->recommend($validated['query']);
        } catch (\Throwable $e) {
            // Pēdējais drošības tīkls — pat ja serviss ne ar SQL fallback netiek galā.
            Log::error('AI assistant error: ' . $e->getMessage());
            return response()->json([
                'ai_available'   => false,
                'intro'          => null,
                'tip'            => null,
                'advertisements' => [],
                'message'        => 'Radās tehniska kļūda. Lūdzu, mēģini vēlreiz.',
            ]);
        }

        // Sludinājumus pārveidojam logrīkam piemērotā formā.
        // Bildes URL veidojam pēc tā paša parauga, ko izmanto pārējās skats —
        // privātās bildes tiek pasniegtas caur 'ad.image' kontroleri.
        $ads = collect($result['advertisements'])->map(function ($ad) {
            $img = $ad->feature_image;
            $imgUrl = $img && str_starts_with($img, 'http')
                ? $img
                : route('ad.image', basename($img));

            return [
                'id'              => $ad->id,
                'name'            => $ad->name,
                'price'           => $ad->price,
                'location'        => $ad->listing_location ?? optional($ad->city)->name,
                'image'           => $imgUrl,
                'url'             => route('product.view', [$ad->id, $ad->slug]),
                'ai_reason'       => $ad->ai_reason ?? null,
                'seller_verified' => $ad->user && $ad->user->email_verified_at !== null,
            ];
        })->values();

        return response()->json([
            'ai_available'   => $result['ai_available'],
            'intro'          => $result['intro'],
            'tip'            => $result['tip'],
            'message'        => $result['message'],
            'advertisements' => $ads,
        ]);
    }
}
