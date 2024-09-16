@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<div class="card shadow-lg">
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
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($bookings->isEmpty())
            <p class="text-muted">You do not have any bookings at this time. Please contact us to make a booking.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>name</th>
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
                                <td>{{ ($booking->user_id) }}</td>
                                <td>{{ ($booking->name) }}</td>
                                <td>
                                    @if ($booking->site_visit_date)
                                        {{ (new DateTime($booking->site_visit_date))->format('Y-m-d') }}
                                    @else
                                        Not Set
                                    @endif
                                </td>
                                <td>{{ ($booking->address) }}</td>
                                <td>{{ ($booking->province) }}</td>
                                <td>{{ ($booking->city) }}</td>

                                <td>
                                    <button 
                                        class="btn btn-sm btn-info" 
                                        data-toggle="modal" 
                                        data-target="#bookingModal"
                                        data-id="{{ $booking->id }}"
                                        data-service="{{ ucfirst($booking->service) }}"
                                        data-site_visit_date="{{ $booking->site_visit_date }}"
                                        data-user_id="{{ $booking->user_id }}"
                                        data-address="{{ $booking->address }}"
                                        data-province="{{ $booking->province }}"
                                        data-city="{{ $booking->city }}">
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
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="receipt-details">
                    <p><strong>ID:</strong> <span id="modalId"></span></p>
                    <p><strong>Service:</strong> <span id="modalService"></span></p>
                    <p><strong>Site Visit Date:</strong> <span id="modalSiteVisitDate"></span></p>
                    <p><strong>User ID:</strong> <span id="modalUserId"></span></p>
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
        background-color: #ffffff;
    }

    .modal-header {
        border-bottom: none;
        padding: 1rem 1.5rem;
        border-radius: 15px 15px 0 0;
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
        border-radius: 0 0 15px 15px;
    }

    /* Button Styles */
    .btn {
        border-radius: 50px;
        transition: all 0.3s ease;
    }


    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-light {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
        color: #212529;
    }

    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }

    /* Modal Close Button */
    .close {
        font-size: 1.25rem;
        line-height: 1;
        color: #fff;
        opacity: 1;
    }

    .close:hover, .close:focus {
        color: #fff;
        text-decoration: none;
        opacity: 0.75;
    }
</style>
@endsection

@section('scripts')
<script>
    $('#bookingModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id'); // Extract info from data-* attributes
        var service = button.data('service');
        var siteVisitDate = button.data('site_visit_date');
        var userId = button.data('user_id');
        var address = button.data('address');
        var province = button.data('province');
        var city = button.data('city');

        // Update the modal's content.
        var modal = $(this);
        
        modal.find('#modalId').text(id);
        modal.find('#modalService').text(service);
        modal.find('#modalSiteVisitDate').text(siteVisitDate ? (new Date(siteVisitDate)).toISOString().split('T')[0] : 'Not Set');
        modal.find('#modalUserId').text(userId);
        modal.find('#modalAddress').text(address);
        modal.find('#modalProvince').text(province);
        modal.find('#modalCity').text(city);
    });
</script>
@endsection
