<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\NYT\BestSellersController;

Route::prefix('nyt')->as('nyt.')->group(function() {
   Route::get('best-sellers', BestSellersController::class)->name('best-sellers.history');
});
