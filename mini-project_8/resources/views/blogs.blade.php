@extends('layouts.app')

@section('title', 'Blog List')

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
    <h2>Blog List</h2>
    <hr>
    <table>
        <tr>
            <td>ID</td>
            <td>Title</td>
            <td>Subject</td>
            <td>Action</td>
        </tr>
        @foreach ($blogs as $blog)
            <tr>
                <td>{{ $blog['id'] }}</td>
                <td>{{ $blog['title'] }}</td>
                <td>{{ $blog['subject'] }}</td>
                <td><a href="/blog/{{ $blog['id'] }}"><button type="button">View</button></a></td>
            </tr>
        @endforeach
    </table>
@endsection