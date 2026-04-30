<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);

        User::create([
            'email'      => 'superadmin@ocd.gov.ph',
            'password'   => Hash::make('SuperAdmin@123'),
            'name'       => 'Super Administrator',
            'role'       => User::ROLE_SUPER_ADMIN,
            'is_active'  => true,
        ]);

        User::create([
            'name'           => 'Admin User',
            'email'          => 'admin@ocd.gov.ph',
            'password'       => Hash::make('Admin@123'),
            'role'           => User::ROLE_ADMIN,
            'is_active'      => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'           => 'Juan Dela Cruz',
            'email'          => 'juan@ocd.gov.ph',
            'password'       => Hash::make('User@123'),
            'role'           => User::ROLE_USER,
            'contact_number' => '09171234567',
            'is_active'      => true,
            'email_verified_at' => now(),
        ]);

        $venues = [
            // NDRRMOC Building
            ['name' => 'Meeting Room A -1st Floor',       'building' => 'NDRRMOC Building',   'location' => 'NDRRMOC Building', 'capacity' => 0, 'is_active' => true],
            ['name' => 'Meeting Room B -3rd Floor',        'building' => 'NDRRMOC Building',   'location' => 'NDRRMOC Building', 'capacity' => 0, 'is_active' => true],
            ['name' => 'Main Conference Room -3rd Floor',  'building' => 'NDRRMOC Building',   'location' => 'NDRRMOC Building', 'capacity' => 0, 'is_active' => true],
            ['name' => 'Office of the Director',           'building' => 'NDRRMOC Building',   'location' => 'NDRRMOC Building', 'capacity' => 0, 'is_active' => true],

            // New Admin Building
            ['name' => 'NAB VIP Room',                     'building' => 'New Admin Building', 'location' => 'New Admin Building', 'capacity' => 0, 'is_active' => true],
            ['name' => 'Main Conference',                  'building' => 'New Admin Building', 'location' => 'New Admin Building', 'capacity' => 0, 'is_active' => true],
            ['name' => 'Multimedia Room',                  'building' => 'New Admin Building', 'location' => 'New Admin Building', 'capacity' => 0, 'is_active' => true],
            ['name' => '8th Floor Multipurpose Room',      'building' => 'New Admin Building', 'location' => 'New Admin Building', 'capacity' => 0, 'is_active' => true],
            ['name' => 'Office of the Director',           'building' => 'New Admin Building', 'location' => 'New Admin Building', 'capacity' => 0, 'is_active' => true],

        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }
    }
}
