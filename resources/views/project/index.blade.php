@extends('layouts.app')

@section('title')
    My Projects
@endsection
<title>Arfil's Landscaping Services</title>
<link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
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
                                <th>ID</th>
                                <th>Booking ID</th>
                                <th>Service Name</th>
                                <th>Site Visit Date</th>
                                <th>Address</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Lot Area</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Actions</th>
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
                                    <td>{{ $project->lot_area }}</td>
                                    <td>₱{{ number_format($project->total_cost, 2) }} </td>
                                    <td>
                                        <span
                                            class="badge 
                                        @if ($project->project_status == 'pending') badge-warning 
                                        @elseif($project->project_status == 'active') badge-success 
                                        @elseif($project->project_status == 'hold') badge-danger    
                                        @elseif($project->project_status == 'finish') badge-primary @endif">
                                            {{ ucfirst($project->project_status) }}
                                        </span>
                                    </td>

                                    <td>
                                        <button class="btn btn-outline-info btn-sm" data-toggle="modal"
                                            data-target="#projectModal" data-id="{{ $project->id }}"
                                            data-booking_id="{{ $project->booking->id }}"
                                            data-service_id="{{ $project->service_id }}"
                                            data-lot_area="{{ $project->lot_area }}"
                                            data-total_cost="{{ $project->total_cost }}"
                                            data-project_status="{{ $project->project_status }}"
                                            data-site_visit_date="{{ $project->booking->site_visit_date }}"
                                            data-address="{{ $project->booking->address }}"
                                            data-province="{{ $project->booking->province }}"
                                            data-city="{{ $project->booking->city }}">
                                            <i class="fas fa-eye"></i> View
                                        </button>
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

    <!-- Project Modal -->
    <div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="projectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white flex-column align-items-center"
                    style="max-height: 80px; margin-bottom: 10px;">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('arfil_logo1.png') }}" alt="Logo" class="img-fluid logo"
                            style="max-height: 50px; margin-right: 10px;">
                        <h5 class="modal-title" id="bookingModalLabel">Project Details</h5>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="receipt-details">
                        <p><strong>ID: </strong> <span id="modalId"></span></p>
                        <p><strong>Booking ID: </strong> <span id="modalBookingId"></span></p>
                        <p><strong>Service ID: </strong> <span id="modalServiceId"></span></p>
                        <p><strong>Site Visit Date: </strong> <span id="modalSiteVisitDate"></span></p>
                        <p><strong>Address: </strong> <span id="modalAddress"></span></p>
                        <p><strong>Province: </strong> <span id="modalProvince"></span></p>
                        <p><strong>City: </strong> <span id="modalCity"></span></p>
                        <p><strong>Lot Area: </strong> <span id="modalLotArea"></span></p>
                        <p><strong>Total Cost: </strong> <span id="modalTotalCost"></span></p>
                        <p><strong>Status: </strong>
                            <span id="modalStatus" class="badge"></span>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
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

            modal.find('#modalStatus').text(project_status.charAt(0).toUpperCase() + project_status.slice(1))
                .removeClass().addClass('badge ' + badgeClass);
        });
    </script>
@endsection
