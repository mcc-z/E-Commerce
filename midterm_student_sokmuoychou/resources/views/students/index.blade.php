<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student List</title>
    <style>
        .header {
            background-color: black;
            color: white;
            padding: 20px;
            margin: 20px;
        }
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
    <h1 class="header">MIDTERM - SOK MUOYCHOU</h1>
    <!-- PART D: QUESTION 2 -->
    <h1>Student Information Page</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
        </tr>
        @foreach ($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->age }}</td>
            </tr>
        @endforeach
    </table>
    <footer class="header">Pannasastra University of Cambodia, 2026</footer>
</body>
</html>