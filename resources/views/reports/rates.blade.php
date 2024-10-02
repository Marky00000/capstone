@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-light">
            <div class="card-header stylish-header text-black">
                <h1> Rates</h1>
            </div>

            <div class="card-body">
                <!-- Filter by Date Form -->
                <form action="{{ route('reports.rates') }}" method="GET" class="mb-4">
                    <div class="d-flex align-items-center"> <!-- Use flexbox for closer alignment -->
                        <!-- Start Date Filter -->
                        <div class="me-2"> <!-- Added margin to the right -->
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                        </div>

                        <!-- End Date Filter -->
                        <div class="me-2"> <!-- Added margin to the right -->
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-sm"> <!-- Added 'btn-sm' for smaller size -->
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </form>

                <table class="table table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th><i class="fas fa-numeric icon-faded-gray"></i> #</th>
                            <th><i class="fas fa-project-diagram"></i> Project ID</th>
                            <th><i class="fas fa-money-check-alt"></i> Payment Method</th>
                            <th><i class="fas fa-money-bill-wave"></i> Payment Type</th>
                            <th><i class="fas fa-coins"></i> Amount</th>
                            <th><i class="fas fa-image"></i> Image</th>
                            <th><i class="fas fa-info-circle"></i> Payment Status</th>
                            <th><i class="fas fa-calendar-alt"></i> Payment Date</th>
                            <th><i class="fas fa-tasks"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalRevenue = 0; // Initialize total revenue
                        @endphp
                        @foreach ($payments as $payment)
                            @if ($payment->payment_status === 'approve') <!-- Show only approved payments -->
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->project_id }}</td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>{{ ucfirst($payment->payment_type) }}</td>
                                    <td>₱{{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        @if ($payment->payment_image)
                                            <a href="#" class="payment-image"
                                                data-image-url="{{ asset('storage/' . $payment->payment_image) }}">
                                                <img src="{{ asset('storage/' . $payment->payment_image) }}"
                                                    alt="Payment Image"
                                                    style="width: 100px; height: auto; border: 1px solid #ccc; display: block; margin: 0 auto;">
                                            </a>
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                    </td>
                                    <td>{{ $payment->created_at->format('F j, Y') }}</td>

                                    <td>
                                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>

                                        <!-- Approve and Decline Buttons -->
                                        @if ($payment->payment_status === 'pending')
                                            <button type="button" class="btn btn-success btn-sm approve-btn"
                                                data-id="{{ $payment->id }}" data-bs-toggle="modal"
                                                data-bs-target="#actionConfirmationModal" data-action="approve">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm decline-btn"
                                                data-id="{{ $payment->id }}" data-bs-toggle="modal"
                                                data-bs-target="#actionConfirmationModal" data-action="decline">
                                                <i class="fas fa-times"></i> Decline
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $totalRevenue += $payment->amount; // Accumulate total revenue
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <!-- Display Total Revenue -->
                <div class="mt-4">
                    <h5 class="total-revenue" > <!-- Change color here -->
                        <i class="fas fa-calculator"></i> Total Revenue: <i style="color: #28a745;">₱{{ number_format($totalRevenue, 2) }}</i>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Payment Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center" style="min-height: 300px;">
                    <img id="modalImage" src="" alt="Payment Image" class="img-fluid"
                        style="transition: transform 0.3s ease; max-width: 100%; max-height: 80vh;">
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Container styling to remove padding for full-width table */
        .container-fluid {
            padding: 0;
        }

        /* Table styling */
        .table {
            font-size: 15px;
            /* Increase font size */
        }

        th,
        td {
            padding: 15px;
            /* Increase cell padding */
        }

        th {
            background-color: #d3d3d3;
            /* Grey background for headers */
        }

        /* Custom dropdown styling */
        .custom-dropdown {
            /* Custom dropdown styles */
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

        .total-revenue {
            /* Additional styling for total revenue text */
            font-weight: bold; /* Make it bold */
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .table-responsive {
                /* Allow horizontal scrolling on smaller screens */
                overflow-x: auto;
            }
        }
    </style>

@endsection

@push('scripts')
    <script>
        // Show image in modal
        document.querySelectorAll('.payment-image').forEach(item => {
            item.addEventListener('click', event => {
                const imageUrl = event.currentTarget.getAttribute('data-image-url');
                document.getElementById('modalImage').src = imageUrl;
            });
        });

        // Confirm action for approve/decline buttons
        document.querySelectorAll('.approve-btn, .decline-btn').forEach(item => {
            item.addEventListener('click', event => {
                const paymentId = event.currentTarget.getAttribute('data-id');
                const action = event.currentTarget.getAttribute('data-action');
                document.getElementById('actionType').innerText = action.charAt(0).toUpperCase() + action.slice(1);
                document.getElementById('actionForm').action = `{{ url('admin/payments') }}/${paymentId}/${action}`;
            });
        });
    </script>
@endpush
