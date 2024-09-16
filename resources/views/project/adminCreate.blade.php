@extends('layouts.app')

@section('title')

@section('content')
<style>
/* Modal Design Styles */
.modal-content {
    border-radius: 8px;
}

.modal-header {
    border-bottom: none;
}

.modal-title {
    font-weight: bold;
}

.modal-body {
    padding: 20px;
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* Two columns */
    gap: 20px;
}

.design-card {
    cursor: pointer;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
    display: flex;
    flex-direction: column; /* Stack content vertically */
    height: 100%; /* Ensure card takes up full height of container */
}

.design-card:hover {
    transform: scale(1.03);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.design-img {
    width: 100%;
    height: 200px; /* Fixed height for images */
    object-fit: cover;
}

.design-card-content {
    padding: 15px;
    text-align: center;
    flex-grow: 1; /* Allow content to fill the card */
}

.design-card-content h5 {
    font-size: 1.1rem;
    margin-bottom: 10px;
}

.btn-grey {
    background-color: #6c757d;
    color: white;
    border: 1px solid #6c757d;
    transition: background-color 0.3s, border-color 0.3s;
}

.btn-grey:hover {
    background-color: #5a6268;
    border-color: #5a6268;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    border: none;
    transition: background-color 0.3s;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #ced4da;
    padding: 12px;
}

.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    border-color: #80bdff;
}

.card {
    border: none;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.card-header {
    background-color: #007bff;
    color: white;
    font-size: 1.25rem;
    border-bottom: 1px solid #ddd;
    padding: 15px;
}

.card-body {
    padding: 20px;
}


</style>

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-info text-white text-center py-4 rounded-top">
            <h6 class="mb-0">Add Project for Booking # {{ $booking_id }}</h6>
        </div>

        <div class="card-body p-4">
            <div class="modal fade" id="designModal" tabindex="-1" aria-labelledby="designModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="designModalLabel">Designs</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="designsContainer">
                            <!-- Design cards will be inserted here dynamically -->
                        </div>
                    </div>
                </div>
            </div>            
            
            <div id="alert-message" class="alert alert-dismissible fade show" role="alert">
                <span class="icon">
                    <i id="alert-icon" class="fas"></i>
                </span>
                <span id="alert-text"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Project Form -->
            <form id="project-form" action="{{ route('projects.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking_id }}">

                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" class="form-control" id="category" required>
                        <option value="landscaping">Landscaping</option>
                        <option value="swimmingpool">Swimming Pool</option>
                        <option value="renovation">Renovation</option>
                    </select>
                </div>
                
                <!-- Design ID Display -->
                <div class="text-center mb-3">
                    <strong>Service ID:</strong> <span id="selectedServiceId">Not selected</span>
                </div>
                
                 <!-- Add Design Button -->
                 <button type="button" class="btn btn-grey" id="addDesignButton">
                    Add Design <i class="fas fa-plus ml-2"></i>
                </button>
                
                <input type="hidden" name="service_id" id="service_id">
                
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <!-- Lot Area -->
                <div class="mb-3">
                    <label for="lot_area" class="form-label">Lot Area (sqm)</label>
                    <input type="text" name="lot_area" id="lot_area" class="form-control @error('lot_area') is-invalid @enderror" value="{{ old('lot_area') }}">
                    @error('lot_area')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Save and Cancel Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-outline-info rounded-pill px-4 py-2">Save Project</button>
                    <a href="{{ route('project.adminIndex') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Form submission handling
    $('#project-form').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        var $submitButton = $(this).find('button[type="submit"]');
        if ($submitButton.prop('disabled')) {
            return; // Prevent further submissions if button is already disabled
        }

        // Disable the button to prevent multiple submissions
        $submitButton.prop('disabled', true);

        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#alert-message')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .find('#alert-icon')
                    .removeClass('fas fa-exclamation-circle')
                    .addClass('fas fa-check-circle')
                    .end()
                    .find('#alert-text')
                    .html(response.message)
                    .end()
                    .fadeIn();

                // Fade out alert after 3 seconds
                setTimeout(function() {
                    $('#alert-message').fadeOut();
                }, 3000);

                // Redirect after 3 seconds
                setTimeout(function() {
                    window.location.href = "{{ route('project.adminIndex') }}";
                }, 3000);
            },
            error: function(xhr) {
                $('#alert-message')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .find('#alert-icon')
                    .removeClass('fas fa-check-circle')
                    .addClass('fas fa-exclamation-circle')
                    .end()
                    .find('#alert-text')
                    .html('Please fix the errors below.')
                    .end()
                    .fadeIn();

                // Re-enable the button
                $submitButton.prop('disabled', false);

                setTimeout(function() {
                    $('#alert-message').fadeOut();
                }, 3000);
            }
        });
    });

    // Handle Add Design Button Click
    $('#addDesignButton').on('click', function() {
        var selectedCategory = $('#category').val();
        if (selectedCategory) {
            $.ajax({
                url: '/designs/' + selectedCategory,
                type: 'GET',
                success: function(response) {
                    var designsContainer = $('#designsContainer');
                    designsContainer.empty();
                    if (Array.isArray(response)) {
                        $.each(response, function(index, design) {
                            designsContainer.append(`
                                <div class="design-card" data-id="${design.id}">
                                    <img src="${design.design}" class="design-img" alt="${design.name}">
                                    <div class="design-card-content">
                                        <h5>${design.name}</h5>
                                        <p>${design.description}</p>
                                        <p><strong>Complexity:</strong> ${design.complexity}</p>
                                    </div>
                                </div>
                            `);
                        });

                        // Attach click event to each design card
                        $('.design-card').on('click', function() {
                            var id = $(this).data('id');
                            $('#service_id').val(id);
                            $('#selectedServiceId').text(id);

                            // Hide the modal using Bootstrap's method
                            var designModal = bootstrap.Modal.getInstance(document.getElementById('designModal'));
                            if (designModal) {
                                designModal.hide(); // Close the modal
                            }

                            // Ensure the backdrop is removed and CSS adjustments are reset
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open'); // Allow scrolling again
                            $('body').css('padding-right', ''); // Reset any padding adjustments
                        });

                        // Show the modal
                        var designModal = new bootstrap.Modal(document.getElementById('designModal'));
                        designModal.show();
                    } else {
                        designsContainer.html('<p>No designs available.</p>');
                    }
                }
            });
        }
    });
});





</script>
@endsection
