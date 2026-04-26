<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <style>
        header {
            background-color: black;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
        }
        a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-size: 20px;
        }
        a:hover {
            color: blue;
        }
        main {
            text-align: center;
            margin: 20px;
            margin-top: 150px;
        }
        footer {
            text-align: center;
            background-color: black;
            color: white;
            padding: 30px;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Mini-Project 7</h1>
        <div>
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/blogs') }}">Blogs</a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        &copy; 2026, Sok Muoychou
    </footer>
</body>
</html>