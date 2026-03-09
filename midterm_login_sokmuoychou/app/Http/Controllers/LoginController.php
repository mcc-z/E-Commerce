<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login_form() {
        return view('login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user', $user->id);
            return redirect('/dashboard');
        }

        return back()->with('error', 'Invalid email or password');
    }

    public function dashboard() {
        if (!Session::has('user')) {
            return redirect('/login')->with('error', 'Please login first!');
        }
        $user = User::find(Session::get('user'));
        return view('dashboard', compact('user'));
    }

    public function logout() {
        Session::forget('user');
        return redirect('/login');
    }

    public function signup_form() {
        return view('signup');
    }

    public function signup(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login')->with('success', 'Account created successfully!');
    }
}