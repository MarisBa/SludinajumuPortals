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
            ],
        ]);

        return redirect()->back()->with('message', 'Privātuma iestatījumi saglabāti!');
    }
}
