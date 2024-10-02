@extends('layouts.app')

@section('title', 'Bookings')
<title>Arfil's Landscaping Services</title>
<link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <div class="pricing-factors mb-4">
        <h5>Booking Overview</h5>
        <p>Below is a list of all your Booking, including their details and current status.</p>
    </div>
    <div class="card shadow-sm rounded-lg border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
            <h4 class="mb-0">My bookings</h4>
            <div>
                <a href="{{ route('booking.form') }}" class="btn btn-light text-info btn-sm">
                    <i class="fas fa-calendar-check"></i> Make a Booking
                </a>
                <a href="{{ route('welcome') }}" class="btn btn-light text-info btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
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
                                <th><i class="fas fa-numeric icon-faded-gray"></i> #</th>
                                <th><i class="fas fa-user icon-faded-gray"></i> Name</th>
                                <th><i class="fas fa-phone-alt icon-faded-gray"></i> Contact</th>
                                <th><i class="fas fa-envelope icon-faded-gray"></i> Email</th>
                                <th><i class="fas fa-map-marker-alt icon-faded-gray"></i> Address</th>
                                <th><i class="fas fa-map"></i> Province</th>
                                <th><i class="fas fa-city icon-faded-gray"></i> City</th>
                                <th><i class="fas fa-calendar-alt icon-faded-gray"></i> Site Visit Date</th>
                                <th><i class="fas fa-clock icon-faded-gray"></i> Booking Status</th>
                                <th><i class="fas fa-cogs icon-faded-gray"></i> Actions</th>
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
                                        @elseif($booking->booking_status == 'declined') badge-secondary 
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

                                        <a href="{{ route('booking.view', $booking->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>

                                        <!-- Add Edit Button -->
                                        <a href="{{ route('booking.edit', $booking->id) }}" class="btn btn-warning btn-sm"
                                            @if ($booking->booking_status !== 'pending') style="display: none;" @endif>
                                            <i class="fas fa-pencil-alt"></i> Edit

                                        </a>

                                        <button type="button" class="btn btn-sm btn-secondary cancel-button"
                                            data-toggle="modal" data-target="#cancelModal"
                                            data-booking_id="{{ $booking->id }}"
                                            @if (
                                                $booking->booking_status === 'visited' ||
                                                    $booking->booking_status === 'cancelled' ||
                                                    $booking->booking_status === 'confirmed') style="display: none;" @endif>
                                            <i class="fas fa-times"></i> Cancel
                                        </button>


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>



    <!-- Cancel Booking Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Cancel Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel this booking?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="confirmCancelButton" class="btn btn-danger">Yes, Cancel</button>
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
                modal.find('#confirmBookingButton').data('booking_id', bookingDetails.bookingId);

            });

            // Show the cancel confirmation modal
            $('#cancelModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const bookingId = button.data('booking_id');
                $(this).data('booking_id', bookingId); // Store booking ID for confirmation
            });

            // Handle booking cancellation
            $('#confirmCancelButton').on('click', function() {
                const bookingId = $('#cancelModal').data('booking_id');

                // Make an AJAX request to cancel the booking
                $.ajax({
                    url: `/bookings/${bookingId}/cancel`, // Adjust this route according to your application
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr(
                            'content') // CSRF token for security
                    },
                    success: function(response) {
                        $('#cancelModal').modal('hide');
                        location.reload(); // Reload the page to reflect changes

                        // Hide the cancel button for the specific booking
                        const bookingStatus = response
                            .booking_status; // Assuming the response contains booking status
                        if (bookingStatus === 'visited') {
                            $(`button[data-booking_id="${bookingId}"]`)
                                .hide(); // Hide if visited
                        }
                    },
                    error: function(xhr) {
                        // Improved error handling
                        const errorMessage = xhr.responseJSON?.message ||
                            'Error canceling booking.';
                        alert(errorMessage);
                    }
                });
            });

            // Helper function to get the badge class based on booking status
            function getBadgeClass(status) {
                switch (status) {
                    case 'pending':
                        return 'badge-warning';
                    case 'cancelled':
                        return 'badge-danger';
                    case 'confirmed':
                        return 'badge-primary'; // Fixed typo here
                    case 'visited':
                        return 'badge-success'; // Corrected mapping here
                    default:
                        return 'badge-secondary';
                }
            }

            // Helper function to capitalize the first letter of a string
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        });
    </script>
@endsection
