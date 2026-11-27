<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Display the profile editing page
    public function edit()
    {
        $user = Auth::user();

        return view('auth.edit-profile', compact('user'));
    }

    // Update user details
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user->username = $validated['username'];
        $user->email = $validated['email'];

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Upload a new profile image
        if ($request->hasFile('avatar')) {

            $oldAvatar = $user->avatar;

            $user->avatar = $request->file('avatar')
                ->store('avatars', 'public');

            $user->save();

            if ($oldAvatar) {
                Storage::disk('public')->delete($oldAvatar);
            }
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}