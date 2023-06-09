@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Purchasing Orders
        </h4>
        @can('create-po-details')
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-success float-end" href="{{ route('purchasing-order.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Purchasing Order
      </a>
      <div class="clearfix"></div>
      <br>
      @endcan
    </div>
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
    @can('view-po-details')
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>PO Date</th>
                    <th>PO Number</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($data as $purchasingOrder)
                    <tr data-id="1">
                        <td>{{ $purchasingOrder->po_date}}</td>
                        <td>{{ $purchasingOrder->po_number }}</td>
                        <td><a title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('purchasing-order.edit',$purchasingOrder->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <script>
        // Set timer for error message
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);

        // Set timer for success message
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
    </script>
        @endcan
    </div>
@endsection
