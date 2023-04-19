@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Addon Master</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('addon.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
        <form method="POST" enctype="multipart/form-data" action="{{ route('addon.store') }}"> 
            @csrf
            <div class="row">
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="addon_id" class="col-form-label text-md-end">{{ __('Addon Name') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-5 col-md-11">
                        <input id="addon_name" name="addon_name" hidden>
                            <input list="cityname" id="addon_id" type="text" class="form-control @error('addon_id') is-invalid @enderror" name="addon_id" placeholder="Choose Addon Name" value="{{ old('addon_id') }}" required autocomplete="addon_id" autofocus>
                            <datalist id="cityname">
                                @foreach($addons as $addon)
                                    <option data-value="{{$addon->id}}" value="{{$addon->name}}"></option>
                                @endforeach
                            </datalist>
                            @error('addon_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-1">
                            <a data-toggle="popover" data-trigger="hover" title="Create New Addon" data-placement="top" style="float: right;" class="btn btn-sm btn-info modal-button" data-modal-id="createNewAddon"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a> 
                        
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="purchase_price" class="col-form-label text-md-end">{{ __('Purchase Price ( AED )') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="purchase_price" type="text" class="form-control @error('purchase_price') is-invalid @enderror" name="purchase_price" placeholder="Enter Purchase Price" value="{{ old('purchase_price') }}" required autocomplete="purchase_price" autofocus>
                            @error('purchase_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="selling_price" class="col-form-label text-md-end">{{ __('Selling Price ( AED )') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="selling_price" type="text" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price" placeholder="Enter Selling Price" value="{{ old('selling_price') }}" required autocomplete="selling_price" autofocus>
                            @error('selling_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="lead_time" class="col-form-label text-md-end">{{ __('Lead Time') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="lead_time" type="text" class="form-control @error('lead_time') is-invalid @enderror" name="lead_time" placeholder="Enter Lead Time" value="{{ old('lead_time') }}" required autocomplete="lead_time" autofocus>
                            @error('lead_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="additional_remarks" class="col-form-label text-md-end">{{ __('Additional Remarks') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <textarea rows="5" id="additional_remarks" type="text" class="form-control @error('additional_remarks') is-invalid @enderror" name="additional_remarks" placeholder="Enter Additional Remarks" value="{{ old('additional_remarks') }}" required autocomplete="additional_remarks" autofocus></textarea>
                            @error('additional_remarks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                            <div class="col-xxl-5 col-lg-5 col-md-10">
                                <div class="row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <label for="brand" class="col-form-label text-md-end">{{ __('Brand') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-5 col-lg-5 col-md-10">
                                <div class="row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <label for="model" class="col-form-label text-md-end">{{ __('Model Line') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-1 col-lg-1 col-md-2">
                            </div>
                        </div>
                    <div id="dynamic_field">
                        <div class="row">
                            <div class="col-xxl-5 col-lg-5 col-md-10">
                                <div class="row">
                                    
                                    <div class="col-xxl-12 col-lg-12 col-md-12">

                                    <input list="cityname1" id="title" type="text" class="form-control @error('brand') is-invalid @enderror" name="brand[]" placeholder="Choose Brand"  value="" required autocomplete="brand" autofocus>
                                    <datalist id="cityname1">
                                    <option data-value="allbrands" value="ALL BRANDS"></option>
                                        @foreach($brands as $brand)
                                            <option data-value="{{$brand->id}}" value="{{$brand->brand_name}}"></option>
                                        @endforeach
                                    </datalist>
                                    @error('brand')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-5 col-lg-5 col-md-10">
                                <div class="row">
                                   
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                    <input list="cityname2" id="title1" type="text" class="form-control @error('model') is-invalid @enderror" name="model[]" placeholder="Choose Model Line" value="" required autocomplete="model" autofocus>
                                    <datalist id="cityname2">
                                    @foreach($modelLines as $modelLine)
                                            <option data-value="{{$modelLine->id}}" value="{{$modelLine->model_line}}"></option>
                                        @endforeach
                                    </datalist>
                                    @error('model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-1 col-lg-1 col-md-2">
                            </div>
                        </div>
                    </div>
                    </br>
                    <div id="newRow"></div>
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                            <a id="add" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a> 
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-12">
                    <input id="image" type="file" class="form-control" name="image" required autocomplete="image" onchange="readURL(this);" />
                    </br>
                    </br>
                    <img id="blah" src="#" alt="your image" />
                </div>
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary" id="submit">Submit</button>
              </div>
            </div>
            </br>
            <div>
          <!--FFFFFFFFFFFFFFFFFFFFFFFF-->
            </div>
        </form>
        <div class="modal modal-class" id="createNewAddon" >
            <div class="modal-content">
                <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                <h3 class="modal-title" style="text-align:center;"> Create New Addon </h3>
                <div class="dropdown-divider"></div>
                <form method="POST" enctype="multipart/form-data"> 
                    @csrf
                    <div class="row modal-row">
                        <div class="col-xxl-12 col-lg-12 col-md-12">
                            <label for="name" class="col-form-label text-md-end ">Addon Name</label>
                        </div>
                        <div class="col-xxl-12 col-lg-12 col-md-12">
                            <textarea rows="5" id="new_addon_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter Addon Name" value="{{ old('name') }}" required autocomplete="name" autofocus></textarea>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row modal-button-class" >                                           
                        <div class="col-xs-12 col-sm-12 col-md-12" >
                            <a id="createAddonId" style="float: right;"  class="btn btn-sm btn-success "><i class="fa fa-check" aria-hidden="true"></i> Submit</a>
                        </div>
                    </div> 
                </form>                                         
            </div>
        </div> 
    </div>  
    </br>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#addon_id').change(function()
            {
                // fetch addon existing images
                var value =$('#addon_id').val();
                var id = $('#cityname [value="' + value + '"]').data('value');
                $.ajax
                ({
                    url: '/addons/existingImage/'+id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) 
                    {
                        var html = '';
                        html += '<h1>cccccccccc</h1>';
                        $('#dynamic_field1').append(html);
                        // console.log(data);
                        // $('select[name="city"]').empty();
                        // $.each(data, function(key, value) {
                        // $('select[name="city"]').append('<option value="'+ key +'">'+ value +'</option>');
                        // });
                    }
                });
            });
            //get addon_id
            $('#submit').click(function()
            {
                var value = $('#addon_id').val();
                var a = $('#cityname [value="' + value + '"]').data('value');
                $('#addon_name').val(a);
            });
            // hide addon image tag when page reload
            $('#blah').css('visibility', 'hidden');
            // add row
            var i=1;
            var j=1;
            $('#add').click(function()
            {
                var title = $("#title").val();
                i++;
                var title = $("#title1").val();
                i++;
                var html = '';
                html += '</br>';
                html += '<div id="row'+i+'" class="dynamic-added">';
                html += '<div class="row">';
                html += '<div class="col-xxl-5 col-lg-5 col-md-10">';
                html += '<div class="row">';
                html += '<div class="col-xxl-12 col-lg-12 col-md-12">';
                html += '<input list="cityname1" id="addon_name" type="text" class="form-control @error('addon_name') is-invalid @enderror" name="brand[]" placeholder="Choose Brand" value="" required autocomplete="addon_name" autofocus>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-xxl-5 col-lg-5 col-md-10">';
                html += '<div class="row">';
                html += '<div class="col-xxl-12 col-lg-12 col-md-12">';
                html += '<input list="cityname2" id="addon_name1" type="text" class="form-control @error('addon_name') is-invalid @enderror" name="model[]" placeholder="Choose Model Line" value="" required autocomplete="addon_name" autofocus>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-xxl-1 col-lg-1 col-md-2">';
                html += '<a id="'+i+'" style="float: right;" class="btn btn-sm btn-danger btn_remove"><i class="fa fa-minus" aria-hidden="true"></i> Remove</a>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                $('#dynamic_field').append(html);
            });
            // remove row
            $(document).on('click', '.btn_remove', function()
            {
                var button_id = $(this).attr("id");
                $('#row'+button_id+'').remove();
            });
            $('.modal-button').on('click', function()
            {
                var modalId = $(this).data('modal-id');
                $('#' + modalId).addClass('modalshow');
                $('#' + modalId).removeClass('modalhide');
            });
            $('.close').on('click', function()
            {
                $('.modal').addClass('modalhide');
                $('.modal').removeClass('modalshow');
            });
            $('#createAddonId').on('click', function()
            {
                // create new addon and list new addon in addon list
                var value =$('#new_addon_name').val();
                $.ajax
                ({
                    url:"{{url('createMasterAddon')}}",
                    type: "POST",
                    data: 
                    {
                        name: value,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result)
                    {
                        $('.modal').removeClass('modalshow');
                        $('.modal').addClass('modalhide');
                        $('#cityname').append("<option data-value='" + result.id + "' value='" + result.name + "'></option>");  
                        $('#addon_id').val(result.name);   
                    }
                });
            });
        });
        // display selected addon image
        function readURL(input)
        {
            if (input.files && input.files[0])
            {
                var reader = new FileReader();
                reader.onload = function (e)
                {
                    $('#blah').css('visibility', 'visible');
                    $('#blah').attr('src', e.target.result).width('100%').height('#blah'.width);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function closemodal()
        {
            $('.modal').removeClass('modalshow');
            $('.modal').addClass('modalhide');
        }
    </script>
@endsection
<style>
        .modal-content {
            position:fixed;
            top: 50%;
            left: 50%;
            width:30em;
            height:18em;
            margin-top: -9em; /*set to a negative number 1/2 of your height*/
            margin-left: -15em; /*set to a negative number 1/2 of your width*/
            border: 2px solid #e3e4f1;
            background-color: white;
        }
        .modal-title {
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .modal-button-class {
            margin-top: 20px;
            margin-left: 20px;
            margin-right: 20px;
        }
        .icon-right {
            z-index: 10;
            position: absolute;
            right: 0;
            top: 0;
        }
</style>

                               