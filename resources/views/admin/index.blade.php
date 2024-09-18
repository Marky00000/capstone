@extends('layouts.app')

@section('title')
Admin Users
@endsection

@section('content')
<div class="pricing-factors mb-4">
    <h5>User Overview</h5>
    <p>Below is a list of all users, including their details and type.</p>
</div>

<div class="card shadow-sm rounded-lg border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h4 class="mb-0">User List</h4>
        <div>
            <!-- Add "Back" button -->
            <a href="{{ route('dashboard') }}" class="btn btn-light text-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <!-- Add "Add User" button -->
            <a href="{{ route('admin.create') }}" class="btn btn-light text-primary btn-sm ml-2">
                <i class="fas fa-user-plus"></i> Add User
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($users->isEmpty())
            <p class="text-muted">No users found.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->usertype) }}</td>
                                <td>
                                    <button 
                                        class="btn btn-outline-info btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#userModal"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-type="{{ $user->usertype }}">
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
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userModalLabel">User Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="receipt-details">
                    <p><strong>ID:</strong> <span id="userModalId"></span></p>
                    <p><strong>Name:</strong> <span id="userModalName"></span></p>
                    <p><strong>Email:</strong> <span id="userModalEmail"></span></p>
                    <p><strong>Type:</strong> <span id="userModalType"></span></p>
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
    $('#userModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('#userModalId').text(button.data('id'));
        modal.find('#userModalName').text(button.data('name'));
        modal.find('#userModalEmail').text(button.data('email'));
        modal.find('#userModalType').text(button.data('type'));
    });
</script>
@endsection
