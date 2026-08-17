<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'code' => 'SLM',
                'name' => 'Cabang Sleman',
                'address' => 'Jl. Sleman No. 123, Sleman, Yogyakarta',
                'phone' => '0274-123456',
                'is_active' => true,
            ],
            [
                'code' => 'MGL',
                'name' => 'Cabang Magelang',
                'address' => 'Jl. Magelang No. 456, Magelang, Jawa Tengah',
                'phone' => '0293-654321',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            DB::table('branches')->updateOrInsert(
                ['code' => $branch['code']],
                array_merge($branch, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}