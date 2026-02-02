<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = [
            ['name' => 'Chou', 'role' => 'Admin'],
            ['name' => 'Henry', 'role' => 'User'],
            ['name' => 'Momo', 'role' => 'User'],
        ];
        return view('admin.users', ['users' => $users]);
    }
}
