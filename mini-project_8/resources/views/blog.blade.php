@extends('layouts.app')

@section('title', 'Blog Details')

<style>
    .container {
        border: 1px solid darkslategray;
        width: 250px;
        margin: 20px auto;
        text-align: center;
    }
</style>

@section('content')
    <h2>Blog Details</h2>
    <hr>
    <div class="container">
        <h4>{{ $blog['title'] }}</h4>
        <hr>
        <p>{{ $blog['desc'] }}</p>
    </div>
    <a href="/blogs"><button type="button">Back</button></a>
@endsection