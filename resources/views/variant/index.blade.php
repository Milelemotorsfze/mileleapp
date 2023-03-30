<!doctype html>
<html lang="en">
<head>
@include('partials.head-css')
</head>
<body data-layout="horizontal">
    <div id="layout-wrapper">
    @include('partials.horizontal')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Variant Info</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
                                        <thead>
                                            <tr>
                                                <th>Variant ID</th>
                                                <th>Name</th>
                                                <th>Variant Detail</th>
                                                <th>Model Line</th>
                                                <th>Model</th>
                                                <th>Brand</th>
                                                <th>Steering</th>
                                                <th>Seats</th>
                                                <th>Fuel</th>
                                                <th>Gear</th>
                                                <th>Upholestry</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $key => $master_variants)
                                            <tr data-id="1">
                                                <td>{{ $master_variants->id }}</td>
                                                <td>{{$master_variants->variant_name}}</td>
                                                <td>{{$master_variants->variant_details}}</td>
                                                <td>{{$master_variants->model_line}}</td>
                                                <td>{{$master_variants->model}}</td>
                                                <td>{{$master_variants->brand}}</td>
                                                <td>{{$master_variants->steering}}</td>
                                                <td>{{$master_variants->seats}}</td>
                                                <td>{{$master_variants->fuel}}</td>
                                                <td>{{$master_variants->gear}}</td>
                                                <td>{{$master_variants->upholestry}}</td>
                                                <td>
                                                <div class="row">
                                                <a class="btn btn-sm btn-success popup" onmouseover="myFunctionShow()" href="{{ route('variants.show',$master_variants->id) }}"><i class="fa fa-eye" aria-hidden="true"></i><span class="popuptext" id="show"></span></a>
                                                <a class="btn btn-sm btn-info popup" onmouseover="myFunctionEdit()" href="{{ route('variants.edit',$master_variants->id) }}"><i class="fa fa-edit" aria-hidden="true"></i><span class="popuptext" id="edit"></span></a>
                                                <a class="btn btn-sm btn-danger popup" onmouseover="myFunctionDelete()" href="{{ route('variants.destroy',$master_variants->id) }}"> <i class="fa fa-trash" aria-hidden="true"></i><span class="popuptext" id="delete"></span></a>
                                                </div>   
                                            </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.footer')
    </div>
</div>
@include('partials.right-sidebar')
@include('partials.vendor-scripts')
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset ('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function () {
    $('#dtBasicExample').DataTable();
});
</script>
</body>
</html>