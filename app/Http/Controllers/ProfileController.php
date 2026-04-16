<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:5120',
        ]);

        $user = User::find(auth()->user()->id);
        $image = $user->avatar;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public/avatar');
        }

        $user->update([
            'name' => $request->name,
            'address' => $request->address,
            'avatar' => $image,
        ]);

        return redirect()->back()->with('message', 'Profils veiksmīgi atjaunināts!');
    }
}
