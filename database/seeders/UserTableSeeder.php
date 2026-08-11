<?php

namespace Database\Seeders;

use App\Models\branches;
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
            'branch_id' => '1',
            'name' => 'Bapak owner',
            'email' => 'owner@gmail.com',
            'phone' =>  '+6287123456789',
            'password' => bcrypt('owner123'),
            'role' => 'owner',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'branch_id' => '1',
            'name' => 'Bapak manager',
            'email' => 'manager@gmail.com',
            'phone' =>  '+6287123456789',
            'password' => bcrypt('manager123'),
            'role' => 'manager',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'branch_id' => '1',
            'name' => 'Bapak admin',
            'email' => 'admin@gmail.com',
            'phone' =>  '+6287123456789',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'branch_id' => '1',
            'name' => 'Bapak PIC',
            'email' => 'pic@gmail.com',
            'phone' =>  '+6287123456789',
            'password' => bcrypt('pic123'),
            'role' => 'pic',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'branch_id' => '1',
            'name' => 'Bapak PIC Sleman',
            'email' => 'pic1@gmail.com',
            'phone' =>  '+6287123456789',
            'password' => bcrypt('pic123'),
            'role' => 'pic',
            'is_active' => true,
            'last_login' => null,
        ],
         [
            'branch_id' => '2',
            'name' => 'Bapak PIC Magelang',
            'email' => 'pic2@gmail.com',
            'phone' =>  '+6287123456789',
            'password' => bcrypt('pic123'),
            'role' => 'pic',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'branch_id' => '2',
            'name' => 'Bapak Security',
            'email' => 'security@gmail.com',
            'phone' =>  '+6287123456789',
            'password' => bcrypt('security123'),
            'role' => 'security',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'branch_id' => '1',
            'name' => 'Bapak Tamu',
            'email' => 'tamu@gmail.com',
            'phone' =>  '+6287123456789',
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