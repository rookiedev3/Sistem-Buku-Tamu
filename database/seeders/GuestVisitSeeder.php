<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GuestVisitSeeder extends Seeder
{
    /**
     * Jalankan: php artisan db:seed --class=GuestVisitSeeder
     */
    public function run(): void
    {
        // 1. Pastikan tabel referensi (branches, visit_purposes, lead_sources, products, users)
        // punya minimal 1 data. Kalau kosong, buat data dummy sederhana.
        $branchId = DB::table('branches')->first()->id
            ?? DB::table('branches')->insertGetId([
                'name'       => 'Kantor Pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $purposeId = DB::table('visit_purposes')->first()->id
            ?? DB::table('visit_purposes')->insertGetId([
                'name'       => 'Konsultasi Produk',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $sourceId = DB::table('lead_sources')->first()->id
            ?? DB::table('lead_sources')->insertGetId([
                'name'       => 'Walk-in',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $userId = DB::table('users')->first()->id
            ?? DB::table('users')->insertGetId([
                'name'       => 'Admin SBT',
                'email'      => 'admin@sbt.test',
                'password'   => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $categoryId = DB::table('guest_categories')->first()->id
            ?? DB::table('guest_categories')->insertGetId([
                'name'       => 'Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $productIds = DB::table('products')->pluck('id')->toArray();
        if (empty($productIds)) {
            $productIds = [
                DB::table('products')->insertGetId([
                    'name' => 'Produk A', 'created_at' => now(), 'updated_at' => now(),
                ]),
                DB::table('products')->insertGetId([
                    'name' => 'Produk B', 'created_at' => now(), 'updated_at' => now(),
                ]),
            ];
        }

        // 2. Data dummy 5 tamu
        $guestsData = [
            ['name' => 'Zahwa Ayu Ramadhani', 'phone' => '+62891837883283', 'email' => 'ayuramadhanizahwa@gmail.com', 'company_name' => 'PNC', 'position' => 'Mahasiswa'],
            ['name' => 'Bagas Dwi Prasetyo',  'phone' => '+628123456701',   'email' => 'bagas.dwi@gmail.com',       'company_name' => 'PT Sinar Jaya', 'position' => 'Staff Marketing'],
            ['name' => 'Citra Puspa Dewi',    'phone' => '+628123456702',   'email' => 'citra.puspa@gmail.com',      'company_name' => 'CV Makmur',     'position' => 'Owner'],
            ['name' => 'Dimas Aditya Nugraha','phone' => '+628123456703',   'email' => 'dimas.aditya@gmail.com',     'company_name' => 'PT Cipta Karya', 'position' => 'Manager'],
            ['name' => 'Elsa Nur Fadilah',    'phone' => '+628123456704',   'email' => 'elsa.nur@gmail.com',         'company_name' => 'Universitas Y',  'position' => 'Dosen'],
        ];

        $statuses = ['Terjadwal', 'Menunggu', 'Selesai', 'Terjadwal', 'Menunggu'];

        foreach ($guestsData as $i => $g) {
            $date       = Carbon::now()->subDays(4 - $i);
            $guestCode  = 'GST-' . $date->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $visitCode  = 'VST-' . $date->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            $guestId = DB::table('guests')->insertGetId([
                'guest_code'        => $guestCode,
                'name'              => $g['name'],
                'phone'             => $g['phone'],
                'email'             => $g['email'],
                'company_name'      => $g['company_name'],
                'position'          => $g['position'],
                'is_vip'            => $i === 0 ? 1 : 0,
                'address'           => 'Jl. Contoh No. ' . ($i + 1) . ', Yogyakarta',
                'guest_category_id' => $categoryId,
                'photo_path'        => null,
                'created_by'        => $userId,
                'created_at'        => $date,
                'updated_at'        => $date,
            ]);

            $visitId = DB::table('visits')->insertGetId([
                'visit_code'           => $visitCode,
                'guest_id'             => $guestId,
                'branch_id'            => $branchId,
                'purpose_id'           => $purposeId,
                'source_id'            => $sourceId,
                'assigned_to'          => $userId,
                'scheduled_at'         => $date->copy()->setTime(8, 0, 0),
                'notes'                => 'Kunjungan seeder ke-' . ($i + 1),
                'check_in_at'          => $date,
                'meeting_start_at'     => null,
                'check_out_at'         => null,
                'status'               => $statuses[$i],
                'queue_number'         => $i + 1,
                'meeting_result'       => null,
                'potential_level'      => null,
                'next_action'          => null,
                'follow_up_at'         => null,
                'is_converted_to_lead' => 0,
                'created_by'           => $userId,
                'updated_by'           => $userId,
                'created_at'           => $date,
                'updated_at'           => $date,
            ]);

            // Setiap visit dikaitkan ke 1-2 produk secara acak
            $productsForVisit = collect($productIds)->random(min(count($productIds), rand(1, 2)));
            foreach ($productsForVisit as $productId) {
                DB::table('visit_products')->insert([
                    'visit_id'   => $visitId,
                    'product_id' => $productId,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}