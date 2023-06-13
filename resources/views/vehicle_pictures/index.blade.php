@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Vehicle Pictures
        </h4>
        @can('vehicles-picture-create')
        <a  class="btn btn-sm btn-info float-end" href="{{ route('vehicle-pictures.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
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
        <div class="table-responsive">
            <table id="supplier-pictures-table" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>S.NO</th>
                    <th>VIN</th>
                    <th>GRN link</th>
                    <th>GDN link</th>
                    <th>Modified link</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($vehiclePictures as $key => $vehiclePicture)
                    <tr data-id="1">
                        <td>{{ ++$i }}</td>
                        <td>{{ $vehiclePicture->vehicle->vin ?? '' }}</td>
                        <td>{{ $vehiclePicture->GRN_link ?? '' }}</td>
                        <td>{{ $vehiclePicture->GDN_link ?? '' }}</td>
                        <td>{{ $vehiclePicture->modification_link ?? ''  }}</td>
                        <td>
                            @can('vehicles-picture-view')
                            <a href="{{ route('vehicle-pictures.show',$vehiclePicture->id) }}">
                                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> </button>
                            </a>
                            @endcan
                            @can('vehicles-picture-edit')
                            <a href="{{ route('vehicle-pictures.edit',$vehiclePicture->id) }}">
                                <button type="button" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> </button>
                            </a>
                            @endcan
                            @can('vehicles-picture-delete')
                            <button type="button" data-id="{{ $vehiclePicture->id }}" data-url="{{ route('vehicle-pictures.destroy',$vehiclePicture->id) }}"
                                    class="btn btn-danger btn-delete btn-sm"><i class="fa fa-trash"></i> </button>
                            @endcan
                        </td>
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



