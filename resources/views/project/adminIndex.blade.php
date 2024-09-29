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
                                    <td>{{ ($project->discount) }}%</td>
                                    <td>₱{{ number_format($project->total_cost, 2) }}</td>
                                    <td>₱{{ number_format($project->total_paid, 2) }}</td>

                                    <td>
                                        <span class="badge 
                                        @if ($project->project_status == 'pending') badge-warning 
                                        @elseif($project->project_status == 'active') badge-success 
                                        @elseif($project->project_status == 'hold') badge-danger    
                                        @elseif($project->project_status == 'finish') badge-primary @endif">
                                            @if ($project->project_status == 'pending')
                                                <i class="fas fa-hourglass-half"></i> 
                                            @elseif($project->project_status == 'active')
                                            <i class="fas fa-spinner fa-spin"></i>  <!-- Changed icon for active to spinner -->
                                            @elseif($project->project_status == 'hold')
                                                <i class="fas fa-pause-circle"></i> 
                                            @elseif($project->project_status == 'finish')
                                                <i class="fas fa-check"></i>  <!-- Changed icon for finish to check -->
                                            @endif
                                            {{ ucfirst($project->project_status) }}
                                        </span>
                                    </td>
                                    
                                    

                                    <td>
                                        <button class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#projectModal" data-id="{{ $project->id }}"
                                            data-booking_id="{{ $project->booking->id }}"
                                            data-booking_name="{{ $project->booking->name }}"
                                            data-service_name="{{ $project->service->name }}"
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
                        <p><strong>Booking ID: </strong> <span id="modalBookingId"></span></p>
                        <p><strong>Customer: </strong> <span id="modalBookingName"></span></p>
                        <p><strong>Service Name: </strong> <span id="modalServiceName"></span></p>
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
        .custom-light-gray {
            background-color: #e9ecef;
            /* Example of a custom light gray */
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


@section('scripts')
    <script>
        $('#projectModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var bookingId = button.data('booking_id');
            var bookingName = button.data('booking_name');
            var serviceName = button.data('service_name');
            var lotArea = button.data('lot_area');
            var totalCost = parseFloat(button.data('total_cost')); // Ensure total_cost is treated as a float
            var projectStatus = button.data('project_status');
            var siteVisitDate = button.data('site_visit_date');
            var address = button.data('address');
            var province = button.data('province');
            var city = button.data('city');

            var modal = $(this);
            modal.find('#modalBookingId').text(bookingId);
            modal.find('#modalBookingName').text(bookingName);
            modal.find('#modalServiceName').text(serviceName);
            modal.find('#modalSiteVisitDate').text(moment(siteVisitDate).format('MMMM D, YYYY'));
            modal.find('#modalAddress').text(address);
            modal.find('#modalProvince').text(province);
            modal.find('#modalCity').text(city);
            modal.find('#modalLotArea').text(lotArea + ' sqm');
            modal.find('#modalTotalCost').text('₱' + totalCost.toLocaleString('en-US'));

            var statusBadge = modal.find('#modalStatus');
            statusBadge.removeClass('badge-warning badge-success badge-danger badge-primary');
            statusBadge.addClass(getStatusBadgeClass(projectStatus));
            statusBadge.text(projectStatus.charAt(0).toUpperCase() + projectStatus.slice(1));
        });

        function getStatusBadgeClass(status) {
            switch (status) {
                case 'pending':
                    return 'badge-warning';
                case 'active':
                    return 'badge-success';
                case 'hold':
                    return 'badge-danger';
                case 'finish':
                    return 'badge-primary';
                default:
                    return '';
            }
        }
    </script>
@endsection
