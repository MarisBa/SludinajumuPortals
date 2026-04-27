<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->only(['name', 'username', 'bio', 'location', 'address']);

        if ($request->hasFile('image')) {
            // Delete old avatar
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            $data['avatar'] = $request->file('image')->store('public/avatar');
        }

        $user->update($data);

        return redirect()->back()->with('message', 'Profils veiksmīgi atjaunināts!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ], [
            'password.required' => 'Ievadi savu paroli.',
            'password.current_password' => 'Nepareiza parole.',
        ]);

        $user = $request->user();
        auth()->logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/home')->with('message', 'Tavs konts ir dzēsts.');
    }
}
