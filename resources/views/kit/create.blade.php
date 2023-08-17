@extends('layouts.main')
<style>
    .paragraph-class
    {
        color: red;
        font-size:11px;
    }
    .required-class
    {
        font-size:11px;
    }
</style>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create Kit</h4>
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
                    <input hidden id="addon_type" name="addon_type" class="form-control" value="K">
                    <input type="hidden" name="fixing_charges_included" value="no">

                        <!-- <div class="col-xxl-2 col-lg-6 col-md-12" >
                            <span class="error">* </span>
                            <label for="addon_type" class="col-form-label text-md-end">{{ __('Addon Type') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12" >

                            <input class="form-control" value="Kit" readonly>
                            <input id="addon_type_show" type="text" class="form-control" hidden readonly onclick=showAlert()>
                            <span id="AddonTypeError" class="required-class invalid-feedback"></span>

                            <span id="addon_type_required" class="email-phone required-class paragraph-class"></span>
                        </div> -->
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="addon_code" class="col-form-label text-md-end">{{ __('Kit Code') }}</label>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <input id="addon_code" type="text" class="form-control widthinput @error('addon_code') is-invalid @enderror" name="addon_code"
                            placeholder="Addon Code" value="{{ $newAddonCode }}"  autocomplete="addon_code" autofocus readonly>
                            @error('addon_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="addon_code" class="col-form-label text-md-end">{{ __('Choose Kit Image') }}</label>
                        </div>
                        <div class="col-xxl-3 col-lg-6 col-md-12">
{{--                            <label for="choices-single-default" class="form-label font-size-13">Choose Kit Image</label>--}}
                            <input id="image" type="file" class="form-control widthinput" name="image" autocomplete="image" onchange="readURL(this);" />
                            <span id="addonImageError" class="email-phone required-class paragraph-class"></span>
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                        <span class="error">* </span>
                            <label for="addon_id" class="col-form-label text-md-end">{{ __('Kit Name') }}</label>
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
                            <span id="addonNameError" class="invalid-feedback"></span>
                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-1">
                            @can('master-kit-create')
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-kit-create']);
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
                            <label for="additional_remarks" class="col-form-label text-md-end">{{ __('Additional Remarks') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
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
                    <center>
                    <img id="blah" src="#" alt="your image" class="contain" data-modal-id="showImageModal" onclick="showImage()"/>
                    </center>
                </div>
                <div class="card" id="branModaDiv">
                    <div class="card-header">
                        <h4 class="card-title">Kit Brand and Model Lines</h4>
                    </div>
                    <div id="London" class="tabcontent">
                        <div class="row">
                            <div class="card-body">
                                <div class="row" >
                                    <div class="card" style="background-color:#fafaff; border-color:#e6e6ff;">
                                        <div id="London" class="tabcontent">
                                            <div class="row">
                                                <div class="card-body">
                                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                                        <div class="row">
                                                            <div class="col-xxl-4 col-lg-6 col-md-12">
                                                                <span class="error">* </span>
                                                                <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                                                                <select onchange=selectBrand(this.id,1) name="brand_id" id="brand"
                                                                       class="brands" multiple="true" style="width: 100%;" >
                                                                    @foreach($brands as $brand)
                                                                        <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span id="brandError" class="brandError invalid-feedback"></span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-md-12 p-0 brandModelLineClass" id="brandModelLineId">
                                                            <div class="col-md-12 brandModelLineDiscription p-0">
                                                                <div class="row brandModelLineDiscriptionApendHere" id="row-1">
                                                                    <div class="row" >
                                                                        <div class="col-xxl-1 col-lg-1 col-md-12">
                                                                        </div>
                                                                        <div class="col-xxl-4 col-lg-6 col-md-12 model-line-div" id="showDivdrop1" hidden>
                                                                            <span class="error">* </span>
                                                                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                                                                            <select class="compare-tag1 model-lines" name="brandModel[1][model_line_id]" id="selectModelLine1"
                                                                                    data-index="1" multiple="true" style="width: 100%;" onchange=selectModelLineDescipt(1)>
                                                                            </select>
                                                                            <span id="ModelLineError1" class="ModelLineError invalid-feedback"></span>
                                                                        </div>
                                                                        <div class="col-xxl-4 col-lg-6 col-md-12 model-number-div" id="showDivModelNumber1" hidden>
                                                                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                                                                            <select class="compare-tag1 model-numbers" name="brandModel[1][model_number][]" data-index="1"
                                                                                    id="selectModelNumber1"  multiple="true" style="width: 100%;" onchange="showValidationErrors(1)" >
                                                                            </select>
                                                                            <span id="ModelNumberError1" class="ModelNumberError invalid-feedback"></span>

                                                                        </div>
                                                                        <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                                                            <a class="btn_round removeButtonbrandModelLineDiscription" hidden data-index="1"
                                                                               id="removeButton1" >
                                                                                <i class="fas fa-trash-alt"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="showaddtrim" class="col-xxl-12 col-lg-12 col-md-12" hidden>
                                                                <a id="add" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a>
                                                            </div>
                                                            <input type="hidden" value="" id="index">
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card"  id="kitSupplier" >
                    <div id="London" class="tabcontent">
                        <div class="row">
                            <div class="card-body">
                            <div id="London" class="tabcontent">
                                <div class="row">
                                    <div class="card-body">
                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                            <div class="row">
                                                <div class="col-md-12 p-0">
                                                    <div class="col-md-12 apendNewaMainItemHere p-0">
                                                        <div class="row kitMainItemRowForSupplier kititemdelete" id="item-1">
                                                            <div class="col-xxl-10 col-lg-6 col-md-12">
                                                                <span class="error">* </span>
                                                                <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                                                                <select class="mainItem form-control widthinput MainItemsClass" name="mainItem[1][item]"
                                                                        id="mainItem1" onchange="KitItemValidations(this,1)"
                                                                        multiple="true" style="width: 100%;" data-index="1" >
                                                                </select>
                                                                <span id="KitItemError1" class="KitItemError invalid-feedback"></span>
                                                            </div>
                                                            <div class="col-xxl-1 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                                                                <span class="error">* </span>
                                                                <label for="choices-single-default" class="form-label font-size-13 ">Quantity</label>
                                                                <input name="mainItem[1][quantity]" id="mainQuantity1" placeholder="Enter Quantity" type="number" value="1" min="1"
                                                                        class="form-control widthinput quantityMainItem" autofocus
                                                                        oninput="validity.valid||(value='1');" required >

                                                                <span id="KitItemQuantityError1" class="kitItemQuantityError invalid-feedback"></span>
                                                            </div>
                                                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                                            <a id="removeMainItem1" class="btn_round removeMainItem" data-index="1">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                                    <a id="addSupplier" style="float: right;" class="btn btn-sm btn-primary addItemForSupplier1" onclick="addItem()"><i class="fa fa-plus" aria-hidden="true"></i> Add Item</a>
                                                </div>
                                            </div>
                                        </div>
                                        </br>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="MainKitItemIndex" value="">
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

                                <div class="row modal-row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <span class="error">* </span>
                                        <label for="kit Year" class="col-form-label text-md-end ">Kit Year</label>
                                    </div>
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <div class="input-group">
                                            <input type="number" id="kit_year" class="form-control @error('kit_year') is-invalid @enderror" name="kit_year"  >
                                            <span class="input-group-text">Year</span>
                                        </div>
                                        <span id="newAddonYearError" class="required-class paragraph-class"></span>
                                    </div>
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <span class="error">* </span>
                                        <label for="kit Year" class="col-form-label text-md-end ">Kit KiloMeter</label>
                                    </div>
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <div class="input-group">
                                            <input type="number" id="kit_km" class="form-control @error('kit_km') is-invalid @enderror" name="kit_km"  >
                                            <span class="input-group-text">KM</span>
                                        </div>
                                        <span id="newAddonKMError" class="required-class paragraph-class"></span>
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
<script type="text/javascript">
        var selectedSuppliers = [];
        var oldselectedSuppliers = [];
        var ifModelLineExist = [];
        var currentAddonType = '';
        var selectedBrands = [];
        var i=1;
        // var fixingCharge = 'yes';
        var sub ='1';
        var imageIsOkay = false;
        $(document).ready(function ()
        {
            $("#addon_type").change(function () {
                var addonType = $(this).val();
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
            // $('#kitSupplier').hide();
            // $('#branModaDiv').hide();
            $('#blah').css('visibility', 'hidden');
            $("#addon_id").attr("data-placeholder","Choose Kit Name....     Or     Type Here To Search....");
            $("#addon_id").select2({
                maximumSelectionLength: 1,
            });

            $('#brandModelNumberId').hide();
            // $('.radioFixingCharge').click(function()
            // {
            //     var addon_type = $("#addon_type").val();
            //     fixingCharge = $(this).val();
            //     if($(this).val() == 'yes')
            //     {
            //         let showFixingChargeAmount = document.getElementById('FixingChargeAmountDiv');
            //         showFixingChargeAmount.hidden = true
            //         let showFixingChargeAmountBr = document.getElementById('FixingChargeAmountDivBr');
            //         showFixingChargeAmountBr.hidden = true
            //     }
            //     else
            //     {
            //         let showFixingChargeAmount = document.getElementById('FixingChargeAmountDiv');
            //         showFixingChargeAmount.hidden = false
            //         let showFixingChargeAmountBr = document.getElementById('FixingChargeAmountDivBr');
            //         showFixingChargeAmountBr.hidden = false
            //     }
            // });
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
                            // removeAddonTypeError($msg);
                            removeAddonNameError($msg);
                            $('#addon_code').val(data.newAddonCode);
                            $("#addon_type").val(data.addon_type.addon_type);
                            $("#brand").removeAttr('disabled');
                            $("#selectBrandMo1").removeAttr('disabled');
                        }
                    });
                }
                else
                {
                    $('#addnewAddonButton').show();
                }
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
        });
        $('form').on('submit', function (e)
        {
            sub ='2';
            var inputAddonType = $('#addon_type').val();
            var inputAddonName = $('#addon_id').val();
            var inputBrand = $('#brand').val();
            var formInputError = false;

            if(inputAddonType == '')
            {
                $msg = "Addon Type is required";
                showAddonTypeError($msg);
                formInputError = true;
            }

            if(inputBrand == '')
            {
                $msg = "Brand is required";
                showBrandError($msg);
                formInputError = true;
            }else{
                countBrandRow = $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
                for (let i = 1; i <= countBrandRow; i++)
                {
                    var inputModelLines = '';
                    var inputModelLines = $('#selectModelLine'+i).val();
                    if(inputModelLines == '')
                    {
                        $msg = "Model Line is required";
                        showModelLineError($msg,i);
                        formInputError = true;
                    }
                    var inputModelNumber = '';
                    var inputModelNumber = $('#selectModelNumber'+i).val();
                    if(inputModelNumber == '')
                    {
                        $msg = "Model Number is required";
                        showModelNumberError($msg,i);
                        formInputError = true;
                    }
                }

            }

            var KitItemIndex = $(".apendNewaMainItemHere").find(".kitMainItemRowForSupplier").length;

            for (let i = 1; i <= KitItemIndex; i++)
            {
                var inputKitItem = $('#mainItem'+i).val();
                if(inputKitItem == '')
                {
                    $msg = "Kit Item is required";
                    showKitItemError($msg,i);
                    formInputError = true;
                }
                var inputKitQuantity = $('#mainQuantity'+i).val();
                if(inputKitQuantity == '')
                {
                    $msg = "Quantity is required";
                    showKitItemQuantityError($msg,i);
                    formInputError = true;
                }
            }
            if(inputAddonName == '')
            {
                $msg = "Addon Name is required";
                showAddonNameError($msg);
                formInputError = true;
            }
            // if(fixingCharge == 'no')
            // {
            //     var inputFixingChargeAmount = $('#fixing_charge_amount').val();
            //     if(inputFixingChargeAmount == '')
            //     {
            //         $msg = "Fixing Charge Amount is required";
            //         showFixingChargeAmountError($msg);
            //         formInputError = true;
            //     }
            // }
            if(imageIsOkay == false)
            {
                formInputError = true;
                document.getElementById("addonImageError").textContent='image with extension svg/jpeg/png/jpg/gif/bmp/tiff/jpe/jfif is required';
            }
            if(formInputError == true)
            {
                e.preventDefault();
            }
        });
        function KitItemValidations(clickInput, index) {
            var kitItem = clickInput.value;
            if(kitItem == '')
            {
                $msg = "Kit Item is required";
                showKitItemError($msg,index)
            }else{
                $msg = "";
                removeKitItemError($msg,index)
            }
        }
        function validationOnKeyUp(clickInput,index)
        {
            var kitItemQuantity = clickInput.value;

            if(kitItemQuantity == '')
            {
                $msg = "Quantity is required";
                showKitItemQuantityError($msg,index)
            }else{
                $msg = "";
                removeKitItemQuantityError($msg,index)
            }

        }
        function showSupplierTypeError($msg)
        {
            // document.getElementById("supplierError").textContent=$msg;
            // document.getElementById("supplier_type").classList.add("is-invalid");
            // document.getElementById("supplierError").classList.add("paragraph-class");
            // $("#supplier_type").attr("data-placeholder","Choose Addon Name....     Or     Type Here To Search....");
            // $("#supplier_type").select2({
            //     containerCssClass : "form-control is-invalid"
            // });
        }
        function removeSupplierTypeError()
        {
            // document.getElementById("supplierError").textContent="";
            // document.getElementById("supplier_type").classList.remove("is-invalid");
            // document.getElementById("supplierError").classList.remove("paragraph-class");
        }
        function showBrandError($msg)
        {
            document.getElementById("brandError").textContent=$msg;
            document.getElementById("brand").classList.add("is-invalid");
            document.getElementById("brandError").classList.add("paragraph-class");
        }
        function removeBrandError($msg)
        {
            document.getElementById("brandError").textContent="";
            document.getElementById("brand").classList.remove("is-invalid");
            document.getElementById("brandError").classList.remove("paragraph-class");
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
        function showModelNumberError($msg,i)
        {
            document.getElementById("ModelNumberError"+i).textContent=$msg;
            document.getElementById("selectModelNumber"+i).classList.add("is-invalid");
            document.getElementById("ModelNumberError"+i).classList.add("paragraph-class");
        }
        function removeModelNumberError($msg,i)
        {
            document.getElementById("ModelNumberError"+i).textContent="";
            document.getElementById("selectModelNumber"+i).classList.remove("is-invalid");
            document.getElementById("ModelNumberError"+i).classList.remove("paragraph-class");
        }
        function showKitItemError($msg,i)
        {
            document.getElementById("KitItemError"+i).textContent=$msg;
            document.getElementById("mainItem"+i).classList.add("is-invalid");
            document.getElementById("KitItemError"+i).classList.add("paragraph-class");
        }
        function removeKitItemError($msg,i)
        {
            $("#KitItemError"+i).text(" ");
            $("mainItem"+i).removeClass("is-invalid");
            $("KitItemError"+i).removeClass("paragraph-class");
        }
        function showKitItemQuantityError($msg,i)
        {

            document.getElementById("KitItemQuantityError"+i).textContent=$msg;
            document.getElementById("mainQuantity"+i).classList.add("is-invalid");
            document.getElementById("KitItemQuantityError"+i).classList.add("paragraph-class");
        }
        function removeKitItemQuantityError($msg,i)
        {

            document.getElementById("KitItemQuantityError"+i).textContent=$msg;
            document.getElementById("mainQuantity"+i).classList.remove("is-invalid");
            document.getElementById("KitItemQuantityError"+i).classList.remove("paragraph-class");
        }
        // function showSPBrandError($msg)
        // {
            // document.getElementById("mobrandError").textContent=$msg;
            // document.getElementById("selectBrandMo1").classList.add("is-invalid");
            // document.getElementById("mobrandError").classList.add("paragraph-class");
        // }
        // function removeSPBrandError($msg)
        // {
            // document.getElementById("mobrandError").textContent="";
            // document.getElementById("selectBrandMo1").classList.remove("is-invalid");
            // document.getElementById("mobrandError").classList.remove("paragraph-class");
        // }
        // function showkitSupplierDropdown1Error($msg)
        // {
        //     document.getElementById("kitSupplierDropdown1Error").textContent=$msg;
        //     document.getElementById("kitSupplierDropdown1").classList.add("is-invalid");
        //     document.getElementById("kitSupplierDropdown1Error").classList.add("paragraph-class");
        // }
        // function removekitSupplierDropdown1Error($msg)
        // {
        //     document.getElementById("kitSupplierDropdown1Error").textContent="";
        //     document.getElementById("kitSupplierDropdown1").classList.remove("is-invalid");
        //     document.getElementById("kitSupplierDropdown1Error").classList.remove("paragraph-class");
        // }
        function showkitSupplier1Item1Error($msg)
        {
            // document.getElementById("kitSupplier1Item1Error").textContent=$msg;
            // document.getElementById("kitSupplier1Item1").classList.add("is-invalid");
            // document.getElementById("kitSupplier1Item1Error").classList.add("paragraph-class");
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
        // function showSupplier1Kit1UnitPriceAEDError($msg)
        // {
        //     document.getElementById("Supplier1Kit1UnitPriceAEDError").textContent=$msg;
        //     document.getElementById("Supplier1Kit1UnitPriceAED").classList.add("is-invalid");
        //     document.getElementById("Supplier1Kit1UnitPriceAEDError").classList.add("paragraph-class");
        // }
        // function removeSupplier1Kit1UnitPriceAEDError($msg)
        // {
        //     document.getElementById("Supplier1Kit1UnitPriceAEDError").textContent="";
        //     document.getElementById("Supplier1Kit1UnitPriceAED").classList.remove("is-invalid");
        //     document.getElementById("Supplier1Kit1UnitPriceAEDError").classList.remove("paragraph-class");
        // }
        // function showSupplier1Kit1TotalPriceAEDError($msg)
        // {
        //     document.getElementById("Supplier1Kit1TotalPriceAEDError").textContent=$msg;
        //     document.getElementById("Supplier1Kit1TotalPriceAED").classList.add("is-invalid");
        //     document.getElementById("Supplier1Kit1TotalPriceAEDError").classList.add("paragraph-class");
        // }
        // function removeSupplier1Kit1TotalPriceAEDError($msg)
        // {
        //     document.getElementById("Supplier1Kit1TotalPriceAEDError").textContent="";
        //     document.getElementById("Supplier1Kit1TotalPriceAED").classList.remove("is-invalid");
        //     document.getElementById("Supplier1Kit1TotalPriceAEDError").classList.remove("paragraph-class");
        // }
        // function showSupplier1Kit1UnitPriceUSDError($msg)
        // {
        //     document.getElementById("Supplier1Kit1UnitPriceUSDError").textContent=$msg;
        //     document.getElementById("Supplier1Kit1UnitPriceUSD").classList.add("is-invalid");
        //     document.getElementById("Supplier1Kit1UnitPriceUSDError").classList.add("paragraph-class");
        // }
        // function removeSupplier1Kit1UnitPriceUSDError($msg)
        // {
        //     document.getElementById("Supplier1Kit1UnitPriceUSDError").textContent="";
        //     document.getElementById("Supplier1Kit1UnitPriceUSD").classList.remove("is-invalid");
        //     document.getElementById("Supplier1Kit1UnitPriceUSDError").classList.remove("paragraph-class");
        // }
        // function showSupplier1Kit1TotalPriceUSDError($msg)
        // {
        //     document.getElementById("Supplier1Kit1TotalPriceUSDError").textContent=$msg;
        //     document.getElementById("Supplier1Kit1TotalPriceUSD").classList.add("is-invalid");
        //     document.getElementById("Supplier1Kit1TotalPriceUSDError").classList.add("paragraph-class");
        // }
        // function removeSupplier1Kit1TotalPriceUSDError($msg)
        // {
        //     document.getElementById("Supplier1Kit1TotalPriceUSDError").textContent="";
        //     document.getElementById("Supplier1Kit1TotalPriceUSD").classList.remove("is-invalid");
        //     document.getElementById("Supplier1Kit1TotalPriceUSDError").classList.remove("paragraph-class");
        // }
        // function showSupplierError($msg)
        // {
        //     document.getElementById("supplierError").textContent=$msg;
        //     document.getElementById("itemArr1").classList.add("is-invalid");
        //     document.getElementById("supplierError").classList.add("paragraph-class");
        // }
        // function removeSupplierError($msg)
        // {
        //     document.getElementById("supplierError").textContent="";
        //     document.getElementById("itemArr1").classList.remove("is-invalid");
        //     document.getElementById("supplierError").classList.remove("paragraph-class");
        // }
        // function showPurchasePriceAEDError($msg)
        // {
        //     document.getElementById("purchasePriceAEDError").textContent=$msg;
        //     document.getElementById("addon_purchase_price_1").classList.add("is-invalid");
        //     document.getElementById("purchasePriceAEDError").classList.add("paragraph-class");
        // }
        // function removePurchasePriceAEDError($msg)
        // {
        //     document.getElementById("purchasePriceAEDError").textContent="";
        //     document.getElementById("addon_purchase_price_1").classList.remove("is-invalid");
        //     document.getElementById("purchasePriceAEDError").classList.remove("paragraph-class");
        // }
        // function showPurchasePriceUSDError($msg)
        // {
        //     document.getElementById("purchasePriceUSDError").textContent=$msg;
        //     document.getElementById("addon_purchase_price_in_usd_1").classList.add("is-invalid");
        //     document.getElementById("purchasePriceUSDError").classList.add("paragraph-class");
        // }
        // function removePurchasePriceUSDError($msg)
        // {
        //     document.getElementById("purchasePriceUSDError").textContent="";
        //     document.getElementById("addon_purchase_price_in_usd_1").classList.remove("is-invalid");
        //     document.getElementById("purchasePriceUSDError").classList.remove("paragraph-class");
        // }
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
            document.getElementById("part_number").classList.add("is-invalid");
            document.getElementById("partNumberError").classList.add("paragraph-class");
        }
        function removePartNumberError($msg)
        {
            document.getElementById("partNumberError").textContent="";
            document.getElementById("part_number").classList.remove("is-invalid");
            document.getElementById("partNumberError").classList.remove("paragraph-class");
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
        // function showFixingChargeAmountError($msg)
        // {
        //     document.getElementById("fixingChargeAmountError1").textContent=$msg;
        //     document.getElementById("fixing_charge_amount").classList.add("is-invalid");
        //     document.getElementById("fixingChargeAmountError1").classList.add("paragraph-class");
        // }
        // function removeFixingChargeAmountError($msg)
        // {
        //     document.getElementById("fixingChargeAmountError1").textContent="";
        //     document.getElementById("fixing_charge_amount").classList.remove("is-invalid");
        //     document.getElementById("fixingChargeAmountError1").classList.remove("paragraph-class");
        // }

        function showNewAddonYearError($msg)
        {
            document.getElementById("newAddonYearError").textContent=$msg;
            document.getElementById("kit_year").classList.add("is-invalid");
            document.getElementById("newAddonYearError").classList.add("paragraph-class");
        }
        function removeNewAddonYearError()
        {
            document.getElementById("newAddonYearError").textContent="";
            document.getElementById("kit_year").classList.remove("is-invalid");
            document.getElementById("newAddonYearError").classList.remove("paragraph-class");
        }
        function showNewAddonKMError($msg)
        {
            document.getElementById("newAddonKMError").textContent=$msg;
            document.getElementById("kit_km").classList.add("is-invalid");
            document.getElementById("newAddonKMError").classList.add("paragraph-class");
        }
        function removeNewAddonKMError()
        {
            document.getElementById("newAddonKMError").textContent="";
            document.getElementById("kit_km").classList.remove("is-invalid");
            document.getElementById("newAddonKMError").classList.remove("paragraph-class");
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
        {{--function getAddonCodeAndDropdown()--}}
        {{--{--}}
        {{--    var e = document.getElementById("addon_type");--}}
        {{--    var value = e.value;--}}
        {{--    currentAddonType = value;--}}
        {{--    if(currentAddonType != '')--}}
        {{--    {--}}
        {{--        $("#selectBrandMo1").removeAttr('disabled');--}}
        {{--        $("#brand").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");--}}
        {{--        $("#brand").select2({--}}
        {{--            maximumSelectionLength: 1,--}}
        {{--        });--}}
        {{--        document.getElementById("addon_type_required").textContent="";--}}
        {{--        // $msg = "";--}}
        {{--        // removeAddonTypeError($msg);--}}
        {{--        if(currentAddonType == 'SP' && ifModelLineExist != '')--}}
        {{--        {--}}
        {{--        }--}}
        {{--        else--}}
        {{--        {--}}
        {{--        }--}}
        {{--        if(value == 'SP' )--}}
        {{--        {--}}
        {{--            $("#brandModelLineId").hide();--}}
        {{--            $("#brandModelNumberId").show();--}}
        {{--            document.getElementById("brandModelNumberId").hidden = false;--}}
        {{--            $("#showaddtrim").hide();--}}
        {{--            $("#showaddtrimDis").show();--}}
        {{--            // let showPartNumber = document.getElementById('partNumberDiv');--}}
        {{--            // showPartNumber.hidden = false--}}
        {{--            // let showPartNumberBr = document.getElementById('partNumberDivBr');--}}
        {{--            // showPartNumberBr.hidden = false--}}
        {{--        }--}}
        {{--        else--}}
        {{--        {--}}
        {{--            // let showPartNumber = document.getElementById('partNumberDiv');--}}
        {{--            // showPartNumber.hidden = true--}}
        {{--            // let showPartNumberBr = document.getElementById('partNumberDivBr');--}}
        {{--            // showPartNumberBr.hidden = true--}}
        {{--            $("#brandModelLineId").show();--}}
        {{--            $("#brandModelNumberId").hide();--}}
        {{--            $("#showaddtrim").show();--}}
        {{--            $("#showaddtrimDis").hide();--}}
        {{--        }--}}
        {{--        $("#purchase_price").val('');--}}
        {{--        if(value == 'K')--}}
        {{--        {--}}
        {{--            $('#kitSupplier').show();--}}
        {{--            $('#branModaDiv').show();--}}
        {{--            hidenotKitSupplier();--}}
        {{--            showkitSupplier();--}}
        {{--            // setLeastPurchasePriceAED();--}}
        {{--            // addItemForSupplier();--}}
        {{--        }--}}
        {{--        else--}}
        {{--        {--}}
        {{--            $('#kitSupplier').show();--}}
        {{--            $('#branModaDiv').show();--}}
        {{--            hidekitSupplier();--}}
        {{--            shownotKitSupplier();--}}
        {{--            // setLeastAEDPrice();--}}
        {{--        }--}}
        {{--        $.ajax--}}
        {{--        ({--}}
        {{--            url:"{{url('getAddonCodeAndDropdown')}}",--}}
        {{--            type: "POST",--}}
        {{--            data:--}}
        {{--            {--}}
        {{--                addon_type: value,--}}
        {{--                _token: '{{csrf_token()}}'--}}
        {{--            },--}}
        {{--            dataType : 'json',--}}
        {{--            success: function(data)--}}
        {{--            {--}}
        {{--                // console.log(data.suppliers);--}}
        {{--                $('#addon_type').val(currentAddonType);--}}
        {{--                $('#addon_code').val(data.newAddonCode);--}}
        {{--                $("#addon_id").html("");--}}
        {{--                myarray = data.addonMasters;--}}
        {{--                var size= myarray.length;--}}
        {{--                if(size >= 1)--}}
        {{--                {--}}
        {{--                    let AddonDropdownData   = [];--}}
        {{--                    $.each(data.addonMasters,function(key,value)--}}
        {{--                    {--}}
        {{--                        AddonDropdownData.push--}}
        {{--                        ({--}}
        {{--                            id: value.id,--}}
        {{--                            text: value.name--}}
        {{--                        });--}}
        {{--                    });--}}
        {{--                    $('#addon_id').select2--}}
        {{--                    ({--}}
        {{--                        placeholder: 'Choose Addon ....     Or     Type Here To Search....',--}}
        {{--                        allowClear: true,--}}
        {{--                        data: AddonDropdownData,--}}
        {{--                        maximumSelectionLength: 1,--}}
        {{--                    });--}}
        {{--                }--}}

        {{--                $("#suppliers1").html("");--}}
        {{--                myarray1 = data.suppliers;--}}
        {{--                var size1= myarray1.length;--}}
        {{--                if(size1 >= 1)--}}
        {{--                {--}}
        {{--                    let SupplierDropdownData   = [];--}}
        {{--                    $.each(data.suppliers,function(key,value)--}}
        {{--                    {--}}
        {{--                        SupplierDropdownData.push--}}
        {{--                        ({--}}
        {{--                            id: value.id,--}}
        {{--                            text: value.supplier--}}
        {{--                        });--}}
        {{--                    });--}}
        {{--                    $('#suppliers1').select2--}}
        {{--                    ({--}}
        {{--                        placeholder: 'Choose Supplier ....     Or     Type Here To Search....',--}}
        {{--                        allowClear: true,--}}
        {{--                        data: SupplierDropdownData,--}}
        {{--                        // maximumSelectionLength: 1,--}}
        {{--                    });--}}
        {{--                }--}}

        {{--            }--}}
        {{--        });--}}
        {{--    }--}}
        {{--    else--}}
        {{--    {--}}
        {{--        $('#kitSupplier').hide();--}}
        {{--        $('#branModaDiv').hide();--}}
        {{--        $('#addon_code').val('');--}}
        {{--        $msg = "Addon Type is required";--}}
        {{--        showAddonTypeError($msg);--}}
        {{--    }--}}
        {{--}--}}
        $('#createAddonId').on('click', function()
        {
            // create new addon and list new addon in addon list
            var year = $('#kit_year').val();
            var kiloMeter = $('#kit_km').val();

            if(year == '' || kiloMeter == '')
            {
                if(year == '') {
                    document.getElementById("newAddonYearError").textContent='Addon Year is Required';
                }
                if(kiloMeter == '') {
                    document.getElementById("newAddonKMError").textContent='Addon KiloMeter is Required';
                }
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
                        kit_year: year,
                        kit_km:kiloMeter,
                        addon_type: currentAddonType,
                        _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result)
                    {
                        if(result.error) {
                            $msg = "Addon with this year and kilometer is already Existing.";
                            showNewAddonYearError($msg);
                        }else{
                            $('.overlay').hide();
                            $('.modal').removeClass('modalshow');
                            $('.modal').addClass('modalhide');
                            $('#addon_id').append("<option value='" + result.id + "'>" + result.name + "</option>");
                            $('#addon_id').val(result.id);
                            var selectedValues = new Array();
                            resetSelectedSuppliers(selectedValues);
                            $('#addnewAddonButton').hide();
                            $('#kit_year').val("");
                            $('#kit_km').val("");

                            document.getElementById("newAddonError").textContent='';
                            $msg = "";
                            removeNewAddonKMError($msg);
                            removeNewAddonYearError($msg);
                        }
                    }
                });
            }
        });
        function setPartNumber(partNum)
        {
            $('#part_number').val(partNum.value);
            var partNumberInput = $('#part_number').val();
            if(partNumberInput == '')
            {
                $msg = "Part Number is required";
                showPartNumberError($msg);
                formInputError = true;
            }
            else
            {
                $msg = "";
                removePartNumberError($msg);
            }
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
        // function changeCurrency(i)
        // {
        //     var e = document.getElementById("currency_"+i);
        //     var value = e.value;
        //     if(value == 'USD')
        //     {
        //         let chooseCurrency = document.getElementById('div_price_in_aedOne_'+i);
        //         chooseCurrency.hidden = true
        //         let currencyUSD = document.getElementById('div_price_in_usd_'+i);
        //         currencyUSD.hidden = false
        //         let currencyAED = document.getElementById('div_price_in_aed_'+i);
        //         currencyAED.hidden = false
        //     }
        //     else
        //     {
        //         let chooseCurrency = document.getElementById('div_price_in_aedOne_'+i);
        //         chooseCurrency.hidden = false
        //         let currencyUSD = document.getElementById('div_price_in_usd_'+i);
        //         currencyUSD.hidden = true
        //         let currencyAED = document.getElementById('div_price_in_aed_'+i);
        //         currencyAED.hidden = true
        //     }
        // }
        // function calculateAED(i)
        // {
        //     var usd = $("#addon_purchase_price_in_usd_"+i).val();
        //     var aed = usd * 3.6725;
        //     var aed = aed.toFixed(4);
        //     aed = parseFloat(aed);
        //     if(aed == 0)
        //     {
        //         document.getElementById('addon_purchase_price_'+i).value = "";
        //         setLeastAEDPrice();
        //     }
        //     else
        //     {
        //         document.getElementById('addon_purchase_price_'+i).value = aed;
        //         setLeastAEDPrice();
        //     }
        // }
        // function calculateUSD(i)
        // {
        //     var aed = $("#addon_purchase_price_"+i).val();
        //     var usd = aed / 3.6725;
        //     var usd = usd.toFixed(4);
        //     if(usd == 0)
        //     {
        //         document.getElementById('addon_purchase_price_in_usd_'+i).value = "";
        //     }
        //     else
        //     {
        //         document.getElementById('addon_purchase_price_in_usd_'+i).value = usd;
        //     }
        //     setLeastAEDPrice();
        // }
        // function setLeastAEDPrice()
        // {
        //     const values = Array.from(document.querySelectorAll('.notKitSupplierPurchasePrice')).map(input => input.value);
        //     // alert(values);
        //     if(values != '')
        //     {
        //         var arrayOfNumbers = [];
        //         values.forEach(v => {
        //             if(v != '')
        //             {
        //                 arrayOfNumbers .push(v);
        //             }
        //         });
        //         var size= arrayOfNumbers.length;
        //         if(size >= 1)
        //         {
        //             var arrayOfNumbers = arrayOfNumbers.map(Number);
        //             const minOfPrice = Math.min(...arrayOfNumbers);
        //             $("#purchase_price").val(minOfPrice);
        //             // disableDropdown();
        //         }
        //         else
        //         {
        //             $("#purchase_price").val('');
        //             // enableDropdown();
        //         }
        //     }
        // }
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
        // function inputNumberAbs(currentPriceInput)
        // {
        //
        //     var id = currentPriceInput.id
        //     var input = document.getElementById(id);
        //     var val = input.value;
        //     val = val.replace(/^0+|[^\d.]/g, '');
        //     if(val.split('.').length>2)
        //     {
        //         val =val.replace(/\.+$/,"");
        //     }
        //     input.value = val;
        //     if(currentPriceInput.id == 'fixing_charge_amount' && sub == '2')
        //     {
        //         var value = currentPriceInput.value;
        //         if(value == '')
        //         {
        //
        //             if(value.legth != 0)
        //             {
        //                 $msg = "Fixing Charge Amount is required";
        //                 showFixingChargeAmountError($msg);
        //             }
        //         }
        //         else
        //         {
        //             removeFixingChargeAmountError();
        //         }
        //     }
        // }
        // function showAlert()
        // {
        //     var confirm = alertify.confirm('You are not able to edit this field while any Supplier is in selection',function (e) {
        //            }).set({title:"Remove Brands and Suppliers"})
        // }
</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#brand").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
        $("#brand").select2({
            maximumSelectionLength: 1,
        });
        $("#selectModelNumber1").attr("data-placeholder","Choose Model Number....     Or     Type Here To Search....");
        $("#selectModelNumber1").select2({

        });

        var index = 1;

        $(document.body).on('select2:select', ".model-lines", function (e) {
            var value = $(this).val();
            var index = $(this).attr('data-index');
            // optionDisable(index, value);
            hideOption(index,value);

        });
        $(document.body).on('select2:select', ".model-numbers", function (e) {
            e.preventDefault();
            var type = 'MODEL_NUMBER';
            var countRow = $(".apendNewaMainItemHere").find(".kitMainItemRowForSupplier").length;
            var KitItems = [];
            for(let i=1; i<=countRow; i++)
            {
                var kitItem = $('#mainItem'+i).val();
                if(kitItem != '') {
                    KitItems.push(kitItem);
                }
            }
            // console.log(KitItems);
            if(KitItems.length > 0) {
                if(confirm("Your Selected Kit Items will be Cleared While changing model Number")) {
                    getItemsDropdown(type);
                }
            }else{
                getItemsDropdown(type);
            }
        });
        $(document.body).on('select2:unselect', ".model-numbers", function (e) {
            e.preventDefault();
            var type = 'MODEL_NUMBER';

            var countRow = $(".apendNewaMainItemHere").find(".kitMainItemRowForSupplier").length;
            var KitItems = [];
            for(let i=1; i<=countRow; i++)
            {
                var kitItem = $('#mainItem'+i).val();
                if(kitItem != '') {
                    KitItems.push(kitItem);
                }
            }
            if(KitItems.length > 0) {
                if(confirm("Your Selected Kit Items will be Cleared While changing model Number")) {
                    getItemsDropdown(type);
                }
            }else{
                getItemsDropdown(type);
            }
        });
        $(document.body).on('select2:unselect', ".model-lines", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            // optionEnable(currentId,data);
            $('#selectModelNumber'+index).empty();
            appendOption(index,data)
        });

        function hideOption(index,value) {
            var indexValue =  $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
            for (var i = 1; i <= indexValue; i++) {
                if (i != index) {
                    var currentId = 'selectModelLine' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        function appendOption(index,data) {
            var indexValue =  $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#selectModelLine'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }
        function addOption(id,text) {
            var indexValue =  $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
            for(var i=1;i<=indexValue;i++) {
                $('#selectModelLine'+i).append($('<option>', {value: id, text :text}))
            }
        }
        // Remove Brand and Model Lines
        //===== delete the form fieed row
        $(document.body).on('click', ".removeButtonbrandModelLineDiscription", function (e)
            {
                var indexNumber = $(this).attr('data-index');
                var type = 'REMOVE';
                var modelDescription = $('#selectModelNumber'+indexNumber).val();
                if(modelDescription != '') {
                    var countRow = $(".apendNewaMainItemHere").find(".kitMainItemRowForSupplier").length;
                    var KitItems = [];
                    for(let i=1; i<=countRow; i++)
                    {
                        var kitItem = $('#mainItem'+i).val();
                        if(kitItem != '') {
                            KitItems.push(kitItem);
                        }
                    }
                    if(KitItems.length > 0) {
                        if(confirm("Your Selected Kit Items will be Cleared While changing model Number")) {
                            removeModelLineItems(indexNumber);
                            getItemsDropdown(type);
                        }
                    }else{
                        removeModelLineItems(indexNumber);
                        getItemsDropdown(type);
                    }
                }else{
                    removeModelLineItems(indexNumber);
                }

            })
        $("#add").on("click", function ()
        {
            // $('#allbrands').prop('disabled',true);
            var index = $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length + 1;
            $('#index').val(index);
            var selectedAddonModelLines = [];
            for(let i=1; i<index; i++)
            {
                var eachSelectedModelLines = $('#selectModelLine'+i).val();
                if(eachSelectedModelLines) {
                    selectedAddonModelLines.push(eachSelectedModelLines);
                }
            }
            var brand = $('#brand').val();
            $.ajax({
                url: '/addons/brandModels/'+brand,
                type: "GET",
                data:
                    {
                        filteredArray: selectedAddonModelLines,
                    },
                dataType: "json",
                success: function(data) {
                    myarray = data;
                    var size = myarray.length;
                    if (size >= 1) {
                        $(".brandModelLineDiscription").append(`
                            <div class="row brandModelLineDiscriptionApendHere dynamic-rows" id="row-${index}">
                                <div class="row">
                                     <div class="col-xxl-1 col-lg-1 col-md-12">
                                     </div>
                                    <div class="col-xxl-4 col-lg-6 col-md-12 model-line-div" id="showDivdrop${index}" >
                                        <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                                        <select class="compare-tag1 model-lines" name="brandModel[${index}][model_line_id]" data-index="${index}"
                                        id="selectModelLine${index}"  multiple="true" style="width: 100%;" onchange=selectModelLineDescipt(${index}) >
                                        </select>
                                        <span id="ModelLineError${index}" class="ModelLineError invalid-feedback"></span>
                                    </div>
                                     <div class="col-xxl-4 col-lg-6 col-md-12 model-number-div" id="showDivModelNumber${index}"  >
                                        <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                                        <select class="compare-tag1 model-numbers" name="brandModel[${index}][model_number][]" data-index="${index}"
                                        id="selectModelNumber${index}" onchange="showValidationErrors(${index})"  multiple="true" style="width: 100%;"  >
                                        </select>
                                        <span id="ModelNumberError${index}" class="ModelNumberError invalid-feedback"></span>

                                    </div>
                                    <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                        <a class="btn_round removeButtonbrandModelLineDiscription" data-index="${index}" id="removeButton${index}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `);

                        let modelLinesData   = [];
                        $.each(data,function(key,value)
                        {
                            modelLinesData.push
                            ({
                                id: value.id,
                                text: value.model_line
                            });
                        });

                        $("#selectModelLine"+index).select2
                        ({
                            placeholder: 'Choose Model Line....     Or     Type Here To Search....',
                            allowClear: true,
                            data: modelLinesData,
                            maximumSelectionLength: 1,
                        });

                        $('#selectModelNumber'+index).select2
                        ({
                            placeholder:"Choose Model Number....     Or     Type Here To Search....",
                            allowClear: true,
                        });
                    }
                }
            });
        });
        function removeModelLineItems(indexNumber) {

            var countRow = 0;
            var countRow = $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
            if(countRow > 1)
            {
                // var indexNumber = $(this).attr('data-index');
                // if(indexNumber == 1) {
                //     $('<option value="allbrands"> ALL BRANDS </option>').prependTo('#selectBrand2');
                // }
                // $('.removeButtonbrandModelLineDiscription').closest('#row-'+indexNumber).find("option:selected").each(function() {
                    var id = $('#selectModelLine'+indexNumber).val();
                    var text = $('#selectModelLine'+indexNumber).text();;
                    // alert(id);
                    // alert(text);
                if(id != '') {
                    addOption(id,text)
                }

                // });
                $('.removeButtonbrandModelLineDiscription').closest('#row-'+indexNumber).remove();
                // model-lines
                $('.brandModelLineDiscriptionApendHere').each(function(i) {
                    console.log("inside index change");
                    console.log(i);
                    var index = +i + +1;
                    $(this).attr('id','row-'+index);
                    $(this).find('.brands').attr('onchange', 'selectBrand(this.id,'+index+')');
                    $(this).find('.brands').attr('data-index',index);
                    $(this).find('.model-line-div').attr('id','showDivdrop'+index);
                    $(this).find('.model-lines').attr('name','brandModel['+ index +'][model_line_id]');
                    $(this).find('.model-lines').attr('id','selectModelLine'+index);
                    $(this).find('.model-lines').attr('data-index',index);
                    $(this).find('.model-lines').attr('onchange','selectModelLineDescipt('+index+')');
                    $(this).find('.ModelLineError').attr('id','ModelLineError'+index);

                    $(this).find('.model-numbers').attr('name','brandModel['+ index +'][model_number][]');
                    $(this).find('.model-numbers').attr('id','selectModelNumber'+index);
                    $(this).find('.model-numbers').attr('data-index',index);
                    $(this).find('.model-numbers').attr('onchange','showValidationErrors('+index+')');
                    $(this).find('.ModelNumberError').attr('id','ModelNumberError'+index);
                    $(this).find('.model-number-div').attr('id','showDivModelNumber'+index);

                    $(this).find('.removeButtonbrandModelLineDiscription').attr('data-index',index);
                    $(this).find('.removeButtonbrandModelLineDiscription').attr('id','removeButton'+index);

                    $("#selectModelLine"+index).attr("data-placeholder","Choose Model Line....     Or     Type Here To Search....");
                    $("#selectModelLine"+index).select2({
                        maximumSelectionLength:1
                    });
                    $('#selectModelNumber'+index).select2
                    ({
                        placeholder:"Choose Model Number....     Or     Type Here To Search....",
                        allowClear: true,
                    });
                })
                // enableDropdown();
            }
            else
            {
                var confirm = alertify.confirm('You are not able to remove this row, Atleast one Model Line and model Number is Required',function (e) {
                }).set({title:"Can't Remove Brand And Model Lines"})
            }
        }

    });

    function selectBrand(id,row)
    {
        var index = $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
        var value =$('#'+id).val();
        if(value != '')
        {
            if(index == row) {
                showRelatedModal(value,row);

            }else {
                for(var i = 1;i<=index;i++) {
                    showRelatedModal(value,i);
                }
            }

            $msg = "";
            removeBrandError($msg);
        }
        else
        {
            $msg = "Brand is Required";
            showBrandError($msg);
            removeRelatedModal(row);
        }
    }
    function selectModelLineDescipt(index)
    {
        var modelLine =$('#selectModelLine'+index).val();
        if(modelLine != '')
        {
            $msg = "";
            removeModelLineError($msg,index);
            showModelNumberDropdown(index);
        }
        else
        {
            $msg = "Model Line is Required";
            showModelLineError($msg,index);
        }
    }
    function showModelNumberDropdown(index)
    {
        var e = document.getElementById("addon_type");
        var value = e.value;
        var selectedModelLine = $("#selectModelLine"+index).val();
        if(selectedModelLine != ''){
            // $('#showModelNumDel'+id).attr('hidden',false);
            // $('#showModelNumberdrop'+id+'Des'+row).attr('hidden',false);
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
                success:function(data)
                {
                    let ModelLineModelDescription   = [];
                    $.each(data.model_description,function(key,value)
                    {
                        ModelLineModelDescription.push
                        ({
                            id: value.id,
                            text: value.model_description
                        });
                    });
                    $("#selectModelNumber"+index).html("");
                    $("#selectModelNumber"+index).select2
                    ({
                        placeholder: 'Choose Model Number....     Or     Type Here To Search....',
                        allowClear: true,
                        data: ModelLineModelDescription
                    });
                }
            });

        }
    }
    function showValidationErrors(index) {
        var modelNumber =$('#selectModelNumber'+index).val();
        if(modelNumber != '')
        {
            $msg = "";
            removeModelNumberError($msg,index);
        }
        else
        {
            $msg = "Model Number is Required";
            showModelNumberError($msg,index);
        }
    }
    function showRelatedModal(value,row)
    {
        let showDivdrop = document.getElementById('showDivdrop'+row);
        showDivdrop.hidden = false;
        let showDivModelNumber = document.getElementById('showDivModelNumber'+row);
        showDivModelNumber.hidden = false;
        let removeButton = document.getElementById('removeButton'+row);
        removeButton.hidden = false;

        let showaddtrim = document.getElementById('showaddtrim');
        showaddtrim.hidden = false
        $.ajax
        ({
            url: '/addons/brandModels/'+value,
            type: "GET",
            dataType: "json",
            success:function(data)
            {
                $("#selectModelLine"+row).html("");
                let BrandModelLine   = [];

                $.each(data,function(key,value)
                {
                    BrandModelLine.push
                    ({
                        id: value.id,
                        text: value.model_line
                    });
                });
                $('#selectModelLine'+row).select2
                ({
                    placeholder: 'Choose Model Line....     Or     Type Here To Search....',
                    allowClear: true,
                    data: BrandModelLine,
                    maximumSelectionLength: 1,
                });
                $('#selectModelNumber'+row).select2
                ({
                    placeholder: 'Choose Model Number....     Or     Type Here To Search....',
                    allowClear: true,
                });
            }
        });
    }
    function removeRelatedModal()
    {

        // let showDivdrop = document.getElementById('showDivdrop'+row);
        // showDivdrop.hidden = true
        // let showDivModelNumber = document.getElementById('showDivModelNumber'+row);
        // showDivModelNumber.hidden = true
        // let removeButton = document.getElementById('removeButton'+row);
        // removeButton.hidden = true
        var index = $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
        for(var i=1;i<=index;i++) {
            $('#selectModelLine'+i).empty();
            $('#selectModelNumber'+i).empty();

        }
        let showaddtrim = document.getElementById('showaddtrim');
        showaddtrim.hidden = true
    }

    // function hideModelNumberDropdown(id,row)
    // {
    //     let showPartNumber = document.getElementById('showModelNumberdrop'+row);
    //     showPartNumber.hidden = true
    // }
    function getItemsDropdown(type) {

        var indexValue =  $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
        var countRow = $(".apendNewaMainItemHere").find(".kitMainItemRowForSupplier").length;

        var selectedAddonModelNumbers = [];
        for(let i=1; i<=indexValue; i++)
        {
            var eachSelectedModelNumbers = $('#selectModelNumber'+i).val();
            $.each(eachSelectedModelNumbers, function( ind, value )
            {
                selectedAddonModelNumbers.push(value);
            });
        }

        var selectedItems = [];
        for(let j=1; j<= countRow; j++)
        {
            var item = $('#mainItem'+j).val();
            if(item != ' ') {
                selectedItems.push(item);
            }
        }

        $.ajax({
            url: "{{url('getCommonKitItems')}}",
            type: "GET",
            data:
                {
                    selectedAddonModelNumbers: selectedAddonModelNumbers,
                    count:selectedAddonModelNumbers.length,
                    type:type,
                    selectedItems:selectedItems
                },
            dataType: "json",
            success: function (data) {
                if(type == 'MODEL_NUMBER' || type == 'REMOVE') {
                    $('.MainItemsClass').empty();
                }
                let addonDropdownData   = [];
                $.each(data,function(key,value)
                {
                    addonDropdownData.push
                    ({

                        id: value.id,
                        text: value.addon_code +' ('+value.addon_name.name +')'
                    });
                });

                for(let i=1; i<= countRow; i++)
                {
                    $('#mainItem'+i).select2
                    ({
                        placeholder:"Choose Items....     Or     Type Here To Search....",
                        allowClear: true,
                        data: addonDropdownData,
                        maximumSelectionLength: 1,
                    });
                }
            }
        })
    }
    $(document).ready(function ()
    {
        $("#mainItem1").attr("data-placeholder","Choose Items....     Or     Type Here To Search....");
        $("#mainItem1").select2
        ({
            maximumSelectionLength: 1,
        });
         /////////// keit item add section //////////////
        $(document.body).on('select2:select', ".MainItemsClass", function (e) {
            var index = $(this).attr('data-index');
            var value = e.params.data.id;
            MainKitItemHideOption(index,value);
            // disableDropdown();
        });
        $(document.body).on('select2:unselect', ".MainItemsClass", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            MainKitItemAppendOption(index,data);
            // enableDropdown();
        });
    });
        function MainKitItemHideOption(index,value) {
            var indexValue = $(".apendNewaMainItemHere").find(".kitMainItemRowForSupplier").length;
            for (var i = 1; i <= indexValue; i++) {
                if (i != index) {
                    var currentId = 'mainItem' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        function MainKitItemAppendOption(index,data) {
            var indexValue = $(".apendNewaMainItemHere").find(".kitMainItemRowForSupplier").length;
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#mainItem'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }
    $(document.body).on('click', ".removeMainItem", function (e)
    {
        var countRow = 0;
        var countRow = $(".apendNewaMainItemHere").find(".kitMainItemRowForSupplier").length;
        if(countRow > 1)
        {
            var indexNumber = $(this).attr('data-index');
            $(this).closest('#item-'+indexNumber).find("option:selected").each(function()
            {
                var id = (this.value);
                var text = (this.text);
                MainKitItemAddOption(id,text)
            });
            $(this).closest('#item-'+indexNumber).remove();
            $('.kitMainItemRowForSupplier').each(function(i)
            {
                var index = +i + +1;
                $(this).attr('id','item-'+index);
                $(this).find('.MainItemsClass').attr('data-index', index);
                $(this).find('.MainItemsClass').attr('id','mainItem'+index);
                $(this).find('.MainItemsClass').attr('name','mainItem['+index+'][item]');
                $(this).find('.quantityMainItem').attr('name', 'mainItem['+index+'][quantity]');
                $(this).find('.quantityMainItem').attr('id', 'mainQuantity'+index);
                $(this).find('.removeMainItem').attr('data-index', index);
                $('#mainItem'+index).select2
                ({
                    placeholder:"Choose Items....     Or     Type Here To Search....",
                    allowClear: true,
                    maximumSelectionLength: 1,
                });
            });
        }
        else
        {
            var confirm = alertify.confirm('You are not able to remove this row, Atleast one Kit Item and Quantity Required',function (e) {
            }).set({title:"Can't Remove Kit Item and Quantity"})
        }
    })
    function MainKitItemAddOption(id,text)
    {
        var indexValue = $('#MainKitItemIndex').val();
        for(var i=1;i<=indexValue;i++)
        {
            $('#mainItem'+i).append($('<option>', {value: id, text :text}))
        }
    }
    function addItem()
    {
        var index = $(".apendNewaMainItemHere").find(".kitMainItemRowForSupplier").length + 1;
        $('#MainKitItemIndex').val(index);
            $(".apendNewaMainItemHere").append(`
                <div class="row kitMainItemRowForSupplier kititemdelete" id="item-${index}">
                    <div class="col-xxl-10 col-lg-6 col-md-12">
                        <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                        <select class="mainItem MainItemsClass" name="mainItem[${index}][item]" id="mainItem${index}" multiple="true"
                         style="width: 100%;" data-index="${index}" onchange="KitItemValidations(this,${index})">
                        </select>
                         <span id="KitItemError${index}" class="KitItemError invalid-feedback"></span>
                        </div>
                        <div class="col-xxl-1 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                            <label for="choices-single-default" class="form-label font-size-13 ">Quantity</label>
                            <input name="mainItem[${index}][quantity]" id="mainQuantity${index}"
                             type="number" value="1" min="1" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror quantityMainItem"
                             placeholder="Enter Quantity" autocomplete="addon_purchase_price_in_usd" autofocus
                             oninput="validity.valid||(value='1');" required >
                              <span id="kitItemQuantityError${index}" class="kitItemQuantityError invalid-feedback"></span>

                        </div>
                    <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                        <a id="removeMainItem${index}" class="btn_round removeMainItem" data-index="${index}">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
            `);
            var type = 'ADD_ITEM'
            getItemsDropdown(type);
    }
</script>
@endsection
