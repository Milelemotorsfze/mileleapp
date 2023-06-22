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
  <div class="card-header">
    <h4 class="card-title">
      Suppliers Info
    </h4>
   @canany(['demand-planning-supplier-create', 'addon-supplier-create'])
      <a class="btn btn-sm btn-success float-end" href="{{ route('suppliers.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> New Supplier
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <div class="clearfix"></div>
      <br>
      @endcanany
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
            <table id="suppliersList" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  @can('addon-supplier-list')
                  <th>Email</th>
                  <th>contact Number</th>
                  <th>Alternative Contact</th>
                  <th>Contact Person</th>
                  <th>Person Contact By</th>
                  <th>Supplier Type</th>
                  <th>Primary Payment Method</th>
                  <th>Other Payment Methods</th>
                    @endcan
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <div hidden>{{$i=0;}}</div>
                @foreach ($suppliers as $key => $supplier)
                  <tr data-id="1">
                    <td>{{ ++$i }}</td>
                    <td>{{ $supplier->supplier }}</td>
                      @can('addon-supplier-list')
                    <td>{{ $supplier->email }}</td>
                    <td>{{ $supplier->contact_number }}</td>
                    <td>{{ $supplier->alternative_contact_number }}</td>
                    <td>{{ $supplier->contact_person }}</td>
                    <td>{{ $supplier->person_contact_by }}</td>
                    <td>
                      @if(count($supplier->supplierTypes) > 0)
                        @foreach($supplier->supplierTypes as $t)
                          <label class="badge badge-soft-primary">
                            @if($t->supplier_type == 'spare_parts')
                              Spare Parts
                            @elseif($t->supplier_type == 'accessories')
                              Accessories
                            @elseif($t->supplier_type == 'freelancer')
                              Freelancer
                            @elseif($t->supplier_type == 'garage')
                              Garage
                            @elseif($t->supplier_type == 'warranty')
                              Warranty
                            @elseif($t->supplier_type == 'demand_planning')
                            Demand Planning
                            @endif
                          </label>
                        @endforeach
                      @endif
                    </td>
                    <td>
                      @if(count($supplier->paymentMethods) > 0)
                        @foreach($supplier->paymentMethods as $v)
                          @if($v->is_primary_payment_method == 'yes')
                            <label class="badge badge-soft-info">{{ $v->PaymentMethods->payment_methods }}</label>
                          @endif
                        @endforeach
                      @endif
                    </td>
                    <td>
                      @if(!empty($supplier->paymentMethods()))
                        @foreach($supplier->paymentMethods as $v)
                          @if($v->is_primary_payment_method == 'no')
                            <label class="badge badge-soft-warning">{{ $v->PaymentMethods->payment_methods }}</label>
                          @endif
                        @endforeach
                      @endif
                    </td>
                      @endcan
                    <td>
                      @if($supplier->status == 'active')
                        <label class="badge badge-soft-success">{{ $supplier->status }}</label>
                      @elseif($supplier->status == 'inactive')
                        <label class="badge badge-soft-danger">{{ $supplier->status }}</label>
                      @endif
                    </td>
                    <td> @can('supplier-addon-price')
                           <a data-toggle="popover" data-trigger="hover" title="Addon Prices" data-placement="top" class="btn btn-sm btn-warning"
                              href="{{ route('suppliers.addonprice',$supplier->id) }}"><i class="fa fa-plus" aria-hidden="true"></i></a>
                              
                        @endcan
                        @can('addon-supplier-view')
                           <a data-toggle="popover" data-trigger="hover" title="View" data-placement="top" class="btn btn-sm btn-success"
                              href="{{ route('suppliers.show',$supplier->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        @endcan
                       
                        @canany(['demand-planning-supplier-edit', 'addon-supplier-edit'])
                            <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info"
                                href="{{ route('suppliers.edit',$supplier->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @endcanany
                        @can('addon-supplier-delete')
                        @if($supplier->is_deletable)
                        <button type="button" class="btn btn-danger btn-sm supplier-delete sm-mt-3"
                            data-id="{{$supplier->id}}" data-url="{{ route('suppliers.destroy', $supplier->id) }}">
                        <i class="fa fa-trash"></i>
                    </button>
                            <!-- <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger modal-button" data-modal-id="deleteSupplier{{$supplier->id}}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                              <div class="overlay"> </div>
                              <div class="modal" id="deleteSupplier{{$supplier->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalCenteredLabel" style="text-align:center;"> Delete Supplier </h5>
                                      <button type="button" class="btn btn-secondary btn-sm close form-control" data-dismiss="modal" aria-label="Close" onclick="closemodal()">
                                        <span aria-hidden="true">X</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="row modal-row">
                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                          <h5 class="modal-paragraph"> Are you sure,</h5>
                                          <h6 class="modal-paragraph"> You want to delete the supplier ?</h6>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <a href="{{ route('suppliers.delete',$supplier->id) }}" style="float: right;" class="btn btn-sm btn-success "><i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                                    </div>
                                  </div>
                                </div>
                              </div> -->
                        @endif
                        @endcan
                      @can('supplier-active-inactive') 
                      @if($supplier->status == 'active')
                      <button title="Make Inactive" data-placement="top" class="btn btn-sm btn-secondary status-inactive-button"
                          data-id="{{ $supplier->id }}" data-status="inactive" >
                      <i class="fa fa-ban" aria-hidden="true"></i></button>
                      @elseif($supplier->status == 'inactive')
                      <a data-id="{{ $supplier->id }}" data-status="active" title="Make Active" data-placement="top" class="btn btn-sm btn-primary status-active-button" >
                      <i class="fa fa-check" aria-hidden="true"></i></a>
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
    </div>
  </div>





  <script type="text/javascript">
     $('.supplier-delete').on('click',function(){
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to Delete this Supplier ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            _method: 'DELETE',
                            id: 'id',
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            alertify.success('Supplier Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Supplier"})
        });
        $('.status-active-button').click(function (e) {
            // alert("ok");
            var status = $(this).attr('data-status');
            var id =  $(this).attr('data-id');
            statusChange(id,status)
        })
        $('.status-inactive-button').click(function (e) {
            // alert("ok");
            var status = $(this).attr('data-status');
            var id =  $(this).attr('data-id');
            statusChange(id,status)
        })

        function statusChange(id,status) {
            let url = '{{ route('suppliers.updateStatus') }}';
            if(status == 'active') {
                var message = 'Active';
            }else{
                var message = 'Inactive';
            }
            var confirm = alertify.confirm('Are you sure you want to '+ message +' this supplier ?',function (e) {
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
            }).set({title:"Status Change"})
        }
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

    function closemodal()
    {
      // $('.modal').removeClass('modalshow');
      // $('.modal').addClass('modalhide');
      // $('.overlay').hide();
      // var table = $('#suppliersList').DataTable();
    }
//     function openModal() {
//   $("#overlay").css({"display":"block"});
//   $("#modal").css({"display":"block"});
//   // $('#suppliersList').DataTable().css({"display":"block"});
// }
  </script>
@endsection
<!-- <style>
    .modal-content {
            position:fixed;
            top: 50%;
            left: 50%;
            width:30em;
            height:18em;
            margin-top: -9em; /*set to a negative number 1/2 of your height*/
            margin-left: -15em; /*set to a negative number 1/2 of your width*/
            border: 2px solid #e3e4f1;
            background-color: white;
        }
        .modal-title {
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .modal-paragraph {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
        .modal-button-class {
            margin-top: 20px;
            margin-left: 20px;
            margin-right: 20px;
        }
        .icon-right {
            z-index: 10;
            position: absolute;
            right: 0;
            top: 0;
        }
</style> -->

