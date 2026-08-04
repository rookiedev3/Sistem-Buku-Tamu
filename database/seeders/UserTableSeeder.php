<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
        [
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'phone' =>  '',
            'password' => bcrypt('owner123'),
            'role' => 'owner',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'phone' =>  '',
            'password' => bcrypt('manager123'),
            'role' => 'manager',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' =>  '',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'PIC',
            'email' => 'pic@gmail.com',
            'phone' =>  '',
            'password' => bcrypt('pic123'),
            'role' => 'pic',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'Security',
            'email' => 'security@gmail.com',
            'phone' =>  '',
            'password' => bcrypt('security123'),
            'role' => 'security',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'Tamu',
            'email' => 'tamu@gmail.com',
            'phone' =>  '',
            'password' => bcrypt('tamu123'),
            'role' => 'tamu',
            'is_active' => true,
            'last_login' => null,
        ]
    ];
            array_map(function (array $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }, $users);
    }
}