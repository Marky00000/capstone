@extends('layouts.app')

@section('title', 'Quotations')
<title>Arfil's Landscaping Services</title>
<link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
@section('content')
    <div class="pricing-factors mb-4">
        <h5>Quotation Overview</h5>
        <p>Below is a list of all your Quotation, including their details and current status.</p>
    </div>
    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
            <h4 class="mb-0">My Quotations</h4>
            <div>
                <a href="{{ route('quotation.create') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-plus"></i> Add Quotation
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

            @if ($quotations->isEmpty())
                <p class="text-muted">You do not have any quotations at this time. Please contact us to request a quotation.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Address</th>
                                <th>Region</th>
                                <th>City</th>
                                <th>Lot Area</th>
                                <th>Service Name</th>
                                <th>Total Amount</th>
                                <th>Working Days</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotations as $quotation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $quotation->address }}</td>
                                    <td>{{ $quotation->region }}</td>
                                    <td>{{ $quotation->city }}</td>
                                    <td>{{ number_format($quotation->lot_area, 0) }} sqm</td>
                                    <td>{{ $quotation->service->name }}</td>
                                    <td>{{ number_format($quotation->amount, 2) }}</td>
                                    <td>{{ $quotation->working_days }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#quotationModal" data-id="{{ $quotation->id }}"
                                            data-address="{{ $quotation->address }}" data-city="{{ $quotation->city }}"
                                            data-region="{{ $quotation->region }}"
                                            data-lot_area="{{ $quotation->lot_area }}"
                                            data-name="{{ $quotation->service->name }}"
                                            data-amount="{{ $quotation->amount }}"
                                            data-working_days="{{ $quotation->working_days }}">
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
                {{ $quotations->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>




    <!-- Quotation Modal -->
    <div class="modal fade" id="quotationModal" tabindex="-1" role="dialog" aria-labelledby="quotationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content rounded-3 shadow-lg">
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
                        <p><strong>Address:</strong> <span id="modalAddress"></span></p>
                        <p><strong>Region:</strong> <span id="modalRegion"></span></p>
                        <p><strong>City:</strong> <span id="modalCity"></span></p>
                        <p><strong>Lot Area:</strong> <span id="modalLotArea"></span></p>
                        <p><strong>Service Name:</strong> <span id="modalServiceName"></span></p>
                        <p><strong>Total Amount:</strong> <span id="modalAmount"></span></p>
                        <p><strong>Working Days:</strong> <span id="modalWorkingDays"></span></p>
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
            /* Center elements horizontally */
        }


        .modal-title {
            flex: 1;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 10px 0 0;
            /* Add margin to separate from the logo */
            font-size: 1.5rem;
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
    </style>
@endsection

@section('scripts')
    <script>
        $('#quotationModal').on('show.bs.modal', function(event) {

            var button = $(event.relatedTarget);
            var id = button.data('id');
            var address = button.data('address');
            var city = button.data('city');
            var region = button.data('region');
            var lotArea = button.data('lot_area');
            var serviceName = button.data('name');
            var amount = button.data('amount');
            var workingDays = button.data('working_days');

            var modal = $(this);
            modal.find('#modalAddress').text(address);
            modal.find('#modalCity').text(city);
            modal.find('#modalRegion').text(region);
            modal.find('#modalLotArea').text(Math.round(lotArea) + ' sqm');
            modal.find('#modalServiceName').text(serviceName);
            modal.find('#modalAmount').text(formatNumber(amount));
            modal.find('#modalWorkingDays').text(workingDays);
        });

     

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    </script>
@endsection
