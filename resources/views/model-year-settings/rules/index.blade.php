@extends('layouts.table')
@section('content')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-rules-list');
    @endphp
    @if ($hasPermission)
        <div class="card-header">
            <h4 class="card-title">
                Model Year Calculation Rules
            </h4>
            @can('model-year-calculation-rules-create')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-rules-create');
                @endphp
                @if ($hasPermission)
                    <a  class="btn btn-sm btn-info float-end" href="{{ route('model-year-calculation-rules.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
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
                    <th>Break Point</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                    @foreach ($modelYearRules as $key => $modelYearRule)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $modelYearRule->name }}</td>
                            <td>{{ $modelYearRule->value }}</td>
                            <td>{{ \Carbon\Carbon::parse($modelYearRule->created_at)->format('d M Y') }}</td>

                            <td>
                                @can('model-year-calculation-rules-edit')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-rules-edit');
                                    @endphp
                                    @if ($hasPermission)
                                        <a data-placement="top" href="{{ route('model-year-calculation-rules.edit', $modelYearRule->id) }}"
                                           class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                    @endif
                                @endcan
                                @can('model-year-calculation-rules-delete')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-rules-delete');
                                    @endphp
                                    @if ($hasPermission)
                                        <a data-placement="top" href="#" data-id="{{ $modelYearRule->id  }}" data-url="{{ route('model-year-calculation-rules.destroy', $modelYearRule->id) }}"
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
            var confirm = alertify.confirm('Are you sure you want to Delete this item ? Model Year Categories Under this Rule also be deleted.',function (e) {
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





