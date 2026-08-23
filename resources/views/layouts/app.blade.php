<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sip N Bite — Modern Café & Bistro')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-red: #E23744;
            --primary-hover: #c42935;
            --dark-bg: #121418;
            --card-bg: rgba(255, 255, 255, 0.95);
            --accent-gold: #FFB800;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
            color: #1f2937;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--primary-red) !important;
            letter-spacing: -0.5px;
        }

        .btn-zomato {
            background-color: var(--primary-red);
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 22px;
            border: none;
            transition: all 0.2s ease-in-out;
        }

        .btn-zomato:hover {
            background-color: var(--primary-hover);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(226, 55, 68, 0.3);
        }

        .food-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .food-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }

        .veg-badge {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #2e7d32;
            padding: 2px;
        }
        .veg-badge::after {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            background: #2e7d32;
            border-radius: 50%;
        }

        .nonveg-badge {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #c62828;
            padding: 2px;
        }
        .nonveg-badge::after {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            background: #c62828;
            border-radius: 50%;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        }
    </style>

    @yield('styles')
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top glass-header py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('customer.home') }}">
                <i class="fa-solid fa-utensils"></i> Sip N Bite
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium gap-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('customer.home') }}"><i class="fa-solid fa-house text-danger me-1"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('customer.menu') }}"><i class="fa-solid fa-book-open text-danger me-1"></i> Order Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('customer.booking') }}"><i class="fa-solid fa-chair text-danger me-1"></i> Book Table</a></li>
                    @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('customer.orders') }}"><i class="fa-solid fa-receipt text-danger me-1"></i> My Orders</a></li>
                    @endauth
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('customer.cart') }}" class="btn btn-outline-dark position-relative rounded-pill px-3">
                        <i class="fa-solid fa-bag-shopping"></i> Cart
                        @php $cartCount = count(session('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle rounded-pill px-3 border" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-circle-user me-1 text-danger"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item fw-semibold text-danger" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-line me-2"></i> Admin Panel</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('customer.profile') }}"><i class="fa-solid fa-user-pen me-2"></i> My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.orders') }}"><i class="fa-solid fa-clock-rotate-left me-2"></i> Order History</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-danger px-3 rounded-pill fw-semibold">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-zomato px-3 rounded-pill">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Body Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h4 class="brand-font text-danger fw-bold"><i class="fa-solid fa-utensils"></i> Sip N Bite</h4>
                    <p class="text-secondary mt-2">Delivering delicious food and unforgettable dining experiences directly to your doorstep and café tables.</p>
                </div>
                <div class="col-md-3 ms-auto">
                    <h6 class="fw-bold mb-3 text-uppercase text-light">Quick Links</h6>
                    <ul class="list-unstyled text-secondary d-flex flex-column gap-2">
                        <li><a href="{{ route('customer.menu') }}" class="text-secondary text-decoration-none">Explore Menu</a></li>
                        <li><a href="{{ route('customer.booking') }}" class="text-secondary text-decoration-none">Table Reservation</a></li>
                        <li><a href="{{ route('admin.dashboard') }}" class="text-secondary text-decoration-none">Admin Management</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold mb-3 text-uppercase text-light">Café Contact</h6>
                    <p class="text-secondary mb-1"><i class="fa-solid fa-location-dot me-2 text-danger"></i> 108 Gourmet Avenue, Sector 18</p>
                    <p class="text-secondary mb-1"><i class="fa-solid fa-phone me-2 text-danger"></i> +91 9876543210</p>
                    <p class="text-secondary mb-0"><i class="fa-solid fa-clock me-2 text-danger"></i> 09:00 AM - 11:00 PM</p>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center text-secondary small">
                © {{ date('Y') }} Sip N Bite Café & Bistro. All rights reserved. Zomato-inspired Platform.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
