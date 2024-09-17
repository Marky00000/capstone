<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff;
            color: #000000;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        .navbar {
            box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.1);
        }
        .sticky-footer {
            background-color: #ffffff;
            text-align: center;
            padding: 10px;
            width: 100%;
            height: 50px;
            font-size: 14px;
            border-top: 1px solid #ddd;
            position: relative;
        }
        .nav-item .nav-link {
            color: #343a40;
            font-weight: 300 !important;
            text-decoration: none;
            transition: color 0.3s, border-bottom-color 0.3s;
        }
        .nav-item:hover .nav-link {
            color: #17a2b8 !important;
            border-bottom: 2px solid #17a2b8;
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

@auth
    @if(Auth::user()->usertype == 'super_admin' || Auth::user()->usertype == 'admin')
        <!-- Include Navbar for admins -->
        @include('layouts.navbar')

        <div class="d-flex" id="wrapper">
            <!-- Include Sidebar -->
            @include('layouts.sidebar')

            <div id="page-content-wrapper" class="flex-grow-1">
                <main class="py-4">
                    {{-- @yield('content') --}}
                </main>
            </div>
        </div>

        <!-- Include Footer -->
        @include('layouts.footer')
    @else
        <!-- Include Navbar for regular users -->
        @include('layouts.navbar')

        <div class="container">
            <main class="py-4">
                {{-- @yield('content') --}}
            </main>
        </div>

        <!-- Include Footer -->
        @include('layouts.footer')
    @endif
@else
    <div class="container">
        <main class="py-4">
            {{-- @yield('content') --}}
        </main>
    </div>
@endauth

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@yield('scripts')

</body>
</html>
