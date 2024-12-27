@extends('layouts.table')
@section('content')
    <div class="container text-center mt-5">
        <h1 class="display-4">500 - Application Error</h1>
        <p class="lead">
            We’re currently experiencing an issue. Please try again later. If the issue persists, kindly 
            <a href="mailto:support.dev@milele.com">contact the IT Development Support Team</a> for further assistance.
        </p>
        <p>Thank you for your understanding and patience.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
    </div>
@endsection