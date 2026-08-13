<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan pengecekan SLA setiap menit
Schedule::command('notify:checkin-sla')->everyMinute();
Schedule::command('notify:followup-due')->dailyAt('08:00');
Schedule::command('visits:cancel-expired')->dailyAt('00:01');