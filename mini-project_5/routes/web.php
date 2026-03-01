<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Http\Request;

Route::get('/', function() {
    return view('welcome');
})->name('home.page');

Route::get('/login', function(Request $request) {
    session(['loggedIn'=>true]);
    return redirect()->route('home.page');
})->name('login');

Route::get('/logout', function(Request $request) {
    session(['loggedIn'=>false]);
    return redirect()->route('home.page');
})->name('logout');

Route::prefix('admin')
    ->middleware('auth.login')
    ->group(function(){
        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users');
        Route::get('/settings', [SettingController::class, 'index'])
            ->name('admin.settings');
    });