@extends('layouts.main')
<style>
    .modal-xl
    {
        max-width: 99% !important;
    }
    #blah
    {
        width: 300px;
        height: 300px;
    }
    #showImage
    {
        width: auto;
        height: auto;
        max-width:1200px;
    }
    @media only screen and (max-device-width: 480px)
    {
        #showImage
        {
            width: 100%;
            height: 100%;
        }
        #blah
        {
            width: 200px;
            height: 200px;
        }
    }
    @media only screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)
    {
        #showImage
        {
            width: 100%;
            height: 100%;
        }
    }
    @media only screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)
    {
        #showImage
        {
            width: 100%;
            height: 100%;
        }
    }
    @media only screen and (max-device-width: 1280px)
    {
        #showImage
        {
            width: 100%;
            height: 100%;
        }
    }
    /* @media only screen and (min-device-width: 1280px)
    {
        #showImage
        {
            width: 100%;
            height: 100%;
            max-width:700px;
        }
    }   */
    .contain
    {
    object-fit: contain;
    }
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
        width: 30px;
        height: 30px;
        display: inline-block;
        /* border-radius: 50%; */
        text-align: center;
        line-height: 35px;
        margin-left: 10px;
        margin-top: 28px;
        border: 1px solid #ccc;
        color:#fff;
        background-color: #fd625e;
        border-radius:5px;
        cursor: pointer;
    }
    .btn_round:hover 
    {
        color: #fff;
        background: #fd625e;
        border: 1px solid #fd625e;
    }
    .paragraph-class 
    {
        margin-top: .25rem;
        font-size: 80%;
        color: #fd625e;
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
    .widthinput
    {
        height:32px!important;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Edit Addon</h4>
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
        <form id="createAddonForm" name="createAddonForm" method="POST" enctype="multipart/form-data" action="{{ route('addon.updatedetails',$addonDetails->id) }}">
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
                            <select id="addon_type" name="addon_type" class="form-control" onchange=getAddonCodeAndDropdown() autofocus disabled>
                                <option value="">Choose Addon Type</option>
                                <option value="P" {{"P" == $addonDetails->addon_type_name  ? 'selected' : ''}}>Accessories</option>
                                <!-- <option value="D">Documentation</option>
                                <option value="DP">Documentation On Purchase</option>
                                <option value="E">Others</option>
                                <option value="S">Shipping Cost</option> -->
                                <option value="SP" {{"SP" == $addonDetails->addon_type_name  ? 'selected' : ''}}>Spare Parts</option>
                                <option value="K" {{"K" == $addonDetails->addon_type_name  ? 'selected' : ''}}>Kit</option>
                                <!-- <option value="W">Warranty</option> -->
                            </select>
                            <input id="addon_type_hiden" name="addon_type_hiden" type="text" value="{{$addonDetails->addon_type_name}}" hidden>
                            <input id="addon_type_show" type="text" class="form-control" hidden readonly onclick=showAlert()>
                            <span id="AddonTypeError" class="required-class invalid-feedback"></span>                       
                            <span id="addon_type_required" class="email-phone required-class paragraph-class"></span>
                        </div>
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="addon_code" class="col-form-label text-md-end">{{ __('Addon Code') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <input id="addon_code" type="text" class="form-control widthinput @error('addon_code') is-invalid @enderror" name="addon_code" 
                            placeholder="Addon Code" value="{{ $addonDetails->addon_code }}"  autocomplete="addon_code" autofocus readonly>
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
                                    <option value="{{$addon->id}}" {{ $addon->id == $addonDetails->addon_id  ? 'selected' : ''}}>{{$addon->name}}</option>
                                @endforeach
                            </select>
                            @error('addon_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <span id="addonNameError" class="invalid-feedback"></span>
                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-1">
                            @can('master-addon-create')
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-addon-create']);
                            @endphp
                            @if ($hasPermission)
                            <a id="addnewAddonButton" data-toggle="popover" data-trigger="hover" title="Create New Addon" data-placement="top" style="float: right;" 
                            class="btn btn-sm btn-info modal-button" data-modal-id="createNewAddon"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                            @endif
                            @endcan
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="purchase_price" class="col-form-label text-md-end">{{ __('Least Purchase Price') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                        <div class="input-group">

                        <input id="purchase_price" type="number" min="0" step="any" class="form-control widthinput @error('purchase_price') is-invalid @enderror" 
                        name="purchase_price" placeholder="Least Purchase Price ( AED )" value="{{ $addonDetails->LeastPurchasePrices->purchase_price_aed }}"  
                        autocomplete="purchase_price" autofocus readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>  
                                                </div> 
                            @error('purchase_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="selling_price" class="col-form-label text-md-end">
                                @if(isset($addonDetails->SellingPrice->selling_price))
                                {{ __('Selling Price') }}
                                @elseif(isset($addonDetails->PendingSellingPrice->selling_price))
                                {{ __('Selling Price') }}
                                <label class="badge badge-soft-danger">Approval Awaiting</label>
                                @endif
                            </label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                        <div class="input-group">
                        <input readonly id="selling_price" oninput="inputNumberAbs(this)" class="form-control widthinput @error('selling_price') is-invalid @enderror"
                         name="selling_price" placeholder="Enter Selling Price" value="{{$addonDetails->SellingPrice->selling_price ?? 
                            $addonDetails->PendingSellingPrice->selling_price ?? ''}}" 
                                autocomplete="selling_price">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>  
                                                </div> 
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
                        <div class="input-group">
                         

                        <input id="lead_time" type="number" aria-label="measurement" aria-describedby="basic-addon2" onkeypress="return event.charCode >= 48" min="1" 
                        class="form-control widthinput @error('lead_time') is-invalid @enderror" name="lead_time" placeholder="Enter Lead Time" 
                        value="{{ $addonDetails->lead_time }}"  autocomplete="lead_time">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">Days</span>
                                                    </div>  
                                                </div> 
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
                            <input id="payment_condition" type="text" class="form-control widthinput @error('payment_condition') is-invalid @enderror" 
                            name="payment_condition" placeholder="Enter Payment Condition" value="{{ $addonDetails->payment_condition }}"  autocomplete="payment_condition" 
                            autofocus>
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
                            <label for="fixing_charges_included" class="col-form-label text-md-end">{{ __('Fixing Charges Included') }}</label>
                        </div>
                            <div class="col-xxl-3 col-lg-3 col-md-6" id="">
                                <fieldset>
                                    <div class="some-class">
                                        <input type="radio" class="radioFixingCharge" name="fixing_charges_included" 
                                        value="yes" id="yes" {{"yes" == $addonDetails->fixing_charges_included  ? 'checked' : ''}} />
                                        <label for="yes">Yes</label>
                                        <input type="radio" class="radioFixingCharge" name="fixing_charges_included" 
                                        value="no" id="no" {{"no" == $addonDetails->fixing_charges_included  ? 'checked' : ''}} />
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
                            <span class="error">* </span>
                            <label for="fixing_charge_amount" class="col-form-label text-md-end">{{ __('Fixing Charge Amount') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12" hidden id="FixingChargeAmountDivBr">
                        <div class="input-group">
                        <input id="fixing_charge_amount" oninput="inputNumberAbs(this)" class="form-control widthinput" name="fixing_charge_amount" placeholder="Fixing Charge Amount" 
                        value="{{ $addonDetails->fixing_charge_amount }}" autocomplete="fixing_charge_amount" >
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>  
                                                </div> 
                            @error('fixing_charge_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <span id="fixingChargeAmountError" class="invalid-feedback"></span>
                        </div>
                        </br>
                        <div class="col-xxl-2 col-lg-6 col-md-12" hidden id="partNumberDiv">
                            <span class="error">* </span>
                            <label for="part_number" class="col-form-label text-md-end">{{ __('Part Number') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12" hidden id="partNumberDivBr">
                        <input id="part_number" type="text" class="form-control widthinput" name="part_number" placeholder="Part Number" 
                        value="{{ $addonDetails->part_number }}" autocomplete="part_number" onkeyup="setPartNumber(this)">
                            @error('part_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <span id="partNumberError" class="invalid-feedback partNumberError"></span>
                        </div>
                    </div>
                    </br>
                    <div class="row" hidden id="rowPartNumber">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="part_number" class="col-form-label text-md-end">{{ __('Part Number') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <input id="part_numberRaw" type="text" class="form-control widthinput" name="part_number" placeholder="Part Number" 
                            value="{{ $addonDetails->part_number }}" autocomplete="part_number" onkeyup="setPartNumber(this)">
                            @error('part_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <span id="partNumberError1" class="invalid-feedback partNumberError"></span>
                        </div>
                    </div>
                    <br hidden id="rowPartNumberBr">
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="additional_remarks" class="col-form-label text-md-end">{{ __('Additional Remarks') }}</label>
                        </div>
                        <div class="col-xxl-10 col-lg-6 col-md-12">
                            <textarea rows="5" id="additional_remarks" type="text" class="form-control @error('additional_remarks') is-invalid @enderror" 
                            name="additional_remarks" placeholder="Enter Additional Remarks" value="{{ $addonDetails->additional_remarks }}"  
                            autocomplete="additional_remarks" autofocus>{{ $addonDetails->additional_remarks }}</textarea>
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
                    <input id="image" type="file" class="form-control widthinput" name="image" autocomplete="image" onchange="readURL(this);" />
                    <span id="addonImageError" class="email-phone required-class paragraph-class"></span>
                    </br>
                    </br>
                    <center>
                    <img id="blah" src="{{ asset('addon_image/' . $addonDetails->image) }}" alt="your image" class="contain" data-modal-id="showImageModal" 
                    onclick="showImage()"/>
                    </center>
                   
                </div>
               
                @include('addon.edit.brandModel')
                <div class="card"  id="kitSupplier" >
                    <div class="card-header">
                        <h4 class="card-title">Addon Suppliers And Purchase Price</h4>
                    </div>
                    <div id="London" class="tabcontent">
                        <div class="row">
                            <div class="card-body">
                                @if($addonDetails->addon_type_name == 'P' || $addonDetails->addon_type_name == 'SP')
                                @include('addon.edit.supplierprice')
                                @elseif($addonDetails->addon_type_name == 'K')
                                @include('addon.kit')
                                @endif
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
                                        <textarea rows="3" id="new_addon_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" 
                                        placeholder="Enter Addon Name" value="{{ old('name') }}"  autofocus></textarea>
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
                            <button type="button" class="btn btn-primary btn-sm" id="createAddonId" style="float: right;"><i class="fa fa-check" aria-hidden="true"></i>
                             Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="showImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenteredLabel" style="text-align:center;"> Addon Image </h5>
                            <button type="button" class="btn btn-secondary btn-sm close form-control" data-dismiss="modal" aria-label="Close" onclick="closemodal()">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row modal-row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <center>
                                            <img id="showImage" src="" alt="your image" class=""/>
                                        </center>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
     var data = {!! json_encode($addonDetails) !!};
    //  console.log(data.fixing_charges_included);
        var selectedSuppliers = [];
        var oldselectedSuppliers = [];
        var ifModelLineExist = [];
        var currentAddonType = '';
        var selectedBrands = [];
        var i=1;
        var fixingCharge = 'yes';
        $(document).ready(function ()
        {
            currentAddonType =  $('#addon_type').val();
            if(data.fixing_charges_included == 'no')
            {
                let showFixingChargeAmount = document.getElementById('FixingChargeAmountDiv');
                showFixingChargeAmount.hidden = false
                let showFixingChargeAmountBr = document.getElementById('FixingChargeAmountDivBr');
                showFixingChargeAmountBr.hidden = false
            }
            if(data.addon_type == 'SP')
            {
                alert('show part number');
                // let showFixingChargeAmount = document.getElementById('FixingChargeAmountDiv');
                // showFixingChargeAmount.hidden = false
                // let showFixingChargeAmountBr = document.getElementById('FixingChargeAmountDivBr');
                // showFixingChargeAmountBr.hidden = false
            }
            $('#addnewAddonButton').hide();
            $.ajaxSetup
            ({
                headers: 
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // $('#blah').css('visibility', 'hidden');
            $("#addon_id").attr("data-placeholder","Choose Addon Name....     Or     Type Here To Search....");
            $("#addon_id").select2({
                maximumSelectionLength: 1,
            });
            // $('#addon_id').select2();
            $("#supplierArray1").attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
            $("#supplierArray1").select2({
                // maximumSelectionLength: 1,
            });
            $('#brandModelNumberId').hide();
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
                            $msg = "";
                            removeAddonTypeError($msg);
                            removeAddonNameError($msg);        
                            $('#addon_code').val(data.newAddonCode);
                            $("#addon_type").val(data.addon_type.addon_type);
                            $("#selectBrandMo1").removeAttr('disabled');
                        }
                    });
                }
                else
                {
                    $('#addnewAddonButton').show();
                }
            });
           
            // $('#submit').click(function()
            // {
            //     var value = $('#addon_id').val();
            //     var a = $('#cityname [value="' + value + '"]').data('value');
            //     $('#addon_name').val(a);
            // });
            // var j=1;

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
            //        html += '<input list="" id="addon_name1" type="text" class="form-control widthinput @error('addon_name') is-invalid @enderror" name="model[]" placeholder="Choose Model Line" value=""  autocomplete="addon_name" autofocus>';
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
                    // document.getElementById("AddonTypeError").classList.add("paragraph-class");
                    // document.getElementById("AddonTypeError").textContent="Please select addon type before create new addon";
                    $msg ="Please select addon type before create new addon";
                    showAddonTypeError($msg);
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
        $('form').on('submit', function (e) 
        {
            var inputAddonType = $('#addon_type').val();
            var inputAddonName = $('#addon_id').val();
            var inputBrand = $('#selectBrand1').val();
            var inputsupplierId = $('#itemArr1').val();
            var inputPurchasePriceAED = $('#addon_purchase_price_1').val();
            var inputPurchasePriceUSD = $('#addon_purchase_price_in_usd_1').val();
            var formInputError = false;
            if(inputsupplierId == '')
            {
                $msg = "Supplier is required";
                showSupplierError($msg);
                formInputError = true;
            }
            if(inputPurchasePriceAED == '')
            {
                $msg = "Purchase price is required";
                showPurchasePriceAEDError($msg);
                formInputError = true;
            }
            if(inputPurchasePriceUSD == '')
            {
                $msg = "Purchase price is required";
                showPurchasePriceUSDError($msg);
                formInputError = true;
            }
            if(inputBrand == '')
            {
                $msg = "Brand is required";
                showBrandError($msg);
                formInputError = true;
            }
            if(inputAddonType == '')
            {
                $msg = "Addon Type is required";
                showAddonTypeError($msg);
                formInputError = true;
            }
            else
            {
                if(inputAddonType == 'SP')
                {
                    var inputPartNumber = $('#part_number').val();
                    var inputSPBrand = $('#selectBrandMo1').val();
                    if(inputPartNumber == '')
                    {
                        $msg = "Part Number is required";
                        showPartNumberError($msg);
                        formInputError = true;
                    }
                    if(inputSPBrand == '')
                    {
                        $msg = "Brand is required";
                        showSPBrandError($msg);
                        formInputError = true;
                    }
                }
                else
                {
                    var inputBrand = $('#selectBrand1').val();
                    if(inputBrand == '')
                    {
                        $msg = "Brand is required";
                        showBrandError($msg);
                        formInputError = true;
                    }
                }
                if(inputAddonType == 'K')
                {
                    var inputkitSupplierDropdown1 = $('#kitSupplierDropdown1').val();
                    var inputkitSupplier1Item1 = $('#kitSupplier1Item1').val();
                    var inputSupplier1Kit1Quantity = $('#Supplier1Kit1Quantity').val();
                    var inputSupplier1Kit1UnitPriceAED = $('#Supplier1Kit1UnitPriceAED').val();
                    var inputSupplier1Kit1TotalPriceAED = $('#Supplier1Kit1TotalPriceAED').val();
                    var inputSupplier1Kit1UnitPriceUSD = $('#Supplier1Kit1UnitPriceUSD').val();
                    var inputSupplier1Kit1TotalPriceUSD = $('#Supplier1Kit1TotalPriceUSD').val();
                    if(inputkitSupplierDropdown1 == '')
                    {
                        $msg = "Supplier is required";
                        showkitSupplierDropdown1Error($msg);
                        formInputError = true;
                    }
                    if(inputkitSupplier1Item1 == '')
                    {
                        $msg = "Kit item is required";
                        showkitSupplier1Item1Error($msg);
                        formInputError = true;
                    }
                    if(inputSupplier1Kit1Quantity == '')
                    {
                        $msg = "Item quantity is required";
                        showSupplier1Kit1QuantityError($msg);
                        formInputError = true;
                    }
                    else if(inputSupplier1Kit1Quantity <= 0)
                    {
                        $msg = "Item quantity is must be greater than zero";
                        showSupplier1Kit1QuantityError($msg);
                        formInputError = true;
                    }
                    if(inputSupplier1Kit1UnitPriceAED == '')
                    {
                        $msg = "Item unit price is required";
                        showSupplier1Kit1UnitPriceAEDError($msg);
                        formInputError = true;
                    }
                    if(inputSupplier1Kit1TotalPriceAED == '')
                    {
                        $msg = "Item total price is required";
                        showSupplier1Kit1TotalPriceAEDError($msg);
                        formInputError = true;
                    }
                    if(inputSupplier1Kit1UnitPriceUSD == '')
                    {
                        $msg = "Item unit price is required";
                        showSupplier1Kit1UnitPriceUSDError($msg);
                        formInputError = true;
                    }
                    if(inputSupplier1Kit1TotalPriceUSD == '')
                    {
                        $msg = "Item total price is required";
                        showSupplier1Kit1TotalPriceUSDError($msg);
                        formInputError = true;
                    }
                }
                else
                {
                    var inputsupplierId = $('#itemArr1').val();
                    var inputPurchasePriceAED = $('#addon_purchase_price_1').val();
                    var inputPurchasePriceUSD = $('#addon_purchase_price_in_usd_1').val();
                    if(inputsupplierId == '')
                    {
                        $msg = "Supplier is required";
                        showSupplierError($msg);
                        formInputError = true;
                    }
                    if(inputPurchasePriceAED == '')
                    {
                        $msg = "Purchase price is required";
                        showPurchasePriceAEDError($msg);
                        formInputError = true;
                    }
                    if(inputPurchasePriceUSD == '')
                    {
                        $msg = "Purchase price is required";
                        showPurchasePriceUSDError($msg);
                        formInputError = true;
                    }
                }
            }
            if(inputAddonName == '')
            {
                $msg = "Addon Name is required";
                showAddonNameError($msg);
                formInputError = true;
            }
            if(fixingCharge == 'no')
            {
                var inputFixingChargeAmount = $('#fixing_charge_amount').val();
                if(inputFixingChargeAmount == '')
                {
                    $msg = "Fixing Charge Amount is required";
                    showFixingChargeAmountError($msg);
                    formInputError = true;
                }
            }
            if(formInputError == true)
            {
                e.preventDefault();
            }
        });
        function validationOnKeyUp(clickInput)
        {
            if(clickInput.id == 'itemArr1')
            {
                var value = clickInput.value; alert(value);
                if(value == '')
                {
                    if(value.legth != 0)
                    {
                        $msg = "Supplier Type is required";
                        showSupplierTypeError($msg);
                    }
                }
                else
                {
                    removeSupplierTypeError();
                }
            }
        }
        function showBrandError($msg)
        {
            document.getElementById("brandError").textContent=$msg;
            document.getElementById("selectBrand1").classList.add("is-invalid");
            document.getElementById("brandError").classList.add("paragraph-class");
        }
        function removeBrandError($msg)
        {
            document.getElementById("brandError").textContent="";
            document.getElementById("selectBrand1").classList.remove("is-invalid");
            document.getElementById("brandError").classList.remove("paragraph-class");
        }
        function showSPBrandError($msg)
        {
            document.getElementById("mobrandError").textContent=$msg;
            document.getElementById("selectBrandMo1").classList.add("is-invalid");
            document.getElementById("mobrandError").classList.add("paragraph-class");
        }
        function removeSPBrandError($msg)
        {
            document.getElementById("mobrandError").textContent="";
            document.getElementById("selectBrandMo1").classList.remove("is-invalid");
            document.getElementById("mobrandError").classList.remove("paragraph-class");
        }
        function showkitSupplierDropdown1Error($msg)
        {
            document.getElementById("kitSupplierDropdown1Error").textContent=$msg;
            document.getElementById("kitSupplierDropdown1").classList.add("is-invalid");
            document.getElementById("kitSupplierDropdown1Error").classList.add("paragraph-class");
        }
        function removekitSupplierDropdown1Error($msg)
        {
            document.getElementById("kitSupplierDropdown1Error").textContent="";
            document.getElementById("kitSupplierDropdown1").classList.remove("is-invalid");
            document.getElementById("kitSupplierDropdown1Error").classList.remove("paragraph-class");
        }
        function showkitSupplier1Item1Error($msg)
        {
            document.getElementById("kitSupplier1Item1Error").textContent=$msg;
            document.getElementById("kitSupplier1Item1").classList.add("is-invalid");
            document.getElementById("kitSupplier1Item1Error").classList.add("paragraph-class");
        }
        function removekitSupplier1Item1Error($msg)
        {
            document.getElementById("kitSupplier1Item1Error").textContent="";
            document.getElementById("kitSupplier1Item1").classList.remove("is-invalid");
            document.getElementById("kitSupplier1Item1Error").classList.remove("paragraph-class");
        }
        function showSupplier1Kit1QuantityError($msg)
        {
            document.getElementById("Supplier1Kit1QuantityError").textContent=$msg;
            document.getElementById("Supplier1Kit1Quantity").classList.add("is-invalid");
            document.getElementById("Supplier1Kit1QuantityError").classList.add("paragraph-class");
        }
        function removeSupplier1Kit1QuantityError($msg)
        {
            document.getElementById("Supplier1Kit1QuantityError").textContent="";
            document.getElementById("Supplier1Kit1Quantity").classList.remove("is-invalid");
            document.getElementById("Supplier1Kit1QuantityError").classList.remove("paragraph-class");
        }
        function showSupplier1Kit1UnitPriceAEDError($msg)
        {
            document.getElementById("Supplier1Kit1UnitPriceAEDError").textContent=$msg;
            document.getElementById("Supplier1Kit1UnitPriceAED").classList.add("is-invalid");
            document.getElementById("Supplier1Kit1UnitPriceAEDError").classList.add("paragraph-class");
        }
        function removeSupplier1Kit1UnitPriceAEDError($msg)
        {
            document.getElementById("Supplier1Kit1UnitPriceAEDError").textContent="";
            document.getElementById("Supplier1Kit1UnitPriceAED").classList.remove("is-invalid");
            document.getElementById("Supplier1Kit1UnitPriceAEDError").classList.remove("paragraph-class");
        }
        function showSupplier1Kit1TotalPriceAEDError($msg)
        {
            document.getElementById("Supplier1Kit1TotalPriceAEDError").textContent=$msg;
            document.getElementById("Supplier1Kit1TotalPriceAED").classList.add("is-invalid");
            document.getElementById("Supplier1Kit1TotalPriceAEDError").classList.add("paragraph-class");
        }
        function removeSupplier1Kit1TotalPriceAEDError($msg)
        {
            document.getElementById("Supplier1Kit1TotalPriceAEDError").textContent="";
            document.getElementById("Supplier1Kit1TotalPriceAED").classList.remove("is-invalid");
            document.getElementById("Supplier1Kit1TotalPriceAEDError").classList.remove("paragraph-class");
        }
        function showSupplier1Kit1UnitPriceUSDError($msg)
        {
            document.getElementById("Supplier1Kit1UnitPriceUSDError").textContent=$msg;
            document.getElementById("Supplier1Kit1UnitPriceUSD").classList.add("is-invalid");
            document.getElementById("Supplier1Kit1UnitPriceUSDError").classList.add("paragraph-class");
        }
        function removeSupplier1Kit1UnitPriceUSDError($msg)
        {
            document.getElementById("Supplier1Kit1UnitPriceUSDError").textContent="";
            document.getElementById("Supplier1Kit1UnitPriceUSD").classList.remove("is-invalid");
            document.getElementById("Supplier1Kit1UnitPriceUSDError").classList.remove("paragraph-class");
        }
        function showSupplier1Kit1TotalPriceUSDError($msg)
        {
            document.getElementById("Supplier1Kit1TotalPriceUSDError").textContent=$msg;
            document.getElementById("Supplier1Kit1TotalPriceUSD").classList.add("is-invalid");
            document.getElementById("Supplier1Kit1TotalPriceUSDError").classList.add("paragraph-class");
        }
        function removeSupplier1Kit1TotalPriceUSDError($msg)
        {
            document.getElementById("Supplier1Kit1TotalPriceUSDError").textContent="";
            document.getElementById("Supplier1Kit1TotalPriceUSD").classList.remove("is-invalid");
            document.getElementById("Supplier1Kit1TotalPriceUSDError").classList.remove("paragraph-class");
        }
        function showSupplierError($msg)
        {
            document.getElementById("supplierError").textContent=$msg;
            document.getElementById("itemArr1").classList.add("is-invalid");
            document.getElementById("supplierError").classList.add("paragraph-class");
        }
        function removeSupplierError($msg)
        {
            document.getElementById("supplierError").textContent="";
            document.getElementById("itemArr1").classList.remove("is-invalid");
            document.getElementById("supplierError").classList.remove("paragraph-class");
        }
        function showPurchasePriceAEDError($msg)
        {
            document.getElementById("purchasePriceAEDError").textContent=$msg;
            document.getElementById("addon_purchase_price_1").classList.add("is-invalid");
            document.getElementById("purchasePriceAEDError").classList.add("paragraph-class");
        }
        function removePurchasePriceAEDError($msg)
        {
            document.getElementById("purchasePriceAEDError").textContent="";
            document.getElementById("addon_purchase_price_1").classList.remove("is-invalid");
            document.getElementById("purchasePriceAEDError").classList.remove("paragraph-class");
        }
        function showPurchasePriceUSDError($msg)
        {
            document.getElementById("purchasePriceUSDError").textContent=$msg;
            document.getElementById("addon_purchase_price_in_usd_1").classList.add("is-invalid");
            document.getElementById("purchasePriceUSDError").classList.add("paragraph-class");
        }
        function removePurchasePriceUSDError($msg)
        {
            document.getElementById("purchasePriceUSDError").textContent="";
            document.getElementById("addon_purchase_price_in_usd_1").classList.remove("is-invalid");
            document.getElementById("purchasePriceUSDError").classList.remove("paragraph-class");
        }
        function showAddonTypeError($msg)
        {
            document.getElementById("AddonTypeError").textContent=$msg;
            document.getElementById("addon_type").classList.add("is-invalid");
            document.getElementById("AddonTypeError").classList.add("paragraph-class");
        }
        function removeAddonTypeError($msg)
        {
            document.getElementById("AddonTypeError").textContent="";
            document.getElementById("addon_type").classList.remove("is-invalid");
            document.getElementById("AddonTypeError").classList.remove("paragraph-class");
        }
        function showPartNumberError($msg)
        {
            document.getElementById("partNumberError").textContent=$msg;
            document.getElementById("partNumberError1").textContent=$msg;
            document.getElementById("part_number").classList.add("is-invalid");
            document.getElementById("part_numberRaw").classList.add("is-invalid");
            document.getElementById("partNumberError").classList.add("paragraph-class");
            document.getElementById("partNumberError1").classList.add("paragraph-class");
        }
        function removePartNumberError($msg)
        {
            document.getElementById("partNumberError").textContent="";
            document.getElementById("partNumberError1").textContent="";
            document.getElementById("part_number").classList.remove("is-invalid");
            document.getElementById("part_numberRaw").classList.remove("is-invalid");
            document.getElementById("partNumberError").classList.remove("paragraph-class");
            document.getElementById("partNumberError1").classList.remove("paragraph-class");
        }
        function showAddonNameError($msg)
        {
            document.getElementById("addonNameError").textContent=$msg;
            document.getElementById("addon_id").classList.add("is-invalid");
            document.getElementById("addonNameError").classList.add("paragraph-class");
        }
        function removeAddonNameError($msg)
        {
            document.getElementById("addonNameError").textContent="";
            document.getElementById("addon_id").classList.remove("is-invalid");
            document.getElementById("addonNameError").classList.remove("paragraph-class");
        }
        function showFixingChargeAmountError($msg)
        {
            document.getElementById("fixingChargeAmountError").textContent=$msg;
            document.getElementById("fixing_charge_amount").classList.add("is-invalid");
            document.getElementById("fixingChargeAmountError").classList.add("paragraph-class");
        }
        function removeFixingChargeAmountError($msg)
        {
            document.getElementById("fixingChargeAmountError").textContent="";
            document.getElementById("fixing_charge_amount").classList.remove("is-invalid");
            document.getElementById("fixingChargeAmountError").classList.remove("paragraph-class");
        }
                        // $("#supplierArray"+index).select2();


        // $('.close').on('click', function()
        // {alert('jj');
        //     $('.modal').addClass('modalhide');
        //     $('.modal').removeClass('modalshow');
        // });

        function showImage()
        {
            var modal = document.getElementById("showImageModal");
            var img = document.getElementById("blah");
            var image = document.getElementById("image");
            var modalImg = document.getElementById("showImage");
            var modalImg = document.getElementById("showImage");
            $('.overlay').show();
            $('#showImageModal').addClass('modalshow');
            $('#showImageModal').removeClass('modalhide');
            modalImg.src = img.src;
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

        function getAddonCodeAndDropdown()
        {
            var e = document.getElementById("addon_type");
            var value = e.value;
            currentAddonType = value;
            if(currentAddonType != '')
            {
                $("#selectBrandMo1").removeAttr('disabled'); 
                $("#selectBrand1").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
                $("#selectBrand1").select2({
                    maximumSelectionLength: 1,
                });

                // document.getElementById("AddonTypeError").classList.remove("paragraph-class");
                // document.getElementById("AddonTypeError").classList.remove("paragraph-class");
                // document.getElementById("AddonTypeError").textContent="";
                document.getElementById("addon_type_required").textContent="";
                $msg = "";
                removeAddonTypeError($msg);
                // document.getElementById("addon_type_required").hidden = true;
                if(currentAddonType == 'SP' && ifModelLineExist != '')
                {
                    // alert('ji');
                    
                    
                    // showModelNumberDropdown(id,row);
                   
                }
                else
                {
                    // hideModelNumberDropdown(id,row);
                    
                }
                if(value == 'SP' )
                {
                    $("#brandModelLineId").hide();
                    $("#brandModelNumberId").show();
                    document.getElementById("brandModelNumberId").hidden = false;
                    $("#showaddtrim").hide();
                    $("#showaddtrimDis").show();
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
                        // let showrowPartNumberBr1 = document.getElementById('brandModelLineClass');
                        // showrowPartNumberBr1.hidden = true
                        // let showrowPartNumberBr2 = document.getElementById('brandModelNumberClass');
                        // showrowPartNumberBr2.hidden = false
                        
                    }
                    else
                    {
                        let showPartNumber = document.getElementById('partNumberDiv');
                        showPartNumber.hidden = false
                        let showPartNumberBr = document.getElementById('partNumberDivBr');
                        showPartNumberBr.hidden = false
                        // let showrowPartNumberBr1 = document.getElementById('brandModelLineClass');
                        // showrowPartNumberBr1.hidden = false
                        // let showrowPartNumberBr2 = document.getElementById('brandModelNumberId');
                        // showrowPartNumberBr2.hidden = true
                       
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
                    $("#brandModelLineId").show();
                    $("#brandModelNumberId").hide();
                    $("#showaddtrim").show();
                    $("#showaddtrimDis").hide();
                }
                $("#purchase_price").val('');
                if(value == 'K')
                {
                    hidenotKitSupplier();
                    showkitSupplier();
                    setLeastPurchasePriceAED();
                }
                else
                {
                    hidekitSupplier();
                    shownotKitSupplier();
                    setLeastAEDPrice();
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
                $('#addon_code').val('');
                $msg = "Addon Type is required";
                showAddonTypeError($msg);
            }
        }
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
                        $msg = "";
                        removeAddonNameError($msg);
                    }
                });
            }
        });
        function setPartNumber(partNum)
        {
            $('#part_number').val(partNum.value);
            $('#part_numberRaw').val(partNum.value);
        }
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
                    document.getElementById("addonImageError").textContent='';
                    var reader = new FileReader();
                    reader.onload = function (e)
                    {
                        $('#blah').show();
                        $('#blah').css('visibility', 'visible');
                        $('#blah').attr('src', e.target.result);
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
            if(values != '')
            {
                var arrayOfNumbers = [];
                values.forEach(v => {
                    if(v != '')
                    {
                        arrayOfNumbers .push(v);
                    }
                });
                var size= arrayOfNumbers.length;
                if(size >= 1)
                {
                    var arrayOfNumbers = arrayOfNumbers.map(Number);
                    const minOfPrice = Math.min(...arrayOfNumbers);
                    $("#purchase_price").val(minOfPrice);
                }
            }
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
        function inputNumberAbs(currentPriceInput) 
        {
            
            var id = currentPriceInput.id
            var input = document.getElementById(id);
            var val = input.value;
            val = val.replace(/^0+|[^\d.]/g, '');
            if(val.split('.').length>2) 
            {
                val =val.replace(/\.+$/,"");
            }
            input.value = val;
            if(currentPriceInput.id == 'fixing_charge_amount')
            {
                var value = currentPriceInput.value;
                if(value == '')
                {
                   
                    if(value.legth != 0)
                    {
                        $msg = "Fixing Charge Amount is required";
                        showFixingChargeAmountError($msg);
                    }
                }
                else
                {
                    removeFixingChargeAmountError();
                }
            }
        }
        function disableDropdown()
        {
            document.getElementById("addon_type").hidden=true;
            if(currentAddonType == 'P')
            {
                document.getElementById("addon_type_show").value="Accessories";
            }
            else if(currentAddonType == 'K')
            {
                document.getElementById("addon_type_show").value="Kit";
            }
            else if(currentAddonType == 'SP')
            {
                document.getElementById("addon_type_show").value="Spare Parts";
            }
            document.getElementById("addon_type_show").hidden=false;
        }
        function enableDropdown()
        {
            var canEnableDropdown = 'yes';
            if(canEnableDropdown == 'yes' && currentAddonType == 'SP' || canEnableDropdown == 'yes' && currentAddonType == 'P')
            {
                var countNotKitSuplr = $(".supplierWithoutKit").find(".supplierWithoutKitApendHere").length;
                for (let i = 1; i <= countNotKitSuplr; i++)
                {
                    if($('#suppliers'+i).val() != '' || $('#addon_purchase_price_'+i).val() != '' || $('#addon_purchase_price_in_usd_'+i).val() != '')
                    {
                        canEnableDropdown = 'no';
                    }
                }
            }
            else if(canEnableDropdown == 'yes' && currentAddonType == 'K')
            {
                var countKitSuplr = $(".supplierAddForKit").find(".addSupplierForKitRow").length;
                for (let i = 1; i <= countKitSuplr; i++)
                {
                    if($('#kitSupplierDropdown'+i).val() != '' || $('#Supplier'+i+'TotalPriceAED').val() != '' || $('#Supplier'+i+'TotalPriceUSD').val() != '')
                    {
                        canEnableDropdown = 'no';
                    }
                    else
                    {
                        var countKitSuplrItem = '';
                        var countKitSuplrItem = $(".apendNewItemHere"+i).find(".kitItemRowForSupplier"+i).length;
                        for (let j = 1; j <= countKitSuplrItem; j++)
                        {
                            if($('#kitSupplier'+i+'Item'+j).val() != '' || $('Supplier'+i+'Kit'+j+'Quantity').val() != ''
                                || $('Supplier'+i+'Kit'+j+'UnitPriceAED').val() != '' || $('Supplier'+i+'Kit'+j+'TotalPriceAED').val() != ''
                                || $('Supplier'+i+'Kit'+j+'UnitPriceUSD').val() != '' || $('Supplier'+i+'Kit'+j+'TotalPriceUSD').val() != '' )
                            {
                                canEnableDropdown = 'no';
                            }
                        }
                    }
                }
            }
            if(canEnableDropdown == 'yes' && currentAddonType == 'P' || canEnableDropdown == 'yes' && currentAddonType == 'K')
            {
                var countBrandModal = $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
                for (let i = 1; i <= countBrandModal; i++)
                {
                    if($('#selectBrand'+i).val() != '' || $('#selectModelLine'+i).val() != '')
                    {
                        canEnableDropdown = 'no';
                    }
                }
            }
            else if(canEnableDropdown == 'yes' && currentAddonType == 'SP')
            { 
                var countModel = $(".brandMoDescrip").find(".brandMoDescripApendHere").length;
                for (let i = 1; i <= countModel; i++)
                {
                    if($('#selectBrandMo'+i).val() != '')
                    {
                        canEnableDropdown = 'no';
                    }
                    else
                    {
                        var countModelDesc = '';
                        var countModelDesc = $(".MoDes"+i).find(".MoDesApndHere"+i).length;
                        for (let j = 1; j <= countModelDesc; j++)
                        {
                            if($('#selectModelLineNum'+i+'Des'+j).val() != '' || $('selectModelNumberDiscri'+i+'Des'+j).val() != '')
                            {
                                canEnableDropdown = 'no';
                            }
                        }
                    }
                }
            }
            if(canEnableDropdown == 'yes')
            {
                document.getElementById("addon_type").hidden=false;
                document.getElementById("addon_type_show").value='';
                document.getElementById("addon_type_show").hidden=true;
            }
        }
</script>
@endsection
