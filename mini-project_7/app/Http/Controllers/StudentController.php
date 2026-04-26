<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index() {
        $students = [
            ["name" => "Sok Muoychou", "age" => 22, "sex" => "F"],
            ["name" => "So Dane", "age" => 21, "sex" => "F"],
            ["name" => "Lee Manet", "age" => 20, "sex" => "M"],
            ["name" => "Haru Maru", "age" => 19, "sex" => "M"],
            ["name" => "Alice Kim", "age" => 23, "sex" => "F"],
        ];
 
        return view('students', ['students' => $students]);
    }
}
?>