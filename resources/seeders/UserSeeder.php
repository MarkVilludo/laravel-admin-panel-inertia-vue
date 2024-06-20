<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminData = [
            'name' => "Administrator",
            'email' => "admin@feiwin.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null
        ];

        $admin = User::create($adminData);
        $admin->assignRole('Administrator');
    }
}
