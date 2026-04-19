<h2>Payroll List</h2>

<a href="/payroll/create">Add Payroll</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Salary</th>
        <th>Bonus</th>
        <th>Deduction</th>
        <th>Action</th>
    </tr>
    @foreach ($payrolls as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->employee_name }}</td>
            <td>{{ $p->salary }}</td>
            <td>{{ $p->bonus }}</td>
            <td>{{ $p->deduction }}</td>
            <td>
                <a href="/payroll/edit/{{ $p->id }}">Edit</a>
                <a href="/payroll/delete/{{ $p->id }}">Delete</a>
            </td>
        </tr>
    @endforeach
</table>