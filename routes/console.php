<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:new filament-issue')->dailyAt('00:05');
Schedule::command('app:package filament-issue')->dailyAt('00:10');

Schedule::command('app:new filament-issue')->dailyAt('14:50');
Schedule::command('app:package filament-issue')->dailyAt('14:55');
