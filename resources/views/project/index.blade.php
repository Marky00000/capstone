@extends('layouts.app')

@section('title', 'My Projects')

<!-- Move the title and favicon link into the head section -->
@section('head')
    <title>Arfil's Landscaping Services</title>
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
@endsection

@section('content')
    <div class="pricing-factors mb-4">
        <h5>Project Overview</h5>
        <p>Below is a list of all your projects, including their details and current status.</p>
    </div>

    <div class="card shadow-sm rounded-lg border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
            <h4 class="mb-0">My Projects</h4>
            <div>
                <a href="{{ route('welcome') }}" class="btn btn-light text-info btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($projects->isEmpty())
                <p class="text-muted">You do not have any projects at this time.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th><i class="fas fa-numeric icon-faded-gray"></i> #</th>
                                <th><i class="fas fa-book"></i> Booking ID</th>
                                <th><i class="fas fa-concierge-bell"></i> Service Name</th>
                                <th><i class="fas fa-calendar-alt"></i> Site Visit Date</th>
                                <th><i class="fas fa-map-marker-alt"></i> Address</th>
                                <th><i class="fas fa-map"></i> Province</th>
                                <th><i class="fas fa-city"></i> City</th>
                                <th><i class="fas fa-arrows-alt-h icon-faded-gray"></i> Lot Area</th>
                                <th><i class="fas fa-money-bill-wave"></i> Total Cost</th>
                                <th><i class="fas fa-money-bill-wave"></i> Total Paid</th>

                                <th><i class="fas fa-clipboard-check"></i> Status</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td>{{ $project->booking->id }}</td>
                                    <td>{{ $project->service->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($project->booking->site_visit_date)->format('F j, Y') }}
                                    </td>
                                    <td>{{ $project->booking->address }}</td>
                                    <td>{{ $project->booking->province }}</td>
                                    <td>{{ $project->booking->city }}</td>
                                    <td>{{ $project->lot_area }} sqm</td>
                                    <td>₱{{ number_format($project->total_cost, 2) }}</td>
                                    <td>₱{{ number_format($project->total_paid, 2) }}</td>

                                    <td>
                                        @php
                                            // Set the icon class based on the project status
                                            $iconClass = '';
                                            $badgeClass = '';

                                            if ($project->project_status == 'pending') {
                                                $iconClass = 'fas fa-hourglass-start';
                                                $badgeClass = 'badge-warning';
                                            } elseif ($project->project_status == 'active') {
                                                $iconClass = 'fas fa-check-circle';
                                                $badgeClass = 'badge-success';
                                            } elseif ($project->project_status == 'hold') {
                                                $iconClass = 'fas fa-pause-circle';
                                                $badgeClass = 'badge-danger';
                                            } elseif ($project->project_status == 'finish') {
                                                $iconClass = 'fas fa-check-double';
                                                $badgeClass = 'badge-primary';
                                            }
                                        @endphp

                                        <span class="badge {{ $badgeClass }}">
                                            <i class="{{ $iconClass }}"></i>
                                            {{ ucfirst($project->project_status) }}
                                        </span>
                                    </td>

                                    <td>
                                        <a href="{{ route('project.view', $project->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>


                                        @php
                                            $totalCost = $project->total_cost;
                                            $totalPaid = $project->total_paid;
                                            $buttonLabel = '';

                                            // Determine button label based on total_paid
                                            if ($totalPaid < 0.5 * $totalCost) {
                                                $buttonLabel = 'Initial Payment';
                                            } elseif ($totalPaid < 0.75 * $totalCost) {
                                                $buttonLabel = 'Midterm Payment';
                                            } elseif ($totalPaid < $totalCost) {
                                                $buttonLabel = 'Final Payment';
                                            }
                                        @endphp

                                        @if ($totalPaid < $totalCost)
                                            <!-- Show button only if total_paid is less than total_cost -->
                                            <a href="{{ route('pay', ['id' => $project->id]) }}"
                                                class="btn btn-success btn-sm">
                                                <i class="fas fa-dollar-sign"></i> {{ $buttonLabel }}
                                            </a>
                                        @endif


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

@section('scripts')
    <script>
        $('#projectModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            // Extract info from data-* attributes
            var id = button.data('id');
            var booking_id = button.data('booking_id');
            var service_id = button.data('service_id');
            var lot_area = button.data('lot_area');
            var total_cost = button.data('total_cost');
            var project_status = button.data('project_status');
            var site_visit_date = new Date(button.data('site_visit_date')); // Parse date
            var address = button.data('address');
            var province = button.data('province');
            var city = button.data('city');

            // Update the modal's content
            var modal = $(this);
            modal.find('#modalId').text(id);
            modal.find('#modalBookingId').text(booking_id);
            modal.find('#modalServiceId').text(service_id);
            modal.find('#modalSiteVisitDate').text(site_visit_date.toLocaleDateString('en-PH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }));
            modal.find('#modalAddress').text(address);
            modal.find('#modalProvince').text(province);
            modal.find('#modalCity').text(city);
            modal.find('#modalLotArea').text(lot_area);
            modal.find('#modalTotalCost').text('₱' + parseFloat(total_cost).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            // Set the status badge
            var badgeClass = '';
            switch (project_status) {
                case 'pending':
                    badgeClass = 'badge-warning';
                    break;
                case 'active':
                    badgeClass = 'badge-success';
                    break;
                case 'hold':
                    badgeClass = 'badge-danger';
                    break;
                case 'finish':
                    badgeClass = 'badge-primary';
                    break;
            }

            modal.find('#modalStatus').text(project_status.charAt(0).toUpperCase() + project_status.slice(1)).attr(
                'class', 'badge ' + badgeClass);
        });
    </script>
@endsection
