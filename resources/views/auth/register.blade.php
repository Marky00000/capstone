@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-8 position-relative">
        <div class="card shadow-sm border-0 rounded-lg" style="width: 100%; cursor: pointer; position: relative;">
            <div class="card-img-top" style="background-image: url('{{ asset('background.jpg') }}'); height: 180px; background-size: cover; background-position: center;">
            </div>

            <!-- Spinner and "Please Wait" message -->
            <div id="loadingSpinner" class="d-none justify-content-center align-items-center" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; background-color: rgba(255, 255, 255, 0.8);">
                <div class="text-center">
                    <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Please wait...</p>
                </div>
            </div>

            <div class="card-body" style="padding: 20px; text-align: center;">
                <h4 class="card-title" style="font-weight: 800;">{{ __('Register') }}</h4>
            </div>

            <!-- Success and error message modals -->
            @if (session('status'))
                <div class="alert alert-success fade-in-out position-absolute">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger fade-in-out position-absolute">
                    {{ session('error') }}
                </div>
            @endif

            <hr style="border-top: 1px solid #010101; margin: 0; position: relative; z-index: 1;">

            <div class="card-body px-5">
                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name" class="font-weight-bold">{{ __('Name') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus placeholder="Enter your name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="font-weight-bold">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="font-weight-bold">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Enter your password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password-confirm" class="font-weight-bold">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm your password">
                    </div>

                    <div class="form-group mb-0 text-center">
                        <button type="submit" id="submitBtn" class="btn btn-info btn-block py-2 font-weight-bold">
                            {{ __('Register') }}
                        </button>
                    </div>

                    <div class="form-group mt-3 text-center">
                        <p>{{ __("Already have an account?") }} <a href="{{ route('login') }}">{{ __('Login') }}</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        background: #ffffff;
        position: relative;
    }

    .form-control {
        border-radius: 8px;
    }

    .btn-info {
        background-color: #17a2b8;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-info:hover {
        background-color: #138f99;
    }

    .invalid-feedback {
        color: #e3342f;
    }

    .fade-in-out {
        opacity: 1;
        transition: opacity 1s ease-in-out;
        z-index: 2;
    }

    .fade-in-out.hidden {
        opacity: 0;
    }

    .position-absolute {
        position: absolute;
        top: 10px;
        left: 0;
        right: 0;
        margin: 0 auto;
        text-align: center;
    }

    hr {
        position: relative;
        z-index: 1;
    }

    /* Spinner and loading text */
    #loadingSpinner {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 9999;
    }

    #loadingSpinner .spinner-border {
        width: 3rem;
        height: 3rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fade out the alert messages
        setTimeout(function () {
            const alerts = document.querySelectorAll('.fade-in-out');
            alerts.forEach(alert => {
                alert.classList.add('hidden');
            });
        }, 2000); // 2000 milliseconds = 2 seconds

        // Handle form submission and show loading spinner
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form from submitting immediately

            // Hide the submit button and show the loading spinner
            document.getElementById('submitBtn').classList.add('d-none');
            document.getElementById('loadingSpinner').classList.remove('d-none');

            // Submit the form after showing the spinner
            this.submit();
        });
    });
</script>
@endsection
