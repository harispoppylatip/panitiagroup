<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:refreshtoken')->dailyAt('00:00');
Schedule::command('app:updatenominalmingguan')->mondays()->at('00:00');
// foto + video baru dari Drive disiapkan duluan (MOV → MP4), supaya pengunjung tidak menunggu konversi
Schedule::command('galeri:sync')->everyTenMinutes()->withoutOverlapping(120)->runInBackground();

// listener berhenti sendiri setelah 55 detik, jadi tidak menumpuk dan tidak menahan jadwal lain
Schedule::command('mqtt:listen --seconds=55')->everyMinute()->withoutOverlapping(2)->runInBackground();
