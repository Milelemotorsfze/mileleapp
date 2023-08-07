@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Variants Info
        </h4>
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('variant-edit');
        @endphp
        @if ($hasPermission)
            <a  class="btn btn-sm btn-info float-end" href="{{ route('variants.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create Varitants</a>
            <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
            <a  class="btn btn-sm btn-primary float-end" href="{{ route('brands.index') }}" ><i class="fa fa-info-circle" aria-hidden="true"></i> Brands</a>
            <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
            <a  class="btn btn-sm btn-primary float-end" href="{{ route('model-lines.index') }}" ><i class="fa fa-info-circle" aria-hidden="true"></i> Model Lines</a>
        @endif
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
        <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>Brand</th>
                    <th>Model Line</th>
                    <th>Model Description</th>
                    <th>Model Year</th>
                    <th>Variant</th>
                    <th>Variant Detail</th>
                    <th>Engine Capacity</th>
                    <th>Transmission</th>
                    <th>Fuel Type</th>
                    <th>Steering</th>
                    <th>Seating Capacity</th>
                    <th>Upholstery</th>                    
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('variant-edit');
                    @endphp
                    @if ($hasPermission)
                    <th>Action</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach ($variants as $key => $variant)
                    <tr data-id="1">
                        <td>{{ $variant->brand->brand_name ?? ''}}</td>
                        <td>{{ $variant->master_model_lines->model_line ?? '' }}</td>
                        <td>{{ $variant->model_detail ?? '' }}</td>
                        <td>{{ $variant->my ?? '' }}</td>
                        <td>{{ $variant->name }}</td>
                        <td>{{ $variant->detail ?? '' }}</td>
                        <td>{{ $variant->engine ?? '' }}</td>
                        <td>{{ $variant->gearbox ?? '' }}</td>
                        <td>{{ $variant->fuel_type ?? '' }}</td>
                        <td>{{ $variant->steering ?? '' }}</td>
                        <td>{{ $variant->seat ?? '' }}</td>
                        <td>{{ $variant->upholestry ?? '' }}</td>
                        
                        
                        
                        @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('variant-edit');
                    @endphp
                    @if ($hasPermission)
                    <td>
                                <a data-placement="top" href="{{ route('variants.edit', $variant->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>
                                </a>
                                </td>
                            @endif
                            <!-- @can('variants-delete')
                                @if($variant->is_deletable == true)
                                <a data-placement="top" id="{{ $variant->id }}" href="{{ route('variants.destroy',$variant->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
                                @endif
                            @endcan -->
                        
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
@endsection



