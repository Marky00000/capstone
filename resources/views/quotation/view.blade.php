@extends('layouts.app')

@section('title', 'Quotations')

@section('content')
<div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h4 class="mb-0">My Quotations</h4>
        <div>
            <a href="{{ route('quotation.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Quotation
            </a>
            <a href="{{ route('welcome') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($quotations->isEmpty())
            <p class="text-muted">You do not have any quotations at this time. Please contact us to request a quotation.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Region</th>
                            <th>Lot Area</th>
                            <th>Service ID</th>
                            <th>Total Amount</th>
                            <th>Working Days</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotations as $quotation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $quotation->address }}</td>
                                <td>{{ $quotation->city }}</td>
                                <td>{{ $quotation->region }}</td>
                                <td>{{ $quotation->lot_area }}</td>
                                <td>{{ $quotation->service_id }}</td>
                                <td>{{ number_format($quotation->amount, 2) }}</td>
                                <td>{{ $quotation->working_days }}</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-primary" 
                                        data-toggle="modal" 
                                        data-target="#quotationModal"
                                        data-id="{{ $quotation->id }}"
                                        data-address="{{ $quotation->address }}"
                                        data-city="{{ $quotation->city }}"
                                        data-region="{{ $quotation->region }}"
                                        data-lot_area="{{ $quotation->lot_area }}"
                                        data-service_id="{{ $quotation->service_id }}"
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
<div class="modal fade" id="quotationModal" tabindex="-1" role="dialog" aria-labelledby="quotationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="quotationModalLabel">Quotation Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="receipt-details">
                    <p><strong>ID:</strong> <span id="modalId"></span></p>
                    <p><strong>Address:</strong> <span id="modalAddress"></span></p>
                    <p><strong>City:</strong> <span id="modalCity"></span></p>
                    <p><strong>Region:</strong> <span id="modalRegion"></span></p>
                    <p><strong>Lot Area:</strong> <span id="modalLotArea"></span></p>
                    <p><strong>Service ID:</strong> <span id="modalServiceId"></span></p>
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

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-primary {
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
    $('#quotationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id'); // Extract primary from data-* attributes
        var address = button.data('address');
        var city = button.data('city');
        var region = button.data('region');
        var lotArea = button.data('lot_area');
        var serviceId = button.data('service_id');
        var amount = button.data('amount');
        var workingDays = button.data('working_days');

        // Update the modal's content.
        var modal = $(this);
        
        modal.find('#modalId').text(id);
        modal.find('#modalAddress').text(address);
        modal.find('#modalCity').text(city);
        modal.find('#modalRegion').text(region);
        modal.find('#modalLotArea').text(lotArea);
        modal.find('#modalServiceId').text(serviceId);
        modal.find('#modalAmount').text(amount);
        modal.find('#modalWorkingDays').text(workingDays);
    });
</script>
@endsection
