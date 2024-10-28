@extends('layouts.app')

@section('title', 'Task Log')
@section('content')
    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
            <h4 class="mb-0">Task Log</h4>
            <a href="{{ route('welcome') }}" class="btn btn-light text-info btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">
            @if ($taskLogs->isEmpty())
                <p class="text-muted">You do not have any task logs at this time.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th><i class="fas fa-numeric icon-faded-gray"></i> Task ID</th>
                                <th><i class="fas fa-id-badge icon-faded-gray"></i> Type ID</th>
                                <th><i class="fas fa-tools icon-faded-gray"></i> Type</th>
                                <th><i class="fas fa-flag-checkered icon-faded-gray"></i> Action</th>
                                <th><i class="fas fa-calendar-alt icon-faded-gray"></i> Action Date</th>
                                <th><i class="fas fa-cogs icon-faded-gray"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taskLogs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->type_id }}</td>
                                    <td>{{ $log->type }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->action_date)->format('F j, Y') }}</td>
                                    <td>
                                        <div style="display: flex; justify-content: space-evenly; align-items: center; gap: 10px; padding: 8px 0;">
                                            @if (trim($log->type) === 'Quotation')
                                                <a href="{{ route('quotation.details', $log->type_id) }}" class="btn btn-sm" style="background-color: transparent; border: none; color: #17a2b8;" data-toggle="tooltip" title="View Project">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @elseif (trim($log->type) === 'Booking')
                                                <a href="{{ route('booking.adminShow', $log->type_id) }}" class="btn btn-sm" style="background-color: transparent; border: none; color: #17a2b8;" data-toggle="tooltip" title="View Booking">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                {{-- Optional: Handle other types or do nothing --}}
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="pagination-wrapper">
                {{ $taskLogs->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 8px;
            border: none;
        }

        .card-header {
            border-bottom: none;
            padding: 1rem 1.5rem;
            font-size: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table {
            margin-bottom: 0;
            border: none;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: #f1f3f5;
        }

        .btn {
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .pagination-wrapper {
            margin-top: 20px;
            text-align: center;
        }

        .pagination-wrapper .pagination {
            display: inline-flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination-wrapper .page-item {
            margin: 0 2px;
        }

        .pagination-wrapper .page-link {
            padding: 10px 15px;
            border-radius: 8px;
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
        }

        .pagination-wrapper .page-link:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection
