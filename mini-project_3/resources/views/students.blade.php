<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student List</title>
</head>

<body>
    <h1>Student List</h1>
    <ul>
        @foreach($students as $student)
            <li>{{ $student['name'] }} - Age: {{ $student['age'] }}</li>
        @endforeach
    </ul>
</body>
</html>