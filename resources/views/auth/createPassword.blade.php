@extends('layouts.auth')
@section('content')
    <div class="text-center">
        <h5 class="mb-0">Create Password</h5>
    </div>
    <form method="POST" action="{{ route('otp.createPasswordOtpGenerate') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <label class="form-label">Password</label>
                </div>
            </div>
            <div class="input-group auth-pass-inputgroup">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <button class="btn btn-light ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <label class="form-label">Confirm Password</label>
                </div>
            </div>
            <div class="input-group auth-pass-inputgroup">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <button class="btn btn-light ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
            </div>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">
                {{ __('Create Password') }}
            </button>
        </div>
    </form>
@endsection
