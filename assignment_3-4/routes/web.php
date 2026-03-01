<?php

use Illuminate\Support\Facades\Route;

$users = [
    ['name' => 'Adam', 'role' => 'User'],
    ['name' => 'Eve', 'role' => 'Admin'],
    ['name' => 'Noah', 'role' => 'User'],
    ['name' => 'Jesus', 'role' => 'Admin'],
];

$currentUser = $users[2];

Route::get('/', function () {
    return view('home');
});

Route::get('/about', function() {
    return view('about');
});

// use ($currentUser) tells php to search for $currentUser variable
// from outside the Route::get, but remain inside the web.php
Route::get('/dashboard', function() use ($currentUser) {
    return view('dashboard', ['user' => $currentUser]);
});