<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ImageController extends Controller
{
    // Display all published images
    public function index()
    {
        $images = Image::with('user')
            ->latest()
            ->get();

        return view('admin.images.index', compact('images'));
    }

    // Display create image form
    public function create()
    {
        $users = User::orderBy('username')->get();

        return view('admin.images.create', compact('users'));
    }

    // Create a new image
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'filter' => 'required|in:none,grayscale,sepia,brightness,contrast',
        ]);

        $duplicate = Image::where('user_id', $validated['user_id'])
            ->where('title', $validated['title'])
            ->exists();

        if ($duplicate) {
            return back()->withInput()->withErrors([
                'title' => 'This image title already exists for this user.',
            ]);
        }

        $path = $request->file('image')
            ->store('images', 'public');

        if (!$path) {
            return back()->withInput()->withErrors([
                'image' => 'Image upload failed.',
            ]);
        }

        try {
            Image::create([
                'user_id' => $validated['user_id'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'image_path' => $path,
                'filter' => $validated['filter'],
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }

        return redirect()
            ->route('admin.images.index')
            ->with('success', 'Image created successfully.');
    }

    // Display edit image form
    public function edit(Image $image)
    {
        $users = User::orderBy('username')->get();

        return view('admin.images.edit', compact('image', 'users'));
    }

    // Update an existing image
    public function update(Request $request, Image $image)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'filter' => 'required|in:none,grayscale,sepia,brightness,contrast',
        ]);

        $duplicate = Image::where('user_id', $validated['user_id'])
            ->where('title', $validated['title'])
            ->where('id', '!=', $image->id)
            ->exists();

        if ($duplicate) {
            return back()->withInput()->withErrors([
                'title' => 'This image title already exists for this user.',
            ]);
        }

        $oldPath = $image->image_path;
        $newPath = null;

        $image->user_id = $validated['user_id'];
        $image->title = $validated['title'];
        $image->description = $validated['description'] ?? null;
        $image->filter = $validated['filter'];

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')
                ->store('images', 'public');

            if (!$newPath) {
                return back()->withInput()->withErrors([
                    'image' => 'Image upload failed.',
                ]);
            }

            $image->image_path = $newPath;
        }

        try {
            $image->save();
        } catch (\Throwable $exception) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }

        if ($newPath && $oldPath !== $newPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()
            ->route('admin.images.index')
            ->with('success', 'Image updated successfully.');
    }

    // Delete an image
    public function destroy(Image $image)
    {
        $path = $image->image_path;

        $image->delete();

        Storage::disk('public')->delete($path);

        return redirect()
            ->route('admin.images.index')
            ->with('success', 'Image deleted successfully.');
    }
}