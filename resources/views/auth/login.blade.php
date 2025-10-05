@extends('layouts.app')
@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow-lg border-0 rounded overflow-hidden">
                <div class="row g-0">
                    <!-- Left form section -->
                    <div class="col-md-6 bg-white p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-primary">Pieslēdzies</h2>
                            <p class="text-muted">Ienāc savā kontā!</p>
                            @if (session('status'))
                                <div class="alert alert-success mt-2">{{ session('status') }}</div>
                            @endif
                        </div>

                        <form action="{{ route('login') }}" method="post">@csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">E-pasts</label>
                                <input type="email" name="email" id="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       placeholder="Ievadi savu e-pastu">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Parole</label>
                                <input type="password" name="password" id="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Ievadi savu paroli">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Atceries mani</label>
                                <div class="mt-2">
                                    <a href="{{ route('password.request') }}" class="text-primary small">Aizmirsi savu paroli?</a>
                                </div>
                            </div>

                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">Pieslēgties</button>
                            </div>

                            <div class="text-center mt-3">
                                <small class="text-muted">Nav konta? 
                                    <a href="{{ route('register') }}" class="text-primary fw-bold">Reģistrējies</a>
                                </small>
                            </div>

                            <hr>

                            <div class="d-grid mt-3">
                                <a class="btn btn-facebook btn-lg fw-bold" href="{{ url('auth/facebook') }}">
                                    Pieslēdzies ar Facebook
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Right image section -->
                    <div class="col-md-6 d-none d-md-block">
                        <img src="{{ asset('img/login.jpg') }}" 
                             alt="Login Image" 
                             class="img-fluid h-100 w-100 object-fit-cover">
                    </div>
                </div> <!-- row g-0 -->
            </div> <!-- card -->
        </div> <!-- col -->
    </div> <!-- row -->
</div> <!-- container -->

<style>
    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #ced4da;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        border-radius: 10px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
    }

    .btn-facebook {
        background: #3B5499;
        color: #ffffff;
        border-radius: 10px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .btn-facebook:hover {
        background: #2d3b73;
        box-shadow: 0 8px 20px rgba(59, 84, 153, 0.4);
    }

    @media (max-width: 767px) {
        .card .col-md-6:last-child {
            display: none; /* Hide the image on small screens */
        }
    }
</style>

@endsection
