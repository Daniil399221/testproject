<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserRoleController;
use Illuminate\Support\Facades\Route;


//          * API *
//         User CRUD

Route::prefix('/users')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store');
        Route::get('/show/{user}', 'show');
        Route::put('/update/{user}', 'update');
        Route::delete('/destroy/{user}', 'destroy');
    });

//          * API *
//         User Roles Management

Route::prefix('/users/{user}/roles')
    ->controller(UserRoleController::class)
    ->group(function () {
        Route::post('/assign', 'assignRole');
        Route::delete('/revoke', 'revokeRole');
});



//          * API *
//         User Roles List

Route::get('/roles', [UserRoleController::class, 'rolesList']);

//          * API *
//         Tasks CRUD

Route::prefix('/tasks')
    ->controller(TaskController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/store', 'store')->middleware('throttle:2,1');
        Route::get('/show/{task}', 'show');
        Route::put('/update/{task}', 'update');
        Route::delete('/destroy/{task}', 'destroy');
        Route::post('/{task}/assignees', 'assign');
        Route::delete('/{task}/unassign', 'unassign');
    });
