<?php

use App\Http\Controllers\DbStudentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// PART C: QUESTION 1
Route::get('/midterm', function() {
    return view('midterm');
});

// PART C: QUESTION 2
Route::get('/student', [StudentController::class, 'index']);
// PART D: QUESTION 2
Route::get('/students', [DbStudentController::class, 'index']);
Route::get('/students/create', [DbStudentController::class, 'create']);
Route::post('/students', [DbStudentController::class, 'store']);