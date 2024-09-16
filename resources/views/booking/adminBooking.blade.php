@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($bookings->isEmpty())
            <p class="text-muted">You do not have any bookings at this time. Please contact us to make a booking.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Site Visit Date</th>
                            <th>Address</th>
                            <th>Province</th>
                            <th>City</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->user->name ?? 'Unknown' }}</td>
                                <td>{{ $booking->site_visit_date ? (new DateTime($booking->site_visit_date))->format('Y-m-d') : 'Not Set' }}</td>
                                <td>{{ $booking->address }}</td>
                                <td>{{ $booking->province }}</td>
                                <td>{{ $booking->city }}</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-info" 
                                        data-toggle="modal" 
                                        data-target="#bookingModal"
                                        data-id="{{ $booking->id }}"
                                        data-site_visit_date="{{ $booking->site_visit_date }}"
                                        data-user="{{ $booking->user->name ?? 'Unknown' }}"
                                        data-address="{{ $booking->address }}"
                                        data-province="{{ $booking->province }}"
                                        data-city="{{ $booking->city }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    
                                    <!-- Add Project Button -->
                                    <a href="{{ route('projects.create', ['booking_id' => $booking->id]) }}"
                                        class="btn btn-sm btn-success add-project-button"
                                        data-booking-id="{{ $booking->id }}"
                                        data-project-count="{{ $booking->projects()->count() }}">
                                        <i class="fas fa-plus"></i> Add Project
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination Controls -->
            <div class="pagination-wrapper">
                {{ $bookings->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="receipt-details">
                    <p><strong>ID:</strong> <span id="modalId"></span></p>
                    <p><strong>Site Visit Date:</strong> <span id="modalSiteVisitDate"></span></p>
                    <p><strong>User:</strong> <span id="modalUser"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddress"></span></p>
                    <p><strong>Province:</strong> <span id="modalProvince"></span></p>
                    <p><strong>City:</strong> <span id="modalCity"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Limit Modal -->
<div class="modal fade" id="limitModal" tabindex="-1" role="dialog" aria-labelledby="limitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="limitModalLabel">Limit Reached</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The booking already has 2 projects. You cannot add more projects.</p>
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
    .card-header {
        background: linear-gradient(135deg, #4CAF50, #81C784); /* Green Gradient */
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header h3 {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
    }

    .card-tools .btn-primary {
        background-color: #4CAF50;
        border-color: #4CAF50;
    }

    .card-body {
        padding: 20px;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background-color: #ffffff; /* White background for table header */
        color: #17a2b8; /* Bootstrap 'info' color for text */
        text-align: center;
        font-weight: bold;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .table tbody tr:nth-child(even) {
        background-color: #ffffff;
    }

    .table td, .table th {
        padding: 15px;
        vertical-align: middle;
        text-align: center;
    }

    .table img {
        border-radius: 5px;
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
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        border: 1px solid #007bff;
    }

    .pagination-wrapper .page-link:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .modal-content {
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .modal-body {
        padding: 20px;
    }

    .btn-info, .btn-primary, .btn-warning, .btn-success {
        padding: 5px 10px; /* Adjust button padding */
    }
</style>
@endsection


@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Bootstrap tooltips (not used in this case)
        // $('[data-toggle="tooltip"]').tooltip();

        $('#bookingModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            var siteVisitDate = button.data('site_visit_date');
            var user = button.data('user');
            var address = button.data('address');
            var province = button.data('province');
            var city = button.data('city');

            // Update the modal's content.
            var modal = $(this);
            
            modal.find('#modalId').text(id);
            modal.find('#modalSiteVisitDate').text(siteVisitDate ? (new Date(siteVisitDate)).toISOString().split('T')[0] : 'Not Set');
            modal.find('#modalUser').text(user);
            modal.find('#modalAddress').text(address);
            modal.find('#modalProvince').text(province);
            modal.find('#modalCity').text(city);
        });

        // Handle Add Project button click
        $('.add-project-button').on('click', function(e) {
            var button = $(this);
            var projectCount = parseInt(button.attr('data-project-count'), 10); // Get the project count from a data attribute
            var bookingId = button.attr('data-booking-id'); // Get the booking ID from a data attribute

            if (projectCount >= 2) {
                e.preventDefault(); // Prevent default action
                $('#limitModal').modal('show'); // Show the limit modal
            } else {
                // Redirect to the project creation page
                window.location.href = "{{ route('projects.create') }}?booking_id=" + bookingId;
            }
        });
    });
</script>
@endsection
