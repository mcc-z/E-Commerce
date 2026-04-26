<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Student</title>
    <style>
        .align-center {
            text-align: center;
        }
        form {
            text-align: center;
            padding: 20px;
        }
        form input {
            width: 150px;
            padding: 5px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="align-center"><h2>Create Student</h2></div>
    <hr>
    <div class="align-center">
        <form action="/students" method="POST">
            @csrf

            <label>Name:</label>
            <input type="text" name="name"><br>

            <label>Email:</label>
            <input type="email" name="email"><br>

            <label>Course:</label>
            <input type="text" name="course"><br>

            <label>Age:</label>
            <input type="number" name="age"><br>

            <button type="submit">Save</button>
        </form>
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</body>
</html>