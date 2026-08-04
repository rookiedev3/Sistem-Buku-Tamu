<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitPurposes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visit_purposes = [
        [
            'name' => 'Meeting',
            'is_active' => true,
        ],
        [
            'name' => 'Konsultasi',
            'is_active' => true,
        ],
        [
            'name' => 'Pembayaran',
            'is_active' => true,
        ],
        [
            'name' => 'Interview',
            'is_active' => true,
        ],
        [
            'name' => 'Vendor',
            'is_active' => true,
        ],
    ];

        foreach ($visit_purposes as $visit_purpose) {
            DB::table('visit_purposes')->updateOrInsert(
                ['name' => $visit_purpose['name']],
                array_merge($visit_purpose, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
