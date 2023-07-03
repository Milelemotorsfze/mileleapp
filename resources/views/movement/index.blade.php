@extends('layouts.table')
@section('content')
@if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6')
    <div class="card-header">
        <h4 class="card-title">
            Movements Info
        </h4>
        @can('view-po-details')
      <a class="btn btn-sm btn-success float-end" href="{{ route('movement.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Vehicles Transaction
      </a>
      <div class="clearfix"></div>
      <br>
      <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Vehicles Transaction</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">All Vehicle Movements</a>
      </li>
    </ul>
    </div>
    @endcan
    @can('view-po-details')
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
                <th>Date</th>
                    <th>Ref No</th>
                    <th>Created By</th>
                    <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                @foreach ($movementreference as $movementreference)
                        <tr data-id="1">
                        <td>{{ date('d-m-Y', strtotime($movementreference->date)) }}</td>
                        <td>MOV - {{ $movementreference->id }}</td>
                        @php
                        $created_bys = DB::table('users')->where('id', $movementreference->created_by)->first();
                        $created_by = $created_bys->name;
                        @endphp
                        <td>{{ $created_by }}</td>
                        <td><a title="Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('movement.show', $movementreference->id) }}"><i class="fa fa-car" aria-hidden="true"></i> View Details</a></td>
                      </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>  
      </div> 
      @endcan
      @can('view-po-details')
      <div class="tab-content">
      <div class="tab-pane fade show" id="tab2"> 
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
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>Date</th>
                    <th>VIN</th>
                    <th>Model</th>
                    <th>From</th>
                    <th>To</th>
                    <th>SO</th>
                    <th>PO</th>
                </tr>
                </thead>
                         <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($data as $movements)
                        <tr data-id="1">
                        @php
                        $movementdate = DB::table('movements_reference')->where('id', $movements->reference_id)->first();
                        $date = $movementdate ? $movementdate->date : '';
                        @endphp
                        <td>{{ date('d-m-Y', strtotime($date)) }}</td>
                        <td>{{ $movements->vin }}</td>
                        <td>{{ $movements->vin }}</td>
                        @php
                        $locationfrom = DB::table('warehouse')->where('id', $movements->from)->first();
                        $from = $locationfrom ? $locationfrom->name : '';
                        @endphp
                        <td>{{ $from }}</td>
                        @php
                        $locationto = DB::table('warehouse')->where('id', $movements->to)->first();
                        $to = $locationto ? $locationto->name : '';
                        @endphp
                        <td>{{ $to }}</td>
                        @php
                        $soid = DB::table('vehicles')->where('vin', $movements->vin)->first();
                        $soids = $soid ? $soid->so_id : '';
                        $sonumber = DB::table('so')->where('id', $soids)->first();
                        $so_numbers = $sonumber ? $sonumber->so_number : '';
                        @endphp
                        <td>SO - {{ $so_numbers }}</td>
                        @php
                        $purchasingorderid = DB::table('vehicles')->where('vin', $movements->vin)->first();
                        $purchasingorderids = $purchasingorderid ? $purchasingorderid->purchasing_order_id : '';
                        $purchasing_orders = DB::table('purchasing_order')->where('id', $purchasingorderids)->first();
                        $po_number = $purchasing_orders ? $purchasing_orders->po_number : '';
                        @endphp
                        <td>PO - {{ $po_number }}</td>
                        </tr>
                        @endforeach
                        </tbody>
            </table>
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
        var dataTablea = $('#dtBasicExample1').DataTable({
    ordering: false,
    initComplete: function() {
      this.api()
        .columns()
        .every(function(d) {
          var column = this;
          var theadname = $("#dtBasicExample1 th").eq([d]).text();
          if (d === 3) {
            return;
          }
          var select = $('<select class="form-control my-1"><option value="">All</option></select>')
            .appendTo($(column.header()))
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });
          column
            .data()
            .unique()
            .sort()
            .each(function(d, j) {
              select.append('<option value="' + d + '">' + d + '</option>');
            });
        });
    }
    });
    });
    $(document).ready(function () {
    var dataTablea = $('#dtBasicExample2').DataTable({
    ordering: false,
    initComplete: function() {
      this.api()
        .columns()
        .every(function(d) {
          var column = this;
          var theadname = $("#dtBasicExample2 th").eq([d]).text();
          var select = $('<select class="form-control my-1"><option value="">All</option></select>')
            .appendTo($(column.header()))
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });
          column
            .data()
            .unique()
            .sort()
            .each(function(d, j) {
              select.append('<option value="' + d + '">' + d + '</option>');
            });
            });
            }
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
