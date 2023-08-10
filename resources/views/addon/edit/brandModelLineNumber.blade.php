<div class="col-md-12 p-0 brandModelNumberClass" id="brandModelNumberId">
    <div class="col-md-12 brandMoDescrip p-0">
        <div hidden>{{$i=0;}}</div>
        @if($addonDetails->is_all_brands == "yes")
            <div id="rowIndexCount" hidden value="{{$i+1}}">{{$i=$i+1;}}</div>
            <div class="row brandMoDescripApendHere" style="background-color:#F8F8F8; border-style: solid; border-width:1px;border-color:#e6e6ff; border-radius:10px;
                    margin-left:10px; margin-right:10px; padding-top:10px; padding-bottom:10px;" id="row-addon-brand-{{$i}}">
                <div class="row">
                    <div class="col-xxl-5 col-lg-5 col-md-12">
                        <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                        <select onchange=selectBrandDisp({{$i}}) name="brand[{{$i}}][brand_id]" id="selectBrandMo{{$i}}" data-index="{{$i}}"
                                multiple="true" style="width: 100%;" class="brandRows">
                                <option id="allbrands" class="allbrands" value="allbrands" {{"yes" == $addonDetails->is_all_brands  ? 'selected' : ''}}>ALL BRANDS</option>
                            @foreach($brands as $brand)
                                <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                            @endforeach
                        </select>
                        <span id="mobrandError{{$i}}" class="mobrandError invalid-feedback"></span>
                        @error('is_primary_payment_method')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-xxl-6 col-lg-6 col-md-12">
                    </div>
                    <div class="col-xxl-1 col-lg-1 col-md-12">
                        <a  class="btn_round removeButtonbrandMoDescrip" data-index="{{$i}}" style="float:right;">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="MoDes{{$i}}" id="model-line-first-row">
                    <div class="row MoDesApndHere{{$i}}" id="row-spare-part-brand-{{$i}}-model-1" >
                        <div class="col-xxl-1 col-lg-1 col-md-12">
                        </div>
                        <div class="col-xxl-5 col-lg-5 col-md-12 model-line-dropdown" id="showDivdropDr{{$i}}Des1" hidden>
                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                            <select class="compare-tag1 spare-parts-model-lines" name="brand[{{$i}}][model][1][model_id]" onchange=selectModelLineDescipt({{$i}},1)
                                    id="selectModelLineNum{{$i}}Des1" multiple="true" style="width: 100%;"  data-index="{{$i}}" data-model-index="1">
                                @foreach($modelLines as $modelLine)
                                    <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>
                                @endforeach
                            </select>
                            <span id="ModelLineError_{{$i}}_1" class="ModelLineError invalid-feedback"></span>

                        </div>
                        <div class="col-xxl-5 col-lg-5 col-md-12 model-description-dropdown" id="showModelNumberdrop{{$i}}Des1" hidden>
                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                            <select class="compare-tag1 model-descriptions" name="brand[{{$i}}][model][1][model_number][]" id="selectModelNumberDiscri{{$i}}Des1"
                                    multiple="true" style="width: 100%;"   onchange=selectModelDescipt({{$i}},1)>
                                @foreach($modelLines as $modelLine)
                                    <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>
                                @endforeach
                            </select>
                            <span id="ModelDescriptionError_{{$i}}_1" class="ModelDescriptionError invalid-feedback"></span>

                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-12">
                            <a  class="btn_round removeButtonModelItem" data-index="{{$i}}" data-model-index="1" hidden id="removeModelNumberdrop{{$i}}Des1">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12 col-lg-12 col-md-12 " id="showModelNumDel{{$i}}" class="delete-model-line-row" hidden>
                        <div id="showaddtrd{{$i}}" class="col-xxl-12 col-lg-12 col-md-12 show-add-button"  >
                            <a id="addDids" style="float: right;" class="btn btn-sm btn-info" onclick="addDiscr({{$i}})">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
        @foreach($existingBrandModel as $existingBrand)
            <div id="rowIndexCount" hidden value="{{$i+1}}">{{$i=$i+1;}}</div>
            <div class="row brandMoDescripApendHere" style="background-color:#F8F8F8; border-style: solid; border-width:1px;border-color:#e6e6ff; border-radius:10px;
                    margin-left:10px; margin-right:10px; padding-top:10px; padding-bottom:10px;" id="row-addon-brand-{{$i}}">
                <div class="row">
                    <div class="col-xxl-5 col-lg-5 col-md-12">
                        <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
{{--                        <!-- <select onchange=selectBrandDisp({{$i}}) name="brand[{{$i}}][brand_id]" id="selectBrandMo{{$i}}" data-index="{{$i}}"--}}
{{--                                multiple="true" style="width: 100%;" class="brandRows">--}}
{{--                                <option id="allbrands" class="allbrands" value="allbrands" {{"yes" == $addonDetails->is_all_brands  ? 'selected' : ''}}>ALL BRANDS</option>--}}
{{--                                <option class="{{$existingBrand->brands->id}}" value="{{$existingBrand->brands->id}}" selected locked="locked">{{$existingBrand->brands->brand_name}}</option>--}}
{{--                                @foreach($brands as $brand)--}}
{{--                                    <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>--}}
{{--                                @endforeach--}}
{{--                        </select> -->--}}
                        <select disabled onchange=selectBrandDisp({{$i}})  id="selectBrandMo{{$i}}" data-index="{{$i}}"
                                multiple="true" style="width: 100%;" class="brandRows">
                                <option id="allbrands" class="allbrands" value="allbrands" {{"yes" == $addonDetails->is_all_brands  ? 'selected' : ''}}>ALL BRANDS</option>
                                <option class="{{$existingBrand->brands->id}}" value="{{$existingBrand->brands->id}}" selected locked="locked">{{$existingBrand->brands->brand_name}}</option>
                                @foreach($brands as $brand)
                                    <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                @endforeach
                        </select>
                        <span id="mobrandError{{$i}}" class="mobrandError invalid-feedback"></span>
                        <input hidden value="{{$existingBrand->brands->id}}" name="brand[{{$i}}][brand_id]">
                        @error('is_primary_payment_method')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-xxl-6 col-lg-6 col-md-12">
                    </div>
                    <div class="col-xxl-1 col-lg-1 col-md-12">
                        <a  class="btn_round removeButtonbrandMoDescrip" data-index="{{$i}}" style="float:right;">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <div hidden>{{$j=0;}}</div>
                @foreach($existingBrand->ModelLine as $ModelLine)
                <div id="rowDesCount{{$i}}" hidden value="{{$j+1}}">{{$j=$j+1;}}</div>
                <div class="MoDes{{$i}}" id="model-line-first-row">
                    <div class="row MoDesApndHere{{$i}}" id="row-spare-part-brand-{{$i}}-model-{{$j}}">
                        <div class="col-xxl-1 col-lg-1 col-md-12">
                        </div>
                        <div class="col-xxl-5 col-lg-5 col-md-12 model-line-dropdown" id="showDivdropDr{{$i}}Des{{$j}}" >
                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Line </label>
                            <select class="compare-tag1 spare-parts-model-lines" name="brand[{{$i}}][model][{{$j}}][model_id]" onchange=selectModelLineDescipt({{$i}},{{$j}})
                                    id="selectModelLineNum{{$i}}Des{{$j}}" multiple="true" style="width: 100%;"  data-index="{{$i}}" data-model-index="{{$j}}">
                                    <option value="allmodellines" {{"yes" == $ModelLine->is_all_model_lines  ? 'selected' : ''}}>All Model Lines</option>
                                    @foreach($existingBrand->ModalLines as $drop)
                                    <option value="{{$drop->id}}" @if($drop->id == $ModelLine->model_id) selected @endif>{{$drop->model_line}}</option>
                                    @endforeach
                            </select>
                            <span id="ModelLineError_{{$i}}_{{$j}}" class="ModelLineError invalid-feedback"></span>

                        </div>
                        <div class="col-xxl-5 col-lg-5 col-md-12 model-description-dropdown" id="showModelNumberdrop{{$i}}Des{{$j}}" @if($ModelLine->is_all_model_lines == 'yes') hidden @endif >
                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                            <select class="compare-tag1 model-descriptions" name="brand[{{$i}}][model][{{$j}}][model_number][]" id="selectModelNumberDiscri{{$i}}Des{{$j}}"
                                    multiple="true" style="width: 100%;"   onchange=selectModelDescipt({{$i}},{{$j}})>
                                    @if(isset($ModelLine->allDes))
                                @foreach($ModelLine->allDes as $allDes)
                                    <option class="{{$ModelLine->brand_id}}" value="{{$allDes->id}}" @if(in_array(" $allDes->id ", $ModelLine->modeldes)) selected @endif
                                        >{{$allDes->model_description}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span id="ModelDescriptionError_{{$i}}_{{$j}}" class="ModelDescriptionError invalid-feedback"></span>

                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-12">
                            <a  class="btn_round removeButtonModelItem" data-index="{{$i}}"  data-model-index="{{$j}}" id="removeModelNumberdrop{{$i}}Des{{$j}}">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="row">
                    <div class="col-xxl-12 col-lg-12 col-md-12 " id="showModelNumDel{{$i}}" class="delete-model-line-row" >
                        <div id="showaddtrd{{$i}}" class="col-xxl-12 col-lg-12 col-md-12 show-add-button" >
                            <a id="addDids" style="float: right;" class="btn btn-sm btn-info" onclick="addDiscr({{$i}})">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            </br>
            @endforeach
        @endif
    </div>
</div>
</br>
<div id="showaddtrimDis" class="col-xxl-12 col-lg-12 col-md-12" style="margin-top:20px;" hidden>
    <a id="addDis" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a>
</div>
</br>
<input type="hidden" id="indexValue" value="">
<input type="hidden" id="modelIndexValue" value="">

<script type="text/javascript">
    var selectedBrandsDisArr = [];
    var existingBrandModel = {!! json_encode($existingBrandModel) !!};
    var lengthExistingBrands = '';
    $(document).ready(function ()
    {
        lengthExistingBrands = existingBrandModel.length;
        if(lengthExistingBrands > 0)
        {
            let showAdTrim = document.getElementById('showaddtrimDis');
            showAdTrim.hidden = false
        }
                $(".brandRows").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
                $(".brandRows").select2({
                    maximumSelectionLength: 1,
                });
                $(".spare-parts-model-lines").attr("data-placeholder","Choose Model Line....     Or     Type Here To Search....");
                $(".spare-parts-model-lines").select2({
                    maximumSelectionLength: 1,
                });
                $(".model-descriptions").attr("data-placeholder","Choose Model Number....     Or     Type Here To Search....");
                $(".model-descriptions").select2();
        ///////////////////// dropdown /////////////

        var index = 1;
        $('#indexValue').val(index);
        $(document.body).on('select2:select', ".brandRows", function (e) {
            var index = $(this).attr('data-index');
            var value = e.params.data.id;

            var indexValue = $(".MoDes"+index).find(".MoDesApndHere"+index).length;
            for(var i = 1;i<=indexValue;i++) {
                $('#selectModelLineNum'+index+'Des'+i).empty();
                $('#selectModelNumberDiscri'+index+'Des'+i).empty();
            }

            hideOption(index,value);
            uniqueCheckSpareParts();

        });
        $(document.body).on('select2:unselect', ".brandRows", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            appendOption(index,data);
            uniqueCheckSpareParts();

        });

        function hideOption(index,value) {
            var indexValue = $('#indexValue').val();
            for (var i = 1; i <= indexValue; i++) {
                if (i != index) {
                    var currentId = 'selectBrandMo' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        function appendOption(index,data) {
            var indexValue = $('#indexValue').val();
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#selectBrandMo'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }

        $(document.body).on('select2:select', ".spare-parts-model-lines", function (e) {
            var index = $(this).attr('data-index');
            var modelIndex = $(this).attr('data-model-index');
            var value = e.params.data.id;
            modelLineDataHide(index,modelIndex,value);
            uniqueCheckSpareParts();
        });

        $(document.body).on('select2:unselect', ".spare-parts-model-lines", function (e) {
            var index = $(this).attr('data-index');
            var modelIndex = $(this).attr('data-model-index');
            var id = e.params.data.id;
            var text = e.params.data.text;
            modelLineDataAppend(index,modelIndex,id,text);
            uniqueCheckSpareParts();
        });
        $(document.body).on('select2:select', ".model-descriptions", function (e) {
            uniqueCheckSpareParts();
        });
        $(document.body).on('select2:unselect', ".model-descriptions", function (e) {
            uniqueCheckSpareParts();
        });
        function modelLineDataHide(index,modelIndex,value) {
            var indexValue = $(".MoDes"+index).find(".MoDesApndHere"+index).length;

            for (var i = 1; i <= indexValue; i++) {
                if (i != modelIndex) {
                    var currentId = 'selectModelLineNum'+ index +'Des' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        function modelLineDataAppend(index,modelIndex,id,text) {
            var indexValue = $(".MoDes"+index).find(".MoDesApndHere"+index).length;
            for (var i = 1; i <= indexValue; i++) {
                if (i != modelIndex) {
                    var currentId = 'selectModelLineNum'+ index +'Des' + i;
                    $('#'+currentId).append($('<option>', {value: id, text : text}))
                }
            }
        }

        function addOption(id,text) {
            var indexValue = $('#indexValue').val();
            for(var i=1;i<=indexValue;i++) {
                $('#selectBrandMo'+i).append($('<option>', {value: id, text :text}))
            }
        }
        $(document.body).on('click', ".removeButtonbrandMoDescrip", function (e)
        {

            var countRow = 0;
            var countRow =  $(".brandMoDescrip").find(".brandMoDescripApendHere").length;
            if(countRow > 1)
            {
                var indexNumber = $(this).attr('data-index');
                if(indexNumber == 1) {
                        $('<option value="allbrands"> ALL BRANDS </option>').prependTo('#selectBrandMo2');
                    }
                $(this).closest('#row-addon-brand-'+indexNumber).find("option:selected").each(function() {
                    var id = (this.value);
                    var text = (this.text);
                    addOption(id,text)
                });

                $(this).closest('#row-addon-brand-'+indexNumber).remove();
                $('.brandMoDescripApendHere').each(function(i){
                    var index = +i + +1;
                    $(this).attr('id','row-addon-brand-'+ index);

                    $(this).find('.brandRows').attr('data-index', index);
                    $(this).find('.brandRows').attr('id','selectBrandMo'+ index);
                    $(this).find('.brandRows').attr('name','brand['+ index +'][brand_id]');
                    $(this).find('.brandRows').attr('onchange','selectBrandDisp('+ index +')');
                    $('#selectBrandMo'+index).select2
                    ({
                        placeholder:"Choose Brands....     Or     Type Here To Search....",
                        allowClear: true,
                        minimumResultsForSearch: -1,
                    });

                    $(this).find('.removeButtonbrandMoDescrip').attr('data-index', index);
                    $(this).find('.delete-model-line-row').attr('id', 'showModelNumDel'+ index);
                    $(this).find('.show-add-button').attr('id','showaddtrd'+ index);
                    $(this).find('#addDids').attr('onclick', 'addDiscr('+ index +')');

                    var oldIndex = '';
                    oldIndex = index+1;
                    itemcount = $(".MoDes"+oldIndex).find(".MoDesApndHere"+oldIndex).length;
                    for (var i = 1; i <= itemcount; i++)
                    {
                        $(this).find('#row-spare-part-brand-'+oldIndex+'-model-'+i).attr('id', 'row-spare-part-brand-'+index+'-model-'+i);
                        $(this).find('#showDivdropDr'+ oldIndex +'Des'+i).attr('id','showDivdropDr'+ index +'Des'+i);
                        $(this).find('#selectModelLineNum'+ oldIndex +'Des'+i).attr('data-index', index);
                        $(this).find('#selectModelLineNum'+ oldIndex +'Des'+i).attr('name','brand['+ index +'][model]['+i+'][model_id]');
                        $(this).find('#selectModelLineNum'+ oldIndex +'Des'+i).attr('onchange','selectModelLineDescipt('+ index +','+i+')');
                        $(this).find('#selectModelLineNum'+ oldIndex +'Des'+i).attr('class','compare-tag1 spare-parts-model-lines');
                        $(this).find('#selectModelLineNum'+ oldIndex +'Des'+i).attr('id','selectModelLineNum'+ index +'Des'+i);
                        $("#selectModelLineNum"+index+"Des"+i).select2
                        ({
                            placeholder: 'Choose Model Line....     Or     Type Here To Search....',
                            allowClear: true,
                            maximumSelectionLength: 1,
                        });

                        $(this).find('#showModelNumberdrop'+ oldIndex +'Des'+i).attr('id', 'showModelNumberdrop'+ index +'Des'+i);
                        $(this).find('#selectModelNumberDiscri'+ oldIndex +'Des'+i).attr('name', 'brand['+ index +'][model]['+i+'][model_number][]');
                        $(this).find('#selectModelNumberDiscri'+ oldIndex +'Des'+i).attr('id', 'selectModelNumberDiscri'+ index +'Des'+i);
                        $(this).find('#selectModelNumberDiscri'+ oldIndex +'Des'+i).attr('onchange','selectModelNumberDiscri('+ index +','+i+')');
                        $("#selectModelNumberDiscri"+index+"Des"+i).select2
                        ({
                            placeholder: 'Choose Model Description....     Or     Type Here To Search....',
                            allowClear: true,
                        });

                        $(this).find('#removeModelNumberdrop' + oldIndex + 'Des' + i).attr('data-index', index);
                        $(this).find('#removeModelNumberdrop' + oldIndex + 'Des' + i).attr('id', 'removeModelNumberdrop' + index + 'Des' + i);
                        $(this).find('#ModelLineError_'+oldIndex+'_'+i).attr('id','ModelLineError_'+index+'_'+i);
                        $(this).find('#ModelDescriptionError_'+oldIndex+'_'+i).attr('id','ModelDescriptionError_'+index+'_'+i);

                    }
                    $(this).find(".MoDes"+oldIndex).attr('class', "MoDes"+index);
                    $(this).find(".MoDesApndHere"+oldIndex).attr('class', "row MoDesApndHere"+index);
                });
            }
            else
            {
                var confirm = alertify.confirm('You are not able to remove this row, Atleast one Brand and Model Lines Required',function (e) {
                }).set({title:"Can't Remove Brand And Model Lines"})
            }
        })
        $(document.body).on('click', ".removeButtonModelItem", function (e) {
            var indexNumber = $(this).attr('data-index');
            var countRow = 0;
            var countRow = $(".MoDes"+indexNumber).find(".MoDesApndHere"+indexNumber).length;
            if(countRow > 1)
            {
                var modelIndex = $(this).attr('data-model-index');
                if(modelIndex == 1) {
                        $('<option value="allmodellines"> All Model Lines </option>').prependTo('#selectModelLineNum'+indexNumber+'Des2');
                    }
                $(this).closest('#row-spare-part-brand-'+indexNumber+'-model-'+modelIndex).find("option:selected").each(function() {
                    var id = (this.value);
                    var text = (this.text);
                    modelLineDataAppend(indexNumber,modelIndex,id,text)
                });
                $(this).closest('#row-spare-part-brand-'+indexNumber+'-model-'+modelIndex).remove();
                $('.MoDesApndHere'+indexNumber).each(function(i){
                    var modelIndex = +i + +1;

                    //// should loop ////////////
                    $(this).attr('id','row-spare-part-brand-'+indexNumber  +'-model-'+modelIndex);
                    $(this).find('.model-line-item-dropdown').attr('id', 'showDivdropDr'+indexNumber+'Des'+modelIndex);

                    $(this).find('.spare-parts-model-lines').attr('data-index',indexNumber);
                    $(this).find('.spare-parts-model-lines').attr('data-model-index',modelIndex);

                    $(this).find('.spare-parts-model-lines').attr('id','selectModelLineNum'+ indexNumber +'Des'+modelIndex);

                    $(this).find('.spare-parts-model-lines').attr('id','selectModelLineNum'+ indexNumber +'Des'+modelIndex);
                    $(this).find('.spare-parts-model-lines').attr('name','brand['+ indexNumber +'][model]['+modelIndex+'][model_id]');
                    $(this).find('.spare-parts-model-lines').attr('onchange','selectModelLineDescipt('+ indexNumber +','+ modelIndex +')');
                    $(this).find('.model-description-dropdown').attr('id', 'showModelNumberdrop'+ indexNumber +'Des'+ modelIndex);

                    $(this).find('.model-descriptions').attr('name', 'brand['+ indexNumber +'][model]['+ modelIndex +'][model_number][]');
                    $(this).find('.model-descriptions').attr('id', 'selectModelNumberDiscri'+ indexNumber +'Des'+ modelIndex);
                    $(this).find('.model-descriptions').attr('onchange','selectModelNumberDiscri('+ indexNumber +','+modelIndex+')');

                    $(this).find('.ModelLineError').attr('id','ModelLineError_'+indexNumber+'_'+modelIndex);
                    $(this).find('.ModelDescriptionError').attr('id','ModelDescriptionError_'+indexNumber+'_'+modelIndex);
                    ////////////// end ////////////////

                    $(this).find('.removeButtonModelItem').attr('data-index',indexNumber);
                    $(this).find('.removeButtonModelItem').attr('data-model-index',modelIndex);


                    $('#selectBrandMo'+indexNumber).select2
                    ({
                        placeholder:"Choose Brands....     Or     Type Here To Search....",
                        allowClear: true,
                        minimumResultsForSearch: -1,
                    });
                    $("#selectModelLineNum"+indexNumber+"Des"+modelIndex).select2
                    ({
                        placeholder: 'Choose Model Line....     Or     Type Here To Search....',
                        allowClear: true,
                        maximumSelectionLength: 1,
                    });
                    $("#selectModelNumberDiscri"+indexNumber+"Des"+modelIndex).select2
                    ({
                        placeholder: 'Choose Model Description....     Or     Type Here To Search....',
                        allowClear: true,
                    });
                });
            }
            else
            {
                var confirm = alertify.confirm('You are not able to remove this row, Atleast one Model Line and Model Required',function (e) {
                }).set({title:"Can't Remove Model Line and Models"})
            }
        })

        //////////////// end //////////////////////

        $("#addDis").on("click", function ()
        {
            $('.allbrandsMo').prop('disabled',true);
            var index = $(".brandMoDescrip").find(".brandMoDescripApendHere").length + 1;

            $('#indexValue').val(index);
            var selectedBrands = [];
            for(let i=1; i<index; i++)
            {
                var eachSelectedBrand = $('#selectBrandMo'+i).val();
                if(eachSelectedBrand) {
                    selectedBrands.push(eachSelectedBrand);
                }
            }
            $.ajax({
                url:"{{url('getBrandForAddons')}}",
                type: "POST",
                data:
                    {
                        filteredArray: selectedBrands,
                        _token: '{{csrf_token()}}'
                    },
                dataType : 'json',
                success: function(data) {
                    myarray = data;
                    var size = myarray.length;
                    if (size >= 1) {
                        $(".brandMoDescrip").append(`
                            </br>
                            <div class="row brandMoDescripApendHere" style="background-color:#F8F8F8; border-style: solid; border-width:1px;
                                    border-color:#e6e6ff; border-radius:10px; margin-left:10px; margin-right:10px; padding-top:10px; padding-bottom:10px;"
                                    id="row-addon-brand-${index}">
                                <div class="row">
                                    <div class="col-xxl-5 col-lg-5 col-md-12">
                                        <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                                        <select onchange=selectBrandDisp(${index}) name="brand[${index}][brand_id]" id="selectBrandMo${index}" data-index="${index}"
                                            multiple="true" style="width: 100%;" class="brandRows">
                                            @foreach($brands as $brand)
                                                <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                            @endforeach
                                        </select>
                                        <span id="mobrandError${index}" class="mobrandError invalid-feedback"></span>
                                        @error('is_primary_payment_method')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-xxl-6 col-lg-6 col-md-12">

                                    </div>
                                    <div class="col-xxl-1 col-lg-1 col-md-12">
                                        <a  class="btn_round removeButtonbrandMoDescrip" data-index="${index}" style="float:right;">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="MoDes${index}" id="model-line-first-row">
                                    <div class="row MoDesApndHere${index}" id="row-spare-part-brand-${index}-model-1" >
                                        <div class="col-xxl-1 col-lg-1 col-md-12">
                                        </div>
                                        <div class="col-xxl-5 col-lg-5 col-md-12 model-line-dropdown" id="showDivdropDr${index}Des1" hidden>
                                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                                            <select class="compare-tag1 spare-parts-model-lines" name="brand[${index}][model][1][model_id]" onchange=selectModelLineDescipt(${index},1)
                                                id="selectModelLineNum${index}Des1" multiple="true" style="width: 100%;"  data-index="${index}" data-model-index="1">

                                            </select>
                                            <span id="ModelLineError_${index}_1" class="ModelLineError invalid-feedback"></span>

                                        </div>
                                        <div class="col-xxl-5 col-lg-5 col-md-12 model-description-dropdown" id="showModelNumberdrop${index}Des1" hidden>
                                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                                            <select class="compare-tag1 model-descriptions" name="brand[${index}][model][1][model_number][]" id="selectModelNumberDiscri${index}Des1"
                                                multiple="true" style="width: 100%;"   onchange=selectModelDescipt(${index},1)>

                                            </select>
                                              <span id="ModelDescriptionError_${index}_1" class="ModelDescriptionError invalid-feedback"></span>

                                        </div>
                                        <div class="col-xxl-1 col-lg-1 col-md-12">
                                            <a  class="btn_round removeButtonModelItem" data-index="${index}" data-model-index="1" hidden id="removeModelNumberdrop${index}Des1">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12 " id="showModelNumDel${index}" class="delete-model-line-row">
                                        <div id="showaddtrd${index}" class="col-xxl-12 col-lg-12 col-md-12 show-add-button" hidden >
                                            <a id="addDids" style="float: right;" class="btn btn-sm btn-info" onclick="addDiscr(${index})">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                        </div>
                                    </div>
                                </div>
                        `);
                        let brandDropdownData   = [];
                        $.each(data,function(key,value)
                        {
                            brandDropdownData.push
                            ({
                                id: value.id,
                                text: value.brand_name
                            });
                        });
                        $('#selectBrandMo'+index).html("");
                        $("#selectBrandMo"+index).attr("data-placeholder","Choose Brand....     Or     Type Here To Search....");
                        $("#selectBrandMo"+index).select2
                        ({
                            data:brandDropdownData,
                            maximumSelectionLength: 1,
                        });
                    }
                }
            });
        });
    });

    function addDiscr(supplier)
    {
        var index = $(".MoDes"+supplier).find(".MoDesApndHere"+supplier).length + 1;


        $(".MoDes" + supplier).append(`
            <div class="row MoDesApndHere${supplier}" id="row-spare-part-brand-${supplier}-model-${index}">
                <div class="col-xxl-1 col-lg-1 col-md-12">
                </div>
                <div class="col-xxl-5 col-lg-5 col-md-12 model-line-item-dropdown" id="showDivdropDr${supplier}Des${index}">
                    <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                    <select class="compare-tag1 spare-parts-model-lines" name=brand[${supplier}][model][${index}][model_id]"
                        onchange=selectModelLineDescipt(${supplier},${index})
                        id="selectModelLineNum${supplier}Des${index}" multiple="true" style="width: 100%;" data-index="${supplier}" data-model-index="${index}">
{{--                        @foreach($modelLines as $modelLine)--}}
        {{--                            <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>--}}
        {{--                        @endforeach--}}
                    </select>
                    <span id="ModelLineError_${supplier}_${index}" class="ModelLineError invalid-feedback"></span>

                </div>
                <div class="col-xxl-5 col-lg-5 col-md-12 model-description-dropdown" id="showModelNumberdrop${supplier}Des${index}" hidden>
                    <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                    <select class="compare-tag1 model-descriptions" name="brand[${supplier}][model][${index}][model_number][]" id="selectModelNumberDiscri${supplier}Des${index}"
                        multiple="true" style="width: 100%;"  onchange=selectModelDescipt(${supplier},${index})>

                    </select>
                      <span id="ModelDescriptionError_${supplier}_${index}" class="ModelDescriptionError invalid-feedback"></span>

                </div>
                <div class="col-xxl-1 col-lg-1 col-md-12">
                    <a  class="btn_round removeButtonModelItem" data-index="${supplier}" data-model-index="${index}" id="removeModelNumberdrop${supplier}Des${index}">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>
            </div>
            </div>
         `);

        selectBrandDisp(supplier,index);
        $("#selectModelNumberDiscri"+supplier+"Des"+index).select2
        ({
            placeholder: 'Choose Model Description....     Or     Type Here To Search....',
            allowClear: true,
        });
        // $("#selectModelNumberDiscri" + supplier + "Des" + index).attr("data-placeholder", "Choose Model Description....     Or     Type Here To Search....");
    }


    function showBrandModelLines(id,row) {
        var brandTotalIndex = $(".brandMoDescrip").find(".brandMoDescripApendHere").length;

        for (let a = 1; a <= i; a++)
        {
            var value = $('#selectBrandMo'+id).val();
            var currentAddonType = $('#addon_type').val();
            var brandId = value;
            globalThis.selectedBrandsDisArr .push(brandId);
            if(brandId != '')
            {
                if(brandId != 'allbrands')
                {
                    $('.spare-parts-model-lines').attr('disabled', false);
                    $('.model-descriptions').attr('disabled', false);
                    for (var j=2;j<=brandTotalIndex;j++) {
                        $('#selectBrandMo'+j).attr('disabled', false);
                    }
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
                    RelatedDataCheck(id,row);
                    $('#showaddtrimDis').attr('hidden',true);
                }
            }

        }
    }
    function selectBrandDisp(id,row)
    {
        var indexValue = $(".MoDes"+id).find(".MoDesApndHere"+id).length;
        console.log(indexValue);
        console.log(row);

        var value = $('#selectBrandMo'+id).val();
        var brandId = value;
        if(brandId != '')
        {
            $msg = "";
            removeSPBrandError($msg,id);
            if(indexValue == row) {
                showBrandModelLines(id,row);

            }else {
                for(var i = 1;i<=indexValue;i++) {
                    // $('#selectModelLineNum'+index+'Des'+i).empty();
                    showBrandModelLines(id,i);
                }
            }
        }
        else
        {
            $msg = "Brand is Required";
            showSPBrandError($msg,id);
        }
    }
    function  RelatedDataCheck() {
        var brandTotalIndex = $(".brandMoDescrip").find(".brandMoDescripApendHere").length;
        var brands = [];
        var modelLines = [];
        var modelDescriptions = [];
        if (brandTotalIndex > 0) {
            for(let i=1; i<=brandTotalIndex; i++)
            {
                var eachBrand = $('#selectBrandMo'+i).val();
                if(eachBrand != '' && eachBrand != 'allbrands') {
                    brands.push(eachBrand);
                }
                var index = $(".MoDes"+i).find(".MoDesApndHere"+i).length;
                for(let j=1; j<=index; j++)
                {
                    var eachModelRow = $('#selectModelLineNum'+ i+'Des'+j).val();
                    var eachModelNumberRow = $('#selectModelNumberDiscri'+ i+'Des'+j).val();
                    if(eachModelRow != '' ) {
                        modelLines.push(eachModelRow);
                    }
                    if(eachModelNumberRow != '' ) {
                        modelDescriptions.push(eachModelNumberRow);
                    }

                    if(eachModelRow != '' || eachModelNumberRow != '')
                    {
                        var confirm = alertify.confirm('You are not able to edit this field while any Items in Brand and Model Line.' +
                            'Please remove those items to edit this field.', function (e) {
                        }).set({title: "Remove Brands and ModelLines"})
                        $("#selectBrandMo1 option:selected").prop("selected", false);
                        $("#selectBrandMo1").trigger('change');
                    }
                }

                if(i != 1) {
                    var eachBrand = $('#selectBrandMo'+i).val();
                    if(eachBrand != '') {
                        var confirm = alertify.confirm('You are not able to edit this field while any Items in Brand and Model Line.' +
                            'Please remove those items to edit this field.', function (e) {
                        }).set({title: "Remove Brands and ModelLines"})
                        $("#selectBrandMo1 option:selected").prop("selected", false);
                        $("#selectBrandMo1").trigger('change');
                    }
                }
            }
            var brandCount = brands.length;
            var ModelLineCount = modelLines.length;
            var ModelDescriptionCount = modelDescriptions.length;

            if(brandCount == 0 && ModelLineCount == 0 && ModelDescriptionCount == 0) {
                $('.spare-parts-model-lines').attr('disabled', true);
                $('.model-descriptions').attr('disabled', true)
                for(let i=2; i<=brandTotalIndex; i++)
                {
                    $('#selectBrandMo'+i).attr('disabled',true)
                }
            }

        }
    }
    function showRelatedModalDis(id,value,row,currentAddonType)
    {
        var selectedModelLines = [];
        for(let i=1; i< row; i++)
        {
            var eachSelectedModelLine = $('#selectModelLineNum'+id+'Des'+i).val();
            if(eachSelectedModelLine) {
                selectedModelLines.push(eachSelectedModelLine);
            }
        }
        $('#showDivdropDr'+id+'Des'+row).attr('hidden', false);
        // let showDel = document.getElementById('removeModelNumberdrop'+id+'Des'+row);
        // showDel.hidden = false
        $('#removeModelNumberdrop'+id+'Des'+row).attr('hidden', false);

        let showaddtrimDis = document.getElementById('showaddtrimDis');
        showaddtrimDis.hidden = false
        $.ajax
        ({
            url: '/addons/brandModels/'+value,
            type: "GET",
            data:
                {
                    filteredArray: selectedModelLines,
                },
            dataType: "json",
            success:function(data)
            {
                $("#selectModelLineNum"+id+"Des"+row).html("");
                let BrandModelLine   = [];
                if(row == 1) {
                BrandModelLine.push
                    ({
                        id: 'allmodellines',
                        text: 'All Model Lines'
                    });
                }
                $.each(data,function(key,value)
                {
                    BrandModelLine.push
                    ({
                        id: value.id,
                        text: value.model_line
                    });
                });
                $("#selectModelLineNum"+id+"Des"+row).select2
                ({
                    placeholder: 'Choose Model Line....     Or     Type Here To Search....',
                    allowClear: true,
                    data: BrandModelLine,
                    maximumSelectionLength: 1,
                });
                $('#showModelNumberdrop'+id+'Des'+row).attr('hidden', false);
                $("#selectModelNumberDiscri"+id+"Des"+row).select2
                ({
                    placeholder: 'Choose Model Number....     Or     Type Here To Search....',
                    allowClear: true,
                    maximumSelectionLength: 1,
                });
            }
        });
    }
    function selectModelLineDescipt(id,row)
    {
        ifModelLineExist = $("#selectModelLineNum"+id+"Des"+row).val();
        if(ifModelLineExist == '')
        {
            $msg="Model line is required";
            showSPModelLineError($msg,id,row);
        }
        else
        {
            showModelNumberDropdown(id,row);
            removeSPModelLineError(id,row);
        }
    }
    function selectModelDescipt(id,row)
    {
        ifModelDiscExist = $("#selectModelNumberDiscri"+id+"Des"+row).val();
        if(ifModelDiscExist == '')
        {
            $msg="Model Description is required";
            showSPModelDescriptionError($msg,id,row);
        }
        else
        {
            removeSPModelDescriptionError(id,row);
        }
    }

    function showModelNumberDropdown(id,row)
    {
        $('showModelNumberdrop'+id+'Des'+row).attr('hidden', false);
        // showPartNumber.hidden = false;
        let showPartNumber1 = document.getElementById('showaddtrd'+id);
        showPartNumber1.hidden = false;

        var e = document.getElementById("addon_type");
        var value = e.value;
        // var selectedModelLine = [];
        var selectedModelLine = $("#selectModelLineNum"+id+"Des"+row).val();
        if(selectedModelLine != '')
        {
            if(selectedModelLine == 'allmodellines')
            {
                RelatedModelLineCheck(id,row)
            }else{
                $('#showModelNumDel'+id).attr('hidden',false);
                $('#showModelNumberdrop'+id+'Des'+row).attr('hidden',false);
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
                        $("#selectModelNumberDiscri"+id+"Des"+row).html("").trigger("change");
                        $("#selectModelNumberDiscri"+id+"Des"+row).select2
                        ({
                            placeholder: 'Choose Model Number....     Or     Type Here To Search....',
                            allowClear: true,
                            data: ModelLineModelDescription
                        });
                    }
                });
            }
        }
    }
    function RelatedModelLineCheck(id,row)
    {
        var index = $(".MoDes"+id).find(".MoDesApndHere"+id).length;

        var modelLinesData = [];
        var modelLineDescriptionData = [];

        for(let j=1; j<=index; j++)
        {
            var eachModelLineRow = $('#selectModelLineNum'+ id+'Des'+j).val();
            var eachModelDescriptionRow = $('#selectModelNumberDiscri'+ id+'Des'+j).val();
            if(eachModelLineRow != '' && eachModelLineRow != 'allmodellines') {
                modelLinesData.push(eachModelLineRow);
            }
            if(eachModelDescriptionRow != '' ) {
                modelLineDescriptionData.push(eachModelDescriptionRow);
            }
            if(j !=1) {
                if(eachModelLineRow != '' )
                {
                    var confirm = alertify.confirm('You are not able to edit this field while any Items Model Line.' +
                        'Please remove those items to edit this field.', function (e) {
                    }).set({title: "Remove Brands and ModelLines"})
                    $("#selectModelLineNum"+id+"Des1").find("option:selected").prop("selected", false);
                    $("#selectModelLineNum"+id+"Des1").trigger('change');
                }
            }
            if(eachModelDescriptionRow != '' )
            {
                var confirm = alertify.confirm('You are not able to edit this field while any Items Model Line.' +
                    'Please remove those items to edit this field.', function (e) {
                }).set({title: "Remove Brands and ModelLines"})
                $("#selectModelLineNum"+id+"Des1").find("option:selected").prop("selected", false);
                $("#selectModelLineNum"+id+"Des1").trigger('change');
            }
        }
        var modelLinesDataCount = modelLinesData.length;
        var modelLineDescriptionDataCount = modelLineDescriptionData.length;

        if(modelLinesDataCount == 0 && modelLineDescriptionDataCount == 0) {
            $('#showModelNumDel'+id).attr('hidden',true);
            for(var i=1;i<=index;i++) {
                $('#showModelNumberdrop'+id+'Des'+i).attr('hidden',true);
                if(i != 1) {
                    $('#removeModelNumberdrop'+id+'Des'+i).attr('hidden', true);
                    $('#showDivdropDr'+id+'Des'+i).attr('hidden',true);
                }
            }
        }
    }
    // function showModelNumberDropdown1(id,row)
    // {
    //     let showPartNumber = document.getElementById('showModelNumberdrop'+id+'Des'+row);
    //     showPartNumber.hidden = false
    //     let showPartNumber1 = document.getElementById('showaddtrd'+id);
    //     showPartNumber1.hidden = false

    //     var e = document.getElementById("addon_type");
    //     var value = e.value;
    //     var selectedModelLine = $("#selectModelLineNum"+id+"Des"+row).val();
    //     $.ajax
    //     ({
    //         url:"{{url('getModelDescriptionDropdown')}}",
    //         type: "POST",
    //         data:
    //         {
    //             model_line_id: selectedModelLine,
    //             addon_type: value,
    //             _token: '{{csrf_token()}}'
    //         },
    //         dataType : 'json',
    //         success:function(data)
    //         {
    //             // console.log(data);
    //             $("#selectModelNumberDiscri"+id+"Des"+row).html("").trigger("change");
    //             let ModelLineModelDescription   = [];
    //             $.each(data.model_description,function(key,value)
    //             {
    //                 ModelLineModelDescription.push
    //                 ({
    //                     id: value.id,
    //                     text: value.model_description
    //                 });
    //             });
    //             console.log(ModelLineModelDescription);
    //             $("#selectModelNumberDiscri"+id+"Des"+row).select2
    //             ({
    //                 placeholder: 'Choose Model Number....     Or     Type Here To Search....',

    //                 allowClear: true,
    //                 data: ModelLineModelDescription
    //             });
    //         }
    //     });
    // }

</script>
