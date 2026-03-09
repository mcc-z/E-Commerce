<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Signup</title>
</head>
<body style="text-align: center">
    <h1>Signup Form</h1>

    <form action="/signup" method="POST">
        @csrf

        <label for="name">Name:</label>
        <input type="text" name="name" id="name"><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email"><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password"><br><br>

        <button type="submit">Signup</button>
    </form>

    <p>Already have an account? <a href="/login">Login</a></p>
</body>
</html>