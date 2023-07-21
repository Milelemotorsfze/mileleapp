<div class="col-md-12 p-0 brandModelLineClass" id="brandModelLineId">
    <div class="col-md-12 brandModelLineDiscription p-0">
        <div hidden>{{$i=0;}}</div>
        @if($addonDetails->is_all_brands == "yes")
            <div id="rowIndexCount" hidden value="{{$i+1}}">{{$i=$i+1;}}</div>
            <div class="row brandModelLineDiscriptionApendHere dynamic-rows" id="row-{{$i}}">
                <div class="row">
                    <div class="col-xxl-4 col-lg-6 col-md-12">
                        <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                        <select onchange=selectBrand(this.id,{{$i}}) name="brandModel[{{$i}}][brand_id]" class="brands" data-index="{{$i}}" id="selectBrand{{$i}}" 
                            multiple="true" style="width: 100%;" required>
                            <option id="allbrands" class="allbrands" value="allbrands" {{"yes" == $addonDetails->is_all_brands  ? 'selected' : ''}}>ALL BRANDS</option>
                                @foreach($brands as $brand)
                                    <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                @endforeach
                        </select>
                        <span id="brandError" class=" invalid-feedback"></span>
                    </div> 
                    <div class="col-xxl-4 col-lg-6 col-md-12 model-line-div" id="showDivdrop{{$i}}" hidden>
                        <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                        <select class="compare-tag1 model-lines" name="brandModel[{{$i}}][modelline_id][]" data-index="{{$i}}" id="selectModelLine{{$i}}"  multiple="true" 
                            style="width: 100%;" required>
                        </select>
                    </div>
                    @if($i != 1)
                    <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                        <button class="btn_round removeButtonbrandModelLineDiscription" data-index="{{$i}}" >
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        @else
        @foreach($existingBrandModel as $existingBrand)
            <div id="rowIndexCount" hidden value="{{$i+1}}">{{$i=$i+1;}}</div>
            <div class="row brandModelLineDiscriptionApendHere dynamic-rows" id="row-{{$i}}">
                <div class="row">
                    <div class="col-xxl-4 col-lg-6 col-md-12">
                        <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                        <!-- <select onchange=selectBrand(this.id,{{$i}}) name="brandModel[{{$i}}][brand_id]" class="brands" data-index="{{$i}}" id="selectBrand{{$i}}" 
                            multiple="true" style="width: 100%;" required>
                            <option id="allbrands" class="allbrands" value="allbrands" {{"yes" == $addonDetails->is_all_brands  ? 'selected' : ''}}>ALL BRANDS</option>
                                <option class="{{$existingBrand->brands->id}}" value="{{$existingBrand->brands->id}}" selected locked="locked">{{$existingBrand->brands->brand_name}}</option>
                                @foreach($brands as $brand)
                                    <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                @endforeach
                        </select> -->
                        <select onchange=selectBrand(this.id,{{$i}})  class="brands" data-index="{{$i}}" id="selectBrand{{$i}}" 
                            multiple="true" style="width: 100%;" required disabled>
                            <option id="allbrands" class="allbrands" value="allbrands" {{"yes" == $addonDetails->is_all_brands  ? 'selected' : ''}}>ALL BRANDS</option>
                                <option class="{{$existingBrand->brands->id}}" value="{{$existingBrand->brands->id}}" selected locked="locked">{{$existingBrand->brands->brand_name}}</option>
                                @foreach($brands as $brand)
                                    <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                @endforeach
                        </select>
                        <input hidden value="{{$existingBrand->brands->id}}" name="brandModel[{{$i}}][brand_id]">
                        <span id="brandError" class=" invalid-feedback"></span>
                    </div> 
                    <div class="col-xxl-4 col-lg-6 col-md-12 model-line-div" id="showDivdrop{{$i}}">
                        <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                        <select class="compare-tag1 model-lines" name="brandModel[{{$i}}][modelline_id][]" data-index="{{$i}}" id="selectModelLine{{$i}}"  multiple="true" 
                            style="width: 100%;" required>
                            <option value="allmodellines" {{"yes" == $existingBrand->is_all_model_lines  ? 'selected' : 'disabled'}}>ALL Model Lines</option>
                            @foreach($existingBrand->ModalLines as $modelLine)
                            <option value="{{ $modelLine->id }}" @if(in_array(" $modelLine->id ", $existingBrand->modelLinesData)) selected @endif 
                                @if($existingBrand->is_all_model_lines == "yes") disabled @endif
                            >{{ $modelLine->model_line }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($i != 1)
                    <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                        <button class="btn_round removeButtonbrandModelLineDiscription" data-index="{{$i}}" >
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
    <div id="showaddtrim" class="col-xxl-12 col-lg-12 col-md-12" hidden>
        <a id="add" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a>
    </div>
    <input type="hidden" value="" id="index">
</div>
<script type="text/javascript">
    var existingBrandModel = {!! json_encode($existingBrandModel) !!};
    var lengthExistingBrands = '';
    $(document).ready(function ()
    {
        lengthExistingBrands = existingBrandModel.length;
        if(lengthExistingBrands == 0)
        {
                $("#selectBrand1").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
                $("#selectBrand1").select2({
                    maximumSelectionLength: 1,
                });
                $("#selectModelLine1").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
                $("#selectModelLine1").select2();
        }
        else
        {
            let showaddtrim = document.getElementById('showaddtrim');
            showaddtrim.hidden = false
            for(let i=1; i<=lengthExistingBrands; i++)
            {   
                $("#selectBrand"+i).attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
                $("#selectBrand"+i).select2({
                    maximumSelectionLength: 1,
                });
                $("#selectModelLine"+i).attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
                $("#selectModelLine"+i).select2();
            }
        }
        $(document.body).on('select2:select', "#selectBrand1", function (e) {
            e.preventDefault();
            var value = $(this).val();
            if(value == "allbrands") {
                var count = $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
                // check each item have data or not?
                if(count > 1) {
                    var isSubRowEmpty = [];
                    for(let i=2; i<=count; i++)
                    {
                        var eachBrand = $('#selectBrand'+i).val();
                        if(eachBrand != '') {
                            // if any data then show alert.
                            var confirm = alertify.confirm('You are not able to edit this field while any Items in Brand and Model Line.' +
                                'Please remove those items to edit this field.',function (e) {
                            }).set({title:"Remove Brands and ModelLines"})
                            $("#selectBrand1 option:selected").prop("selected", false);
                            $("#selectBrand1").trigger('change');
                        }else{
                            isSubRowEmpty.push(1);
                        }
                    }
                    var subRowCount = count - 1;
                    if(isSubRowEmpty.length == subRowCount ) {
                        $(".brandModelLineDiscription").find(".dynamic-rows").remove();
                    }
                }
            }
        })

        var index = 1;

        $(document.body).on('select2:select', ".model-lines", function (e) {
            var value = $(this).val();
            var index = $(this).attr('data-index');
            optionDisable(index, value);

        });
         function optionDisable(index, value){
             var currentId = 'selectModelLine'+index;
             if(value == 'allmodellines') {
                 $('#' + currentId +' option').not(':selected').attr('disabled', true);
             }else{
                 $('#' + currentId + ' option[value=allmodellines]').prop('disabled', true)
             }
         }

        $(document.body).on('select2:unselect', ".model-lines", function (e) {
            var index = $(this).attr('data-index');
            var currentId = 'selectModelLine'+index;
            var data = e.params.data.id;
            optionEnable(currentId,data);

        });
         function optionEnable(currentId,data) {
             if(data == 'allmodellines') {
                 $('#' + currentId + ' option').prop('disabled', false);
             }else {
                $values = '';
                $values =  $('#'+currentId).val();
                if($values == '')
                {
                    $('#' + currentId + ' option[value=allmodellines]').prop('disabled', false);
                }
             }
         }

        $(document.body).on('select2:select', ".brands", function (e) {

            var index = $(this).attr('data-index');
            var value = e.params.data.id;
            hideOption(index,value);
            disableDropdown();

        });
        function hideOption(index,value) {
            var indexValue =  $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
            for (var i = 1; i <= indexValue; i++) {
                if (i != index) {
                    var currentId = 'selectBrand' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        $(document.body).on('select2:unselect', ".brands", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            appendOption(index,data);
            enableDropdown();
        });
        function appendOption(index,data) {
            var indexValue =  $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#selectBrand'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }
        function addOption(id,text) {
            var indexValue =  $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length;
            for(var i=1;i<=indexValue;i++) {
                $('#selectBrand'+i).append($('<option>', {value: id, text :text}))
            }
        }
        //===== delete the form fieed row
        $(document.body).on('click', ".removeButtonbrandModelLineDiscription", function (e)
            // $("body").on("click", ".", function ()
        {
            var indexNumber = $(this).attr('data-index');
            $(this).closest('#row-'+indexNumber).find("option:selected").each(function() {
                var id = (this.value);
                var text = (this.text);
                addOption(id,text)
            });
            $(this).closest('#row-'+indexNumber).remove();

            $('.brandModelLineDiscriptionApendHere').each(function(i) {
                var index = +i + +1;
                $(this).attr('id','row-'+index);
                $(this).find('.brands').attr('onchange', 'selectBrand(this.id,'+ index +')');
                $(this).find('.brands').attr('name', 'brandModel['+ index +'][brand_id]');
                $(this).find('.brands').attr('id', 'selectBrand'+index);
                $(this).find('.brands').attr('data-index',index);
                $(this).find('.model-line-div').attr('id','showDivdrop'+index);
                $(this).find('.model-lines').attr('name','brandModel['+ index +'][modelline_id][]');
                $(this).find('.model-lines').attr('id','selectModelLine'+index);
                $(this).find('.model-lines').attr('data-index',index);
                $(this).find('.removeButtonbrandModelLineDiscription').attr('data-index',index);
                $('#selectBrand'+index).select2
                ({
                    placeholder:"Choose Brands....     Or     Type Here To Search....",
                    allowClear: true,
                    maximumSelectionLength: 1,
                    minimumResultsForSearch: -1,
                });
                $("#selectModelLine"+index).attr("data-placeholder","Choose Model Line....     Or     Type Here To Search....");
                $("#selectModelLine"+index).select2();
            })
            enableDropdown();

        })
        $("#add").on("click", function ()
        {
            // $('#allbrands').prop('disabled',true);
            var index = $(".brandModelLineDiscription").find(".brandModelLineDiscriptionApendHere").length + 1;
            $('#index').val(index);
            var selectedAddonBrands = [];
            for(let i=1; i<index; i++)
            {
                var eachSelectedBrand = $('#selectBrand'+i).val();
                if(eachSelectedBrand) {
                    selectedAddonBrands.push(eachSelectedBrand);
                }
            }

            $.ajax({
                url:"{{url('getBranchForWarranty')}}",
                type: "POST",
                data:
                    {
                        filteredArray: selectedAddonBrands,
                        _token: '{{csrf_token()}}'
                    },
                dataType : 'json',
                success: function(data) {
                    myarray = data;
                    var size = myarray.length;
                    if (size >= 1) {
                        $(".brandModelLineDiscription").append(`
                            <div class="row brandModelLineDiscriptionApendHere dynamic-rows" id="row-${index}">
                                <div class="row">
                                    <div class="col-xxl-4 col-lg-6 col-md-12">
                                        <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                                        <select onchange=selectBrand(this.id,${index}) name="brandModel[${index}][brand_id]" class="brands"
                                          data-index="${index}" id="selectBrand${index}" multiple="true" style="width: 100%;" required>
                                            @foreach($brands as $brand)
                                    <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="col-xxl-4 col-lg-6 col-md-12 model-line-div" id="showDivdrop${index}" hidden>
                                        <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                                        <select class="compare-tag1 model-lines" name="brandModel[${index}][modelline_id][]" data-index="${index}"
                                        id="selectModelLine${index}"  multiple="true" style="width: 100%;" required>
                                        </select>
                                    </div>
                                    <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                        <button class="btn_round removeButtonbrandModelLineDiscription" data-index="${index}" >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
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
                        $('#selectBrand'+index).html("");
                        $('#selectBrand'+index).select2
                        ({
                            placeholder:"Choose Brands....     Or     Type Here To Search....",
                            allowClear: true,
                            data: brandDropdownData,
                            maximumSelectionLength: 1,
                        });
                    }
                }
            });

            $("#selectModelLine"+index).attr("data-placeholder","Choose Model Line....     Or     Type Here To Search....");
            $("#selectModelLine"+index).select2();
        });
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
        // alert("div");
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
    function hideModelNumberDropdown(id,row)
    {
        let showPartNumber = document.getElementById('showModelNumberdrop'+row);
        showPartNumber.hidden = true
    }
    $(function() {
   $('#selectBrand1').select2({
   	 tags: true,
     placeholder: 'Select an option',
     templateSelection : function (tag, container){
     		// here we are finding option element of tag and
        // if it has property 'locked' we will add class 'locked-tag' 
        // to be able to style element in select
      	var $option = $('#selectBrand1 option[value="'+tag.id+'"]');
        if ($option.attr('locked')){
           $(container).addClass('locked-tag');
           tag.locked = true; 
        }
        return tag.text;
     },
   })
   .on('select2:unselecting', function(e){
   		// before removing tag we check option element of tag and 
      // if it has property 'locked' we will create error to prevent all select2 functionality
       if ($(e.params.args.data.element).attr('locked')) {
        var confirm = alertify.confirm('You are not able to remove this Brand, remove its model lines first then remove brand or delete the row',function (e) {
                   }).set({title:"Not Able to Remove"})
           e.preventDefault();
        }
     });
});
</script>
