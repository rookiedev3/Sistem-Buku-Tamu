<?php

if (! function_exists('rupiah')) {
    /**
     * Format angka jadi format ribuan ala Indonesia, contoh: 7000000 -> "7.000.000".
     * Set $withPrefix = true kalau mau otomatis ditambah "Rp " di depan.
     */
    function rupiah($angka, bool $withPrefix = false): string
    {
        if ($angka === null || $angka === '') {
            return $withPrefix ? 'Rp 0' : '0';
        }

        $formatted = number_format((float) $angka, 0, ',', '.');

        return $withPrefix ? 'Rp ' . $formatted : $formatted;
    }
}