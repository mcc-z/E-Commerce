<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// practice 3
Route::get('/profile', function() {
    return view('profile', ['name'=>'Chou', 'age'=>15]);
});

// practice 4
Route::get('/students', function() {
    $students = ['Chou', 'Rithy', 'Vanda', 'Rothana'];
    return view('students', compact('students'));
});