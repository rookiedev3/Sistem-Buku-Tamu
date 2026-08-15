<?php

namespace App\Console\Commands;

use App\Models\follow_ups;
use App\Models\notifications;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FollowUpDueNotification extends Command
{
    /**
     * Nama command artisan yang akan dipanggil
     */
    protected $signature = 'notify:followup-due';

    /**
     * Deskripsi command
     */
    protected $description = 'Kirim notifikasi pengingat follow-up yang jatuh tempo hari ini kepada PIC terkait';

    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        $fiveMinutesAgo = Carbon::now()->subMinutes(5);

        // 1. Cari follow-up yang jatuh tempo hari ini atau yang sudah lewat (overdue)
        // dan statusnya belum selesai (misal status bukan 'deal' atau 'lost')
        $pendingFollowUps = follow_ups::with(['lead.visit.guest', 'lead.visit.purpose', 'lead.assignedUser'])
            ->whereNotNull('due_at')
            ->whereDate('due_at', '<=', $today)
            ->get();

        if ($pendingFollowUps->isEmpty()) {
            $this->info('Tidak ada jadwal follow-up yang jatuh tempo hari ini.');
            return 0;
        }

        $processedCount = 0;

        foreach ($pendingFollowUps as $fu) {
            $lead = $fu->lead;
            if (!$lead) continue;

            $guest     = $lead->visit->guest ?? null;
            $purpose   = $lead->visit->purpose ?? null;
            $picId     = $lead->assigned_to ?? $fu->created_by; // ID PIC yang bertanggung jawab

            if (!$picId) continue;

            $guestName   = $guest->name ?? '-';
            $kebutuhan   = $purpose->name ?? $lead->visit->notes ?? '-';
            $tindakan    = $fu->action_plan ?? $fu->notes ?? $fu->result ?? 'Melakukan kontak/follow-up lanjutan';

            // Identifier unik untuk cegah spaming notifikasi ganda dalam 5 menit terakhir
            $recentlyNotified = notifications::where('user_id', $picId)
                ->where('type', 'followup_reminder')
                ->where('body', 'like', '%' . $guestName . '%')
                ->where('created_at', '>=', $fiveMinutesAgo)
                ->exists();

            if ($recentlyNotified) {
                continue;
            }

            // Format tanggal tenggat
            $dueDateFormatted = Carbon::parse($fu->due_at)->translatedFormat('d F Y');

            $title   = 'Pengingat Follow-Up Jatuh Tempo!';
            $message = "Jadwal follow-up telah jatuh tempo ({$dueDateFormatted}).\n" .
                "• Nama Tamu: {$guestName}\n" .
                "• Kebutuhan: {$kebutuhan}\n" .
                "• Tindakan Berikutnya: {$tindakan}";

            // 2. Kirim Notifikasi Sistem (Database) ke PIC Terkait
            notifications::send(
                $picId,
                'followup_reminder',
                $title,
                $message
            );

            // 3. Kirim Notifikasi WhatsApp Fonnte ke Nomor HP PIC Terkait
            $assignedPicPhone = $lead->assignedUser->phone ?? null;

            if (! empty($assignedPicPhone)) {
                $token     = config('services.fonnte.token', env('FONNTE_TOKEN'));
                $waMessage = "*{$title}*\n\n" . $message;

                try {
                    Http::withoutVerifying()
                        ->withHeaders([
                            'Authorization' => $token,
                        ])->post('https://api.fonnte.com/send', [
                            'target'  => $assignedPicPhone, // 🟢 Mengirim ke nomor telepon PIC terkait
                            'message' => $waMessage,
                        ]);
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim WA Follow-Up ke PIC ({$assignedPicPhone}): " . $e->getMessage());
                }
            }

            $processedCount++;
        }

        $this->info("Berhasil mengirim {$processedCount} notifikasi pengingat follow-up.");
        return 0;
    }
}