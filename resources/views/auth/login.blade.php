@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required autofocus>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
@endsection
