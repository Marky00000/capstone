@extends('layouts.app')

@section('title')
Add New User
@endsection

@section('content')
<div class="card shadow-sm rounded-lg border-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Add New User</h4>
    </div>
    <div class="card-body">
        <!-- Form -->
        <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            <!-- Hidden input for user type (set to admin by default) -->
            <input type="hidden" name="usertype" value="admin">

            <!-- Spinner and Submit Button -->
            <div class="d-flex align-items-center">
                <div id="loadingSpinner" class="loading-spinner d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <button type="submit" id="submitBtn" class="btn btn-primary ml-2">Add User</button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="fas fa-check-circle"></i> Success
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>User has been created successfully!</p>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">
                    <i class="fas fa-times-circle"></i> Error
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>There was an error adding the user. Please try again.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Adjust spinner and form alignment */
    .loading-spinner {
        display: inline-block;
        margin-right: 10px;
    }

    /* Modal icons styling */
    .fa-check-circle, .fa-times-circle {
        color: white;
        font-size: 1.5rem;
    }
</style>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Submit form via AJAX
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        // Show the spinner and disable the submit button
        $('#loadingSpinner').removeClass('d-none');
        $('#submitBtn').prop('disabled', true);

        // Clear previous error states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Serialize form data
        var formData = $(this).serialize();

        // AJAX request
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: formData,
            success: function(response) {
                // Hide the spinner and re-enable the submit button
                $('#loadingSpinner').addClass('d-none');
                $('#submitBtn').prop('disabled', false);

                // Show success modal
                $('#successModal').modal('show');

                // Automatically close the modal after 3 seconds
                setTimeout(function() {
                    $('#successModal').modal('hide');
                }, 2000); // 3-second delay

                // Redirect to /admin after a short delay (e.g., 2 seconds)
                setTimeout(function() {
                    window.location.href = "{{ route('admin.index') }}";
                }, 2000); // Adjust delay if needed
            },
            error: function(xhr) {
                // Hide the spinner and re-enable the submit button
                $('#loadingSpinner').addClass('d-none');
                $('#submitBtn').prop('disabled', false);

                // Show error modal
                $('#errorModal').modal('show');

                // Automatically close the error modal after 3 seconds
                setTimeout(function() {
                    $('#errorModal').modal('hide');
                }, 2000); // 3-second delay

                // Display validation errors
                if(xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key).after('<span class="invalid-feedback" role="alert"><strong>' + value[0] + '</strong></span>');
                    });
                }
            }
        });
    });
});

</script>
@endsection
