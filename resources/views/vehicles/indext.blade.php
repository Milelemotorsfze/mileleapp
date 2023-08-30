@extends('layouts.table')
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
                                <th id="po_number" class="nowrap-td">PO Number</th>
                                <th id="po_date" class="nowrap-td">PO Date</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('ETA-timer-view');
                                @endphp
                                @if ($hasPermission)
                                <th class="nowrap-td">ETA Timer</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('estimated-arrival-view');
                                @endphp
                                @if ($hasPermission)
                                <th id="estimation_date" class="nowrap-td">Estimated Arrival</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-view');
                                @endphp
                                @if ($hasPermission)
                                <th id="grn_number" class="nowrap-td">GRN</th>
                                <th id="grn_date" class="nowrap-td">GRN Date</th>
                                <th id="netsuit_grn_number" class="nowrap-td">Netsuit GRN Number</th>
                                <th id="netsuit_grn_date" class="nowrap-td">Netsuit GRN Date</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-status-view');
                                @endphp
                                @if ($hasPermission)
                                <th class="nowrap-td">Stock Status</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-view');
                                @endphp
                                @if ($hasPermission)
                                <th id="inspection_date" class="nowrap-td">GRN Inspection Date</th>
                                <th id="grn_remark" class="nowrap-td">GRN Remarks</th>
                                <th id="qc_remarks" class="nowrap-td">QC Remarks</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('aging-view');
                                @endphp
                                @if ($hasPermission)
                                <th class="nowrap-td">Payment Aging</th>
                                <th class="nowrap-td">Stock Aging</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-so');
                                @endphp
                                @if ($hasPermission)
                                    <th id="so_number" class="nowrap-td">SO Number</th>
                                    <th id="so_date" class="nowrap-td">SO Date</th>
                                    @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('reservation-view');
                                @endphp
                                @if ($hasPermission)
                                <th id="sales_person_id" class="nowrap-td">Sales Person</th>
                                    <th id="reservation_start_date" class="nowrap-td">Reservation Date</th>
                                    <th id="reservation_end_date" class="nowrap-td">Reservation Due Date</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('so-remarks');
                                @endphp
                                @if ($hasPermission)
                                    <th id="sales_remarks" style="vertical-align: middle;" class="nowrap-td">Sales Remarks</th>
                                @endif
                                <th id="pdi_date" class="nowrap-td">PDI Inspection Date</th>
                                <th id="pdi_remarks" class="nowrap-td">PDI Remarks</th>
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('gdn-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="gdn_number" class="nowrap-td">GDN Number</th>
                                    <th id="gdn_date" class="nowrap-td">GDN Date</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="brand" class="nowrap-td">Brand</th>
                                    <th id="model_line" class="nowrap-td">Model Line</th>
                                    <th id="model_description" class="nowrap-td">Model Description</th>
                                    <th id="variant" id="variant" style="vertical-align: middle;" class="nowrap-td">Variant Name</th>
                                    <th id="variant_details" class="nowrap-td">Variant Detail</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vin-view');
                                @endphp
                                @if ($hasPermission)
                                <th id="vin" class="nowrap-td">VIN Number</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('conversion-view');
                                @endphp
                                @if ($hasPermission)
                                <th id="conversion" class="nowrap-td">Conversion</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('enginee-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="engine" class="nowrap-td">Engine</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="model_year" class="nowrap-td">Model Year</th>
                                    <th id="steering" class="nowrap-td">Steering</th>
                                    <th id="seats" class="nowrap-td">Seats</th>
                                    <th id="fuel_type" class="nowrap-td">Fuel Type</th>
                                    <th id="gear" class="nowrap-td">Transmission</th>
                                    <th id="ex_colour" class="nowrap-td" id="ex-colour" style="vertical-align: middle;" style="min-width:150px">Ext Colour</th>
                                    <th id="int_colour" class="nowrap-td" id="int-colour"  style="vertical-align: middle;" style="min-width:150px">Int Colour</th>
                                    <th id="upholestry" class="nowrap-td">Upholstery</th>
                                    <th id="extra_features" class="nowrap-td">Extra Features</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="ppmmyyy" class="nowrap-td">Production Year</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('territory-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="territory" class="nowrap-td">Allowed Territory</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehousest-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="latest_location" class="nowrap-td">Warehouse</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-remarks-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="warehouseremarks" style="vertical-align: middle;" class="nowrap-td">Warehouse Remarks</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="price"class="nowrap-td">Price</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('document-view');
                                @endphp
                                @if ($hasPermission)
                               <th id="importdoc" class="nowrap-td" style="vertical-align: middle;">Import Document Type</th>
                               <th id="ownership" class="nowrap-td" style="vertical-align: middle;">Document Ownership</th>
                                    <th id="documentwith" class="nowrap-td" style="vertical-align: middle;">Documents With</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('bl-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="bl_number"class="nowrap-td">BL Number</th>
                                    <th id="bl_dms_uploading"class="nowrap-td">BL DMS Upload</th>
                                @endif
                                    <th id="changelogs" class="nowrap-td"id="log" style="vertical-align: middle;">Details</th>
                               </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
        <!-- Data rows will be added dynamically -->
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
        searching: false,
        info: false,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'po_number', name: 'po_number' },
            { data: 'po_date', name: 'po_date' },
            { data: 'int_colour', name: 'int_colour' },
            { data: 'estimation_date', name: 'estimation_date' },
            { data: 'grn_date', name: 'grn_date' },
            { data: 'grn_number', name: 'grn_number' },
            { data: 'netsuit_grn_number', name: 'netsuit_grn_number' },
            { data: 'netsuit_grn_date', name: 'netsuit_grn_date' },
            { data: 'netsuit_grn_date', name: 'netsuit_grn_date' },
            { data: 'inspection_date', name: 'inspection_date' },
            { data: 'grn_remark', name: 'grn_remark' },
            { data: 'qc_remarks', name: 'qc_remarks' },
            { data: 'qc_remarks', name: 'qc_remarks' },
            { data: 'qc_remarks', name: 'qc_remarks' },
            { data: 'so_number', name: 'so_number' },
            { data: 'so_date', name: 'so_date' },
            { data: 'so_date', name: 'so_date' },
            { data: 'reservation_start_date', name: 'reservation_start_date' },
            { data: 'reservation_start_date', name: 'reservation_start_date' },
            { data: 'so_id', name: 'so_id' },
            { data: 'pdi_date', name: 'pdi_date' },
            { data: 'pdi_remarks', name: 'pdi_remarks' },
            { data: 'gdn_date', name: 'gdn_date' },
            { data: 'gdn_number', name: 'gdn_number' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'vin', name: 'vin' },
            { data: 'conversion', name: 'conversion' },
            { data: 'engine', name: 'engine' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
            { data: 'so_id', name: 'so_id' },
        ],
    });
    loadMoreData();
    $(window).on('scroll', function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadMoreData();
        }
    });
});
</script>
@endsection
