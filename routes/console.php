<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-close daily sales at midnight (Asia/Manila)
Schedule::command('app:close-daily-sales')->dailyAt('00:00')->timezone('Asia/Manila');
