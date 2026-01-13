<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/migrate', function () {
    try {
        Artisan::call('migrate:fresh --seed --force');
        return 'Migration and Seeding Completed Successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
