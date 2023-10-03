<?php

use App\Http\Controllers\AddUserController;
use App\Http\Controllers\GetUsersController;
use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
    'middleware' => 'auth:sanctum'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])
        ->withoutMiddleware('auth:sanctum');
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function ($router) {
    Route::get('/me', [AuthController::class, 'me']);
});
