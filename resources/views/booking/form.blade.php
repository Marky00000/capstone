@extends('layouts.apps')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded">
        <div class="card-header bg-info text-white text-center py-3">
            <h4 class="mb-0">Create Booking</h4>
        </div>
        <div class="card-body p-4">
            <div id="alert-message" class="alert alert-dismissible fade show" role="alert" style="display: none;">
                <span class="icon">
                    <i id="alert-icon" class="fas"></i>
                </span>
                <span id="alert-text"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Multi-step form -->
            <form id="booking-form" action="{{ route('booking.store') }}" method="POST">
                @csrf
                <!-- Hidden field for user ID -->
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                <!-- Step 1: Booking Information -->
                <div id="step-1">
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                        <div id="name-error" class="text-danger mt-2"></div>
                    </div>

                    <!-- Contact -->
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" name="contact" id="contact" class="form-control" required>
                        <div id="contact-error" class="text-danger mt-2"></div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                        <div id="email-error" class="text-danger mt-2"></div>
                    </div>

                    <!-- Next button -->
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-info" id="cancel-button">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-outline-info btn-next" id="next-step">
                            Next
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Address Information -->
                <div id="step-2" class="d-none">
                    <div class="row">
                        <!-- Address -->
                        <div class="mb-3 col-12">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" id="address" class="form-control" required>
                            <div id="address-error" class="text-danger mt-2"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Region -->
                        <div class="col-md-6 mb-3">
                            <label for="province" class="form-label">Region</label>
                            <select name="province" class="form-select" id="province" required>
                                <option value="">Select Region</option>
                                @foreach (['NCR', 'CAR', 'Ilocos Region', 'Cagayan Valley', 'Central Luzon', 'CALABARZON', 'MIMAROPA', 'Bicol Region', 'Western Visayas', 'Central Visayas', 'Eastern Visayas', 'Zamboanga Peninsula', 'Northern Mindanao', 'Davao Region', 'SOCCSKSARGEN', 'Caraga', 'BARMM'] as $province)
                                    <option value="{{ $province }}">{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City/Municipality -->
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City/Municipality</label>
                            <select name="city" class="form-select" id="city" required>
                                <option value="">Select City/Municipality</option>
                            </select>
                        </div>
                    </div>

                    <!-- Site Visit Date -->
                    <div class="mb-3">
                        <label for="site_visit_date" class="form-label">Site Visit Date</label>
                        <input type="date" name="site_visit_date" id="site_visit_date" class="form-control" required>
                        <div id="site_visit_date-error" class="text-danger mt-2"></div>
                    </div>

                    <!-- Previous and Submit buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-info" id="previous-step">
                            <i class="fas fa-arrow-left me-2"></i> Previous
                        </button>
                        <button type="submit" class="btn btn-outline-info">
                            Submit Booking
                            <i class="fas fa-check ms-2"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- jQuery and AJAX script -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle step navigation
        $('#next-step').on('click', function() {
            $('#step-1').addClass('d-none');
            $('#step-2').removeClass('d-none');
        });

        $('#previous-step').on('click', function() {
            $('#step-2').addClass('d-none');
            $('#step-1').removeClass('d-none');
        });

        // Redirect to booking index on cancel
        $('#cancel-button').on('click', function() {
            window.location.href = "{{ route('booking.index') }}";
        });

        // City selection based on region
        const cities = @json($cities);

        $('#province').change(function() {
            const selectedRegion = $(this).val();
            const citySelect = $('#city');
            citySelect.empty();
            citySelect.append('<option value="">Select City/Municipality</option>');

            if (selectedRegion) {
                const provinceCities = cities[selectedRegion] || [];
                provinceCities.forEach(city => {
                    citySelect.append(`<option value="${city.id}">${city.name}</option>`);
                });
            }
        });

        // Handle form submission with AJAX
        $('#booking-form').on('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting the default way

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

                    // Automatically fade out the alert after 3 seconds
                    setTimeout(function() {
                        $('#alert-message').fadeOut();
                    }, 3000);

                    // Clear error messages
                    $('#name-error').text('');
                    $('#contact-error').text('');
                    $('#email-error').text('');
                    $('#service-error').text('');
                    $('#site_visit_date-error').text('');
                    $('#address-error').text('');

                    // Redirect to booking index
                    window.location.href = "{{ route('booking.index') }}";
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
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

                    // Automatically fade out the alert after 4 seconds
                    setTimeout(function() {
                        $('#alert-message').fadeOut();
                    }, 4000);

                    // Display error messages
                    $('#name-error').text(errors.name ? errors.name[0] : '');
                    $('#contact-error').text(errors.contact ? errors.contact[0] : '');
                    $('#email-error').text(errors.email ? errors.email[0] : '');
                    $('#service-error').text(errors.service ? errors.service[0] : '');
                    $('#site_visit_date-error').text(errors.site_visit_date ? errors.site_visit_date[0] : '');
                    $('#address-error').text(errors.address ? errors.address[0] : '');
                }
            });
        });
    });
</script>

<!-- Custom CSS for Modern Look -->
<style>
    .btn-outline-info {
        transition: all 0.3s ease-in-out;
    }

    .btn-outline-info:hover {
        transform: scale(1.05);
        background-color: #17a2b8; /* Darker blue for hover effect */
        color: #fff; /* Ensure text is white on hover */
    }

    .card {
        border-radius: 10px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }

    .alert {
        border-radius: 8px;
    }
</style>
@endsection
