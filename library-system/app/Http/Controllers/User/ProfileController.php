<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $adminStats = [
                'total_books' => Book::count(),
                'members' => User::members()->count(),
                'active_borrows' => Borrow::whereIn('status', ['active', 'overdue'])->count(),
                'reservations' => Reservation::whereIn('status', ['pending', 'ready'])->count(),
            ];

            $recentActivity = ActivityLog::with('user')
                ->latest()
                ->limit(8)
                ->get();

            $recentMembers = User::members()
                ->latest()
                ->limit(5)
                ->get();

            $recentBooks = Book::with('category')
                ->latest()
                ->limit(5)
                ->get();

            return view('user.profile', compact(
                'user',
                'adminStats',
                'recentActivity',
                'recentMembers',
                'recentBooks'
            ));
        }

        $borrowHistory = $user->borrows()->with('book')->orderByDesc('created_at')->paginate(10);

        return view('user.profile', compact('user', 'borrowHistory'));
    }

    public function edit()
    {
        return view('user.profile-edit', ['user' => Auth::user()]);
    }

    public function avatar(string $filename): StreamedResponse
    {
        $path = 'avatars/' . basename($filename);
        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path, headers: [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'avatar'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->update($request->only('name', 'phone', 'address'));
        if (isset($path)) $user->save();

        ActivityLog::log('update_profile', "User updated their profile");

        return back()->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        ActivityLog::log('change_password', "User changed their password");

        return back()->with('success', 'Password changed successfully!');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate(['password' => 'required']);
        $user = Auth::user();

        if ($user->isAdmin()) {
            return back()->withErrors(['password' => 'Admin accounts cannot be deleted from the profile page.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.']);
        }

        if ($user->activeBorrows()->count() > 0) {
            return back()->withErrors(['password' => 'Cannot delete account with active borrows.']);
        }

        ActivityLog::log('delete_account', "User deleted their account");
        Auth::logout();
        $user->delete();

        return redirect()->route('login')->with('success', 'Account deleted successfully.');
    }
}
