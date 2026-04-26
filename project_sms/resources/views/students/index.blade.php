<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student List</title>
    <style>
        .align-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="align-center"><h2>Student List</h2></div>
    <hr>
    <div>
        <table style="margin: 20px auto;" border="1">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Age</th>
                <th colspan="2">Action</th>
            </tr>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->course }}</td>
                    <td>{{ $student->age }}</td>
                    <td>
                        <a href="/students/{{ $student->id }}/edit"><button type="submit">Edit</button></a>
                    </td>
                    <td>
                        <form action="/students/{{ $student->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="align-center"><a href="/students/create"><button type="submit">Create Student</button></a></div>
</body>
</html>