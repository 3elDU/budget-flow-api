<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OperationController;
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
        Route::get('{category}/operations', 'operations');
        Route::put('{category}', 'update');
        Route::delete('{category}', 'delete');
    });

Route::middleware('auth:sanctum')
    ->group(function () {
        // Budget routes
        Route::controller(BudgetController::class)
            ->prefix('budgets')
            ->group(function () {
                Route::get('/', 'budgets');
                Route::get('{budget}', 'budget');
                Route::get('{budget}/analytics', 'analytics');
                Route::put('{budget}', 'update');
                Route::delete('{budget}', 'delete');
                Route::post('/', 'create');
            });

        // Operation routes
        Route::controller(OperationController::class)
            ->prefix('operations')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('{operation}', 'get');
                Route::put('{operation}', 'update');
                Route::delete('{operation}', 'delete');
            });
        Route::post('/budgets/{budget}/operations', [OperationController::class, 'create']);
    });

// Budget routes
Route::controller(BudgetController::class)
    ->middleware('auth:sanctum')
    ->prefix('budgets')
    ->group(function () {
    });


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});
