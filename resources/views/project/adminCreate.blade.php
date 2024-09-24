@extends('layouts.app')

@section('title')

@section('content')
    <style>
        /* General Styles */
        .card {
            border: none;
            border-radius: 12px;
            /* Remove or override the overflow property */
            /* overflow: hidden; */
            /* Commented out to prevent clipping */
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            background-color: #ffffff;
        }

        /* If you still need overflow: hidden for other reasons, consider overriding it here */
        /* .card {
                                                            overflow: visible;
                                                        } */

        .card-header {
            position: relative;
            width: 100%;
            height: 180px;
            background-image: url('{{ asset('background.jpg') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Dark overlay for text readability */
            z-index: 1;
        }

        .card-header h6 {
            position: relative;
            z-index: 2;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            /* Center the text */
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: none;
            background-color: #f8f9fa;
        }

        .modal-title {
            font-weight: 600;
            color: #343a40;
        }

        .modal-body {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            /* Responsive columns */
            gap: 20px;
            background-color: #ffffff;
        }

        .design-card {
            cursor: pointer;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .design-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .design-img {
            width: 100%;
            height: 180px;
            /* Adjusted height for better balance */
            object-fit: cover;
        }

        .design-card-content {
            padding: 20px;
            text-align: center;
            flex-grow: 1;
        }

        .design-card-content h5 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #495057;
        }

        /* Button Styles */
        .btn-grey {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-grey:hover {
            background-color: #5a6268;
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

        .btn-outline-info {
            border-color: #17a2b8;
            color: #17a2b8;
        }

        .btn-outline-info:hover {
            background-color: #17a2b8;
            color: #fff;
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        .px-4 {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        .py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        /* Form Controls */
        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 12px;
            transition: border-color 0.3s, box-shadow 0.3s;
            width: 100%;
            box-sizing: border-box;
            /* Ensure padding is included in width */
            font-size: 1rem;
            /* Ensure font size is readable */
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #80bdff;
        }

        /* Form Controls */
        .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 12px;
            transition: border-color 0.3s, box-shadow 0.3s;
            width: 100%;
            box-sizing: border-box;
            /* Ensure padding is included in width */
            font-size: 1rem;
            /* Ensure font size is readable */
        }


        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
        }

        /* Alert Styles */
        #alert-message {
            display: none;
            /* Initially hidden */
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .modal-body {
                grid-template-columns: 1fr;
                /* Single column on smaller screens */
            }

            .card-header {
                height: 150px;
            }

            .design-img {
                height: 150px;
            }

            .card-header h6 {
                font-size: 1.2rem;
            }
        }
    </style>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header">
                <h6 class="mb-0">Add Project for Booking # {{ $booking_id }}</h6>
            </div>

            <div id="spinner" style="display:none;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="modal fade" id="designModal" tabindex="-1" aria-labelledby="designModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="designModalLabel">Choose a Design</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="designsContainer">
                                <!-- Design cards will be dynamically inserted here -->
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

                <form id="project-form" action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking_id }}">

                    <!-- Category Selection -->
                    <div class="form-group mb-4">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" class="form-select" id="category" required>
                            <option value="">Select a category</option>
                            <option value="landscaping" {{ old('category') == 'landscaping' ? 'selected' : '' }}>Landscaping
                            </option>
                            <option value="swimmingpool" {{ old('category') == 'swimmingpool' ? 'selected' : '' }}>Swimming
                                Pool</option>
                            <option value="renovation" {{ old('category') == 'renovation' ? 'selected' : '' }}>Renovation
                            </option>
                        </select>
                    </div>



                    <!-- Hidden Inputs -->
                    <input type="hidden" name="service_id" id="service_id">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <!-- Lot Area -->
                    <div class="mb-4">
                        <label for="lot_area" class="form-label">Lot Area (sqm)</label>
                        <input type="number" name="lot_area" id="lot_area"
                            class="form-control @error('lot_area') is-invalid @enderror" value="{{ old('lot_area') }}"
                            min="0" step="0.01" required>
                        @error('lot_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Discount Selection -->
                    <div class="mb-4">
                        <label for="discount" class="form-label">Discount</label>
                        <select name="discount" class="form-select" id="discount">
                            <option value="">Select a discount</option>
                            @foreach ($discounts as $discount)
                                <!-- Assume $discounts is passed from the controller -->
                                <option value="{{ $discount }}">{{ $discount }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Design ID Display -->
                    <div class="text-center mb-4">
                        <strong>Service ID:</strong> <span id="selectedServiceName">Not selected</span>
                    </div>

                    <!-- Add Design Button -->
                    <div class="text-center mb-4">
                        <button type="button" class="btn btn-grey" id="addDesignButton">
                            <i class="fas fa-plus me-2"></i> Add Design
                        </button>
                    </div>
                    <div id="totalCost" class="total-cost">
                        Total Cost: <span id="costValue">₱0.00</span>
                    </div>


                    <!-- Save and Cancel Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-outline-info rounded-pill px-4 py-2">Save Project</button>
                        <a href="{{ route('project.adminIndex') }}"
                            class="btn btn-outline-secondary rounded-pill px-4 py-2">Cancel</a>
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
            // Event listener for input changes
            $('#category, #complexity, #lot_area, #discount').on('change input', function() {
                const selectedCategory = $('#category').val();
                const selectedComplexity = $('#complexity').val(); // Assuming there's a complexity dropdown
                const lotArea = parseFloat($('#lot_area').val()) || 0; // Get lot area
                const discount = parseFloat($('#discount').val()) || 0; // Get discount percentage

                // Check if required inputs are filled
                if (selectedCategory && selectedComplexity && lotArea > 0) {
                    // Call the server to calculate the total cost
                    $.ajax({
                        url: '/calculate-cost', // Update with your route for calculating cost
                        type: 'POST',
                        data: {
                            category: selectedCategory,
                            complexity: selectedComplexity,
                            lot_area: lotArea,
                            discount: discount,
                            _token: $('meta[name="csrf-token"]').attr(
                                'content') // Include CSRF token
                        },
                        success: function(response) {
                            $('#costValue').text('₱' + parseFloat(response.cost).toFixed(
                            2)); // Update displayed cost
                        },
                        error: function(xhr) {
                            $('#costValue').text('₱0.00'); // Reset to zero if an error occurs
                        }
                    });
                } else {
                    $('#costValue').text('₱0.00'); // Reset to zero if inputs are invalid
                }
            });

            $('#project-form').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                var $submitButton = $(this).find('button[type="submit"]');
                if ($submitButton.prop('disabled')) {
                    return; // Prevent further submissions if button is already disabled
                }

                // Disable the button to prevent multiple submissions
                $submitButton.prop('disabled', true);
                // Show spinner
                $('#spinner').show(); // Assuming you have a spinner element with this ID

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

                        // Update total cost display (optional: if needed to reflect the final cost)
                        $('#costValue').text('₱' + parseFloat(response.cost).toFixed(2));

                        // Fade out alert after 3 seconds
                        setTimeout(function() {
                            $('#alert-message').fadeOut();
                        }, 3000);

                        // Redirect after 3 seconds
                        setTimeout(function() {
                            window.location.href =
                                "{{ route('project.adminIndex') }}"; // Adjust route as necessary
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
                                <div class="design-card" data-id="${design.id}" data-name="${design.name}" data-complexity="${design.complexity}">
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
                                    var name = $(this).data('name');
                                    var complexity = $(this).data(
                                    'complexity'); // Get the complexity of the service
                                    $('#service_id').val(id);
                                    $('#selectedServiceId').text(id);
                                    $('#selectedServiceName').text(name);
                                    $('#selectedComplexity').val(
                                    complexity); // Store complexity in a hidden field

                                    // Hide the modal using Bootstrap's method
                                    var designModal = bootstrap.Modal.getInstance(
                                        document.getElementById('designModal'));
                                    if (designModal) {
                                        designModal.hide(); // Close the modal
                                    }

                                    // Ensure the backdrop is removed and CSS adjustments are reset
                                    $('.modal-backdrop').remove();
                                    $('body').removeClass(
                                    'modal-open'); // Allow scrolling again
                                    $('body').css('padding-right',
                                    ''); // Reset any padding adjustments
                                });

                                // Show the modal
                                var designModal = new bootstrap.Modal(document.getElementById(
                                    'designModal'));
                                designModal.show();
                            } else {
                                designsContainer.html('<p>No designs available.</p>');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching designs:', xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>



@endsection

