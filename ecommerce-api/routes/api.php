<?php

// use Illuminate\Http\Request;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::put('/{id}', [ProductController::class,'update']);
    Route::delete('/{id}', [ProductController::class,'delete']);
});