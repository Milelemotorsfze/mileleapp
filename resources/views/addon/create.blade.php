@extends('layouts.main')
<style>
   /* .addonCreateCard
   {
    top: 50%;
            left: 50%;
   } */

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
    .paragraph-class 
    {
        color: red;
        font-size:11px;
    }
  .overlay
  {
    position: fixed; /* Positioning and size */
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(128,128,128,0.5); /* color */
  display: none; /* making it hidden by default */
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
                <strong>Whoops!</strong> There were some problems with your input.</br></br>
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
                            <select id="addon_type" name="addon_type" class="form-control form-control-sm" onchange=getAddonCodeAndDropdown() autofocus>
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
                            <span id="addon_type_required" class="email-phone required-class paragraph-class"></span>                        
                        </div>
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="addon_code" class="col-form-label text-md-end">{{ __('Addon Code') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <input id="addon_code" type="text" class="form-control form-control-sm @error('addon_code') is-invalid @enderror" name="addon_code" placeholder="Addon Code" value="{{ old('addon_code') }}"  autocomplete="addon_code" autofocus readonly>
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
                            <input id="purchase_price" type="text" class="form-control form-control-sm @error('purchase_price') is-invalid @enderror" name="purchase_price" placeholder="Least Purchase Price ( AED )" value="{{ old('purchase_price') }}"  autocomplete="purchase_price" readonly>
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
                            <input id="lead_time" type="text" class="form-control form-control-sm @error('lead_time') is-invalid @enderror" name="lead_time" placeholder="Enter Lead Time" value="{{ old('lead_time') }}"  autocomplete="lead_time">
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
                            <input id="payment_condition" type="text" class="form-control form-control-sm @error('payment_condition') is-invalid @enderror" name="payment_condition" placeholder="Enter Payment Condition" value="{{ old('payment_condition') }}"  autocomplete="payment_condition" autofocus>
                            @error('payment_condition')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-2 col-md-4">
                            <!-- <span class="error">* </span> -->
                            <label for="fixing_charges_included" class="col-form-label text-md-end">{{ __('Fixing Charges Included') }}</label>
                        </div>
                            <div class="col-xxl-3 col-lg-3 col-md-6" id="">
                                <fieldset>
                                    <div class="some-class">
                                        <input type="radio" class="radioFixingCharge" name="x" value="yes" id="yes" checked />
                                        <label for="yes">Yes</label>
                                        <input type="radio" class="radioFixingCharge" name="x" value="no" id="no" />
                                        <label for="no">No</label>
                                    </div>
                                </fieldset>
                                @error('fixing_charges_included')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-xxl-2 col-lg-6 col-md-12" hidden id="FixingChargeAmountDiv">
                            <!-- <span class="error">* </span> -->
                            <label for="fixing_charge_amount" class="col-form-label text-md-end">{{ __('Fixing Charge Amount') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12" hidden id="FixingChargeAmountDivBr">
                        <input id="fixing_charge_amount" type="text" class="form-control form-control-sm" name="fixing_charge_amount" placeholder="Fixing Charge Amount" value="{{ old('fixing_charge_amount') }}" autocomplete="fixing_charge_amount" >
                            @error('fixing_charge_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror                          
                        </div>
                        </br>
                        <div class="col-xxl-2 col-lg-6 col-md-12" hidden id="partNumberDiv">
                            <!-- <span class="error">* </span> -->
                            <label for="part_number" class="col-form-label text-md-end">{{ __('Part Number') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12" hidden id="partNumberDivBr">
                        <input id="part_number" type="text" class="form-control form-control-sm" name="part_number" placeholder="Part Number" value="{{ old('part_number') }}" autocomplete="part_number" >
                            @error('part_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror                          
                        </div>
                    </div>
                    </br>
                    <div class="row" hidden id="rowPartNumber">
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
                    <br hidden id="rowPartNumberBr">
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="additional_remarks" class="col-form-label text-md-end">{{ __('Additional Remarks') }}</label>
                        </div>
                        <div class="col-xxl-10 col-lg-6 col-md-12">
                            <textarea rows="5" id="additional_remarks" type="text" class="form-control form-control-sm @error('additional_remarks') is-invalid @enderror" name="additional_remarks" placeholder="Enter Additional Remarks" value="{{ old('additional_remarks') }}"  autocomplete="additional_remarks" autofocus></textarea>
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
                    <label for="choices-single-default" class="form-label font-size-13">Choose Addon Image</label>
                    <input id="image" type="file" class="form-control form-control-sm" name="image" autocomplete="image" onchange="readURL(this);" />
                    <span id="addonImageError" class="email-phone required-class paragraph-class"></span>
                    </br>
                    </br>
                    <img id="blah" src="#" alt="your image" />
                </div>
                @include('addon.brandModel')
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
        </form>
        <div class="overlay">
            <div class="modal" id="createNewAddon" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenteredLabel" style="text-align:center;"> Create New Addon </h5>
                            <button type="button" class="btn btn-secondary btn-sm close form-control" data-dismiss="modal" aria-label="Close" onclick="closemodal()">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data"> 
                                @csrf
                                <div class="row modal-row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <span class="error">* </span>
                                        <label for="name" class="col-form-label text-md-end ">Addon Name</label>
                                    </div>
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <textarea rows="3" id="new_addon_name" type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" placeholder="Enter Addon Name" value="{{ old('name') }}"  autofocus></textarea>
                                        <span id="newAddonError" class="required-class paragraph-class"></span>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </form> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" onclick="closemodal()"><i class="fa fa-times"></i> Close</button>
                            <button type="button" class="btn btn-primary btn-sm" id="createAddonId" style="float: right;"><i class="fa fa-check" aria-hidden="true"></i> Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<script type="text/javascript">
        var selectedSuppliers = [];
        var oldselectedSuppliers = [];
        var ifModelLineExist = [];
        var currentAddonType = '';
        var selectedBrands = [];
        var i=1;
        var fixingCharge = 'yes';
        $(document).ready(function ()
        {
            $('#blah').css('visibility', 'hidden');
            $("#addon_id").attr("data-placeholder","Choose Addon Name....     Or     Type Here To Search....");
            $("#addon_id").select2({
                maximumSelectionLength: 1,
            });
            // $('#addon_id').select2();
            $("#supplierArray1").attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
            $("#supplierArray1").select2({
                // maximumSelectionLength: 1,
            });
           
            $('.radioFixingCharge').click(function() 
            {
                var addon_type = $("#addon_type").val();
                fixingCharge = $(this).val();
                if($(this).val() == 'yes')
                {
                    let showFixingChargeAmount = document.getElementById('FixingChargeAmountDiv');
                    showFixingChargeAmount.hidden = true  
                    let showFixingChargeAmountBr = document.getElementById('FixingChargeAmountDivBr');
                    showFixingChargeAmountBr.hidden = true
                    if(addon_type != '' && addon_type == 'SP')
                    {
                        let showPartNumber = document.getElementById('partNumberDiv');
                        showPartNumber.hidden = false  
                        let showPartNumberBr = document.getElementById('partNumberDivBr');
                        showPartNumberBr.hidden = false
                        let showrowPartNumber = document.getElementById('rowPartNumber');
                        showrowPartNumber.hidden = true  
                        let showrowPartNumberBr = document.getElementById('rowPartNumberBr');
                        showrowPartNumberBr.hidden = true
                    }
                }
                else
                {
                    if(addon_type != '' && addon_type == 'SP')
                    {
                        let showPartNumber = document.getElementById('partNumberDiv');
                        showPartNumber.hidden = true  
                        let showPartNumberBr = document.getElementById('partNumberDivBr');
                        showPartNumberBr.hidden = true
                        let showrowPartNumber = document.getElementById('rowPartNumber');
                        showrowPartNumber.hidden = false  
                        let showrowPartNumberBr = document.getElementById('rowPartNumberBr');
                        showrowPartNumberBr.hidden = false
                    }
                    let showFixingChargeAmount = document.getElementById('FixingChargeAmountDiv');
                    showFixingChargeAmount.hidden = false  
                    let showFixingChargeAmountBr = document.getElementById('FixingChargeAmountDivBr');
                    showFixingChargeAmountBr.hidden = false
                }
            });
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
          
            $('#submit').click(function()
            {
                var value = $('#addon_id').val();
                var a = $('#cityname [value="' + value + '"]').data('value');
                $('#addon_name').val(a);
            });
            var j=1;
           
        //    $('#add').click(function()
        //    {
        //         $('.allbrands').prop('disabled',true);
        //        // globalThis.selectedBrands = [];
        //        // console.log(globalThis.selectedBrands);
        //        for (let j = 1; j <= i; j++)
        //        {
        //             var value =$('#selectBrand'+j).val();
        //             // globalThis.selectedBrands .push(value);
        //             $('.'+value).prop('disabled',true);
        //             // globalThis.selectedBrands = [];
        //             // globalThis.selectedBrands.push(a);
        //        }
        //        //         $.each(data.existingSuppliers,function(key,value)
        //        //         {
        //        //             var a = value.supplier_id;
        //        //             selectedBrands.push(a);
        //        // // $("#city-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
        //        // });
        //        // var brandvalue = $('#selectBrand').val();
               
        //        // var a = $('#cityname [value="' + brandvalue + '"]').data('value');
        //        // $('#addon_name').val(a);
        //        var selectBrand = $("#selectBrand1").val();
        //        i++;
        //        // onChange="get_data('+i+')" 
        //        var selectBrand = $("#selectModelLine").val();
        //        // i++;
        //        var html = '';
        //        html += '</br>';
        //        html += '<div id="row'+i+'" class="dynamic-added">';
        //        html += '<div class="row">';
        //        html += '<div class="col-xxl-5 col-lg-5 col-md-10">';
        //        html += '<div class="row">';
        //        html += '<div class="col-xxl-12 col-lg-12 col-md-12">';
        //        html += '<select onchange=selectBrand(this.id) name="br[]" id="selectBrand'+i+'" multiple="true" style="width: 100%;">';
        //        html += '@foreach($brands as $brand)';
        //        html += '<option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>';
        //        html += '@endforeach';
        //        html += '</select>';                                     
        //        html += '</div>';
        //        html += '</div>';
        //        html += '</div>';
        //        html += '<div class="col-xxl-5 col-lg-5 col-md-10">';
        //        html += '<div class="row">';
        //        html += '<div class="col-xxl-12 col-lg-12 col-md-12">';
        //        html += '<input list="" id="addon_name1" type="text" class="form-control form-control-sm @error('addon_name') is-invalid @enderror" name="model[]" placeholder="Choose Model Line" value=""  autocomplete="addon_name" autofocus>';
        //        html += '</div>';
        //        html += '</div>';
        //        html += '</div>';
        //        html += '<div class="col-xxl-1 col-lg-1 col-md-2">';
        //        html += '<a id="'+i+'" style="float: right;" class="btn btn-sm btn-danger btn_remove"><i class="fa fa-minus" aria-hidden="true"></i> Remove</a>';
        //        html += '</div>';
        //        html += '</div>';
        //        html += '</div>';
        //        $('#dynamic_field').append(html);
        //        $("#selectBrand"+i).attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
        //         $("#selectBrand"+i).select2({
        //             maximumSelectionLength: 1,
        //         });
        //    });
        
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
                    $('.overlay').show();
                    $("#addon_id").val('');
                    var modalId = $(this).data('modal-id');
                    $('#' + modalId).addClass('modalshow');
                    $('#' + modalId).removeClass('modalhide');
                }
            });
            // $('.modal-button').on('click', function()
            // {alert('hhh');
            //     currentAddonType =  $('#addon_type').val();
            //     if(currentAddonType == '')
            //     { 
            //         document.getElementById("AddonTypeError").classList.add("paragraph-class"); 
            //         document.getElementById("AddonTypeError").textContent="Please select addon type before create new addon";
            //     }
            //     else
            //     {
            //         $("#addon_id").val('');
            //         var modalId = $(this).data('modal-id');
            //         $('#' + modalId).addClass('modalshow');
            //         $('#' + modalId).removeClass('modalhide');
            //     }
            // });
            // $('.close').on('click', function()
            // {
            //     // alert('hii');
            //     $('.overlay').hide();
            //     $('.modal').addClass('modalhide');
            //     $('.modal').removeClass('modalshow');
            // });
        });
      
                        // $("#supplierArray"+index).select2();
    
        
        // $('.close').on('click', function()
        // {alert('jj');
        //     $('.modal').addClass('modalhide');
        //     $('.modal').removeClass('modalshow');
        // });
        $('#createAddonId').on('click', function()
        {
            // create new addon and list new addon in addon list
            var value = $('#new_addon_name').val();
            if(value == '')
            {
                document.getElementById("newAddonError").textContent='Addon Name is Required';
            }
            else
            {
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
                        $('.overlay').hide();
                        $('.modal').removeClass('modalshow');
                        $('.modal').addClass('modalhide');
                        $('#addon_id').append("<option value='" + result.id + "'>" + result.name + "</option>");  
                        $('#addon_id').val(result.id); 
                        var selectedValues = new Array();       
                        resetSelectedSuppliers(selectedValues);
                        $('#addnewAddonButton').hide();
                        $('#new_addon_name').val("");
                        document.getElementById("newAddonError").textContent='';
                    }
                });
            }
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
            $('.overlay').hide();
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

                $("#selectBrand1").removeAttr('disabled');
                $("#selectBrand1").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
                $("#selectBrand1").select2({
                    maximumSelectionLength: 1,
                }); 
                document.getElementById("AddonTypeError").classList.remove("paragraph-class"); 
                document.getElementById("AddonTypeError").textContent="";
                document.getElementById("addon_type_required").textContent="";
                // document.getElementById("addon_type_required").hidden = true;
                if(currentAddonType == 'SP' && ifModelLineExist != '')
                {
                    // alert('hi');
                    // showModelNumberDropdown(id,row);
                }
                else
                {
                    // alert('hiff');
                    // hideModelNumberDropdown(id,row);
                }
                if(value == 'SP' )
                {                     
                    if(fixingCharge == 'no')
                    {
                        let showPartNumber = document.getElementById('partNumberDiv');
                        showPartNumber.hidden = true  
                        let showPartNumberBr = document.getElementById('partNumberDivBr');
                        showPartNumberBr.hidden = true
                        let showrowPartNumber = document.getElementById('rowPartNumber');
                        showrowPartNumber.hidden = false  
                        let showrowPartNumberBr = document.getElementById('rowPartNumberBr');
                        showrowPartNumberBr.hidden = false
                    }
                    else
                    {
                        let showPartNumber = document.getElementById('partNumberDiv');
                        showPartNumber.hidden = false  
                        let showPartNumberBr = document.getElementById('partNumberDivBr');
                        showPartNumberBr.hidden = false
                    }
                }
                else
                {
                    let showPartNumber = document.getElementById('partNumberDiv');
                    showPartNumber.hidden = true  
                    let showPartNumberBr = document.getElementById('partNumberDivBr');
                    showPartNumberBr.hidden = true 
                    let showrowPartNumber = document.getElementById('rowPartNumber');
                    showrowPartNumber.hidden = true  
                    let showrowPartNumberBr = document.getElementById('rowPartNumberBr');
                    showrowPartNumberBr.hidden = true
                }
                if(value == 'K')
                {
                    hidenotKitSupplier();
                    showkitSupplier();                 
                }
                else
                {
                    hidekitSupplier();
                    shownotKitSupplier();          
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
                $("#selectBrand1").attr('disabled','disabled'); 
                $('#addon_code').val('');
            }
        }
        function readURL(input)
        {
            var allowedExtension = ['svg','jpeg','png','jpg','gif','bmp','tiff','jpe','jfif'];
            var fileExtension = input.value.split('.').pop().toLowerCase();
            var isValidFile = false;
            for(var index in allowedExtension) 
            {
                if(fileExtension === allowedExtension[index]) 
                {
                    isValidFile = true; 
                    break;
                }
            }
            if(!isValidFile) 
            {               
                $('#blah').hide();
                document.getElementById("addonImageError").textContent='Allowed Extensions are : *.' + allowedExtension.join(', *.');
            }
            else
            {
                if (input.files && input.files[0])
                {
                    var reader = new FileReader();
                    reader.onload = function (e)
                    {
                        $('#blah').show();
                        $('#blah').css('visibility', 'visible');
                       
                        $('#blah').attr('src', e.target.result).width('100%').height('300px');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
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
            aed = parseFloat(aed);
            if(aed == 0)
            {
                document.getElementById('addon_purchase_price_'+i).value = "";
                setLeastAEDPrice();
            }
            else
            {
                document.getElementById('addon_purchase_price_'+i).value = aed;
                setLeastAEDPrice();
            }
        }
        function calculateUSD(i)
        {
            var aed = $("#addon_purchase_price_"+i).val();
            var usd = aed / 3.6725;
            var usd = usd.toFixed(4);
            if(usd == 0)
            {
                document.getElementById('addon_purchase_price_in_usd_'+i).value = "";
            }
            else
            {
                document.getElementById('addon_purchase_price_in_usd_'+i).value = usd;
            }
            setLeastAEDPrice();
        }
        function setLeastAEDPrice()
        {
            const values = Array.from(document.querySelectorAll('.notKitSupplierPurchasePrice')).map(input => input.value);
            var arrayOfNumbers = [];
            values.forEach(v => {
                if(v != '')
                {
                    arrayOfNumbers .push(v);
                }
            });
            var arrayOfNumbers = arrayOfNumbers.map(Number);
            const minOfPrice = Math.min(...arrayOfNumbers);
            $("#purchase_price").val(minOfPrice);
        }
        function showkitSupplier()
        {
            $('#kitSupplierIdToHideandshow').show();
            $('#kitSupplierBrToHideandshow').show();
            $('#kitSupplierButtonToHideandshow').show();
        }
        function hidenotKitSupplier()
        {
            $('#notKitSupplier').hide();
        }
        function shownotKitSupplier()
        { 
            $('#notKitSupplier').show();
        }
        function hidekitSupplier()
        {
            $('#kitSupplierIdToHideandshow').hide();
            $('#kitSupplierBrToHideandshow').hide();
            $('#kitSupplierButtonToHideandshow').hide();
        }
</script>
@endsection