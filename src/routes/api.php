<?php

use App\Http\Controllers\AddUserController;
use App\Http\Controllers\GetUsersController;
use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::group([
    'prefix' => 'auth',
    'middleware' => 'auth:sanctum'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])
        ->withoutMiddleware('auth:sanctum');
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Budget routes
Route::group([
    'prefix' => 'budgets',
    'middleware' => 'auth:sanctum'
], function ($router) {
    Route::get('/', [BudgetController::class, 'budgets']);
    Route::get('/{budget}', [BudgetController::class, 'budget']);
    Route::put('/{budget}', [BudgetController::class, 'update']);
    Route::delete('/{budget}', [BudgetController::class, 'delete']);
    Route::post('/', [BudgetController::class, 'create']);
});

Route::middleware('auth:sanctum')->group(function ($router) {
    Route::get('/me', [AuthController::class, 'me']);
});
