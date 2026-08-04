<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lead_sources = [
        [
            'name' => 'Google',
        ],
        [
            'name' => 'Instagram',
        ],
        [
            'name' => 'TikTok',
        ],
        [
            'name' => 'Meta Ads',
        ],
        [
            'name' => 'canvassing',
        ],
    ];

        foreach ($lead_sources as $lead_source) {
            DB::table('lead_sources')->updateOrInsert(
                ['name' => $lead_source['name']],
                array_merge($lead_source, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}