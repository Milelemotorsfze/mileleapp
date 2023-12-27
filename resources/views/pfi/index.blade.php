@extends('layouts.table')
@section('content')
    @can('PFI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    PFI List
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive" >
                    <table id="PFI-table" class="table table-striped table-editable table-edits table table-condensed" style="">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.NO</th>
                            <th>Created Date</th>
                            <th>Reference Number</th>
                            <th>Customer Name </th>
                            <th>Customer Country</th>
                            <th>Amount</th>
                            <th>PFI Date</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($pfis as $key => $pfi)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($pfi->created_at)->format('d M y') }}</td>
                                <td>{{ $pfi->pfi_reference_number }}</td>
                                <td>{{ $pfi->letterOfIndent->customer->name }}</td>
                                <td>{{ $pfi->letterOfIndent->customer->country->name ?? ''  }}</td>
                                <td>{{ $pfi->amount }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($pfi->pfi_date)->format('d M y') }}</td>
                                <td>{{ $pfi->comment }}</td>
                                <td>{{$pfi->status }}</td>
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
                                            <a class="btn btn-primary btn-sm" href="{{ route('pfi.edit', $pfi->id) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endif
                                        @endcan
                                    @endif
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-pfi-docs-{{$pfi->id}}">
                                        View Docs
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-pfi-items-{{$pfi->id}}">
                                        View PFI Items
                                    </button>
                                    @can('create-demand-planning-po')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-demand-planning-po');
                                        @endphp
                                        @if ($hasPermission)
                                            @if($pfi->is_po_active == true)
                                                <a href="{{ route('demand-planning-purchase-orders.create', ['id' => $pfi->id]) }}"  class="btn btn-primary btn-sm"> Add PO </a>
                                            @endif
                                        @endif
                                    @endcan

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
                                                                            <label class="form-label">Model</label>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <label  class="form-label">SFX</label>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                                            <label class="form-label">Variant</label>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <label class="form-label">Quantity</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @foreach($pfi->pfi_items as $value => $approvedItem)
                                                            <div class="row">
                                                                <div class="d-flex">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="row mt-3">
                                                                            <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                                                                <input type="text" value="{{ $approvedItem->letterOfIndentItem->masterModel->model ?? ''}}" readonly class="form-control" >
                                                                            </div>
                                                                            <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                <label  class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                                                                <input type="text" value="{{$approvedItem->letterOfIndentItem->masterModel->sfx ?? '' }}" readonly class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Variant</label>
                                                                                <input type="text" value="{{ $approvedItem->letterOfIndentItem->masterModel->variant->name ?? ''}}" readonly class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                                                <input type="text" value="{{ $approvedItem->quantity }}" readonly class="form-control">
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


















