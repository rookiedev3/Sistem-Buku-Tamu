<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom id di paling depan, isi otomatis utk baris lama, jadikan primary key
        DB::statement('ALTER TABLE follow_ups ADD id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY FIRST');
    }

    public function down(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
};