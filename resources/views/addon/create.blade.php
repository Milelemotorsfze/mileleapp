@extends('layouts.main')
<style>
    .removePartNumber
    {
        margin-top: 0px!important;
    }
    .partNumberApendHere
    {
        margin-bottom: 10px!important;
    }
    .invalid-feedback-lead {
    width: 100%;
    margin-top: .25rem;
    font-size: 80%;
    color: #fd625e;
}
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
        padding-top:7px;
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
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
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
        <form id="createAddonForm" name="createAddonForm" method="POST" enctype="multipart/form-data" action="{{ route('addon.store') }}">
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
                            <!-- <select id="addon_type" name="addon_type" class="form-control" onchange=getAddonCodeAndDropdown() autofocus> -->
                            <select id="addon_type" name="addon_type" class="form-control" onchange=getAddonCodeAndDropdown() autofocus @if($addon_type == 'SP') disabled @endif>
                                <option value="">Choose Addon Type</option>
                                <option value="P">Accessories</option>
                                <!-- <option value="D">Documentation</option>
                                <option value="DP">Documentation On Purchase</option>
                                <option value="E">Others</option>
                                <option value="S">Shipping Cost</option> -->
                                <option value="SP" @if($addon_type == 'SP') selected @endif>Spare Parts</option>
                                <!-- <option value="K">Kit</option> -->
                                <!-- <option value="W">Warranty</option> -->
                            </select>
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
                            placeholder="Addon Code" value="{{$addon_code}}"  autocomplete="addon_code" autofocus readonly>
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                        <span class="error">* </span>
                            <label for="addon_id" class="col-form-label text-md-end">{{ __('Addon Name') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-5 col-md-11">
                            <select name="addon_id" id="addon_id" multiple="true" style="width: 100%;" autofocus @if($addon_id != '') disabled @endif>
                                @foreach($addons as $addon)
                                    <option class="{{$addon->addon_type}}" value="{{$addon->id}}" @if($addon_id == $addon->id) selected @endif>{{$addon->name}}</option>
                                @endforeach
                            </select>
                            <span id="addonNameError" class="invalid-feedback"></span>
                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-1">
                        @if($addon_id == '')
                            @can('master-addon-create')
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-addon-create']);
                            @endphp
                            @if ($hasPermission)
                            <a id="addnewAddonButton" data-toggle="popover" data-trigger="hover" title="Create New Addon" data-placement="top" style="float: right;"
                            class="btn btn-sm btn-info modal-button" data-modal-id="createNewAddon"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                            @endif
                            @endcan
                        @endif
                        </div>
                    </div>
                    </br>
                    <div class="row" >
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="addon_id" class="col-form-label text-md-end">{{ __('Addon Description') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-5 col-md-11">
                            <div id="select-description">
                                <select name="description" id="description" multiple="true" style="width: 100%;" >

                                </select>
                            </div>
                            <input type="text" hidden name="description_text" id="description-text" placeholder="Enter Addon Description" value=""
                                   class="form-control widthinput @error('description') is-invalid @enderror"
                            autofocus>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <span id="addonDescriptionError" class="invalid-feedback"></span>
                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-1">
{{--                            @can('master-addon-description-create')--}}
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-master-addon-description']);
                                @endphp
                                @if ($hasPermission)
                                    <a id="addnewDescriptionButton" data-toggle="popover" data-trigger="hover" title="Create New Description" data-placement="top" style="float: right;"
                                       class="btn btn-sm btn-info" ><i class="fa fa-plus" aria-hidden="true"></i> Add New description</a>
                                    <a id="descr-dropdown-button" data-toggle="popover" hidden data-trigger="hover" title="Create New Description" data-placement="top" style="float: right;"
                                    class="btn btn-sm btn-info" >Choose From List</a>
                                @endif
{{--                            @endcan--}}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="purchase_price" class="col-form-label text-md-end">{{ __('Least Purchase Price') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                        <div class="input-group">
                        <input id="purchase_price" type="number" min="0" step="any" class="form-control widthinput @error('purchase_price') is-invalid @enderror"
                            name="purchase_price" placeholder="Least Purchase Price ( AED )" value="{{ old('purchase_price') }}"  autocomplete="purchase_price" autofocus
                            readonly>
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
                            <label for="selling_price" class="col-form-label text-md-end">{{ __('Selling Price') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                        <div class="input-group">

                        <input id="selling_price" oninput="inputNumberAbs(this)" class="form-control widthinput @error('selling_price') is-invalid @enderror"
                        name="selling_price" placeholder="Enter Selling Price" value="{{ old('selling_price') }}" autocomplete="selling_price">
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
                        <div class="col-xxl-3 col-lg-2 col-md-4">
                            <label for="fixing_charges_included" class="col-form-label text-md-end">{{ __('Fixing Charges Included') }}</label>
                        </div>
                        <div class="col-xxl-3 col-lg-3 col-md-6" id="">
                                <fieldset>
                                    <div class="some-class">
                                        <input type="radio" class="radioFixingCharge" name="fixing_charges_included" value="yes" id="yes" checked />
                                        <label for="yes">Yes</label>
                                        <input type="radio" class="radioFixingCharge" name="fixing_charges_included" value="no" id="no" />
                                        <label for="no">No</label>
                                    </div>
                                </fieldset>
                                @error('fixing_charges_included')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12" hidden id="FixingChargeAmountDiv">
                            <span class="error">* </span>
                            <label for="fixing_charge_amount" class="col-form-label text-md-end">{{ __('Fixing Charge Amount') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12" hidden id="FixingChargeAmountDivBr">
                            <div class="input-group">
                                <input id="fixing_charge_amount" oninput="inputNumberAbs(this)" class="form-control widthinput" name="fixing_charge_amount"
                                    placeholder="Fixing Charge Amount" value="{{ old('fixing_charge_amount') }}" autocomplete="fixing_charge_amount">
                                <div class="input-group-append">
                                    <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                </div>
                                <span id="fixingChargeAmountError1" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <label for="additional_remarks" class="col-form-label text-md-end">{{ __('Additional Remarks') }}</label>
                        </div>
                        <div class="col-xxl-10 col-lg-6 col-md-12">
                            <textarea rows="5" id="additional_remarks" type="text" class="form-control @error('additional_remarks') is-invalid @enderror"
                            name="additional_remarks" placeholder="Enter Additional Remarks" value="{{ old('additional_remarks') }}"  autocomplete="additional_remarks"
                            autofocus></textarea>
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
                <span class="error">* </span>
                    <label for="choices-single-default" class="form-label font-size-13">Choose Addon Image</label>
                    <input id="image" type="file" class="form-control widthinput" name="image" autocomplete="image" onchange="readURL(this);" />
                    <span id="addonImageError" class="email-phone required-class paragraph-class"></span>
                    </br>
                    </br>
                    <center>
                    <img id="blah" src="#" alt="your image" class="contain" data-modal-id="showImageModal" onclick="showImage()"/>
                    </center>
                </div>
                <div class="card" id="partNumberDiv" hidden>
                    <div class="card-header">
                        <h4 class="card-title">Part Numbers</h4>
                    </div>
                    <div class="card-body">
                        <div class="row partNumberMain">
                            <div class="col-xxl-4 col-lg-6 col-md-12 partNumberApendHere" id="row-1">
                                <div class="row">
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input id="part_number_1" type="text" class="form-control widthinput part_number" name="part_number[1]"
                                        placeholder="Part Number" value="{{ old('part_number') }}"
                                        autocomplete="part_number" onkeyup="setPartNumber(this,1)">
                                        <span id="partNumberError_1" class="invalid-feedback partNumberError"></span>
                                    </div>
                                    <div class="col-xxl-3 col-lg-1 col-md-1">
                                        <a class="btn_round removePartNumber" data-index="1" >
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-12 col-lg-12 col-md-12" id="partNumberDivBr" hidden>
                                <a id="addPartNumberBtn" style="float: right;" class="btn btn-sm btn-info">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add Part Numbers</a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('addon.brandModel')
                <div class="card"  id="kitSupplier" >
                    <!-- <div class="card-header">
                        <h4 class="card-title">Addon Suppliers And Purchase Price</h4>
                    </div> -->
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
                                        <textarea rows="3" id="new_addon_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                        placeholder="Enter Addon Name" value="{{ old('name') }}" oninput="checkValidation()" autofocus></textarea>
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
                            <button type="button" class="btn btn-primary btn-sm" id="createAddonId" style="float: right;">
                            <i class="fa fa-check" aria-hidden="true"></i> Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="showImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenteredLabel" style="text-align:center;"> Addon Image </h5>
                            <button type="button" class="btn btn-secondary btn-sm close form-control" data-dismiss="modal" aria-label="Close" onclick="closeImage()">
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
<script type="text/javascript">
        var selectedSuppliers = [];
        var oldselectedSuppliers = [];
        var ifModelLineExist = [];
        var currentAddonType = '';
        var selectedBrands = [];
        var i=1;
        var fixingCharge = 'yes';
        var sub ='1';
        var imageIsOkay = false;
        var isPartNumberErrorExists = true;

        $(document).ready(function () {
            $("#addnewDescriptionButton").click(function () {
                $('#descr-dropdown-button').attr('hidden', false);
                $('#description-text').attr('hidden', false);
                $('#select-description').attr('hidden', true);
                $('#addnewDescriptionButton').attr('hidden', true);
                $("#description option:selected").prop("selected", false);
               $("#description").trigger('change.select2');
            });
            $('#description').change(function () {
                var addonType = $('#addon_type').val();
                if(addonType == 'P') {
                    uniqueCheckAccessories();

                }else if(addonType == 'SP') {
                    uniqueCheckSpareParts();
                }
            })
            $('#description-text').keyup('keyup, change', function () {
                var value = $(this).val();
                addonDescriptionUniqueCheck();
                var addonType = $('#addon_type').val();
                if(addonType == 'P') {
                    if(!value) {
                        uniqueCheckAccessories();
                    }else {

                        var indexValue =  $(".brandMoDescrip").find(".brandMoDescripApendHere").length;
                        for(var i=1;i<=indexValue;i++){
                            var $msg = "";
                           removeBrandError($msg,i);
                        }
                    }

                }else if(addonType == 'SP') {
                    if(!value) {
                        uniqueCheckSpareParts();
                    }else{
                        var indexValue =  $(".brandMoDescrip").find(".brandMoDescripApendHere").length;
                        for(var i=1;i<=indexValue;i++){
                            var index = $(".MoDes"+i).find(".MoDesApndHere"+i).length;
                            for(let j=1; j<=index; j++)
                            {
                                removeSPModelLineError(i,j);
                            }
                        }
                    }
                }
            });

            $("#descr-dropdown-button").click(function () {


                $('#description-text').attr('hidden', true);
                $('#select-description').attr('hidden', false);
                $("#addnewDescriptionButton").attr('hidden', false);
                $('#descr-dropdown-button').attr('hidden', true);
                $('#description-text').val("");
                var msg = "";
                removeAddonDescriptionError();
                formInputError = false;
            });

            $("#addon_type").change(function () {
                var addonType = $(this).val();
                $('#description').empty();
                let url = '{{ url('supplier-change-addon-type') }}';
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        addonType: addonType,
                    },
                    success: function (data) {
                        $('#suppliers1').empty();
                        jQuery.each(data, function (key, value) {
                            $('#suppliers1').append('<option value="' + value.id + '">' + value.supplier + '</option>');
                        });
                    }
                });
            })

            $.ajaxSetup
            ({
                headers:
                    {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
            $('#kitSupplier').hide();
            $('#branModaDiv').hide();
            $('#blah').css('visibility', 'hidden');
            $("#addon_id").attr("data-placeholder", "Choose Addon Name....     Or     Type Here To Search....");
            $("#addon_id").select2({
                maximumSelectionLength: 1,
            });
            $("#description").attr("data-placeholder", "Choose Addon Description....     Or     Type Here To Search....");
            $("#description").select2({
                maximumSelectionLength: 1,
            });
            // $('#addon_id').select2();
            $("#supplierArray1").attr("data-placeholder", "Choose Vendor....     Or     Type Here To Search....");
            $("#supplierArray1").select2({
                // maximumSelectionLength: 1,
            });
            $('#brandModelNumberId').hide();
            $('.radioFixingCharge').click(function () {
                var addon_type = $("#addon_type").val();
                fixingCharge = $(this).val();
                if ($(this).val() == 'yes') {
                    let showFixingChargeAmount = document.getElementById('FixingChargeAmountDiv');
                    showFixingChargeAmount.hidden = true
                    let showFixingChargeAmountBr = document.getElementById('FixingChargeAmountDivBr');
                    showFixingChargeAmountBr.hidden = true
                } else {
                    let showFixingChargeAmount = document.getElementById('FixingChargeAmountDiv');
                    showFixingChargeAmount.hidden = false
                    let showFixingChargeAmountBr = document.getElementById('FixingChargeAmountDivBr');
                    showFixingChargeAmountBr.hidden = false
                }
            });
            // $("#supplierArray1").select2();


            $('#addon_id').change(function () {
                var id = $('#addon_id').val();
                // fetch addon existing detils

                if (id != '') {
                    $('#addnewAddonButton').hide();
                    $.ajax
                    ({
                        url: '/addons/existingImage/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $msg = "";
                            removeAddonTypeError($msg);
                            removeAddonNameError($msg);
                            $('#addon_code').val(data.newAddonCode);
                            $("#addon_type").val(data.addon_type.addon_type);
                            var value = data.addon_type.addon_type;
                            currentAddonType = value;
                            if(value != '')
                            {
                                showAddonTypeDependsData(value);
                                uniqueCheck(value,id);
                            }
                            $("#selectBrand1").removeAttr('disabled');
                            $("#selectBrandMo1").removeAttr('disabled');
                        }
                    });
                } else {
                    $('#addnewAddonButton').show();
                }
            });
            $("#addPartNumberBtn").on("click", function ()
            {
                var index = $(".partNumberMain").find(".partNumberApendHere").length + 1;
                $(".partNumberMain").append(`
                                    <div class="col-xxl-4 col-lg-6 col-md-12 partNumberApendHere" id="row-${index}">
                                    <div class="row">
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input id="part_number_${index}" type="text" class="form-control widthinput part_number" name="part_number[${index}]"
                                        placeholder="Part Number" value="{{ old('part_number') }}"
                                        autocomplete="part_number" onkeyup="setPartNumber(this,${index})">
                                        <span id="partNumberError_${index}" class="invalid-feedback partNumberError"></span>
                                    </div>
                                    <div class="col-xxl-3 col-lg-1 col-md-1 add_del_btn_outer">
                                        <a class="btn_round removePartNumber" data-index="${index}" >
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                    </div>
                                    </div>
                `);
            });
            $(document.body).on('click', ".removePartNumber", function (e)
            {
               var countRow = 0;
                var countRow = $(".partNumberMain").find(".partNumberApendHere").length;
                if(countRow > 1)
                {
                    var indexNumber = $(this).attr('data-index');
                    var deletedValue = '';
                    deletedValue = $("#part_number_"+indexNumber).val();
                    $(this).closest('#row-'+indexNumber).remove();
                    $('.partNumberApendHere').each(function(i) {
                        var index = +i + +1;
                        $(this).attr('id','row-'+index);
                        $(this).find('.part_number').attr('name', 'part_number['+ index +']');
                        $(this).find('.part_number').attr('id', 'part_number_'+index);
                        $(this).find('.part_number').attr('onkeyup', 'setPartNumber(this,'+index+')');
                        $(this).find('.removePartNumber').attr('data-index',index);
                        $(this).find('.partNumberError').attr('id', 'partNumberError_'+index);
                        if(deletedValue != '')
                        {
                            partNumberUniquecheck(deletedValue);
                        }
                    });
                    enableDropdown();
                }
                else
                {
                    var confirm = alertify.confirm('You are not able to remove this row, Atleast one Part Number Required',function (e) {
                   }).set({title:"Can't Remove Part Number"})
                }
        })
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
            function partNumberUniquecheck(deletedValue)
            {
                var indexPartNumber = $(".partNumberMain").find(".partNumberApendHere").length;
                var existingPartNumbers = [];
                for(var l = 1; l <= indexPartNumber; l++)
                {
                    var partNumberInput = '';
                    partNumberInput = $('#part_number_'+l).val();
                    if(partNumberInput == deletedValue)
                    {
                        existingPartNumbers.push(partNumberInput);
                    }
                }
                if(existingPartNumbers.length <= 1)
                {
                    for(var l = 1; l <= indexPartNumber; l++)
                    {
                        var partNumberInput = '';
                        partNumberInput = $('#part_number_'+l).val();
                        if(partNumberInput == deletedValue)
                        {
                            isPartNumberErrorExists = true;
                            removePartNumberError(l);
                            disableDropdown();
                            uniqueCheckSpareParts();
                        }
                    }
                }
            }
            function uniqueCheck(addonType,id)
            {
                addonDescriptionUniqueCheck();
                if(addonType == 'P') {
                    uniqueCheckAccessories();

                }else if(addonType == 'SP') {
                    uniqueCheckSpareParts();
                }
                let url = '{{ route('addon.getAddonDescription') }}';
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        addon_id: id,
                        addonType:addonType
                    },
                    success: function (data) {
                        $('#description').empty();
                        jQuery.each(data, function (key, value) {
                            $('#description').append('<option value="' + value.id + '">' + value.description + '</option>');
                        });
                    }
                });
            }
        function addonDescriptionUniqueCheck() {
            var id = $('#addon_id').val();
            var description = $('#description-text').val();
            var addonType = $('#addon_type').val();

            let url = '{{ route('addon.getUniqueAddonDescription') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    addon_id: id,
                    description: description,
                    addonType:addonType

                },
                success: function (data) {
                    if (data > 0) {
                        var msg = "This Description is already existing"
                        showAddonDescriptionError(msg);
                        formInputError = true;
                    } else {
                        var msg = "";
                        removeAddonDescriptionError();
                        formInputError = false;
                    }
                }
            })
        }
        function uniqueCheckAccessories() {
            var addon_id = $('#addon_id').val();
            var description = $('#description').val();
            var newDescription = $('#description-text').val();
            var addonType = $('#addon_type').val();
            var uniqueCounts = [];
            var indexValue =  $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
            for (var i = 1; i <= indexValue; i++) {
                var brand =  $('#selectBrand'+i).val();
                var modelLine = $("#selectModelLine"+i).val();
                $.ajax({
                    url: "{{url('getUniqueAccessories')}}",
                    type: "GET",
                    async: false,
                    cache: false,
                    data:
                        {
                            addon_id: addon_id[0],
                            description:description[0],
                            newDescription:newDescription,
                            brand:brand[0],
                            index:i,
                            model_line:modelLine,
                            addonType:addonType
                        },
                    dataType: 'json',
                    success: function (data) {
                        if(data.count > 0 ) {
                            var modelLine = "";
                            if(data.model_line) {
                              var  modelLine = data.model_line;
                                $msg = "This Addon,Description,Brand and model line("+ modelLine +") Combination is existing";
                            }else if(data.is_all_brands > 0) {
                                $msg = "This Addon,Description,Brand (all brands) Combination is existing";
                            }
                            showBrandError($msg,data.index);
                            var count = data.count;
                            uniqueCounts.push(count);
                        }else{
                            $msg = "";
                            removeBrandError($msg,data.index);
                            uniqueCounts.pop();
                        }
                    }
                });
            }

            var uniqueValueCount = uniqueCounts.length;
            if(uniqueValueCount > 0) {
                formInputError = true;
            }else{
                formInputError = false;
            }
        }
        function uniqueCheckSpareParts(){
            var addon_id = $('#addon_id').val();
            var description = $('#description').val();
            var newDescription = $('#description-text').val();
            var addonType = $('#addon_type').val();
            var indexPartNumber = $(".partNumberMain").find(".partNumberApendHere").length;
            for(var k = 1; k <= indexPartNumber; k++)
            {
                var partNumber = $('#part_number_'+k).val();
                var isExistingUniqueCounts = [];
                var indexValue =  $(".brandMoDescrip").find(".brandMoDescripApendHere").length;
                for (var i = 1; i <= indexValue; i++) {
                    var brand =  $('#selectBrandMo'+i).val();
                    var index = $(".MoDes"+i).find(".MoDesApndHere"+i).length;
                    for(let j=1; j<=index; j++)
                    {
                        var modelLine = $('#selectModelLineNum'+ i+'Des'+j).val();
                        var modelNumber = $('#selectModelNumberDiscri'+ i+'Des'+j).val();
                        $.ajax({
                            url: "{{url('getUniqueSpareParts')}}",
                            type: "GET",
                            async: false,
                            cache: false,
                            data:
                                {
                                    addon_id: addon_id[0],
                                    description:description[0],
                                    newDescription:newDescription,
                                    part_number:partNumber,
                                    brand:brand[0],
                                    i:i,
                                    j:j,
                                    model_line:modelLine[0],
                                    model_number:modelNumber,
                                    addonType:addonType
                                },
                            dataType: 'json',
                            success: function (data) {
                                if(data.count > 0 ) {
                                    var modelNumber = "";
                                    if(data.model_number) {
                                        var modelNumber = data.model_number;
                                    }
                                    $msg = "This Addon,Description,Brand,model line and model Description("+ modelNumber +") Combination is existing";
                                    showSPModelLineError($msg,data.i,data.j);
                                    var count = data.count;
                                    isExistingUniqueCounts.push(count);
                                }else{

                                    removeSPModelLineError(data.i,data.j);
                                    isExistingUniqueCounts.pop();
                                }
                            }
                        });
                    }

                }
            }

            var uniqueValueCount = isExistingUniqueCounts.length;
            if(uniqueValueCount > 0) {
                formInputError = true;
            }else{
                formInputError = false;
            }
        }

        $('form').on('submit', function (e)
        {
            sub ='2';
            var inputAddonType = $('#addon_type').val();
            var inputAddonName = $('#addon_id').val();
            formInputError = false;
            // removeModelYearEndError();
            if(inputAddonType == 'P') {
                uniqueCheckAccessories();

            }else if(inputAddonType == 'SP') {
                uniqueCheckSpareParts();
            }
            addonDescriptionUniqueCheck();
            if(inputAddonType == '')
            {
                $msg = "Addon Type is required";
                showAddonTypeError($msg);
                formInputError = true;
            }
            else
            {
                if(inputAddonType == 'SP' || inputAddonType == 'P')
                {
                    var countVendorPriceRow = 0;
                    countVendorPriceRow = $(".supplierWithoutKit").find(".supplierWithoutKitApendHere").length;
                    for (let j = 1; j <= countVendorPriceRow; j++)
                    {
                        var inputsupplierId = $('#suppliers'+j).val();
                        var inputPurchasePriceAED = $('#addon_purchase_price_'+j).val();
                        var inputPurchasePriceUSD = $('#addon_purchase_price_in_usd_'+j).val();
                        if(inputsupplierId == '')
                        {
                            $msg = "Vendor is required";
                            showSupplierError($msg,j);
                            formInputError = true;
                        }
                        if(inputPurchasePriceAED == '')
                        {
                            $msg = "Purchase price is required";
                            showPurchasePriceAEDError($msg,j);
                            formInputError = true;
                        }
                        if(inputPurchasePriceUSD == '')
                        {
                            $msg = "Purchase price is required";
                            showPurchasePriceUSDError($msg,j);
                            formInputError = true;
                        }
                        var minLeadTime = $('#lead_time_'+j).val();
                        var maxLeadTime = $('#lead_time_max_'+j).val();
                        if(minLeadTime != '' && maxLeadTime != '')
                        {
                            if(Number(minLeadTime) > Number(maxLeadTime))
                            {
                                showMaxLeadTimeError(j);
                                formInputError = true;
                            }
                        }
                    }
                }
                if(inputAddonType == 'SP')
                {
                    var countPartNumber = $(".partNumberMain").find(".partNumberApendHere").length;
                    for (let i = 1; i <= countPartNumber; i++)
                    {
                        var inputPartNumber = $('#part_number_'+i).val();
                        if(inputPartNumber == '')
                        {
                            $msg = "Part Number is required";
                            showPartNumberError($msg,i);
                            formInputError = true;
                        }
                    }

                    var countBrandRow = 0;
                    countBrandRow = $(".brandMoDescrip").find(".brandMoDescripApendHere").length;
                    for (let i = 1; i <= countBrandRow; i++)
                    {
                        var inputSPBrand = $('#selectBrandMo'+i).val();
                        if(inputSPBrand == '')
                        {
                            $msg = "Brand is required";
                            showSPBrandError($msg,i);
                            formInputError = true;
                        }
                        else if(inputSPBrand != 'allbrands')
                        {
                            var countModelDescriptionRow = 0;
                            countModelDescriptionRow = $(".MoDes"+i).find(".MoDesApndHere"+i).length;
                            for (let j = 1; j <= countModelDescriptionRow; j++)
                            {
                                var inputSPModelLine = $('#selectModelLineNum'+i+'Des'+j).val();
                                if(inputSPModelLine == '')
                                {
                                    $msg = "Model line is required";
                                    showSPModelLineError($msg,i,j);
                                    formInputError = true;
                                }
                                else if(inputSPModelLine != 'allmodellines')
                                {
                                    var inputSPModelDescription = $('#selectModelNumberDiscri'+i+'Des'+j).val();
                                    if(inputSPModelDescription == '')
                                    {
                                        $msg = "Model Description is required";
                                        showSPModelDescriptionError($msg,i,j);
                                        formInputError = true;
                                    }
                                }
                                // validation check for model year
                                var inputModelYearStart = $('#selectModelYearStart'+i+'Des'+j).val();
                                var inputModelYearEnd = $('#selectModelYearEnd'+i+'Des'+j).val();
                                if(inputModelYearStart == '')
                                {
                                    $msg = "Model Year is required"
                                    showModelYearStartError($msg,i,j);
                                    formInputError = true;
                                }
                                // else if(inputModelYearStart.length != 4)
                                // {
                                //     $msg = "Model Year required 4 digits number"
                                //     showModelYearStartError($msg,i,j);
                                //     formInputError = true;
                                // }
                                else if(inputModelYearEnd != '' && inputModelYearEnd.length != 4)
                                {
                                    $msg = "Model Year required 4 digits number"
                                    showModelYearEndError($msg,i,j);
                                    formInputError = true;
                                }
                                else if(inputModelYearEnd != '' && inputModelYearStart != '')
                                {
                                    if(Number(inputModelYearEnd) > 2050) {
                                        $msg = "The Model Year should be between 2019-2050";
                                        showModelYearEndError($msg,i,j);
                                        formInputError = true;

                                    }else if(Number(inputModelYearEnd) <= Number(inputModelYearStart))
                                    {
                                        removeModelYearStartError(i,j);
                                        $msg = "Enter higher value than min leadtime"
                                        showModelYearEndError($msg,i,j);
                                        formInputError = true;
                                    }else{
                                       removeModelYearEndError(i,j);
                                    }
                                }
                                else
                                {
                                    removeModelYearStartError(i,j);
                                }

                            }
                        }
                    }
                }
                else
                {
                    var countBrandRow = 0;
                    countBrandRow = $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
                    for (let i = 1; i <= countBrandRow; i++)
                    {
                        var inputBrand = $('#selectBrand'+i).val();
                        if(inputBrand == '')
                        {
                            $msg = "Brand is required";
                            showBrandError($msg,i);
                            formInputError = true;
                        }
                        else if(inputBrand != 'allbrands')
                        {
                            var inputModelLines = '';
                            var inputModelLines = $('#selectModelLine'+i).val();
                            if(inputModelLines == '')
                            {
                                $msg = "Model Line is required";
                                showModelLineError($msg,i);
                                formInputError = true;
                            }
                        }
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
            if(imageIsOkay == false)
            {
                formInputError = true;
                document.getElementById("addonImageError").textContent='image with extension svg/jpeg/png/jpg/gif/bmp/tiff/jpe/jfif is required';
            }
            if(isPartNumberErrorExists == false)
            {
                e.preventDefault();
            }
            if(formInputError == true)
            {
                e.preventDefault();
            }
        });
        function validationOnKeyUp(clickInput,row)
        {
            var value = clickInput.value;
            if(value == '')
            {
                if(value.legth != 0)
                {
                    $msg = "Vendor is required";
                    showSupplierError($msg,row);
                }
            }
            else
            {
                removeSupplierError(row);
            }
        }
        function showBrandError($msg,i)
        {
            document.getElementById("brandError"+i).textContent=$msg;
            document.getElementById("selectBrand"+i).classList.add("is-invalid");
            document.getElementById("brandError"+i).classList.add("paragraph-class");
        }
        function removeBrandError($msg,i)
        {
            document.getElementById("brandError"+i).textContent="";
            document.getElementById("selectBrand"+i).classList.remove("is-invalid");
            document.getElementById("brandError"+i).classList.remove("paragraph-class");
        }
        function showModelLineError($msg,i)
        {
            document.getElementById("ModelLineError"+i).textContent=$msg;
            document.getElementById("selectModelLine"+i).classList.add("is-invalid");
            document.getElementById("ModelLineError"+i).classList.add("paragraph-class");
        }
        function removeModelLineError($msg,i)
        {
            document.getElementById("ModelLineError"+i).textContent="";
            document.getElementById("selectModelLine"+i).classList.remove("is-invalid");
            document.getElementById("ModelLineError"+i).classList.remove("paragraph-class");
        }
        function showSPBrandError($msg,i)
        {
            document.getElementById("mobrandError"+i).textContent=$msg;
            document.getElementById("selectBrandMo"+i).classList.add("is-invalid");
            document.getElementById("mobrandError"+i).classList.add("paragraph-class");
        }
        function removeSPBrandError($msg,i)
        {
            document.getElementById("mobrandError"+i).textContent="";
            document.getElementById("selectBrandMo"+i).classList.remove("is-invalid");
            document.getElementById("mobrandError"+i).classList.remove("paragraph-class");
        }
        function showSPModelLineError($msg,i,j)
        {
            document.getElementById('ModelLineError_'+i+'_'+j).textContent=$msg;
            document.getElementById('selectModelLineNum'+i+'Des'+j).classList.add("is-invalid");
            document.getElementById('ModelLineError_'+i+'_'+j).classList.add("paragraph-class");
        }
        function removeSPModelLineError(i,j)
        {
            document.getElementById('ModelLineError_'+i+'_'+j).textContent="";
            document.getElementById('selectModelLineNum'+i+'Des'+j).classList.remove("is-invalid");
            document.getElementById('ModelLineError_'+i+'_'+j).classList.remove("paragraph-class");
        }
        function showSPModelDescriptionError($msg,i,j)
        {
            document.getElementById('ModelDescriptionError_'+i+'_'+j).textContent=$msg;
            document.getElementById('selectModelNumberDiscri'+i+'Des'+j).classList.add("is-invalid");
            document.getElementById('ModelDescriptionError_'+i+'_'+j).classList.add("paragraph-class");
        }
        function removeSPModelDescriptionError(i,j)
        {
            document.getElementById('ModelDescriptionError_'+i+'_'+j).textContent="";
            document.getElementById('selectModelNumberDiscri'+i+'Des'+j).classList.remove("is-invalid");
            document.getElementById('ModelDescriptionError_'+i+'_'+j).classList.remove("paragraph-class");
        }
        function showSupplierError($msg,j)
        {
            document.getElementById("supplierError_"+j).textContent=$msg;
            document.getElementById("suppliers"+j).classList.add("is-invalid");
            document.getElementById("supplierError_"+j).classList.add("paragraph-class");
        }
        function removeSupplierError(j)
        {
            document.getElementById("supplierError_"+j).textContent="";
            document.getElementById("suppliers"+j).classList.remove("is-invalid");
            document.getElementById("supplierError_"+j).classList.remove("paragraph-class");
        }
        function showPurchasePriceAEDError($msg,j)
        {
            document.getElementById("purchasePriceAEDError_"+j).textContent=$msg;
            document.getElementById("addon_purchase_price_"+j).classList.add("is-invalid");
            document.getElementById("purchasePriceAEDError_"+j).classList.add("paragraph-class");
        }
        function removePurchasePriceAEDError(j)
        {
            document.getElementById("purchasePriceAEDError_"+j).textContent="";
            document.getElementById("addon_purchase_price_"+j).classList.remove("is-invalid");
            document.getElementById("purchasePriceAEDError_"+j).classList.remove("paragraph-class");
        }
        function showPurchasePriceUSDError($msg,j)
        {
            document.getElementById("purchasePriceUSDError_"+j).textContent=$msg;
            document.getElementById("addon_purchase_price_in_usd_"+j).classList.add("is-invalid");
            document.getElementById("purchasePriceUSDError_"+j).classList.add("paragraph-class");
        }
        function removePurchasePriceUSDError(j)
        {
            document.getElementById("purchasePriceUSDError_"+j).textContent="";
            document.getElementById("addon_purchase_price_in_usd_"+j).classList.remove("is-invalid");
            document.getElementById("purchasePriceUSDError_"+j).classList.remove("paragraph-class");
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
        function showPartNumberError($msg, index)
        {
            document.getElementById("partNumberError_"+index).textContent=$msg;
            document.getElementById("part_number_"+index).classList.add("is-invalid");
            document.getElementById("partNumberError_"+index).classList.add("paragraph-class");
        }
        function removePartNumberError(index)
        {
            document.getElementById("partNumberError_"+index).textContent="";
            document.getElementById("part_number_"+index).classList.remove("is-invalid");
            document.getElementById("partNumberError_"+index).classList.remove("paragraph-class");
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
        function showAddonUniqueError($msg)
        {
            document.getElementById("addonNameUniqueError").textContent=$msg;
            document.getElementById("addon_id").classList.add("is-invalid");
            document.getElementById("addonNameUniqueError").classList.add("paragraph-class");
        }
        function removeAddonUniqueError($msg)
        {
            document.getElementById("addonNameUniqueError").textContent="";
            document.getElementById("addon_id").classList.remove("is-invalid");
            document.getElementById("addonNameUniqueError").classList.remove("paragraph-class");
        }
        function showAddonDescriptionError($msg)
        {
            document.getElementById("addonDescriptionError").textContent=$msg;
            document.getElementById("description-text").classList.add("is-invalid");
            document.getElementById("addonDescriptionError").classList.add("paragraph-class");
        }
        function removeAddonDescriptionError($msg)
        {
            document.getElementById("addonDescriptionError").textContent="";
            document.getElementById("description-text").classList.remove("is-invalid");
            document.getElementById("addonDescriptionError").classList.remove("paragraph-class");
        }

        function showFixingChargeAmountError($msg)
        {
            document.getElementById("fixingChargeAmountError1").textContent=$msg;
            document.getElementById("fixing_charge_amount").classList.add("is-invalid");
            document.getElementById("fixingChargeAmountError1").classList.add("paragraph-class");
        }
        function removeFixingChargeAmountError($msg)
        {
            document.getElementById("fixingChargeAmountError1").textContent="";
            document.getElementById("fixing_charge_amount").classList.remove("is-invalid");
            document.getElementById("fixingChargeAmountError1").classList.remove("paragraph-class");
        }
        function showNewAddonError($msg)
        {
            document.getElementById("newAddonError").textContent=$msg;
            document.getElementById("new_addon_name").classList.add("is-invalid");
            document.getElementById("newAddonError").classList.add("paragraph-class");
        }
        function removeNewAddonError()
        {
            document.getElementById("newAddonError").textContent="";
            document.getElementById("new_addon_name").classList.remove("is-invalid");
            document.getElementById("newAddonError").classList.remove("paragraph-class");
        }
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
                document.getElementById("addon_type_required").textContent="";
                $msg = "";
                removeAddonTypeError($msg);
                showAddonTypeDependsData(value);
                $("#purchase_price").val('');


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
                        // console.log(data.suppliers);
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
                                placeholder: 'Choose Addon ....     Or     Type Here To Search....',
                                allowClear: true,
                                data: AddonDropdownData,
                                maximumSelectionLength: 1,
                            });
                        }

                        $("#suppliers1").html("");
                        myarray1 = data.suppliers;
                        var size1= myarray1.length;
                        if(size1 >= 1)
                        {
                            let SupplierDropdownData   = [];
                            $.each(data.suppliers,function(key,value)
                            {
                                SupplierDropdownData.push
                                ({
                                    id: value.id,
                                    text: value.supplier
                                });
                            });
                            $('#suppliers1').select2
                            ({
                                placeholder: 'Choose Vendor ....     Or     Type Here To Search....',
                                allowClear: true,
                                data: SupplierDropdownData,
                                // maximumSelectionLength: 1,
                            });
                        }

                    }
                });
            }
            else
            {
                $('#kitSupplier').hide();
                $('#branModaDiv').hide();
                $('#addon_code').val('');
                $msg = "Addon Type is required";
                showAddonTypeError($msg);
            }
        }
        function showAddonTypeDependsData(value)
        {
            if(value == 'SP')
                {
                    $("#brandModelLineId").hide();
                    $("#brandModelNumberId").show();
                    document.getElementById("brandModelNumberId").hidden = false;
                    $("#showaddtrim").hide();
                    $("#showaddtrimDis").show();
                    let showPartNumber = document.getElementById('partNumberDiv');
                    showPartNumber.hidden = false
                    let showPartNumberBr = document.getElementById('partNumberDivBr');
                    showPartNumberBr.hidden = false
                }
                else if(value == 'P')
                {
                    let showPartNumber = document.getElementById('partNumberDiv');
                    showPartNumber.hidden = true
                    let showPartNumberBr = document.getElementById('partNumberDivBr');
                    showPartNumberBr.hidden = true
                    $("#brandModelLineId").show();
                    $("#brandModelNumberId").hide();
                    // document.getElementById("brandModelNumberId").hidden = true;
                    $("#showaddtrim").show();
                    $("#showaddtrimDis").hide();
                }
                $('#kitSupplier').show();
                    $('#branModaDiv').show();
                    hidekitSupplier();
                    shownotKitSupplier();
                    setLeastAEDPrice();
        }
        function checkValidation()
        {
            var value = $('#new_addon_name').val();
            if(value == '')
            {
                $msg = 'Addon Name is Required';
                showNewAddonError($msg);
            }
            else
            {
                removeNewAddonError();
            }
        }
        $('#createAddonId').on('click', function()
        {
            // create new addon and list new addon in addon list
            var value = $('#new_addon_name').val();
            if(value == '')
            {
                $msg = 'Addon Name is Required';
                showNewAddonError($msg);
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
                        if(result.error) {
                            $msg = result.error;
                            showNewAddonError($msg);
                        }else{
                            $('.overlay').hide();
                            $('.modal').removeClass('modalshow');
                            $('.modal').addClass('modalhide');
                            $('#addon_id').append("<option value='" + result.id + "'>" + result.name + "</option>");
                            $('#addon_id').val(result.id);
                            var selectedValues = new Array();
                            resetSelectedSuppliers(selectedValues);
                            $('#addnewAddonButton').hide();
                            $('#new_addon_name').val("");
                            $msg = "";
                            removeAddonNameError($msg);
                            removeNewAddonError();
                        }
                    }
                });
            }
        });
        function setPartNumber(partNum, index)
        {
            var partNumberInput = '';
            $('#part_number_'+index).val(partNum.value);
            partNumberInput = $('#part_number_'+index).val();
            if(partNumberInput == '')
            {
                $msg = "Part Number is required";
                showPartNumberError($msg,index);
                formInputError = true;
                enableDropdown();
            }
            else
            {
                removePartNumberError(index);
                disableDropdown();
                uniqueCheckSpareParts();
            }
            var indexPartNumber = $(".partNumberMain").find(".partNumberApendHere").length;
            var existingPartNumbers = [];
            for(var k = 1; k <= indexPartNumber; k++)
            {
                if(k != index)
                {
                    var partNumberInputOther = '';
                    var partNumberInputOther = $('#part_number_'+k).val();
                    existingPartNumbers.push(partNumberInputOther);
                }
            }
            var indexPartNum = $(".partNumberMain").find(".partNumberApendHere").length;
            for(var j = 1; j <= indexPartNumber; j++)
            {
                inpartNumberInput = $('#part_number_'+j).val();
                if(inpartNumberInput != '')
                {
                    var numexistingPartNumbers = [];
                    for(var m = 1; m <= indexPartNumber; m++)
                    {
                        if(m != j)
                        {
                            var partNumberInputOther = '';
                            var partNumberInputOther = $('#part_number_'+m).val();
                            numexistingPartNumbers.push(partNumberInputOther);
                        }
                    }
                    if(numexistingPartNumbers.includes(inpartNumberInput))
                    {
                        $msg = "Part Number is already exist";
                        showPartNumberError($msg,j);
                        formInputError = true;
                        enableDropdown();
                        isPartNumberErrorExists = false;
                    }
                    else
                    {
                        isPartNumberErrorExists = true;
                        removePartNumberError(j);
                        disableDropdown();
                        // uniqueCheckSpareParts();
                    }
                }
            }
        }
        function closeImage()
        {
            $('.overlay').hide();
            $('.modal').removeClass('modalshow');
            $('.modal').addClass('modalhide');
        }
        function closemodal()
        {
            $('.overlay').hide();
            $('.modal').removeClass('modalshow');
            $('.modal').addClass('modalhide');
            $('#new_addon_name').val("");
            removeNewAddonError();
        }
        function resetSelectedSuppliers(selectedValues)
        {
            $('#supplier_id').val(selectedValues);
            $('#supplier_id').trigger('change');
        }
        function readURL(input)
        {
            if(input.value == '')
            {
                $('#blah').hide();
                document.getElementById("addonImageError").textContent='Addon Image is required';
                imageIsOkay = false;
            }
            else
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
                    imageIsOkay = false;
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
                        imageIsOkay = true;
                    }
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
            if(usd == '')
            {
                document.getElementById('addon_purchase_price_'+i).value = "";
                $msg = "Purchase price is required";
                showPurchasePriceUSDError($msg,i);
                showPurchasePriceAEDError($msg,i);
                setLeastAEDPrice();
            }
            else
            {
                removePurchasePriceAEDError(i);
                removePurchasePriceUSDError(i);
                var aed = usd * 3.6725;
                var aed = aed.toFixed(4);
                aed = parseFloat(aed);
                if(aed == 0)
                {
                    document.getElementById('addon_purchase_price_'+i).value = "";
                    $msg = "Purchase price is required";
                    showPurchasePriceAEDError($msg,i);
                    showPurchasePriceUSDError($msg,i);
                }
                else
                {
                    document.getElementById('addon_purchase_price_'+i).value = aed;
                    removePurchasePriceAEDError(i);
                    removePurchasePriceUSDError(i);
                }
                setLeastAEDPrice();
            }
        }
        function calculateUSD(i)
        {
            var aed = $("#addon_purchase_price_"+i).val();
            if(aed == '')
            {
                document.getElementById('addon_purchase_price_in_usd_'+i).value = "";
                $msg = "Purchase price is required";
                showPurchasePriceAEDError($msg,i);
                showPurchasePriceUSDError($msg,i);
                setLeastAEDPrice();
            }
            else
            {
                removePurchasePriceAEDError(i);
                removePurchasePriceUSDError(i);
                var usd = aed / 3.6725;
                var usd = usd.toFixed(4);
                if(usd == 0)
                {
                    document.getElementById('addon_purchase_price_in_usd_'+i).value = "";
                    $msg = "Purchase price is required";
                    showPurchasePriceAEDError($msg,i);
                    showPurchasePriceUSDError($msg,i);
                }
                else
                {
                    document.getElementById('addon_purchase_price_in_usd_'+i).value = usd;
                    removePurchasePriceAEDError(i);
                    removePurchasePriceUSDError(i);
                }
                setLeastAEDPrice();
            }
        }
        function disableDropdown()
        {
            document.getElementById("addon_type").hidden=true;
            if(currentAddonType == 'P')
            {
                document.getElementById("addon_type_show").value="Accessories";
                $("#addon_id>option[class='SP']").attr('disabled','disabled');
                $("#addon_id>option[class='P']").removeAttr('disabled');
            }
            else if(currentAddonType == 'SP')
            {
                document.getElementById("addon_type_show").value="Spare Parts";
                $("#addon_id>option[class='P']").attr('disabled','disabled');
                $("#addon_id>option[class='SP']").removeAttr('disabled');
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
            if(canEnableDropdown == 'yes' && currentAddonType == 'P')
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
                            if($('#selectModelLineNum'+i+'Des'+j).val() != '' || $('#selectModelNumberDiscri'+i+'Des'+j).val() != '')
                            {
                                canEnableDropdown = 'no';
                            }
                        }
                    }
                }
                var countPartNumbers = $(".partNumberMain").find(".partNumberApendHere").length;
                for (let i = 1; i <= countPartNumbers; i++)
                {
                    if($('#part_number_'+i).val() != '')
                    {
                        canEnableDropdown = 'no';
                    }
                }
            }
            if(canEnableDropdown == 'yes')
            {
                document.getElementById("addon_type").hidden=false;
                document.getElementById("addon_type_show").value='';
                document.getElementById("addon_type_show").hidden=true;
            }
            $("#addon_id>option[class='P']").removeAttr('disabled');
            $("#addon_id>option[class='SP']").removeAttr('disabled');
        }
        function setLeastAEDPrice()
        {
            const values = Array.from(document.querySelectorAll('.notKitSupplierPurchasePrice')).map(input => input.value);
            if(values != '' || values !=',')
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
                    disableDropdown();
                }
                else
                {
                    $("#purchase_price").val("");
                    enableDropdown();
                }
            }
            else if(values ==',')
            {
                $("#purchase_price").val("");
                enableDropdown();
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
        // function checkGreaterYear(CurrentInput)
        // {
        //     var id = CurrentInput.id
        //     var input = document.getElementById(id);
        //     var val = input.value;
        //     val = val.replace(/^0+|[^\d]/g, '');
        //     input.value = val;
        //     var modelYearStart = $('#model_year_start').val();
        //     var modelYearEnd = $('#model_year_end').val();
        //     if(id == 'model_year_start')
        //     {
        //         if(modelYearStart == '')
        //         {
        //             $msg = "Model year is Required";
        //             showModelYearStartError($msg);
        //         }
        //         else if(modelYearStart.length != 4)
        //         {
        //             $msg = "Model Year required 4 digits number";
        //             showModelYearStartError($msg);
        //         }
        //     }
        //     else if(id == 'model_year_end')
        //     {
        //         if(Number(inputModelYearEnd) < Number(inputModelYearStart))
        //         {
        //             showModelYearEndError();
        //             formInputError = true;
        //         }
        //     }
        // }
        function showModelYearStartError($msg,i,j)
        {
            document.getElementById('modelYearStart'+i+'Error'+j).textContent=$msg;
            document.getElementById('selectModelYearStart'+i+'Des'+j).classList.add("is-invalid");
            document.getElementById('modelYearStart'+i+'Error'+j).classList.add("paragraph-class");
        }
        function showModelYearEndError($msg,i,j)
        {
            document.getElementById('modelYearEnd'+i+'Error'+j).textContent=$msg;
            document.getElementById('selectModelYearEnd'+i+'Des'+j).classList.add("is-invalid");
            document.getElementById('modelYearEnd'+i+'Error'+j).classList.add("paragraph-class");
        }
        function removeModelYearStartError(i,j)
        {
            document.getElementById('modelYearStart'+ i +'Error'+j).textContent="";
            document.getElementById('selectModelYearStart'+i+'Des'+j).classList.remove("is-invalid");
            document.getElementById('modelYearStart'+i+'Error'+j).classList.remove("paragraph-class");
        }
        function removeModelYearEndError(i,j)
        {
            document.getElementById('modelYearEnd'+i+'Error'+j).textContent="";
            document.getElementById('selectModelYearEnd'+i+'Des'+j).classList.remove("is-invalid");
            document.getElementById('modelYearEnd'+i+'Error'+j).classList.remove("paragraph-class");
        }
        function checkGreater(CurrentInput, row)
        {
            var id = CurrentInput.id
            var input = document.getElementById(id);
            var val = input.value;
            val = val.replace(/^0+|[^\d]/g, '');
            input.value = val;
            var minLeadTime = $('#lead_time_'+row).val();
            var maxLeadTime = $('#lead_time_max_'+row).val();
            if(minLeadTime != '' && maxLeadTime != '')
            {
                if(Number(minLeadTime) > Number(maxLeadTime))
                {
                    var id = CurrentInput.id;
                    if(id == 'lead_time')
                    {
                        showMinLeadTimeError(row);
                        removeMaxLeadTimeError(row);
                    }
                    else
                    {
                        showMaxLeadTimeError(row);
                        removeMinLeadTimeError(row);
                    }
                }
                else
                {
                    removeMinLeadTimeError(row);
                    removeMaxLeadTimeError(row);
                }
            }
            else
            {
                removeMinLeadTimeError(row);
                removeMaxLeadTimeError(row);
            }
        }
        function showMinLeadTimeError(row)
        {
            document.getElementById('minLeadTimeError_'+row).textContent="Enter smaller value than max leadtime";
            document.getElementById('lead_time_'+row).classList.add("is-invalid");
            document.getElementById('minLeadTimeError_'+row).classList.add("paragraph-class");
        }
        function showMaxLeadTimeError(row)
        {
            document.getElementById('maxLeadTimeError_'+row).textContent="Enter higher value than min leadtime";
            document.getElementById('lead_time_max_'+row).classList.add("is-invalid");
            document.getElementById('maxLeadTimeError_'+row).classList.add("paragraph-class");
        }
        function removeMinLeadTimeError(row)
        {
            document.getElementById('minLeadTimeError_'+row).textContent="";
            document.getElementById('lead_time_'+row).classList.remove("is-invalid");
            document.getElementById('minLeadTimeError_'+row).classList.remove("paragraph-class");
        }
        function removeMaxLeadTimeError(row)
        {
            document.getElementById('maxLeadTimeError_'+row).textContent="";
            document.getElementById('lead_time_max_'+row).classList.remove("is-invalid");
            document.getElementById('maxLeadTimeError_'+row).classList.remove("paragraph-class");
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
            if(currentPriceInput.id == 'fixing_charge_amount' && sub == '2')
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
        function inputNumberAbsLeadTime(currentPriceInput)
        {
            var id = currentPriceInput.id
            var input = document.getElementById(id);
            var val = input.value;
            val = val.replace(/^0+|[^\d-]/g, '');
            if(val.split('-').length>2)
            {
                val =val.replace(/\-+$/,"");
            }
            input.value = val;
        }
        function showAlert()
        {
            var confirm = alertify.confirm('You are not able to edit this field while any Vendor is in selection',function (e) {
                   }).set({title:"Remove Brands and Vendors"})
        }
</script>
@endsection
