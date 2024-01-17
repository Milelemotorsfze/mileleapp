@extends('layouts.table')
@section('content')
    @can('list-master-models')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-master-models');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    Master Models
                </h4>
                @can('create-master-models')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-master-models');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end" href="{{ route('master-models.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
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
                {!! $html->table(['class' => 'table table-bordered table-striped table-responsive thead-dark','id'=> 'model-table']) !!}
            </div>
    @endif
        @endcan
    <script>
        $('#model-table').on('click', '.btn-delete', function (e) {
            var url = $(this).data('url');
            var id = $(this).data('id');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            _method: 'DELETE',
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            alertify.success('Model Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
    </script>
@endsection
@push('scripts')
    {!! $html->scripts() !!}
@endpush


