@extends('layouts.apps')

@section('content')
    <div class="container">


        <!-- Display Project Information -->
        <div class="mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Progress for Project: <span class="text-light">{{ $project->id }}</span></h4>
                </div>
                <div class="card-body">
                    <h5 class="text-muted">Services:
                        @php
                            // Decode service IDs from JSON format
                            $serviceIds = $project->service_ids ? json_decode($project->service_ids) : [];
                            // Retrieve services based on decoded IDs
                            $services = !empty($serviceIds)
                                ? \App\Models\Service::whereIn('id', $serviceIds)->get()
                                : collect();
                        @endphp

                        @if ($services->isNotEmpty())
                            @foreach ($services as $service)
                                <span class="badge bg-secondary text-white">{{ $service->name }}</span>
                                @if (!$loop->last)
                                    <span class="text-muted">, </span>
                                @endif <!-- Add a comma between services except for the last one -->
                            @endforeach
                        @else
                            <span class="text-danger">No services found</span>
                        @endif
                    </h5>

                    @php
                        $lastProgress = $progress->last(); // Get the last progress entry
                    @endphp

                    <div class="mt-3">
                        @if ($progress->isNotEmpty())
                            <!-- Check if there is any progress -->
                            @if ($lastProgress->phase == 'phase_three' && $lastProgress->phase_progress == 100)
                                <strong class="text-success">Current Phase:</strong>
                                <i class="fas fa-check-circle text-success"></i>
                                <span class="text-success ms-2">Project Finished</span>
                            @else
                                <strong><i class="fas fa-hourglass-half text-warning"></i> Current Phase:</strong>
                                <span class="text-success ms-2">{{ $lastProgress->phase }}</span><br>
                                <strong><i class="fas fa-tasks text-info"></i> Current Progress:</strong>
                                <span class="text-success ms-2">{{ $lastProgress->phase_progress }}%</span>
                            @endif
                        @else
                            <div class="text-center text-danger">
                                <i class="fas fa-exclamation-circle fa-2x"></i><br>
                                <strong>No progress recorded for this project yet.</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>






        <!-- Success message -->
        <div id="successMessage" class="alert alert-success" style="display: none;"></div>
        <table class="table table-striped">
            <thead class="thead">
                <tr>
                    <th><i class="fas fa-chart-line"></i> Phase</th>
                    <th><i class="fas fa-chart-line"></i> Phase Progress</th>
                    <th><i class="fas fa-image"></i> Image</th>
                    <th><i class="fas fa-comment-dots"></i> Remarks</th>
                    <th><i class="fas fa-calendar-alt"></i>Updated at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($progress as $item)
                    <tr>
                        <td class="hover-animation">{{ $item->phase }}</td>
                        <td class="hover-animation">{{ $item->phase_progress }}%</td>
                        <td>
                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="Project Image"
                                    class="img-thumbnail img-preview" data-image="{{ asset('storage/' . $item->image) }}"
                                    style="width: 100px; cursor: pointer;">
                            @else
                                No Image
                            @endif
                        </td>
                        <td class="hover-animation">{{ $item->remarks }}</td>
                        <td class="hover-animation">{{ $item->created_at->format('F j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a class="btn btn-secondary btn-md" href="{{ route('project.adminIndex') }}" data-bs-toggle="tooltip"
            data-bs-placement="top" title="Go back to the project overview">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        @php
            // Get the latest progress entry, if available
            $latestProgress = $progress->last(); // Use last() to get the most recent progress entry
        @endphp

        @if (!$latestProgress || !($latestProgress->phase === 'phase_three' && $latestProgress->phase_progress === '100'))
            <button class="btn btn-warning btn-md position-relative" onclick="openUpdateModal('{{ $project->id }}')"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Update the project's progress">
                <i class="fas fa-plus-circle"></i> Update Progress
                <span class="spinner-border spinner-border-sm position-absolute" id="loadingSpinner" style="display: none;"
                    role="status" aria-hidden="true"></span>
            </button>
        @endif





        <!-- Modal for Updating Progress -->
        <div class="modal fade" id="updateProjectProgressModal" tabindex="-1" role="dialog"
            aria-labelledby="updateProjectProgressModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="updateProjectProgressModalLabel">Update Project Progress</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                <!-- Display current phase -->
                            </div>

                            <div class="form-group">
                                <label for="project_phase_progress">Phase Progress</label>
                                <select id="project_phase_progress" class="form-control custom-select" name="phase_progress"
                                    required>
                                    <option value="">Select Progress</option>
                                    @for ($i = 0; $i <= 100; $i += 10)
                                        <option value="{{ $i }}">{{ $i }}%</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="project_image">Upload Image</label>
                                <input type="file" class="form-control-file" id="project_image" name="image"
                                    accept="image/*" required>
                            </div>

                            <div class="form-group">
                                <label for="project_remarks">Remarks</label>
                                <textarea class="form-control" id="project_remarks" name="remarks" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveProjectProgressButton" class="btn btn-primary">Save
                            changes</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Progress Image</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img id="expandedImage" src="" alt="Expanded Project Image" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>

    </div>

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openUpdateModal(projectId) {
            const lastProgress = @json($progress->last());
            let phase = "phase_one";
            let currentProgress = 0;

            if (lastProgress) {
                currentProgress = lastProgress.phase_progress;
                if (currentProgress == 100) {
                    if (lastProgress.phase === "phase_one") {
                        phase = "phase_two";
                    } else if (lastProgress.phase === "phase_two") {
                        phase = "phase_three";
                    }
                } else {
                    phase = lastProgress.phase;
                }
            }

            document.getElementById('project_id').value = projectId;
            document.getElementById('project_phase').value = phase;
            document.getElementById('currentPhaseDisplay').innerText = phase.replace(/_/g, ' ').replace(/\b\w/g, c => c
                .toUpperCase());
            document.getElementById('errorMessages').style.display = 'none';
            document.getElementById('successMessage').style.display = 'none';
            $('#updateProjectProgressModal').modal('show');

            const progressSelect = document.getElementById('project_phase_progress');
            for (let option of progressSelect.options) {
                option.disabled = false;
                option.classList.remove('disabled-option');
            }

            if (lastProgress && lastProgress.phase === phase) {
                for (let option of progressSelect.options) {
                    if (parseInt(option.value) <= currentProgress) {
                        option.disabled = true;
                        option.classList.add('disabled-option');
                    }
                }
            }
        }

        document.getElementById('saveProjectProgressButton').addEventListener('click', function() {
            const saveButton = this;
            const imageInput = document.getElementById('project_image');
            const phaseInput = document.getElementById('project_phase');
            const progressInput = document.getElementById('project_phase_progress');
            const remarksInput = document.getElementById('project_remarks');
            const errorMessagesDiv = document.getElementById('errorMessages');
            const successMessageDiv = document.getElementById('successMessage');
            let errorMessages = [];

            if (!progressInput.value) {
                errorMessages.push('Phase progress is required.');
            }
            if (!imageInput.files.length) {
                errorMessages.push('Image is required.');
            }

            if (errorMessages.length) {
                errorMessagesDiv.innerHTML = errorMessages.join('<br>');
                errorMessagesDiv.style.display = 'block';
                successMessageDiv.style.display = 'none';
                return;
            } else {
                errorMessagesDiv.style.display = 'none';
            }

            // Change button text to "Saving Changes..."
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving Changes...';

            const formData = new FormData(document.getElementById('updateProjectProgressForm'));

            fetch('{{ route('progress.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Progress saved successfully!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.error || 'An error occurred. Please try again.',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                    saveButton.innerHTML = 'Save changes';
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    saveButton.innerHTML = 'Save changes';
                    console.error('Error:', error);
                });
        });

        document.querySelectorAll('.img-preview').forEach(image => {
            image.addEventListener('click', function() {
                const imgSrc = this.dataset.image;
                const expandedImage = document.getElementById('expandedImage');
                expandedImage.src = imgSrc;
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
