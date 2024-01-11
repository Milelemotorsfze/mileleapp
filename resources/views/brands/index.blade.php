@extends('layouts.table')
@section('content')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-brand-list');
        @endphp
        @if ($hasPermission)
        <div class="card-header">
            <h4 class="card-title">
               Brands
            </h4>
            @can('master-brand-create')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-brand-create');
                @endphp
                @if ($hasPermission)
                <a  class="btn btn-sm btn-info float-end" href="{{ route('brands.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
                @endif
            @endcan
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
            {!! $html->table(['class' => 'table table-bordered table-striped table-responsive ']) !!}
        </div>
        @endif
@endsection
@push('scripts')
    {!! $html->scripts() !!}
@endpush



