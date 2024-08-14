@extends('layouts.table')
@section('content')
    <div class="container text-center mt-5">
        <h1 class="display-4">Access Denied</h1>
        <p class="lead">You do not have permission to access this page.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
    </div>
@endsection