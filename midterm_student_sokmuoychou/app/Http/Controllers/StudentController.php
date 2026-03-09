<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    // PART C: QUESTION 2
    public function index() {
        // PART C: QUESTION 3
        $student = [
            'name' => 'Sok Muoychou',
            'age' => 22,
            'email' => 'chou@example.come',
        ];
        return view('student', ['student' => $student]);
    }
}
