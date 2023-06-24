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
                                    <select class="form-control widthinput vehicles" multiple="true" required  id="vehicles-1" data-index="1" autofocus name="vins[]" >
                                        <option></option>
                                        @foreach($vins as $vin)
                                            <option value="{{ $vin->id }}">{{ $vin->vin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Variant Detail</label>
                                    <input type="text" class="form-control widthinput" id="variant-detail-1" readonly placeholder="Vehicle Details">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">GRN</label>
                                    <input type="url"  name="GRN_link[]" class="form-control widthinput mygroup-1" placeholder="GRN Link">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">GDN</label>
                                    <input type="url"  name="GDN_link[]" class="form-control widthinput mygroup-1" placeholder="GDN Link">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Modification Link</label>
                                    <input type="url" name="modification_link[]" class="form-control widthinput mygroup-1"
                                           placeholder="Modification Link">
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="col-xxl-12 col-lg-12 col-md-12 col-md-12">
                            <a onclick="clickAdd()" style="float: right;" class="btn btn-sm btn-info  mt-2">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add
                            </a>
                        </div>
                    </div>
                </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-sm" id="submit" style="float:right;">Submit</button>
            </div>
        </form>
        @endcan
    </div>
    </div>
    <input type="hidden" id="indexValue" value="">

@endsection
@push('scripts')
    <script>
        $('#vehicles-1').select2({
            placeholder: 'Choose Vehicles',
            maximumSelectionLength:1,
            allowClear: true

        })
        // $("#form-create").validate({
        //     rules: {
        //         vins: {
        //             required: true,
        //         },
        //         'modification_link[]':{
        //             url:true,
        //             require_from_group: [1, '.mygroup'+2]
        //         },
        //         GDN_link:{
        //             url:true,
        //             require_from_group: [1, '.mygroup'+2]
        //         },
        //         GRN_link:{
        //             url:true,
        //             require_from_group: [1, '.mygroup'+2]
        //         },
        //         groups: {
        //             mygroup: "GDN_link GRN_link modification_link"
        //         },
        //     }
        // });
        // $('#vehicles-1').on('change',function(){
            function showVariantDetail(index) {
                $('#vin-error').remove();
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


        var index = 1;
        $('#indexValue').val(index);
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
                url:"{{url('getVinForVehicle')}}",
                type: "POST",
                data:
                    {
                        filteredArray: selectedVehicles,
                        _token: '{{csrf_token()}}'
                    },
                dataType : 'json',
                success: function(data)
                {
                    myarray = data;
                    // var size= myarray.length;
                    // if(size >= 1)
                    // {
                        $(".form_field_outer").append(`
                           <div class="row form_field_outer_row" id="row-${index}">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> VIN</label>
                                    <select class="form-control vehicles" required multiple="true" id="vehicles-${index}"  data-index="${index}" autofocus name="vins[]" id="vin">
                                    <option></option>
                                    </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Variant Detail</label>
                                <input type="text" value="" class="form-control widthinput variant-detail" id="variant-detail-${index}" readonly placeholder="Vehicle Details">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">GRN</label>
                                <input type="url" name="GRN_link[]" class="form-control widthinput mygroup-${index}" placeholder="GRN Link">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">GDN</label>
                                    <input type="url" name="GDN_link[]" class="form-control widthinput mygroup-${index}" placeholder="GDN Link">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Modification Link</label>
                                    <input type="url" name="modification_link[]" widthinput class="form-control mygroup-${index}"
                                           placeholder="Modification Link">
                                </div>
                            </div>
                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 " style="margin-top:32px" >
                                <button type="button" class="btn btn-danger btn-sm removeButton" id="remove-${index}" data-index="${index}" >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `);
                        let vinDropdownData   = [];
                        $.each(data,function(key,value)
                        {
                            vinDropdownData.push
                            ({
                                id: value.id,
                                text: value.vin
                            });
                        });
                        $('#vehicles-'+index).html("");
                        $('#vehicles-'+index).select2
                        ({
                            placeholder:"Choose Vehicles",
                            allowClear: true,
                            data: vinDropdownData,
                            maximumSelectionLength: 1
                        });
                    // }
                }
            });
        }
    </script>
@endpush

