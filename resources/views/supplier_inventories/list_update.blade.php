@extends('layouts.table')
@section('content')
<style>
    .input-width{
        min-height:34px;
    }
 </style>
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list-view-all');
    @endphp
    @if ($hasPermission)
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                Inventory Stock
            </h4>
                <div class="ml-auto float-end">
                    @can('supplier-inventory-list')
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list');
                        @endphp
                        @if ($hasPermission)
                            <a href="{{ route('supplier-inventories.index') }}" class="btn btn-soft-green me-md-2 btn-sm"><i class="fa fa-th-large"></i></a>
                        @endif
                    @endcan
                    @can('supplier-inventory-edit')
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-edit');
                        @endphp
                        @if ($hasPermission)
                             <a  class="btn btn-sm btn-info float-end update-inventory-btn" href="#" > Update</a>
                        @endif
                    @endcan
                </div>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br>
                    <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-success" id="success-alert">
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                    {{ Session::get('success') }}
                </div>
            @endif
            <form id="form-list" action="{{route('supplier-inventories.view-all')}}" >
                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Vendor</label>
                            <select class="form-control input-width" data-trigger name="supplier_id" id="supplier">
                                <option value="" >Select The Vendor</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $supplier->id == request()->supplier_id ? 'selected'  : ''}}>
                                        {{ $supplier->supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Dealer</label>

                                <select class="form-control input-width" data-trigger name="dealers" >
                                    <option value="" >Select The Dealer</option>
                                    <option value="{{ \App\Models\SupplierInventory::DEALER_TRANS_CARS }}"
                                        {{  \App\Models\SupplierInventory::DEALER_TRANS_CARS == request()->dealers ? 'selected'  : ''}}>Trans Cars</option>
                                    <option value="{{ \App\Models\SupplierInventory::DEALER_MILELE_MOTORS }}"
                                     {{ \App\Models\SupplierInventory::DEALER_MILELE_MOTORS == request()->dealers ? 'selected'  : '' }}>Milele Motors</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit"  class="btn btn-primary mt-4 search">Search</button>
                        <a href="{{route('supplier-inventories.view-all')}}">
                            <button type="button"  class="btn btn-info mt-4 ">Refresh</button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive p-2">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>S.No</th>
                    <th>Country</th>
                    <th>Dealer</th>
                    <th>Vendor</th>
                    <th>Model</th>
                    <th>SFX</th>
                    <th>Model Year</th>
                    <th>Variant</th>

                    <th>Chasis</th>
                    <th>Engine Number</th>
                    <th>Color Code</th>
                    <th>Interior Color</th>
                    <th>Exterior Color</th>
                    <th>ETA Import Date</th>
                    <th>Production Month</th>
                    <th>DN Number</th>
                    <th>LOI</th>
                    <th>PFI Number </th>
                    <th>PO Number</th>
                    <th>PO AMS</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                    @foreach ($supplierInventories as $key => $supplierInventory)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>
                                <select class="country" data-field="country" data-id="{{ $supplierInventory->id }}" id="country-editable-{{$supplierInventory->id}}">
                                    <option value="UAE" {{ $supplierInventory->country == 'UAE' ? 'selected' : '' }} >UAE</option>
                                    <option value="Belgium" {{ $supplierInventory->country == 'Belguim' ? 'selected' : '' }}>Belgium</option>
                                </select>
                            </td>
                            <td>
                                <select class="whole_sales" data-field="whole_sales" data-id="{{ $supplierInventory->id }}" id="whole_sales-editable-{{$supplierInventory->id}}">
                                    <option value="{{ \App\Models\SupplierInventory::DEALER_TRANS_CARS }}"
                                        {{ $supplierInventory->whole_sales == \App\Models\SupplierInventory::DEALER_TRANS_CARS ? 'selected' : ''}} >
                                        Trans Cars </option>
                                    <option value="{{\App\Models\SupplierInventory::DEALER_MILELE_MOTORS}}"
                                        {{ $supplierInventory->whole_sales == \App\Models\SupplierInventory::DEALER_MILELE_MOTORS ? 'selected' : ''}} >
                                        Milele Motors </option>
                                </select>
                            </td>
                            <td>
                                <select  class="supplier" data-field="supplier_id" data-id="{{ $supplierInventory->id }}" id="supplier_id-editable-{{$supplierInventory->id}}">
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $supplierInventory->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->supplier }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>{{ $supplierInventory->masterModel->model ?? '' }}</td>
                            <td> {{ $supplierInventory->masterModel->sfx ?? '' }}</td>
                            <td>
                                <select  class="model-year" data-field="model_year" data-id="{{ $supplierInventory->id }}" id="model_year-editable-{{$supplierInventory->id}}">
                                   @foreach($supplierInventory->modelYears as $modelYear)
                                       <option value="{{ $modelYear }}" {{ $modelYear == $supplierInventory->masterModel->model_year ? 'selected' : '' }}>
                                           {{ $modelYear }}
                                       </option>
                                   @endforeach
                                </select>
                            </td>
                            <td> {{ $supplierInventory->masterModel->variant->name ?? '' }}</td>

                            <td data-field="chasis" id="chasis-editable-{{$supplierInventory->id}}" contenteditable="true" data-id="{{$supplierInventory->id}}" >
                                {{ $supplierInventory->chasis }}</td>
                            <td  data-field="engine_number" id="engine_number-editable-{{$supplierInventory->id}}"
                                 contenteditable="true" data-id="{{$supplierInventory->id}}" > {{ $supplierInventory->engine_number ?? '' }}</td>
                            <td  data-field="color_code" id="color_code-editable-{{$supplierInventory->id}}"
                                 contenteditable="true" data-id="{{$supplierInventory->id}}">{{ $supplierInventory->color_code }}</td>
                            <td>{{ $supplierInventory->interiorColor->name ?? '' }}</td>
                            <td>{{ $supplierInventory->exteriorColor->name ?? '' }}</td>
                            <td class="eta-import">
                                <input type="date" class="eta-import form-control" data-field="eta_import" id="eta_import-editable-{{$supplierInventory->id}}"
                                       data-id="{{$supplierInventory->id}}" value="{{ $supplierInventory->eta_import }}" >
                            </td>
                            <td data-field="pord_month" class="pord_month"  id="pord_month-editable-{{$supplierInventory->id}}"  contenteditable="true"
                                data-id="{{$supplierInventory->id}}" >{{$supplierInventory->pord_month}}</td>
                            <td data-field="delivery_note"  id="delivery_note-editable-{{$supplierInventory->id}}"  contenteditable="true"
                                data-id="{{$supplierInventory->id}}" >{{$supplierInventory->delivery_note}} </td>
                            <td>{{ $supplierInventory->letterOfIndentItem->uuid ?? '' }}</td>
                            <td> {{ $supplierInventory->pfi->pfi_reference_number ?? '' }} </td>
                            <td> {{ $supplierInventory->purchaseOrder->po_number ?? ''}} </td>
                            <td data-field="po_arm" class="po_arm"  id="po_arm-editable-{{$supplierInventory->id}}"  contenteditable="true"
                                data-id="{{$supplierInventory->id}}" >{{ $supplierInventory->po_arm }}</td>

                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection
@push('scripts')
    <script type="text/javascript">
        var updatedData = [];
        var feildValidInput = true;

        $(document).ready(function () {
            var table = $('#dtBasicExample3').DataTable();
            $('#dtBasicExample3 tbody').on('keyup', 'td', function () {
                var id = $(this).data('id');
                var field = $(this).data('field');
                validData(field,id);
                if(feildValidInput == true) {
                    addUpdatedData(id,field);
                }
            });

            $('#dtBasicExample3 tbody td').on('change', '.model-year', function () {

                var id = $(this).data('id');
                var field = $(this).data('field');
                if(feildValidInput == true) {
                    addUpdatedData(id, field);
                }
            });
            $('#dtBasicExample3 tbody td').on('change', '.supplier', function () {
                var id = $(this).data('id');
                var field = $(this).data('field');
                if(feildValidInput == true) {
                    addUpdatedData(id, field);
                }
            });
            $('#dtBasicExample3 tbody td').on('change', '.eta-import', function () {
                var id = $(this).data('id');
                var field = $(this).data('field');
                if(feildValidInput == true) {
                    addUpdatedData(id, field);
                }
            });
            $('#dtBasicExample3 tbody td').on('change', '.country', function () {
                var id = $(this).data('id');
                var field = $(this).data('field');
                if(feildValidInput == true) {
                    addUpdatedData(id, field);
                }
            });
            $('#dtBasicExample3 tbody td').on('change', '.whole_sales', function () {
                var id = $(this).data('id');
                var field = $(this).data('field');

                if(feildValidInput == true) {
                    addUpdatedData(id, field);
                }
            });

            function validData(field,id) {

                if(field == 'pord_month') {
                    let InputId = 'pord_month-editable-'+id;
                    let value = $('#'+InputId).text();

                    if($.isNumeric(value) == true){
                        feildValidInput = true;
                        removeValidationError(InputId);

                        if(value.length != 6) {
                            $msg = "Characters length should be 6";
                            showValidationError(InputId,$msg);

                        }else {

                            let url = '{{ route('supplier-inventories.checkProductionMonth') }}';
                            $.ajax({
                                type:"GET",
                                url: url,
                                data: {
                                    prod_month: value,
                                    id:id,
                                },
                                dataType : 'json',
                                success: function(data) {
                                    if(data !== 1) {
                                        $msg = 'The model,sfx and the requested model year ('+ data +') combination not existing in the system.';
                                        showValidationError(InputId, $msg);
                                    }else{
                                        removeValidationError(InputId);
                                    }
                                }
                            });
                            removeValidationError(InputId);
                        }
                        // removePorductionMonthError(id);
                    }else{
                        $msg = "Only Numeric is allowed";
                        showValidationError(InputId,$msg);
                    }
                }else if(field == 'chasis') {
                    let InputId = 'chasis-editable-'+id;

                    let url = '{{ route('supplier-inventories.unique-chasis') }}';
                    let chasis = $('#'+InputId).text();
                    let inventoryId = $('#'+InputId).attr('data-id');
                    console.log(inventoryId);
                    if(chasis.length > 0) {
                        $.ajax({
                        type:"GET",
                        url: url,
                        data: {
                            inventoryId: inventoryId,
                            chasis: chasis,
                        },
                        dataType : 'json',
                        success: function(data) {

                            if(data == 1) {
                                $msg = "This chasis is already existing";
                                showValidationError(InputId, $msg);
                            }else{
                                removeValidationError(InputId);
                            }
                        }
                        });
                    }

                }else if(field == 'color_code') {

                    let url = '{{ route('supplier-inventories.isExistColorCode') }}';
                    let InputId = 'color_code-editable-'+id;
                    let colorCode = $('#'+InputId).text();

                    if(colorCode.length > 5 ) {
                        $msg = "Maximum length is 5";
                        showValidationError(InputId,$msg);

                    }else if(colorCode.length < 4) {
                        $msg = "Minimum length is 4";
                        showValidationError(InputId,$msg);
                    }else{
                        console.log("lenght is ok");
                        removeValidationError(InputId);
                    }

                    if(colorCode.length == 5 || colorCode.length == 4) {
                        $.ajax({
                            type:"GET",
                            url: url,
                            data: {
                                color_code: colorCode,
                            },
                            dataType : 'json',
                            success: function(data) {
                                if(data == 0) {
                                    $msg = "This color code is not existing in our master Color Codes.";
                                    showValidationError(InputId, $msg);
                                }else{
                                    console.log("colour code existing");
                                    removeValidationError(InputId);
                                }
                            }
                        });
                    }
                }else if(field == 'delivery_note') {
                    let InputId = 'delivery_note-editable-'+id;
                    let deliveryNote = $('#'+InputId).text();

                    if($.isNumeric(deliveryNote)) {
                        if(deliveryNote.length < 5) {
                            $msg = "Delivey Note minimum length should be 5";
                            showValidationError(InputId,$msg);
                        }else {
                            removeValidationError(InputId);
                        }
                    }else{
                        if(deliveryNote != 'waiting' && deliveryNote != 'WAITING'){
                            $msg = "Only Waiting status is allowed or any DN Numer can update.";
                            showValidationError(InputId,$msg);
                        }else {
                            removeValidationError(InputId);
                        }
                    }
                }
            }

             $('.update-inventory-btn').on('click', function () {
                 if(feildValidInput == true) {
                     var selectedUpdatedDatas = [];

                     $.each(updatedData,function(key,value) {
                         var splitValue = value.split('-');
                         var cellId = splitValue[1] +'-editable-' + splitValue[0];
                         console.log(cellId);

                         if(splitValue[1] == 'model_year' || splitValue[1] == 'supplier_id' ||  splitValue[1] == 'eta_import'
                         || splitValue[1] == 'country' || splitValue[1] == 'whole_sales' ) {
                             var cellValue = $('#'+ cellId).val();
                         }else{
                             var cellValue = $('#'+ cellId).text();
                         }
                         console.log(cellValue);
                         selectedUpdatedDatas.push({id: splitValue[0],field: splitValue[1], value: cellValue});

                     });

                     let url = '{{ route('update-inventory') }}';
                     $.ajax({
                         type:"POST",
                         url: url,
                         data: {
                             selectedUpdatedDatas:  selectedUpdatedDatas,
                             _token: '{{csrf_token()}}'
                         },
                         dataType : 'json',
                         success: function(data) {
                            //  console.log("success");
                             alertify.success('Inventory Updated Successfully.');
                             location.reload();
                         }
                     });

                 }else{
                     alertify.confirm('Please check Validation Errors!');
                 }
             });
        });
        function addUpdatedData(id,field) {
            var arrayvalue = id + '-' + field;

            if ($.inArray(arrayvalue, updatedData) == -1) {
                updatedData.push(arrayvalue);
            }
        }
        function showValidationError(id,$msg){

            feildValidInput = false;
            $('#'+id).attr('title', $msg);
            $('#'+id).css('color', 'red');
            alertify.error($msg);
        }
        function removeValidationError(id){

            feildValidInput = true;
            $('#'+id).attr('title', "");
            $('#'+id).css('color', 'black');
        }
    </script>
@endpush




