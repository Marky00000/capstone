@extends('layouts.app')

@section('title', 'Bookings')
<title>Arfil's Landscaping Services</title>
<link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')

    <div class="card shadow-sm rounded-lg border-0">
        <div class="card-header stylish-header text-black">
            <h1>Bookings</h1>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($bookings->isEmpty())
                <p class="text-muted">You do not have any bookings at this time. Please contact us to make a booking.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Site Visit Date</th>
                                <th>Booking Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $booking->name }}</td>
                                    <td>{{ $booking->contact }}</td>
                                    <td>{{ $booking->email }}</td>
                                    <td>{{ $booking->address }}</td>
                                    <td>{{ $booking->province }}</td>
                                    <td>{{ $booking->city }}</td>
                                    <td>
                                        @if ($booking->site_visit_date)
                                            {{ (new DateTime($booking->site_visit_date))->format('F j, Y') }}
                                        @else
                                            No site visit scheduled.
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge 
                                    @if ($booking->booking_status == 'pending') badge-warning 
                                    @elseif($booking->booking_status == 'confirmed') badge-primary 
                                    @elseif($booking->booking_status == 'visited') badge-success 
                                    @elseif($booking->booking_status == 'cancelled') badge-danger 
                                    @elseif($booking->booking_status == 'declined') badge-danger 
                                    @else badge-secondary @endif">

                                            @if ($booking->booking_status == 'pending')
                                                <i class="fas fa-hourglass-half"></i>
                                            @elseif($booking->booking_status == 'confirmed')
                                                <i class="fas fa-check-circle"></i>
                                            @elseif($booking->booking_status == 'visited')
                                                <i class="fas fa-check-double"></i>
                                            @elseif($booking->booking_status == 'cancelled')
                                                <i class="fas fa-times-circle"></i>
                                            @elseif($booking->booking_status == 'declined')
                                                <i class="fas fa-ban"></i> <!-- Icon for declined status -->
                                            @endif

                                            {{ ucfirst($booking->booking_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <!-- Existing View Button -->
                                            <button class="btn btn-sm btn-info btn-sm me-2" data-toggle="modal"
                                                data-target="#bookingModal"
                                                data-site_visit_date="{{ $booking->site_visit_date }}"
                                                data-user_id="{{ $booking->user_id }}" data-name="{{ $booking->name }}"
                                                data-address="{{ $booking->address }}"
                                                data-province="{{ $booking->province }}" data-city="{{ $booking->city }}"
                                                data-contact="{{ $booking->contact }}" data-email="{{ $booking->email }}"
                                                data-booking_status="{{ $booking->booking_status }}">
                                                <i class="fas fa-eye"></i> View
                                            </button>

                                            <!-- Confirm Booking Button -->
                                            @if ($booking->booking_status === 'pending')
                                                <button type="button" class="btn btn-sm btn-info confirm-booking-button"
                                                    data-toggle="modal" data-target="#confirmModal"
                                                    data-booking_id="{{ $booking->id }}">
                                                    <i class="fas fa-check-circle"></i> Confirm
                                                </button>

                                                <!-- Decline Booking Button -->
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-sm me-2 decline-booking-button"
                                                    data-toggle="modal" data-target="#declineModal"
                                                    data-booking_id="{{ $booking->id }}">
                                                    <i class="fas fa-times-circle"></i> Decline
                                                </button>
                                            @endif

                                            <!-- Make Project Button -->
                                            @if ($booking->booking_status === 'confirmed' && $booking->projects->count() < 3)
                                                <a href="{{ route('projects.create', ['booking_id' => $booking->id]) }}"
                                                    class="btn btn-sm btn-info btn-sm me-2">
                                                    <i class="fas fa-plus-circle"></i> Make Project
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white flex-column align-items-center"
                    style="max-height: 80px; margin-bottom: 10px;">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('arfil_logo1.png') }}" alt="Logo" class="img-fluid logo"
                            style="max-height: 50px; margin-right: 10px;">
                        <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="receipt-details">
                        <p><strong>Name: </strong> <span id="modalName"></span></p>
                        <p><strong>Contact: </strong> <span id="modalContact"></span></p>
                        <p><strong>Email: </strong> <span id="modalEmail"></span></p>
                        <p><strong>Address: </strong> <span id="modalAddress"></span></p>
                        <p><strong>Province: </strong> <span id="modalProvince"></span></p>
                        <p><strong>City: </strong> <span id="modalCity"></span></p>
                        <p><strong>Site Visit Date: </strong> <span id="modalSiteVisitDate"></span></p>
                        <p><strong>Booking Status: </strong> <span id="modalBookingStatus"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to confirm this booking?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmActionButton">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Decline Confirmation Modal -->
    <div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="declineModalLabel">Decline Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to decline this booking?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="declineActionButton">Decline</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('styles')
    <style>
        /* General Card Styles */
        .card {
            border-radius: 8px;
            border: none;
        }

        .card-header {
            border-bottom: none;
            padding: 1rem 1.5rem;
            font-size: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table {
            margin-bottom: 0;
            border: none;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: #f1f3f5;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .modal-header {
            border-bottom: none;
            padding: 1rem 1.5rem;
            background-color: #007bff;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-title {
            flex: 1;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 10px 0 0;
        }

        .modal-body {
            padding: 2rem;
            background-color: #f9f9f9;
            animation: fadeIn 0.5s ease-in-out;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: none;
            background-color: #f9f9f9;
            border-radius: 0 0 15px 15px;
        }

        /* Button Styles */
        .btn {
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-info {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        /* Pagination Styles */
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

        /* Modal Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Pricing Factors Styles */
        .pricing-factors {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .logo {
            max-height: 50px;
        }
    </style>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('scripts')
    <script>
        $(document).ready(function() {
            // Show the booking details in the modal when clicking the "View" button
            $('#bookingModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const bookingDetails = {
                    name: button.data('name'),
                    contact: button.data('contact'),
                    email: button.data('email'),
                    address: button.data('address'),
                    province: button.data('province'),
                    city: button.data('city'),
                    siteVisitDate: button.data('site_visit_date'),
                    bookingStatus: button.data('booking_status'),
                    bookingId: button.data('booking_id') // Store booking ID for later use
                };

                const modal = $(this);
                modal.find('#modalName').text(bookingDetails.name);
                modal.find('#modalContact').text(bookingDetails.contact);
                modal.find('#modalEmail').text(bookingDetails.email);
                modal.find('#modalAddress').text(bookingDetails.address);
                modal.find('#modalProvince').text(bookingDetails.province);
                modal.find('#modalCity').text(bookingDetails.city);
                modal.find('#modalSiteVisitDate').text(
                    bookingDetails.siteVisitDate ?
                    new Date(bookingDetails.siteVisitDate).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) :
                    'No site visit scheduled.'
                );

                // Set the booking status badge class and text
                const bookingBadgeClass = getBadgeClass(bookingDetails.bookingStatus);
                modal.find('#modalBookingStatus')
                    .text(capitalizeFirstLetter(bookingDetails.bookingStatus))
                    .removeClass()
                    .addClass('badge ' + bookingBadgeClass);

                // Store the booking ID in the confirm and decline buttons
                $('#confirmActionButton').data('booking_id', bookingDetails.bookingId);
                $('#declineActionButton').data('booking_id', bookingDetails.bookingId);
            });

            // Handle booking confirmation
            $('#confirmModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const bookingId = button.data('booking_id');

                // Set the booking ID for confirmation
                $('#confirmActionButton').data('booking_id', bookingId);
            });

            // Confirm booking action
            $('#confirmActionButton').click(function() {
                const bookingId = $(this).data('booking_id');

                $.ajax({
                    url: '/bookings/' + bookingId +
                    '/confirm', // Adjust the URL based on your route
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        $('#confirmModal').modal('hide');
                        location.reload(); // Reload the page to display session message
                    },
                    error: function(xhr) {
                        alert('Error confirming booking: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Handle booking decline
            $('#declineModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const bookingId = button.data('booking_id');

                // Set the booking ID for decline action
                $('#declineActionButton').data('booking_id', bookingId);
            });

            // Decline booking action
            $('#declineActionButton').click(function() {
                const bookingId = $(this).data('booking_id');

                $.ajax({
                    url: '/bookings/' + bookingId +
                    '/decline', // Adjust the URL based on your route
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        $('#declineModal').modal('hide');
                        location.reload(); // Reload the page to display session message
                    },
                    error: function(xhr) {
                        alert('Error declining booking: ' + xhr.responseJSON.message);
                    }
                });
            });
        });

        // Function to get badge class based on booking status
        function getBadgeClass(status) {
            switch (status) {
                case 'pending':
                    return 'badge-warning';
                case 'confirmed':
                    return 'badge-primary';
                case 'visited':
                    return 'badge-success';
                case 'cancelled':
                    return 'badge-danger';
                case 'declined':
                    return 'badge-danger'; // or any other class you want to use for declined
                default:
                    return 'badge-secondary';
            }
        }

        // Function to capitalize the first letter of a string
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    </script>


@endsection
