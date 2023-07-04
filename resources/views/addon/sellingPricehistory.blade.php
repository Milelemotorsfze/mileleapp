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
  .paragraph-class
    {
        color: red;
        font-size:11px;
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
        Addon Selling Prices History
      </h4>
      <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                    <!-- <td>
                        @if($historyData->status == 'pending')
                        <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info"
                                href=""><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a data-id="{{ $historyData->id }}" data-status="active" title="Edit" data-placement="top" class="btn btn-sm btn-info price-edit-button" >
                      <i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a data-id="{{ $historyData->id }}" data-status="active" title="Approved" data-placement="top" class="btn btn-sm btn-success approve" >
                      <i class="fa fa-check" aria-hidden="true"></i></a>
                                <button title="Rejected" data-placement="top" class="btn btn-sm btn-danger reject"
                          data-id="{{ $historyData->id }}" data-status="rejected" >
                      <i class="fa fa-ban" aria-hidden="true"></i></button>
                    
                        @endif
                    </td> -->
                    <td>
                    @if($historyData->status == 'pending')
                    @can('edit-addon-new-selling-price')
                    @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price']);
                                @endphp
                                @if ($hasPermission)
                                            <button type="button" class="btn btn-primary btn-sm " data-bs-toggle="modal"
                                                    data-bs-target="#edit-selling-price-{{$historyData->id}}">
                                                <i class="fa fa-edit"></i></button>
                                                @endif
                                       @endcan
                                       @can('approve-addon-new-selling-price')
                                       @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['approve-addon-new-selling-price']);
                                        @endphp
                                        @if ($hasPermission)
                                            <button type="button" title="Approved" class="btn btn-success btn-sm"  data-bs-toggle="modal"
                                                    data-bs-target="#approve-selling-price-{{$historyData->id}}">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                            </button>
                                            @endif
                                            @endcan
                                            @can('reject-addon-new-selling-price')
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['reject-addon-new-selling-price']);
                                            @endphp
                                            @if ($hasPermission)
                                            <button type="button" title="Rejected" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#reject-selling-price-{{$historyData->id}}">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                            </button>
                                            @endif
                                        @endcan
                                        @endif
                                    </td>
                                    <div class="modal fade" id="edit-selling-price-{{$historyData->id}}"  tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <form id="form-update" action="{{ route('addon.UpdateSellingPrice', $historyData->id) }}"
                                                  method="POST" >
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Selling Price</h1>
                                                        <button type="button" class="btn-close closeUpdateSelling" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-3">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13 text-muted">Selling Price</label>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="input-group">
                                                                            <input id="updateSellingPriceId" oninput="inputNumberAbs(this)" name="selling_price" class="form-control"
                                                                                   placeholder="Enter Selling Price" value="{{$historyData->selling_price}}"
                                                                                    >
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                            </div>
                                                                            <span id="updateSellingPriceError" class="invalid-feedback"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary closeUpdateSelling" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary ">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="approve-selling-price-{{$historyData->id}}"
                                         tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Addon Selling Price Approval</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row mt-2">
                                                                    @if($currentPrice)
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13 text-center">Current Price</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                                                        
                                                                               <div class="input-group">
                                                                               <input type="text" value="{{$currentPrice->selling_price}}"
                                                                               class="form-control" readonly >
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13">New Price</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                                                        
                                                                               <div class="input-group">
                                                                               <input type="text" value="{{$historyData->selling_price}}"
                                                                               id="updated-price"  class="form-control" readonly >
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                 
                                                    <button type="button" class="btn btn-primary approve"
                                                            data-id="{{ $historyData->id }}" data-status="active">Approve</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="reject-selling-price-{{$historyData->id}}"
                                         tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Addon Selling Price Rejection</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row mt-2">
                                                                    @if($currentPrice)
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <label class="form-label font-size-13 text-center">Current Price</label>
                                                                        </div>
                                                                        <div class="col-lg-9 col-md-12 col-sm-12">
                                                                                <div class="input-group">
                                                                                <input type="text" value="{{$currentPrice->selling_price}}"
                                                                                class="form-control" readonly >
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13">New Price</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                                                        
                                                                               <div class="input-group">
                                                                               <input type="text" value="{{$historyData->selling_price}}"
                                                                               id="updated-price"  class="form-control" readonly >
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary  reject" data-id="{{ $historyData->id }}"
                                                            data-status="rejected">Reject</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    var data = {!! json_encode($historyData) !!};
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

@push('scripts')
    <script>
        $('.approve').click(function (e) {
            // alert("ok");
            var status = $(this).attr('data-status');
            var id =  $(this).attr('data-id');
            statusChange(id,status)
        })
        $('.reject').click(function (e) {
            // alert("ok");
            var status = $(this).attr('data-status');
            var id =  $(this).attr('data-id');
            statusChange(id,status)
        })

        function statusChange(id,status) {
            let url = '{{ route('addon-selling-price.status-change') }}';
            if(status == 'active') {
                var message = 'Approve';
            }else{
                var message = 'Reject';
            }
            var confirm = alertify.confirm('Are you sure you want to '+ message +' this addon selling price ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            id: id,
                            status: status,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            window.location.reload();
                            alertify.success(status + " Successfully");
                        }
                    });
                }
            }).set({title: message +" Addon Selling Price"})
        }
        function inputNumberAbs(currentPriceInput) 
        { 
            var id = currentPriceInput.id
            var input = document.getElementById(id);
            var val = input.value;
            val = val.replace(/^0+|[^\d.]/g, '');
            if(val.split('.').length>2) 
            {
                val =val.replace(/\.+$/,"");
            }
            input.value = val;
            if(currentPriceInput.id == 'updateSellingPriceId')
            {
                var value = currentPriceInput.value;
                if(value == '')
                {
                   
                    if(value.legth != 0)
                    {
                        $msg = "Selling Price is required";
                        showSellingPriceError($msg);
                    }
                }
                else
                {
                    removeSellingPriceError();
                }
            }
        }
        function showSellingPriceError($msg)
        {
            document.getElementById("updateSellingPriceError").textContent=$msg;
            document.getElementById("updateSellingPriceId").classList.add("is-invalid");
            document.getElementById("updateSellingPriceError").classList.add("paragraph-class");
        }
        function removeSellingPriceError()
        {
            document.getElementById("updateSellingPriceError").textContent="";
            document.getElementById("updateSellingPriceId").classList.remove("is-invalid");
            document.getElementById("updateSellingPriceError").classList.remove("paragraph-class");
        }
        $('form').on('submit', function (e)
        {
            var formInputError = false;
            var inputupdateSellingPriceId = $('#updateSellingPriceId').val();
            if(inputupdateSellingPriceId == '')
            {
                $msg = "Selling Price is required";
                showSellingPriceError($msg);
                formInputError = true;
            }
            if(formInputError == true)
            {
                e.preventDefault();
            }
        });
        $(".closeUpdateSelling").click(function()
        {
            removeSellingPriceError();
            $("#updateSellingPriceId").val(data.selling_price);
        });
    </script>
@endpush
