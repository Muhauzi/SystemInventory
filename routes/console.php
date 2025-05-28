<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

// Artisan::command('inspire', function () { // create a new command named inspire
//     $this->comment(Inspiring::quote()); // display an inspiring quote   
// })->purpose('Display an inspiring quote')->hourly(); // run the command every hour

// shcedule to send reminder via email
Schedule::command('send:return-reminders')
    ->everyMinute();
Schedule::command('send:denda-reminders')
    ->everyMinute();






