<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <div class="container-fluid">
        <!-- Logo positioned to the far left -->
        @auth
            @if(Auth::user()->usertype != 'super_admin' && Auth::user()->usertype != 'admin')
                <a class="navbar-brand mr-auto" href="{{ route('welcome') }}" style="display: flex; align-items: center;">
                    <img src="{{ asset('arfil_logo1.png') }}" alt="Arfil's Logo" style="max-width: 55px; margin-right: 10px;">
                    <span>Arfil's Landscaping and Swimmingpool Services</span>
                </a>
            @endif
        @endauth

        <!-- Navbar items that should stay in place -->
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                @auth
                    @if(Auth::user()->usertype != 'super_admin' && Auth::user()->usertype != 'admin')
                        <!-- About -->
                        <li class="nav-item mr-4">
                            <a class="nav-link text-dark" href="#about">About</a>
                        </li>
                    
                        <li class="nav-item dropdown mr-4">
                            <a class="nav-link dropdown-toggle text-dark" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Services
                            </a>
                            <div class="dropdown-menu" aria-labelledby="servicesDropdown">
                                <a class="dropdown-item" href="{{ route('services.byCategory', ['category' => 'landscaping']) }}">Landscaping</a>
                                <a class="dropdown-item" href="{{ route('services.byCategory', ['category' => 'swimmingpool']) }}">Swimming Pool</a>
                                <a class="dropdown-item" href="{{ route('services.byCategory', ['category' => 'renovation']) }}">Renovation</a>
                                <a class="dropdown-item" href="{{ route('services.byCategory', ['category' => 'maintenance']) }}">Maintenance</a>
                            </div>
                        </li>
                        
                        <!-- Contact -->
                        <li class="nav-item mr-4">
                            <a class="nav-link text-dark" href="#contact">Contact</a>
                        </li>

                        <!-- Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">0</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header bg-info">Alerts Center</h6>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>
                    @endif
                @endauth

                <!-- Nav Item - User Dropdown -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @auth
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                        @endauth
                        <img class="img-profile rounded-circle" src="{{ asset('man.png') }}">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        @auth
                            @if(Auth::user()->usertype == 'super_admin' || Auth::user()->usertype == 'admin')
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @else
                                <a class="dropdown-item" href="{{ route('quotation.view') }}">
                                    <i class="fas fa-file-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    My Quotations
                                </a>
                                <a class="dropdown-item" href="{{ route('booking.index') }}">
                                    <i class="fas fa-calendar-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    My Bookings
                                </a>
                                {{-- <a class="dropdown-item" href="{{ route('project.index') }}">
                                    <i class="fas fa-briefcase fa-sm fa-fw mr-2 text-gray-400"></i>
                                    My Projects
                                </a> --}}
    
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @endif
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
        </div>
    </div>
</nav>
