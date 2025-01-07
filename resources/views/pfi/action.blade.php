
<div class="dropdown">
    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
    <i class="fa fa-bars" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-start">      
       
        @can('pfi-edit')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('pfi-edit');
        @endphp
        @if ($hasPermission)
            @if($pfi->is_toyota_pfi == 1 || ($pfi->is_toyota_pfi == 0 && !$isExistPO) )
                    <!-- if Other brand pfi => show if po not exist -->
                    <li>
                        <a class="btn btn-info btn-sm" style="width:100%; margin-top:2px; margin-bottom:2px;"  title="To Edit PFI" href="{{ route('pfi.edit', $pfi->id) }}">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    <li>
            @endif
        <li>
            <button type="button" style="width:100%; margin-top:2px; margin-bottom:2px; font-size:12px;"  class="btn btn-info btn-sm" title="To Update Released Amount"
                data-bs-toggle="modal" data-bs-target="#update-released-amount-{{$pfi->id}}">
                <i class="fa fa-euro-sign"></i> Released Amount
            </button>
        </li>
        @endif
    @endcan
    @can('PFI-list')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-list');
    @endphp
    @if ($hasPermission)
        <li>
            <button type="button" class="btn btn-primary btn-sm"  style="width:100%; margin-top:2px; margin-bottom:2px;" title="To view PFI Document" data-bs-toggle="modal" data-bs-target="#view-pfi-docs-{{$pfi->id}}">
                <i class="fa fa-file-pdf"></i> View PFI Document
            </button>
        </li>
        <li>
            <button type="button" class="btn btn-primary btn-sm"  style="width:100%; margin-top:2px; margin-bottom:2px;" 
            title="To View PFI Items" data-bs-toggle="modal" data-bs-target="#view-pfi-items-{{$pfi->id}}">
                <i class="fa fa-list"></i> View PFI Items
            </button>
        </li>
        @endif
    @endcan
    @can('create-demand-planning-po')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-demand-planning-po');
        @endphp
        @if ($hasPermission)
            @if($showCreatePOBtn == 1) 
            <li>
                <a class="btn btn-info btn-sm" style="width:100%; margin-top:2px; margin-bottom:2px;" 
                title="To Create PO" href="{{ route('demand-planning-purchase-orders.create', ['id' => $pfi->id]) }}">
                    <i class="fa fa-plus"></i> Create PO
                </a>
            <li> 
            @endif
        @endif
        @endcan

   
        @can('pfi-delete')
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('pfi-delete');
            @endphp
            @if ($hasPermission)
                @if(!$isExistPO)
                    <li>
                        <button type="button" style="width:100%; margin-top:2px; margin-bottom:2px;" class="btn btn-danger btn-sm pfi-button-delete mt-1" title="Delete PFI"
                                data-id="{{ $pfi->id }}" data-url="{{ route('pfi.destroy', $pfi->id) }}">
                            <i class="fa fa-trash"></i> To Delete PFI
                        </button>
                    </li>
                @endif
            @endif
        @endcan
    </ul>
</div>

        <!-- PFI released amount update Model -->
        <div class="modal fade " id="update-released-amount-{{$pfi->id}}" data-bs-backdrop="static" 
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"> Update Released Amount</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                            <div class="modal-body">
                                <div class="col-lg-12">
                                <label class="form-label font-size-13">Released Date</label>
                                    <div class="row p-2">
                                        <input type="date" name="released_date" value="{{ Illuminate\Support\Carbon::parse($pfi->released_date)->format('Y-m-d') }}"
                                         required class="form-control" id="released_date_{{$pfi->id}}" >
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                <label class="form-label font-size-13">Released Amount</label>
                                    <div class="row p-2">
                                        <input type="number" min="0" value="{{ $pfi->released_amount }}" required name="released_amount" 
                                        class="form-control" id="released_amount_{{$pfi->id}}" placeholder="Enter Amount" min="1" >
                                        <span id="released-amount-error-{{ $pfi->id }}" class="text-danger"> </span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden"  value="{{ $pfi->id }}" name="pfi_id" id="pfi_id">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info released-detail-update" data-id="{{$pfi->id}}">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                    </div>
                </div>
            </div>

           
            <!--  PFI PFI DOCS VIEW MODAL -->

        <div class="modal fade " id="view-pfi-docs-{{$pfi->id}}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"> PFI Document</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12">
                                @if($pfi->new_pfi_document_without_sign)
                                    <label class="fw-bold">Old PFI Document</label>
                                @endif
                                @if($pfi->pfi_document_without_sign)
                                    <div class="row p-2 justify-content-center">
                                        <div class="col-md-2">
                                            <a href="{{ url('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign) }}" width="100px"
                                            class="btn btn-primary mb-2 text-center" download="{{ $oldPFIFileName }}">
                                            Download <i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                        </div>
                                        <embed src="{{ url('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign) }}" height="400" >
                                    </div>
                                @endif
                            @if($pfi->new_pfi_document_without_sign)
                            <div class="row p-2 mt-3 justify-content-center">
                                <label class="fw-bold">Latest PFI Document</label>
                                <div class="col-md-2">
                                    <a href="{{ url('New_PFI_document_without_sign/'.$pfi->new_pfi_document_without_sign) }}" width="100px"
                                    class="btn btn-primary mb-2 text-center" download="{{ $newPFIFileName }}">
                                    Download <i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                </div>
                                <embed src="{{ url('New_PFI_document_without_sign/'.$pfi->new_pfi_document_without_sign) }}" height="400" >
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- PFI Items Modal -->
        <div class="modal fade" id="view-pfi-items-{{$pfi->id}}" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"> PFI Items</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($parentPfiItems->count() > 0)
                            <div class="row  d-none d-lg-block d-xl-block d-xxl-block">
                                <div class="d-flex">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row">
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt>LOI Item Code</dt>
                                            </div>
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt>Model</dt>
                                            </div>
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt>SFX</dt>
                                            </div>
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt>Unit Price</dt>
                                            </div>
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt>Quantity</dt>
                                            </div>
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt>Total Price ({{ $pfi->currency }})</dt>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach($parentPfiItems as $value => $pfiItem)
                                <div class="row">
                                    <div class="d-flex">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <hr>
                                            <div class="row mt-3">
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none">LOI Item Code</dt>
                                                    <dl> {{ $pfiItem->loi_item_code }} </dl>
                                                </div>
                                              
                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Model</dt>
                                                    <dl> {{ $pfiItem->masterModel->model ?? ''}} </dl>
                                                </div>                                              
                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">SFX</dt>
                                                    <dl> {{ $pfiItem->masterModel->sfx ?? ''}} </dl>
                                                </div>
                                              
                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">Unit Price</dt>
                                                    <dl>{{ $pfiItem->unit_price ?? ''}} </dl>
                                                </div>
                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">Quantity</dt>
                                                    <dl>{{ $pfiItem->pfi_quantity }}</dl>
                                                </div>
                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">Total Price ({{ $pfi->currency }})</dt>
                                                    <dl>{{ $pfiItem->unit_price * $pfiItem->pfi_quantity }}  </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
                                      
<script>
    
    $('.pfi-button-delete').on('click',function(){
        let id = $(this).attr('data-id');
        let url =  $(this).attr('data-url');
        var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
          
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
                        var table1 = $('#PFI-table').DataTable();
                        table1.ajax.reload();
                        alertify.success('PFI Deleted successfully.');
                    }
                });
            }
        }).set({title:"Delete Item"})
    });

    $('.released-detail-update').on('click',function(){
        let pfi_id = $(this).attr('data-id');

        let url =  "{{ route('pfi-released-amount-update') }}";
        let released_amount = $('#released_amount_'+pfi_id).val();
        let released_date = $('#released_date_'+pfi_id).val();
        if(released_amount.length > 0) {
            document.getElementById("released-amount-error-"+pfi_id).textContent="";
	        document.getElementById("released_amount_"+pfi_id).classList.remove("is-invalid");
	        document.getElementById("released-amount-error-"+pfi_id).classList.remove("paragraph-class");
            var confirm = alertify.confirm('Are you sure you want to Update Released amount and data for this item ?',function (e) {
            $('#update-released-amount-'+pfi_id).modal('hide');
            
            if (e) {
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        released_amount: released_amount,
                        released_date: released_date,
                        pfi_id: pfi_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success:function (data) {
                        console.log(data);
                        var table1 = $('#PFI-table').DataTable();
                        table1.ajax.reload();

                        alertify.success('Relaesed amount updated successfully.');
                    }
                });
            }
        }).set({title:"Update Item"})
        }else{
                let msg = "This filed is required";
                document.getElementById("released-amount-error-"+pfi_id).textContent=msg;
                document.getElementById("released_amount_"+pfi_id).classList.add("is-invalid");
                document.getElementById("released-amount-error-"+pfi_id).classList.add("paragraph-class");
        }
      
    });

</script>
