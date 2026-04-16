<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdatePrivacyRequest;

class PrivacyController extends Controller
{
    public function update(UpdatePrivacyRequest $request)
    {
        $request->user()->update([
            'privacy_prefs' => [
                'profile_visibility' => $request->input('profile_visibility', 'public'),
                'show_phone' => (bool) $request->input('show_phone', true),
                'show_full_name' => (bool) $request->input('show_full_name', true),
                'allow_messages' => (bool) $request->input('allow_messages', true),
                'personalized_ads' => (bool) $request->input('personalized_ads', false),
            ],
        ]);

        return redirect()->back()->with('message', 'Privātuma iestatījumi saglabāti!');
    }
}
