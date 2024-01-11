@extends('layouts.table')
@section('content')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-categories-list');
    @endphp
    @if ($hasPermission)
        <div class="card-header">
            <h4 class="card-title">
                Model Year Calculation Categories
            </h4>
            @can('model-year-calculation-categories-create')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-categories-create');
                @endphp
                @if ($hasPermission)
                    <a  class="btn btn-sm btn-info float-end" href="{{ route('model-year-calculation-categories.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
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
        <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Rule Name</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                    @foreach ($modelYearCategories as $key => $modelYearCategory)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $modelYearCategory->name }}</td>
                            <td>{{ $modelYearCategory->modelYearRule->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($modelYearCategory->created_at)->format('d M Y') }}</td>

                            <td>
                                @can('model-year-calculation-categories-edit')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-categories-edit');
                                    @endphp
                                    @if ($hasPermission)
                                        <a data-placement="top" href="{{ route('model-year-calculation-categories.edit', $modelYearCategory->id) }}"
                                           class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                    @endif
                                @endcan
                                @can('model-year-calculation-categories-delete')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-categories-delete');
                                    @endphp
                                    @if ($hasPermission)
                                        <a data-placement="top" href="#" data-id="{{ $modelYearCategory->id  }}" data-url="{{ route('model-year-calculation-categories.destroy', $modelYearCategory->id) }}"
                                           class="btn btn-danger btn-sm delete-button"><i class="fa fa-trash"></i></a>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <script type="text/javascript">
        $('.delete-button').on('click',function(){
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            _method: 'DELETE',
                            id: 'id',
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            alertify.success('Item deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
    </script>
@endsection





