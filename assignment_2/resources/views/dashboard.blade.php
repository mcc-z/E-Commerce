<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        .heading {
            text-align: center;
            background-color: orangered;
            color: white;
            padding: 20px;
        }
        .main_body {
            text-align: center;
            padding: 20px;
            background-color: rgb(236, 210, 255);
            margin: 20px;
        }
        .footer {
            text-align: center;
            background-color: orangered;
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
    </style>
</head>

<body>
    <header class="heading">
        <h1>🔶  Welcome to the DASHBOARD! 🔶 </h1>
    </header>

    <main class="main_body">
        <a style="color: white; text-decoration: none;" href="{{ url('/logout') }}"><button class="greet_button"><strong>Logout</strong></button></a>
    </main>

    <footer class="footer">
        <p>&copy; Footer, 2026</p>
    </footer>
</body>
</html>