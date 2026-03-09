<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Form</title>
    <style>
        button, input {
            padding: 10px;
        }
    </style>
</head>
<body style="text-align: center">
    <h1>Add Student</h1>
    <form action="/students" method="post">
        @csrf

        <label for="name">Name:</label>
        <input type="text" name="name" id="name"><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email"><br><br>

        <label for="age">Age:</label>
        <input type="number" name="age" id="age"><br><br>

        <button type="submit">Submit</button>
    </form>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>