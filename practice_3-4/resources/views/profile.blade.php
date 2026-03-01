<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Practice 3</title>
</head>
<body>
    <h1>Profile</h1>
    <p>Name: {{ $name }}</p>
    <p>Age: {{ $age }}</p>
    @if($age < 18)
        <p>Status: Minor</p>
    @else
        <p>Status: Adult</p>
    @endif
</body>
</html>