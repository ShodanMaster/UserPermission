<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login-in', [LoginController::class, 'loginIn'])->name('loginin');
Route::get('/', function () {
    return view('welcome');
});
