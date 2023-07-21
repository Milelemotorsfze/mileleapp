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
                        <select onchange=selectBrandDisp({{$i}},1) name="brand[{{$i}}][brand_id]" id="selectBrandMo{{$i}}" data-index="{{$i}}"
                                multiple="true" style="width: 100%;" class="brandRows">
                                <option id="allbrands" class="allbrands" value="allbrands" {{"yes" == $addonDetails->is_all_brands  ? 'selected' : ''}}>ALL BRANDS</option>
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
                        <button  class="btn_round removeButtonbrandMoDescrip" data-index="{{$i}}" style="float:right;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
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
                            @error('is_primary_payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-xxl-5 col-lg-5 col-md-12 model-description-dropdown" id="showModelNumberdrop{{$i}}Des1" hidden>
                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                            <select class="compare-tag1 model-descriptions" name="brand[{{$i}}][model][1][model_number][]" id="selectModelNumberDiscri{{$i}}Des1"
                                    multiple="true" style="width: 100%;">
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
                    <div class="col-xxl-12 col-lg-12 col-md-12 " id="showModelNumDel{{$i}}" class="delete-model-line-row">
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
                        <!-- <select onchange=selectBrandDisp({{$i}},1) name="brand[{{$i}}][brand_id]" id="selectBrandMo{{$i}}" data-index="{{$i}}"
                                multiple="true" style="width: 100%;" class="brandRows">
                                <option id="allbrands" class="allbrands" value="allbrands" {{"yes" == $addonDetails->is_all_brands  ? 'selected' : ''}}>ALL BRANDS</option>
                                <option class="{{$existingBrand->brands->id}}" value="{{$existingBrand->brands->id}}" selected locked="locked">{{$existingBrand->brands->brand_name}}</option>
                                @foreach($brands as $brand)
                                    <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                @endforeach
                        </select> -->
                        <select disabled onchange=selectBrandDisp({{$i}},1)  id="selectBrandMo{{$i}}" data-index="{{$i}}"
                                multiple="true" style="width: 100%;" class="brandRows">
                                <option id="allbrands" class="allbrands" value="allbrands" {{"yes" == $addonDetails->is_all_brands  ? 'selected' : ''}}>ALL BRANDS</option>
                                <option class="{{$existingBrand->brands->id}}" value="{{$existingBrand->brands->id}}" selected locked="locked">{{$existingBrand->brands->brand_name}}</option>
                                @foreach($brands as $brand)
                                    <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                @endforeach
                        </select>
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
                        <button  class="btn_round removeButtonbrandMoDescrip" data-index="{{$i}}" style="float:right;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <div hidden>{{$j=0;}}</div>
                @foreach($existingBrand->ModelLine as $ModelLine)
                <div id="rowDesCount{{$i}}" hidden value="{{$j+1}}">{{$j=$j+1;}}</div>
                <div class="MoDes{{$i}}" id="model-line-first-row">
                    <div class="row MoDesApndHere{{$i}}" id="row-spare-part-brand-{{$i}}-model-{{$j}}" >
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
                            @error('is_primary_payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-xxl-5 col-lg-5 col-md-12 model-description-dropdown" id="showModelNumberdrop{{$i}}Des{{$j}}" @if($ModelLine->is_all_model_lines == 'yes') hidden @endif >
                            <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                            <select class="compare-tag1 model-descriptions" name="brand[{{$i}}][model][{{$j}}][model_number][]" id="selectModelNumberDiscri{{$i}}Des{{$j}}"
                                    multiple="true" style="width: 100%;">
                                    @if(isset($ModelLine->allDes))
                                @foreach($ModelLine->allDes as $allDes)
                                    <option class="{{$ModelLine->brand_id}}" value="{{$allDes->id}}" @if(in_array(" $allDes->id ", $ModelLine->modeldes)) selected @endif
                                        >{{$allDes->model_description}}</option>
                                @endforeach
                                @endif
                            </select>
                            @error('is_primary_payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="row">
                    <div class="col-xxl-12 col-lg-12 col-md-12 " id="showModelNumDel{{$i}}" class="delete-model-line-row">
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
<div id="showaddtrimDis" class="col-xxl-12 col-lg-12 col-md-12" >
    <a id="addDis" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a>
</div>
</br>
<input type="hidden" id="indexValue" value="">
<input type="hidden" id="modelIndexValue" value="">

<script type="text/javascript">
    var selectedBrandsDisArr = [];
    // var existingBrandModel = {!! json_encode($existingBrandModel) !!};
    // var lengthExistingBrands = '';
    $(document).ready(function ()
    {
        // lengthExistingBrands = existingBrandModel.length;  
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
            hideOption(index,value);
            if(value == 'allbrands') {
                // var
                // alert("ok");
                var count = $(".MoDes" + index).find(".MoDesApndHere" + index).length;
                // check if supplier index count is > 1
                if (count > 0) {
                   // check if first model line of addon brand is having data or not
                    var confirm = alertify.confirm('You are not able to edit this field while any Items in Brand and Model Line.' +
                        'Please remove those items to edit this field.', function (e) {
                    }).set({title: "Remove Brands and ModelLines"})

                    // $("#selectBrandMo1 option:selected").prop("selected", false);
                    // $('#selectBrandMo1').trigger('change');
                }
            }
        });
        $(document.body).on('select2:unselect', ".brandRows", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            appendOption(index,data);
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
        });

        $(document.body).on('select2:unselect', ".spare-parts-model-lines", function (e) {
            var index = $(this).attr('data-index');
            var modelIndex = $(this).attr('data-model-index');
            var data = e.params.data;
            modelLineDataAppend(index,modelIndex,data);
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
        function modelLineDataAppend(index,modelIndex,data) {
            var indexValue = $(".MoDes"+index).find(".MoDesApndHere"+index).length;
            for (var i = 1; i <= indexValue; i++) {
                if (i != modelIndex) {
                    var currentId = 'selectModelLineNum'+ index +'Des' + i;
                    $('#'+currentId).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }

        function addOption(id,text) {
            var indexValue = $('#indexValue').val();
            for(var i=1;i<=indexValue;i++) {
                $('#selectBrandMo'+i).append($('<option>', {value: id, text :text}))
            }
        }
        $(document.body).on('click', ".removeButtonbrandMoDescrip", function (e) {
            var indexNumber = $(this).attr('data-index');

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
                $(this).find('.brandRows').attr('onchange','selectBrandDisp('+ index +',1)');
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
                    $("#selectModelNumberDiscri"+index+"Des"+i).select2
                    ({
                        placeholder: 'Choose Model Description....     Or     Type Here To Search....',
                        allowClear: true,
                    });

                }
                $(this).find(".MoDes"+oldIndex).attr('class', "MoDes"+index);
                $(this).find(".MoDesApndHere"+oldIndex).attr('class', "row MoDesApndHere"+index);
                $(this).find('.removeButtonModelItem').attr('data-index',indexNumber);
            });
        })
        $(document.body).on('click', ".removeButtonModelItem", function (e) {
            var indexNumber = $(this).attr('data-index');
            var modelIndex = $(this).attr('data-model-index');
            $(this).closest('#row-spare-part-brand-'+indexNumber+'-model-'+modelIndex).find("option:selected").each(function() {
                var id = (this.value);
                var text = (this.text);
                // addOption(id,text)
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
                                        <select onchange=selectBrandDisp(${index},1) name="brand[${index}][brand_id]" id="selectBrandMo${index}" data-index="${index}"
                                         multiple="true" style="width: 100%;" class="brandRows">
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
                                        <button  class="btn_round removeButtonbrandMoDescrip" data-index="${index}" style="float:right;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
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
                                            <div class="col-xxl-5 col-lg-5 col-md-12 model-description-dropdown" id="showModelNumberdrop${index}Des1" hidden>
                                                <label for="choices-single-default" class="form-label font-size-13">Choose Model Description</label>
                                                <select class="compare-tag1 model-descriptions" name="brand[${index}][model][1][model_number][]" id="selectModelNumberDiscri${index}Des1"
                                                multiple="true" style="width: 100%;">
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
                    <select class="compare-tag1 model-descriptions" name="brand[${supplier}][model][${index}][model_number][]" id="selectModelNumberDiscri${supplier}Des${index}"
                        multiple="true" style="width: 100%;">
{{--                        @foreach($modelLines as $modelLine)--}}
{{--                            <option class="{{$modelLine->brand_id}}" value="{{$modelLine->id}}">{{$modelLine->model_line}}</option>--}}
{{--                        @endforeach--}}
                    </select>
                    @error('is_primary_payment_method')
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-xxl-1 col-lg-1 col-md-12">
                    <button  class="btn_round removeButtonModelItem" data-index="${supplier}"  data-model-index="${index}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            </div>
         `);
        selectBrandDisp(supplier, index);
        $("#selectModelNumberDiscri"+supplier+"Des"+index).select2
        ({
            placeholder: 'Choose Model Description....     Or     Type Here To Search....',
            allowClear: true,
        });
        // $("#selectModelNumberDiscri" + supplier + "Des" + index).attr("data-placeholder", "Choose Model Description....     Or     Type Here To Search....");
    }

    function selectModelLineDescipt(id,row)
    {
        ifModelLineExist = $("#selectModelLineNum"+id+"Des"+row).val();
        showModelNumberDropdown(id,row);
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
                    hideRelatedModalDis(id,row);
                }
            }
            // else
            // {
            //     hideRelatedModalDis(id,row);
            // }
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
        let showDivdropDr = document.getElementById('showDivdropDr'+id+'Des'+row);
        showDivdropDr.hidden = false
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
                $("#selectModelLineNum"+id+"Des"+row).select2
                ({
                    placeholder: 'Choose Model Line....     Or     Type Here To Search....',
                    allowClear: true,
                    data: BrandModelLine,
                    maximumSelectionLength: 1,
                });

                $('#showModelNumberdrop'+id+'Des'+row).attr('hidden', false);
            }
        });
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
                // console.log(data);
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
                console.log(ModelLineModelDescription);
                $("#selectModelNumberDiscri"+id+"Des"+row).select2
                ({
                    placeholder: 'Choose Model Number....     Or     Type Here To Search....',

                    allowClear: true,
                    data: ModelLineModelDescription
                });
            }
        });
    }
    function hideRelatedModalDis(id,row)
    {
        let showDivdropModelLine = document.getElementById('showDivdropDr'+id+'Des'+row);
        showDivdropModelLine.hidden = true
        let showDivdropDr = document.getElementById('showDivdropDr'+id+'Des'+row);
        showDivdropDr.hidden = true
        let showaddtrim = document.getElementById('showaddtrimDis');
        showaddtrim.hidden = true
    }
    function hideModelNumberDropdown(id,row)
    {
        let showPartNumber = document.getElementById('showModelNumberdrop'+row);
        showPartNumber.hidden = true
    }
</script>
