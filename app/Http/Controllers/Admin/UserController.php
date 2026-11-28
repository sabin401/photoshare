<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Display all users
    public function index()
    {
        $users = User::orderBy('id')->get();

        return view('admin.users.index', compact('users'));
    }

    // Display create user form
    public function create()
    {
        return view('admin.users.create');
    }

    // Store a new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|alpha_dash|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        $user = new User();

        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role = $validated['role'];

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    // Display edit user form
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update an existing user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'alpha_dash',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        // Prevent administrators from removing their own role
        if (
            $user->id === auth()->id() &&
            $validated['role'] !== 'admin'
        ) {
            return back()->withErrors([
                'role' => 'You cannot remove your own administrator role.',
            ]);
        }

        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    // Delete an existing user
    public function destroy(User $user)
    {
        // Prevent administrators from deleting their own account
        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'user' => 'You cannot delete your own administrator account.',
            ]);
        }

        // Retrieve the user's uploaded image paths
        $imagePaths = Image::where('user_id', $user->id)
            ->pluck('image_path');

        $avatar = $user->avatar;

        // Delete the user and related database records
        DB::transaction(function () use ($user) {
            $user->delete();
        });

        // Remove uploaded image files
        foreach ($imagePaths as $path) {
            Storage::disk('public')->delete($path);
        }

        // Remove the user's avatar
        if ($avatar) {
            Storage::disk('public')->delete($avatar);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}