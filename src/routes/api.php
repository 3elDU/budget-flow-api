<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OperationController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::controller(CategoryController::class)
        ->prefix('categories')
        ->group(function () {
            Route::get('/', 'categories');
            Route::post('/', 'create');
            Route::get('{category}/operations', 'operations');
            Route::put('{category}', 'update');
            Route::delete('{category}', 'delete');
        });

    Route::group(['prefix' => 'budgets'], function () {
        Route::controller(BudgetController::class)
            ->group(function () {
                Route::get('/', 'budgets');
                Route::get('{budget}', 'budget');
                Route::get('{budget}/analytics', 'analytics');
                Route::get('{budget}/amount', 'amount');
                Route::put('{budget}', 'update');
                Route::delete('{budget}', 'delete');
                Route::post('/', 'create');
            });
        Route::post('{budget}/operations', [OperationController::class, 'create']);
    });
    Route::get('/analytics', [BudgetController::class, 'analyticsAll']);

    Route::controller(OperationController::class)
        ->prefix('operations')
        ->group(function () {
            Route::get('/', 'index');
            Route::get('{operation}', 'get');
            Route::put('{operation}', 'update');
            Route::delete('{operation}', 'delete');
        });

    Route::group(['prefix' => 'users'], function () {
        Route::get('me', [AuthController::class, 'me']);

        Route::get('me/settings', [UserController::class, 'settings']);
        Route::put('me/settings', [UserController::class, 'updateSettings']);
    });
});
