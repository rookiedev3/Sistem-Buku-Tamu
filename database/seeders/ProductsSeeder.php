<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'code' => 'P01',
                'name' => 'Website',
                'category' => 'category1',
                'is_active' => true,
            ],
            [
                'code' => 'P02',
                'name' => 'POS',
                'category' => 'category2',
                'is_active' => true,
            ],
            [
                'code' => 'P03',
                'name' => 'SEO',
                'category' => 'category3',
                'is_active' => true,
            ],
            [
                'code' => 'P04',
                'name' => 'Digital Marketing',
                'category' => 'category4',
                'is_active' => true,
            ],
            [
                'code' => 'P05',
                'name' => 'Custom System',
                'category' => 'category5',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->updateOrInsert(
                ['code' => $product['code']],
                array_merge($product, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
