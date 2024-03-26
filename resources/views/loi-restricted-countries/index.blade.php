@extends('layouts.table')
@section('content')
    @can('loi-restricted-country-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    LOI Restricted Countries
                </h4>
                @can('loi-restricted-country-create')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-create');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end" href="{{ route('loi-restricted-countries.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
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
                {!! $html->table(['class' => 'table table-bordered table-striped table-responsive', 'id'=> 'country-table']) !!}
            </div>
        @endif
    @endcan
    <script>
        $('#country-table').on('click', '.btn-delete', function (e) {
            var url = $(this).data('url');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            alertify.success('Item Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });

        $('#country-table').on('click', '.btn-status-change', function (e) {
            var url = $(this).data('url');
            var id = $(this).data('id');
            var status = $(this).data('status');

            var confirm = alertify.confirm('Are you sure, Do you want to '+ status +' this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            id: id,
                            status:status,
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            if(status == '{{ \App\Models\LoiRestrictedCountry::STATUS_INACTIVE }}') {
                                $msg = 'Item Inactivated Successfully.';
                            }else{
                                $msg = 'Item Activated Successfully.';
                            }
                            alertify.success($msg);
                        }
                    });
                }
            }).set({title:"Status Change!"})
        });
    </script>
@endsection
@push('scripts')
    {!! $html->scripts() !!}
@endpush



