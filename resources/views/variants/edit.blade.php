@extends('layouts.main')
<style>
    .heading-background {
  display: inline-block;
  background-color: #f2f2f2;
  padding: 5px 10px;
}
    </style>
@section('content')

    @can('variants-edit')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-edit');
        @endphp
        @if ($hasPermission)

    <div class="card-header">
        <h4 class="card-title">Edit Variant</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
        <form id="form-update" action="{{ route('variants.update', $variant->id) }}" method="POST" >
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label"> Brand</label>
                        <select class="form-control" autofocus name="brands_id" id="brand">
                            <option></option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"  {{ $variant->brands_id == $brand->id ? 'selected' : '' }}>{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Model</label>
                        <select class="form-control" autofocus name="master_model_lines_id" id="model">
                            <option></option>
                            @foreach($masterModelLines as $masterModelLine)
                                <option value="{{ $masterModelLine->id }}" {{ $variant->master_model_lines_id == $masterModelLine->id ? 'selected' : '' }}>
                                    {{ $masterModelLine->model_line }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Model Description</label>
                            <input type="text" value="{{ old('model_detail', $variant->model_detail) }}" name="model_detail" class="form-control "placeholder="Model Description" required>
                        </div>
                    </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Model Year</label>
                        @php
                            $currentYear = date("Y");
                            $years = range($currentYear + 10, $currentYear - 10);
                            $years = array_reverse($years);
                            @endphp
                            <select name="my" class="form-control">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ old('my') == $year || $variant->my == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Variant</label>
                        <input type="text" value="{{ old('name', $variant->name) }}" name="name" class="form-control " placeholder="Name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Variant Detail</label>
                        <input type="text" value="{{ old('detail', $variant->detail) }}" name="detail" class="form-control "placeholder="Detail">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Engine Capacity</label>
                            <input type="text" value="{{ old('engine_capacity', $variant->engine) }}" name="engine" class="form-control "placeholder="Engine Capacity" required>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Transmission</label>
                        <select class="form-control" autofocus name="gearbox" id="model">
                                <option value="Auto" {{ old('gearbox') == 'Auto' || $variant->gearbox == 'Auto' ? 'selected' : '' }}>Auto</option>
                                <option value="Manual" {{ old('gearbox') == 'Manual' || $variant->gearbox == 'Manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Fuel Type</label>
                        <select class="form-control" autofocus name="fuel_type" id="model">
                            <option value="Diesel" {{ (old('fuel_type') == 'Diesel' || $variant->fuel_type == 'Diesel') ? 'selected' : '' }}>Diesel</option>
                            <option value="EV" {{ (old('fuel_type') == 'EV' || $variant->fuel_type == 'EV') ? 'selected' : '' }}>EV</option>
                            <option value="Gasoline" {{ (old('fuel_type') == 'Gasoline' || $variant->fuel_type == 'Gasoline') ? 'selected' : '' }}>Gasoline</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Steering</label>
                        <select class="form-control" autofocus name="steering" id="model">
                            <option value="LHD" {{ (old('steering') == 'LHD' || $variant->steering == 'LHD') ? 'selected' : '' }}>LHD</option>
                            <option value="RHD" {{ (old('steering') == 'RHD' || $variant->steering == 'RHD') ? 'selected' : '' }}>RHD</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Seating Capacity</label>
                        <select name="seat" class="form-control">
                                @for($i = 1; $i <= 50; $i++)
                                    <option value="{{ $i }}" {{ old('seat') == $i || $variant->seat == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Upholestry</label>
                        <select class="form-control" autofocus name="upholestry" id="model">
                                <option value="Fabric" {{ old('upholestry') == 'Fabric' || $variant->upholestry == 'Fabric' ? 'selected' : '' }}>Fabric</option>
                                <option value="Leather" {{ old('upholestry') == 'Leather' || $variant->upholestry == 'Leather' ? 'selected' : '' }}>Leather</option>
                                <option value="Fabric + Leather" {{ old('upholestry') == 'Fabric + Leather' || $variant->upholestry == 'Fabric + Leather' ? 'selected' : '' }}>Fabric + Leather</option>
                                <option value="Fabric / Leather" {{ old('upholestry') == 'Fabric / Leather' || $variant->upholestry == 'Fabric / Leather' ? 'selected' : '' }}>Fabric / Leather</option>
                                <option value="Vinyl" {{ old('upholestry') == 'Vinyl' || $variant->upholestry == 'Vinyl' ? 'selected' : '' }}>Vinyl</option>
                            </select>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <h4 class="card-title heading-background text-center">Logs</h4>
	<div class="card-body">
    <div class="table-responsive">
            <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Changed By</th>
                        <th>Field</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($variantlog as $variantlog)
                <tr data-id="1">
                        <td>{{ date('d-m-Y', strtotime($variantlog->date)) }}</td>
                        <td>{{$variantlog->time}}</td>
                        <td>{{$variantlog->status}}</td>
                        <td>
                            @php
                                $change_by = DB::table('users')->where('id', $variantlog->created_by)->first();
                                $change_bys = $change_by->name;
                            @endphp
                            {{$change_bys}}
                        </td>
                        <td>
                            @if($variantlog->field == "fuel_type")
                                Fuel Type
                            @elseif($variantlog->field == "name")
                                Name
                            @elseif($variantlog->field == "brands_id")
                                Brand Name
                            @elseif($variantlog->field == "master_model_lines_id")
                                Model Line
                            @elseif($variantlog->field == "steering")
                                Steering
                            @elseif($variantlog->field == "name")
                            Name
                            @elseif($variantlog->field == "gearbox")
                            Gear Box
                            @elseif($variantlog->field == "my")
                            My
                            @elseif($variantlog->field == "seat")
                            Seat
                            @elseif($variantlog->field == "upholestry")
                            Upholestry
                            @elseif($variantlog->field == "engine")
                            Engine Capacity
                            @elseif($variantlog->field == "detail")
                                Variant Detail
                            @endif
                        </td>
                        @if($variantlog->field == "brands_id")
    @php
        $brandold = DB::table('brands')->where('id', $variantlog->old_value)->first();
        $old_values = $brandold->brand_name;
        $brandnew = DB::table('brands')->where('id', $variantlog->new_value)->first();
        $new_values = $brandnew->brand_name;
    @endphp
    <td>{{ $old_values }}</td>
    <td>{{ $new_values }}</td>
@elseif($variantlog->field == "master_model_lines_id")
    @php
        $modelold = DB::table('master_model_lines')->where('id', $variantlog->old_value)->first();
        $old_values = $modelold->model_line;
        $modelnew = DB::table('master_model_lines')->where('id', $variantlog->new_value)->first();
        $new_values = $modelnew->model_line;
    @endphp
    <td>{{ $old_values }}</td>
    <td>{{ $new_values }}</td>
@else
    <td>{{ $variantlog->old_value }}</td>
    <td>{{ $variantlog->new_value }}</td>
@endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        $('#brand').select2({
            placeholder: 'Select Brand'
        })
        $('#model').select2({
            placeholder: 'Select Model'
        })
        $('#brand').on('change', function() {
            $('#brand-error').remove();
            // Clear all related fields
            $('input[name="model_detail"]').val('');
            // If model description is a select, clear its options except default
            if ($('#model_detail').is('select')) {
                $('#model_detail').empty().append('<option value="">Select a Model</option>').val('').trigger('change');
            }
            $('input[name="steering"]').val('');
            $('input[name="engine"]').val('');
            $('input[name="fuel_type"]').val('');
            $('input[name="transmission"]').val('');
            $('input[name="window_type"]').val('');
            $('input[name="drive_train"]').val('');
            $('input[name="grade_name"]').val('');
            $('input[name="specialEditions"]').val('');
            $('input[name="others"]').val('');
            // Clear all specification selects if present
            $('#specification-details-container').empty();
            $('#selected_specifications').val('');
        })
        $('#model').on('change', function() {
            $('#model-error').remove();
            // Clear all related fields
            $('input[name="model_detail"]').val('');
            // If model description is a select, clear its options except default
            if ($('#model_detail').is('select')) {
                $('#model_detail').empty().append('<option value="">Select a Model</option>').val('').trigger('change');
            }
            $('input[name="steering"]').val('');
            $('input[name="engine"]').val('');
            $('input[name="fuel_type"]').val('');
            $('input[name="transmission"]').val('');
            $('input[name="window_type"]').val('');
            $('input[name="drive_train"]').val('');
            $('input[name="grade_name"]').val('');
            $('input[name="specialEditions"]').val('');
            $('input[name="others"]').val('');
            // Clear all specification selects if present
            $('#specification-details-container').empty();
            $('#selected_specifications').val('');
        })
        $("#form-update").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    string:true,
                    max:255
                },
                master_model_lines_id:{
                    required:true,
                },
                brands_id:{
                    required:true,
                },
            }
        });
    </script>
<script>
    $(document).ready(function() {
        function fetchModelLines(selectedBrandId) {
            $.ajax({
                url: '/get-model-lines/' + selectedBrandId,
                type: 'GET',
                success: function(data) {
                    $('#model').empty();
                    $.each(data, function(index, modelLine) {
                        $('#model').append('<option value="' + modelLine.id + '">' + modelLine.model_line + '</option>');
                    });
                },
                error: function(error) {
                    console.log('Error fetching model lines:', error);
                }
            });
        }

        // Trigger the AJAX call and populate the "Model Line" select element on page load
        var selectedBrandId = $('#brand').val();
        if (selectedBrandId) {
            fetchModelLines(selectedBrandId);
        }

        // Add an event listener to the "Brand" select element
        $('#brand').on('change', function() {
            var selectedBrandId = $(this).val();
            fetchModelLines(selectedBrandId);
        });
    });
</script>
@endpush

