@extends('layouts.app')
@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow-lg border-0 rounded overflow-hidden">
                <div class="row g-0">
                    <!-- Left image section -->
                    <div class="col-md-6 d-none d-md-block">
                        <img src="{{ asset('img/register.jpg') }}" 
                             alt="Register Image" 
                             class="img-fluid h-100 w-100 object-fit-cover">
                    </div>

                    <!-- Right form section -->
                    <div class="col-md-6 bg-white p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-primary">Reģistrējies</h2>
                            <p class="text-muted">Reģistrējies un izzini Latviju!</p>
                        </div>

                        <form action="" method="post">@csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Vārds</label>
                                <input type="text" name="name" id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       placeholder="Ievadi savu vārdu">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

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

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Apstiprini paroli</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       placeholder="Apstiprini savu paroli">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    Reģistrēties
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <small class="text-muted">Jau ir konts? 
                                    <a href="{{ route('login') }}" class="text-primary fw-bold">Pieslēdzies</a>
                                </small>
                            </div>
                        </form>
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
        box-shadow: none;
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

    @media (max-width: 767px) {
        .card .col-md-6:first-child {
            display: none; /* Hide the image on small screens */
        }
    }
</style>

@endsection


