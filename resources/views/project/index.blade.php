@extends('layouts.app')

@section('title')
My Projects
@endsection

@section('contents')
<div class="pricing-factors mb-4">
    <h5>Project Overview</h5>
    <p>Below is a list of all your projects, including their details and current status.</p>
</div>

<div class="card shadow-sm rounded-lg border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h4 class="mb-0">My Projects</h4>
        <div>
            <!-- Add "Back" button -->
            <a href="{{ route('welcome') }}" class="btn btn-light text-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($projects->isEmpty())
            <p class="text-muted">You do not have any projects at this time.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Booking ID</th>
                            <th>Service ID</th>
                            <th>Lot Area</th>
                            <th>Total Cost</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td>{{ $project->id }}</td>
                                <td>{{ $project->booking_id }}</td>
                                <td>{{ $project->service_id }}</td>
                                <td>{{ $project->lot_area }}</td>
                                <td>${{ number_format($project->total_cost, 2) }}</td>
                                <td>{{ $project->description }}</td>
                                <td>{{ ucfirst($project->project_status) }}</td>
                                <td>
                                    <button 
                                        class="btn btn-outline-info btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#projectModal"
                                        data-id="{{ $project->id }}"
                                        data-booking_id="{{ $project->booking_id }}"
                                        data-service_id="{{ $project->service_id }}"
                                        data-lot_area="{{ $project->lot_area }}"
                                        data-total_cost="{{ $project->total_cost }}"
                                        data-description="{{ $project->description }}"
                                        data-project_status="{{ $project->project_status }}">
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
<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="projectModalLabel">Project Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="receipt-details">
                    <p><strong>ID:</strong> <span id="modalId"></span></p>
                    <p><strong>Booking ID:</strong> <span id="modalBookingId"></span></p>
                    <p><strong>Service ID:</strong> <span id="modalServiceId"></span></p>
                    <p><strong>Lot Area:</strong> <span id="modalLotArea"></span></p>
                    <p><strong>Total Cost:</strong> $<span id="modalTotalCost"></span></p>
                    <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
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

    .btn-primary {
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
    $('#projectModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('#modalId').text(button.data('id'));
        modal.find('#modalBookingId').text(button.data('booking_id'));
        modal.find('#modalServiceId').text(button.data('service_id'));
        modal.find('#modalLotArea').text(button.data('lot_area'));
        modal.find('#modalTotalCost').text(button.data('total_cost'));
        modal.find('#modalDescription').text(button.data('description'));
        modal.find('#modalStatus').text(button.data('project_status'));
    });
</script>
@endsection
