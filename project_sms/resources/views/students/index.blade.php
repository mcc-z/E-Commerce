<table border="1">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Course</th>
        <th>Age</th>
    </tr>
    @foreach ($students as $student)
        <tr>
            <td>{{ $students->name }}</td>
            <td>{{ $students->email }}</td>
            <td>{{ $students->course }}</td>
            <td>{{ $students->age }}</td>
        </tr>
    @endforeach
</table>