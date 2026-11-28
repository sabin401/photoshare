<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Image;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $totalImages = Image::count();

        $totalAdmins = User::where(
            'role',
            'admin'
        )->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalImages',
            'totalAdmins'
        ));
    }
}