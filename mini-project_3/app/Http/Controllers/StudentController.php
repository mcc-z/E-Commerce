<?php

// namespace used to specify where the controller is located
// in this case, it's saying StudentController is located in App\Http\Controllers\StudentController
namespace App\Http\Controllers;

// this is used to call the Request class from laravel (it's not being used this time since we alr have manual array)
// the Request class is used to handle HTTP data (form input, query parameters, etc.)
use Illuminate\Http\Request;

// this creates StudentController as an extension of Laravel's og Controller class
// this means it has access to built-in controller features (instead of being a new class on it's own with no features)
class StudentController extends Controller
{
    public function index() // this is the public index() function that let's us view the students page (public, meaning everyone can see it, recall OOP classes)
    {
        $students = [
            ['name' => 'Alice', 'age' => 20],
            ['name' => 'Bob', 'age' => 22],
            ['name' => 'Charlie', 'age' => 19],
        ];

        // open students.blade.php, give it a variable 'students' with values $students
        return view('students', ['students' => $students]);
    }
}
?>