@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  /* padding: 4px 8px 4px 8px; */
  text-align: center;
  vertical-align: middle;
}
.table-wrapper {
      position: relative;
    }
    thead th {
      position: sticky!important;
      top: 0;
      background-color: rgb(194, 196, 204)!important;
      z-index: 1;
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
  </style>
@section('content')
  <div class="card-header">
    <h4 class="card-title">
     Shipping Rates: {{$shippingmendium->name}} - {{$shippingmendium->code}}
     <a  class="btn btn-sm btn-info float-end" href="{{ route('Shipping.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
     <a class="btn btn-sm btn-success float-end" href="{{ route('shipping_medium.openmedium_create', ['id' => $shippingmendium->id]) }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New
      </a>
    </h4>
    <br>
  </div>

        <div class="card-body">
        @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
        {!! $html->table(['class' => 'table table-bordered table-striped table-responsive ']) !!}
        </div>  

      </div>
    </div>
  </div>
@endsection
@push('scripts')
        {!! $html->scripts() !!}
    @endpush