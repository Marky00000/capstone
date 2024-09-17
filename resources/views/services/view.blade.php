@extends('layouts.apps')

@section('title')

@section('content')

    <div class="service-container" style="position: relative; display: flex; flex-wrap: wrap; justify-content: center; gap: 30px; padding: 30px; background-color: #f7f7f7; border-radius: 8px;">
        <!-- Top Button inside the service container -->
        <a href="{{ route('welcome') }}" class="btn btn-outline-info" style="position: absolute; top: 20px; left: 20px;">
            <i class="fa fa-home"></i> Home
        </a>

        <h1 class="display-5" style="font-weight: 300; text-align: center; width: 100%;">{{ ucfirst($category) }} Services</h1>

        @if($services->isEmpty())
            <p class="text-center">No {{ $category }} services available at the moment.</p>
        @else
            @foreach($services as $service)
                <div class="card" style="width: 300px; display: flex; flex-direction: column; background-color: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
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
        border-color: #17a2b8;
        color: #17a2b8;
        background-color: transparent;
        border-radius: 50px;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease-in-out;
    }

    .btn-outline-info:hover {
        transform: scale(1.05);
        background-color: #17a2b8;
        color: #fff;
        border-color: #17a2b8;
    }

    /* Card Styles */
    .card {
        background-color: #ffffff; /* Ensure solid white background */
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Form Control Styles */
    .form-control, .form-select {
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    /* Alert Styles */
    .alert {
        border-radius: 8px;
    }
</style>
@endpush

@push('scripts')
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
@endpush
