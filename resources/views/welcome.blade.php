<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Arfil's Landscaping Services</title>

    <!-- Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff; /* Set body background to white */
            color: #000000; /* Set text color to black */
            scroll-behavior: smooth; 
        }

        .nav-item .nav-link {
            color: #343a40; /* Default color */
            font-weight: 600;
            text-decoration: none; /* Remove underline by default */
            transition: color 0.3s, border-bottom-color 0.3s; /* Smooth transition for color and underline */
        }

        .nav-item:hover .nav-link {
            color: #17a2b8 !important; /* Success (green) color */
            border-bottom: 2px solid  #17a2b8; /* Underline on hover */
        }

        .navbar {
            box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.1); /* Add shadow for bottom highlight */
        }

        .jumbotron {
            position: relative;
            background-size: cover;
            color: #000000; /* Text color black */
            text-align: center; /* Center text */
            padding: 4rem 2rem; /* Add padding for better layout */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: #ffffff; /* Set jumbotron background to white */
        }

        .jumbotron::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5); /* Dark overlay */
            backdrop-filter: blur(10px); /* Blur effect */
        }

        .jumbotron .container {
            position: relative;
            z-index: 1;
        }

        .jumbotron h1 {
            font-family: 'Roboto', sans-serif; /* Roboto font */
            font-weight: 300; /* Extra bold */
            color: #000000; /* White color for contrast */
        }

        .jumbotron .lead {
            font-family: 'Roboto', sans-serif; /* Roboto font */
            font-weight: 100; /* Semi-bold */
            color: #000000; /* White color for contrast */
        }

        .jumbotron a.btn {
            margin: 0 10px; /* Add margin between buttons */
        }

        /* Placeholder styling */
        .placeholder {
            background-color: #f2f2f2; /* Light grey background */
            padding: 20px;
            margin-bottom: 20px;
        }

        .placeholder .card-text {
            color: #888888; /* Light grey text color */
        }

        .card-background {
        position: relative;
        width: 100%;
        height: 100%;
        background-size: cover;
        border-radius: 12px; /* Increased radius for a smoother look */
        overflow: hidden; /* Ensure content stays within bounds */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Soft shadow for depth */
        transition: transform 0.3s ease; /* Smooth transform effect */
    }

    .card-background:hover {
        transform: scale(1.05); /* Slightly scale up on hover for a dynamic effect */
    }

    .card-body {
        position: relative;
        z-index: 1;
        background-color: rgba(255, 255, 255, 0.8); /* Slightly more transparent background */
        padding: 1.5rem; /* Increased padding for better spacing */
        border-radius: 12px; /* Match border radius with the background */
        margin: 1rem; /* Add margin to separate from the card border */
        backdrop-filter: blur(8px); /* Slight blur for a frosted glass effect */
    }

        
    </style>

    <!-- Custom fonts -->
    <link href="https://startbootstrap.github.io/startbootstrap-sb-admin-2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles -->
    <link href="https://startbootstrap.github.io/startbootstrap-sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow mb-0">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Logo -->
    <a class="navbar-brand" href="">
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
                @auth
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                @endauth
                <img class="img-profile rounded-circle" src="man.png">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                @auth
                <a class="dropdown-item" href="{{ route('quotation.view') }}">
                    <i class="fas fa-file-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    My Quotations
                </a>
                
                <a class="dropdown-item" href="{{ route('booking.index') }}">
                    <i class="fas fa-calendar-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    My Bookings
                </a>
                {{-- <a class="dropdown-item" href="{{ route('project.index') }}"> --}}
                    <i class="fas fa-briefcase fa-sm fa-fw mr-2 text-gray-400"></i>
                    My Projects
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @else
                <a class="dropdown-item" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Sign in
                </a>
                @if (Route::has('register'))
                <a class="dropdown-item" href="{{ route('register') }}">
                    <i class="fas fa-user-plus fa-sm fa-fw mr-2 text-gray-400"></i>
                    Sign Up
                </a>
                @endif
                @endauth
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->

<!-- Page Content -->

<!-- Carousel -->
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="max-height: 300px; overflow: hidden; width: 100%;">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" src="{{ asset('landscaping.jpg') }}" alt="Landscape Services" style="object-fit: cover; max-height: 300px;">
            <div class="carousel-caption d-none d-md-block text-dark">
                <h5 class="font-weight-bold text-light">Landscape Services</h5>
                <p class="font-weight-bold text-light">Elevating outdoor spaces with inspired designs and meticulous care.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="{{ asset('swimmingpool.jpg') }}" alt="SwimmingPool Services" style="object-fit: cover; max-height: 300px;">
            <div class="carousel-caption d-none d-md-block text-dark">
                <h5 class="font-weight-bold text-light">Swimming Pool Services</h5>
                <p class="font-weight-bold text-light">Dive into luxury with our expert swimming pool design and maintenance services.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="{{ asset('renovation.jpg') }}" alt="Renovation Services" style="object-fit: cover; max-height: 300px;">
            <div class="carousel-caption d-none d-md-block text-dark">
                <h5 class="font-weight-bold text-light">Renovation Services</h5>
                <p class="font-weight-bold text-light">Revitalize your space with our expert renovation solutions, tailored to your vision.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="{{ asset('maintenance.jpg') }}" alt="Maintenance Services" style="object-fit: cover; max-height: 300px;">
            <div class="carousel-caption d-none d-md-block text-dark">
                <h5 class="font-weight-bold text-light">Maintenance Services</h5>
                <p class="font-weight-bold text-light">Keeping your property pristine with reliable maintenance solutions for every season.</p>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<!-- End of Carousel -->
<div class="container-fluid p-0">

    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">Transform Your Outdoors with Arfil's Landscaping Services</h1>
            <p>We offer a comprehensive range of landscaping services to make your outdoor space beautiful and functional.</p>
            <p>From landscaping and swimming pool design to renovation and maintenance, we have the expertise to bring your vision to life.</p>
            <hr class="my-4">
            <a class="btn btn-success btn-lg" href="{{ route('booking.form') }}" role="button">Schedule Site Visit</a>
            <a class="btn btn-primary btn-lg" href="{{ route('quotation.create') }}" role="button">Get a Quote</a>
        </div>
    </div>
    

    <!-- Divider -->    
    <div class="border-top my-4"></div>


  <h1 class="display-5" style="font-weight: 300; text-align: center;">Services</h1>


  <div class="placeholder" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; padding: 30px;">
   <!-- Card 1: Landscaping -->
   <div class="card" style="width: 300px; cursor: pointer;" onclick="window.location.href='{{ route('services.byCategory', ['category' => 'landscaping']) }}'">
    <img src="landscaping.jpg" class="card-img-top" alt="Landscaping" style="width: 100%; height: 180px; object-fit: cover;">
    <div class="card-body" style="padding: 20px; text-align: center;">
        <h5 class="card-title" style="font-weight: 800;">Landscaping</h5>
        <p class="card-text">Transform your outdoor space with our professional landscaping services. We offer custom designs and expert installation to enhance your property's beauty.</p>
        <a href="{{ route('services.byCategory', ['category' => 'landscaping']) }}" class="btn btn-primary">View Services</a>
    </div>
</div>

<!-- Card 2: Swimming Pool -->
<div class="card" style="width: 300px; cursor: pointer;" onclick="window.location.href='{{ route('services.byCategory', ['category' => 'swimmingpool']) }}'">
    <img src="swimmingpool.jpg" class="card-img-top" alt="Swimming Pool" style="width: 100%; height: 180px; object-fit: cover;">
    <div class="card-body" style="padding: 20px; text-align: center;">
        <h5 class="card-title" style="font-weight: 800;">Swimming Pool</h5>
        <p class="card-text">Dive into luxury with our professional swimming pool design and maintenance services. We create stunning pools tailored to your lifestyle and preferences.</p>
        <a href="{{ route('services.byCategory', ['category' => 'swimmingpool']) }}" class="btn btn-primary">View Services</a>
    </div>
</div>

<!-- Card 3: Renovation -->
<div class="card" style="width: 300px; cursor: pointer;" onclick="window.location.href='{{ route('services.byCategory', ['category' => 'renovation']) }}'">
    <img src="Renovation.jpg" class="card-img-top" alt="Renovation" style="width: 100%; height: 180px; object-fit: cover;">
    <div class="card-body" style="padding: 20px; text-align: center;">
        <h5 class="card-title" style="font-weight: 800;">Renovation</h5>
        <p class="card-text">Revitalize your space with our renovation services. Whether it's a complete makeover or minor updates, we'll transform your property to meet your vision.</p>
        <a href="{{ route('services.byCategory', ['category' => 'renovation']) }}" class="btn btn-primary">View Services</a>
    </div>
</div>

<!-- Card 4: Maintenance -->
<div class="card" style="width: 300px; cursor: pointer;" onclick="window.location.href='{{ route('services.byCategory', ['category' => 'maintenance']) }}'">
    <img src="maintenance1.jpg" class="card-img-top" alt="Maintenance" style="width: 100%; height: 180px; object-fit: cover;">
    <div class="card-body" style="padding: 20px; text-align: center;">
        <h5 class="card-title" style="font-weight: 800;">Maintenance</h5>
        <p class="card-text">Keep your property in top shape with our reliable maintenance services. From seasonal upkeep to ongoing care, we ensure your outdoor space remains pristine.</p>
        <a href="{{ route('services.byCategory', ['category' => 'maintenance']) }}" class="btn btn-primary">View Services</a>
    </div>
</div>

</div>

</div>


<div id="about" class="border-top my-4"></div>

<h1 class="display-5" style="font-weight: 300; text-align: center;">About Us</h1>

<div class="placeholder1" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; padding: 30px;">
    <div class="card" style="width: 18rem;">
        <div class="card-background">
            <div class="card-body">
                <h5 class="card-title" style="font-weight: 700;">Mission</h5>
                <p class="card-text">To provide exceptional landscaping services that transform outdoor spaces and exceed client expectations.</p>
            </div>
        </div>
    </div>
    <div class="card" style="width: 18rem;">
        <div class="card-background">
            <div class="card-body">
                <h5 class="card-title" style="font-weight: 700;">Vision</h5>
                <p class="card-text">To become a leading provider of innovative and sustainable landscaping solutions in the region.</p>
            </div>
        </div>
    </div>
    <div class="card" style="width: 18rem;">
        <div class="card-background">
            <div class="card-body">
                <h5 class="card-title" style="font-weight: 700;">Goal</h5>
                <p class="card-text">To continuously improve our services, foster long-term client relationships, and contribute positively to environmental conservation.</p>
            </div>
        </div>
    </div>
</div>
<h1   class="display-5" style="font-weight: 300; text-align: center;">Contact Us</h1>
<div id="contact" class="placeholder" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; padding: 30px;">
    <a href="https://www.facebook.com/profile.php?id=100087594043346" class="btn btn-primary">
        <i class="fab fa-facebook-f"></i> Facebook
    </a>

    <!-- Gmail Button -->
    <a href="https://mail.google.com/mail/u/0/#inbox?compose=jrjtXFBjqfMzNMcCpJTDGhPJRdkVjFQVJCkKrZlcNZPzQqJFBXcGMdvbnZsxqSgQgGDtffdG" class="btn btn-danger">
        <i class="far fa-envelope"></i> Gmail
    </a>
</div>

</div>


<footer class="sticky-footer bg-white">
    <div class="container my-auto">
      <div class="copyright text-center my-auto">
        <span>Copyright © Arfil's Landscaping Services</span>
      </div>
    </div>
  </footer>

<!-- Bootstrap core JavaScript-->
<script src="https://startbootstrap.github.io/startbootstrap-sb-admin-2/vendor/jquery/jquery.min.js"></script>
<script src="https://startbootstrap.github.io/startbootstrap-sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="https://startbootstrap.github.io/startbootstrap-sb-admin-2/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="https://startbootstrap.github.io/startbootstrap-sb-admin-2/js/sb-admin-2.min.js"></script>

</body>
</html>
