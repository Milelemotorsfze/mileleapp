@extends('layouts.table')
@section('content')
@if (Auth::user()->selectedRole === '2' || Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6'|| Auth::user()->selectedRole === '8'|| Auth::user()->selectedRole === '9'|| Auth::user()->selectedRole === '10'|| Auth::user()->selectedRole === '11'|| Auth::user()->selectedRole === '12'|| Auth::user()->selectedRole === '21'|| Auth::user()->selectedRole === '22')
    <div class="card-header">
        <h4 class="card-title">
            Purchase Orders
        </h4>
      @can('create-po-details')
      @if (Auth::user()->selectedRole === '9'|| Auth::user()->selectedRole === '10')
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-success float-end" href="{{ route('purchasing-order.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Purchasing Order
      </a>
      <div class="clearfix"></div>
      <br>
      @endif
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
                    <th>Status</th>
                    <th>Vehicles Details</th>
                    @can('edit-po-details')
                    @if (Auth::user()->selectedRole === '9'|| Auth::user()->selectedRole === '10'|| Auth::user()->selectedRole === '21'|| Auth::user()->selectedRole === '22')
                    <th>Update & Edit</th>
                    @endif
                    @endcan
                    @can('delete-po-details')
                    @if (Auth::user()->selectedRole === '9'|| Auth::user()->selectedRole === '10')
                    <th>Cancel PO</th>
                    @endif
                    @endcan
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($data as $purchasingOrder)
                    <tr data-id="1">
                        <td>{{ $purchasingOrder->po_date}}</td>
                        <td>{{ $purchasingOrder->po_number }}</td>
                        <td>
                            @if ($purchasingOrder->status == 'Active')
                                <span class="btn btn-sm btn-info">Active</span>
                            @elseif ($purchasingOrder->status == 'Deactive')
                                <span class="btn btn-sm btn-danger">Deactive</span>
                            @elseif ($purchasingOrder->status == 'Complete')
                                <span class="btn btn-sm btn-success">Complete</span>
                            @endif
                        </td>
                        <td><a title="Vehicles Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('purchasing-order.viewdetails', $purchasingOrder->id) }}"><i class="fa fa-car" aria-hidden="true"></i> View Details</a></td>
                        @can('edit-po-details')
                        @if (Auth::user()->selectedRole === '9'|| Auth::user()->selectedRole === '10'|| Auth::user()->selectedRole === '21'|| Auth::user()->selectedRole === '22')
                        <td><a title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('purchasing-order.edit',$purchasingOrder->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Update</a></td>
                       @endif
                        @endcan
                        @can('delete-po-details')
                        @if (Auth::user()->selectedRole === '9'|| Auth::user()->selectedRole === '10')
                        <td>
                        <a title="Cancel" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('podelete.deletes',$purchasingOrder->id) }}" onclick="return confirmCancel();">
                            <i class="fa fa-times" aria-hidden="true"></i> Cancel
                        </a>
                        </td>
                        @endif
                        @endcan
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
    <script>
  function confirmCancel() {
    var confirmDialog = confirm("Are you sure you want to cancel this purchase order?");
    if (confirmDialog) {
      return true;
    } else {
      return false;
    }
  }
</script>
        @endcan
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
