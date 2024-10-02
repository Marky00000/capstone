@extends('layouts.app')

@section('title')
    My Projects
@endsection
<title>Arfil's Landscaping Services</title>
<link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
@section('content')

    <div class="card shadow-sm rounded-lg border-1">
        <div class="card-header stylish-header text-black">
            <h1>Projects</h1>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Filter and Sort Form -->
            <form action="{{ route('project.adminIndex') }}" method="GET" class="mb-4">
                <div class="d-flex align-items-center"> <!-- Use flexbox for closer alignment -->

                    <!-- Project Status Filter -->
                    <div class="me-2"> <!-- Added margin to the right -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <select name="project_status" class="form-select form-select-sm" style="max-width: 120px;">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('project_status') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="active" {{ request('project_status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="hold" {{ request('project_status') == 'hold' ? 'selected' : '' }}>On Hold
                                </option>
                                <option value="finish" {{ request('project_status') == 'finish' ? 'selected' : '' }}>
                                    Finished</option>
                            </select>
                        </div>
                    </div>

                    <!-- Start Date Filter -->
                    <div class="me-2"> <!-- Added margin to the right -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" name="start_date" class="form-control form-control-sm"
                                value="{{ request('start_date') }}">
                        </div>
                    </div>

                    <!-- End Date Filter -->
                    <div class="me-2"> <!-- Added margin to the right -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" name="end_date" class="form-control form-control-sm"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>




            @if ($projects->isEmpty())
                <p class="text-muted">You do not have any projects at this time.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Booking ID</th>
                                <th>Name</th>
                                <th>Service Name</th>
                                <th>Site Visit Date</th>
                                <th>Address</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Lot Area</th>
                                <th>Discount</th>
                                <th>Total Cost</th>
                                <th>Approved Paid</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td>{{ $project->booking->id }}</td>
                                    <td>{{ $project->booking->name }}</td>
                                    <td>{{ $project->service->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($project->booking->site_visit_date)->format('F j, Y') }}
                                    </td>
                                    <td>{{ $project->booking->address }}</td>
                                    <td>{{ $project->booking->province }}</td>
                                    <td>{{ $project->booking->city }}</td>
                                    <td>{{ $project->lot_area }} sqm</td>
                                    <td>{{ $project->discount }}%</td>
                                    <td>₱{{ number_format($project->total_cost, 2) }}</td>
                                    <td>₱{{ number_format($project->total_paid, 2) }}</td>

                                    <td>
                                        <span
                                            class="badge 
                                        @if ($project->project_status == 'pending') badge-warning 
                                        @elseif($project->project_status == 'active') badge-success 
                                        @elseif($project->project_status == 'hold') badge-danger    
                                        @elseif($project->project_status == 'finish') badge-primary @endif">
                                            @if ($project->project_status == 'pending')
                                                <i class="fas fa-hourglass-half"></i>
                                            @elseif($project->project_status == 'active')
                                                <i class="fas fa-spinner fa-spin"></i>
                                                <!-- Changed icon for active to spinner -->
                                            @elseif($project->project_status == 'hold')
                                                <i class="fas fa-pause-circle"></i>
                                            @elseif($project->project_status == 'finish')
                                                <i class="fas fa-check"></i> <!-- Changed icon for finish to check -->
                                            @endif
                                            {{ ucfirst($project->project_status) }}
                                        </span>
                                    </td>



                                    <td>
                                        <a href="{{ route('project.adminShow', $project->id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>View
                                        </a>

                                        {{-- <a href="{{ route('project.edit', $project->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Edit
                                        </a> --}}


                                        <!-- Show Hold button only if project_status is 'active' -->
                                        @if ($project->project_status === 'active')
                                            <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#holdModal{{ $project->id }}">
                                                <i class="fas fa-pause"></i>Hold
                                            </button>

                                            <!-- Confirmation Modal for Hold -->
                                            <div class="modal fade" id="holdModal{{ $project->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="holdModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="holdModalLabel">Confirm Hold</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to put this project on hold?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('project.hold', $project->id) }}"
                                                                method="POST" id="hold-form-{{ $project->id }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-warning">Confirm
                                                                    Hold</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Show Activate button only if project_status is 'hold' -->
                                        @if ($project->project_status === 'hold')
                                            <button class="btn btn-sm btn-success" data-toggle="modal"
                                                data-target="#activateModal{{ $project->id }}">
                                                <i class="fas fa-check"></i>Active
                                            </button>

                                            <!-- Confirmation Modal for Activate -->
                                            <div class="modal fade" id="activateModal{{ $project->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="activateModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="activateModalLabel">Confirm
                                                                Activation</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to activate this project?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('project.activate', $project->id) }}"
                                                                method="POST" id="activate-form-{{ $project->id }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-success">Confirm
                                                                    Activation</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>



                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="pagination-wrapper">
                {{ $projects->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .custom-light-gray {
            background-color: #e9ecef;
            /* Example of a custom light gray */
        }

        .custom-dropdown {
            height: 35px;
            /* Adjust height as needed */
            font-size: 0.9rem;
            /* Adjust font size if needed */
        }


        /* General Card Styles */
        .card {
            border-radius: 12px;
            border: none;
            overflow: hidden;
        }

        .card-header {
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
            font-size: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
        }

        .table tbody tr:hover {
            background-color: #f1f3f5;
        }

        /* Pricing Factors Styles */
        .pricing-factors {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            background-color: #f9f9f9;
        }

        .pricing-factors h5 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .pricing-factors p {
            margin-bottom: 0.5rem;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .modal-header {
            border-bottom: none;
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0;
            background-color: #007bff;
            color: #ffffff;
        }

        .modal-body {
            padding: 1.5rem;
            background-color: #f9f9f9;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: none;
            background-color: #f9f9f9;
            border-radius: 0 0 12px 12px;
        }

        .pagination-wrapper {
            margin-top: 20px;
            text-align: center;
        }

        .pagination-wrapper .pagination {
            display: inline-flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination-wrapper .page-item {
            margin: 0 2px;
        }

        .pagination-wrapper .page-link {
            padding: 10px 15px;
            border-radius: 8px;
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
        }

        .pagination-wrapper .page-link:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn {
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-info {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-light {
            background-color: #f8f9fa;
            color: #007bff;
            border: 1px solid #007bff;
        }

        .btn-light:hover {
            background-color: #e2e6ea;
        }
    </style>
@endsection
