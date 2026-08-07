<?php

use App\Helpers\DateHelper;

if (!function_exists('tgl')) {
    function tgl($date) {
        return DateHelper::tanggal($date);
    }
}

if (!function_exists('tgl_jam')) {
    function tgl_jam($date) {
        return DateHelper::tanggalJam($date);
    }
}

if (!function_exists('tgl_hari')) {
    function tgl_hari($date) {
        return DateHelper::tanggalHari($date);
    }
}