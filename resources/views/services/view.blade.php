
@extends('layouts.apps')

@section('title')

@section('content')
        <h1 class="display-5" style="font-weight: 300; text-align: center; width: 100%;">{{ ucfirst($category) }} Services</h1>

        @if($services->isEmpty())
            <p class="text-center">No {{ $category }} services available at the moment.</p>
        @else
            @foreach($services as $service)
                <div class="card" style="width: 300px; display: flex; flex-direction: column; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <img src="{{ asset('storage/' . $service->design) }}" class="card-img-top" alt="{{ $service->name }}" style="width: 100%; height: 180px; object-fit: cover;">
                    <div class="card-body" style="flex: 1; padding: 20px; text-align: center;">
                        <h5 class="card-title" style="font-weight: 800;">{{ $service->name }}</h5>
                        <p class="card-text">{{ $service->description }}</p>
                    </div>
                    <div class="card-footer" style="text-align: center; padding: 10px; border-top: 1px solid #ddd;">
                        <a href="{{ route('booking.form') }}" class="btn btn-outline-info">Book Service</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@push('styles')
<style>
    /* Button Outline Info */
    .btn-outline-info {
        border-color: #17a2b8; /* Border color */
        color: #17a2b8; /* Text color */
        background-color: transparent; /* Transparent background */
        border-radius: 50px; /* Rounded corners */
        padding: 0.5rem 1rem; /* Padding inside the button */
        font-size: 1rem; /* Font size */
        text-align: center; /* Center text */
        text-decoration: none; /* Remove underline */
        display: inline-block; /* Inline block for spacing */
        transition: all 0.3s ease-in-out; /* Smooth transition */
    }

    .btn-outline-info:hover {
        transform: scale(1.05); /* Slightly enlarge the button */
        background-color: #17a2b8; /* Darker blue for hover effect */
        color: #fff; /* Ensure text is white on hover */
        border-color: #17a2b8; /* Match border color on hover */
    }

    /* Card Styles */
    .card {
        border-radius: 10px; /* Rounded corners for card */
    }

    /* Form Control Styles */
    .form-control, .form-select {
        border-radius: 8px; /* Rounded corners for form controls */
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }

    /* Alert Styles */
    .alert {
        border-radius: 8px; /* Rounded corners for alerts */
    }
</style>
@endpush

@push('scripts')
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
@endpush
