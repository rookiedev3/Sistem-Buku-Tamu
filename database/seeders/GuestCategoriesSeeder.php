<?php

namespace Database\Seeders;

use App\Models\guest_categories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $guestCategories = [
            ['name' => 'Prospek'],
            ['name' => 'Klien'],
            ['name' => 'Vendor'],
            ['name' => 'Pelamar'],
            ['name' => 'Mitra'],
            ['name' => 'Umum'],
        ];

        foreach ($guestCategories as $category) {
            DB::table('guest_categories')->updateOrInsert(
                ['name' => $category['name']],
                array_merge($category, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}