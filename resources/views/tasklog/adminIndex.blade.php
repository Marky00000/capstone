@extends('layouts.apps')

@section('content')
    <div class="card shadow-sm rounded-lg border-1">
        <div class="card-header stylish-header text-black">
            <h1>Task Log</h1>
        </div>

        @if ($taskLogs->isEmpty())
            <p class="text-muted">You do not have any task logs at this time.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Task ID</th>
                            <th>Type ID</th>
                            <th>Type</th>
                            <th>Action</th>
                            <th>Action Date</th>
                            <th>Actions</th>
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
                                    <div
                                        style="display: flex; justify-content: space-evenly; align-items: center; gap: 10px; padding: 8px 0;">
                                        @if (trim($log->type) === 'Project')
                                            <a href="{{ route('project.adminShow', $log->type_id) }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Project">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (trim($log->type) === 'Booking')
                                            <a href="{{ route('booking.adminShow', $log->type_id) }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Booking">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (trim($log->type) === 'Payment')
                                            <a href="{{ route('admin.payments.show', $log->type_id) }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Payment">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (trim($log->type) === 'Progress')
                                            <a href="{{ route('progress.index', $log->type_id) }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Progress">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (trim($log->type) === 'Landscaping Service')
                                            <!-- Add this line -->
                                            <a href="{{ route('landscape') }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Landscaping Service">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @elseif (trim($log->type) === 'Swimmingpool Service')
                                            <!-- Add this line -->
                                            <a href="{{ route('swimmingpool') }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Landscaping Service">
                                                <i class="fas fa-eye"></i>  
                                            </a>
                                            @elseif (trim($log->type) === 'Renovation Service')
                                            <!-- Add this line -->
                                            <a href="{{ route('renovation') }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Landscaping Service">
                                                <i class="fas fa-eye"></i>  
                                            </a>
                                            @elseif (trim($log->type) === 'Package Service')
                                            <!-- Add this line -->
                                            <a href="{{ route('package') }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Landscaping Service">
                                                <i class="fas fa-eye"></i>  
                                            </a>
                                        @elseif (trim($log->type) === 'Archive Service')
                                            <!-- Add this line -->
                                            <a href="{{ route('archive.index') }}" class="btn btn-sm"
                                                style="background-color: transparent; border: none; color: #17a2b8; outline: none;"
                                                data-toggle="tooltip" title="View Landscaping Service">
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
                <div class="pagination-wrapper">
                    {{ $taskLogs->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    @endsection
