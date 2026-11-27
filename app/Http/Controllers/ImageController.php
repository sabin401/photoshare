<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // Display dashboard
    public function index()
    {
        return view('dashboard');
    }

    // Display upload page
    public function create()
    {
        return view('images.create');
    }

    // Publish an image
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'filter' => 'required|in:none,grayscale,sepia,brightness,contrast',
        ]);

        $duplicate = Image::where('user_id', Auth::id())
            ->where('title', $validated['title'])
            ->exists();

        if ($duplicate) {
            return response()->json([
                'message' => 'This image title already exists.'
            ], 422);
        }

        $path = $request->file('image')
            ->store('images', 'public');

        $image = Image::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image_path' => $path,
            'filter' => $validated['filter'],
        ]);

        return response()->json([
            'message' => 'Image published successfully.',
            'image' => $this->formatImage($image)
        ], 201);
    }

    // Display image editing page
    public function edit(Image $image)
    {
        abort_unless($image->user_id == Auth::id(), 403);

        return view('images.edit', compact('image'));
    }

    // Update an image
    public function update(Request $request, Image $image)
    {
        abort_unless($image->user_id == Auth::id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'filter' => 'required|in:none,grayscale,sepia,brightness,contrast',
        ]);

        $duplicate = Image::where('user_id', Auth::id())
            ->where('title', $validated['title'])
            ->where('id', '!=', $image->id)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'message' => 'This image title already exists.'
            ], 422);
        }

        $oldPath = $image->image_path;

        $image->title = $validated['title'];
        $image->description = $validated['description'] ?? null;
        $image->filter = $validated['filter'];

        if ($request->hasFile('image')) {
            $image->image_path = $request->file('image')
                ->store('images', 'public');
        }

        $image->save();

        if ($oldPath !== $image->image_path) {
            Storage::disk('public')->delete($oldPath);
        }

        return response()->json([
            'message' => 'Image updated successfully.',
            'image_url' => asset('storage/' . $image->image_path),
            'filter' => $image->filter
        ]);
    }

    // Delete an image
    public function destroy(Image $image)
    {
        abort_unless($image->user_id == Auth::id(), 403);

        $path = $image->image_path;

        $image->delete();

        Storage::disk('public')->delete($path);

        return response()->json([
            'message' => 'Image deleted successfully.'
        ]);
    }

    // Retrieve latest published images
    public function latest()
    {
        $images = Image::with('user')
            ->latest()
            ->limit(20)
            ->get();

        return response()->json(
            $images->map(fn ($image) => $this->formatImage($image))
        );
    }

    // Retrieve current user's images
    public function mine()
    {
        $images = Image::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json(
            $images->map(fn ($image) => $this->formatImage($image))
        );
    }

    // Retrieve images belonging to other users
    public function others()
    {
        $images = Image::with('user')
            ->where('user_id', '!=', Auth::id())
            ->latest()
            ->get();

        return response()->json(
            $images->map(fn ($image) => $this->formatImage($image))
        );
    }

    // Retrieve authentication state
    public function authState()
    {
        $user = Auth::user();

        return response()->json([
            'loggedIn' => Auth::check(),
            'user' => $user ? [
                'id' => $user->id,
                'username' => $user->username
            ] : null
        ]);
    }

    // Format image information for the API
    private function formatImage(Image $image)
    {
        return [
            'id' => $image->id,
            'user_id' => $image->user_id,
            'username' => $image->user->username,
            'title' => $image->title,
            'description' => $image->description,
            'image_url' => asset('storage/' . $image->image_path),
            'filter' => $image->filter,
            'created_at' => $image->created_at->format('d M Y'),
            'edit_url' => route('images.edit', $image),
            'delete_url' => route('images.destroy', $image)
        ];
    }
}