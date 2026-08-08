<?php

namespace App\Console\Commands;

use App\Models\notifications;
use App\Models\users;
use App\Models\visits;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckInSlaNotification extends Command
{
    /**
     * Nama command artisan yang akan dipanggil
     */
    protected $signature = 'notify:checkin-sla';

    /**
     * Deskripsi command
     */
    protected $description = 'Kirim notifikasi peringatan SLA jika tamu sudah check-in lebih dari 10 menit dan belum dilayani';

    public function handle()
    {
        // Batas waktu tunggu SLA (10 menit lalu)
        $tenMinutesAgo = Carbon::now()->subMinutes(10);

        // Batas waktu jeda pengiriman ulang notifikasi (5 menit lalu)
        $fiveMinutesAgo = Carbon::now()->subMinutes(5);

        // 1. Cari kunjungan yang sudah check-in >= 10 menit dan statusnya masih menunggu
        $delayedVisits = visits::with(['guest', 'purpose', 'branch'])
            ->whereNotNull('check_in_at')
            ->where('check_in_at', '<=', $tenMinutesAgo)
            ->whereIn('status', ['Terjadwal', 'Menunggu', 'waiting', 'pending'])
            ->get();

        $adminUsers = users::where('role', 'admin')->get();

        foreach ($delayedVisits as $visit) {
            $guest = $visit->guest;

            // 🟢 CHEK: Apakah notifikasi SLA tamu ini SUDAH PERNAH dikirim dalam 5 menit terakhir?
            $recentlyNotified = notifications::where('type', 'sla_warning')
                ->where('body', 'like', '%' . ($guest->name ?? '') . '%')
                ->where('created_at', '>=', $fiveMinutesAgo) // 👈 Kunci jeda 5 menit
                ->exists();

            // Jika sudah pernah dikirim dalam 5 menit terakhir, lewati ke tamu berikutnya
            if ($recentlyNotified) {
                continue;
            }

            $purposeType = $visit->purpose;
            $branch = $visit->branch;

            // Hitung total menit keterlambatan (dibulatkan ke bawah)
            $waitingMinutes = (int) floor(Carbon::parse($visit->check_in_at)->diffInSeconds(now()) / 60);

            // Kirim Notifikasi ke Seluruh Admin
            foreach ($adminUsers as $admin) {
                notifications::send(
                    $admin->id,
                    'sla_warning',
                    '⚠️ Peringatan SLA Pelayanan!',
                    'Tamu telah menunggu selama ' . $waitingMinutes . ' menit.' .
                        "\n" . 'Nama: ' . ($guest->name ?? '-') .
                        "\n" . 'Instansi: ' . ($guest->company_name ?? '-') .
                        "\n" . 'Tujuan: ' . ($purposeType->name ?? '-') .
                        "\n" . 'Cabang: ' . ($branch->name ?? '-') .
                        "\n" . 'Waktu Check-in: ' . Carbon::parse($visit->check_in_at)->format('H:i') . ' WIB'
                );
            }
        }

        return 0;
    }
}
