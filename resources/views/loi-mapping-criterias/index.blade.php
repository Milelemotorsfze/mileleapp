@extends('layouts.table')
@section('content')
    @can('list-loi-mapping-criterias')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-loi-mapping-criterias');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    LOI Mapping Criteria
                </h4>
                @can('create-loi-mapping-criterias')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-loi-mapping-criterias');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end" href="{{ route('loi-mapping-criterias.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
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
                @if (Session::has('error'))
                    <div class="alert alert-danger" >
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('success') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Value</th>
                            <th>Type</th>
                            <th>Order</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                            @foreach ($loiMappingCriterias as $key => $loiMappingCriteria)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $loiMappingCriteria->name }}</td>
                                    <td>{{ $loiMappingCriteria->value }}</td>
                                    <td>{{ $loiMappingCriteria->value_type }}</td>
                                    <td>{{ $loiMappingCriteria->order }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($loiMappingCriteria->created_at)->format('d M Y') ?? '' }}</td>
                                    @canany(['edit-loi-mapping-criterias','delete-loi-mapping-criterias'])
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-loi-mapping-criterias','delete-loi-mapping-criterias']);
                                        @endphp
                                        @if ($hasPermission)
                                            <td>
                                                <a data-placement="top" href="{{ route('loi-mapping-criterias.edit', $loiMappingCriteria->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a data-placement="top" href="#" data-id="{{ $loiMappingCriteria->id }}" data-url="{{ route('loi-mapping-criterias.destroy', $loiMappingCriteria->id) }}" class="btn btn-danger btn-delete btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        @endif
                                    @endcan
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        $('.btn-delete').on('click',function(e){
            e.preventDefault();
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            _method: 'DELETE',
                            id: 'id',
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
    </script>
@endpush



