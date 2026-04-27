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
        User::create([
            'name'       => 'Super Administrator',
            'email'      => 'superadmin@ocd.gov.ph',
            'password'   => Hash::make('SuperAdmin@123'),
            'role'       => User::ROLE_SUPER_ADMIN,
            'department' => 'IT / Systems Administration',
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'NDRRMOC Administrator',
            'email'      => 'ndrrmoc@ocd.gov.ph',
            'password'   => Hash::make('Ndrrmoc@123'),
            'role'       => User::ROLE_NDRRMOC,
            'department' => 'NDRRMOC',
            'is_active'  => true,
        ]);

        User::create([
            'name'       => 'NAB Administrator',
            'email'      => 'nab@ocd.gov.ph',
            'password'   => Hash::make('NabAdmin@123'),
            'role'       => User::ROLE_NAB,
            'department' => 'NAB',
            'is_active'  => true,
        ]);

        User::create([
            'name'           => 'Juan Dela Cruz',
            'email'          => 'juan@ocd.gov.ph',
            'password'       => Hash::make('User@123'),
            'role'           => User::ROLE_USER,
            'department'     => 'Operations',
            'contact_number' => '09171234567',
            'is_active'      => true,
        ]);

        $venues = [
            [
                'name'        => 'Conference Room A',
                'location'    => '2nd Floor, Main Building',
                'capacity'    => 30,
                'description' => 'Air-conditioned conference room with projector and whiteboard.',
                'amenities'   => ['projector', 'whiteboard', 'airconditioner', 'wifi'],
                'is_active'   => true,
            ],
            [
                'name'        => 'Training Hall',
                'location'    => '3rd Floor, Main Building',
                'capacity'    => 80,
                'description' => 'Spacious training hall suitable for seminars and large meetings.',
                'amenities'   => ['projector', 'sound_system', 'airconditioner', 'wifi', 'podium'],
                'is_active'   => true,
            ],
            [
                'name'        => 'Board Room',
                'location'    => '4th Floor, Executive Wing',
                'capacity'    => 15,
                'description' => 'Executive board room with video conferencing capability.',
                'amenities'   => ['projector', 'video_conference', 'airconditioner', 'wifi'],
                'is_active'   => true,
            ],
            [
                'name'        => 'Auditorium',
                'location'    => 'Ground Floor, Events Building',
                'capacity'    => 200,
                'description' => 'Main auditorium for large-scale events and ceremonies.',
                'amenities'   => ['stage', 'sound_system', 'projector', 'airconditioner', 'wifi'],
                'is_active'   => true,
            ],
        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }
    }
}
