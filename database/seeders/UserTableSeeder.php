<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'branch_id' => 1,
                'name' => 'Andi Wijaya',
                'email' => 'owner@gmail.com',
                'phone' => '+6281234567890',
                'password' => bcrypt('owner123'),
                'role' => 'owner',
                'is_active' => true,
                'last_login' => null,
            ],
            [
                'branch_id' => 1,
                'name' => 'Budi Santoso',
                'email' => 'manager@gmail.com',
                'phone' => '+6281234567891',
                'password' => bcrypt('manager123'),
                'role' => 'manager',
                'is_active' => true,
                'last_login' => null,
            ],
            [
                'branch_id' => 1,
                'name' => 'Citra Dewi',
                'email' => 'admin@gmail.com',
                'phone' => '+6281234567892',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'last_login' => null,
            ],
            [
                'branch_id' => 1,
                'name' => 'Dedi Kurniawan',
                'email' => 'pic@gmail.com',
                'phone' => '+6281234567893',
                'password' => bcrypt('pic123'),
                'role' => 'pic',
                'is_active' => true,
                'last_login' => null,
            ],
            [
                'branch_id' => 1,
                'name' => 'Eka Prasetyo',
                'email' => 'pic1@gmail.com',
                'phone' => '+6281234567894',
                'password' => bcrypt('pic123'),
                'role' => 'pic',
                'is_active' => true,
                'last_login' => null,
            ],
            [
                'branch_id' => 2,
                'name' => 'Fajar Nugroho',
                'email' => 'pic2@gmail.com',
                'phone' => '+6281234567895',
                'password' => bcrypt('pic123'),
                'role' => 'pic',
                'is_active' => true,
                'last_login' => null,
            ],
            [
                'branch_id' => 2,
                'name' => 'Gilang Ramadhan',
                'email' => 'security@gmail.com',
                'phone' => '+6281234567896',
                'password' => bcrypt('security123'),
                'role' => 'security',
                'is_active' => true,
                'last_login' => null,
            ],
            [
                'branch_id' => 1,
                'name' => 'Hesti Lestari',
                'email' => 'tamu@gmail.com',
                'phone' => '+6281234567897',
                'password' => bcrypt('tamu123'),
                'role' => 'tamu',
                'is_active' => true,
                'last_login' => null,
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}