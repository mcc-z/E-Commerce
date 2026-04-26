<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Homepage</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        .heading {
            text-align: center;
            background-color: palevioletred;
            color: white;
            padding: 20px;
        }
        .main_body {
            text-align: center;
            padding: 20px;
            background-color: rgb(218, 255, 218);
            margin: 20px;
        }
        .footer {
            text-align: center;
            background-color: palevioletred;
            color: white;
            padding: 20px;
        }
        .greet_button {
            padding: 20px;
            margin: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: rgb(92, 214, 255);
            color: white;
            text-decoration: none;
        }
        .greet_button:hover {
            background-color: rgb(35, 131, 163);
        }
        .dashboard_button {
            background-color: rgb(255, 115, 0);
            border: none;
            cursor: pointer;
            padding: 20px;
            margin: 20px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .dashboard_button:hover {
            background-color: rgb(186, 60, 18);
        }
    </style>
</head>

<body>
    <header class="heading">
        <h1>🌸 Welcome to the Homepage! 🌸</h1>
    </header>

    <main class="main_body">
        <p>
            My name is <strong>Sok Muoychou</strong> and this is my submission for <strong>mini-project 5</strong> of Laravel.
        </p>

        <div>
            <a href="{{ route('admin.users') }}"><button class="dashboard_button">User List</button></a> |
            <a href="{{ route('admin.settings') }}"><button class="dashboard_button">Settings</button></a>
        </div>

        <div>
            <p>You can only open the User List and Admin Settings page if you are logged in. Click the button below to change your login status!</p>
        </div>

        <div>
            @if(session('loggedIn'))
                <p>You are logged in!</p>
                <a href="{{ route('logout') }}"><button class="dashboard_button">Log Out</button></a>
            @else
                <p>You are logged out!</p>
                <a href="{{ route('login') }}"><button class="dashboard_button">Log In</button></a>
            @endif
        </div>
    </main>

    <footer class="footer">
        <p>&copy; Footer, 2026</p>
    </footer>
</body>
</html>