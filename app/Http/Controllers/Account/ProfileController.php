<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateProfileRequest;
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
}
