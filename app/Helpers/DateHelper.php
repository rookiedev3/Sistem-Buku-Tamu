<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    protected static array $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    protected static array $bulanSingkat = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
    ];

    protected static array $hari = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
    ];

    /**
     * Format tanggal lengkap: "07 Agustus 2026"
     */
    public static function tanggal($date): string
    {
        if (!$date) return '-';
        $c = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $c->format('d') . ' ' . self::$bulan[(int) $c->format('n')] . ' ' . $c->format('Y');
    }

    /**
     * Format tanggal singkat: "07 Agu 2026"
     */
    public static function tanggalSingkat($date): string
    {
        if (!$date) return '-';
        $c = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $c->format('d') . ' ' . self::$bulanSingkat[(int) $c->format('n')] . ' ' . $c->format('Y');
    }

    /**
     * Format tanggal + jam: "07 Agustus 2026, 14:30"
     */
    public static function tanggalJam($date): string
    {
        if (!$date) return '-';
        $c = $date instanceof Carbon ? $date : Carbon::parse($date);
        return self::tanggal($c) . ', ' . $c->format('H:i');
    }

    /**
     * Format dengan nama hari: "Jumat, 07 Agustus 2026"
     */
    public static function tanggalHari($date): string
    {
        if (!$date) return '-';
        $c = $date instanceof Carbon ? $date : Carbon::parse($date);
        $namaHari = self::$hari[$c->format('l')] ?? $c->format('l');
        return $namaHari . ', ' . self::tanggal($c);
    }
}