<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arfil's Landscaping and Swimmingpool Services</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        html,
        body {
            height: 100%;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            padding: 30px;
            /* Optional: Adjust padding to provide space for content */
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            height: 60px; /* Adjust height as needed */
            z-index: 1000; /* Ensure the navbar stays on top */
            border-bottom: 1px solid #ddd; /* Optional: Add a bottom border */
        }

        .footer {
            background-color: #f7f7f7;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            position: relative;
            width: 100%;
            height: 60px; /* Fixed height for the footer */
            box-sizing: border-box; /* Ensure padding is included in height */
            margin-top: auto;
        }

        .img-profile {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }

        .btn-outline-info {
            border-color: #17a2b8;
            color: #17a2b8;
            background-color: transparent;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease-in-out;
        }

        .btn-outline-info:hover {
            transform: scale(1.05);
            background-color: #17a2b8;
            color: #fff;
            border-color: #17a2b8;
        }

        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            width: 300px;
            text-align: center;
        }

        .card-img-top {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card-body {
            flex: 1;
            padding: 20px;
        }

        .card-title {
            font-weight: 800;
        }

        .card-footer {
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .card-footer .btn-outline-info {
            margin: 0;
        }

        .service-container {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 30px;
            background-color: #f7f7f7;
            border-radius: 8px;
        }

    </style>

    <!-- Custom fonts -->
    <link href="https://startbootstrap.github.io/startbootstrap-sb-admin-2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles -->
    <link href="https://startbootstrap.github.io/startbootstrap-sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">

    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow mb-0">
        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('welcome') }}">
            <img src="{{ asset('arfil_logo1.png') }}" alt="Arfil's Logo" style="max-width: 55px;">
            Arfil's Landscaping and Swimmingpool Services
        </a>

        <!-- Links -->
        <ul class="navbar-nav ml-auto">
            <!-- About -->
            <li class="nav-item mr-4">
                <a class="nav-link text-dark font-weight-bold" href="#about">About</a>
            </li>

            <li class="nav-item dropdown mr-4">
                <a class="nav-link dropdown-toggle text-dark font-weight-bold" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Services
                </a>
                <div class="dropdown-menu" aria-labelledby="servicesDropdown">
                    <a class="dropdown-item" href="{{ route('services.byCategory', ['category' => 'landscaping']) }}">Landscaping</a>
                    <a class="dropdown-item" href="{{ route('services.byCategory', ['category' => 'swimmingpool']) }}">Swimming Pool</a>
                    <a class="dropdown-item" href="{{ route('services.byCategory', ['category' => 'renovation']) }}">Renovation</a>
                    <a class="dropdown-item" href="{{ route('services.byCategory', ['category' => 'maintenance']) }}">Maintenance</a>
                </div>
            </li>

            <li class="nav-item mr-4">
                <a class="nav-link text-dark font-weight-bold" href="#contact">Contact</a>
            </li>

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <!-- Counter - Alerts -->
                    <span class="badge badge-danger badge-counter">0</span>
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header bg-info">
                        Alerts Center
                    </h6>
                    <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- User Dropdown -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                    <img class="img-profile rounded-circle" src="{{ asset('man.png') }}" alt="Profile Image">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Settings
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="service-container">
            <a href="{{ route('welcome') }}" class="btn btn-outline-info"
                style="position: absolute; top: 20px; left: 20px;">
                <i class="fa fa-home"></i> Home
            </a>

            <h1 class="display-5" style="font-weight: 300; text-align: center; width: 100%;">{{ ucfirst($category) }}
                Services</h1>

            @if ($services->isEmpty())
                <p class="text-center">No {{ $category }} services available at the moment.</p>
            @else
                @foreach ($services as $service)
                    <div class="card">
                        <img src="{{ asset('storage/' . $service->design) }}" class="card-img-top"
                            alt="{{ $service->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $service->name }}</h5>
                            <p class="card-text">{{ $service->description }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('booking.form') }}" class="btn btn-outline-info">Book Service</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Arfil's Landscaping and Swimmingpool Services. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
