@extends('layouts.main')
@section('content')
{{--    @php--}}
{{--        $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-model-create');--}}
{{--    @endphp--}}
{{--    @if ($hasPermission)--}}
        <div class="card-header">
            <h4 class="card-title">Add New Models</h4>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
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
            <form id="form-create" action="{{ route('master-models.store') }}" method="POST" >
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13 text-muted">Steering</label>
                            <select class="form-control" data-trigger name="steering" >
                                <option value="LHS">LHS</option>
                                <option value='RHS'>RHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="basicpill-firstname-input" class="form-label">Model</label>
                            <input type="text" class="form-control" name="model">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="basicpill-firstname-input" class="form-label">SFX</label>
                            <input type="text" class="form-control"  name="sfx">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="basicpill-firstname-input" class="form-label">Amount in USD</label>
                            <input type="number" class="form-control"  name="amount_uae">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="basicpill-firstname-input" class="form-label">Amount in EUR</label>
                            <input type="number" class="form-control" name="amount_belgium">
                        </div>
                    </div>
                    </br>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary ">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
{{--    @endif--}}
@endsection
@push('scripts')
    <script>
        $("#form-create").validate({
            ignore: [],
            rules: {
                steering: {
                    required: true,
                    maxlength:255
                },
            },
        });
    </script>
@endpush






















<!doctype html>
<html lang="en">
<head>
@include('partials.head-css')
</head>
<body data-layout="horizontal">
    <!-- Begin page -->
    <div id="layout-wrapper">
    @include('partials.horizontal')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- end page title -->
                                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add New Models</h4>
                            </div>
                            <div class="card-body">
                               <div class="row">
                        </div>
                            <form action="/addnewmodelrecord" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                    <label for="choices-single-default" class="form-label font-size-13 text-muted">Steering</label>
                                        <select class="form-control" data-trigger name="steering" id="choices-single-default">
                                            <option value="LHS">LHS</option>
                                            <option value='RHS'>RHS</option>
                                        </select>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <label for="basicpill-firstname-input" class="form-label">Model</label>
                                    <input type="text" class="form-control" id="basicpill-firstname-input" name="model">
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <label for="basicpill-firstname-input" class="form-label">SFX</label>
                                    <input type="text" class="form-control" id="basicpill-firstname-input" name="sfx">
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <label for="basicpill-firstname-input" class="form-label">Amount in USD</label>
                                    <input type="number" class="form-control" id="basicpill-firstname-input" name="amount_uae">
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <label for="basicpill-firstname-input" class="form-label">Amount in EUR</label>
                                    <input type="number" class="form-control" id="basicpill-firstname-input" name="amount_belgium">
                                </div>
                                </div>

                                           <div class="col-lg-12 col-md-12">
                            <input type="submit" name="submit" value="submit" class="btn btn-dark btncenter" />
                            </div>
                            </form>
                        </br>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @include('partials.footer')
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->
@include('partials.right-sidebar')
<!-- JAVASCRIPT -->
@include('partials.vendor-scripts')
<!-- dropzone js -->
<script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
