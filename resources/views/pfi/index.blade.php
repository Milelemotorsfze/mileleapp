@extends('layouts.table')
@section('content')
    @can('PFI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    PFI Lists
                    @can('PFI-create')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-create');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end" href="{{ route('pfi.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
                    @endif
                @endcan
                </h4>
                @if (Session::has('success'))
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('success') }}
                    </div>
                @endif
            </div>

            <div class="card-body">
                <div class="table-responsive" >
                    <table id="PFI-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.NO</th>
                            <th>PFI Number</th>
                            <th>Vendor</th>
                            <th>Dealer</th>
                            <th>Delivery Location</th>
                            <th>Currency</th>
                            <th>Customer Name </th>
                            <th>Customer Country</th>
                            <th>Amount</th>
                            <th>Released Amount</th>
                            <th>Release Date</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($pfis as $key => $pfi)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $pfi->pfi_reference_number }}</td>
                                <td>{{ $pfi->supplier->supplier ?? '' }}</td>
                                <td>{{ $pfi->letterOfIndent->dealers ?? ''}}</td>
                                <td>{{ $pfi->delivery_location }}</td>
                                <td>{{ $pfi->currency }}</td>
                                <td>{{ $pfi->letterOfIndent->client->name ?? ''}}</td>
                                <td>{{ $pfi->letterOfIndent->country->name ?? ''  }}</td>
                                <td>{{ $pfi->amount }}</td>
                                <td>{{ $pfi->released_amount }}</td>
                               <td>{{ \Illuminate\Support\Carbon::parse($pfi->released_date)->format('d M y') }}</td>
                                <td>{{ $pfi->comment }}</td>
                                <td>{{ $pfi->status }}</td>
                                <td>{{ $pfi->payment_status }} </td>
                                <td>{{ \Illuminate\Support\Carbon::parse($pfi->created_at)->format('d M y') }}</td>
                                <td>
                                    @if($pfi->status == 'New')
                                        @can('pfi-delete')
                                            @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-delete');
                                            @endphp
                                            @if ($hasPermission)
                                                <button type="button" class="btn btn-danger btn-sm pfi-button-delete"
                                                        data-id="{{ $pfi->id }}" data-url="{{ route('pfi.destroy', $pfi->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        @endcan
                                        @can('pfi-edit')
                                            @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-edit');
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="btn btn-soft-green btn-sm" title="To Edit PFI" href="{{ route('pfi.edit', $pfi->id) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-secondary btn-sm" title="To Update Released Amount"
                                                data-bs-toggle="modal" data-bs-target="#update-released-amount-{{$pfi->id}}">
                                                <i class="fa fa-euro-sign"></i>
                                            </button>
                                            @endif
                                        @endcan
                                    @endif
                                    <button type="button" class="btn btn-soft-violet btn-sm" title="To view PFI Document" data-bs-toggle="modal" data-bs-target="#view-pfi-docs-{{$pfi->id}}">
                                        <i class="fa fa-file-pdf"></i>
                                    </button>
                                    <button type="button" class="btn btn-dark-blue btn-sm" title="To View PFI Items" data-bs-toggle="modal" data-bs-target="#view-pfi-items-{{$pfi->id}}">
                                        <i class="fa fa-list"></i>
                                    </button>
                                    @can('create-demand-planning-po')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-demand-planning-po');
                                        @endphp
                                        @if ($hasPermission)
                                            @if($pfi->is_po_active == true)
                                                <a href="{{ route('demand-planning-purchase-orders.create', ['id' => $pfi->id]) }}" title="To Create Purchase Order" class="btn btn-soft-blue btn-sm"> Add PO </a>
                                            @endif
                                        @endif
                                    @endcan
                                    @can('pfi-payment-status-update')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('pfi-payment-status-update');
                                        @endphp
                                        @if ($hasPermission)
                                            <button type="button" style="background-color: #2688a6;color: #FFFFFF" class="btn btn-sm"
                                                    title="To Update PFI Payment Status" data-bs-toggle="modal" data-bs-target="#update-pfi-payment-status-{{$pfi->id}}">
                                                <i class="fa fa-dollar-sign"></i>
                                            </button>
                                        @endif
                                    @endcan

                                   <!-- PFI released amount update Model -->
                                   <div class="modal fade " id="update-released-amount-{{$pfi->id}}" data-bs-backdrop="static" 
                                                      tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog ">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel"> Update Released Amount</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('pfi-released-amount-update', $pfi->id) }}" method="POST">
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
                                        <div class="modal fade " id="update-pfi-payment-status-{{$pfi->id}}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        </div>
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
                                                          <embed src="{{ url('PFI_Document_with_sign/'.$pfi->pfi_document_with_sign) }}" height="400" >
                                                      </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade " id="view-pfi-items-{{$pfi->id}}" data-bs-backdrop="static" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel"> PFI Items</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($pfi->pfi_items->count() > 0)
                                                        <div class="row  d-none d-lg-block d-xl-block d-xxl-block">
                                                            <div class="d-flex">
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="row">
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <dt>Model</dt>
                                                                        </div>
                                                                        <div class="col-lg-1 col-md-12 col-sm-12">
                                                                            <dt>SFX</dt>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <dt>Model Year</dt>
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
                                                        @foreach($pfi->pfi_items as $value => $approvedItem)
                                                            <div class="row">
                                                                <div class="d-flex">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <hr>
                                                                        <div class="row mt-3">
                                                                            <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                <dt class="d-lg-none d-xl-none d-xxl-none">Model</dt>
                                                                                <dl> {{ $approvedItem->letterOfIndentItem->masterModel->model ?? ''}} </dl>
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">SFX</dt>
                                                                                <dl> {{ $approvedItem->letterOfIndentItem->masterModel->sfx ?? ''}} </dl>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt class="d-lg-none d-xl-none d-xxl-none">Model Year</dt>
                                                                                <dl> {{ $approvedItem->letterOfIndentItem->masterModel->model_year ?? ''}}</dl>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">Unit Price</dt>
                                                                                <dl>{{ $approvedItem->unit_price ?? ''}} </dl>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">Quantity</dt>
                                                                                <dl>{{ $approvedItem->quantity }}</dl>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">Total Price ({{ $pfi->currency }})</dt>
                                                                                <dl>{{ $approvedItem->unit_price * $approvedItem->quantity }}  </dl>
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
                                    </td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endcan
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
                            location.reload();
                            alertify.success('PFI Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
    </script>
@endsection


















