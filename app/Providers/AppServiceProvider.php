<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force GTM+8 / Manila time regardless of server timezone
        date_default_timezone_set('Asia/Manila');
        Carbon::setLocale('en');
    }
}
