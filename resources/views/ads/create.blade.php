@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card profile-card">
                <div class="card-body profile-body">
                   <img src="/img/man.jpg" class="profile-img">
                    <p class="profile-name">John Doe</p>
                </div>
                <div class="menu-divider"></div>
                <div class="vertical-menu">
                    <a href="#" class="menu-item active">Dashboard</a>
                    <a href="#" class="menu-item">Profile</a>
                    <a href="#" class="menu-item">Create ads</a>
                    <a href="#" class="menu-item">Publish ads</a>
                    <a href="#" class="menu-item">Pending ads</a>
                    <a href="#" class="menu-item">Message</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .profile-body {
        padding: 20px;
        text-align: center;
    }

    .profile-img {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #4285f4;
        margin: 0 auto 15px;
        display: block;
    }

    .profile-name {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0;
        color: #333;
    }

    .menu-divider {
        height: 2px;
        background: linear-gradient(to right, #4285f4, #34a853);
        margin: 0;
        border: none;
    }

    .vertical-menu {
        display: flex;
        flex-direction: column;
        padding: 0;
    }

    .menu-item {
        padding: 12px 20px;
        text-decoration: none;
        color: #555;
        font-weight: 500;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .menu-item:hover {
        background-color: #f8f9fa;
        color: #4285f4;
        border-left: 3px solid #4285f4;
    }

    .menu-item.active {
        background-color: #e8f0fe;
        color: #4285f4;
        border-left: 3px solid #4285f4;
    }
</style>
@endsection