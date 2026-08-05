@extends('layouts.guest')

@section('content')
<div style="width: 100vw; min-height: 100vh; background: #f4f7fc; display: flex; align-items: center; justify-content: center; padding: 40px; box-sizing: border-box; margin: -24px;">
    
    <div style="width: 100%; max-width: 600px; background: #ffffff; border-radius: 28px; box-shadow: 0 24px 60px rgba(31,53,97,0.1); border: 1px solid #e8edf5; padding: 50px; text-align: center; box-sizing: border-box;">
        
        <div style="width: 70px; height: 70px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px auto;">
            ✓
        </div>

        <h1 style="font-size: 24px; font-weight: 800; color: #172033; margin: 0 0 8px 0;">Check-In Berhasil!</h1>
        <p style="font-size: 14px; color: #778195; margin: 0 0 30px 0;">Terima kasih telah mengisi buku tamu. Silakan tunjukkan token antrian di bawah ini kepada resepsionis.</p>

        <div style="background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 20px; padding: 24px; margin-bottom: 30px;">
            <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Nomor Token Antrian Anda</span>
            <div style="font-size: 42px; font-weight: 900; color: #1463ff; margin-top: 8px; letter-spacing: 2px;">
                {{ $visit->queue_number }}
            </div>
        </div>

        <a href="/check-in/step-1" style="display: block; width: 100%; background: #1463ff; color: #fff; padding: 14px; border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; box-sizing: border-box; box-shadow: 0 4px 15px rgba(20,99,255,0.25);">
            Selesai / Check-In Baru 🔄
        </a>

    </div>

</div>
@endsection