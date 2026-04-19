<h2>Edit Payroll</h2>

<form action="/payroll/update/{{ $payroll->id }}" method="POST">
    @csrf

    Employee Name
    <input type="text" name="employee_name" value="{{ $payroll->employee_name }}"><br><br>

    Salary
    <input type="number" name="salary" value="{{ $payroll->salary }}"><br><br>

    Bonus
    <input type="number" name="bonus" value="{{ $payroll->bonus }}"><br><br>

    Deduction
    <input type="number" name="deduction" value="{{ $payroll->deduction }}"><br><br>

    <button type="submit">Update</button>
</form>