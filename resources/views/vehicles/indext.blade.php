@extends('layouts.table')
<style>
  div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
  text-align: center;
  vertical-align: middle;
}
    /* Hide the default "Show x entries" dropdown */
  #dtBasicExample1_length {
    display: none;
  }
      .table-responsive {
      overflow: auto;
    }
    .table-wrapper {
      position: relative;
    }
    thead th {
      position: sticky!important;
      top: 0;
      background-color: rgb(194, 196, 204)!important;
      z-index: 1; /* Ensure the table header is on top of other elements */
    }
    #table-responsive {
      height: 100vh;
      overflow-y: auto;
    }
    #dtBasicSupplierInventory {
      width: 100%;
      font-size: 12px;
    }
    th.nowrap-td {
      white-space: nowrap;
      height: 10px;
    }
    .nowrap-td {
        white-space: nowrap;
      }
</style>
@section('content')
<div class="card-body">
                        <div class="table-responsive" >
                            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
                            <thead class="bg-soft-secondary">
                               <tr>
                                <th style="width:205px;" id="vehicle_id">Ref No</th>
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po');
                                    @endphp
                                    @if ($hasPermission)
                                    <th data-column="po_number" id="po_number" class="nowrap-td">
                                    PO Number
                                    <br>
                                    <input type="text" data-search-column="po_number" id="vinSearchInput" placeholder="Search">
                                    </th>
                                    <th data-column="po_date" id="po_date" class="nowrap-td">
                                    PO Date
                                    <br>
                                    <input type="text" data-search-column="po_date" id="po_date" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('ETA-timer-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="eta" id="eta" class="nowrap-td">
                                ETA Timer
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('estimated-arrival-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="estimation_date" id="estimation_date" class="nowrap-td">
                                Estimated Arrival
                                    <br>
                                    <input type="text" data-search-column="estimation_date" id="estimation_date" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="grn_number" id="grn_number" class="nowrap-td">
                                GRN
                                    <br>
                                    <input type="text" data-search-column="grn_number" id="grn_number" placeholder="Search">
                                    </th>
                                    <th data-column="grn_date" id="grn_date" class="nowrap-td">
                                    GRN Date
                                    <br>
                                    <input type="text" data-search-column="grn_date" id="grn_date" placeholder="Search">
                                    </th>
                                    <th data-column="netsuit_grn_number" id="netsuit_grn_number" class="nowrap-td">
                                    Netsuit GRN Number
                                    <br>
                                    <input type="text" data-search-column="netsuit_grn_number" id="netsuit_grn_number" placeholder="Search">
                                    </th>
                                    <th data-column="netsuit_grn_date" id="netsuit_grn_date" class="nowrap-td">
                                    Netsuit GRN Date
                                    <br>
                                    <input type="text" data-search-column="netsuit_grn_date" id="netsuit_grn_date" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-status-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="stock" id="netsuit_grn_date" class="nowrap-td">
                                Stock Status
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="inspection_date" id="inspection_date" class="nowrap-td">
                                GRN Inspection Date
                                    <br>
                                    <input type="text" data-search-column="inspection_date" id="inspection_date" placeholder="Search">
                                    </th>
                                    <th data-column="grn_remark" id="grn_remark" class="nowrap-td">
                                    GRN Remarks
                                    <br>
                                    <input type="text" data-search-column="grn_remark" id="grn_remark" placeholder="Search">
                                    </th>
                                    <th data-column="qc_remarks" id="qc_remarks" class="nowrap-td">
                                    QC Remarks
                                    <br>
                                    <input type="text" data-search-column="qc_remarks" id="qc_remarks" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('aging-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="payment_agining" id="payment_agining" class="nowrap-td">
                                Payment Aging
                                    </th>
                                    <th data-column="stock_agining" id="stock_agining" class="nowrap-td">
                                    Stock Aging
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-so');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="so_number" id="so_number" class="nowrap-td">
                                    SO Number
                                    <br>
                                    <input type="text" data-search-column="so_number" id="so_number" placeholder="Search">
                                    </th>
                                    <th data-column="so_date" id="so_date" class="nowrap-td">
                                    SO Date
                                    <br>
                                    <input type="text" data-search-column="so_date" id="so_date" placeholder="Search">
                                    </th>
                                    @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('reservation-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="sales_person_id" id="sales_person_id" class="nowrap-td">
                                Sales Person
                                    <br>
                                    <input type="text" data-search-column="sales_person_id" id="sales_person_id" placeholder="Search">
                                    </th>
                                    <th data-column="reservation_start_date" id="reservation_start_date" class="nowrap-td">
                                    Reservation Date
                                    <br>
                                    <input type="text" data-search-column="reservation_start_date" id="reservation_start_date" placeholder="Search">
                                    </th>
                                    <th data-column="reservation_end_date" id="reservation_end_date" class="nowrap-td">
                                    Reservation Due Date
                                    <br>
                                    <input type="text" data-search-column="reservation_end_date" id="reservation_end_date" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('so-remarks');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="sales_remarks" id="sales_remarks" class="nowrap-td">
                                Sales Remarks
                                    <br>
                                    <input type="text" data-search-column="sales_remarks" id="sales_remarks" placeholder="Search">
                                    </th>
                                @endif
                                <th data-column="pdi_date" id="pdi_date" class="nowrap-td">
                                PDI Inspection Date
                                    <br>
                                    <input type="text" data-search-column="pdi_date" id="pdi_date" placeholder="Search">
                                    </th>
                                    <th data-column="pdi_remarks" id="pdi_remarks" class="nowrap-td">
                                    PDI Remarks
                                    <br>
                                    <input type="text" data-search-column="pdi_remarks" id="pdi_remarks" placeholder="Search">
                                    </th>
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('gdn-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="gdn_number" id="gdn_number" class="nowrap-td">
                                GDN Number
                                    <br>
                                    <input type="text" data-search-column="gdn_number" id="gdn_number" placeholder="Search">
                                    </th>
                                    <th data-column="gdn_date" id="gdn_date" class="nowrap-td">
                                    GDN Date
                                    <br>
                                    <input type="text" data-search-column="gdn_date" id="gdn_date" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="brand" id="brand" class="nowrap-td">
                                Brand
                                    <br>
                                    <input type="text" data-search-column="brand" id="brand" placeholder="Search">
                                    </th>
                                    <th data-column="model_line" id="model_line" class="nowrap-td">
                                Model Line
                                    <br>
                                    <input type="text" data-search-column="model_line" id="model_line" placeholder="Search">
                                    </th>
                                    <th data-column="model_description" id="model_description" class="nowrap-td">
                                    Model Description
                                    <br>
                                    <input type="text" data-search-column="model_description" id="model_description" placeholder="Search">
                                    </th>
                                    <th data-column="variant" id="variant" class="nowrap-td">
                                    Variant Name
                                    <br>
                                    <input type="text" data-search-column="variant" id="variant" placeholder="Search">
                                    </th>
                                    <th data-column="variant_details" id="variant_details" class="nowrap-td">
                                    Variant Detail
                                    <br>
                                    <input type="text" data-search-column="variant_details" id="variant_details" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vin-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="vin" id="vin" class="nowrap-td">
                                VIN Number
                                    <br>
                                    <input type="text" data-search-column="vin" id="vin" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('conversion-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="conversion" id="conversion" class="nowrap-td">
                                Conversion
                                    <br>
                                    <input type="text" data-search-column="conversion" id="conversion" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('enginee-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="engine" id="engine" class="nowrap-td">
                                Engine
                                    <br>
                                    <input type="text" data-search-column="engine" id="engine" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="model_year" id="model_year" class="nowrap-td">
                                Model Year
                                    <br>
                                    <input type="text" data-search-column="model_year" id="model_year" placeholder="Search">
                                    </th>
                                    <th data-column="steering" id="steering" class="nowrap-td">
                                    Steering
                                    <br>
                                    <input type="text" data-search-column="steering" id="steering" placeholder="Search">
                                    </th>
                                    <th data-column="seats" id="seats" class="nowrap-td">
                                    Seats
                                    <br>
                                    <input type="text" data-search-column="seats" id="seats" placeholder="Search">
                                    </th>
                                    <th data-column="fuel_type" id="fuel_type" class="nowrap-td">
                                    Fuel Type
                                    <br>
                                    <input type="text" data-search-column="fuel_type" id="fuel_type" placeholder="Search">
                                    </th>
                                    <th data-column="gear" id="gear" class="nowrap-td">
                                    Transmission
                                    <br>
                                    <input type="text" data-search-column="gear" id="gear" placeholder="Search">
                                    </th>
                                    <th data-column="ex_colour" id="ex_colour" class="nowrap-td">
                                    Ext Colour
                                    <br>
                                    <input type="text" data-search-column="ex_colour" id="ex_colour" placeholder="Search">
                                    </th>
                                    <th data-column="int_colour" id="int_colour" class="nowrap-td">
                                    Int Colour
                                    <br>
                                    <input type="text" data-search-column="int_colour" id="int_colour" placeholder="Search">
                                    </th>
                                    <th data-column="upholestry" id="upholestry" class="nowrap-td">
                                    Upholstery
                                    <br>
                                    <input type="text" data-search-column="upholestry" id="upholestry" placeholder="Search">
                                    </th>
                                    <th data-column="extra_features" id="extra_features" class="nowrap-td">
                                    Extra Features
                                    <br>
                                    <input type="text" data-search-column="extra_features" id="extra_features" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="ppmmyyy" id="ppmmyyy" class="nowrap-td">
                                Production Year
                                    <br>
                                    <input type="text" data-search-column="ppmmyyy" id="ppmmyyy" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('territory-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="territory" id="territory" class="nowrap-td">
                                Allowed Territory
                                    <br>
                                    <input type="text" data-search-column="territory" id="territory" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehousest-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="latest_location" id="latest_location" class="nowrap-td">
                                Warehouse
                                    <br>
                                    <input type="text" data-search-column="latest_location" id="latest_location" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-remarks-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="warehouseremarks" id="warehouseremarks" class="nowrap-td">
                                Warehouse Remarks
                                    <br>
                                    <input type="text" data-search-column="warehouseremarks" id="warehouseremarks" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="price" id="price" class="nowrap-td">
                                Price
                                    <br>
                                    <input type="text" data-search-column="price" id="price" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('document-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="importdoc" id="importdoc" class="nowrap-td">
                                Import Document Type
                                    <br>
                                    <input type="text" data-search-column="importdoc" id="importdoc" placeholder="Search">
                                    </th>
                                    <th data-column="ownership" id="ownership" class="nowrap-td">
                                    Document Ownership
                                    <br>
                                    <input type="text" data-search-column="ownership" id="ownership" placeholder="Search">
                                    </th>
                                    <th data-column="documentwith" id="documentwith" class="nowrap-td">
                                    Documents With
                                    <br>
                                    <input type="text" data-search-column="documentwith" id="documentwith" placeholder="Search">
                                    </th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('bl-view');
                                @endphp
                                @if ($hasPermission)
                                <th data-column="bl_number" id="bl_number" class="nowrap-td">
                                BL Number
                                    <br>
                                    <input type="text" data-search-column="bl_number" id="bl_number" placeholder="Search">
                                    </th>
                                    <th data-column="bl_dms_uploading" id="bl_dms_uploading" class="nowrap-td">
                                    BL DMS Upload
                                    <br>
                                    <input type="text" data-search-column="bl_dms_uploading" id="bl_dms_uploading" placeholder="Search">
                                    </th>
                                @endif
                                    <!-- <th id="changelogs" class="nowrap-td"id="log" style="vertical-align: middle;">Details</th> -->
                               </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
    </tbody>
</table>
<script>
var offset = 0;
var length = 40;
var isLoading = false;
function loadMoreData() {
    if (isLoading) {
        return;
    }
    isLoading = true;
    $.ajax({
        url: '{{ route("vehicles.viewalls") }}',
        type: 'GET',
        data: {
            offset: offset,
            length: length
        },
        success: function(data) {
            if (data.length > 0) {
                var table = $('#dtBasicExample1').DataTable();
                table.rows.add(data).draw();
                offset += length;
            }
            isLoading = false;
        },
        error: function() {
            isLoading = false;
        }
    });
}
$(document).ready(function() {
    var table = $('#dtBasicExample1').DataTable({
        paging: false,
        searching: true,
        info: false,
        columns: [
            { data: 'id', name: 'id' },
            @if (Auth::user()->hasPermissionForSelectedRole('view-po'))
            { data: 'po_number', name: 'po_number'},
            { data: 'po_date', name: 'po_date' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('ETA-timer-view'))
            {
    data: null, // We're using 'null' here because we'll render the content directly in the 'render' function
    name: 'eta',
    render: function(data, type, row) {
        if (row.estimation_date && row.grn_number === null) {
            var savedDate = moment(row.estimation_date, 'YYYY-MM-DD');
            var today = moment();
            var numberOfDaysEta = savedDate.diff(today, 'days');
            var sign = (numberOfDaysEta >= 0) ? '' : '-';
            numberOfDaysEta = Math.abs(numberOfDaysEta);

            return sign + numberOfDaysEta + ' ' + (numberOfDaysEta === 1 ? 'Day' : 'Days');
        } else if (row.grn_number) {
            return 'Arrived';
        } else {
            return 'Incoming';
        }
    }
},
@endif
@if (Auth::user()->hasPermissionForSelectedRole('estimated-arrival-view'))
            { data: 'estimation_date', name: 'estimation_date' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('grn-view'))
            { data: 'grn_number', name: 'grn_number' },
            { data: 'grn_date', name: 'grn_date' },
            { data: 'netsuit_grn_number', name: 'netsuit_grn_number' },
            { data: 'netsuit_grn_date', name: 'netsuit_grn_date' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('stock-status-view'))
            {
    data: null,
    name: 'stockstatus',
    render: function(data, type, row) {
        if (row.grn_number === null) {
            return 'Incoming';
        } else if (row.so_number === null && row.reservation_end_date && moment(row.reservation_end_date).isAfter(moment())) {
            return 'Reserved';
        } else if (row.so_number && row.gdn_number === null) {
            return 'Booked';
        } else if (row.gdn_number) {
            return 'Sold';
        } else {
            return 'Available';
        }
    }
},
@endif
@if (Auth::user()->hasPermissionForSelectedRole('inspection-view'))
            { data: 'inspection_date', name: 'inspection_date' },
            { data: 'grn_remark', name: 'grn_remark' },
            { data: 'qc_remarks', name: 'qc_remarks' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('aging-view'))
            {
    data: null,
    name: 'payment_days',
    render: function(data, type, row) {
        if (row.grn_date !== null) {
            if (row.paymentLog) {
                var savedDate = moment(row.paymentLog.date);
                var today = moment(row.grn_date);
                var numberOfDays = today.diff(savedDate, 'days');
                return numberOfDays;
            } else {
                return '';
            }
        } else {
            if (row.paymentLog) {
                var savedDate = moment(row.paymentLog.date);
                var today = moment();
                var numberOfDays = today.diff(savedDate, 'days');
                return numberOfDays;
            } else {
                return '';
            }
        }
    }
},
{
    data: null,
    name: 'aging',
    render: function(data, type, row) {
        if (row.grn_date && row.gdn_date === null) {
            var grnDate = moment(row.grn_date);
            var aging = moment().diff(grnDate, 'days');
            return aging;
        } else if (row.gdn_date) {
            var aging = moment(row.grn_date).diff(row.gdn_date, 'days');
            return aging;
        } else {
            return '';
        }
    }
},
@endif
            @if (Auth::user()->hasPermissionForSelectedRole('view-so'))
            { data: 'so_number', name: 'so_number' },
            { data: 'so_date', name: 'so_date' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('reservation-view'))
            { data: 'salespersonname', name: 'salespersonname' },
            { data: 'reservation_start_date', name: 'reservation_start_date' },
            { data: 'reservation_start_date', name: 'reservation_start_date' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('so-remarks'))
            { data: 'latest_remark_sales', name: 'latest_remark_sales' },
            { data: 'pdi_date', name: 'pdi_date' },
            { data: 'pdi_remarks', name: 'pdi_remarks' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('gdn-view'))
            { data: 'gdn_number', name: 'gdn_number' },
            { data: 'gdn_date', name: 'gdn_date' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view'))
            { data: 'variant.brand.brand_name', name: 'variant.brand.brand_name' },
            { data: 'variant.master_model_lines.model_line', name: 'variant.master_model_lines.model_line' },
            { data: 'model_detail', name: 'model_detail' },
            { data: 'variantname', name: 'variantname' },
            { data: 'variantdetail', name: 'variantdetail' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('vin-view'))
            { data: 'vin', name: 'vin' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('conversion-view'))
            { data: 'conversion', name: 'conversion' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('enginee-view'))
            { data: 'engine', name: 'engine' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view'))
            { data: 'variantmy', name: 'variantmy' },
            { data: 'variantsteering', name: 'variantsteering' },
            { data: 'variantseat', name: 'variantseat' },
            { data: 'variantfuel_type', name: 'variantfuel_type' },
            { data: 'transmission', name: 'transmission' },
            { data: 'interiorcolours', name: 'interiorcolours' },
            { data: 'exteriorcolour', name: 'exteriorcolour' },
            { data: 'upholestry', name: 'upholestry' },
            { data: 'extra_features', name: 'extra_features' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view'))
            { data: 'ppmmyyy', name: 'ppmmyyy' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('territory-view'))
            { data: 'territory', name: 'territory' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('warehousest-view'))
            { data: 'warehousename', name: 'warehousename' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('warehouse-remarks-view'))
            { data: 'latest_remark_warehouse', name: 'latest_remark_warehouse' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('price-view'))
            { data: 'price', name: 'price' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('document-view'))
            { data: 'import_type', name: 'import_type' },
            { data: 'owership', name: 'owership' },
            { data: 'document_with', name: 'document_with' },
            @endif
            @if (Auth::user()->hasPermissionForSelectedRole('bl-view'))
            { data: 'bl_number', name: 'bl_number' },
            { data: 'bl_dms_uploading', name: 'bl_dms_uploading' },
            @endif
        ],
    });
    var searchParams = {}; // To store column-specific search values
    var offset = 0;
    var length = 40;
    var isLoading = false;
    var loadedRowsSet = new Set(); // Set to track loaded rows' identifiers

    // Function to clear the loaded rows set
    function clearLoadedRowsSet() {
        loadedRowsSet.clear();
    }

    // Attach event listeners to input elements in table headers
    table.columns().every(function() {
    var column = this;
    var searchColumn = $(column.header()).data('column'); // Get the data attribute

    $('input', this.header()).on('keyup change', function() {
        if (column.search() !== this.value) {
            column.search(this.value).draw();
        }

        // Update searchParams for the column
        searchParams[searchColumn] = this.value;

        offset = 0;
        loadedRowsSet.clear();
        loadMoreData();
    });
});

    loadMoreData();

    function loadMoreData() {
        if (isLoading) {
            return;
        }
        isLoading = true;

        // Send search values along with other parameters
        $.ajax({
            url: '{{ route("vehicles.viewalls") }}',
            type: 'GET',
            data: {
                offset: offset,
                length: length,
                columns: searchParams // Send search values
            },
            success: function(data) {
                var newData = data.filter(row => !loadedRowsSet.has(row.id)); // Filter out existing rows
                if (newData.length > 0) {
                    table.rows.add(newData).draw(false); // Append new rows without clearing
                    newData.forEach(row => loadedRowsSet.add(row.id)); // Update loaded rows set
                    offset += length;
                }
                isLoading = false;
            },
            error: function() {
                isLoading = false;
            }
        });
    }
    $(window).on('scroll', function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadMoreData();
        }
    });
});
</script>
@endsection
