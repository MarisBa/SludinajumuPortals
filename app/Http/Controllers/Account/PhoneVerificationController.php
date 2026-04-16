<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class PhoneVerificationController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^\+371\d{8}$/'],
        ], [
            'phone.required' => 'Ievadi tālruņa numuru.',
            'phone.regex' => 'Tālrunim jābūt formātā +371XXXXXXXX.',
        ]);

        $user = $request->user();
        $cacheKey = 'phone_otp_' . $user->id;

        // Rate limit: 3 per 10 minutes
        $attempts = Cache::get($cacheKey . '_attempts', 0);
        if ($attempts >= 3) {
            return redirect()->back()->with('message', 'Pārāk daudz mēģinājumu. Lūdzu gaidi 10 minūtes.');
        }

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store hashed code in cache for 10 min
        Cache::put($cacheKey, Hash::make($code), 600);
        Cache::put($cacheKey . '_phone', $request->phone, 600);
        Cache::put($cacheKey . '_attempts', $attempts + 1, 600);

        // TODO: Send SMS via real provider
        // SmsService::send($request->phone, "Jūsu verifikācijas kods: $code");

        // For development: log the code
        \Log::info("Phone OTP for user {$user->id}: {$code}");

        return redirect()->back()->with('message', "Verifikācijas kods nosūtīts uz {$request->phone}. (Dev: skatīt logā)");
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'Ievadi verifikācijas kodu.',
            'code.digits' => 'Kodam jābūt 6 cipariem.',
        ]);

        $user = $request->user();
        $cacheKey = 'phone_otp_' . $user->id;
        $storedHash = Cache::get($cacheKey);
        $phone = Cache::get($cacheKey . '_phone');

        if (!$storedHash || !$phone) {
            return redirect()->back()->with('message', 'Kods ir beidzies. Lūdzu pieprasiet jaunu.');
        }

        if (!Hash::check($request->code, $storedHash)) {
            return redirect()->back()->with('message', 'Nepareizs kods. Mēģini vēlreiz.');
        }

        $user->update([
            'phone' => $phone,
            'phone_verified_at' => now(),
        ]);

        Cache::forget($cacheKey);
        Cache::forget($cacheKey . '_phone');
        Cache::forget($cacheKey . '_attempts');

        return redirect()->back()->with('message', 'Tālrunis veiksmīgi verificēts!');
    }

    public function destroy(Request $request)
    {
        $request->user()->update([
            'phone' => null,
            'phone_verified_at' => null,
        ]);

        return redirect()->back()->with('message', 'Tālrunis noņemts.');
    }
}
