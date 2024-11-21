<?php


use Illuminate\Support\Facades\Route;


Route::middleware(['api'])
    ->as('api.')
    ->group(function () {
        Route::prefix('1')->as('v1.')->group(__DIR__ . '/v1.php');
    });
