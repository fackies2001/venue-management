<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'name'              => 'John Vincent',
            'email'             => 'fabayjohnvincent@gmail.com',
            'password'          => Hash::make('password123'),
            'role'              => User::ROLE_SUPER_ADMIN,
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }
}
