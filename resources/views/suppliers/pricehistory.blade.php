@extends('layouts.table')
<style>
  .overlay
  {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(128,128,128,0.5);
    display: none;
    opacity:0.5;
  }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
@section('content')
  @can('supplier-addon-price')
  @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-price']);
  @endphp
  @if ($hasPermission)
    <div class="card-header">
      <h4 class="card-title">
        Vendor Addon prices History
      </h4>
      <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('suppliers.addonprice',$supplierId) }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1">
        <div class="card-body">
          <div class="table-responsive">
            <table id="supplierAddonPrices" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Purchase Price</th>
                  <th>Status</th>
                  <th>Created Date And Time</th>
                  <th>Created By</th>
                </tr>
              </thead>
              <tbody>
                <div hidden>{{$i=0;}}</div>
                @foreach ($history as $key => $historyData)
                  <tr data-id="1">
                    <td>{{++$i}}</td>
                    <td >{{$historyData->purchase_price_aed}} AED</td>
                    <td >{{$historyData->status}}</td>
                    <td>{{$historyData->created_at}}</td>
                    <td>{{$historyData->CreatedBy->name ?? ''}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  @endif
  @endcan
  <script type="text/javascript">
    $(document).ready(function ()
    {
      $('#supplierAddonPrices').DataTable();
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function ()
    {
      $('#suppliersList').DataTable();
      var table = $('#suppliersList').DataTable();
      $('.modal-button').on('click', function()
      {
        var modalId = $(this).data('modal-id');
        $('#' + modalId).addClass('modalshow');
        $('#' + modalId).removeClass('modalhide');

        $('.overlay').show();
        table.destroy();
      });
      $('.close').on('click', function()
      {
        $('.modal').addClass('modalhide');
        $('.modal').removeClass('modalshow');
        $('.overlay').hide();
        $('#suppliersList').DataTable();
      });
    });

    // function closemodal()
    // {
    //   $('.modal').addClass('modalhide');
    //     $('.modal').removeClass('modalshow');
    //     $('.overlay').hide();
    //     $('#suppliersList').DataTable();
    //   // $('.modal').removeClass('modalshow');
    //   // $('.modal').addClass('modalhide');
    //   // $('.overlay').hide();
    //   // var table = $('#suppliersList').DataTable();
    // }
//     function openModal() {
//   $("#overlay").css({"display":"block"});
//   $("#modal").css({"display":"block"});
//   // $('#suppliersList').DataTable().css({"display":"block"});
// }
  </script>
@endsection


