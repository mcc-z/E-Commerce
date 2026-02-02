<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/{name}', function($name) {
    return view('greet', ['name' => $name]);
})->name('user.greet');