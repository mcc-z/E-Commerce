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
        }
        a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            font-size: 20px;
        }
        a:hover {
            color: blue;
        }
        main {
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Mini-Project 7</h1>
        <div>
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/students') }}">Student</a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>