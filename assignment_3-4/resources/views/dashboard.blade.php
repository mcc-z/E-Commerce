@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if($user['role'] === 'Admin')
        <h2>Admin Dashboard</h2>
    @else
        <h2>User Dashboard</h2>
    @endif
    <p>This is for assignment 4!</p>
    <p>
        If you want to switch users, go to web.php and change the index number<br>
        of $currentUser = $users[i] (0, 2 - User role | 1, 3 - Admin role)
    </p>
@endsection