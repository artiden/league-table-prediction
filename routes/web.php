<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('league');
});

Route::get('/history', [\App\Http\Controllers\Api\FictureController::class, 'listWeekHistory'])
    ->name('history');
