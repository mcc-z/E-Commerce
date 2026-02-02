<?php

use Illuminate\Support\Facades\Route;

$students = [
    ['id' => 1, 'name' => 'Chou', 'age' => 22, 'subject' => 'Computer Science'],
    ['id' => 2, 'name' => 'Dane', 'age' => 20, 'subject' => 'Business Economics'],
    ['id' => 3, 'name' => 'Reach', 'age' => 24, 'subject' => 'International Relations'],
];

Route::get('/', function () {
    return view('welcome');
});

Route::get('/students', function() use ($students){
    return view('student_list', ['students' => $students]);
})->name('students.list');

Route::get('/student/{id}', function($id) use ($students){
    $student = collect($students)->firstWhere('id', $id); // collect data from $students array where id matches $id
    if(!$student) {                       // if no student is found
        abort(404, 'Student not found.'); // sends a laravel abort - stops executing the route, and shows a 404 message (meaning Not Found)
    }
    return view('student', ['student' => $student]);
})->name('students.show');