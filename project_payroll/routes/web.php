<?php

use App\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payroll', [PayrollController::class, 'index']);
Route::get('/payroll/create', [PayrollController::class, 'create']);
Route::post('/payroll/store', [PayrollController::class, 'store']);
Route::get('/payroll/edit/{id}', [PayrollController::class, 'edit']);
Route::post('/payroll/update/{id}', [PayrollController::class, 'update']);
Route::get('/payroll/delete/{id}', [PayrollController::class, 'destroy']);