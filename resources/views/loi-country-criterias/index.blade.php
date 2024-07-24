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
                        <a  class="btn btn-sm btn-info float-end" href="{{ route('loi-country-criterias.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
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

                <div class="table-responsive m-2">
                    <table id="loi-criteria-country-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.NO:</th>
                            <th>Country</th>
                            <th>Is LOI Restricted</th>
                            <th>Restricted Model Lines</th>
                            <th>Allowed Model Lines</th>
                            <th>Is Only Company Allowed</th>
                            <th>Maximum QTY/ Passport</th>
                            <th>Maximum QTY/ Company</th>
                            <th>Minimum QTY/ Company</th>
                            <th>Comment</th>
                            <th>Status</th>                                  
                            <th>Created At</th>    
                            <th>Created By</th>    
                            <th>Updated At</th>                                
                            <th>Updated By</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($loiCountryCriterias as $key => $loiCountryCriteria)
                            <tr>
                                
                                <td> {{ ++$i }}</td>                              
                                <td>{{ $loiCountryCriteria->country->name ?? '' }}</td>
                                <td>   @if($loiCountryCriteria->is_loi_restricted == true) Yes @else No @endif  </td>
                                <td>{{ implode(", ",$loiCountryCriteria->restricted_model_lines)  ?? '' }}</td>
                                <td>{{ implode(", ",$loiCountryCriteria->allowed_model_lines)  ?? '' }}</td>
                                <td> @if($loiCountryCriteria->is_only_company_allowed == true) Yes @else No @endif </td>
                                <td>{{ $loiCountryCriteria->max_qty_per_passport }}</td>
                                <td> {{ $loiCountryCriteria->max_qty_for_company }} </td>
                                <td>{{ $loiCountryCriteria->min_qty_for_company }}</td>
                                <td>{{ $loiCountryCriteria->comment}}</td>
                                <td>{{ $loiCountryCriteria->status }} </td>
                                <td>{{ \Illuminate\Support\Carbon::parse($loiCountryCriteria->created_at)->format('d M Y')  }}</td>
                                <td>{{ $loiCountryCriteria->createdBy->name ?? '' }} </td>     
                                <td>{{ \Illuminate\Support\Carbon::parse($loiCountryCriteria->updated_at)->format('d M Y')  }}</td>
                                <td>  {{ $loiCountryCriteria->updatedBy->name ?? '' }}</td>
                                <td>
                                @can('loi-restricted-country-edit')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-edit');
                                    @endphp
                                    @if ($hasPermission)
                                        <a data-placement="top" href="{{ route('loi-country-criterias.edit', $loiCountryCriteria->id) }}" class="btn btn-info btn-sm mt-1"><i class="fa fa-edit"></i></a>
                                    @endif
                                @endcan
                                @can('loi-restricted-country-active-inactive')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-active-inactive');
                                    @endphp
                                    @if ($hasPermission)
                                        @if($loiCountryCriteria->status == \App\Models\LoiCountryCriteria::STATUS_ACTIVE)
                                            <button data-url="{{ route('loi-country-criterias.active-inactive') }}" title="Make Inactive"  data-id="{{ $loiCountryCriteria->id }}"
                                                    data-status="{{ \App\Models\LoiCountryCriteria::STATUS_INACTIVE }}"  class="btn btn-success btn-sm btn-status-change mt-1"><i class="fa fa-check"></i></button>
                                        @else
                                            <button data-url="{{ route('loi-country-criterias.active-inactive') }}" title="Make Active"  data-id="{{ $loiCountryCriteria->id }}"
                                                    data-status="{{ \App\Models\LoiCountryCriteria::STATUS_ACTIVE }}" class="btn btn-secondary btn-sm btn-status-change mt-"><i class="fa fa-times"></i></button>
                                        @endif
                                    @endif
                                @endcan
                                @can('loi-restricted-country-delete')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-delete');
                                    @endphp
                                    @if ($hasPermission)
                                        <button data-url="{{ route('loi-country-criterias.destroy', $loiCountryCriteria->id ) }}" class="mt-1 btn btn-danger btn-sm btn-delete">
                                            <i class="fa fa-trash"></i></button>
                                    @endif
                                @endcan
                                </td>                           
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
            </div>
            </div>
        @endif
    @endcan
    <script>
        $('#loi-criteria-country-table').on('click', '.btn-delete', function (e) {
            var url = $(this).data('url');
            var confirm = alertify.confirm('Are you sure, Do you want to Delete this item ?',function (e) {
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

        $('#loi-criteria-country-table').on('click', '.btn-status-change', function (e) {
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
                            if(status == '{{ \App\Models\LoiCountryCriteria::STATUS_INACTIVE }}') {
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




