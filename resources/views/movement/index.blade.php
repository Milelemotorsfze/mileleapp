@extends('layouts.table')
<style>
    #dtBasicExample2 {
        width: 100%;
    }
</style>
@section('content')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('View-daily-movemnets');
                    @endphp
                    @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">
            Movements Info
        </h4>
        @can('View-daily-movemnets')
        @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-daily-movemnets');
                    @endphp
                    @if ($hasPermission)
      <a class="btn btn-sm btn-success float-end" href="{{ route('movement.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Movement Transection
      </a>
      <div class="clearfix"></div>
      @endif
      <br>
      <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Movement Transection</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">All Vehicle Movements</a>
      </li>
    </ul>
    </div>
    @endcan
    @can('View-daily-movemnets')
    <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
    <div class="card-body">
    @if ($errors->has('source_name'))
            <div id="error-message" class="alert alert-danger">
                {{ $errors->first('source_name') }}
            </div>
        @endif

        @if (session('error'))
            <div id="error-message" class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive" >
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                <th>Movement Batch</th>
                <th>Vehicle Quantity</th>
                <th>Created By</th>
                <th>Created Date</th>
                <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($movementreference as $movementreference)
                        <tr data-id="1">
                        <td>MOV - {{ $movementreference->id }}</td>
                        @php
                        $vehicles = DB::table('movements')->where('reference_id', $movementreference->id)->count();
                        @endphp
                        <td>{{$vehicles}}</td>
                        @php
                        $created_bys = DB::table('users')->where('id', $movementreference->created_by)->first();
                        $created_by = $created_bys->name;
                        @endphp
                        <td>{{ $created_by }}</td>
                        <td>{{ date('d-M-Y', strtotime($movementreference->date)) }} {{ date('H:i:s', strtotime($movementreference->created_at)) }}</td>
                        <td><a title="Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('movement.show', $movementreference->id) }}"><i class="fa fa-car" aria-hidden="true"></i> View Details</a></td>
                      </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
        </div>  
      </div> 
      @endcan
      @can('View-daily-movemnets')
      <div class="tab-content">
    <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
            <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
            <tr>
                <th>Date</th>
                <th>VIN</th>
                <th>Model Detail</th>
                <th>From Name</th>
                <th>To Name</th>
                <th>SO Number</th>
                <th>PO Number</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
           </div>
        </div>
    </div>
</div>
      @endcan
        <script>
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
        $(document).ready(function () {
            var dataTable = $('#dtBasicExample1').DataTable({
  pageLength: 10,
  initComplete: function() {
    this.api().columns().every(function(d) {
      var column = this;
      var columnId = column.index();
      var columnName = $(column.header()).attr('id');
      if (d === 4) {
        return;
      }

      var selectWrapper = $('<div class="select-wrapper"></div>');
      var select = $('<select class="form-control my-1" multiple><option value="">All</option></select>')
        .appendTo(selectWrapper)
        .select2({
          width: '100%',
          dropdownCssClass: 'select2-blue'
        });
      select.on('change', function() {
        var selectedValues = $(this).val();
        column.search(selectedValues ? selectedValues.join('|') : '', true, false).draw();
      });

      selectWrapper.appendTo($(column.header()));
      $(column.header()).addClass('nowrap-td');
      
      column.data().unique().sort().each(function(d, j) {
        select.append('<option value="' + d + '">' + d + '</option>');
      });
    });
  }
});
    });
            </script>
<script>
    $(document).ready(function() {
        var table = $('#dtBasicExample2').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('movement.index') }}", // Change this to your actual route

            // Disable sorting for all columns
            "ordering": false,

            // Define column-specific filters
            "columns": [
                { data: 'date', name: 'date' },
                { data: 'vin', name: 'vin' },
                { data: 'model_detail', name: 'model_detail' },
                { data: 'from_name', name: 'from_name' },
                { data: 'to_name', name: 'to_name' },
                { data: 'so_number', name: 'so_number' },
                { data: 'po_number', name: 'po_number' }
            ]
        });

        // Add individual column filters
        $('#dtBasicExample2 thead tr').clone(true).appendTo('#dtBasicExample2 thead');
        $('#dtBasicExample2 thead tr:eq(1) th').each(function(i) {
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