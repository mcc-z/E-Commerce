<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // =============================================
    // SHOW LOGIN FORM
    // =============================================
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isAdmin() ? 'admin.dashboard' : 'user.dashboard');
        }
        return view('auth.login');
    }

    // =============================================
    // HANDLE LOGIN
    // =============================================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        if ($user->isBlocked()) {
            return back()->withErrors(['email' => 'Your account has been blocked. Please contact the library.']);
        }

        Auth::login($user, $request->boolean('remember'));

        ActivityLog::log('login', "User logged in", null, $user->id);

        return redirect()->intended(
            $user->isAdmin() ? route('admin.dashboard') : route('user.dashboard')
        );
    }

    // =============================================
    // SHOW REGISTER FORM
    // =============================================
    public function showRegister()
    {
        return view('auth.register');
    }

    // =============================================
    // HANDLE REGISTER
    // =============================================
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'password'  => Hash::make($request->password),
            'member_id' => User::generateMemberId(),
            'role'      => 'user',
            'status'    => 'active',
        ]);

        ActivityLog::log('register', "New member registered", $user, $user->id);

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Welcome to LibraryMS, ' . $user->name . '!');
    }

    // =============================================
    // LOGOUT
    // =============================================
    public function logout(Request $request)
    {
        ActivityLog::log('logout', "User logged out");
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
