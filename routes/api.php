<?php

declare(strict_types=1);

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class)
        ->only(['index', 'store', 'update', 'destroy', 'show']);
    
    Route::patch('products/{id}/toggle-top', [ProductController::class, 'toggleTop']);
});

