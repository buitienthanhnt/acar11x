<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * run cmd: php artisan sync-storage
 */
Artisan::command('sync-storage', function () {
    $result = Process::run('sudo cp -rf /var/www/html/10xreact/storage/app/public/ /var/www/html/acar11x/storage/app/')->throw();
    $this->comment($result->output());
    // dd($result->output());
});
