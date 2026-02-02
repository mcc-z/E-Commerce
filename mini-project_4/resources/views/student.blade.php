<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Details</title>
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
            color: white;
            text-decoration: none;
        }
        .greet_button:hover {
            background-color: rgb(35, 131, 163);
        }
        .dashboard_button {
            background-color: rgb(255, 115, 0);
            border: none;
            cursor: pointer;
            padding: 20px;
            margin: 20px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .dashboard_button:hover {
            background-color: rgb(186, 60, 18);
        }
        table {
            border-collapse: collapse;
            width: 60%;
            margin: auto;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: rgb(186, 186, 186);
        }
        td {
            background-color: white;
        }
    </style>
</head>

<body>
    <header class="heading">
        <h1>STUDENT DETAILS</h1>
    </header>

    <main class="main_body">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Major</th>
            </tr>
            <tr>
                <td>{{ $student['id'] }}</td>
                <td>{{ $student['name'] }}</td>
                <td>{{ $student['age'] }}</td>
                <td>{{ $student['subject'] }}</td>
            </tr>
        </table>

        <div>
            <a href="{{ route('students.list') }}"><button class="dashboard_button">Back</button></a>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; Footer, 2026</p>
    </footer>
</body>
</html>