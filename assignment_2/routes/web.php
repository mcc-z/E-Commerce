<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function(){
    session(['loggedIn' => true]);
    return redirect('/dashboard');
});

Route::get('/logout', function(){
    session(['loggedIn' => false]);
    return redirect('/');
});

Route::get('/dashboard', function(){
    return view('dashboard');
})->middleware('auth.login');