@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <h2>Product List</h2>

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

    <table>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Status</th>
        </tr>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td>${{ $product['price'] }}</td>
                @if ($product['stock'] === '0')
                    <td style="color: red">Out of Stock</td>
                @else
                    <td style="color: green">In Stock</td>
                @endif
            </tr>
        @endforeach
    </table>
@endsection