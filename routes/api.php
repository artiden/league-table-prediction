<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->group(function () {
        Route::get('/health', function (){
            return response()
                ->json();
        });

        Route::post('/generate-teams', [\App\Http\Controllers\Api\FictureController::class, 'generateTeams']);
        Route::post('/reset', [\App\Http\Controllers\Api\FictureController::class, 'reset']);
        Route::post('/simulate', [\App\Http\Controllers\Api\FictureController::class, 'simulate']);
    });
