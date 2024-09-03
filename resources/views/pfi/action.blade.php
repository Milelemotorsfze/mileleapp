
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
        <li>
            <a class="btn btn-info btn-sm" style="width:100%; margin-top:2px; margin-bottom:2px;"  title="To Edit PFI" href="{{ route('pfi.edit', $pfi->id) }}">
                <i class="fa fa-edit"></i> Edit
            </a>
        <li>
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
            <button type="button" class="btn btn-primary btn-sm"  style="width:100%; margin-top:2px; margin-bottom:2px;" title="To View PFI Items" data-bs-toggle="modal" data-bs-target="#view-pfi-items-{{$pfi->id}}">
                <i class="fa fa-list"></i> View PFI Items
            </button>
        </li>
        @endif
    @endcan
    @can('pfi-payment-status-update')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('pfi-payment-status-update');
        @endphp
        @if ($hasPermission)
        <li>
            <button type="button" style="width:100%; margin-top:2px; margin-bottom:2px;"class="btn btn-sm btn-info"
                    title="To Update PFI Payment Status" data-bs-toggle="modal" data-bs-target="#update-pfi-payment-status-{{$pfi->id}}">
                <i class="fa fa-dollar-sign"></i>
            </button>
        </li>
        @endif
    @endcan
    @can('pfi-delete')
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('pfi-delete');
            @endphp
            @if ($hasPermission)
            <li>
                <button type="button" style="width:100%; margin-top:2px; margin-bottom:2px;" class="btn btn-danger btn-sm pfi-button-delete mt-1" title="Delete PFI"
                        data-id="{{ $pfi->id }}" data-url="{{ route('pfi.destroy', $pfi->id) }}">
                    <i class="fa fa-trash"></i> To Delete PFI
                </button>
                </li>
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
                        <form action="{{ route('pfi-released-amount-update', $pfi->id) }}" method="POST" id="released-amount">
                            @csrf
                            <div class="modal-body">
                                <div class="col-lg-12">
                                    <div class="row p-2">
                                        <input type="date" name="released_date" required class="form-control" >
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row p-2">
                                        <input type="number" min="0" required name="released_amount" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--  PFI PAYMENT UPDATE MODAL -->

            <!-- <div class="modal fade " id="update-pfi-payment-status-{{$pfi->id}}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"> Update Payment Status</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('pfi-payment-status-update', $pfi->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="col-lg-12">
                                    <div class="row p-2">
                                        <select name="payment_status" class="form-select">
                                            <option value="UNPAID" {{ \App\Models\PFI::PFI_PAYMENT_STATUS_UNPAID == $pfi->payment_status ? 'selected' : '' }} >UNPAID </option>
                                            <option value="PARTIALY PAID" {{ \App\Models\PFI::PFI_PAYMENT_STATUS_PARTIALY_PAID == $pfi->payment_status ? 'selected' : '' }}>
                                                PARTIALY PAID
                                            </option>
                                            <option value="PAID" {{ \App\Models\PFI::PFI_PAYMENT_STATUS_PAID == $pfi->payment_status ? 'selected' : '' }} >
                                                PAID
                                            </option>
                                            <option value="CANCELLED" {{ \App\Models\PFI::PFI_PAYMENT_STATUS_CANCELLED == $pfi->payment_status ? 'selected' : '' }}>CANCELLED </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> -->
            <!--  PFI PAYMENT DOCS MODAL -->

        <div class="modal fade " id="view-pfi-docs-{{$pfi->id}}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"> PFI Document</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row p-2">
                                <embed src="{{ url('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign) }}" height="400" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- PFI Items Modal -->
        <div class="modal fade " id="view-pfi-items-{{$pfi->id}}" data-bs-backdrop="static" tabindex="-1"
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
                            {{ $pfiItem->test}}
                                <div class="row">
                                    <div class="d-flex">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <hr>
                                            <div class="row mt-3">
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none">LOI Item Code</dt>
                                                    <dl> {{ $pfiItem->letterOfIndentItem->code ?? ''}} </dl>
                                                </div>
                                              
                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Model</dt>
                                                    <dl> {{ $pfiItem->letterOfIndentItem->masterModel->model ?? ''}} </dl>
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
                                                    <dl>{{ $pfiItem->quantity }}</dl>
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

</script>
