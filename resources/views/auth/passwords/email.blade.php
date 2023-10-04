@extends('layouts.auth')
@section('content')
    <div class="text-center">
        <h5 class="mb-0">Reset Password</h5>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
    </div>
    @if (session('success'))
        <div class="alert alert-success" role="alert"> {{session('success')}}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert"> {{session('error')}}
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-sm">
                {{ __('Send Password Reset Link') }}
            </button>
        </div>
    </form>
@endsection
