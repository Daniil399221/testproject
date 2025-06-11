<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/users')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
        Route::get('/show/{user}', 'show');
        Route::put('/update/{user}', 'update');
        Route::delete('/destroy/{user}', 'destroy');
    });
