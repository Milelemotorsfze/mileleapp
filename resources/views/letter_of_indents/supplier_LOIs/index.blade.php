@extends('layouts.table')
@section('content')
    <style>
        .modal {
            position: absolute;
            float: left;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        body.modal-open {
            overflow: hidden;
        }
    </style>

    <div class="card-header">
        <h4 class="card-title">
           Supplier LOI Info

        </h4>
    </div>
    <div class="card-body">
        <form id="form-search" action="{{ route('letter-of-indents.get-suppliers-LOIs') }}" >
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Supplier</label>
                        <select class="form-control" autofocus  name="supplier_id"  id="supplier" required>
                            <option></option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @if($supplierId){{ $supplier->id == $supplierId ? 'selected' : '' }} @endif>{{ $supplier->supplier }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary search-button" >Search</button>
                        <a href="{{ route('letter-of-indents.get-suppliers-LOIs') }}">
                            <button type="button" class="btn btn-secondary "> Refresh </button>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="portfolio">
        <ul class="nav nav-pills nav-fill" id="my-tab">
            <li class="nav-item">
                <a class="nav-link tab-1 active" data-bs-toggle="tab" data-tab="PENDING" href="#pending-approved-LOI">Pending Approval LOIs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link tab-2" data-bs-toggle="tab" data-tab="APPROVED" href="#approved-LOI"> Approved LOIs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link tab-3" data-bs-toggle="tab" data-tab="REJECTED" href="#rejected-LOI">Rejected LOIs</a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pending-approved-LOI">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="new-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.NO</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Supplier</th>
                            <th>Category</th>
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                            <th>LOI Items</th>
                            <th>LOI Documents</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($approvalPendingLOIs as $key => $letterOfIndent)
                            <tr>
                                <td> {{ ++$i }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->supplier->supplier }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#view-new-loi-items-{{$letterOfIndent->id}}">View
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#view-new-loi-docs-{{$letterOfIndent->id}}">View
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#approve-LOI-{{ $letterOfIndent->id }}" > Approve </button>

                                    <button type="button" class="btn btn-danger modal-button btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#reject-LOI-{{$letterOfIndent->id}}"> Reject </button>
                                </td>
                                <div class="modal fade" id="view-new-loi-items-{{$letterOfIndent->id}}" data-bs-backdrop="static"
                                     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">LOI Items</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-3">
                                                @if($letterOfIndent->letterOfIndentItems->count() > 0)
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
                                                    @foreach($letterOfIndent->letterOfIndentItems as $value => $LOIItem)
                                                        <div class="row">
                                                            <div class="d-flex">
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="row mt-3">
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                                                            <input type="text" value="{{ $LOIItem->masterModel->model ?? ''}}" readonly class="form-control" >
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <label  class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                                                            <input type="text" value="{{ $LOIItem->masterModel->sfx ?? '' }}" readonly class="form-control">
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Variant</label>
                                                                            <input type="text" value="{{ $LOIItem->masterModel->variant->name ?? ''}}" readonly class="form-control">
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                                            <input type="text" value="{{ $LOIItem->quantity }}" readonly class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-center"> No Data Available! </span>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="view-new-loi-docs-{{$letterOfIndent->id}}" data-bs-backdrop="static"
                                     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"> LOI Documents</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-lg-12">
                                                    <div class="row p-2">
                                                        @foreach($letterOfIndent->LOIDocuments as $letterOfIndentDocument)
                                                            <div class="d-flex">
                                                                <div class="col-lg-12">
                                                                    <div class="row p-2">
                                                                        <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}" width="400" height="600"
                                                                        ></embed>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="reject-LOI-{{$letterOfIndent->id}}" data-bs-backdrop="static"
                                     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"> Reject LOI </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-3">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="row mt-2">
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-center">Customer</label>
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <input type="text" value="{{  $letterOfIndent->customer->name }}" class="form-control" readonly >
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-muted">Category</label>
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <input type="text" value="{{ $letterOfIndent->category }}" class="form-control" readonly >
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-muted">Supplier</label>
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <input type="text" value="{{ $letterOfIndent->supplier->supplier }}" class="form-control" readonly >
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-muted">LOI Date</label>
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <input type="text" value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}"
                                                                           readonly class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-muted">Reason</label>
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <textarea class="form-control" cols="75" name="review" id="review"  rows="5" required></textarea>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" value="{{ $letterOfIndent->id }}" id="id">
                                                            <input type="hidden" value="REJECTED" id="status-reject">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary status-reject-button">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="approve-LOI-{{$letterOfIndent->id}}" data-bs-backdrop="static"
                                     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"> Approve LOI </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-3">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="row mt-2">
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-center">Customer</label>
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <input type="text" value="{{  $letterOfIndent->customer->name }}" class="form-control" readonly >
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-muted">Category</label>
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <input type="text" value="{{ $letterOfIndent->category }}" class="form-control" readonly >
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-muted">Supplier</label>
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <input type="text" value="{{ $letterOfIndent->supplier->supplier }}" class="form-control" readonly >
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-muted">LOI Date</label>
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <input type="text" value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}"
                                                                           readonly class="form-control">
                                                                </div>
                                                            </div>
                                                            <input type="hidden" value="{{ $letterOfIndent->id }}" id="id">
                                                            <input type="hidden" value="APPROVE" id="status-approve">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary status-change-button-approve">Submit</button>
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
        <div class="tab-pane fade" id="approved-LOI">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="supplier-approved-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>supplier</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                            <th>Deal Items</th>
                            <th>Deal Documents</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($approvedLOIs as $key => $letterOfIndent)
                            <tr>
                                <td>{{ $letterOfIndent->supplier->supplier }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#supplier-approved-loi-items-{{$letterOfIndent->id}}">View
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#supplier-approved-loi-doc-{{$letterOfIndent->id}}">View
                                    </button>
                                </td>
                                <div class="modal fade" id="supplier-approved-loi-items-{{$letterOfIndent->id}}" data-bs-backdrop="static"
                                     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">LOI Items</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-3">
                                                @if($letterOfIndent->letterOfIndentItems->count() > 0)
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
                                                    @foreach($letterOfIndent->letterOfIndentItems as $value => $LOIItem)
                                                        <div class="row">
                                                            <div class="d-flex">
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="row mt-3">
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                                                            <input type="text" value="{{ $LOIItem->model }}" readonly class="form-control" >
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <label  class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                                                            <input type="text" value="{{ $LOIItem->sfx  }}" readonly class="form-control">
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Variant</label>
                                                                            <input type="text" value="{{ $LOIItem->variant_name }}" readonly class="form-control">
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                                            <input type="text" value="{{ $LOIItem->quantity }}" readonly class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-center"> No Data Available! </span>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="supplier-approved-loi-doc-{{$letterOfIndent->id}}" data-bs-backdrop="static"
                                     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"> LOI Documents</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-lg-12">
                                                    <div class="row p-2">
                                                        @foreach($letterOfIndent->LOIDocuments as $letterOfIndentDocument)
                                                            <div class="d-flex">
                                                                <div class="col-lg-12">
                                                                    <div class="row p-2">
                                                                        <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}" width="400" height="600"
                                                                        ></embed>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        <div class="tab-pane fade" id="rejected-LOI">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="supplier-rejected-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>supplier</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                            <th>Review</th>
                            <th>Deal Items</th>
                            <th>Deal Documents</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($rejectedLOIs as $key => $letterOfIndent)
                            <tr>
                                <td>{{ $letterOfIndent->supplier->supplier }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td>{{ $letterOfIndent->review }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#supplier-rejected-loi-items-{{$letterOfIndent->id}}">View
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#supplier-rejected-loi-doc-{{$letterOfIndent->id}}">View
                                    </button>
                                </td>
                                <div class="modal fade" id="supplier-rejected-loi-items-{{$letterOfIndent->id}}" data-bs-backdrop="static"
                                     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">LOI Items</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-3">
                                                @if($letterOfIndent->letterOfIndentItems->count() > 0)
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
                                                    @foreach($letterOfIndent->letterOfIndentItems as $value => $LOIItem)
                                                        <div class="row">
                                                            <div class="d-flex">
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="row mt-3">
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                                                            <input type="text" value="{{ $LOIItem->model }}" readonly class="form-control" >
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <label  class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                                                            <input type="text" value="{{ $LOIItem->sfx  }}" readonly class="form-control">
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Variant</label>
                                                                            <input type="text" value="{{ $LOIItem->variant_name }}" readonly class="form-control">
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                                            <input type="text" value="{{ $LOIItem->quantity }}" readonly class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-center"> No Data Available! </span>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="supplier-rejected-loi-doc-{{$letterOfIndent->id}}" data-bs-backdrop="static"
                                     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"> LOI Documents</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-lg-12">
                                                    <div class="row p-2">
                                                        @foreach($letterOfIndent->LOIDocuments as $letterOfIndentDocument)
                                                            <div class="d-flex">
                                                                <div class="col-lg-12">
                                                                    <div class="row p-2">
                                                                        <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}" width="400" height="600"
                                                                        ></embed>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

@endsection
@push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {

            $("#supplier").select2({
                placeholder:'Select Supplier'
            })
            $("#form-search").validate({
            rules: {
                supplier_id: {
                    required: true,
                    },
                },
            });

            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });

            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#my-tab a[href="' + activeTab + '"]').tab('show');
            }

            $('.status-change-button-approve').click(function () {
                var id = $('#id').val();
                var status = $('#status-approve').val();
                statusChange(id,status);
            })
            $('.status-reject-button').click(function (e) {
                var id = $('#id').val();
                var status = $('#status-reject').val();
                statusChange(id,status)
            })
            function statusChange(id,status) {
                let url = '{{ route('letter-of-indents.supplier-approval') }}';
                if(status == 'REJECTED') {
                        var message = 'Reject';
                    }else{
                        var message = 'Approve';
                    }
                var confirm = alertify.confirm('Are you sure you want to '+ message +' this item ?',function (e) {
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
        })

    </script>
@endpush

















