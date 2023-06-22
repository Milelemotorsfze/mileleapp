<div class="col-md-12 p-0 brandModelNumberClass" id="brandModelNumberId" hidden>
    <div class="col-md-12 brandMoDescrip p-0">
        <div class="row brandMoDescripApendHere" style="background-color:#F8F8F8; border-style: solid; border-width:1px; border-color:#e6e6ff; border-radius:10px; margin-left:10px; margin-right:10px; padding-top:10px; padding-bottom:10px;">
            <div class="row">
                <div class="col-xxl-5 col-lg-5 col-md-12">
                <span class="error">* </span>
                    <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                    <select onchange=selectBrandDisp(1,1) name="brand[1][brand_id]" id="selectBrandMo1" multiple="true" style="width: 100%;">
                        <option id="allbrands" class="allbrands" value="allbrands">ALL BRANDS</option>
                        @foreach($brands as $brand)
                            <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                        @endforeach
                    </select>
                    @error('is_primary_payment_method')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <span id="mobrandError" class=" invalid-feedback"></span>
                </div>
                <div class="col-xxl-1 col-lg-1 col-md-12">
                    <button  class="btn_round removeButtonbrandMoDescrip" disabled style="float:right;" hidden>
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            <div class="MoDes1">
                <div class="row MoDesApndHere1" >
                    <div class="col-xxl-1 col-lg-1 col-md-12">
                    </div>
                    <div class="col-xxl-5 col-lg-5 col-md-12" id="showDivdropDr1Des1" hidden>
                    <span class="error">* </span>
                        <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                        <select class="compare-tag1" name="brand[1][model][1][model_id]" onchange=selectModelLineDescipt(1,1) id="selectModelLineNum1Des1" multiple="true" style="width: 100%;">
                            @foreach($modelLines as $modelLine)
                                <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>
                            @endforeach
                        </select>      
                        @error('is_primary_payment_method')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-xxl-5 col-lg-5 col-md-12" id="showModelNumberdrop1Des1" hidden>
                        <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                        <select class="compare-tag1" name="brand[1][model][1][model_number][]" id="selectModelNumberDiscri1Des1" multiple="true" style="width: 100%;">
                            @foreach($modelLines as $modelLine)
                                <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>
                            @endforeach
                        </select>      
                        @error('is_primary_payment_method')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-12 col-lg-12 col-md-12 " id="showModelNumDel1">
                    <div id="showaddtrd1" class="col-xxl-12 col-lg-12 col-md-12" hidden>
                        <a id="addDids" style="float: right;" class="btn btn-sm btn-info" onclick="addDiscr(1)"><i class="fa fa-plus" aria-hidden="true"></i> Add</a> 
                    </div>
                </div>              
            </div>
        </div>
        </br>
    </div>
</div>
</br>
<div id="showaddtrimDis" class="col-xxl-12 col-lg-12 col-md-12" hidden>
    <a id="addDis" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a> 
</div>
</br>
<script type="text/javascript">
    var selectedBrandsDisArr = [];
    $(document).ready(function ()
    {
        $("#selectBrandMo1").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
        $("#selectBrandMo1").select2({
            maximumSelectionLength: 1,
        });
        $("#selectModelLineNum1Des1").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
        $("#selectModelLineNum1Des1").select2({
            maximumSelectionLength: 1,
        });
        $("#selectModelNumberDiscri1Des1").attr("data-placeholder","Choose Model Number....     Or     Type Here To Search....");
        $("#selectModelNumberDiscri1Des1").select2();  
        $("#addDis").on("click", function ()
        {
            $('.allbrands').prop('disabled',true);
            var index = $(".brandMoDescrip").find(".brandMoDescripApendHere").length + 1; 
            $(".brandMoDescrip").append(`  
            </br>         
            <div class="row brandMoDescripApendHere" style="background-color:#F8F8F8; border-style: solid; border-width:1px; border-color:#e6e6ff; border-radius:10px; margin-left:10px; margin-right:10px; padding-top:10px; padding-bottom:10px;">
            <div class="row">
                <div class="col-xxl-5 col-lg-5 col-md-12">
                    <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                    <select onchange=selectBrandDisp(${index},1) name="brand[${index}][brand_id]" id="selectBrandMo${index}" multiple="true" style="width: 100%;">
                        @foreach($brands as $brand)
                            <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                        @endforeach
                    </select>
                    @error('is_primary_payment_method')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
            
                    </div>
                <div class="col-xxl-1 col-lg-1 col-md-12">
                <button  class="btn_round removeButtonbrandMoDescrip" disabled style="float:right;">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    </div>
            </div>
            <div class="MoDes${index}">
            <div class="row MoDesApndHere${index}" >
                
                <div class="col-xxl-1 col-lg-1 col-md-12">
                </div>
                <div class="col-xxl-5 col-lg-5 col-md-12" id="showDivdropDr${index}Des1" hidden>
                    <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                    <select class="compare-tag1" name="brand[${index}][model][1][model_id]" onchange=selectModelLineDescipt(${index},1) id="selectModelLineNum${index}Des1" multiple="true" style="width: 100%;">
                        @foreach($modelLines as $modelLine)
                            <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>
                        @endforeach
                    </select>      
                    @error('is_primary_payment_method')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>                                        
                <div class="col-xxl-5 col-lg-5 col-md-12" id="showModelNumberdrop${index}Des1" hidden>
                    <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                    <select class="compare-tag1" name="brand[${index}][model][1][model_number][]" id="selectModelNumberDiscri${index}Des1" multiple="true" style="width: 100%;">
                        @foreach($modelLines as $modelLine)
                            <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>
                        @endforeach
                    </select>      
                    @error('is_primary_payment_method')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            </div>
                <div class="row">
                    <div class="col-xxl-12 col-lg-12 col-md-12 " id="showModelNumDel${index}">
                        <div id="showaddtrd${index}" class="col-xxl-12 col-lg-12 col-md-12" hidden>
                            <a id="addDids" style="float: right;" class="btn btn-sm btn-info" onclick="addDiscr(${index})"><i class="fa fa-plus" aria-hidden="true"></i> Add</a> 
                        </div>
                    </div>
                </div>
           
                `); 
                $(".brandMoDescrip").find(".removeButtonbrandMoDescrip:not(:first)").prop("disabled", false); $(".brandMoDescrip").find(".removeButtonbrandMoDescrip").first().prop("disabled", true); 
                $("#selectBrandMo"+index).attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
                $("#selectBrandMo"+index).select2
                ({
                    maximumSelectionLength: 1,
                });
                $("#selectModelLineNum"+index).attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
                $("#selectModelLineNum"+index).select2
                ({
                    // maximumSelectionLength: 1,
                });
                $("#selectModelNumberDiscri"+index+"Des1").attr("data-placeholder","Choose Model Number....     Or     Type Here To Search....");
                $("#selectModelNumberDiscri"+index+"Des1").select2(); 
            //===== delete the form fieed row
            $("body").on("click", ".removeButtonbrandMoDescrip", function () 
            {
                alert('hi');
                $(this).closest(".brandMoDescripApendHere").remove();
            });
        }); 
    });
    function selectModelLineDescipt(id,row)
    {
        ifModelLineExist = $("#selectModelLineNum"+id+"Des"+row).val();
        // if(currentAddonType == 'SP' && ifModelLineExist != '')
        // {
            showModelNumberDropdown(id,row);
        // }
        // else
        // {
        //     hideModelNumberDropdown(id,row);
        // }
    }
    function selectBrandDisp(id,row)
    {   
        for (let a = 1; a <= i; a++)
        { 
            var value =$('#selectBrandMo'+id).val();
            var currentAddonType = $('#addon_type').val();
            var brandId = value;
            globalThis.selectedBrandsDisArr .push(brandId);
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
                        showRelatedModalDis(id,value,row,currentAddonType);
                    }
                }
                else
                {
                    hideRelatedModalDis(brandId,row);
                }
            }
            else
            { 
                hideRelatedModalDis(brandId,row);
            }
        }
        // globalThis.selectedBrandsDisArr[i] = 2;
        // var value =$('#selectBrand'+j).val();
        // var value =$('#'+id).val();
        //     var brandId = $('#cityname1 [value="' + value + '"]').data('value');
        //     globalThis.selectedBrandsDisArr .push(brandId);
    }
    function showRelatedModalDis(id,value,row,currentAddonType)
    { 
        let showDivdropDr = document.getElementById('showDivdropDr'+id+'Des'+row);
        showDivdropDr.hidden = false
        let showaddtrimDis = document.getElementById('showaddtrimDis');
        showaddtrimDis.hidden = false
        $.ajax
        ({
            url: '/addons/brandModels/'+value,
            type: "GET",
            dataType: "json",
            success:function(data) 
            {
                console.log(data);
                console.log("#selectModelLineNum"+id+"Des"+row);
                $("#selectModelLineNum"+id+"Des"+row).html("");
                let BrandModelLine   = [];
                $.each(data,function(key,value)
                {
                    BrandModelLine.push 
                    ({
                        id: value.id,
                        text: value.model_line
                    });
                });
                // if(currentAddonType == 'SP')
                // {
                    $("#selectModelLineNum"+id+"Des"+row).select2
                    ({
                        placeholder: 'Choose Model Line....     Or     Type Here To Search....',
                        allowClear: true,
                        data: BrandModelLine,
                        maximumSelectionLength: 1,
                    });
                // }
                // else
                // {
                //     $("#selectModelLineNum"+value+"Des"+row).select2
                //     ({
                //         placeholder: 'Choose Model Line....     Or     Type Here To Search....',
                //         allowClear: true,
                //         data: BrandModelLine
                //     });
                // }   
            }
        });
    }
    function hideRelatedModalDis(id,row)
    {
        let showDivdropDr = document.getElementById('showDivdropDr'+row);
        showDivdropDr.hidden = true
        let showaddtrim = document.getElementById('showaddtrim');
        showaddtrim.hidden = true
    }
    function hideModelNumberDropdown(id,row)
    {
        let showPartNumber = document.getElementById('showModelNumberdrop'+row);
        showPartNumber.hidden = true  
    }
    function showModelNumberDropdown(id,row)
    {
        let showPartNumber = document.getElementById('showModelNumberdrop'+id+'Des'+row);
        showPartNumber.hidden = false 
        let showPartNumber1 = document.getElementById('showaddtrd'+id);
        showPartNumber1.hidden = false 
        
        var e = document.getElementById("addon_type"); 
        var value = e.value;
        var selectedModelLine = $("#selectModelLineNum"+id+"Des"+row).val();
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
                $("#selectModelNumberDiscri"+id+"Des"+row).html("").trigger("change");
                let ModelLineModelDescription   = [];
                $.each(data.model_description,function(key,value)
                {
                    ModelLineModelDescription.push 
                    ({
                        id: value.id,
                        text: value.model_description
                    });
                });
                $("#selectModelNumberDiscri"+id+"Des"+row).select2
                ({
                    placeholder: 'Select value',
                    allowClear: true,
                    data: ModelLineModelDescription
                });
            }
        });
    }
    function addDiscr(supplier)
    {
        alert('hiig');
        var index = $(".MoDes"+supplier).find(".MoDesApndHere"+supplier).length + 1; 
        $(".MoDes"+supplier).append(`
            <div class="row MoDesApndHere${supplier}">
            <div class="col-xxl-1 col-lg-1 col-md-12">
                    </div>
                    <div class="col-xxl-5 col-lg-5 col-md-12" id="showDivdropDr${supplier}Des${index}">
                        <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>        
                        <select class="compare-tag1" name=brand[${supplier}][model][${index}][model_id]" onchange=selectModelLineDescipt(${supplier},${index}) id="selectModelLineNum${supplier}Des${index}" multiple="true" style="width: 100%;">
                            @foreach($modelLines as $modelLine)
                                <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>
                            @endforeach
                        </select>      
                        @error('is_primary_payment_method')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>                                        
                    <div class="col-xxl-5 col-lg-5 col-md-12" id="showModelNumberdrop${supplier}Des${index}" hidden>
                        <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                        <select class="compare-tag1" name="brand[${supplier}][model][${index}][model_number][]" id="selectModelNumberDiscri${supplier}Des${index}" multiple="true" style="width: 100%;">
                            @foreach($modelLines as $modelLine)
                                <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>
                            @endforeach
                        </select>      
                        @error('is_primary_payment_method')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    </div>
            </div>
            `); 
        $(".apendNewItemHere"+supplier).find(".removeKitItemForSupplier"+supplier+":not(:first)").prop("disabled", false); $(".apendNewItemHere"+supplier).find(".removeKitItemForSupplier"+index).first().prop("disabled", true); 
        selectBrandDisp(supplier, index);
        // $("#selectModelLineNum"+supplier+"Des"+index).attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
        // $("#selectModelLineNum"+supplier+"Des"+index).select2
        // ({
        //     maximumSelectionLength: 1,
        // });  
        $("#selectModelNumberDiscri"+supplier+"Des"+index).attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
        $("#selectModelNumberDiscri"+supplier+"Des"+index).select2(); 
    }
</script>