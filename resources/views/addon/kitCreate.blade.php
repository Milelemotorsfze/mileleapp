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
                                    <select class="mainItem form-control widthinput MainItemsClass" name="mainItem[1][item]" id="mainItem1" 
                                            multiple="true" style="width: 100%;" data-index="1">
                                            @foreach($kitItemDropdown as $kitItemDropdownData)
                                                <option value="{{$kitItemDropdownData->id}}">{{$kitItemDropdownData->addon_code}} ( {{$kitItemDropdownData->AddonName->name}} )</option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="col-xxl-1 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                                    <span class="error">* </span>
                                    <label for="choices-single-default" class="form-label font-size-13 ">Quantity</label>
                                    <input name="mainItem[1][quantity]" id="mainQuantity1" placeholder="Enter Quantity" type="number" value="1" min="1" 
                                            class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror quantityMainItem" autofocus 
                                            oninput="validity.valid||(value='1');">
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
<script type="text/javascript">
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
            disableDropdown();
        });
        $(document.body).on('select2:unselect', ".MainItemsClass", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            MainKitItemAppendOption(index,data);
            enableDropdown();
        });
    });
        function MainKitItemHideOption(index,value) {
            alert(index);
            alert(value);
            var indexValue = $('#MainKitItemIndex').val();
            for (var i = 1; i <= indexValue; i++) {
                if (i != index) {
                    var currentId = 'mainItem' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        function MainKitItemAppendOption(index,data) {
            var indexValue = $('#MainKitItemIndex').val();
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#mainItem'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }
    $(document.body).on('click', ".removeMainItem", function (e) 
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
            alert(index);
            $('#mainItem'+index).select2
            ({
                placeholder:"Choose Items....     Or     Type Here To Search....",
                allowClear: true,
                maximumSelectionLength: 1,
            });
        });
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
        var selectedItems = [];
        for(let i=1; i<index; i++)
        {
            var eachSelectedAddon = $('#mainItem'+i).val();
            if(eachSelectedAddon) {
                selectedItems.push(eachSelectedAddon);
            }
        }
        $.ajax({
            url:"{{url('getKitItemsForAddon')}}",
            type: "POST",
            data:
                {
                    filteredArray: selectedItems,
                    _token: '{{csrf_token()}}'
                },
            dataType : 'json',
            success: function(data) {
                myarray = data;
                var size = myarray.length;
                if (size >= 1) {
                    $(".apendNewaMainItemHere").append(`
                        <div class="row kitMainItemRowForSupplier kititemdelete" id="item-${index}">
                            <div class="col-xxl-10 col-lg-6 col-md-12">
                                <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                                <select class="mainItem MainItemsClass" name="mainItem[${index}][item]" id="mainItem${index}" multiple="true"
                                 style="width: 100%;" data-index="${index}">
                                    @foreach($kitItemDropdown as $kitItemDropdownData)
                                <option value="{{$kitItemDropdownData->id}}">{{$kitItemDropdownData->addon_code}} ( {{$kitItemDropdownData->AddonName->name}} )</option>
                                    @endforeach
                                </select>                               
                                </div>
                                <div class="col-xxl-1 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                                    <label for="choices-single-default" class="form-label font-size-13 ">Quantity</label>
                                    <input name="mainItem[${index}][quantity]" id="mainQuantity${index}"
                                     type="number" value="1" min="1" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror quantityMainItem"
                                     placeholder="Enter Quantity" autocomplete="addon_purchase_price_in_usd" autofocus
                                     oninput="validity.valid||(value='1');">
                                </div>
                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                <a id="removeMainItem${index}" class="btn_round removeMainItem" data-index="${index}">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    `);
                    let addonDropdownData   = [];
                    $.each(data,function(key,value)
                    {
                        addonDropdownData.push
                        ({

                            id: value.id,
                            text: value.addon_code +' ('+value.addon_name.name +')'
                        });
                    });
                    $('#mainItem'+index).html("");
                    $('#mainItem'+index).select2
                    ({
                        placeholder:"Choose Items....     Or     Type Here To Search....",
                        allowClear: true,
                        data: addonDropdownData,
                        maximumSelectionLength: 1,
                    });
                }
            }
        });
    }
</script>