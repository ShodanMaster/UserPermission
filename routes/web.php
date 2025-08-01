<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login-in', [LoginController::class, 'loginIn'])->name('loginin');

Route::middleware('auth')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('is_permit')->group(function(){

        Route::resource('user', UserController::class);

        Route::resource('category', CategoryController::class);
        Route::resource('product', ProductController::class);

        Route::resource('permission', PermissionController::class);
    });
    Route::post('getPermissions', [PermissionController::class, 'getPermissions'])->name('getpermissions');
    Route::post('users', [UserController::class, 'getUsers']);

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

});

Route::get('/', function () {
    return view('welcome');
});
