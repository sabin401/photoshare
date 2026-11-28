<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrNew([
            'username' => 'admin'
        ]);

        $admin->email = 'admin@photoshare.test';
        $admin->password = Hash::make('admin');
        $admin->role = 'admin';

        $admin->save();
    }
}