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
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group mb-3">
                        <label for="email" class="font-weight-bold">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autofocus placeholder="Enter your email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="font-weight-bold">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Enter your new password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password-confirm" class="font-weight-bold">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm your new password">
                    </div>

                    <div class="form-group mb-0 text-center">
                        <button type="submit" class="btn btn-info btn-block py-2 font-weight-bold">
                            {{ __('Reset Password') }}
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
</style>
@endsection
