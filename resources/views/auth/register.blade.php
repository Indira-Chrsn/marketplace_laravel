<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h2>Register</h2>
        <br>

@extends('auth.layouts')

@section('content')

        <form action="{{ route('store') }}" method="POST">
            @csrf
        
            <label for="name">Name: </label>
            <input type="text" name="name" value="name">

            <label for="email">Email </label>
            <input type="email" name="email" value="email">

            <label for="password">Password: </label>
            <input type="password" name="password" value="password">

            <input type="submit" value="Register">
        </form>

    </body>
</html>

@endsection