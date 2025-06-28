<?php

use App\Http\Controllers\Api\V1\BorrowerController;
use App\Http\Controllers\Api\V1\RecordBorrowTransactionController;
use App\Http\Controllers\Api\V1\StoreStatsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {
    Route::post('transaction', RecordBorrowTransactionController::class);
    Route::get('store-stats', StoreStatsController::class);
    Route::get('borrowers', [BorrowerController::class, 'index']);
    Route::get("borrowers/{api}", [BorrowerController::class, 'show']);
});


Route::group(['prefix' => 'v1'], function () {
    //Landing page
    Route::get('/', function () {
        return ['Laravel' => app()->version()];
    });

    //Authentication
    require __DIR__ . '/auth.php';
});
