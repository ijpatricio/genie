<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:new filament-issue')->at('00:05');
Schedule::command('app:package filament-issue')->at('00:10');

Schedule::command('app:new filament-issue')->at('14:30');
Schedule::command('app:package filament-issue')->at('14:35');
