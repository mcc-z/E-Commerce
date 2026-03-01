<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
</head>
<body>
    <header style="text-align: center; background-color: black; color: white; padding: 20px;">
        <h1>My Website</h1>
        <nav>
            <a style="color: white" href="{{ url('/') }}">Home</a> | 
            <a style="color: white" href="{{ url('/about') }}">About</a> | 
            <a style="color: white" href="{{ url('/dashboard') }}">Dashboard (Practice 4)</a>
        </nav>
    </header>

    <main style="text-align: center">
        @yield('content')
    </main>

    <footer style="text-align: center; background-color: black; color: white; padding: 20px;">
        <p>&copy; 2026, Sok Muoychou</p>
    </footer>
</body>
</html>