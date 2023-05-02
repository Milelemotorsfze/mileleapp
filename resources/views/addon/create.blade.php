@extends('layouts.main')
<!-- <style>
    .modal-content 
    {
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
    .modal-title 
    {
        margin-top: 10px;
        margin-bottom: 5px;
    }
    .modal-paragraph 
    {
        margin-top: 10px;
        margin-bottom: 10px;
        text-align: center;
    }
    .modal-button-class {
        margin-top: 20px;
        margin-left: 20px;
        margin-right: 20px;
    }
    .icon-right 
    {
        z-index: 10;
        position: absolute;
        right: 0;
        top: 0;
    }
</style> -->
<style>
    #allbrands
    { 
        visibility: hidden;
    }
    .error 
    {
        color: #FF0000;
    }
    .datalist-arrow 
    {
        position:relative;
    }
    .datalist-arrow:after 
    {
        content:'\25bc';
        position:absolute;
        right:5px;
        top:50%;
        transform: translateY(-50%);
        display:block;
        width:35px;
        height:35px;
        line-height:40px;
        font-size: 1.5em;
        color:#333;
        text-align:center;
        background:#fff;
        border:3px solid #ccc;
        border-radius:50%;
        pointer-events:none;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
                <p><span style="float:right;" class="error">* Required Field</span></p>
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="addon_type" class="col-form-label text-md-end">{{ __('Addon Type') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                        <select id="addon_type" name="addon_type" class="form-control" onchange=getAddonCodeAndDropdown()>
                            <option value="">Choose Addon Type</option>
                            <option value="P">Accessories</option>                          
                            <option value="D">Documentation</option>
                            <option value="DP">Documentation On Purchase</option>
                            <option value="E">Not Mentioned Any Specification</option>
                            <option value="S">Shipping Cost</option>
                        </select>
                            @error('addon_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror                           
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="addon_code" class="col-form-label text-md-end">{{ __('Addon Code') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                        <input id="addon_code" type="text" class="form-control @error('addon_code') is-invalid @enderror" name="addon_code" placeholder="Addon Code" value="{{ old('addon_code') }}" required autocomplete="addon_code" autofocus readonly>
                            @error('addon_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror                          
                        </div>
                    </div>
                    </br>                   
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                        <span class="error">* </span>
                            <label for="addon_id" class="col-form-label text-md-end">{{ __('Addon Name') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-5 col-md-11">
                        <input id="addon_name" name="addon_name" hidden>
                        <span class="datalist-arrow">
                            <input list="cityname" id="addon_id" type="text" class="form-control @error('addon_id') is-invalid @enderror" name="addon_id" placeholder="Choose Addon Name" value="{{ old('addon_id') }}" required autocomplete="addon_id" autofocus>
                            <datalist id="cityname">
                                @foreach($addons as $addon)
                                    <option class="Option" data-value="{{$addon->id}}" value="{{$addon->name}}"></option>
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
                            <label for="supplier_id" class="col-form-label text-md-end">{{ __('Suppliers') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <select name="supplier_id[]" id="supplier_id" multiple="true" style="width: 100%;">
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->supplier}}</option>
                                @endforeach
                            </select>                           
                            @error('supplier_id')
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
                        <div id="showDiv" class="col-xxl-5 col-lg-5 col-md-10" hidden>
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
                                    <input list="cityname1" onchange=selectBrand(this.id) id="selectBrand1" type="text" class="keepDatalist cityname1 form-control @error('brand') is-invalid @enderror" name="brand[]" placeholder="Choose Brand"  value="" required autocomplete="brand" autofocus>
                                    <datalist id="cityname1">
                                    <option data-value="allbrands" value="ALL BRANDS" id="allbrands"></option>
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
                            <div id="showDivdrop" class="col-xxl-5 col-lg-5 col-md-10" hidden>
                                <div class="row">                                 
                                    <div class="col-xxl-12 col-lg-12 col-md-12">                                  
                                    <select class="compare-tag1" name="model[]" id="selectModelLine" multiple="true" style="width: 100%;">
                                    @foreach($modelLines as $modelLine)
                                    <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>
                                @endforeach
                            </select>     
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
                    <div id="showaddtrim" class="col-xxl-12 col-lg-12 col-md-12" hidden>
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
                            <textarea rows="5" id="new_addon_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter Addon Name" value="{{ old('name') }}" required autofocus></textarea>
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
        var selectedBrands = [];
        var i=1;
        $(document).ready(function ()
        {
            // multi select dropdown for 
            $("#supplier_id").attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
            $("#supplier_id").select2();
            $("#selectModelLine").attr("data-placeholder","Choose Model Line....     Or     Type Here To Search....");
            $("#selectModelLine").select2();
            $('#addon_id').change(function()
            {
                // fetch addon existing detils
                var value =$('#addon_id').val();
                var id = $('#cityname [value="' + value + '"]').data('value');
                $.ajax
                ({
                    url: '/addons/existingImage/'+id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) 
                    {
                            //         var selectedValues = new Array();
                //         $.each(data.existingSuppliers,function(key,value)
                //         {
                //             var a = value.supplier_id;
                //             selectedValues.push(a);
                // // $("#city-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
                // });
                // resetSelectedSuppliers(selectedValues)
                        // var html = '';
                        // html += '<h1>cccccccccc</h1>';
                        // $('#dynamic_field1').append(html);
                        // $('select[name="city"]').empty();
                        // $.each(data, function(key, value) {
                        // $('select[name="city"]').append('<option value="'+ key +'">'+ value +'</option>');
                        // });
                    }
                });
            });
            // $('#selectBrand1').change(function()
            // {
            //     // fetch addon existing detils
            //     var value =$('#selectBrand1').val();
            //     var id = $('#cityname1 [value="' + value + '"]').data('value');
            //     globalThis.selectedBrands .push(id);
            //     if(id != 'allbrands')
            //     {
            //         showRelatedModal(id);
                
            //     }
            // });

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
           
            var j=1;
           
            $('#add').click(function()
            {
                alert(i);
                alert(globalThis.selectedBrands);
                let opt = document.querySelector('option[data-value=allbrands]')
                opt.setAttribute('disabled','disabled');
                // globalThis.selectedBrands = [];
                // console.log(globalThis.selectedBrands);
                for (let j = 1; j <= i; j++)
                {
                    var value =$('#selectBrand'+j).val();
                    var id = $('#cityname1 [value="' + value + '"]').data('value');
                    // console.log(id);
                    // globalThis.selectedBrands .push(id);
                    // var v = "1"
                    let opt2 = document.querySelector('option[data-value="'+id+'"]')
                    // console.log(opt2);
                opt2.setAttribute('disabled','disabled');
                console.log(opt2);
                // globalThis.selectedBrands = [];
                    // globalThis.selectedBrands.push(a);
                    
                }
                // console.log(globalThis.selectedBrands);
                // console.log(globalThis.selectedBrands)
                // alert(i);
                //         $.each(data.existingSuppliers,function(key,value)
                //         {
                //             var a = value.supplier_id;
                //             selectedBrands.push(a);
                // // $("#city-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
                // });
                // var brandvalue = $('#selectBrand').val();
                
                // var a = $('#cityname [value="' + brandvalue + '"]').data('value');
                // $('#addon_name').val(a);
                var selectBrand = $("#selectBrand1").val();
                i++;
                // alert('j');
                // onChange="get_data('+i+')" 
                var selectBrand = $("#selectModelLine").val();
                // i++;
                var html = '';
                html += '</br>';
                html += '<div id="row'+i+'" class="dynamic-added">';
                html += '<div class="row">';
                html += '<div class="col-xxl-5 col-lg-5 col-md-10">';
                html += '<div class="row">';
                html += '<div class="col-xxl-12 col-lg-12 col-md-12">';
                html += '<input list="cityname1" onchange=selectBrand(this.id)  id="selectBrand'+i+'" type="text" class="cityname1 form-control @error('addon_name') is-invalid @enderror" name="brand[]" placeholder="Choose Brand" value="" required autocomplete="addon_name" autofocus>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-xxl-5 col-lg-5 col-md-10">';
                html += '<div class="row">';
                html += '<div class="col-xxl-12 col-lg-12 col-md-12">';
                html += '<input list="" id="addon_name1" type="text" class="form-control @error('addon_name') is-invalid @enderror" name="model[]" placeholder="Choose Model Line" value="" required autocomplete="addon_name" autofocus>';
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
                        var selectedValues = new Array();       
                        resetSelectedSuppliers(selectedValues);
                    }
                });
            });
        });
        function selectBrand(id)
        {
            // alert(id); //selectBrand1
            // alert(i);
            
            for (let a = 1; a <= i; a++)
                {
                    var value =$('#'+id).val();
            alert(value); // BENTLEY
            // id="selectBrand'+i+'"
            // '#'+id 
                var brandId = $('#cityname1 [value="' + value + '"]').data('value');
                alert(brandId); //1
                globalThis.selectedBrands .push(brandId);
                // alert('i is coming');
                // alert(globalThis.i);
                if(brandId != 'allbrands')
                {
                    showRelatedModal(brandId);
                
                }
                alert('#selectBrand'+i);
                }
            // globalThis.selectedBrands[i] = 2;
            // alert(selectedBrands);
            // alert('ff');
            // alert(id)
            // var value =$('#selectBrand'+j).val();
            // alert('#selectBrand1'+id);
            
            // var value =$('#'+id).val();
            // alert(value); // BENTLEY
            // // id="selectBrand'+i+'"
            // // '#'+id 
            //     var brandId = $('#cityname1 [value="' + value + '"]').data('value');
            //     alert(brandId); //1
            //     globalThis.selectedBrands .push(brandId);
            //     // alert('i is coming');
            //     // alert(globalThis.i);
            //     if(brandId != 'allbrands')
            //     {
            //         showRelatedModal(brandId);
                
            //     }
        }
                
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
        function resetSelectedSuppliers(selectedValues)
        {        
            $('#supplier_id').val(selectedValues);
            $('#supplier_id').trigger('change'); 
        }
        function get_data(i)
        {
            var value =$('#selectBrand'+i).val();
            var id = $('#cityname1 [value="' + value + '"]').data('value');
            globalThis.selectedBrands .push(id);
            showRelatedModal(id);
        }
        function showRelatedModal(id)
        {
            let showDiv = document.getElementById('showDiv');
            showDiv.hidden = false
            let showDivdrop = document.getElementById('showDivdrop');
            showDivdrop.hidden = false
            let showaddtrim = document.getElementById('showaddtrim');
            showaddtrim.hidden = false
            $.ajax
            ({
                url: '/addons/brandModels/'+id,
                type: "GET",
                dataType: "json",
                success:function(data) 
                {
                    $("#selectModelLine").html("").trigger("change");
                    let BrandModelLine   = [];
                    $.each(data,function(key,value)
                    {
                        BrandModelLine.push 
                        ({
                            id: value.id,
                            text: value.model_line
                        });
                    });
                    $('#selectModelLine').select2
                    ({
                        placeholder: 'Slect value',
                        allowClear: true,
                        data: BrandModelLine
                    });
                }
            });
        }
        function getAddonCodeAndDropdown()
        {
            var e = document.getElementById("addon_type");  
            var value = e.value;
            // var text = e.options[e.selectedIndex].text;
            // var value =$('#new_addon_name').val();
                $.ajax
                ({
                    url:"{{url('getAddonCodeAndDropdown')}}",
                    type: "POST",
                    data: 
                    {
                        addon_type: value,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result)
                    {
                        $('#addon_code').val(result.newAddonCode);
                        if(result.addonMasters!='')
                        {
                            document.getElementById('cityname').innerHTML = '';
                            $.each(result.addonMasters,function(key,value)
                            {
                                $('#cityname').append("<option data-value='" + value.id + "' value='" + value.name + "'></option>");  
                            });
//                             for (var i = 0; i < result.addonMasters.length; i++) 
//                             {
// consoel
//                                 // $("<option/>").html(result.addonMasters.[i].name).appendTo("#addon_name");
//                             }
                        }
                     

                        // $('.modal').removeClass('modalshow');
                        // $('.modal').addClass('modalhide');
                        // $('#cityname').append("<option data-value='" + result.id + "' value='" + result.name + "'></option>");  
                        // $('#addon_id').val(result.name); 
                        // var selectedValues = new Array();       
                        // resetSelectedSuppliers(selectedValues);
                    }
                });

        }
    </script>
@endsection


                               