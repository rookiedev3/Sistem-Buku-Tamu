@extends('layouts.guest')

@section('content')
<style>
    /* Styling Responsif untuk Halaman Sukses */
    @media (max-width: 640px) {
        .success-card-wrapper {
            padding: 20px !important;
        }
        .success-card {
            padding: 30px 20px !important;
            border-radius: 20px !important;
        }
        .success-title {
            font-size: 20px !important;
        }
        .success-schedule {
            font-size: 20px !important;
        }
    }
</style>

<div class="success-card-wrapper" style="width: 100vw; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px; box-sizing: border-box; margin: -24px; background-color: #f7faf8; position: relative; overflow-x: hidden;">

    <div class="success-card" style="width: 100%; max-width: 600px; background: #013220; border-radius: 28px; box-shadow: 0 24px 60px rgba(1, 50, 32, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); padding: 50px; text-align: center; box-sizing: border-box;">

        <div style="width: 70px; height: 70px; background: #C7AB6B; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            ✓
        </div>

        <h1 class="success-title" style="font-size: 24px; font-weight: 800; color: #ffffff; margin: 0 0 8px 0;">Check-In Berhasil!</h1>
        <p style="font-size: 14px; color: rgba(255, 255, 255, 0.7); margin: 0 0 30px 0;">Terima kasih telah mengisi buku tamu.</p>

        <div style="background: rgba(255, 255, 255, 0.05); border: 2px dashed rgba(199, 171, 107, 0.5); border-radius: 20px; padding: 24px; margin-bottom: 30px; box-sizing: border-box;">
            <span style="font-size: 12px; font-weight: 700; color: #C7AB6B; text-transform: uppercase; letter-spacing: 1px;">Jadwal Pertemuan Anda</span>
            <div class="success-schedule" style="font-size: 26px; font-weight: 900; color: #ffffff; margin-top: 8px; word-break: break-word;">
                {{ $visit->scheduled_at ? \Carbon\Carbon::parse($visit->scheduled_at)->format('d/m/Y - H:i') . ' WIB' : '-' }}
            </div>
        </div>

        <a href="/check-in/step-1" style="display: block; width: 100%; background: #C7AB6B; color: #013220; padding: 14px; border-radius: 12px; font-size: 14px; font-weight: 800; text-decoration: none; box-sizing: border-box; box-shadow: 0 4px 15px rgba(199, 171, 107, 0.3); transition: opacity 0.2s ease;">
            Selesai / Check-In Baru
        </a>

    </div>

</div>
@endsection