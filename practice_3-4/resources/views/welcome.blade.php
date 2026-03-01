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
            background-color: rgb(172, 237, 216);
        }
        .greet_button:hover {
            background-color: rgb(86, 164, 139);
        }
    </style>
</head>

<body>
    <header class="heading">
        <h1>🌸 Welcome to the Homepage! 🌸</h1>
    </header>

    <main class="main_body">
        <p>
            My name is <strong>Sok Muoychou</strong> and this is my submission for <strong>practice 3 and 4</strong> of Laravel. :D
        </p>

        <div>
            <p>Go to URL: <strong>/profile</strong> to see the profile page. (Practice 3)</p>
            <p>Go to URL: <strong>/students</strong> to see the students page. (Practice 4)</p>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; Footer, 2026</p>
    </footer>
</body>
</html>