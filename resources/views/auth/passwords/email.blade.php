@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-lg" style="width: 100%; cursor: pointer;">

            <div class="card-body" style="padding: 20px; text-align: center;">
                <h4 class="card-title" style="font-weight: 800;">{{ __('Reset Password') }}</h4>
            </div>
            <hr style="border-top: 1px solid #010101; margin: 0;">

            <div class="card-body px-5">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="email" class="font-weight-bold">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-0 text-center">
                        <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold">
                            {{ __('Send Password Reset Link') }}
                        </button>
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
    }

    .form-control {
        border-radius: 8px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .invalid-feedback {
        color: #e3342f;
    }
</style>
@endsection
