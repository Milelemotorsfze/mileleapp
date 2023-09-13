@extends('layouts.auth')
@section('content')  
    <div class="text-center">
        <h5 class="mb-0">Reset Password</h5>
    </div>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="hidden" name="token" value="{{$token}}">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
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
                {{ __('Reset Password') }}
            </button>                    
        </div>                                       
    </form>
@endsection
                                   