@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">
      Suppliers Info
    </h4>
    <!-- @can('user-create') -->
      <a class="btn btn-sm btn-success float-end" href="{{ route('suppliers.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> New Supplier
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <div class="clearfix"></div>
      <br>
    <!-- @endcan -->      
  </div>
  <div class="tab-content">
    <!-- @can('user-list-active') -->
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="suppliersList" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>contact Number</th>
                  <th>Alternative Contact</th>
                  <th>Contact Person</th>
                  <th>Person Contact By</th>
                  <th>Supplier Type</th>
                  <th>Primary Payment Method</th>
                  <th>Other Payment Methods</th>
                  <!-- <th>Adoon Code</th> -->
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <div hidden>{{$i=0;}}</div>
                @foreach ($suppliers as $key => $supplier)
                  <tr data-id="1">
                    <td>{{ ++$i }}</td>
                    <td>{{ $supplier->supplier }}</td>
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
                            @endif
                          </label>
                        @endforeach
                      @endif
                    </td>
                    <td>
                      @if(count($supplier->paymentMethods) > 0)
                        @foreach($supplier->paymentMethods as $v)
                        
                      
                          @if($v->is_primary_payment_method == 'yes')
                          
                            <label class="badge badge-soft-success">{{ $v->PaymentMethods->payment_methods }}</label>
                          @endif
                         
                        @endforeach
                    
                      @endif
                    </td>
                    <td>
                      @if(!empty($supplier->paymentMethods()))
                        @foreach($supplier->paymentMethods as $v)
                          @if($v->is_primary_payment_method == 'no')
                            <label class="badge badge-soft-danger">{{ $v->PaymentMethods->payment_methods }}</label>
                          @endif
                        @endforeach
                      @endif
                    </td>
                    <!-- <td>
                      @if(!empty($supplier->supplierAddons()))
                        @foreach($supplier->supplierAddons as $v)
                          <label class="badge badge-soft-info">{{ $v->supplierAddonDetails->addon_code }}</label>
                        @endforeach
                      @endif
                    </td> -->
                    <td>
                      <!-- @can('user-view') -->
                        <a data-toggle="popover" data-trigger="hover" title="View" data-placement="top" class="btn btn-sm btn-success" href="{{ route('suppliers.show',$supplier->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                      <!-- @endcan -->
                      <!-- @can('user-edit') -->
                        <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('suppliers.edit',$supplier->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                      <!-- @endcan -->
                      <!-- @can('user-delete') -->
                        <!-- <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger modal-button" data-modal-id="deleteSupplier{{$supplier->id}}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                        <div class="modal modal-class" id="deleteSupplier{{$supplier->id}}" >
                          <div class="modal-content">
                            <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                            <h3 class="modal-title" style="text-align:center;"> Delete Supplier </h3>
                            <div class="dropdown-divider"></div>
                            <h4 class="modal-paragraph"> Are you sure,</h4>
                            <h5 class="modal-paragraph"> You want to delete the supplier ?</h5>
                            <div class="dropdown-divider"></div>
                            <div class="row modal-button-class">                                           
                              <div class="col-xs-6 col-sm-6 col-md-6">
                                <a href="{{ route('suppliers.destroy',$supplier->id) }}" style="float: right;" class="btn btn-sm btn-success "><i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                              </div>
                            </div>                                          
                          </div>
                        </div> -->
                      <!-- @endcan -->
                      <!-- @can('user-make-inactive') -->
                        <!-- <a data-toggle="popover" data-trigger="hover" title="Make Inactive" data-placement="top" class="btn btn-sm btn-secondary modal-button" data-modal-id="makeInactiveSupplier{{$supplier->id}}"><i class="fa fa-ban" aria-hidden="true"></i></a>
                        <div class="modal modal-class" id="makeInactiveSupplier{{$supplier->id}}" >
                          <div class="modal-content">
                            <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                            <h3 class="modal-title" style="text-align:center;"> Make Inactive Supplier </h3>
                            <div class="dropdown-divider"></div>
                            <h4 class="modal-paragraph"> Are you sure,</h4>
                            <h5 class="modal-paragraph"> You want to make inactive ?</h5>
                            <div class="dropdown-divider"></div>
                            <div class="row modal-button-class">                                           
                              <div class="col-xs-6 col-sm-6 col-md-6">
                                <a href="{{ route('suppliers.update',$supplier->id) }}" style="float: right;" class="btn btn-sm btn-success "><i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                              </div>
                            </div>                                          
                          </div>
                        </div> -->
                      <!-- @endcan                               -->
                    </td>                
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
    <!-- @endcan       -->
      </div><!-- end tab-content-->
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function ()
    {
      $('#suppliersList').DataTable();
      $('.modal-button').on('click', function()
      {
        var modalId = $(this).data('modal-id');
        $('#' + modalId).addClass('modalshow');
        $('#' + modalId).removeClass('modalhide');
      });
      $('.close').on('click', function()
      {
        $('.modal').addClass('modalhide');
        $('.modal').removeClass('modalshow');
      });
    });
    function closemodal()
    {
      $('.modal').removeClass('modalshow');
      $('.modal').addClass('modalhide');
    }
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
   
