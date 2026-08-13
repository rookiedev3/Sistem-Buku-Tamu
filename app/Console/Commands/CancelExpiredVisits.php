<?php

namespace App\Console\Commands;

use App\Models\visit_status_logs;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredVisits extends Command
{
    /**
     * Nama dan deskripsi perintah Artisan
     */
    protected $signature = 'visits:cancel-expired';
    protected $description = 'Membatalkan otomatis kunjungan yang telah melewati tanggal jadwal dan belum selesai.';

    public function handle()
    {
        $this->info('Mulai mengecek kunjungan yang kadaluarsa...');

        // 1. Cari data kunjungan yang scheduled_at nya sudah lewat hari ini
        //    dan statusnya bukan 'Selesai' serta belum 'Dibatalkan'
        $expiredVisits = visits::whereDate('scheduled_at', '<', Carbon::today())
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->get();

        if ($expiredVisits->isEmpty()) {
            $this->info('Tidak ada kunjungan kadaluarsa yang perlu dibatalkan.');
            return Command::SUCCESS;
        }

        $count = 0;

        foreach ($expiredVisits as $visit) {
            try {
                DB::transaction(function () use ($visit) {
                    $oldStatus = $visit->status;

                    // Update status kunjungan jadi Dibatalkan
                    $visit->update([
                        'status' => 'Dibatalkan',
                    ]);

                    // Catat ke riwayat log status (jika ada)
                    visit_status_logs::create([
                        'visit_id'   => $visit->id,
                        'old_status' => $oldStatus,
                        'new_status' => 'Dibatalkan',
                        'changed_by' => null, // null menandakan diubah oleh sistem/cron
                        'changed_at' => now(),
                    ]);
                });

                $count++;
            } catch (\Exception $e) {
                Log::error("Gagal membatalkan visit ID {$visit->id}: " . $e->getMessage());
            }
        }

        $this->info("Berhasil membatalkan {$count} kunjungan.");
        return Command::SUCCESS;
    }
}