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
                <h1>Payments</h1>
            </div>

            <div class="card-body">
                <table class="table table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th><i class="fas fa-numeric icon-faded-gray"></i> #</th>
                            <th><i class="fas fa-project-diagram"></i> Project ID</th>
                            <th><i class="fas fa-money-check-alt"></i> Payment Method</th>
                            <th><i class="fas fa-money-check-alt"></i> Payment Type</th>
                            <th><i class="fas fa-coins"></i> Amount</th>
                            <th><i class="fas fa-image"></i> Image</th>
                            <th><i class="fas fa-info-circle"></i> Payment Status</th>
                            <th><i class="fas fa-calendar-alt"></i> Payment Date</th>
                            <th><i class="fas fa-tasks"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
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
                                            <img src="{{ asset('storage/' . $payment->payment_image) }}" alt="Payment Image"
                                                style="width: 100px; height: auto; border: 1px solid #ccc; display: block; margin: 0 auto;">
                                        </a>
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>
                                    @if ($payment->payment_status === 'approve')
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                    @elseif ($payment->payment_status === 'decline')
                                        <span class="text-danger">
                                            <i class="fas fa-times-circle"></i> Declined
                                        </span>
                                    @else
                                        <span class="text-warning">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @endif
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
                        @endforeach
                    </tbody>
                </table>
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

    <!-- Action Confirmation Modal -->
    <div class="modal fade" id="actionConfirmationModal" tabindex="-1" aria-labelledby="actionConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="actionConfirmationModalLabel">Confirmation</h5>
                    <button type="button" id="cancelActionButton" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to <span id="actionType"></span> this payment?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelActionButton">Cancel</button>
                    <form id="actionForm" action="" method="POST" style="display:inline;">
                        @csrf
                        <!-- Change here: Use btn-primary for the Confirm button -->
                        <button type="submit" class="btn btn-primary" id="confirmActionButton">Confirm</button>
                    </form>
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
            /* Grey background for table headers */
            color: #333;
            /* Optional: change text color for better visibility */
        }
    </style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentImageLinks = document.querySelectorAll('.payment-image');
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        const modalImage = document.getElementById('modalImage');

        paymentImageLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default anchor click behavior
                const imageUrl = this.getAttribute('data-image-url');
                modalImage.src = imageUrl; // Set the modal image source
                modal.show(); // Show the modal
            });
        });

        // Reset scale on modal close and cleanup
        document.getElementById('imageModal').addEventListener('hidden.bs.modal', function() {
            modalImage.style.transform = 'scale(1)'; // Reset scale when the modal closes
            modalImage.src = ''; // Clear the image source to free memory
        });

        // Action confirmation modal logic
        const actionConfirmationModal = new bootstrap.Modal(document.getElementById('actionConfirmationModal'));
        const actionForm = document.getElementById('actionForm');
        const actionTypeSpan = document.getElementById('actionType');

        document.querySelectorAll('.approve-btn, .decline-btn').forEach(button => {
            button.addEventListener('click', function() {
                const paymentId = this.getAttribute('data-id');
                const actionType = this.getAttribute('data-action');
                actionTypeSpan.innerText = actionType.charAt(0).toUpperCase() + actionType.slice(1);
                actionForm.action = actionType === 'approve' ? 
                    "{{ route('admin.payments.approve', ':id') }}".replace(':id', paymentId) : 
                    "{{ route('admin.payments.decline', ':id') }}".replace(':id', paymentId);
                actionConfirmationModal.show();
            });
        });

        // Refresh page on cancel
        document.getElementById('cancelActionButton').addEventListener('click', function() {
            window.location.reload(); // Refresh the page
        });

        // Reset action form on modal close
        document.getElementById('actionConfirmationModal').addEventListener('hidden.bs.modal', function() {
            actionForm.reset(); // Reset the form inputs if any
        });
    });
</script>


@endsection
