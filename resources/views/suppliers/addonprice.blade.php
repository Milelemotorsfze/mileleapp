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
                        <!-- <a data-toggle="popover" data-trigger="hover" title="Add New Price" data-placement="top" class="btn btn-sm btn-success"
                              href=""><i class="fa fa-plus" aria-hidden="true"></i></a> -->
                              <a id="addnewAddonButton" data-toggle="popover" data-trigger="hover" title="Addon Prices" data-placement="top" class="btn btn-sm btn-warning modal-button" data-modal-id="createNewAddon"><i class="fa fa-plus" aria-hidden="true"></i></a>
                              <div class="overlay"></div>
            <div class="modal" id="createNewAddon" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenteredLabel" style="text-align:center;"> Add New Purchase Price </h5>
                            <button type="button" class="btn btn-secondary btn-sm close form-control" data-dismiss="modal" aria-label="Close" onclick="closemodal()">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row modal-row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <span class="error">* </span>
                                        <label for="name" class="col-form-label text-md-end ">Add New Purchase Price</label>
                                    </div>
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                    <input hidden id="" type="text" class="form-control @error('name') is-invalid @enderror" name="id" value="{{}}" placeholder="Enter New Purchase Price" value="{{ old('name') }}"  autofocus>

                                        <input id="new_addon_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter New Purchase Price" value="{{ old('name') }}"  autofocus>
                                        <span id="newAddonError" class="required-class paragraph-class"></span>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" onclick="closemodal()"><i class="fa fa-times"></i> Close</button> -->
                            <button type="button" class="btn btn-primary btn-sm" id="createAddonId" style="float: right;"><i class="fa fa-check" aria-hidden="true"></i> Submit</button>
                        </div>
                    </div>
                </div>
            </div>
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
    $('#createAddonId').on('click', function()
        {
            // create new addon and list new addon in addon list
            var value = $('#new_addon_name').val();
            if(value == '')
            {
                document.getElementById("newAddonError").textContent='Addon Name is Required';
            }
            else
            {
                currentAddonType =  $('#addon_type').val();
                $.ajax
                ({
                    url:"{{url('createNewSupplierAddonPrice')}}",
                    type: "POST",
                    data:
                    {
                        name: value,
                        addon_type: currentAddonType,
                        _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result)
                    {
                        $('.overlay').hide();
                        $('.modal').removeClass('modalshow');
                        $('.modal').addClass('modalhide');
                        $('#addon_id').append("<option value='" + result.id + "'>" + result.name + "</option>");
                        $('#addon_id').val(result.id);
                        var selectedValues = new Array();
                        resetSelectedSuppliers(selectedValues);
                        $('#addnewAddonButton').hide();
                        $('#new_addon_name').val("");
                        document.getElementById("newAddonError").textContent='';
                        $msg = "";
                        removeAddonNameError($msg);
                    }
                });
            }
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

   
