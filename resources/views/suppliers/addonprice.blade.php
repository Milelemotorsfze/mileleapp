@extends('layouts.table')
@section('content')
  @can('supplier-addon-price')
    <div class="card-header">
      <h4 class="card-title">
        Supplier Addon prices Info
      </h4>
      <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('suppliers.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="supplierAddonPrices" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Addon Name</th>
                  <th>Addon Code</th>
                  <th>Current Purchase Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <div hidden>{{$i=0;}}</div>
                @foreach ($supplierAddons as $key => $supplierAddon)
                  <tr data-id="1">
                    <td>{{++$i}}</td>
                    <td>{{$supplierAddon->supplierAddonDetails->AddonName->name}}</td>                   
                    <td>{{$supplierAddon->supplierAddonDetails->addon_code}}</td>
                    <td>{{$supplierAddon->purchase_price_aed}} AED</td>
                    <td>
                      @can('supplier-new-purchase-price')
                        <a data-toggle="popover" data-trigger="hover" title="Add New Price" data-placement="top" class="btn btn-sm btn-success"
                              href=""><i class="fa fa-plus" aria-hidden="true"></i></a>
                      @endcan
                      @can('supplier-addon-delete')
                        <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger modal-button" data-modal-id="">
                         <i class="fa fa-trash" aria-hidden="true"></i></a>
                      @endcan
                      <a data-toggle="popover" data-trigger="hover" title="View History" data-placement="top" class="btn btn-sm btn-info modal-button" data-modal-id="">
                         <i class="fa fa-history" aria-hidden="true"></i></a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>  
        </div>  
      </div>
    </div>
  @endcan
  <script type="text/javascript">
    $(document).ready(function ()
    {
      $('#supplierAddonPrices').DataTable();
    });
  </script>
@endsection

   
