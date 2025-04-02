<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;

//Rutas publicas
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//Rutas privadas
Route::middleware([IsUserAuth::class])->group(function(){
    Route::controller(AuthController::class)->group(function(){
        Route::post('logout', 'logout');
        Route::get('me', 'getUser');
    });

    Route::middleware([IsAdmin::class])->group(function(){
        Route::controller(ProductController::class)->group(function(){
            Route::post('products', 'addProduct');
            Route::get('products/{id}', 'getProduct');
            Route::put('products/{id}', 'updateProduct');
            Route::delete('products/{id}', 'deleteProduct');
        });
    });
});