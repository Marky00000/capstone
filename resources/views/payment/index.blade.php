@extends('layouts.app')

@section('content')
<div class="pricing-factors mb-4">
    <h5>Payment Overview</h5>
    <p>Below is a list of all your payments, including their details and current status.</p>
</div>
<div class="container-fluid mt-4">

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
 
    <div class="card shadow-sm border-light">
        <div class="card-header bg-info text-white d-flex justify-content-between">
            <h3 class="mb-0">Payment Records</h3>
            <a href="{{ route('welcome') }}" class="btn btn-light text-info btn-sm ml-auto">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">
            <table class="table table-bordered" style="width: 100%;">
                <thead>
                    <tr>
                        <th><i class="fas fa-numeric icon-faded-gray"></i> #</th>
                        <th><i class="fas fa-project-diagram"></i> Project ID</th>
                        <th><i class="fas fa-money-check-alt"></i> Payment Method</th>
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
                            <td>₱{{ number_format($payment->amount, 2) }}</td>
                            <td>
                                @if ($payment->payment_image)
                                    <img src="{{ asset('storage/' . $payment->payment_image) }}" alt="Payment Image" style="width: 100px; height: auto; border: 1px solid #ccc; display: block; margin: 0 auto;">
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
                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
        font-size: 15px; /* Increase font size */
    }

    th, td {
        padding: 15px; /* Increase cell padding */
    }

    th {
        background-color: #d3d3d3; /* Grey background for table headers */
        color: #333; /* Optional: change text color for better visibility */
    }
</style>
@endsection
