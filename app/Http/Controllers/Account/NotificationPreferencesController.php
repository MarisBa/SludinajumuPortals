<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateNotificationsRequest;

class NotificationPreferencesController extends Controller
{
    public function update(UpdateNotificationsRequest $request)
    {
        $request->user()->update([
            'notification_prefs' => [
                'email' => $request->input('email', []),
                'push' => $request->input('push', []),
                'sms' => $request->input('sms', []),
            ],
        ]);

        return redirect()->back()->with('message', 'Paziņojumu iestatījumi saglabāti!');
    }
}
