<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Berhasil Diubah | IT Solution</title>

    {{-- Kalau project sudah pakai Vite/Mix untuk Tailwind, ganti baris di bawah ini
         dengan @vite(['resources/css/app.css']) dan hapus tag <script> CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">

    <div class="flex min-h-screen">

        {{-- ================= PANEL KIRI: BRANDING HIJAU ================= --}}
        <div class="hidden md:flex md:w-[45%] relative overflow-hidden"
             style="background: linear-gradient(135deg, #01281b 0%, #013220 40%, #006B3F 100%);">

            {{-- Motif lingkaran dekoratif --}}
            <div class="absolute -top-10 -left-10 w-72 h-72 rounded-full border border-white/10"></div>
            <div class="absolute top-1/3 -right-24 w-96 h-96 rounded-full border border-white/10"></div>
            <div class="absolute -bottom-24 left-1/4 w-80 h-80 rounded-full border border-white/10"></div>

            <div class="flex flex-col justify-between p-12 relative z-10 w-full">
                <div>
                    <h2 class="text-white text-4xl font-black tracking-tight flex items-center gap-2">
                        <span>IT SOLUTION</span>
                    </h2>
                    <p class="text-white/70 text-sm mt-4 max-w-xs">
                        Sistem Buku Tamu &amp; Registrasi Kunjungan Digital Perusahaan.
                    </p>
                </div>

                <p class="text-white/50 text-xs">
                    &copy; {{ date('Y') }} IT Solution Corp. All rights reserved.
                </p>
            </div>
        </div>

        {{-- ================= PANEL KANAN: KONTEN SUKSES ================= --}}
        <div class="w-full md:w-[55%] flex items-center justify-center bg-white px-6 py-12">
            <div class="w-full max-w-md text-center">

                {{-- Ikon Sukses --}}
                <div class="mx-auto mb-6 flex items-center justify-center w-20 h-20 rounded-full bg-green-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#006B3F]" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-3">
                    Password Berhasil Diubah
                </h1>

                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    Password Anda telah berhasil diperbarui.<br>
                    Silakan kembali ke aplikasi dan login menggunakan password baru Anda.
                </p>

                <p class="text-gray-400 text-xs">
                    Anda bisa menutup tab ini sekarang.
                </p>

            </div>
        </div>

    </div>

</body>
</html>