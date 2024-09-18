@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-lg" style="width: 100%; cursor: pointer;">
            <div class="card-body" style="padding: 20px; text-align: center;">
                <h4 class="card-title" style="font-weight: 800;">{{ __('Confirm OTP') }}</h4>
            </div>
            <hr style="border-top: 1px solid #010101; margin: 0;">

            <div class="card-body px-5">
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif

                <form method="POST" action="{{ route('confirm.login.with.otp.post') }}">
                    @csrf

                    <div class="form-group mb-3 text-center">
                        <label for="otp" class="font-weight-bold">{{ __('Enter OTP') }}</label>
                        <div class="d-flex justify-content-center mt-3">
                            @for ($i = 0; $i < 6; $i++)
                                <input id="otp{{ $i }}" type="text" class="form-control otp-input mx-1 @error('otp') is-invalid @enderror" name="otp[]" maxlength="1" required style="width: 40px; text-align: center;">
                            @endfor
                        </div>
                        @error('otp')
                            <span class="invalid-feedback d-block text-center mt-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-0 text-center">
                        <button type="submit" class="btn btn-info btn-block py-2 font-weight-bold">
                            {{ __('Verify OTP') }}
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const otpInputs = document.querySelectorAll('.otp-input');
        
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function () {
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && index > 0 && this.value === '') {
                    otpInputs[index - 1].focus();
                }
            });
        });
    });
</script>
@endsection
