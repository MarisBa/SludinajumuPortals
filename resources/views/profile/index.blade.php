@extends('layouts.app')

@section('content')
<style>
/* Assuming these variables are defined in your main CSS or the previous file */
:root {
    --bs-main-blue: #0057B7; /* Galvenā zilā krāsa (Latvijas karoga tonis) */
    --bs-dark-blue: #00449e; /* Tumšā zilā (hover) */
    --bs-primary-light: #e6f0ff; /* Ļoti gaiši zils fons */
    --bs-success: #28a745; /* Zaļš */
    --bs-danger: #dc3545; /* Sarkans */
}

/* Custom Card & Button Styles */
.card-elegant {
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: none;
}
.card-header-primary {
    background-color: var(--bs-main-blue);
    color: white;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    padding: 1rem 1.5rem;
    font-weight: 600;
    font-size: 1.25rem;
}
.btn-primary-custom {
    background-color: var(--bs-main-blue);
    border-color: var(--bs-main-blue);
    transition: all 0.2s ease;
}
.btn-primary-custom:hover {
    background-color: var(--bs-dark-blue);
    border-color: var(--bs-dark-blue);
    transform: translateY(-1px);
}
.profile-img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid var(--bs-main-blue);
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.error-message {
    color: var(--bs-danger);
    font-size: 0.875em;
    margin-top: 0.25rem;
}
</style>

<div class="container py-4">
    <div class="row">

        <!-- Sidebar (Visible on large screens) -->
        

        <!-- Main Content Area -->
        <div class="col-lg-9">
            
            <!-- Profile Overview Header -->
            <div class="card card-elegant mb-5 p-4 text-center">
                <div class="d-flex flex-column align-items-center">
                    <img 
                        src="{{ auth()->user()->image ?? 'https://placehold.co/100x100/0057B7/ffffff?text=U' }}" 
                        class="profile-img" 
                        alt="Profile Picture"
                        onerror="this.onerror=null;this.src='https://placehold.co/100x100/0057B7/ffffff?text=U';"
                    >
                    <h3 class="mt-3 mb-1 fw-bold text-main-blue">{{ auth()->user()->name }}</h3>
                    <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <!-- Session Message Display -->
            @if(Session::has('message'))
            <div class="alert alert-success alert-dismissible fade show card-elegant" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ Session::get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show card-elegant" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Jūsu parole ir atjaunināta!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row g-4">
                <!-- Column for Profile Update -->
                <div class="col-lg-6">
                    <form action="" method="post" enctype="multipart/form-data">@csrf
                        <div class="card card-elegant">
                            <div class="card-header-primary">Atjaunināt Profila Datus</div>

                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Pilnais Vārds</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}">
                                    @error('name')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adrese</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', auth()->user()->address) }}">
                                    @error('address')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="image" class="form-label">Profila Bilde</label>
                                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="image">
                                    @error('image')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-primary-custom w-100">Saglabāt Profilu</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Column for Password Change -->
                <div class="col-lg-6">
                    <form action="{{ route('user-password.update') }}" method="post">@csrf
                        @method('PUT')

                        <div class="card card-elegant">
                            <div class="card-header-primary">Mainīt Paroli</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Pašreizējā Parole</label>
                                    <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                    @error('current_password', 'updatePassword')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Jaunā Parole</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password', 'updatePassword')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">Apstiprināt Jauno Paroli</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                                    @error('password_confirmation', 'updatePassword')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button class="btn btn-primary-custom w-100" type="submit">Atjaunināt Paroli</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar (Visible below forms on small/medium screens) -->
            <div class="d-lg-none mt-4">
             
            </div>
            
        </div>
    </div>
</div>

@endsection
