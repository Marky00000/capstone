@extends('layouts.app')

@section('title', 'Project List')

@section('content')
<div class="card">
    <div class="card-header stylish-header">
        <h1>Projects</h1>
    </div>
    <div class="card-body">
       <!-- Display success message -->
@if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <!-- Display error message -->
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif


        @if($projects->isEmpty())
            <p class="text-muted">No projects available at this time.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Booking ID</th>
                            <th>Service</th>
                            <th>Lot Area</th>
                            <th>Total Cost</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $project->booking_id }}</td>
                                <td>{{ $project->service_id }}</td>
                                <td>{{ $project->lot_area }}</td>
                                <td>{{ $project->total_cost }}</td>
                                <td>{{ $project->project_status }}</td>
                                <td>
                                    <!-- View Project Details Button -->
                                    <button 
                                        class="btn btn-sm btn-info" 
                                        data-toggle="modal" 
                                        data-target="#projectModal"
                                        data-id="{{ $project->id }}"
                                        data-booking_id="{{ $project->booking_id }}"
                                        data-service_id="{{ $project->service_id }}"
                                        data-lot_area="{{ $project->lot_area }}"
                                        data-total_cost="{{ $project->total_cost }}"
                                        data-status="{{ ucfirst($project->project_status) }}"
                                        data-designs="{{ json_encode($project->service_id) }}"> <!-- assuming designs is a relationship or attribute -->
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <!-- Add additional action buttons if needed -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination Controls -->
            <div class="pagination-wrapper">
                {{ $projects->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>

<!-- Project Details Modal -->
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
                <p><strong>ID:</strong> <span id="modalId"></span></p>
                <p><strong>Booking ID:</strong> <span id="modalBookingId"></span></p>
                <p><strong>Service:</strong> <span id="modalServiceId"></span></p>
                <p><strong>Lot Area:</strong> <span id="modalLotArea"></span></p>
                <p><strong>Total Cost:</strong> ₱<span id="modalTotalCost"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>

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
        background-color: #007bff;
        color: #fff;
        text-align: center;
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

    .custom-alert {
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        font-size: 16px;
        border: 1px solid transparent;
    }

    .custom-alert.alert-success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .custom-alert.alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    .modal-dialog.modal-lg {
        max-width: 1000px;
    }

    .modal-content {
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .modal-body {
        padding: 20px;
    }

    .btn-info, .btn-primary {
        padding: 5px 10px; /* Adjust button padding */
    }
</style>
@endsection

@section('scripts')
<script>
    $('#projectModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var bookingId = button.data('booking_id');
        var lotArea = button.data('lot_area');
        var serviceId = button.data('service_id'); // Get the service_id
        var totalCost = button.data('total_cost');
        var status = button.data('status');
        var designs = button.data('designs'); // Assuming this is a list of designs

        // Update the modal's content.
        var modal = $(this);
        
        modal.find('#modalId').text(id);
        modal.find('#modalBookingId').text(bookingId);
        modal.find('#modalServiceId').text(serviceId); // Update this line to show service_id
        modal.find('#modalLotArea').text(lotArea);
        modal.find('#modalTotalCost').text(totalCost); // Format total cost with peso symbol
        modal.find('#modalStatus').text(status);
        
        // Display designs
        var designsHtml = '';
        if (designs && designs.length) {
            designs.forEach(function(design) {
                designsHtml += `<div class="mb-2">
                    <img src="/path/to/designs/${design.image}" alt="${design.name}" class="img-fluid" />
                    <p>${design.name}</p>
                </div>`;
            });
        } else {
            designsHtml = '<p>No designs available.</p>';
        }
        modal.find('#modalDesigns').html(designsHtml);
    });
</script>
@endsection
