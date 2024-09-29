@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="padding: 20px; border-radius: 10px;">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card for Payment Details -->
        <div class="card shadow-sm border-light">
            <div class="card-header bg-info text-white">
                <h3 class="mb-0"><i class="fas fa-money-check-alt"></i> Payment for Project: {{ $project->id }}</h3>
            </div>
            <div class="card-body d-flex" style="align-items: stretch;">
                <!-- Payment Overview Section -->
                <div class="me-2" style="flex: 1; padding: 20px;">
                    <h5 class="text-muted">Payment Overview</h5>
                    <p class="fw-bold">Total Cost: <span
                            class="text-success">₱{{ number_format($project->total_cost, 2) }}</span></p>
                    <p class="fw-bold">Initial Payment (50%): <span
                            class="text-success">₱{{ number_format($project->total_cost * 0.5, 2) }}</span></p>
                    <p class="fw-bold">Midterm Payment (25%): <span
                            class="text-success">₱{{ number_format($project->total_cost * 0.25, 2) }}</span></p>
                    <p class="fw-bold">Final Payment (25%): <span
                            class="text-success">₱{{ number_format($project->total_cost * 0.25, 2) }}</span></p>
                    <p class="fw-bold">Total Paid: <span
                            class="text-success">₱{{ number_format($project->total_paid, 2) }}</span></p>
                </div>

                <!-- Vertical Divider -->
                <div class="vr" style="border-left: 1px solid #343a40; height: auto;"></div>

                <!-- Payment Form Section -->
                <form id="initialPaymentForm" method="POST" enctype="multipart/form-data" style="flex: 1; padding: 20px;">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <!-- Payment Amount Input Section -->
                    <div class="mb-3">
                        <label for="payment_amount" class="form-label">Payment Amount <span
                                style="color: red;">*</span></label>
                        <input type="number" name="payment_amount" id="payment_amount" class="form-control" required
                            min="{{ $project->total_paid == 0 ? $project->total_cost * 0.25 : 0 }}"
                            max="{{ $project->total_cost - $project->total_paid }}" step="0.01">
                        <div class="form-text text-muted">
                            The payment amount must be between ₱{{ number_format($project->total_cost * 0.25, 2) }} and
                            ₱{{ number_format($project->total_cost - $project->total_paid, 2) }}.
                        </div>
                        <p class="fw-bold">Remaining Payables: <span
                                class="text-danger">₱{{ number_format($project->total_cost - $project->total_paid, 2) }}</span>
                        </p>
                    </div>


                    <div class="mb-3">
                        <label for="payment_image" class="form-label">Upload Initial Payment Image</label>
                        <input type="file" name="payment_image" class="form-control" accept="image/*" required>
                    </div>

                    <hr style="border-top: 1px solid #343a40;" />

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-info hover-effect">
                            <i class="fas fa-paper-plane"></i> Submit Initial Payment
                        </button>
                        <a href="{{ route('project.index', ['booking_id' => $project->booking_id]) }}"
                            class="btn btn-secondary hover-effect">
                            <i class="fas fa-arrow-left"></i> Back to Projects
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        body {
            background: url('{{ asset('landscaping.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
        }

        .header-hover {
            color: #fff;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .header-hover:hover {
            color: #17a2b8;
            transform: scale(1.05);
        }

        .hover-effect {
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-effect:hover {
            background-color: #17a2b8;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .card {
            border-radius: 10px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
        }

        .card-body {
            font-family: 'Arial', sans-serif;
            color: #333;
            display: flex;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .form-control,
        .form-select {
            height: 38px;
            padding: .375rem .75rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .fw-bold {
            font-weight: 600;
        }

        .text-muted {
            color: #6c757d;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .vr {
            border-left: 1px solid #343a40;
            height: auto;
            margin: 0 20px;
        }
    </style>

    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMdYB2X2k+7IbjzdH9pEZq25F1dkK4RiG5+/yJ" crossorigin="anonymous">
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#initialPaymentForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Prepare the form data
                var formData = new FormData(this);

                $.ajax({
                    url: '{{ route('payment.store.initial') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success response
                        window.location.href = response
                            .redirect; // Redirect to the payments.show route
                    },
                    error: function(xhr) {
                        // Handle errors
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        alert(errorMessage);
                    }
                });
            });
        });
    </script>
@endsection
