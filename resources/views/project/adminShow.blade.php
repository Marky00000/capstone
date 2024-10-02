@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-light" id="printableArea">
            <div class="card-header text-white bg-info text-center d-flex flex-column align-items-center">
                <img src="{{ asset('arfil_logo.png') }}" alt="Company Logo" class="logo" style="width: 120px">
                <h2 class="mb-1">Arfils Landscaping Services</h2>
                <p>Zone 10, Carmen<br>Cagayan de Oro City</p>
                <p>
                    <i class="fas fa-phone"></i> Contact: 09776912110<br>
                    <i class="fab fa-facebook"></i> Facebook: Arfils Landscaping and Swimming Pool Services<br>
                    <i class="fas fa-envelope"></i> Email: arifillandscaping@gmail.com
                </p>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    <h4 class="mb-0">Project Details</h4>
                    <h3 class="mb-0" style="font-size: 16px;">Created:
                        {{ \Carbon\Carbon::parse($projects->booking->created_at)->format('F j, Y') }}</h3>
                </div>

                <table class="table">
                    <tbody>
                        <tr>
                            <th>Project ID</th>
                            <td>{{ $projects->id }}</td>
                        </tr>
                        <tr>
                            <th>Booking ID</th>
                            <td>{{ $projects->booking->id }}</td>
                        </tr>
                        <tr>
                            <th>Client Name</th>
                            <td>{{ $projects->booking->name }}</td>
                        </tr>
                        <tr>
                            <th>Service</th>
                            <td>{{ $projects->service->name }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $projects->booking->address }}</td>
                        </tr>
                        <tr>
                            <th>Province</th>
                            <td>{{ $projects->booking->province }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $projects->booking->city }}</td>
                        </tr>
                        <tr>
                            <th>Lot Area</th>
                            <td>{{ $projects->lot_area }} sqm</td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>{{ $projects->discount }}%</td>
                        </tr>
                        <tr>
                            <th>Total Cost</th>
                            <td>₱{{ number_format($projects->total_cost, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Total Paid</th>
                            <td>₱{{ number_format($projects->total_paid, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    class="badge 
                                    @if ($projects->project_status == 'pending') badge-warning 
                                    @elseif($projects->project_status == 'active') badge-success 
                                    @elseif($projects->project_status == 'hold') badge-danger    
                                    @elseif($projects->project_status == 'finish') badge-primary @endif">
                                    @if ($projects->project_status == 'pending')
                                        <i class="fas fa-hourglass-half"></i>
                                    @elseif($projects->project_status == 'active')
                                        <i class="fas fa-spinner fa-spin"></i>
                                    @elseif($projects->project_status == 'hold')
                                        <i class="fas fa-pause-circle"></i>
                                    @elseif($projects->project_status == 'finish')
                                        <i class="fas fa-check"></i>
                                    @endif
                                    {{ ucfirst($projects->project_status) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr class="my-4">

                <!-- Progress Bar Section -->
                <div class="mt-4">
                    <h4>Project Progress</h4>
                    <div class="progress">
                        @php
                            // Fetch the latest progress entry
                            $latestProgress = $projects->progress->last();
                            $progress = $latestProgress ? $latestProgress->phase_progress : 0; // Fallback to 0 if no progress
                            $currentPhase = $latestProgress ? $latestProgress->phase : 'phase_one'; // Default to 'phase_one' if no phase

                            // Define the phases in order
                            $phases = [
                                'phase_one' => 'Phase One',
                                'phase_two' => 'Phase Two',
                                'phase_three' => 'Phase Three',
                                // Add more phases as needed
                            ];

                            // Move to the next phase if the current phase is complete
                            if ($progress == 100 && $currentPhase !== 'phase_three') {
                                $nextPhase = array_search($currentPhase, array_keys($phases)) + 1;
                                $currentPhase = array_keys($phases)[$nextPhase] ?? $currentPhase; // Move to next phase or remain if no next phase
                                $progress = 0; // Reset progress for new phase
                            }

                            // Set readable phase name
                            $readablePhase = $phases[$currentPhase] ?? 'Not Started';

                            // Set phase color based on progress
                            $phaseColor = 'bg-success'; // Default color for success
                            if ($progress < 50) {
                                $phaseColor = 'bg-warning'; // Color for warning
                            } elseif ($progress == 100) {
                                $phaseColor = 'bg-primary'; // Color for complete
                            }
                        @endphp

                        <!-- Display the progress bar -->
                        <div class="progress-bar {{ $phaseColor }}" role="progressbar"
                            style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                            aria-valuemax="100">
                            {{ $progress }}%
                        </div>
                    </div>

                    <div class="mt-2 text-center">
                        <i class="fas fa-check-circle"></i>
                        @if ($progress == 100 && $currentPhase === 'phase_three')
                            Project Complete
                        @elseif ($progress == 0 && ($currentPhase === 'phase_two' || $currentPhase === 'phase_three'))
                            Phase: {{ $readablePhase }} (Starting)
                        @elseif ($progress > 0)
                            Phase: {{ $readablePhase }} (In Progress)
                        @else
                            Project Not Started
                        @endif
                    </div>
                </div>





                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('project.adminIndex') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>

                    @if ($projects->project_status === 'active')
                        <a href="{{ route('progress.index', ['projectId' => $projects->id]) }}"
                            class="btn btn-primary btn-md">
                            <i class="fas fa-sync-alt"></i> View Progress
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <style>
        /* Additional styles for a modern project view */
        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: none;
            padding: 20px;
        }

        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #e9ecef;
            font-weight: bold;
            color: #495057;
        }

        .table td {
            font-size: 14px;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        hr {
            border: 1px solid #e9ecef;
        }

        p i {
            margin-right: 5px;
        }

        p {
            font-size: 14px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                padding: 0;
            }

            .card {
                width: 100%;
                box-shadow: none;
                border: 1px solid #ddd;
                background-color: #fff;
            }

            .btn {
                display: none;
            }

            .card-header {
                background-color: #17a2b8;
                color: #fff;
            }

            .table th,
            .table td {
                color: #000;
            }

            hr {
                border: 1px solid #17a2b8;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
