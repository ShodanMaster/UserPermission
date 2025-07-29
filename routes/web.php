<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login-in', [LoginController::class, 'loginIn'])->name('loginin');

Route::middleware('auth')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

});

Route::get('/', function () {
    return view('welcome');
});
