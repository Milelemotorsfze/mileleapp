<div class="col-md-12 p-0 brandModelLineClass" id="brandModelLineId">
    <div class="col-md-12 brandModelLineDiscription p-0">
        <div class="row brandModelLineDiscriptionApendHere" id="row-1">
            <div class="row">
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <span class="error">* </span>
                    <label for="choices-single-default" class="form-label font-size-13">Choose Brand Name</label>
                    <select onchange=selectBrand(this.id,1) name="brandModel[1][brand_id]" id="selectBrand1"
                            data-index="1" class="brands" multiple="true" style="width: 100%;">
                        <option id="allbrands" class="allbrands" value="allbrands">ALL BRANDS</option>
                        @foreach($brands as $brand)
                            <option class="{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                        @endforeach
                    </select>
                    <span id="brandError" class=" invalid-feedback"></span>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-12 model-line-div" id="showDivdrop1" hidden>
                    <span class="error">* </span>
                    <label for="choices-single-default" class="form-label font-size-13">Choose Model Line</label>
                    <select class="compare-tag1 model-lines" name="brandModel[1][modelline_id][]" id="selectModelLine1" data-index="1" multiple="true"
                            style="width: 100%;">
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div id="showaddtrim" class="col-xxl-12 col-lg-12 col-md-12" hidden>
        <a id="add" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a>
    </div>
    <input type="hidden" value="" id="index">
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#selectBrand1").attr("data-placeholder","Choose Brand Name....     Or     Type Here To Search....");
        $("#selectBrand1").select2({
            maximumSelectionLength: 1,
        });

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
                 $('#' + currentId + ' option[value=allmodellines]').prop('disabled', false)
             }
         }

        $(document.body).on('select2:select', ".brands", function (e) {
            var index = $(this).attr('data-index');
            var value = e.params.data.id;
            hideOption(index,value);
            disableDropdown();
        });
        function hideOption(index,value) {
            var indexValue = $('#index').val();
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
            var indexValue = $('#index').val();
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#selectBrand'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }
        function addOption(id,text) {
            var indexValue = $('#index').val();
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
                    minimumResultsForSearch: -1,
                });
                $("#selectModelLine"+index).attr("data-placeholder","Choose Model Line....     Or     Type Here To Search....");
                $("#selectModelLine"+index).select2();
            })
            enableDropdown();

        })
        $("#add").on("click", function ()
        {
            $('#allbrands').prop('disabled',true);
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
                            <div class="row brandModelLineDiscriptionApendHere" id="row-${index}">
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
</script>
