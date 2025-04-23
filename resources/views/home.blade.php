@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Home</div>

                <div class="card-body">
                    Hello, {{ auth()->user()->name }}

                    {{-- Logout poga --}}
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>

                    {{-- Vai arī - izmantojot saiti ar JavaScript
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="btn btn-danger mt-3">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    --}}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
