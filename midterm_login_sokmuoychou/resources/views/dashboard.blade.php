<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <style>
        .header {
            background-color: black;
            color: white;
            padding: 20px;
            margin: 20px;
        }
    </style>
</head>
<body style="text-align: center">
    <h1 class="header">MIDTERM EXAM - DASHBOARD</h1>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Email: {{ $user->email }}</p>
    <a href="/logout">Logout</a>
    <footer class="header">Pannasastra University of Cambodia, 2026</footer>
</body>
</html>