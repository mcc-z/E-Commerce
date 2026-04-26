@extends('layouts.app')

@section('title', 'Student List')

<style>
    table {
        margin: 20px auto;
        border-collapse: collapse;
        width: 60%;
    }
    th, td {
        border: 1px solid black;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: lightgray;
    }
</style>
    
@section('content')
    <h2>Student List</h2>
    <hr>
    <table>
        <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Sex</th>
        </tr>
        @foreach ($students as $student)
            <tr>
                <td>{{ $student['name'] }}</td>
                <td>{{ $student['age'] }}</td>
                <td>{{ $student['sex'] }}</td>
            </tr>
        @endforeach
    </table>
@endsection