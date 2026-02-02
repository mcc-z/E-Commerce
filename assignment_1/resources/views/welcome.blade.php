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
        }
        .greet_button:hover {
            background-color: rgb(35, 131, 163);
        }
    </style>
</head>

<body>
    <header class="heading">
        <h1>🌸 Welcome to the Homepage! 🌸</h1>
    </header>

    <main class="main_body">
        <p>
            My name is <strong>Sok Muoychou</strong> and this is my submission for <strong>assignment 1</strong> of Laravel.
        </p>

        <div>
            <p>Enter the product ID in the URL (Ex: /product/88) to display product ID, or click the button below to display default ID!</p>
            <button class="greet_button"><strong><a style="text-decoration: none; color: white;" href="{{ url('/product/') }}">Display Default Product ID</a></strong></button>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; Footer, 2026</p>
    </footer>
</body>
</html>