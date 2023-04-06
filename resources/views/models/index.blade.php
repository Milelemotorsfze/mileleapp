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
                                <h4 class="card-title">Models Info</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
                                        <thead>
                                            <tr>
                                                <th>Steering</th>
                                                <th>Model</th>
                                                <th>SFX</th>
                                                <th>Currency & Amount UAE</th>
                                                <th>Currency & Amount Belgium</th>
                                                <th>Update</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($data as $key => $models)
                                            <tr data-id="1">
                                                <td style="width: 80px">{{ $models->steering }}</td>
                                                <td style="width: 80px">{{ $models->model }}</td>
                                                <td>{{ $models->sfx }}</td>
                                                <td>{{ $models->amount_uae }} USD</td>
                                                <td>{{ $models->amount_belgium }} EUR</td>
                                                <td><a href="{{ route('carmodels.edit',$models->id) }}"><button class="btn btn-primary modal-button" type="button">Edit</button></a></td>
											@endforeach
                                        </tr>
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
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function () {
    $('#dtBasicExample').DataTable();
});
</script>
</body>
</html>