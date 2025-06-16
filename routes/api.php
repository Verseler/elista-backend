<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {

});


Route::group(['prefix' => 'v1'], function () {
    //Landing page
    Route::get('/', function () {
        return ['Laravel' => app()->version()];
    });

    //Authentication
    require __DIR__ . '/auth.php';
});
