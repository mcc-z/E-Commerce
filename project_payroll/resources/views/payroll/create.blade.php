<h2>Add Payroll</h2>

<form action="/payroll/store" method="POST">
    @csrf

    Employee Name
    <input type="text" name="employee_name"><br><br>

    Salary
    <input type="number" name="salary"><br><br>

    Bonus
    <input type="number" name="bonus"><br><br>

    Deduction
    <input type="number" name="deduction"><br><br>

    <button type="submit">Save</button>
</form>