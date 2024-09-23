<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('arfil_logo.png') }}">
    <title>Arfil's Landscaping Services</title>
    <style>
        /* Button Design Styles */
        .btn-outline-info {
            color: #17a2b8;
            /* Initial text color */
            background-color: white;
            /* Initial background color */
            border: 1px solid #17a2b8;
            /* Initial border color */
            transition: all 0.3s ease-in-out;
        }

        .btn-outline-info:hover {
            color: white;
            /* Text color on hover */
            background-color: #17a2b8;
            /* Background color on hover */
            border-color: #17a2b8;
            /* Border color on hover */
            transform: scale(1.05);
            /* Scale effect on hover */
        }

        /* Apply similar styles to other button classes */
        .btn-info {
            color: #007bff;
            /* Initial text color */
            background-color: white;
            /* Initial background color */
            border: 1px solid #007bff;
            /* Initial border color */
            transition: all 0.3s ease-in-out;
        }

        .btn-info:hover {
            color: white;
            /* Text color on hover */
            background-color: #007bff;
            /* Background color on hover */
            border-color: #007bff;
            /* Border color on hover */
            transform: scale(1.05);
            /* Scale effect on hover */
        }

        .btn-secondary {
            color: #6c757d;
            /* Initial text color */
            background-color: white;
            /* Initial background color */
            border: 1px solid #6c757d;
            /* Initial border color */
            transition: all 0.3s ease-in-out;
        }

        .btn-secondary:hover {
            color: white;
            /* Text color on hover */
            background-color: #6c757d;
            /* Background color on hover */
            border-color: #6c757d;
            /* Border color on hover */
            transform: scale(1.05);
            /* Scale effect on hover */
        }


        /* Form Control and Card Design Styles */
        .form-control,
        .form-select {
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .alert {
            border-radius: 8px;
        }


        /* Form Control and Card Design Styles */
        .form-control,
        .form-select {
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .alert {
            border-radius: 8px;
        }


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
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
        }

        .design-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .design-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .design-card-content {
            padding: 15px;
            text-align: center;
        }

        .design-card-content h5 {
            font-size: 1.1rem;
            margin-bottom: 10px;
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

</head>

<body>
    @extends('layouts.apps')

    @section('content')
        <div class="container mt-4">

            <div class="card">
                <div class="card-header bg-info text-white">

                    <h5 class="mb-0 text-center">
                        {{ isset($quotation) ? 'Edit Quotation' : 'Create Quotation' }}
                    </h5>
                </div>
                <div class="card-body">

                    <div class="modal fade" id="designModal" tabindex="-1" aria-labelledby="designModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="designModalLabel">Designs</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="designsContainer">
                                    <!-- Design cards will be inserted here dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="responseMessage"></div>

                    <!-- Address Information Form -->
                    <form id="addressForm">
                        @csrf
                        <!-- Address -->
                        <div class="form-group">
                            <label for="address">Address <span
                                    style="color: red; font-size: 0.75em;">*Required</span></label>
                            <input type="text" name="address" class="form-control" id="address"
                                value="{{ old('address', $quotation->address ?? '') }}" required>
                        </div>

                        <!-- Region -->
                        <div class="form-group">
                            <label for="region">Region <span
                                    style="color: red; font-size: 0.75em;">*Required</span></label>
                            <select name="region" class="form-control" id="region" required>
                                <option value="">Select Region</option>
                                <option value="NCR">NCR</option>
                                <option value="CAR">CAR</option>
                                <option value="Ilocos Region">Ilocos Region</option>
                                <option value="Cagayan Valley">Cagayan Valley</option>
                                <option value="Central Luzon">Central Luzon</option>
                                <option value="CALABARZON">CALABARZON</option>
                                <option value="MIMAROPA">MIMAROPA</option>
                                <option value="Bicol Region">Bicol Region</option>
                                <option value="Western Visayas">Western Visayas</option>
                                <option value="Central Visayas">Central Visayas</option>
                                <option value="Eastern Visayas">Eastern Visayas</option>
                                <option value="Zamboanga Peninsula">Zamboanga Peninsula</option>
                                <option value="Northern Mindanao">Northern Mindanao</option>
                                <option value="Davao Region">Davao Region</option>
                                <option value="SOCCSKSARGEN">SOCCSKSARGEN</option>
                                <option value="Caraga">Caraga</option>
                                <option value="BARMM">BARMM</option>

                            </select>
                        </div>

                        <!-- City/Municipality -->
                        <div class="form-group">
                            <label for="city">City/Municipality <span
                                    style="color: red; font-size: 0.75em;">*Required</span></label>
                            <select name="city" class="form-control" id="city" required>
                                <option value="">Select City/Municipality</option>
                            </select>
                        </div>

                        <!-- Next button -->
                        <div class="d-flex justify-content-between">
                            <button href ="{{ route('quotation.view') }}" type="button" class="btn btn-outline-info"
                                id="cancel-button">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-outline-info btn-next" id="nextButton">
                                Next
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Project Information Form (Initially Hidden) -->
                    <form id="projectForm"
                        action="{{ isset($quotation) ? route('quotation.update', $quotation->id) : route('quotation.store') }}"
                        method="POST" class="d-none">
                        @csrf
                        @if (isset($quotation))
                            @method('PUT')
                        @endif

                        <!-- Lot Area, Service -->
                        <div class="form-group">
                            <label for="lot_area">Lot Area <span style="color: red; font-size: 0.75em;">*
                                    required</span></label>
                            <input placeholder="Please enter a value between 20 and 300 square meters." name="lot_area"
                                class="form-control" id="lot_area"
                                value="{{ old('lot_area', $quotation->lot_area ?? '') }}" min="20" max="300"
                                required>
                        </div>

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
                            <strong>Service ID:</strong> <span id="selectedServiceName">Not selected</span>
                        </div>

                        <!-- Add Design Button -->
                        <button type="button" class="btn btn-grey" id="addDesignButton">
                            Add Design <i class="fas fa-plus ml-2"></i>
                        </button>

                        <input type="hidden" name="service_id" id="service_id">


                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-info" id="backButton">
                                <i class="fas fa-arrow-left me-2"></i> Previous
                            </button>
                            <button type="submit" class="btn btn-outline-info", id="submitButton">
                                Submit Quotation
                                <i class="fas fa-check ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">
                            <i class="fas fa-exclamation-circle" style="color: red; margin-right: 8px;"></i>
                            Error
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Error message will be inserted here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const regionSelect = document.getElementById('region');
                const citySelect = document.getElementById('city');
                const nextButton = document.getElementById('nextButton');
                const addressInput = document.getElementById('address');
                const regionInput = document.getElementById('region');
                const cityInput = document.getElementById('city');
                const projectForm = document.getElementById('projectForm');
                const addressForm = document.getElementById('addressForm');
                const addDesignButton = document.getElementById('addDesignButton');
                const designModal = new bootstrap.Modal(document.getElementById('designModal'));
                const designsContainer = document.getElementById('designsContainer');
                const selectedServiceId = document.getElementById('selectedServiceId');
                const selectedServiceName = document.getElementById('selectedServiceName');
                const serviceIdInput = document.getElementById('service_id');

                // Load cities based on region selection
                regionSelect.addEventListener('change', function() {
                    const regionId = this.value;
                    citySelect.innerHTML =
                        '<option value="">Select City/Municipality</option>'; // Reset options

                    if (regionId) {
                        fetch(`/api/cities/${regionId}`)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(city => {
                                    const option = document.createElement('option');
                                    option.value = city.id;
                                    option.textContent = city.name;
                                    citySelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching cities:', error));
                    }
                });

                // Function to validate form inputs
                function validateAddressForm() {
                    let isValid = true;

                    // Check if all required fields are filled
                    if (addressInput.value.trim() === '') {
                        isValid = false;
                    }

                    if (regionInput.value === '') {
                        isValid = false;
                    }

                    if (cityInput.value === '') {
                        isValid = false;
                    }

                    return isValid;
                }

                // Handle Next button click
                nextButton.addEventListener('click', function() {
                    if (validateAddressForm()) {
                        addressForm.classList.add('d-none'); // Hide the address form
                        projectForm.classList.remove('d-none'); // Show the project form
                    } else {
                        alert('Please fill out all required fields.');
                    }
                });

                // Handle Back button click
                document.getElementById('backButton').addEventListener('click', function() {
                    projectForm.classList.add('d-none'); // Hide the project form
                    addressForm.classList.remove('d-none'); // Show the address form
                });

                // Redirect to booking index on cancel
                $('#cancel-button').on('click', function() {
                    window.location.href = "{{ route('quotation.view') }}";
                });

                // Fetch and display designs based on selected category
                addDesignButton.addEventListener('click', function() {
                    const selectedService = document.getElementById('category').value;

                    fetch(`/designs/${selectedService}`)
                        .then(response => response.json())
                        .then(data => {
                            if (Array.isArray(data)) {
                                designsContainer.innerHTML = data.map(design => `
                        <div class="design-card" data-id="${design.id}" data-name="${design.name}">
                            <img src="${design.design}" class="design-img" alt="${design.name}">
                            <div class="design-card-content">
                                <h5 class="card-title">${design.name}</h5>
                                <p>${design.description}</p>
                                <p><strong>Complexity:</strong> ${design.complexity}</p>
                            </div>
                        </div>
                    `).join('');

                                // Add click event to each design card
                                designsContainer.querySelectorAll('.design-card').forEach(card => {
                                    card.addEventListener('click', function() {
                                        const id = this.getAttribute('data-id');
                                        const name = this.getAttribute('data-name');
                                        selectDesign(id, name); // Pass both id and name
                                    });
                                });
                            } else {
                                designsContainer.innerHTML = `<p>${data.error}</p>`;
                            }
                            designModal.show();
                        })
                        .catch(error => console.error('Error fetching designs:', error));
                });

                // Update function to display the selected design's name
                window.selectDesign = function(id, name) {
                    serviceIdInput.value = id; // Store the service ID in the hidden input
                    selectedServiceName.textContent = name; // Display the service name
                    designModal.hide();
                };
            });

            $(document).ready(function() {
                // Handle form submission with AJAX
                $('#submitButton').on('click', function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    // Get the lot area value
                    const lotAreaValue = parseFloat($('#lot_area').val());

                    // Validate lot area
                    if (lotAreaValue < 20 || lotAreaValue > 300) {
                        // Show error modal message
                        $('#errorModal .modal-body').text(
                            'Please enter a lot area between 20 and 300 square meters.');
                        $('#errorModal').modal('show');

                        // Automatically close the modal after 3 seconds
                        setTimeout(function() {
                            $('#errorModal').modal('hide');
                        }, 3000); // 3000 ms = 3 seconds

                        return; // Stop further execution
                    }

                    // Serialize both forms
                    const addressFormData = $('#addressForm').serializeArray();
                    const projectFormData = $('#projectForm').serializeArray();

                    // Combine form data, ensuring _token is included only once
                    const combinedData = {};
                    addressFormData.forEach(item => {
                        combinedData[item.name] = item.value;
                    });

                    projectFormData.forEach(item => {
                        combinedData[item.name] = item.value;
                    });

                    // Add CSRF token to data
                    combinedData._token = $('meta[name="csrf-token"]').attr('content');

                    // Add lot area to combined data
                    combinedData.lot_area = lotAreaValue;

                    $.ajax({
                        url: $('#projectForm').attr('action'), // Use form action URL
                        type: 'POST',
                        data: combinedData,
                        success: function(response) {
                            // Handle successful response
                            if (response.success) {
                                $('#responseMessage').html('<div class="alert alert-success">' +
                                    response.message + '</div>');

                                // Redirect after a delay
                                setTimeout(function() {
                                    window.location.href = response.redirect_url;
                                }, 2000); // Delay in milliseconds (2000 ms = 2 seconds)
                            } else {
                                $('#responseMessage').html('<div class="alert alert-danger">' +
                                    response.message + '</div>');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle errors
                            $('#responseMessage').html(
                                '<div class="alert alert-danger">An error occurred: ' + xhr
                                .responseText + '</div>');
                        }
                    });
                });
            });
        </script>
    @endsection
</body>

</html>
