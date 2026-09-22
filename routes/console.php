<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('gam3a:status', function () {
    $this->info('Gam3a backend is ready.');
});
