@extends('layouts.table')
<style>
    #dtBasicExample2 {
        width: 100%;
    }
</style>
@section('content')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
                    @endphp
                    @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">
            Leads Data Center
        </h4>
    </div>
        <div class="card-body">
            <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
            <tr>
                <th>Date</th>
                <th>Current Status</th>
                <th>Priority</th>
                <th>Selling Type</th>
                <th>Customer Name</th>
                <th>Customer Phone</th>
                <th>Customer Email</th>
                <th>Sales Person</th>
                <th>Brands & Model</th>
                <th>Custom Model & Brand</th>
                <th>Lead Source</th>
                <th>Strategies</th>
                <th>Preferred Language</th>
                <th>Destination</th>
                <th>Remarks & Messages</th>
                <th>Sales Notes</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
           </div>
        </div>
<script>
    $(document).ready(function() {
        var table = $('#dtBasicExample2').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('calls.datacenter') }}", 
            "columns": [
                { data: 'created_at', name: 'created_at' },
                { data: 'status', name: 'status' },
                { data: 'priority', name: 'priority' },
                { data: 'type', name: 'type' },
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'salesperson', name: 'salesperson' },
                { data: 'brand_model', name: 'brand_model' },
                { data: 'custom_brand_model', name: 'custom_brand_model' },
                { data: 'leadsource', name: 'leadsource' },
                { data: 'strategies', name: 'strategies' },
                { data: 'language', name: 'language' },
                { data: 'location', name: 'location' },
                { data: 'remarks', name: 'remarks' },
                { data: 'sales_remarks_coming', name: 'sales_remarks_coming' },
            ]
        });
        $('#dtBasicExample2 thead tr:eq(0) th').each(function(i) {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        $('input', this).on('keyup change', function() {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });
});
</script>
            </div>
            @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
            @endsection