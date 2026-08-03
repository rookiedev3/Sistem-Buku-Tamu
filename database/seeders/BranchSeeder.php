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
                'address' => null,
                'phone' => null,
                'is_active' => true,
            ],
            [
                'code' => 'MGL',
                'name' => 'Cabang Magelang',
                'address' => null,
                'phone' => null,
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