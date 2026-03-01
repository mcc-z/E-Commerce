<?php

use Illuminate\Support\Facades\Route;

$products = [
    ['name' => 'Mask', 'price' => '1000', 'stock' => '0'],
    ['name' => 'Lollipop', 'price' => '50', 'stock' => '123'],
    ['name' => 'Perfume', 'price' => '4500', 'stock' => '4'],
    ['name' => 'Sunscreen', 'price' => '270', 'stock' => '0'],
];

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', function() use ($products) {
    return view('products', ['products' => $products]);
});