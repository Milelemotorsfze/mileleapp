@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Master Models
        </h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ route('master-models.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
    </div>
    <div class="card-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br>
                <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('success') }}
            </div>
        @endif
    </div>
    <div class="m-3">
        {!! $html->table(['class' => 'table table-bordered table-striped table-responsive thead-dark']) !!}
    </div>
@endsection
@push('scripts')
    {!! $html->scripts() !!}
@endpush


