@extends('layouts.app')

@section('title', 'Bookings')
<title>Arfil's Landscaping Services</title>
<link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
@section('content')
    <div class="pricing-factors mb-4">
        <h5>Booking Overview</h5>
        <p>Below is a list of all your Booking, including their details and current status.</p>
    </div>
    <div class="card shadow-sm rounded-lg border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
            <h4 class="mb-0">Bookings</h4>
            <div>
                <a href="{{ route('booking.form') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-calendar-check"></i> Make a Booking
                </a>
                <a href="{{ route('welcome') }}" class="btn btn-info btn-sm">
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
                                <th>#</th>
                                <th>Name</th>
                                <th>Contact</th> <!-- New Contact Header -->
                                <th>Email</th>   <!-- New Email Header -->
                                <th>Address</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Site Visit Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $booking->name }}</td>
                                    <td>{{ $booking->contact }}</td> <!-- Display Contact -->
                                    <td>{{ $booking->email }}</td>   <!-- Display Email -->
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
                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#bookingModal"
                                            data-site_visit_date="{{ $booking->site_visit_date }}"
                                            data-user_id="{{ $booking->user_id }}" data-name="{{ $booking->name }}"
                                            data-address="{{ $booking->address }}" data-province="{{ $booking->province }}"
                                            data-city="{{ $booking->city }}" data-contact="{{ $booking->contact }}"
                                            data-email="{{ $booking->email }}">
                                            <i class="fas fa-eye"></i> View
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
                        <p><strong>Name: </strong> <span id="modalName"></span></p> <!-- Name Field -->
                        <p><strong>Contact: </strong> <span id="modalContact"></span></p> <!-- Contact Field -->
                        <p><strong>Email: </strong> <span id="modalEmail"></span></p>      <!-- Email Field -->
                        <p><strong>Address: </strong> <span id="modalAddress"></span></p>
                        <p><strong>Province: </strong> <span id="modalProvince"></span></p>
                        <p><strong>City: </strong> <span id="modalCity"></span></p>
                        <p><strong>Site Visit Date: </strong> <span id="modalSiteVisitDate"></span></p>
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
    </style>
@endsection

@section('scripts')
    <script>
        $('#bookingModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var siteVisitDate = button.data('site_visit_date');
            var userId = button.data('user_id');
            var name = button.data('name'); // New name data
            var address = button.data('address');
            var province = button.data('province');
            var city = button.data('city');
            var contact = button.data('contact'); // New contact data
            var email = button.data('email');     // New email data

            // Update the modal's content.
            var modal = $(this);

            modal.find('#modalSiteVisitDate').text(siteVisitDate ? (new Date(siteVisitDate)).toLocaleDateString(
                'en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) : 'Not Set');

            modal.find('#modalName').text(name);          // Update name field
            modal.find('#modalContact').text(contact);     // Update contact field
            modal.find('#modalEmail').text(email);         // Update email field
            modal.find('#modalAddress').text(address);     // Update address field
            modal.find('#modalProvince').text(province);   // Update province field
            modal.find('#modalCity').text(city);           // Update city field
        });
    </script>
@endsection
