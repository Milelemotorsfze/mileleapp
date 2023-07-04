<div class="col-md-12 p-0 brandModelLineClass" id="brandModelLineId">
    <div class="col-md-12 brandModelLineDiscription p-0">
        <div class="row brandModelLineDiscriptionApendHere">
            <div class="row">
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <span class="error">* </span>
                    <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                    <select onchange=selectBrand(this.id,1) name="brandModel[1][brand_id]" id="selectBrand1" multiple="true" style="width: 100%;">
                        <option id="allbrands" class="allbrands" value="allbrands">ALL BRANDS</option>
                        @foreach($brands as $brand)
                            <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                        @endforeach
                    </select>
                    <span id="brandError" class=" invalid-feedback"></span>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-12" id="showDivdrop1" hidden>
                    <span class="error">* </span>
                    <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                    <select class="compare-tag1" name="brandModel[1][modelline_id][]" id="selectModelLine1" multiple="true" 
                            style="width: 100%;">
                    </select> 
                </div>
            </div>
        </div> 
    </div>
    <div id="showaddtrim" class="col-xxl-12 col-lg-12 col-md-12" hidden>
        <a id="add" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a> 
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#selectBrand1").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
        $("#selectBrand1").select2({
            maximumSelectionLength: 1,
        });
        $("#add").on("click", function ()
        {
            $('#allbrands').prop('disabled',true);
            var index = $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length + 1; 
            $(".brandModelLineDiscription").append(`
                <div class="row brandModelLineDiscriptionApendHere">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                            <select onchange=selectBrand(this.id,${index}) name="brandModel[${index}][brand_id]" id="selectBrand${index}" multiple="true" style="width: 100%;" required>
                                @foreach($brands as $brand)
                                    <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                @endforeach
                            </select> 
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-12" id="showDivdrop${index}" hidden>
                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                            <select class="compare-tag1" name="brandModel[${index}][modelline_id][]" onchange=selectModelLine(this.id,${index}) id="selectModelLine${index}" 
                                    multiple="true" style="width: 100%;" required>
                            </select>
                        </div>
                        <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                            <button class="btn_round removeButtonbrandModelLineDiscription" disabled>
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `); 
            $(".brandModelLineDiscription").find(".removeButtonbrandModelLineDiscription:not(:first)").prop("disabled", false); 
            $(".brandModelLineDiscription").find(".removeButtonbrandModelLineDiscription").first().prop("disabled", true); 
            $("#selectBrand"+index).attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
            $("#selectBrand"+index).select2
            ({
                maximumSelectionLength: 1,
            });
            $("#selectModelLine"+index).attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
            $("#selectModelLine"+index).select2();
        }); 
    });
    //===== delete the form fieed row
    $("body").on("click", ".removeButtonbrandModelLineDiscription", function () 
    {
        $(this).closest(".brandModelLineDiscriptionApendHere").remove();
    });
    function selectBrand(id,row)
    {
        var value =$('#'+id).val();
        var currentAddonType = $('#addon_type').val();
        var brandId = value;
        globalThis.selectedBrands .push(brandId);
        if(brandId != '')
        { 
            if(brandId != 'allbrands')
            { 
                if(currentAddonType == '')
                {
                        // document.getElementById("addon_type_required").classList.add("paragraph-class");
                        // .textContent="Please select any addon type";
                        // classList..add("paragraph-class");
                        // alert('please select any addon type');
                }
                else
                {
                    showRelatedModal(value,row,currentAddonType);
                }
            }
            else
            { 
                hideRelatedModal(brandId,row);
            }
            $msg = "";
            removeBrandError($msg);
        }
        else
        { 
            hideRelatedModal(brandId,row);
        }
    }
    function showRelatedModal(value,row,currentAddonType)
    {
        let showDivdrop = document.getElementById('showDivdrop'+row);
        showDivdrop.hidden = false
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
                BrandModelLine.push 
                    ({
                        id: 'allmodellines',
                        text: 'All Model Lines'
                    });
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
                    data: BrandModelLine
                });
            }
        });
    }
    function hideRelatedModal(id,row)
    {
        let showDivdrop = document.getElementById('showDivdrop'+row);
        showDivdrop.hidden = true
        let showaddtrim = document.getElementById('showaddtrim');
        showaddtrim.hidden = true
    }
    function hideModelNumberDropdown(id,row)
    {
        let showPartNumber = document.getElementById('showModelNumberdrop'+row);
        showPartNumber.hidden = true  
    }
</script>