<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/product/{id?}', function($id = 100){
    return view('product', ['id' => $id]);
})->where('id', '[0-9]+'); // [0-9] represents numeric digits 0-9, + is a quantifier
                           // meaning, you can add any combination of numbers from 0-9 (ex: 999, 1024, 58, but NOT things like: abc, 1&dg, 12kg)