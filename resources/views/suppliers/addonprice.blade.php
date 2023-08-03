@extends('layouts.table')
<style>
  .widthinput
    {
        height:32px!important;
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
        Vendor Addon prices Info
      </h4>
      <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('suppliers.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
      @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger" >
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('error') }}
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('success') }}
            </div>
        @endif
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
                    <td>{{$supplierAddon->supplierAddonDetails->AddonName->name ?? ''}}</td>
                    <td>{{$supplierAddon->supplierAddonDetails->addon_code ?? ''}}</td>
                    <td id="{{$supplierAddon->id}}">{{$supplierAddon->purchase_price_aed}} AED</td>
                    <td>
                      @can('supplier-new-purchase-price')
                      @php
                      $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-new-purchase-price']);
                      @endphp
                      @if ($hasPermission)
                      <button type="button" title="Addon Prices" class="btn btn-warning btn-sm " data-bs-toggle="modal"
                                                data-bs-target="#purchasePrice-{{$supplierAddon->id}}">
                                            <i class="fa fa-plus"></i></button>
                                            <div class="modal fade" id="purchasePrice-{{$supplierAddon->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <form id="form-update" action="{{ route('addon.createNewSupplierAddonPrice') }}" method="POST" >
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Update Prices</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-3">
                                    <div class="col-lg-12">
                                        <div class="row">
                                        <div class="row mt-2">
                                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                            <span class="error">* </span>
                                            <label for="name" class="col-form-label text-md-end ">Add New Purchase Price</label>
                                            </div>
                                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                            <input hidden id="inputId" name="id" value="{{ $supplierAddon->id }}">
                                            <input hidden id="inputSupplierId" name="supplier_id" value="{{ $supplierAddon->supplier_id }}">
                                            <div class="input-group">
                                                <input id="new_addon_name_{{$supplierAddon->id}}" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter New Purchase Price"
                                                value="{{ old('name') }}" autofocus oninput="inputNumberAbs(this)" required>
                                                <div class="input-group-append">
                                                <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                </div>
                                            </div>
                                            <span id="newAddonError" class="required-class paragraph-class"></span>
                                            </div>
                                        </div>
                                        </div>
                                        </br>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary ">Submit</button>
                                    </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                            </div>

                      @endif
                      @endcan
                      @can('supplier-addon-delete')
                      @php
                      $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-delete']);
                      @endphp
                      @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger modal-button" data-modal-id="">
                         <i class="fa fa-trash" aria-hidden="true"></i></a>
                      @endif
                      @endcan
                      @can('supplier-addon-purchase-price-history')
                      @php
                      $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-history']);
                      @endphp
                      @if ($hasPermission)
                      <a title="View History" class="btn btn-sm btn-info modal-button" href="{{ route('suppliers.purchasepricehistory',$supplierAddon->id) }}">
                         <i class="fa fa-history" aria-hidden="true"></i></a>
                         @endif
                         @endcan
                    </td>
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

        // $('.overlay').show();
        table.destroy();
      });
      $('.close').on('click', function()
      {
        $('.modal').addClass('modalhide');
        $('.modal').removeClass('modalshow');
        // $('.overlay').hide();
        $('#suppliersList').DataTable();
      });
    });
    $('#createAddonId').on('click', function()
        {
            // create new addon and list new addon in addon list
            var value = $('#new_addon_name').val();
            var inputId = $('#inputId').val();
            if(value == '')
            {
                document.getElementById("newAddonError").textContent='Addon Name is Required';
            }
            else
            {
                $.ajax
                ({
                    url:"{{url('createNewSupplierAddonPrice')}}",
                    type: "POST",
                    data:
                    {
                        name: value,
                        inputId:inputId,
                        _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result)
                    {
                      console.log('#'+inputId);
                        // $('.overlay').hide();
                        $('.modal').removeClass('modalshow');
                        $('.modal').addClass('modalhide');
                        $msg = result.purchase_price_aed + "AED";
                        document.getElementById(inputId).textContent=$msg;
                        // $('#'+inputId).val(result.purchase_price_aed);
                        // $('#addon_id').append("<option value='" + result.id + "'>" + result.name + "</option>");
                        // $('#addon_id').val(result.id);
                        // var selectedValues = new Array();
                        // resetSelectedSuppliers(selectedValues);
                        // $('#addnewAddonButton').hide();
                        // $('#new_addon_name').val("");
                        // document.getElementById("newAddonError").textContent='';
                        // $msg = "";
                        // removeAddonNameError($msg);
                    }
                });
            }
        });





    // $('#createAddonId').on('click', function()
    //     {
    //         // create new addon and list new addon in addon list
    //         var value = $('#new_addon_name').val();
    //         var inputId = $('#inputId').val();
    //         if(value == '')
    //         {
    //             document.getElementById("newAddonError").textContent='Addon Name is Required';
    //         }
    //         else
    //         {
    //             $.ajax
    //             ({
    //                 url:"{{url('createNewSupplierAddonPrice')}}",
    //                 type: "POST",
    //                 data:
    //                 {
    //                     name: value,
    //                     inputId:inputId,
    //                     _token: '{{csrf_token()}}'
    //                 },
    //                 dataType : 'json',
    //                 success: function(result)
    //                 {
    //                   console.log('#'+inputId);
    //                     // $('.overlay').hide();
    //                     $('.modal').removeClass('modalshow');
    //                     $('.modal').addClass('modalhide');
    //                     $msg = result.purchase_price_aed + "AED";
    //                     document.getElementById(inputId).textContent=$msg;
    //                     // $('#'+inputId).val(result.purchase_price_aed);
    //                     // $('#addon_id').append("<option value='" + result.id + "'>" + result.name + "</option>");
    //                     // $('#addon_id').val(result.id);
    //                     // var selectedValues = new Array();
    //                     // resetSelectedSuppliers(selectedValues);
    //                     // $('#addnewAddonButton').hide();
    //                     // $('#new_addon_name').val("");
    //                     // document.getElementById("newAddonError").textContent='';
    //                     // $msg = "";
    //                     // removeAddonNameError($msg);
    //                 }
    //             });
    //         }
    //     });
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
        function inputNumberAbs(currentPriceInput)
        {
            var id = currentPriceInput.id;
            var input = document.getElementById(id);
            var val = input.value;
            val = val.replace(/^0+|[^\d.]/g, '');
            if(val.split('.').length>2)
            {
                val =val.replace(/\.+$/,"");
            }
            input.value = val;
        }
  </script>
@endsection


