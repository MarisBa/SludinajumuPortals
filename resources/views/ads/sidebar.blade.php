<div class="col-xxl-3 col-xl-4 col-lg-4 col-md-5 mb-4">
    <div class="card profile-card h-100" style="min-width: 280px;">
        <div class="card-body text-center">
            <div class="avatar-container mb-3">
                <img src="/img/man.jpg" alt="User Avatar" 
                     class="img-fluid rounded-circle mx-auto d-block" 
                     style="width: 120px; height: 120px; object-fit: cover;">
            </div>
            <h5 class="profile-name"><b>{{auth()->user()->name}}</b></h5>
            <p class="text-muted small">Premium Member</p>
        </div>

        <div class="sidebar-menu">
    <a href="{{ route('dashboard') }}" 
       class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>

    <a href="{{ route('profile') }}" 
       class="sidebar-item {{ request()->routeIs('profile') ? 'active' : '' }}">
        <i class="fas fa-user"></i> Profile
    </a>

    <a href="{{ route('ads.create') }}" 
       class="sidebar-item {{ request()->routeIs('ads.create') ? 'active' : '' }}">
        <i class="fas fa-plus-circle"></i> Create Ad
    </a>

    <a href="{{ route('ads.index') }}" 
       class="sidebar-item {{ request()->routeIs('ads.index') ? 'active' : '' }}">
        <i class="fas fa-check-circle"></i> Published Ads
    </a>

    <a href="" 
       class="sidebar-item {{ request()->routeIs('ads.pending') ? 'active' : '' }}">
        <i class="fas fa-clock"></i> Pending Ads
    </a>

    <a href="" 
       class="sidebar-item {{ request()->routeIs('messages.index') ? 'active' : '' }}">
        <i class="fas fa-envelope"></i> Messages
        <span class="badge badge-pill badge-primary ml-auto">3</span>
    </a>
</div>

    </div>
</div>

<style>
    .profile-card {
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        min-width: 280px;
        width: 100%;
    }

    .card-body {
        padding: 25px 20px;
    }

    .profile-name {
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 18px;
        color: #333;
    }

    .avatar-container {
        margin: 0 auto 15px;
        width: 120px;
        height: 120px;
    }

    .sidebar-menu {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding: 0 20px 20px;
        flex-grow: 1;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        color: #555;
        text-decoration: none;
        font-size: 15px;
        padding: 12px 15px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .sidebar-item i {
        margin-right: 12px;
        font-size: 16px;
        width: 20px;
        text-align: center;
        color: #666;
    }

    .sidebar-item:hover {
        background-color: #f8f9fa;
        color: #0062cc;
    }

    .sidebar-item:hover i {
        color: #0062cc;
    }

    .sidebar-item.active {
        font-weight: 600;
        color: #0056b3;
        background-color: rgba(0, 98, 204, 0.08);
    }

    .sidebar-item.active i {
        color: #0056b3;
    }

    .badge {
        margin-left: auto;
        font-size: 12px;
        font-weight: 500;
        padding: 4px 8px;
    }

    /* Responsive adjustments */
    @media (min-width: 1400px) {
        .profile-card {
            min-width: 320px;
        }
        .sidebar-menu {
            padding: 0 25px 25px;
        }
    }

    @media (max-width: 1199.98px) {
        .sidebar-item {
            padding: 10px 12px;
            font-size: 14px;
        }
    }

    @media (max-width: 991.98px) {
        .profile-card {
            min-width: 260px;
        }
        
        .avatar-container {
            width: 100px;
            height: 100px;
        }
        
        .avatar-container img {
            width: 100px;
            height: 100px;
        }
    }

    @media (max-width: 767.98px) {
        .profile-card {
            min-width: 100%;
            max-width: 100%;
        }
        
        .sidebar-menu {
            padding: 0 15px 15px;
        }
    }
</style>