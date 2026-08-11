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
            'name' => 'Bapak owner',
            'email' => 'owner@gmail.com',
            'phone' =>  '',
            'password' => bcrypt('owner123'),
            'role' => 'owner',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'Bapak manager',
            'email' => 'manager@gmail.com',
            'phone' =>  '',
            'password' => bcrypt('manager123'),
            'role' => 'manager',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'Bapak admin',
            'email' => 'admin@gmail.com',
            'phone' =>  '',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'Bapak PIC',
            'email' => 'pic@gmail.com',
            'phone' =>  '',
            'branch_id' => '1',
            'password' => bcrypt('pic123'),
            'role' => 'pic',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'Bapak PIC Sleman',
            'email' => 'pic1@gmail.com',
            'phone' =>  '',
            'branch_id' => '1',
            'password' => bcrypt('pic123'),
            'role' => 'pic',
            'is_active' => true,
            'last_login' => null,
        ],
         [
            'name' => 'Bapak PIC Magelang',
            'email' => 'pic2@gmail.com',
            'phone' =>  '',
            'branch_id' => '2',
            'password' => bcrypt('pic123'),
            'role' => 'pic',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'Bapak Security',
            'email' => 'security@gmail.com',
            'phone' =>  '',
            'password' => bcrypt('security123'),
            'role' => 'security',
            'is_active' => true,
            'last_login' => null,
        ],
        [
            'name' => 'Bapak Tamu',
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