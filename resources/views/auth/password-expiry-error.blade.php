@extends('layouts.auth')

@section('content')
    <section class="content">
        <div class="error-page">
            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle " style="color: #c06c13"></i> Oops! Password Reset Link Expired.</h3>
                <p>Please click here to return forgot password page!  </p>
                <p>  <a href="{{ route('password.request') }}" class="btn btn-primary"> Go to forgot password</a> </p>
            </div>
        </div>
    </section>
@endsection


