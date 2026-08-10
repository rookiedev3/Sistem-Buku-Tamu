<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom ini dipakai untuk membedakan:
     * - activated_at = null            -> akun BELUM PERNAH disetujui admin (pending baru daftar)
     * - activated_at terisi, is_active=false -> akun PERNAH aktif lalu dinonaktifkan admin
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('activated_at')->nullable()->after('is_active');
        });

        // Untuk data user yang sudah ada dan sudah is_active=1 sebelum migration ini,
        // anggap mereka sudah pernah "diaktifkan" sejak dulu supaya tidak salah pesan.
        \DB::table('users')
            ->where('is_active', 1)
            ->whereNull('activated_at')
            ->update(['activated_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('activated_at');
        });
    }
};