@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-lg" style="width: 100%; cursor: pointer;">
            <div class="card-img-top" style="background-image: url('{{ asset('background.jpg') }}'); height: 180px; background-size: cover; background-position: center;">
            </div>

            <div class="card-body" style="padding: 20px; text-align: center;">
                <h4 class="card-title" style="font-weight: 800;">{{ __('Register') }}</h4>
            </div>
            <hr style="border-top: 1px solid #010101; margin: 0;">



            <div class="card-body px-5">
                <form method="POST" action="{{ route('register') }}">
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
                        <button type="submit" class="btn btn-info btn-block py-2 font-weight-bold">
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
