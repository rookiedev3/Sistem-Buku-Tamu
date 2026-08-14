<?php

namespace App\Console\Commands;

use App\Models\notifications;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckInSlaNotification extends Command
{
    /**
     * Nama command artisan yang akan dipanggil
     */
    protected $signature = 'notify:checkin-sla';

    /**
     * Deskripsi command
     */
    protected $description = 'Kirim notifikasi peringatan SLA ke PIC jika tamu sudah check-in lebih dari 10 menit dan belum dilayani';

    public function handle()
    {
        // Batas waktu tunggu SLA (10 menit lalu)
        $tenMinutesAgo = Carbon::now()->subMinutes(10);

        // Batas waktu jeda pengiriman ulang notifikasi (5 menit lalu)
        $fiveMinutesAgo = Carbon::now()->subMinutes(5);

        // 1. Cari kunjungan yang sudah check-in >= 10 menit, status masih menunggu, dan MEMILIKI PIC (assigned_to)
        $delayedVisits = visits::with(['guest', 'purpose', 'branch', 'assignedUser'])
            ->whereNotNull('check_in_at')
            ->whereNotNull('assigned_to') // 🟢 Hanya ambil yang sudah ada PIC
            ->where('check_in_at', '<=', $tenMinutesAgo)
            ->whereIn('status', ['Terjadwal', 'Menunggu', 'waiting', 'pending', 'Check-in'])
            ->get();

        if ($delayedVisits->isEmpty()) {
            $this->info('Tidak ada tamu dengan PIC yang melewati batas SLA 10 menit.');
            return 0;
        }

        $processedCount = 0;

        foreach ($delayedVisits as $visit) {
            $guest = $visit->guest;
            $visitIdentifier = $visit->visit_code ?? $guest->name ?? '';

            // Cek apakah notifikasi SLA tamu ini SUDAH PERNAH dikirim dalam 5 menit terakhir
            $recentlyNotified = notifications::where('type', 'sla_warning')
                ->where('body', 'like', '%' . $visitIdentifier . '%')
                ->where('created_at', '>=', $fiveMinutesAgo)
                ->exists();

            if ($recentlyNotified) {
                continue;
            }

            // Hitung total menit keterlambatan
            $totalMinutes = (int) Carbon::parse($visit->check_in_at)->diffInMinutes(now());

            // Ubah durasi menjadi format Jam & Menit
            $formattedDuration = $this->formatDuration($totalMinutes);

            $purposeName = $visit->purpose->name ?? '-';
            $branchName  = $visit->branch->name ?? '-';

            // 🟢 Kirim Notifikasi HANYA ke PIC Terpilih (assigned_to)
            notifications::send(
                $visit->assigned_to,
                'sla_warning',
                '⚠️ Peringatan SLA Pelayanan!',
                'Tamu telah menunggu Anda selama ' . $formattedDuration . '.' .
                    "\n" . 'Kode: ' . ($visit->visit_code ?? '-') .
                    "\n" . 'Nama: ' . ($guest->name ?? '-') .
                    "\n" . 'Instansi: ' . ($guest->company_name ?? '-') .
                    "\n" . 'Tujuan: ' . $purposeName .
                    "\n" . 'Cabang: ' . $branchName .
                    "\n" . 'Waktu Check-in: ' . Carbon::parse($visit->check_in_at)->format('H:i') . ' WIB'
            );

            $token = env('FONNTE_TOKEN'); // Mengambil value token dari env

            $message = "*⚠️ Peringatan SLA Pelayanan!*\n\n"
            . "Tamu telah menunggu Anda selama *" . $formattedDuration . "*.\n\n"
            . "Kode: " . ($visit->visit_code ?? '-') . "\n"
            . "Nama: " . ($guest->name ?? '-') . "\n"
            . "Instansi: " . ($guest->company_name ?? '-') . "\n"
            . "Tujuan: " . $purposeName . "\n"
            . "Cabang: " . $branchName . "\n"
            . "Waktu Check-in: " . Carbon::parse($visit->check_in_at)->format('H:i') . " WIB";

            Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target'  => '085926276649', // 💡 Ganti dengan variabel nomor HP penerima (contoh: $admin->phone atau $admin->nohp)
                    'message' => $message,
                ]);

            $processedCount++;
        }

        $this->info("Berhasil mengirim notifikasi SLA kepada PIC untuk {$processedCount} kunjungan.");
        return 0;
    }

    /**
     * Helper untuk memformat total menit menjadi string Jam & Menit
     */
    private function formatDuration(int $totalMinutes): string
    {
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} jam {$minutes} menit";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        } else {
            return "{$minutes} menit";
        }
    }
}