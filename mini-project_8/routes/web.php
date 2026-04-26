<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/blogs', function() {
    $blogs = [
        ['id' => 1, 'title' => 'A Case Study on Cats', 'desc' => 'Cats are very fluffy, soft, and cute! Their purr has a very soothing effect.', 'subject' => 'Art'],
        ['id' => 2, 'title' => 'The Future of Techonology', 'desc' => 'Technology is appearing everywhere in our lives. The Internet is now indispensable.', 'subject' => 'Technology'],
        ['id' => 3, 'title' => 'Tips for Beginner Gardeners', 'desc' => 'Gardening is a delicate task. You must ensure soil quality, sufficient sunlight, and adequate water for each plant.', 'subject' => 'Lifestyle'],
        ['id' => 4, 'title' => 'The Yummiest Egg Dishes', 'desc' => 'Egg dishes are prevalent all around the world. Fried egg is by far the most versatile dish. You can put anything in a fried egg, or a fried egg in anything!', 'subject' => 'Food'],
    ];
    return view('blogs', ['blogs' => $blogs]);
});

Route::get('/blog/{id}', function($id) {
    $blogs = [
        ['id' => 1, 'title' => 'A Case Study on Cats', 'desc' => 'Cats are very fluffy, soft, and cute! Their purr has a very soothing effect.', 'subject' => 'Art'],
        ['id' => 2, 'title' => 'The Future of Techonology', 'desc' => 'Technology is appearing everywhere in our lives. The Internet is now indispensable.', 'subject' => 'Technology'],
        ['id' => 3, 'title' => 'Tips for Beginner Gardeners', 'desc' => 'Gardening is a delicate task. You must ensure soil quality, sufficient sunlight, and adequate water for each plant.', 'subject' => 'Lifestyle'],
        ['id' => 4, 'title' => 'The Yummiest Egg Dishes', 'desc' => 'Egg dishes are prevalent all around the world. Fried egg is by far the most versatile dish. You can put anything in a fried egg, or a fried egg in anything!', 'subject' => 'Food'],
    ];
    foreach ($blogs as $blog) {
        if ($blog['id'] == $id) {
            return view('blog', ['blog' => $blog]);
        }
    }
});