@extends('layouts.main')
<style>
    .error 
    {
        color: #FF0000;
    }
    .paragraph-class 
    {
        color: red;
        font-size:11px;
    }
    .btn_round 
    {
        width: 35px;
        height: 35px;
        display: inline-block;
        border-radius: 50%;
        text-align: center;
        line-height: 35px;
        margin-left: 10px;
        border: 1px solid #ccc;
        cursor: pointer;
    }
    .btn_round:hover 
    {
        color: #fff;
        background: #6b4acc;
        border: 1px solid #6b4acc;
    } 
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
<div class="card-header">
        <h4 class="card-title">Addon Master</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="addon_type" class="col-form-label text-md-end">{{ __('Addon Type') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <select id="addon_type" name="addon_type" class="form-control form-control-sm" onchange=getAddonCodeAndDropdown()>
                                <option value="">Choose Addon Type</option>
                                <option value="P">Accessories</option>                          
                                <!-- <option value="D">Documentation</option>
                                <option value="DP">Documentation On Purchase</option>
                                <option value="E">Others</option>
                                <option value="S">Shipping Cost</option> -->
                                <option value="SP">Spare Parts</option>
                                <option value="K">Kit</option>
                                <option value="W">Warranty</option>
                            </select>
                            <span id="AddonTypeError" class="required-class"></span>                           
                        </div>
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="addon_code" class="col-form-label text-md-end">{{ __('Addon Code') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <input id="addon_code" type="text" class="form-control form-control-sm @error('addon_code') is-invalid @enderror" name="addon_code" placeholder="Addon Code" value="{{ old('addon_code') }}" required autocomplete="addon_code" autofocus readonly>
                            @error('addon_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror                          
                        </div>
                        
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                        <span class="error">* </span>
                            <label for="addon_id" class="col-form-label text-md-end">{{ __('Addon Name') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-5 col-md-11">
                            <select name="addon_id" id="addon_id" multiple="true" style="width: 100%;">
                                @foreach($addons as $addon)
                                    <option value="{{$addon->id}}">{{$addon->name}}</option>
                                @endforeach
                            </select>   
                            @error('addon_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror                           
                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-1">
                            <a id="addnewAddonButton" data-toggle="popover" data-trigger="hover" title="Create New Addon" data-placement="top" style="float: right;" class="btn btn-sm btn-info modal-button" data-modal-id="createNewAddon"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>                        
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="purchase_price" class="col-form-label text-md-end">{{ __('Least Purchase Price ( AED )') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <input id="purchase_price" type="text" class="form-control form-control-sm @error('purchase_price') is-invalid @enderror" name="purchase_price" placeholder="Least Purchase Price ( AED )" value="{{ old('purchase_price') }}" required autocomplete="purchase_price" readonly>
                            @error('purchase_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="selling_price" class="col-form-label text-md-end">{{ __('Selling Price ( AED )') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <input id="selling_price" type="text" class="form-control form-control-sm @error('selling_price') is-invalid @enderror" name="selling_price" placeholder="Enter Selling Price" value="{{ old('selling_price') }}" autocomplete="selling_price">
                            @error('selling_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="lead_time" class="col-form-label text-md-end">{{ __('Lead Time') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <input id="lead_time" type="text" class="form-control form-control-sm @error('lead_time') is-invalid @enderror" name="lead_time" placeholder="Enter Lead Time" value="{{ old('lead_time') }}" required autocomplete="lead_time" autofocus>
                            @error('lead_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="payment_condition" class="col-form-label text-md-end">{{ __('Payment Condition') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <input id="payment_condition" type="text" class="form-control form-control-sm @error('payment_condition') is-invalid @enderror" name="payment_condition" placeholder="Enter Payment Condition" value="{{ old('payment_condition') }}" required autocomplete="payment_condition" autofocus>
                            @error('payment_condition')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <!-- <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
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
                    </br> -->
                    <div class="row" hidden id="partNumberDiv">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <!-- <span class="error">* </span> -->
                            <label for="part_number" class="col-form-label text-md-end">{{ __('Part Number') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                        <input id="part_number" type="text" class="form-control form-control-sm" name="part_number" placeholder="Part Number" value="{{ old('part_number') }}" autocomplete="part_number" >
                            @error('part_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror                          
                        </div>
                    </div>
                    <br hidden id="partNumberDivBr">  
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="additional_remarks" class="col-form-label text-md-end">{{ __('Additional Remarks') }}</label>
                        </div>
                        <div class="col-xxl-10 col-lg-6 col-md-12">
                            <textarea rows="5" id="additional_remarks" type="text" class="form-control form-control-sm @error('additional_remarks') is-invalid @enderror" name="additional_remarks" placeholder="Enter Additional Remarks" value="{{ old('additional_remarks') }}" required autocomplete="additional_remarks" autofocus></textarea>
                            @error('additional_remarks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                   
                   
                  
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-12">
                    <input id="image" type="file" class="form-control form-control-sm" name="image" required autocomplete="image" onchange="readURL(this);" />
                    </br>
                    </br>
                    <img id="blah" src="#" alt="your image" />
                </div>
               
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Addon Brand and Model Lines</h4>
                    </div>
                    <div class="row">
                                        <div class="col-xxl-4 col-lg-6 col-md-12">
                                            <div class="row">
                                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                                    <label for="brand" class="keepDatalist col-form-label text-md-end">{{ __('Brand') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="showDiv" class="col-xxl-4 col-lg-6 col-md-12" hidden>
                                            <div class="row">
                                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                                    <label for="model" class="col-form-label text-md-end">{{ __('Model Line') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="showModelNumberLabel" class="col-xxl-4 col-lg-6 col-md-12" hidden>
                                            <div class="row">
                                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                                    <label for="model" class="col-form-label text-md-end">{{ __('Model Description') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-1 col-lg-1 col-md-2">
                                        </div>
                                    </div>
                                    <div id="dynamic_field">
                                        <div class="row">
                                            <div class="col-xxl-4 col-lg-6 col-md-12">
                                                <div class="row">                                   
                                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                                    <input list="cityname1" onchange=selectBrand(this.id) id="selectBrand1" type="text" class="keepDatalist cityname1 form-control form-control-sm @error('brand') is-invalid @enderror" name="brand[]" placeholder="Choose Brand"  value="" required autocomplete="brand" autofocus>
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
                                            <div id="showDivdrop" class="col-xxl-4 col-lg-6 col-md-12" hidden>
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
                                            <div id="showModelNumberdrop" class="col-xxl-4 col-lg-6 col-md-12" hidden>
                                                <div class="row">                                 
                                                    <div class="col-xxl-12 col-lg-12 col-md-12">                                  
                                                    <select class="compare-tag1" name="model_number[]" id="selectModelNumber" multiple="true" style="width: 100%;">
                                                    
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
                                        <div id="newRow"></div>
                                        <div id="showaddtrim" class="col-xxl-12 col-lg-12 col-md-12" hidden>
                                            <a id="add" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a> 
                                        </div>
                                    </div>
                                    </br>      
                                </div>
                                <div class="card"  id="kitSupplier" >
                    <div class="card-header">
                        <h4 class="card-title">Addon Suppliers And Purchase Price</h4>
                    </div>
                    
                    <div id="London" class="tabcontent">
                        <div class="row">
                            <div class="card-body">
                @include('addon.kit')
                @include('addon.supplierprice')
                </div>
                        </div>  
                    </div>
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
                                            <textarea rows="5" id="new_addon_name" type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" placeholder="Enter Addon Name" value="{{ old('name') }}" required autofocus></textarea>
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
        var selectedSuppliers = [];
        var oldselectedSuppliers = [];
        var ifModelLineExist = [];
        var currentAddonType = '';
        var selectedBrands = [];
        var i=1;
        $(document).ready(function ()
        {
            $('#blah').css('visibility', 'hidden');
            $("#addon_id").attr("data-placeholder","Choose Addon Name....     Or     Type Here To Search....");
            $("#addon_id").select2({
                maximumSelectionLength: 1,
            });
            $("#supplierArray1").attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
            $("#supplierArray1").select2({
                // maximumSelectionLength: 1,
            });
            $("#selectModelNumber").attr("data-placeholder","Choose Model Number....     Or     Type Here To Search....");
            $("#selectModelNumber").select2();
             // $("#supplierArray1").select2();
             $('#addon_id').change(function()
            {
                // fetch addon existing detils
                var id = $('#addon_id').val();
                if(id != '')
                {
                    $('#addnewAddonButton').hide();
                    $.ajax
                    ({
                        url: '/addons/existingImage/'+id,
                        type: "GET",
                        dataType: "json",
                        success:function(data) 
                        {
                            document.getElementById("AddonTypeError").classList.remove("paragraph-class"); 
                            document.getElementById("AddonTypeError").textContent="";
                            $('#addon_code').val(data.newAddonCode);
                            $("#addon_type").val(data.addon_type.addon_type); 
                            // this code is for showing related addons of selcted addon
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
                }
                else
                {
                    $('#addnewAddonButton').show();
                }
            });
            $('#supplierArray1').change(function()
            {
                showAndHideSupplierDropdownOptions(1);
            });
              $('#selectModelLine').change(function()
            {
                // alert('hi');
                // alert(currentAddonType);
                // alert($("#selectModelLine").val());
                // if($("#selectModelLine").val() != '')
                // {
                //     alert('lp');
                //     alert(count($("#selectModelLine").val()));
                // }
               
                ifModelLineExist = $("#selectModelLine").val();
            // //    alert(ifModelLineExist)
                if(currentAddonType == 'SP' && ifModelLineExist != '')
                {
                    // alert('inside');
            //     //     alert('inside when choode model line');
                    showModelNumberDropdown();
                }
                else
                {
                    hideModelNumberDropdown();
                }
            //     // alert(ifModelLineExist);
            });
            $('#submit').click(function()
            {
                var value = $('#addon_id').val();
                var a = $('#cityname [value="' + value + '"]').data('value');
                $('#addon_name').val(a);
            });
            var j=1;
           
           $('#add').click(function()
           {
            //    alert(i);
               // alert(globalThis.selectedBrands);
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
               html += '<input list="cityname1" onchange=selectBrand(this.id)  id="selectBrand'+i+'" type="text" class="cityname1 form-control form-control-sm @error('addon_name') is-invalid @enderror" name="brand[]" placeholder="Choose Brand" value="" required autocomplete="addon_name" autofocus>';
               html += '</div>';
               html += '</div>';
               html += '</div>';
               html += '<div class="col-xxl-5 col-lg-5 col-md-10">';
               html += '<div class="row">';
               html += '<div class="col-xxl-12 col-lg-12 col-md-12">';
               html += '<input list="" id="addon_name1" type="text" class="form-control form-control-sm @error('addon_name') is-invalid @enderror" name="model[]" placeholder="Choose Model Line" value="" required autocomplete="addon_name" autofocus>';
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
            currentAddonType =  $('#addon_type').val();
            if(currentAddonType == '')
            { 
                document.getElementById("AddonTypeError").classList.add("paragraph-class"); 
                document.getElementById("AddonTypeError").textContent="Please select addon type before create new addon";
            }
            else
            {
                $("#addon_id").val('');
                var modalId = $(this).data('modal-id');
                $('#' + modalId).addClass('modalshow');
                $('#' + modalId).removeClass('modalhide');
            }
        });
            $('.close').on('click', function()
            {
                $('.modal').addClass('modalhide');
                $('.modal').removeClass('modalshow');
            });
        });
      
                        // $("#supplierArray"+index).select2();
    
        $('.modal-button').on('click', function()
        {
            currentAddonType =  $('#addon_type').val();
            if(currentAddonType == '')
            { 
                document.getElementById("AddonTypeError").classList.add("paragraph-class"); 
                document.getElementById("AddonTypeError").textContent="Please select addon type before create new addon";
            }
            else
            {
                $("#addon_id").val('');
                var modalId = $(this).data('modal-id');
                $('#' + modalId).addClass('modalshow');
                $('#' + modalId).removeClass('modalhide');
            }
        });
        $('.close').on('click', function()
        {
            $('.modal').addClass('modalhide');
            $('.modal').removeClass('modalshow');
        });
        $('#createAddonId').on('click', function()
        {
            // create new addon and list new addon in addon list
            var value = $('#new_addon_name').val();
            currentAddonType =  $('#addon_type').val();
            $.ajax
            ({
                url:"{{url('createMasterAddon')}}",
                type: "POST",
                data: 
                {
                    name: value,
                    addon_type: currentAddonType,
                    _token: '{{csrf_token()}}' 
                },
                dataType : 'json',
                success: function(result)
                {
                    $('.modal').removeClass('modalshow');
                    $('.modal').addClass('modalhide');
                    $('#addon_id').append("<option value='" + result.id + "'>" + result.name + "</option>");  
                    $('#addon_id').val(result.id); 
                    var selectedValues = new Array();       
                    resetSelectedSuppliers(selectedValues);
                }
            });
        });
        function showAndHideSupplierDropdownOptions(i)
        {
            var eachSelected = [];
            // var eachSelected = $('#supplierArray'+i).select2().val();
            var eachSelected = $('#supplierArray'+i).select2("val");
            // var selected1 = $('#supplierArray'+i).val();
            
            globalThis.oldselectedSuppliers[i] = globalThis.selectedSuppliers[i];
            oldselectedSuppliers.forEach(function(item)
            {
                $('.'+item).prop('disabled',false)
                // $("#supplierArray1").select2();
            })
            globalThis.selectedSuppliers[i] = [];
            $.each(eachSelected, function( ind, value ) 
            {
                globalThis.selectedSuppliers[i] .push(value);
            });
            selectedSuppliers.forEach(function(item)
            {
// //                 // alert('hii');
// // //                 if ($('.'+item).attr("disabled")) {
// // //                     // alert('disabled');
// // //                     alert('hlo');
// // //                     // $('.'+item).prop('disabled',false)
// // //     // Remove disabled attribute if it is
// // //     // $('.'+item).removeAttr("disabled");
// // //   } else {
    $('.'+item).prop('disabled',true)
    
//     // alert('enabled');
//     // Add disabled attribute if it is not
//     // $('.'+item).attr("disabled", "disabled");
  });
//                 // $('.'+item).prop('disabled',true)
//                 // $('.'+item).prop('disabled', !$('.'+item).prop('disabled'));
//                 // $("#supplierArray1>optgroup>option[value='1']").attr('disabled','disabled');
// //                 $('#supplierArray1 option[value="1"]').prop('disabled',false);

// // $('#supplierArray1').select2();
//             })
//             $.each(selectedSuppliers, function( ind1, value1 ) 
//             {
//                 // $('.one').prop('disabled', !$('.one').prop('disabled'));
//             // $("#supplierArray1>optgroup>option[value='1']").attr('disabled','disabled');
//             //     // globalThis.selectedSuppliers[i] .push(value);
            // });
        }
        

//         function changeAddon(i)
//         {
//             var eachSelected = [];

//                 var eachSelected = $('#adoon_'+i).select2().val();
//                 // globalThis.selectedSuppliers[i] = [];
//                 $.each(eachSelected, function( ind, value ) {
//                     // globalThis.selectedSuppliers[i] .push(value); 
//                     globalThis.selectedSuppliers .push(value);
//             //     // 
//                 // alert( index + ": " + value );
// //                 $("#adoon_1").find(':selected').attr('disabled','disabled');
// // $("#adoon_1").trigger('change');
// // $("#adoon_2").find(':selected').attr('disabled','disabled');
// // $("#adoon_2").trigger('change');
//                 });
        // }
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
        function getAddonCodeAndDropdown()
        {
            var e = document.getElementById("addon_type"); 
            var value = e.value;
            currentAddonType = value;
            if(currentAddonType != '')
            {
                document.getElementById("AddonTypeError").classList.remove("paragraph-class"); 
                document.getElementById("AddonTypeError").textContent="";
                if(currentAddonType == 'SP' && ifModelLineExist != '')
                {
                    showModelNumberDropdown();
                }
                else
                {
                    hideModelNumberDropdown();
                }
                if(value == 'SP' )
                {    
                    let showPartNumber = document.getElementById('partNumberDiv');
                    showPartNumber.hidden = false  
                    let showPartNumberBr = document.getElementById('partNumberDivBr');
                    showPartNumberBr.hidden = false
                    shownotKitSupplier();
                    hidekitSupplier();
                }
                else
                {
                    let showPartNumber = document.getElementById('partNumberDiv');
                    showPartNumber.hidden = true  
                    let showPartNumberBr = document.getElementById('partNumberDivBr');
                    showPartNumberBr.hidden = true 
                    showkitSupplier();
                    hidenotKitSupplier();
                }
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
                    success: function(data)
                    {
                        console.log(currentAddonType);
                        $('#addon_type').val(currentAddonType);
                        $('#addon_code').val(data.newAddonCode);
                        $("#addon_id").html("");
                        myarray = data.addonMasters;
                        var size= myarray.length;
                        if(size >= 1)
                        {
                            let AddonDropdownData   = [];
                            $.each(data.addonMasters,function(key,value)
                            {
                                AddonDropdownData.push 
                                ({
                                    id: value.id,
                                    text: value.name
                                });
                            });
                            $('#addon_id').select2
                            ({
                                placeholder: 'Select value',
                                allowClear: true,
                                data: AddonDropdownData,
                                maximumSelectionLength: 1,
                            });
                        }
                    }
                });
            }
            else
            {
                $('#addon_code').val('');
            }
        }
        function hideModelNumberDropdown()
        {
            let showPartNumber = document.getElementById('showModelNumberdrop');
            showPartNumber.hidden = true  
            let showPartNumberBr = document.getElementById('showModelNumberLabel');
            showPartNumberBr.hidden = true  
        }
        function showModelNumberDropdown()
        {
            let showPartNumber = document.getElementById('showModelNumberdrop');
            showPartNumber.hidden = false  
            let showPartNumberBr = document.getElementById('showModelNumberLabel');
            showPartNumberBr.hidden = false  
            var e = document.getElementById("addon_type"); 
            var value = e.value;
            var selectedModelLine = $("#selectModelLine").val();
            $.ajax
            ({
                url:"{{url('getModelDescriptionDropdown')}}",
                type: "POST",
                data: 
                {
                    model_line_id: selectedModelLine,
                    addon_type: value,
                    _token: '{{csrf_token()}}' 
                },
                dataType : 'json',
                // success: function(result)
                // {
                //     $('#addon_code').val(result.newAddonCode);
                //     if(result.addonMasters!='')
                //     {
                //         $("#addon_id").html("").trigger("change");
                //         $.each(result.addonMasters,function(key,value)
                //         {
                //             $('#cityname').append("<option data-value='" + value.id + "' value='" + value.name + "'></option>");  
                //         });
                //     }
                // }
                success:function(data) 
                {
                    $("#selectModelNumber").html("").trigger("change");
                    let ModelLineModelDescription   = [];
                    $.each(data,function(key,value)
                    {
                        ModelLineModelDescription.push 
                        ({
                            id: value.id,
                            text: value.model_description
                        });
                    });
                    $('#selectModelNumber').select2
                    ({
                        placeholder: 'Select value',
                        allowClear: true,
                        data: ModelLineModelDescription
                    });
                }
            });
        }
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
        function changeCurrency(i)
        {
            var e = document.getElementById("currency_"+i);
            var value = e.value;
            if(value == 'USD')
            {
                let chooseCurrency = document.getElementById('div_price_in_aedOne_'+i);
                chooseCurrency.hidden = true  
                let currencyUSD = document.getElementById('div_price_in_usd_'+i);
                currencyUSD.hidden = false  
                let currencyAED = document.getElementById('div_price_in_aed_'+i);
                currencyAED.hidden = false  
            }
            else
            {
                let chooseCurrency = document.getElementById('div_price_in_aedOne_'+i);
                chooseCurrency.hidden = false  
                let currencyUSD = document.getElementById('div_price_in_usd_'+i);
                currencyUSD.hidden = true  
                let currencyAED = document.getElementById('div_price_in_aed_'+i);
                currencyAED.hidden = true 
            }
        }
        function calculateAED(i)
        {
            var usd = $("#addon_purchase_price_in_usd_"+i).val();
            var aed = usd * 3.6725;
            var aed = aed.toFixed(4);
            if(aed == 0)
            {
                document.getElementById('addon_purchase_price_'+i).value = "";
            }
            else
            {
                document.getElementById('addon_purchase_price_'+i).value = aed;
            }
        }
        function selectBrand(id)
        {
            // alert(id); 
            //selectBrand1
            // alert(i);
            
            for (let a = 1; a <= i; a++)
                {
                    var value =$('#'+id).val();
            // alert(value); // BENTLEY
            // id="selectBrand'+i+'"
            // '#'+id 
                var brandId = $('#cityname1 [value="' + value + '"]').data('value');
                // alert(brandId); //1
                globalThis.selectedBrands .push(brandId);
                // alert('i is coming');
                // alert(globalThis.i);
                if(brandId != 'allbrands')
                {
                    showRelatedModal(brandId);
                
                }
                // alert('#selectBrand'+i);
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
                        placeholder: 'Select value',
                        allowClear: true,
                        data: BrandModelLine
                    });
                }
            });
        }
        function showkitSupplier()
        {
            let showDiv = document.getElementById('kitSupplier');
            showDiv.hidden = false
        }
        function hidenotKitSupplier()
        {
            let showDiv = document.getElementById('notKitSupplier');
            showDiv.hidden = true
        }
        function shownotKitSupplier()
        {
            let showDiv = document.getElementById('notKitSupplier');
            showDiv.hidden = false
        }
        function hidekitSupplier()
        {
            let showDiv = document.getElementById('kitSupplier');
            showDiv.hidden = true
        }
</script>
@endsection