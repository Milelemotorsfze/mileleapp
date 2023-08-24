@extends('layouts.main')
@section('content')
    <style>
        .widthinput
        {
            height:32px!important;
        }
    </style>
    <div class="card-header">
        <h4 class="card-title">Add New Vehicle Picture</h4>
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
        @can('vehicles-picture-create')
        <form id="form-create" action="{{ route('vehicle-pictures.store') }}" method="POST" >
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form_field_outer" >
                        <div class="row form_field_outer_row" id="row-1">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> VIN</label>
                                    <select class="form-control widthinput vehicles" required  id="vin-input" name="vin[]" >
                                        @foreach($vins as $vin)
                                            <option value="{{ $vin->vin }}">{{ $vin->vin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Pictures Category</label>
                                    <select id="category" class="form-control mb-1">
                                <option value="GRN">GRN</option>
                                <option value="Modification">Modification</option>
                                <option value="PDI">PDI</option>
                                </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Vehicle Picture Link</label>
                                    <input type="url"  name="vehicle_picture_link[]" class="form-control widthinput " required placeholder="Vehicle Picture Link">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id ="rows-container">
        </div>
    </br>
                    <div class="row">
        <div class="col-lg-2 col-md-6">
        <div class="btn btn-primary add-row-btn" data-row="1">
         <i class="fas fa-plus"></i> Add More
        </div>
        </div>
        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
        <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
    </div>
        </form>
        @endcan
    </div>
    </div>
    <input type="hidden" id="indexValue" value="">

@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#category').select2();
        $('#vehicles-1').select2();
    });
</script>
    <script>
        var index = 1;
        $('#indexValue').val(index);
        $('#vehicles-1').select2({
            placeholder: 'Choose Vehicles',
            maximumSelectionLength:1,
            allowClear: true

        })

        $("#form-create").validate({
            rules: {
                'vins[]': {
                    required: true,
                },
                'vehicle_picture_link[]':{
                    url:true,
                    required: true
                },

            }
        });
        // $('#vehicles-1').on('change',function(){
            function showVariantDetail(index) {
                $('#vehicles-'+ index +'-error').remove();
                var vehicle_id = $('#vehicles-'+index).val();
                let url = '{{ route('vehicle-pictures.variant-details') }}'
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: vehicle_id,
                    },
                    success:function (response) {
                        $('#variant-detail-'+index).val(response);
                    }
                });
            }

        // })
        $(document.body).on('select2:select', ".vehicles", function (e) {
            var index = $(this).attr('data-index');
            var value = e.params.data.id;
            hideOption(index,value);
            showVariantDetail(index);
        });
        $(document.body).on('select2:unselect', ".vehicles", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            appendOption(index,data);
        });
        function addOption(id,text) {
            var indexValue = $('#indexValue').val();
            for(var i=1;i<=indexValue;i++) {
                $('#vehicles-'+i).append($('<option>', {value: id, text :text}))
            }
        }

        function hideOption(index,value) {
            var indexValue = $('#indexValue').val();
            for (var i = 1; i <= indexValue; i++) {
                if (i != index) {
                    var currentId = 'vehicles-' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        function appendOption(index,data) {
            var indexValue = $('#indexValue').val();
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#vehicles-'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }
        $(document.body).on('click', ".removeButton", function (e) {
            var indexNumber = $(this).attr('data-index');

            $(this).closest('#row-'+indexNumber).find("option:selected").each(function() {
                var id = (this.value);
                var text = (this.text);
                addOption(id,text)
            });

            $(this).closest('#row-'+indexNumber).remove();
            $('.form_field_outer_row').each(function(i){
                var index = +i + +1;
                $(this).attr('id','row-'+ index);
                $(this).find('select').attr('data-index', index);
                $(this).find('select').attr('id','vehicles-'+ index);
                $(this).find('.variant-detail').attr('id','variant-detail-'+index);
                $(this).find('.select').attr('data-select2-id','select2-data-vehicles-'+index);

                $(this).find('button').attr('data-index', index);
                $(this).find('button').attr('id','remove-'+ index);
                $('#vehicles-'+index).select2
                ({
                    placeholder: 'Choose Vehicles',
                    maximumSelectionLength:1,
                    allowClear: true
                });
            });
        })
        function clickAdd()
        {
            var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;

            $('#indexValue').val(index);
            var selectedVehicles = [];
            for(let i=1; i<index; i++)
            {
                var eachSelectedVehicle = $("#vehicles-"+i).val();
                if(eachSelectedVehicle) {
                    selectedVehicles.push(eachSelectedVehicle);
                }
            }

            $.ajax({
                url: '{{ route('vehicles.vehiclesdetails') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    vin: selectedVin
                },
                success: function(response) {
                    $('#variant').val(response.variant);
                    $('#brand').val(response.brand);
                    $('#model_line').val(response.modelLine);
                $.ajax({
                        url: '{{ route('vehicles.checkEntry') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            vin: selectedVin
                        },
                        success: function(response) {
                            if (response.entryExists) {
                                var existingCategory = response.category;

                                $('#category option').each(function() {
                                    if ($(this).val() === existingCategory) {
                                        $(this).attr('disabled', true);
                                    } else {
                                        $(this).attr('disabled', false);
                                    }
                                });

                                // Reset the selected option in the category dropdown
                                $('#category').val('').trigger('change');
                            } else {
                                // If the entry doesn't exist, enable all options and reset selection
                                $('#category option').attr('disabled', false);
                                $('#category').val('').trigger('change');
                            }
                        }
                    });
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        var row = 1;
        $('.add-row-btn').click(function() {
            row++;
            var newRow = `
                <div class="row" data-row="${row}">
                    <div class="col-lg-2 col-md-6">
                        <select name="vin[]" class="form-control mb-1 vin" id="vin${row}">
                            <option value="" selected disabled>Select VIN</option>
                            @foreach($vins as $vin)
                                <option value="{{ $vin->vin }}">{{ $vin->vin }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <select name="category[]"id="category${row}" class="form-control mb-1" Required>
                                <option value="GRN">GRN</option>
                                <option value="Modification">Modification</option>
                                <option value="PDI">PDI</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-6">
                        <input type="text" id="brand${row}" name="brand[]" class="form-control" placeholder="Brand" readonly>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <input type="text" id="model-line${row}" name="model-line[]" class="form-control" placeholder="Model Line" readonly>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <input type="text" id="variant${row}" name="variant[]" class="form-control" placeholder="Variant" readonly>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <input type="url" name="vehicle_picture_link[]" class="form-control widthinput" required placeholder="Vehicle Picture Link">
                    </div>
                    <div class="col-lg-1 col-md-6">
                        <button type="button" class="btn btn-danger btn-sm remove-row-btn" data-row="${row}">Remove</button>
                    </div>
                </div>
                `;
            $('#rows-container').append(newRow);
            $('#vin' + row).select2(); // Move this line inside the click event handler
            $('#category' + row).select2(); // Move this line inside the click event handler

            // Disable the selected VIN in other dropdowns
            $('.vin').each(function() {
                var selectedVin = $(this).val();
                if (selectedVin) {
                    $('.vin').not(this).find('option[value="' + selectedVin + '"]').prop('disabled', true);
                }
            });
        });

        $('#rows-container').on('change', '.vin', function() {
            var selectedVin = $(this).val();
            var row = $(this).closest('.row').data('row');
            var brandField = $('#brand' + row);
            var modelLineField = $('#model-line' + row);
            var variantField = $('#variant' + row);
            console.log(brandField);
            $.ajax({
                url: '{{ route('vehicles.vehiclesdetails') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    vin: selectedVin
                },
                success: function(response) {
                    variantField.val(response.variant);
                    brandField.val(response.brand);
                    modelLineField.val(response.modelLine);
                }
            });

            // Re-enable the previously disabled VIN in other dropdowns
            $('.vin').not(this).find('option').prop('disabled', false);
            // Disable the newly selected VIN in other dropdowns
            $('.vin').not(this).find('option[value="' + selectedVin + '"]').prop('disabled', true);
        });

        $('#rows-container').on('click', '.remove-row-btn', function() {
            var row = $(this).data('row');
            var removedVin = $('#vin' + row).val();

            // Re-enable the removed VIN in other dropdowns
            if (removedVin) {
                $('.vin').not('#vin' + row).find('option[value="' + removedVin + '"]').prop('disabled', false);
            }

            $('[data-row="' + row + '"]').remove();
        });
    });
</script>
@endpush

