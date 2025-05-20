@extends('layouts.table')
@section('content')
    <div class="container text-center mt-5">
        <h1 class="display-4">Something went wrong!</h1>
        <p class="lead">We encountered an unexpected error. Please contact the system administrator.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Go Back</a>
    </div>
@endsection