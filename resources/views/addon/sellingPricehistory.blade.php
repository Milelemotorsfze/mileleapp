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
    <div class="card-header">
      <h4 class="card-title">
        Supplier Addon prices History
      </h4>
      <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                  <th>Approved/Rejected Date And Time</th>
                  <th>Approved/Rejected By</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <div hidden>{{$i=0;}}</div>
                @foreach ($history as $key => $historyData)
                  <tr data-id="1">
                    <td>{{++$i}}</td>
                    <td >{{$historyData->selling_price}} AED</td>
                    <td>
                        @if($historyData->status == 'active')
                        <label class="badge badge-soft-success">Active</label>
                        @elseif($historyData->status == 'inactive')
                        <label class="badge badge-soft-secondary">Inactive</label>
                        @elseif($historyData->status == 'rejected')
                        <label class="badge badge-soft-danger">Rejected</label>
                        @elseif($historyData->status == 'pending')
                        <label class="badge badge-soft-info">Pending</label>
                        @endif
                    </td>
                    <td>{{$historyData->created_at}}</td>  
                    <td>{{$historyData->CreatedBy->name}}</td> 
                    @isset($historyData->StatusUpdatedBy)
                    <td>{{$historyData->updated_at}}</td>  
                    <td>{{$historyData->StatusUpdatedBy->name}}</td> 
                    @else
                    <td></td>
                    <td></td>
                    @endif
                    <td>
                        @if($historyData->status == 'pending')
                        <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info"
                                href=""><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @endif
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

   
