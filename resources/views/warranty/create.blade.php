@extends('layouts.main')
<style>
    .error
    {
        color: #FF0000;
    }
    input:focus 
    {
        border-color: #495057!important;
    }
    select:focus 
    {
        border-color: #495057!important;
    }
    .widthinput
    {
        height:32px!important;
    }
</style>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create Warranty</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <form id="createAddonForm" name="createAddonForm" method="POST" enctype="multipart/form-data" action="{{ route('warranty.store') }}">
            @csrf
            <div class="row">
                <p><span style="float:right;" class="error">* Required Field</span></p>                
                <div class="row">
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Policy Name') }}</label>
                        <select name="warranty_policies_id" id="warranty_policies_id" class="form-control" autofocus>
                            @foreach($policyNames as $policyName)
                                <option value="{{$policyName->id}}">{{$policyName->name}}</option>
                            @endforeach
                        </select>
                        <span id="supplierError" class="invalid-feedback"></span>                      
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Vehicle Category 1') }}</label>
                        <select name="vehicle_category1" id="vehicle_category1" class="form-control" autofocus>
                                <option value="non_electric">Non Electric</option>
                                <option value="electric">Electric</option>
                        </select>                        
                        <span id="supplierError" class="invalid-feedback"></span>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Vehicle Category 2') }}</label>
                        <select name="vehicle_category1" id="vehicle_category1" class="form-control" autofocus>
                            <option value="normal_and_premium">Normal And Premium</option>
                            <option value="lux_sport_exotic">Lux/Sport/Exotic</option>
                        </select>                                
                        <span id="supplierError" class="invalid-feedback"></span>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Eligibility Years') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Eligibility Years" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Years</span>
                            </div>
                        </div>
                        <span id="supplierError" class="invalid-feedback"></span>                     
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Eligibility Mileage') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Eligibility Mileage" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">KM</span>
                            </div>
                        </div>                            
                        <span id="supplierError" class="invalid-feedback"></span>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Is Open Mileage') }}</label>
                        <fieldset>
                            <div class="row some-class">
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="radioFixingCharge" name="fixing_charges_included" value="yes" id="yes" checked />
                                    <label for="yes">Yes</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="radioFixingCharge" name="fixing_charges_included" value="no" id="no" />
                                    <label for="no">No</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Extended Warranty Period') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Extended Warranty Period" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Months</span>
                            </div>
                        </div> 
                        <span id="supplierError" class="invalid-feedback"></span>                      
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Claim Limit') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Claim Limit" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">AED</span>
                            </div>
                        </div>                                   
                        <span id="supplierError" class="invalid-feedback"></span>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4" id="ExtendedWarrantyMileageDiv" hidden>
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Extended Warranty Mileage') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" onkeypress="return event.charCode >= 48" min="1" placeholder="Extended Warranty Mileage" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">KM</span>
                            </div>
                        </div>                         
                        <span id="supplierError" class="invalid-feedback"></span>                     
                    </div>
                </div>
            </div>
            </br>
            <div class="card"  id="kitSupplier" >
                <div class="card-header">
                    <center>
                        <h4 class="card-title">Purchase Prices</h4>
                    </center>
                </div>
                <div class="card-body">
                    <div class="form_field_outer">
                        <div class="row form_field_outer_row">
                            <div class="col-xxl-9 col-lg-8 col-md-8">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Brands') }}</label>                                                                 
                                <select name="brands1[]" id="brands1" multiple="true" style="width: 100%;"  class="form-control" autofocus>
                                    @foreach($brands as $brand)
                                        <option id="brand1Option{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                    @endforeach   
                                </select>                      
                                <span id="supplierError" class="invalid-feedback"></span>
                            </div>                                      
                            <div class="col-xxl-2 col-lg-3 col-md-3">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Purchase Price') }}</label>
                                <div class="input-group">
                                    <input type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Purchase Price" aria-label="measurement" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                </div>                                   
                                <span id="supplierError" class="invalid-feedback"></span>
                            </div>
                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                <button class="btn_round  removeButtonSupplierWithoutKit" disabled hidden>
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                        <a onclick="clickAdd()" id="addSupplier" style="float: right;" class="btn btn-sm btn-info addSupplierAndPriceWithoutKit">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </a> 
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-sm" id="submit" style="float:right;">Submit</button>
                </div>
    </form>
</div>
<script type="text/javascript"> 
    var oldSelectedBrands = [];
    var selectedBrands = [];
    var totalRow = 1;
    var filteredArray = [];
    $(document).ready(function ()
    {
        $("#brands1").attr("data-placeholder","Choose Brands....     Or     Type Here To Search....");
        $('#brands1').select2({
            allowClear: true,
            minimumResultsForSearch: -1,
            templateResult: hideSelected,
        });
        $("#brands1").data('originalvalues', []);
        $("#brands1").on('change', function(e) 
        {
            var that = this;
            removed = []
            $($(this).data('originalvalues')).each(function(k, v) 
            {
                if (!$(that).val()) 
                {
                    removed[removed.length] = v;
                    return false;
                }
                if ($(that).val().indexOf(v) == -1) 
                {
                    removed[removed.length] = v;
                    $.each(removed, function( ind, value ) 
                    {
                        filteredArray = selectedBrands.filter(function(e) { return e !== value })
                    });
                    $.ajax
                    ({
                        url:"{{url('getBranchForWarranty')}}",
                        type: "POST",
                        data:
                        {
                            filteredArray: filteredArray,
                            _token: '{{csrf_token()}}'
                        },
                        dataType : 'json',
                        success: function(data)
                        { 
                            myarray = data;
                            var size= myarray.length;
                            if(size >= 1)
                            {
                                let brandDropdownData   = [];
                                $.each(data,function(key,value)
                                {
                                    brandDropdownData.push
                                    ({
                                        id: value.id,
                                        text: value.brand_name
                                    });
                                });
                                for(let i=1; i<=totalRow; i++)
                                {
                                    var brandRowID = "brands"+i;
                                    var brandRowSelectedValue = [];
                                    if(brandRowID != "brands1")
                                    {
                                        var brandRowSelectedValue = $("#brands"+i).val();
                                        $('#'+brandRowID).html("");
                                        $('#'+brandRowID).select2
                                        ({
                                            placeholder: 'Select value',
                                            allowClear: true,
                                            data: brandDropdownData,
                                            maximumSelectionLength: 1,
                                        });
                                        $("#"+brandRowID).val(brandRowSelectedValue).trigger('change');
                                    }
                                }    
                            }
                        }
                    });
                    for(let i=1; i<=totalRow; i++)
                    {
                        var brandRowID = "brands"+i;
                        if(brandRowID != "brands1")
                        {

                        }
                    }
                }
            });
            if ($(this).val()) 
            {
                $(this).data('originalvalues', $(this).val());
            } 
            else 
            {
                $(this).data('originalvalues', []);
            }
            if(removed != '')
            {
                for(let i=1; i<=totalRow; i++)
                {
                    var brandRowID = "brands"+i;
                    if(brandRowID != "brands1")
                    {
                        $('#'+brandRowID+' option[value='+removed+']').detach();
                    }
                }
                selectedBrands = $(this).val();
            }
            else
            {
                selectedBrands = $(this).val();
                var diff = $(selectedBrands).not(oldSelectedBrands).get();
                for(let i=1; i<=totalRow; i++)
                {
                    var brandRowID = "brands"+i;
                    if(brandRowID != "brands1")
                    {
                       
                        $('#'+brandRowID+' option[value='+diff+']').detach();
                    }
                }
                oldSelectedBrands = selectedBrands;
            }   
        });
    }); 
    function clickAdd()
    { 
        var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;              
        $(".form_field_outer").append(`
            <div class="row form_field_outer_row">
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <span class="error">* </span>
                    <label for="supplier" class="col-form-label text-md-end">{{ __('Brands') }}</label>                                                                 
                    <select name="brands1[]" id="brands${index}" multiple="true" style="width: 100%;"  class="form-control" autofocus>
                        @foreach($brands as $brand)
                            <option value="{{$brand->id}}">{{$brand->brand_name}}</option>
                        @endforeach   
                    </select>                      
                    <span id="supplierError" class="invalid-feedback"></span>
                </div>                                      
                <div class="col-xxl-2 col-lg-3 col-md-3">
                    <span class="error">* </span>
                    <label for="supplier" class="col-form-label text-md-end">{{ __('Purchase Price') }}</label>
                    <div class="input-group">
                        <input type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Purchase Price" aria-label="measurement" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                        </div>
                    </div>                                   
                    <span id="supplierError" class="invalid-feedback"></span>
                </div>
                <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                    <button class="btn_round  removeButtonSupplierWithoutKit" disabled hidden>
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `); 
        $(".form_field_outer").find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false); 
        $(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true); 
        globalThis.totalRow = globalThis.totalRow+1;
        setDropdownValue(index);
    }  
    function setDropdownValue(index)
    {
        $("#brands"+index).attr("data-placeholder","Choose Brand....     Or     Type Here To Search....");
        $('#brands'+index).select2({
            allowClear: true,
            minimumResultsForSearch: -1,
            templateResult: hideSelected,
        });
    }
    function hideSelected(value) 
    {
        if (value && !value.selected) {
            return $('<span>' + value.text + '</span>');
        } 
    }
    $('.radioFixingCharge').click(function()
    {
        if($(this).val() == 'yes')
        {
            hideExtendedWarrantyMileage();  
        }
        else
        {
            showExtendedWarrantyMileage();
        }
    });
    function showExtendedWarrantyMileage()
    {
        let showExtendedWarrantyMilage = document.getElementById('ExtendedWarrantyMileageDiv');
        showExtendedWarrantyMilage.hidden = false
    }
    function hideExtendedWarrantyMileage()
    {
        let showExtendedWarrantyMilage = document.getElementById('ExtendedWarrantyMileageDiv');
        showExtendedWarrantyMilage.hidden = true
    }
</script>    
@endsection