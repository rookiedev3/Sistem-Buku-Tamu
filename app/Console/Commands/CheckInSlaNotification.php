<?php

namespace App\Console\Commands;

use App\Models\notifications;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            ->whereNotNull('assigned_to') // Hanya ambil yang sudah ada PIC
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
            $guestName   = $guest->name ?? '-';
            $companyName = $guest->company_name ?? '-';
            $visitCode   = $visit->visit_code ?? '-';
            $checkInTime = Carbon::parse($visit->check_in_at)->format('H:i');

            $title   = 'Peringatan SLA Pelayanan!';
            $message = "Tamu telah menunggu Anda selama {$formattedDuration}.\n"
                . "• Kode: {$visitCode}\n"
                . "• Nama: {$guestName}\n"
                . "• Instansi: {$companyName}\n"
                . "• Tujuan: {$purposeName}\n"
                . "• Cabang: {$branchName}\n"
                . "• Waktu Check-in: {$checkInTime} WIB";

            // 1. Kirim Notifikasi Sistem (Database) ke PIC (assigned_to)
            notifications::send(
                $visit->assigned_to,
                'sla_warning',
                $title,
                $message
            );

            // 2. Kirim Notifikasi WhatsApp Fonnte ke Nomor HP PIC
            $assignedPicPhone = $visit->assignedUser->phone ?? null;

            if (! empty($assignedPicPhone)) {
                $token     = config('services.fonnte.token', env('FONNTE_TOKEN'));
                $waMessage = "*{$title}*\n\n" . $message;

                try {
                    Http::withoutVerifying()
                        ->withHeaders([
                            'Authorization' => $token,
                        ])->post('https://api.fonnte.com/send', [
                            'target'  => $assignedPicPhone,
                            'message' => $waMessage,
                        ]);
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim WA SLA ke PIC ({$assignedPicPhone}): " . $e->getMessage());
                }
            }

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
        $hours   = floor($totalMinutes / 60);
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