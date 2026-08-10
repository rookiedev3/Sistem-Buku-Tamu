<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BranchSeeder::class,
            UserTableSeeder::class,
            ProductsSeeder::class,
            VisitPurposesSeeder::class,
            LeadSourcesSeeder::class,
            GuestCategoriesSeeder::class,
            GuestVisitSeeder::class,
        ]);
    }
}