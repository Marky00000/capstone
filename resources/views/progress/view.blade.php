@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h4 class="mb-4">Progress for Project: <span class="text-primary">{{ $project->id }}</span></h4>
        <h5 class="text-muted">Service: {{ $project->service->name }}</h5>

        <!-- Display Project Information -->
        <div class="mb-4">
            @if ($progress->isNotEmpty())
                <div class="p-3 bg-light rounded shadow-sm">
                    <strong>Current Phase:</strong> <span class="text-success">{{ $progress->last()->phase }}</span><br>
                    <strong>Current Progress:</strong> <span class="text-success">{{ $progress->last()->phase_progress }}%</span>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    No progress recorded for this project yet.
                </div>
            @endif
        </div>

        <!-- Table for Project Progress -->
        <h2 class="mb-4">Progress History</h2>
        
        <!-- Success message -->
        <div id="successMessage" class="alert alert-success" style="display: none;"></div>
        
        <table class="table table-hover table-bordered shadow-sm">
            <thead class="thead-light">
                <tr>
                    <th><i class="fas fa-chart-line"></i> Phase</th>
                    <th><i class="fas fa-chart-line"></i> Phase Progress</th>
                    <th><i class="fas fa-image"></i> Image</th>
                    <th><i class="fas fa-comment-dots"></i> Remarks</th>
                    <th><i class="fas fa-calendar-alt"></i> Updated at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($progress as $item)
                    <tr class="hover-animation">
                        <td>{{ $item->phase }}</td>
                        <td>{{ $item->phase_progress }}%</td>
                        <td>
                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="Project Image"
                                    class="img-thumbnail img-preview" data-image="{{ asset('storage/' . $item->image) }}"
                                    style="width: 100px; cursor: pointer;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $item->remarks }}</td>
                        <td>{{ $item->created_at->format('F j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a class="btn btn-secondary btn-md mt-4" href="{{ route('project.index') }}">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <!-- Modal for Updating Progress -->
        <div class="modal fade" id="updateProjectProgressModal" tabindex="-1" role="dialog"
            aria-labelledby="updateProjectProgressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="updateProjectProgressModalLabel">Update Project Progress</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="updateProjectProgressForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}">

                            <div class="form-group">
                                <div id="errorMessages" class="text-danger" style="display: none;"></div>
                                <input type="hidden" id="project_phase" name="phase" required>
                                <label>Current Phase</label>
                                <span id="currentPhaseDisplay" class="form-control-plaintext" readonly></span>
                            </div>

                            <div class="form-group">
                                <label for="project_phase_progress">Phase Progress</label>
                                <select id="project_phase_progress" class="form-control custom-select" name="phase_progress" required>
                                    <option value="">Select Progress</option>
                                    @for ($i = 0; $i <= 100; $i += 10)
                                        <option value="{{ $i }}">{{ $i }}%</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="project_image">Upload Image</label>
                                <input type="file" class="form-control-file" id="project_image" name="image" accept="image/*" required>
                            </div>

                            <div class="form-group">
                                <label for="project_remarks">Remarks</label>
                                <textarea class="form-control" id="project_remarks" name="remarks" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveProjectProgressButton" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Progress Image</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="expandedImage" src="" alt="Expanded Project Image" class="img-fluid rounded shadow-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        function openUpdateModal(projectId) {
            const lastProgress = @json($progress->last());
            let phase = "phase_one";
            let currentProgress = 0;

            if (lastProgress) {
                currentProgress = lastProgress.phase_progress;
                if (currentProgress == 100) {
                    phase = lastProgress.phase === "phase_one" ? "phase_two" : "phase_three";
                } else {
                    phase = lastProgress.phase;
                }
            }

            document.getElementById('project_id').value = projectId;
            document.getElementById('project_phase').value = phase;
            document.getElementById('currentPhaseDisplay').innerText = phase.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            $('#updateProjectProgressModal').modal('show');

            const progressSelect = document.getElementById('project_phase_progress');
            Array.from(progressSelect.options).forEach(option => {
                option.disabled = parseInt(option.value) <= currentProgress;
                option.classList.toggle('disabled-option', parseInt(option.value) <= currentProgress);
            });
        }

        document.getElementById('saveProjectProgressButton').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('updateProjectProgressForm'));

            fetch('{{ route('progress.store') }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    document.getElementById('errorMessages').innerHTML = data.error || 'An error occurred. Please try again.';
                    document.getElementById('errorMessages').style.display = 'block';
                }
            })
            .catch(() => {
                document.getElementById('errorMessages').innerHTML = 'An error occurred. Please try again.';
                document.getElementById('errorMessages').style.display = 'block';
            });
        });

        document.querySelectorAll('.img-preview').forEach(image => {
            image.addEventListener('click', function() {
                document.getElementById('expandedImage').src = this.dataset.image;
                $('#imageModal').modal('show');
            });
        });
    </script>
@endsection

<style>
    .btn {
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn:hover {
        transform: scale(1.05);
    }

    .hover-animation {
        transition: background-color 0.3s;
    }

    .disabled-option {
        background-color: #f0f0f0;
        color: #aaa;
        pointer-events: none;
    }

    .img-thumbnail {
        border: none;
        transition: transform 0.3s;
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .modal-header {
        border-bottom: none;
    }
</style>
@endsection
