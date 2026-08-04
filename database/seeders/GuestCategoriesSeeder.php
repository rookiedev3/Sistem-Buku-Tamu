<?php

namespace Database\Seeders;

use App\Models\guest_categories;
use Illuminate\Database\Seeder;

class GuestCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $guestCategories = [
            ['id' => 1, 'name' => 'Prospek'],
            ['id' => 2, 'name' => 'Klien'],
            ['id' => 3, 'name' => 'Vendor'],
            ['id' => 4, 'name' => 'Pelamar'],
            ['id' => 5, 'name' => 'Mitra'],
            ['id' => 6, 'name' => 'Umum'],
        ];

        foreach ($guestCategories as $category) {
            guest_categories::query()->updateOrCreate(
                ['id' => $category['id']],
                $category
            );
        }
    }
}