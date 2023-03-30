<!doctype html>

<html lang="en">
<head>
@include('partials.head-css')
</head>
<body data-layout="horizontal">
    <!-- Begin page -->
    <div id="layout-wrapper">
    @include('partials.horizontal')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- end page title -->
                                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add New Variants</h4>
                            </div>
                            <div class="card-body">
                               <div class="row">
										</div>  
                                        {!! Form::open(array('route' => 'variants.store','method'=>'POST')) !!}
                                            <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                            <label for="basicpill-firstname-input" class="form-label">Variants Name</label>
                                            {!! Form::text('variant_name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                            <input type="hidden" class="form-control" id="basicpill-firstname-input" name="user_id" value="">
                                            <input type="hidden" class="form-control" id="basicpill-firstname-input" name="status" value="New">
                                        </div>
										   <div class="col-lg-6 col-md-6">
                                            <label for="basicpill-firstname-input" class="form-label">Variants detail</label>
                                            {!! Form::text('variant_details', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                        </div>
										<div class="col-lg-6 col-md-6">
                                            <label for="basicpill-firstname-input" class="form-label">Model line</label>
                                            {!! Form::text('model_line', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                        </div>
										<div class="col-lg-6 col-md-6">
                                            <label for="basicpill-firstname-input" class="form-label">Model</label>
                                            {!! Form::text('model', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                        </div>
										<div class="col-lg-4 col-md-6">
                                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Brand</label>
                                                <select class="form-control" data-trigger name="brand" id="choices-single-default">
                                                    <option value='BENTLEY'>BENTLEY</option>
                                                    <option value='BMW'>BMW</option>
                                                    <option value='CADILAC'>CADILAC</option>
                                                    <option value='CHEVROLET'>CHEVROLET</option>
                                                    <option value='FERRARI'>FERRARI</option>
                                                    <option value='FORD'>FORD</option>
                                                    <option value='GMC'>GMC</option>
                                                    <option value='HINO'>HINO</option>
                                                    <option value='HYUNDAI'>HYUNDAI</option>
                                                    <option value='ISUZU'>ISUZU</option>
                                                    <option value='KIA'>KIA</option>
                                                    <option value='LAMBORGHINI'>LAMBORGHINI</option>
                                                    <option value='LAND ROVER'>LAND ROVER</option>
                                                    <option value='LEXUS'>LEXUS</option>
                                                    <option value='MERCEDES BENZ'>MERCEDES BENZ</option>
                                                    <option value='MITSUBISHI'>MITSUBISHI</option>
                                                    <option value='NISSAN'>NISSAN</option>
                                                    <option value='PEUGEOT'>PEUGEOT</option>
                                                    <option value='PORSCHE'>PORSCHE</option>
                                                    <option value='ROLLS ROYCE'>ROLLS ROYCE</option>
                                                    <option value='SUZUKI'>SUZUKI</option>
                                                    <option value='TESLA'>TESLA</option>
                                                    <option value='TOYOTA'>TOYOTA</option>
                                                    <option value='VOLKSWAGEN'>VOLKSWAGEN</option>
                                                </select>
                                        </div>
										<div class="col-lg-4 col-md-6">
                                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Steering</label>
                                                <select class="form-control" data-trigger name="steering" id="choices-single-default">
                                                    <option value='LHD'>LHD</option>
                                                    <option value='RHD'>RHD</option>
                                                </select>
                                        </div>
										<div class="col-lg-4 col-md-6">
                                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Seatss</label>
                                                <select class="form-control" data-trigger name="seats" id="choices-single-default">
												@for($i=1;$i<=30;$i++)
                                                    <option value="{{$i}}">{{$i}}</option>
												@endfor
                                                </select>
                                        </div>
										<div class="col-lg-6 col-md-6">
                                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Fuel</label>
                                                <select class="form-control" data-trigger name="fuel" id="choices-single-default">
                                                    <option value='Diesel'>Diesel</option>
                                                    <option value='EV'>EV</option>
                                                    <option value='Gasonline'>Gasonline</option>
                                                </select>
                                        </div>
										<div class="col-lg-6 col-md-6">
                                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Gear</label>
                                                <select class="form-control" data-trigger name="gear" id="choices-single-default">
                                                    <option value='Auto'>Auto</option>
                                                    <option value='Manual'>Manual</option>
                                                </select>
                                        </div>
										<div class="col-lg-6 col-md-6">
                                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Upholestry</label>
                                                <select class="form-control" data-trigger name="upholestry" id="choices-single-default">
                                                    <option value='Fabric'>Fabric</option>
                                                    <option value='Fabric & Leather'>Fabric & Leather</option>
                                                    <option value='Leather'>Leather</option>
                                                    <option value='Vinly'>Vinly</option>
                                                </select>
                                        </div>
										</div>   
										</div>   
										<div class="col-lg-12 col-md-12">
								<input type="submit" name="submit" value="submit" class="btn btn-dark btncenter" />
								</div>  
                                {!! Form::close() !!}
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
<script src="{{ asset ('libs/dropzone/min/dropzone.min.js') }}"></script>
<script src="{{ asset ('js/app.js') }}"></script>
</body>
</html>