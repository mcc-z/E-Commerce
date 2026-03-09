<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'login_form']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/signup', [LoginController::class, 'signup_form']);
Route::post('/signup', [LoginController::class, 'signup']);
Route::get('/dashboard', [LoginController::class, 'dashboard']);
Route::get('/logout', [LoginController::class, 'logout']);