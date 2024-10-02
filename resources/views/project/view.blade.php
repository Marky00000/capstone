@extends('layouts.app')

@section('title', 'Project Details')

@section('content')

    <div class="container mt-4" id="printableArea">
        <div class="card shadow-sm border-light">
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
                    <h3 class="mb-0" style="font-size: 16px;">
                        Created At: {{ \Carbon\Carbon::parse($project->created_at)->format('F j, Y') }}
                    </h3>
                </div>

                <table class="table">
                    <tbody>
                        <tr>
                            <th>Booking ID</th>
                            <td>{{ $project->booking->id }}</td>
                        </tr>
                        <tr>
                            <th>Service</th>
                            <td>{{ $project->service->name }}</td>
                        </tr>
                        <tr>
                            <th>Site Visit Date</th>
                            <td>{{ \Carbon\Carbon::parse($project->booking->site_visit_date)->format('F j, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $project->booking->address }}</td>
                        </tr>
                        <tr>
                            <th>Province</th>
                            <td>{{ $project->booking->province }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $project->booking->city }}</td>
                        </tr>
                        <tr>
                            <th>Lot Area</th>
                            <td>{{ $project->lot_area }} sqm</td>
                        </tr>
                        <tr>
                            <th>Total Cost</th>
                            <td>₱{{ number_format($project->total_cost, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Project Status</th>
                            <td>
                                @if ($project->project_status === 'active')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @elseif($project->project_status === 'pending')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-hourglass-half"></i> Pending
                                    </span>
                                @elseif($project->project_status === 'hold')
                                    <span class="badge badge-danger">
                                        <i class="fas fa-pause-circle"></i> On Hold
                                    </span>
                                @elseif($project->project_status === 'finish')
                                    <span class="badge badge-primary">
                                        <i class="fas fa-check"></i> Finished
                                    </span>
                                @else
                                    <span class="badge badge-light">
                                        {{ ucfirst($project->project_status) }}
                                    </span>
                                @endif
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
                            // Assuming you're fetching the latest progress entry
$latestProgress = $project->progress->last(); // Get the last entry (latest progress)
$progress = $latestProgress ? $latestProgress->phase_progress : 0; // Fallback to 0 if no progress
$currentPhase = $latestProgress ? $latestProgress->phase : 'Not Started'; // Fallback to 'Not Started' if no phase

// Map phase identifiers to readable names
$phaseNames = [
    'phase_one' => 'Phase One',
    'phase_two' => 'Phase Two',
    'phase_three' => 'Phase Three',
    // Add more phases as needed
];

// Get the readable phase name, default to 'Not Started'
$readablePhase = $phaseNames[$currentPhase] ?? 'Not Started';

$phaseColor = 'bg-success'; // Default color for success
if ($progress < 50) {
    $phaseColor = 'bg-warning'; // Color for warning
} elseif ($progress == 100) {
    $phaseColor = 'bg-primary'; // Color for complete
                            }
                        @endphp
                        <div class="progress-bar {{ $phaseColor }}" role="progressbar"
                            style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0"
                            aria-valuemax="100">
                            {{ $progress }}%
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <i class="fas fa-check-circle"></i>
                        @if ($progress == 100)
                            Project Complete
                        @elseif ($progress > 0)
                            Phase: {{ $readablePhase }} (In Progress)
                        @else
                            Project Not Started
                        @endif
                    </div>
                </div>



                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('project.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>

                    @if ($project->project_status === 'active')
                        <a href="{{ route('progress.view', ['projectId' => $project->id]) }}"
                            class="btn btn-primary btn-md">
                            <i class="fas fa-sync-alt"></i> View Progress
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <style>
        /* Additional styles for a modern receipt look */
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

        .btn-info {
            background-color: #17a2b8;
            border: none;
        }

        .btn-info:hover {
            background-color: #138496;
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

        /* Style for Project Terms */
        .project-terms {
            margin-top: 20px;
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
        }

        .project-terms h4 {
            margin-bottom: 10px;
        }

        @media print {

            /* Ensure colors are retained when printing */
            body {
                -webkit-print-color-adjust: exact;
                /* Chrome, Safari */
                print-color-adjust: exact;
                /* Non-Webkit */
            }

            .btn {
                display: none;
                /* Hide buttons when printing */
            }

            /* Optional: hide unnecessary elements or modify styles */
            .card-header {
                background-color: #17a2b8;
                /* Ensure header color is retained */
            }

            .project-terms {
                background-color: #f1f1f1;
                /* Ensure background color is retained */
            }

            /* Align print buttons in the desired positions */
            .d-flex {
                justify-content: space-between !important;
            }
        }
    </style>

    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script>
        function printDocument() {
            window.print();
        }
    </script>
@endsection
