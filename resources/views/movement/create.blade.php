@extends('layouts.main')
@section('content')
<style>
    .error, p.is-invalid {
        color: red;
    }

    p.is-invalid {
        margin-top: 12px;
    }
    .select2-dropdown.select2-dropdown--below {
        position: relative !important;
        z-index: 3 !important;
    }
    .is-invalid.invalid-feedback {
        margin-top: 10px;
    }
    .input-group .select-so .select2-container--default,
    .input-group .select-po .select2-container--default {
        width: 150px !important;
    }
    .select-po.select2-container, .select-so.select2-container {
        margin: 0px 0px 10px 0px !important;
    }
    .warehouse-from-location {
        color: #AEB5BD !important;
    }
</style>
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-daily-movemnets');
    @endphp
    @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">Add New Movement Transaction</h4>
        <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div>
            @if ($lastIdExists)
                <a class="btn btn-sm btn-info" href="{{ route('movement.lastReference', ['currentId' => ($movementsReferenceId - 1)]) }}">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
            @endif
        <b>Ref No: {{$movementsReferenceId}}</b>
        @if ($NextIdExists)
            <a class="btn btn-sm btn-info" href="{{ route('movement.lastReference', ['currentId' => ($movementsReferenceId + 1)]) }}">
            <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </a>
        @endif
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
    </div>
    <div class="card-body">
    <div class="col-lg-12">
    <div id="flashMessage"></div>
</div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger" >
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('error') }}
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('success') }}
            </div>
        @endif

        <form id="formCreate" action="{{ route('movement.store') }}" method="POST"  enctype="multipart/form-data" >
        @csrf
        <div class="row">
        <div class="col-lg-4 col-md-6">
        <span class="error">* </span>
        <label for="basicpill-firstname-input" class="form-label">Date : </label>
        <input type="Date" id="date" name="date" class="form-control" placeholder="PO Date" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>
        </div>
        <br>
        <div class="row">
        <div class="col-lg-4 col-md-6">
        <div class="form-group">
    <label for="vin_file">Upload VIN File:</label>
    <input type="file" id="vin_file" name="file" class="form-control" />
</div>
<br>
<button id="upload-vin-button" class="btn btn-primary">Upload VIN File</button>
    </div>
    </div>
<br>
        <div id ="rows-containertitle">
            <div class="row">
                <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Vin</label>
                </div>
                <div class="col-lg-1 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">PO</label>
                </div>
                <div class="col-lg-1 col-md-6" >
                    <label for="basicpill-firstname-input" class="form-label">SO</label>
                </div>
                <div class="col-lg-1 col-md-6" >
                    <label for="basicpill-firstname-input" class="form-label">Ownership</label>
                </div>
                <div class="col-lg-1 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>From</label>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>To </label>
                </div>
                <div class="col-lg-1 col-md-6">
                    <label for="QTY" class="form-label">Brand</label>
                </div>
                <div class="col-lg-1 col-md-6">
                    <label for="QTY" class="form-label">Model Line</label>
                </div>
                <div class="col-lg-1 col-md-6">
                    <label for="QTY" class="form-label">Variant</label>
                </div>
                <div class="col-lg-1 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Remarks</label>
                </div>
            </div>
        </div>
        <div id ="rows-containerpo">
        </div>
        <div id ="rows-container">
        </div>
        <br>
        <div class="row">
            <div class="col-lg-1 col-md-2 col-sm-6 pb-2 d-flex align-items-center">
                <div class="btn btn-primary add-row-btn" data-row="1">
                    <i class="fas fa-plus"></i> Add Vehicles
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 pb-2 d-flex align-items-center">
                <div class="input-group">
                    <div class="select-po">
                        <select name="po_number" class="form-control mx-4 mb-1" id="po_number">
                            <option value="" selected disabled>Select PO</option>
                            @foreach ($purchasing_order as $po)
                                <option value="{{ $po->id }}">{{ $po->po_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="generate-button">
                        <i class="fas fa-cogs"></i> Add PO Vehicles
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 pb-2 d-flex align-items-center">
                <div class="input-group">
                    <div class="select-so">
                        <select name="so_number" class="form-control mb-1" id="so_number">
                            <option value="" selected disabled>Select SO</option>
                            @foreach ($so as $so)
                                <option value="{{ $so->id }}">{{ $so->so_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="generate-sobutton">
                            <i class="fas fa-cogs"></i> Add SO Vehicles
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </br>
        <div class="col-lg-12 col-md-12">
        <input type="submit" value="Submit" id="btn-submit" class="btn btn-success btncenter" />
    </div>
</form>
		</br>
    </div>
    @endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#vin-input').select2();
        $('#po_number').select2();
        $('#so_number').select2();
    });
</script>
<script>
    $(document).ready(function() {
        var row = 1;
        $('.add-row-btn').click(function() {
            row++;
            var newRow = `
            <div class="row" data-row="${row}">
                <div class="col-lg-2 col-md-6">
                    <select name="vin[]" class="form-control mb-1 vin" id="vin${row}">
                        <option value="" selected disabled>Select VIN</option>
                        @foreach ($vehicles as $vin)
                        <option value="{{ $vin }}">{{ $vin }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-1 col-md-6" >
                    <input type="text" id="po${row}" class="form-control" placeholder="PO #" readonly>
                </div>
                <div class="col-lg-1 col-md-6" >
                    <input type="text" id="so_number${row}" class="form-control" placeholder="SO #" readonly>
                </div>
                <div class="col-lg-1 col-md-6">
                    <select id="ownership_type${row}" class="form-control" name="ownership_type[]">
                        <option value="" disabled ${!vehicle.ownership_type ? 'selected' : ''}>Select Ownership</option>
                        <option value="Incoming">Incoming</option>
                        <option value="Milele Motors FZE">Milele Motors FZE</option>
                        <option value="Trans Car FZE">Trans Car FZE</option>
                        <option value="Supplier Docs">Supplier Docs</option>
                        <option value="Supplier Docs + VCC + BOE">Supplier Docs + VCC + BOE</option>
                        <option value="RTA Possesion Cert/BOD">RTA Possesion Cert/BOD</option>
                        <option value="RTA Possession Cert/Milele Cars Trading">RTA Possession Cert/Milele Cars Trading</option>
                        <option value="RTA Possession Cert/Milele Car Rental">RTA Possession Cert/Milele Car Rental</option>
                    </select>
                </div>
                <div class="col-lg-1 col-md-6">
                    <select class="form-control mb-1 warehouse-from-location" id="from${row}" readonly disabled>
                        <option value="" selected disabled>From</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="from[]" class="form-control mb-1" id="from-input${row}">
                </div>
                <div class="col-lg-2 col-md-6">
                    <select name="to[]" class="form-control mb-1" id="to${row}" required>
                        <option value="" selected disabled>Select To</option>
                        @foreach ($warehouses as $warehouse)
                            @if ($warehouse->name !== 'Supplier')
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-1 col-md-6">
                    <input type="text" id="brand${row}" name="brand[]" class="form-control" placeholder="Brand" readonly>
                </div>
                <div class="col-lg-1 col-md-6">
                    <input type="text" id="model-line${row}" name="model-line[]" class="form-control" placeholder="Model Line" readonly>
                </div>
                <div class="col-lg-1 col-md-6">
                    <input type="text" id="variant${row}" name="variant[]" class="form-control" placeholder="Variant" readonly>
                </div>
               
                <div class="col-lg-1 col-md-6">
                    <div class="d-flex align-items-center">
                        <input type="text" name="remarks[]" class="form-control mr-2" placeholder="Remarks">
                        <button type="button" class="btn btn-danger btn-sm remove-row-btn "><i class="fa fa-times"></i></button>
                    </div>
                    </div>
                </div>
            `;
            $('#rows-container').append(newRow);
            $('#vin' + row).select2();
            $('#to' + row).select2();
            let $fromSelect = $('#from' + row);
            if ($fromSelect.find('option:selected').text().trim() === 'From') {
                $fromSelect.addClass('warehouse-from-location');
            } else {
                $fromSelect.removeClass('warehouse-from-location');
            }
        });
        $('#rows-container').on('change', '.vin', function() {
            let id = $(this).attr('id');
            $('#'+id+"-error").remove();
            var selectedVin = $(this).val();
            var row = $(this).closest('.row').data('row');
            var brandField = $('#brand' + row);
            var fromField = $('#from' + row);
            var fromFieldinput = $('#from-input' + row);
            var SoFeildinput = $('#so_number' + row);
            var ownershipFeildinput = $('#ownership_type' + row);
            var PoFeildinput = $('#po' + row);
            var modelLineField = $('#model-line' + row);
            var variantField = $('#variant' + row);
            $.ajax({
                url: '{{ route('vehicles.vehiclesdetails') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    vin: selectedVin
                },
                success: function(response) {
                    variantField.val(response.variant);
                    fromField.val(response.movement);
                    fromFieldinput.val(response.movement);
                    brandField.val(response.brand);
                    modelLineField.val(response.modelLine);
                    SoFeildinput.val(response.so_number);
                    ownershipFeildinput.val(response.ownership_type);
                    PoFeildinput.val(response.po_number);
                    if (fromField.find("option:selected").text().trim() === "From") {
                        fromField.addClass("warehouse-from-location");
                    } else {
                        fromField.removeClass("warehouse-from-location");
                    }
                },
            });
        });
        $('#rows-container').on('click', '.remove-row-btn', function() {
            $(this).closest(".row").remove();
        });
        $('.vin').trigger('change');
    });
</script>
<script>
 $(document).ready(function () {
        $("#generate-button").click(function () {  // Bind to the Generate button's click event
            var selectedPOId = $("#po_number").val();  // Get the selected PO ID
            $.ajax({
                type: "GET",
                url: "{{ route('vehicles.getVehiclesDataformovement') }}",
                data: { po_id: selectedPOId },
                dataType: "json",
                success: function (response) {
                    if (response.length === 0) {
                        alertify.alert("No Eligible Vehicles", "All vehicles under this PO already have GDN or are not eligible.");
                        return;
                    }

                    response.forEach(function (vehicle) {
                        var rowHtml = `
                            <div class="row">
                                <div class="col-lg-2 col-md-6">
                                    <input type="text" name="vin[]" class="form-control" placeholder="VIN" readonly value="${vehicle.vin}">
                                </div>
                                <div class="col-lg-1 col-md-6" >
                                    <input type="text" class="form-control" placeholder="PO #" readonly value="${vehicle.po_number}">
                                </div>
                                <div class="col-lg-1 col-md-6"> 
                                    <input type="text" class="form-control" placeholder="SO #" readonly value="${vehicle.so_number}">
                                </div>
                                <div class="col-lg-1 col-md-6" >
                                    <select class="form-control" id="ownership_type" name="ownership_type[]">
                                        <option value="" disabled ${!vehicle.ownership_type ? 'selected' : ''}>Select Ownership</option>
                                        <option value="Incoming" ${vehicle.ownership_type === 'Incoming' ? 'selected' : ''}>Incoming</option>
                                        <option value="Milele Motors FZE" ${vehicle.ownership_type === 'Milele Motors FZE' ? 'selected' : ''}>Milele Motors FZE</option>
                                        <option value="Trans Car FZE" ${vehicle.ownership_type === 'Trans Car FZE' ? 'selected' : ''}>Trans Car FZE</option>
                                        <option value="Supplier Docs" ${vehicle.ownership_type === 'Supplier Docs' ? 'selected' : ''}>Supplier Docs</option>
                                        <option value="Supplier Docs + VCC + BOE" ${vehicle.ownership_type === 'Supplier Docs + VCC + BOE' ? 'selected' : ''}>Supplier Docs + VCC + BOE</option>
                                        <option value="RTA Possesion Cert/BOD" ${vehicle.ownership_type === 'RTA Possesion Cert/BOD' ? 'selected' : ''}>RTA Possesion Cert/BOD</option>
                                        <option value="RTA Possession Cert/Milele Cars Trading" ${vehicle.ownership_type === 'RTA Possession Cert/Milele Cars Trading' ? 'selected' : ''}>RTA Possession Cert/Milele Cars Trading</option>
                                        <option value="RTA Possession Cert/Milele Car Rental" ${vehicle.ownership_type === 'RTA Possession Cert/Milele Car Rental' ? 'selected' : ''}>RTA Possession Cert/Milele Car Rental</option>
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" class="form-control mb-1" readonly value="${vehicle.warehouseNames ?? ''}">
                                    <input type="hidden" name="from[]" class="form-control mb-1" value="${vehicle.warehouseName ?? ''}">
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <select name="to[]" class="form-control to-select" required>
                                        ${warehouseOptionsHtml}
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="brand" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.brand}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="model-line" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.modelLine}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="variant" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.variant}">
                                </div>
                                 <div class="col-lg-1 col-md-6">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="remarks[]" class="form-control mr-2" placeholder="Remarks">
                                        <button type="button" class="btn btn-danger btn-sm remove-row-btn"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                `;
                                // if (vehicle.warehouseNames == 'Supplier') {
                                //     rowHtml += `
                                //         <div class="col-lg-1 col-md-6">
                                //             <div class="d-flex align-items-center">
                                //                 <input type="text" name="newvin[]" class="form-control mr-2" placeholder="New VIN">
                                //             </div>
                                //         </div>
                                //         <div class="col-lg-1 col-md-6">
                                //             <div class="d-flex align-items-center">
                                //                 <input type="text" name="remarks[]" class="form-control mr-2" placeholder="Remarks">
                                //                 <button type="button" class="btn btn-danger btn-sm remove-row-btn"><i class="fa fa-times"></i></button>
                                //             </div>
                                          
                                //         </div>
                                //     `;
                                // }
                                // else{
                                //     rowHtml += `
                                //                 <div class="col-lg-2 col-md-6">
                                //                     <div class="d-flex align-items-center">
                                //                         <input type="text" name="remarks[]" class="form-control mr-2" placeholder="Remarks">
                                //                         <button type="button" class="btn btn-danger btn-sm remove-row-btn"><i class="fa fa-times"></i></button>
                                //                     </div>
                                               
                                //                 </div>
                                //                 `;
                                // }
                               
                        $("#rows-containerpo").append(rowHtml);
                    });

                    $("#rows-containerpo select.to-select").each(function () {
                        if (!$(this).hasClass("select2-hidden-accessible")) {
                            $(this).select2();
                        }
                    });
                    // Attach the remove-row event handler after adding the rows
                    attachRemoveRowHandler();
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });

        // Function to attach the remove-row event handler
        function attachRemoveRowHandler() {
            $(".remove-row-btn").on("click", function () {
                $(this).closest(".row").remove();
            });
        }

        // Attach the remove-row event handler on document load
        attachRemoveRowHandler();
    });
</script>

<script>
    var warehouseOptionsHtml = `
        <option value="" selected disabled>Select To</option>
        @foreach ($warehouses as $warehouse)
            @if ($warehouse->name !== 'Supplier')
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
            @endif
        @endforeach
    `;
</script>

<script>
 $(document).ready(function () {
        $("#generate-sobutton").click(function () {  // Bind to the Generate button's click event
            var selectedSOId = $("#so_number").val();  // Get the selected PO ID
          
            $.ajax({
                type: "GET",
                url: "{{ route('vehicles.getVehiclesDataformovementso') }}",
                data: { so_id: selectedSOId },
                dataType: "json",
                success: function (response) {
                    if (response.length === 0) {
                        alertify.alert("No Eligible Vehicles", "All vehicles under this SO already have GDN or are not eligible.");
                        return;
                    }
                    
                    response.forEach(function (vehicle) {
                        var rowHtml = `
                            <div class="row">
                                <div class="col-lg-2 col-md-6">
                                        <input type="text" name="vin[]" class="form-control" placeholder="VIN" readonly value="${vehicle.vin}">
                                    </div>
                                <div class="col-lg-1 col-md-6" >
                                    <input type="text" class="form-control" placeholder="PO #" readonly value="${vehicle.po_number}">
                                </div>
                                <div class="col-lg-1 col-md-6" >
                                    <input type="text" class="form-control" placeholder="SO #" readonly value="${vehicle.so_number}">
                                </div>
                                <div class="col-lg-1 col-md-6" >
                                    <select class="form-control" id="ownership_type" name="ownership_type[]">
                                        <option value="" disabled ${!vehicle.ownership_type ? 'selected' : ''}>Select Ownership</option>
                                        <option value="Incoming" ${vehicle.ownership_type === 'Incoming' ? 'selected' : ''}>Incoming</option>
                                        <option value="Milele Motors FZE" ${vehicle.ownership_type === 'Milele Motors FZE' ? 'selected' : ''}>Milele Motors FZE</option>
                                        <option value="Trans Car FZE" ${vehicle.ownership_type === 'Trans Car FZE' ? 'selected' : ''}>Trans Car FZE</option>
                                        <option value="Supplier Docs" ${vehicle.ownership_type === 'Supplier Docs' ? 'selected' : ''}>Supplier Docs</option>
                                        <option value="Supplier Docs + VCC + BOE" ${vehicle.ownership_type === 'Supplier Docs + VCC + BOE' ? 'selected' : ''}>Supplier Docs + VCC + BOE</option>
                                        <option value="RTA Possesion Cert/BOD" ${vehicle.ownership_type === 'RTA Possesion Cert/BOD' ? 'selected' : ''}>RTA Possesion Cert/BOD</option>
                                        <option value="RTA Possession Cert/Milele Cars Trading" ${vehicle.ownership_type === 'RTA Possession Cert/Milele Cars Trading' ? 'selected' : ''}>RTA Possession Cert/Milele Cars Trading</option>
                                        <option value="RTA Possession Cert/Milele Car Rental" ${vehicle.ownership_type === 'RTA Possession Cert/Milele Car Rental' ? 'selected' : ''}>RTA Possession Cert/Milele Car Rental</option>
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" class="form-control mb-1" readonly value="${vehicle.warehouseNames ?? ''}">
                                    <input type="hidden" name="from[]" class="form-control mb-1" value="${vehicle.warehouseName ?? ''}">
                                </div>
                                <div class="col-lg-2 col-md-6">
                                   <select name="to[]" class="form-control mb-1 to-select" required>
    ${warehouseOptionsHtml}
</select>

                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="brand" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.brand}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="model-line" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.modelLine}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="variant" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.variant}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="remarks[]" class="form-control mr-2" placeholder="Remarks">
                                        <button type="button" class="btn btn-danger btn-sm remove-row-btn"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                            </div>`;

                        // if (vehicle.warehouseNames == 'Supplier') {
                        //     rowHtml += `
                        //         <div class="col-lg-1 col-md-6">
                        //             <div class="d-flex align-items-center">
                        //                 <input type="text" name="newvin[]" class="form-control mr-2" placeholder="New VIN">
                        //             </div>
                        //         </div>
                        //         <div class="col-lg-1 col-md-6">
                        //                         <div class="d-flex align-items-center">
                        //                             <input type="text" name="remarks[]" class="form-control mr-2" placeholder="Remarks">
                        //                             <button type="button" class="btn btn-danger btn-sm remove-row-btn"><i class="fa fa-times"></i></button>
                        //                         </div>
                        //                         </div>
                        //                     </div>
                        //     `;
                        // }
                        // else{
                        //     rowHtml += `
                        //         <div class="col-lg-2 col-md-6">
                        //         <div class="d-flex align-items-center">
                        //             <input type="text" name="remarks[]" class="form-control mr-2" placeholder="Remarks">
                        //             <button type="button" class="btn btn-danger btn-sm remove-row-btn"><i class="fa fa-times"></i></button>
                        //         </div>
                        //         </div>
                        //     </div>
                        // `;
                        // }
                        
                        $("#rows-containerpo").append(rowHtml);
                        $("#rows-containerpo").find("select[name='to[]']").select2();

                        $("#rows-containerpo").find("select[name='to[]']").each(function () {
                            $(this).rules("add", {
                                required: true,
                                messages: {
                                    required: "To location is required."
                                }
                            });
                        });

                        $("#rows-containerpo").find("select[name='to[]']").on("change.select2", function () {
                            $(this).valid();
                        });
                                            });
                    $(".remove-row-btn").on("click", function () {
                        $(this).closest(".row").remove();
                    });
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    $(document).ready(function () {
    // Handle VIN file upload
    $("#upload-vin-button").click(function (e) {
        e.preventDefault();

        // Get the uploaded file
        var vinFile = $("#vin_file")[0].files[0];
        if (!vinFile) {
            alert("Please upload a VIN file.");
            return;
        }

        var formData = new FormData();
        formData.append("vin_file", vinFile);
        // AJAX call to upload the file
        $.ajax({
            url: "{{ route('vehicles.uploadVinFile') }}",  // Define a new route for file upload
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // console.log(response);
                if (response.success) {

                    // Clear any existing excel rows before inserting new ones
                    $("#rows-containerpo .excel-uploaded-row").remove();
                    
                    response.vehicleDetails.forEach(function (vehicle) {
                        var rowHtml = `
                            <div class="row">
                            <div class="col-lg-2 col-md-6" >
                                    <input type="text" name="vin[]" class="form-control" placeholder="VIN" readonly value="${vehicle.vin}">
                                </div>
                                <div class="col-lg-1 col-md-6" >
                                    <input type="text" class="form-control" placeholder="PO #" readonly value="${vehicle.po_number}">
                                </div>
                                <div class="col-lg-1 col-md-6"> 
                                    <input type="text" class="form-control" placeholder="SO #" readonly value="${vehicle.so_number}">
                                </div>
                                <div class="col-lg-1 col-md-6" >
                                    <select class="form-control" id="ownership_type" name="ownership_type[]">
                                        <option value=""${!vehicle.ownership_type ? 'selected' : ''} disabled>Select Ownership</option>
                                        <option value="Incoming" ${vehicle.ownership_type === 'Incoming' ? 'selected' : ''}>Incoming</option>
                                        <option value="Milele Motors FZE" ${vehicle.ownership_type === 'Milele Motors FZE' ? 'selected' : ''}>Milele Motors FZE</option>
                                        <option value="Trans Car FZE" ${vehicle.ownership_type === 'Trans Car FZE' ? 'selected' : ''}>Trans Car FZE</option>
                                        <option value="Supplier Docs" ${vehicle.ownership_type === 'Supplier Docs' ? 'selected' : ''}>Supplier Docs</option>
                                        <option value="Supplier Docs + VCC + BOE" ${vehicle.ownership_type === 'Supplier Docs + VCC + BOE' ? 'selected' : ''}>Supplier Docs + VCC + BOE</option>
                                        <option value="RTA Possesion Cert/BOD" ${vehicle.ownership_type === 'RTA Possesion Cert/BOD' ? 'selected' : ''}>RTA Possesion Cert/BOD</option>
                                        <option value="RTA Possession Cert/Milele Cars Trading" ${vehicle.ownership_type === 'RTA Possession Cert/Milele Cars Trading' ? 'selected' : ''}>RTA Possession Cert/Milele Cars Trading</option>
                                        <option value="RTA Possession Cert/Milele Car Rental" ${vehicle.ownership_type === 'RTA Possession Cert/Milele Car Rental' ? 'selected' : ''}>RTA Possession Cert/Milele Car Rental</option>
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-6">
                                <input type="text" class="form-control mb-1" readonly value="${vehicle.warehouseNames ?? ''}">
                                    <input type="hidden" name="from[]" class="form-control mb-1" value="${vehicle.warehouseName ?? ''}">
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <select name="to[]" class="form-control to-select" required>
                                        ${warehouseOptionsHtml.replace(
                                            `value="${vehicle.matchedWarehouseId}"`,
                                            `value="${vehicle.matchedWarehouseId}" selected`
                                        )}
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="brand" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.brand}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="model-line" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.modelLine}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="variant" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.variant}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="remarks[]" class="form-control mr-2" placeholder="Remarks">
                                        <button type="button" class="btn btn-danger btn-sm remove-row-btn"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                                `;
                                // if (vehicle.warehouseNames == 'Supplier') {
                                //     rowHtml += `
                                //         <div class="col-lg-1 col-md-6">
                                //             <div class="d-flex align-items-center">
                                //                 <input type="text" name="newvin[]" class="form-control mr-2" placeholder="New VIN">
                                //             </div>
                                //         </div>
                                //         <div class="col-lg-1 col-md-6">
                                //                         <div class="d-flex align-items-center">
                                //                             <input type="text" name="remarks[]" class="form-control mr-2" placeholder="Remarks">
                                //                             <button type="button" class="btn btn-danger btn-sm remove-row-btn"><i class="fa fa-times"></i></button>
                                //                         </div>
                                //                         </div>
                                //                     </div>
                                //     `;
                                // }
                                // else{
                                //     rowHtml += `
                                //                         <div class="col-lg-2 col-md-6">
                                //                         <div class="d-flex align-items-center">
                                //                             <input type="text" name="remarks[]" class="form-control mr-2" placeholder="Remarks">
                                //                             <button type="button" class="btn btn-danger btn-sm remove-row-btn"><i class="fa fa-times"></i></button>
                                //                         </div>
                                //                         </div>
                                //                     </div>
                                //                     `;
                                //                 }
                        // rowHtml += `</div>`;
                        $("#rows-containerpo").append(rowHtml);

                        $("#rows-containerpo .to-select").each(function () {
                            if (!$(this).hasClass("select2-hidden-accessible")) {
                                $(this).select2();
                            }
                        });
                    });

                    // Attach the remove-row event handler
                    attachRemoveRowHandler();
                } else {
                    let errors = response.failedVINs || [];
                    if (errors.length > 0) {
                        let message = "<strong>The following VIN(s) could not be added:</strong><br><ul>";
                        errors.forEach(item => {
                            message += `<li><strong>${item.vin}</strong>: ${item.reason}</li>`;
                        });
                        message += "</ul>";
                        alertify.alert("VIN Upload Issues", message);
                    } else {
                        alertify.alert("Error", response.message || "Some VINs could not be processed.");
                    }
                }
            },
            error: function (error) {
                console.error(error);
            }
        });
    });

    function attachRemoveRowHandler() {
        $(".remove-row-btn").on("click", function () {
            $(this).closest(".row").remove();
        });
    }

    $("#formCreate").validate({
    ignore: [],
    rules: {
        "vin[]": {
            required: true
        },
        "to[]": {
            required: true
        },
        file: {
            extension: "csv"
        }
    },
    messages: {
        "vin[]": {
            required: "VIN is required."
        },
        "to[]": {
            required: "To location is required."
        },
        file: {
            extension: "Please upload file in .csv format."
        }
    },
    errorPlacement: function (error, element) {
        if (element.hasClass('select2-hidden-accessible')) {
            error.insertAfter(element.next('.select2')); // places error after select2 span
        } else {
            error.insertAfter(element);
        }
    }
});


        $.validator.prototype.checkForm = function (){
            this.prepareForm();
            for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
                if (this.findByName( elements[i].name ).length != undefined && this.findByName( elements[i].name ).length > 1) {
                    for (var cnt = 0; cnt < this.findByName( elements[i].name ).length; cnt++) {
                        this.check( this.findByName( elements[i].name )[cnt] );
                    }
                }
                else {
                    this.check( elements[i] );
                }
            }
            return this.valid();
        };
        
        $(document).on('select2:select', 'select[name="to[]"]', function () {
            $(this).valid(); 
            const $errorLabel = $(this).next('.select2').next('label.error');
            if ($errorLabel.length) {
                $errorLabel.hide();
            }
        });

        $('#btn-submit').click(function (e) {
            e.preventDefault();

            let vinArray = [];
            let duplicateVinMap = {};
            let duplicateMessages = [];
            let errorMessages = [];

            let fromArray = $("input[name='from[]']").map(function () { return $(this).val(); }).get();
            let toArray = $("select[name='to[]']").map(function () { return $(this).val(); }).get();
            let vinInputs = $("input[name='vin[]'], select[name='vin[]']");

            let locationConflictErrors = [];

            for (let i = 0; i < fromArray.length; i++) {
                if (fromArray[i] && toArray[i] && fromArray[i] === toArray[i]) {
                    let vin = vinInputs.eq(i).val() || 'Unknown VIN';
                    locationConflictErrors.push(`❌ VIN ${vin} has the same From and To location.`);
                }
            }

            if (locationConflictErrors.length > 0) {
                alertify.alert("Location Conflict", locationConflictErrors.join("<br>"));
                return false;
            }

            $("input[name='vin[]'], select[name='vin[]']").each(function () {
                let vinVal = $(this).val();
                if (vinVal) {
                    vinArray.push(vinVal);
                    duplicateVinMap[vinVal] = (duplicateVinMap[vinVal] || 0) + 1;
                }
            });

            for (const vin in duplicateVinMap) {
                if (duplicateVinMap[vin] > 1) {
                    duplicateMessages.push(`❌ VIN ${vin} appears ${duplicateVinMap[vin]} times.`);
                }
            }

            if (duplicateMessages.length > 0) {
                alertify.alert("Duplicate VINs Found", duplicateMessages.join("<br>"));
                return false;
            }
            if (vinArray.length <= 0) {
                $("select[name='vin[]']").each(function () {
                    vinArray.push($(this).val());
                });
            }

            $("input[name='from[]']").each(function (index, element) {
                let fromVal = $(this).val();
                fromArray.push(fromVal);

                if (!fromVal || fromVal.trim() === "" || fromVal === "From" || fromVal === 'undefined' || fromVal === 'null') {
                    let vinVal = vinArray[index] ?? 'Unknown';
                    errorMessages.push(`❌ VIN ${vinVal} has no valid "From" location. Please contact IT Development team.`);
                    $(this).closest('.row').addClass('border border-danger');
                } else {
                    $(this).closest('.row').removeClass('border border-danger');
                }
            });

            if (errorMessages.length > 0) {
                alertify.alert("Invalid Data", errorMessages.join("<br>"));
                console.warn("🚨 Validation blocked form submit");
                return false; 
            }

            if (!$("#formCreate").valid()) {
                console.log("Form validation failed");
                return false;
            }

            $("select[name='to[]']").each(function () {
                toArray.push($(this).val());
            });

            // Ajax check after both validations
            let url = '{{ route('movement.unique-check') }}';
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    vin: vinArray,
                },
                success: function (data) {
                    if (data.length > 0) {
                        let message = "The following duplicate VINs were found:<br>";
                        data.forEach(function (duplicate) {
                            message += duplicate + "<br>";
                        });
                        alertify.alert("Invalid VINs", message);
                        return false;
                    } else {
                        document.getElementById("formCreate").submit();
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error:", error);
                    const isErrorMsg = "An error occurred. Please try again with all valid fields."
                    alertify.alert(isErrorMsg);
                }
            });
        });
});


</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endpush