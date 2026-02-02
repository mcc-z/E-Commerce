<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', function() {
    return view('welcome');
})->name('home.page');

Route::prefix('admin')
    ->middleware('auth')
    ->group(function(){
        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users');
        Route::get('/settings', [SettingController::class, 'index'])
            ->name('admin.settings');
    });