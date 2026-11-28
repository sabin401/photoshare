<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImageController;
use App\Http\Middleware\ImageAuthorization;

// Public routes
Route::view('/', 'home')->name('home');

Route::get('/api/images/latest', [ImageController::class, 'latest'])
    ->name('images.latest');

Route::get('/api/auth-state', [ImageController::class, 'authState'])
    ->name('auth.state');

// Guest routes
Route::middleware('guest')->group(function () {

    Route::view('/register', 'auth.register')
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1');

    Route::view('/login', 'auth.login')
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');
});

// Authenticated user routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [ImageController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Image pages
    Route::get('/images/create', [ImageController::class, 'create'])
        ->name('images.create');

    Route::get('/images/{image}/edit', [ImageController::class, 'edit'])
        ->name('images.edit');

    Route::view('/community', 'images.community')
        ->name('images.community');

    // Protected API routes
    Route::middleware(ImageAuthorization::class)->group(function () {

        Route::get('/api/images/mine', [ImageController::class, 'mine'])
            ->name('images.mine');

        Route::get('/api/images/others', [ImageController::class, 'others'])
            ->name('images.others');

        Route::post('/api/images', [ImageController::class, 'store'])
            ->name('images.store');

        Route::put('/api/images/{image}', [ImageController::class, 'update'])
            ->name('images.update');

        Route::delete('/api/images/{image}', [ImageController::class, 'destroy'])
            ->name('images.destroy');

    });
});
require __DIR__.'/admin.php';