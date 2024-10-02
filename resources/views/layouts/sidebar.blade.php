@if (auth()->user()->usertype !== 'user')
    <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon rotate-n-15"></div>
            <img src="{{ asset('arfil_logo.png') }}" class="img-fluid" alt="Logo" style="max-width: 140px;">
        </a>

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center"  href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon rotate-n-15"></div>
            <div class="sidebar-brand-text mx-3">Arfil's Admin</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Nav Item - Services -->
        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseServices"
                aria-expanded="false" aria-controls="collapseServices">
                <i class="fas fa-seedling"></i>
                <span>Services</span>
            </a>
            <div id="collapseServices" class="collapse" aria-labelledby="headingServices"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('archive.index') }}">
                        <i class="fas fa-archive"></i> Archived Services
                    </a>
                    <a class="collapse-item" href="{{ route('landscape') }}">
                        <i class="fas fa-tree"></i> Landscaping Services
                    </a>
                    <a class="collapse-item" href="{{ route('swimmingpool') }}">
                        <i class="fas fa-swimmer"></i> Swimming Pool Services
                    </a>
                    <a class="collapse-item" href="{{ route('renovation') }}">
                        <i class="fas fa-tools"></i> Renovation Services
                    </a>
                    <a class="collapse-item" href="{{ route('package') }}">
                        <i class="fas fa-tools"></i> Packages
                    </a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Bookings (Visible for both admin and super_admin) -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('booking.adminBooking') }}">
                <i class="fas fa-project-diagram"></i>
                <span>Bookings</span>
            </a>
        </li>

        @if (auth()->user()->usertype === 'admin')
            <!-- Nav Item - Projects (Visible for admin) -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('project.adminIndex') }}">
                    <i class="fas fa-user-friends"></i>
                    <span>Projects</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Nav Item - Payments (Only for admin) -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.payments.index') }}">
                    <i class="fas fa-cash-register"></i>
                    <span>Payments</span>
                </a>
            </li>

            <!-- Nav Item - Services -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseReports"
                    aria-expanded="false" aria-controls="collapseReports">
                    <i class="fas fa-seedling"></i>
                    <span>Reports</span>
                </a>
                <div id="collapseReports" class="collapse" aria-labelledby="headingServices"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        {{-- <a class="collapse-item"  href="{{ route('reports.projects') }}">
                            <i class="fas fa-archive"></i> Project Reports
                        </a> --}}
                        <a class="collapse-item" href="{{ route('reports.rates') }}">
                            <i class="fas fa-tree"></i> Rates Reports
                        </a>
                    </div>
                </div>
            </li>
        @else
            <!-- Nav Item - Projects (Visible for super_admin) -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('project.adminIndex') }}">
                    <i class="fas fa-user-friends"></i>
                    <span>Projects</span>
                </a>
            </li>
        @endif

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
@endif
