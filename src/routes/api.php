<?php

use App\Http\Controllers\AddUserController;
use App\Http\Controllers\GetUsersController;
use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IncomeController;
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

// Category routes
Route::controller(CategoryController::class)
    ->middleware('auth:sanctum')
    ->prefix('categories')
    ->group(function () {
        Route::get('/', 'categories');
        Route::post('/', 'create');
        Route::get('/{category}/incomes', 'incomes');
        Route::get('/{category}/expenses', 'expenses');
        Route::put('/{category}', 'update');
        Route::delete('/{category}', 'delete');
    });

// Budget routes
Route::controller(BudgetController::class)
    ->middleware('auth:sanctum')
    ->prefix('budgets')
    ->group(function () {
        Route::get('/', 'budgets');
        Route::get('/{budget}', 'budget');
        Route::put('/{budget}', 'update');
        Route::delete('/{budget}', 'delete');
        Route::post('/', 'create');


        // Income routes
        Route::controller(IncomeController::class)->group(function () {
            Route::get('/{budget}/incomes', 'incomes');
            Route::get('/{budget}/incomes/{income}', 'income');
            Route::post('/{budget}/incomes', 'create');
            Route::put('/{budget}/incomes/{income}', 'update');
            Route::delete('/{budget}/incomes/{income}', 'delete');
        });
    });


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});
