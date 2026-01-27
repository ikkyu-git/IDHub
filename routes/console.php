<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the pruning command to run daily
use Illuminate\Support\Facades\Schedule;

Schedule::command('sso:prune')->daily();

