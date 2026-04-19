<form action="/students" method="POST">
    @csrf

    <label>Name:</label>
    <input type="text" name="name"><br>

    <label>Email:</label>
    <input type="email" name="email"><br>

    <label>Course:</label>
    <input type="text" name="course"><br>

    <label>Age:</label>
    <input type="number" name="age"><br>

    <button type="submit">Save</button>
</form>
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif