<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Midterm - Sok Muoychou</title>
    <style>
        table {
            border-collapse: collapse;
            margin: auto;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
        }
        th {
            background-color: lightgray;
        }
    </style>
</head>
<body style="text-align: center">
    <!-- PART C: QUESTION 2 -->
    <h1>This is Student Controller</h1>
    <p>By: Sok Muoychou</p>
    <!-- PART C: QUESTION 3 -->
    <h1>Student Information Page</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Email</th>
        </tr>
        <tr>
            <td>{{ $student['name'] }}</td>
            <td>{{ $student['age'] }}</td>
            <td>{{ $student['email'] }}</td>
        </tr>
    </table>
</body>
</html>