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
        .modal-header-sticky {
            position: sticky;
            top: 0;
            z-index: 1055;
        }

    </style>
    <div class="card-header">
        <h4 class="card-title">
            LOI Info
        </h4>
        <a  class="btn btn-sm btn-info float-end" href="{{route('letter-of-indents.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>

    </div>
    <div class="portfolio">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="pill" href="#new-LOI">New LOI</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#supplier-approved-LOI">Supplier Approved LOI</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#milele-partial-approved-LOI">Milele Partial Approved LOI</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#milele-approved-LOI">Milele Approved LOI</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#supplier-rejected-LOI">Supplier Rejected LOI</a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="new-LOI">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="new-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.NO</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Dealers</th>
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
                        @foreach ($newLOIs as $key => $letterOfIndent)
                            <tr>
                                <td> {{ ++$i }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->supplier->supplier }}</td>
                                <td>{{ $letterOfIndent->dealers }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="viewdealinfo-{{ $letterOfIndent->id }}" data-modal-type="ITEM">View </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="view-LOI-doc-{{ $letterOfIndent->id }}"  data-modal-type="DOC">View </button>
                                </td>

                                <td>
                                    <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class="btn btn-primary btn-sm">
                                            LOI PDF</button>
                                    </a>
                                        <a href="{{ route('letter-of-indents.edit',$letterOfIndent->id) }}">
                                            <button type="button" class="btn btn-primary btn-sm">
                                                 <i class="fa fa-edit"></i></button>
                                        </a>

                                    <button type="button" class="btn btn-danger modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="reject-LOI-{{ $letterOfIndent->id }}" > Reject </button>

                                </td>
                                <div class="modal modalhide" id="viewdealinfo-{{$letterOfIndent->id}}" >
                                    <div class="modal-header bg-primary">
                                        <h1 class="modal-title fs-5 text-white text-center" > LOI Items</h1>
                                        <button type="button" class="btn-close close"  aria-label="Close"></button>
                                    </div>
                                    <div class="modal-content p-5">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Model</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">SFX</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Varients</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Qty</label>
                                                </div>
                                                @foreach($letterOfIndent->letterOfIndentItems()->get() as $LOIItem)
                                                    <div class="d-flex">
                                                        <div class="col-lg-12">
                                                            <div class= "row">
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="model" value="{{$LOIItem->model}}"  readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="sfx" value="{{$LOIItem->sfx}}"  readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="varient" value="{{$LOIItem->variant_name}}" readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="quantity" value="{{$LOIItem->quantity}}"  readonly="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal modalhide" id="view-LOI-doc-{{$letterOfIndent->id}}" >
                                    <div class="modal-header bg-primary modal-header-sticky">
                                        <h1 class="modal-title fs-5 text-white text-center" > LOI Documents</h1>
                                        <button type="button" class="btn-close close"  aria-label="Close"></button>
                                    </div>
                                    <div class="modal-content p-5">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                @foreach($letterOfIndent->LOIDocuments()->get() as $letterOfIndentDocument)
                                                    <div class="d-flex">
                                                        <div class="col-lg-12">
                                                            <div class="row p-2">
                                                                <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  width="400" height="400"></embed>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal modalhide" id="reject-LOI-{{$letterOfIndent->id}}" style="width: 600px" >
                                    <div class="modal-header bg-primary modal-header-sticky">
                                        <h1 class="modal-title fs-5 text-white text-center" > Reject LOI</h1>
                                        <button type="button" class="btn-close close"  aria-label="Close"></button>
                                    </div>
                                    <div class="modal-content p-3">
                                        <div class="col-12">
                                            <div class="row mt-2">
                                                <div class="col-2">
                                                    <label class="form-label font-size-13 text-muted">Customer</label>
                                                </div>
                                                <div class="col-10">
                                                    <input type="text" value="{{  $letterOfIndent->customer->name }}" class="form-control" readonly >
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-2">
                                                    <label class="form-label font-size-13 text-muted">Category</label>
                                                </div>
                                                <div class="col-10">
                                                    <input type="text" value="{{ $letterOfIndent->category }}" class="form-control" readonly >
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-2">
                                                    <label class="form-label font-size-13 text-muted">Supplier</label>
                                                </div>
                                                <div class="col-10">
                                                    <input type="text" value="{{ $letterOfIndent->supplier->supplier }}" class="form-control" readonly >
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-2">
                                                    <label class="form-label font-size-13 text-muted">LOI Date</label>
                                                </div>
                                                <div class="col-10">
                                                    <input type="text" value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}"
                                                           readonly class="form-control">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-2">
                                                    <label class="form-label font-size-13 text-muted">Reason</label>
                                                </div>
                                                <div class="col-10">
                                                    <textarea class="form-control" cols="75" name="review" id="review"  rows="5" required></textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" value="{{ $letterOfIndent->id }}" id="id">
                                            <input type="hidden" value="{{ \App\Models\LetterOfIndent::LOI_STATUS_REJECTED }}" id="status">
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary btnright status-reject-button">Submit</button>
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
        <div class="tab-pane fade" id="supplier-approved-LOI">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="supplier-approved-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Dealers</th>
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                            <th>LOI Items</th>
                            <th>LOI Documents</th>
                            <th>Milele Approval</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($supplierApprovedLOIs as $key => $letterOfIndent)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->supplier->supplier }}</td>
                                <td>{{ $letterOfIndent->dealers }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="supplier-approved-loi-items-{{ $letterOfIndent->id }}" >View </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="supplier-approved-loi-doc-{{ $letterOfIndent->id }}" >View </button>
                                </td>
                                <td>
                                    <a href="{{ route('letter-of-indents.milele-approval',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class=" btn btn-primary btn-sm" >
                                             Partial Approval
                                        </button>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class="btn btn-primary btn-sm">
                                           LOI PDF</button>
                                    </a>
                                </td>
                                <div class="modal modalhide" id="supplier-approved-loi-items-{{$letterOfIndent->id}}" >
                                    <div class="modal-header bg-primary">
                                        <h1 class="modal-title fs-5 text-white text-center" > LOI Items</h1>
                                        <button type="button" class="btn-close close"  aria-label="Close"></button>
                                    </div>
                                    <div class="modal-content p-5">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Model</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">SFX</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Varients</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Qty</label>
                                                </div>
                                                @foreach($letterOfIndent->letterOfIndentItems()->get() as $LOIItem)
                                                    <div class="d-flex">
                                                        <div class="col-lg-12">
                                                            <div class= "row">
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="model" value="{{$LOIItem->model}}"  readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="sfx" value="{{$LOIItem->sfx}}"  readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="varient" value="{{$LOIItem->variant_name}}" readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="quantity" value="{{$LOIItem->quantity}}"  readonly="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal modalhide" id="supplier-approved-loi-doc-{{$letterOfIndent->id}}" >
                                    <div class="modal-header bg-primary modal-header-sticky">
                                        <h1 class="modal-title fs-5 text-white text-center" > LOI Documents</h1>
                                        <button type="button" class="btn-close close"  aria-label="Close"></button>
                                    </div>
                                    <div class="modal-content p-5">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                @foreach($letterOfIndent->LOIDocuments()->get() as $letterOfIndentDocument)
                                                    <div class="d-flex">
                                                        <div class="col-lg-12">
                                                            <div class="row p-2">
                                                                <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  width="400" height="600"></embed>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
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
        <div class="tab-pane fade" id="milele-partial-approved-LOI">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="milele-partial-approved-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Dealer</th>
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                            <th>LOI Items</th>
                            <th>LOI Documents</th>
                            <th>Approval</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($partialApprovedLOIs as $key => $letterOfIndent)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->supplier->supplier }}</td>
                                <td>{{ $letterOfIndent->dealers }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="partial-approved-loi-items-{{ $letterOfIndent->id }}" >View </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="partial-approved-loi-doc-{{ $letterOfIndent->id }}" >View </button>
                                </td>
                                <td>
                                    <a href="{{ route('letter-of-indents.milele-approval',['id' => $letterOfIndent->id ]) }}">
                                    <button type="button" class=" btn btn-primary btn-sm" >
                                        Approval
                                    </button>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fa fa-download"></i></button>
                                    </a>
                                </td>
                                <div class="modal modalhide" id="partial-approved-loi-items-{{$letterOfIndent->id}}" >
                                    <div class="modal-header bg-primary">
                                        <h1 class="modal-title fs-5 text-white text-center" > LOI Items</h1>
                                        <button type="button" class="btn-close close"  aria-label="Close"></button>
                                    </div>
                                    <div class="modal-content p-5">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Model</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">SFX</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Varients</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Qty</label>
                                                </div>
                                                @foreach($letterOfIndent->letterOfIndentItems()->get() as $LOIItem)
                                                    <div class="d-flex">
                                                        <div class="col-lg-12">
                                                            <div class= "row">
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="model" value="{{$LOIItem->model}}"  readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="sfx" value="{{$LOIItem->sfx}}"  readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="varient" value="{{$LOIItem->variant_name}}" readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="quantity" value="{{$LOIItem->quantity}}"  readonly="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal modalhide" id="partial-approved-loi-doc-{{$letterOfIndent->id}}" >
                                    <div class="modal-header bg-primary modal-header-sticky">
                                        <h1 class="modal-title fs-5 text-white text-center" > LOI Documents</h1>
                                        <button type="button" class="btn-close close"  aria-label="Close"></button>
                                    </div>
                                    <div class="modal-content p-5">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                @foreach($letterOfIndent->LOIDocuments()->get() as $letterOfIndentDocument)
                                                    <div class="d-flex">
                                                        <div class="col-lg-12">
                                                            <div class="row p-2">
                                                                <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  width="400" height="600"></embed>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
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
        <div class="tab-pane fade" id="milele-approved-LOI">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="milele-approved-LOI-table" class="table table-striped table-editable table-edits table table-condensed">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Dealer</th>
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
                        @foreach ($approvedLOIs as $key => $letterOfIndent)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->supplier->supplier }}</td>
                                <td>{{ $letterOfIndent->dealers }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="view-approved-loi-items-{{ $letterOfIndent->id }}" data-modal-type="ITEM">View </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="view-approved-loi-doc-{{ $letterOfIndent->id }}"  data-modal-type="DOC">View </button>
                                </td>
                                <td>
                                    <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class="btn btn-primary btn-sm">
                                            View LOI</button>
                                    </a>
                                </td>

                                    <div class="modal modalhide" id="view-approved-loi-items-{{$letterOfIndent->id}}" >
                                        <div class="modal-header bg-primary">
                                            <h1 class="modal-title fs-5 text-white text-center" > LOI Items</h1>
                                            <button type="button" class="btn-close close"  aria-label="Close"></button>
                                        </div>
                                        <div class="modal-content p-5">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3">
                                                        <label for="basicpill-firstname-input" class="form-label">Model</label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3">
                                                        <label for="basicpill-firstname-input" class="form-label">SFX</label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3">
                                                        <label for="basicpill-firstname-input" class="form-label">Varients</label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3">
                                                        <label for="basicpill-firstname-input" class="form-label">Qty</label>
                                                    </div>
                                                    @foreach($letterOfIndent->letterOfIndentItems()->get() as $LOIItem)
                                                        <div class="d-flex">
                                                            <div class="col-lg-12">
                                                                <div class= "row">
                                                                    <div class="col-lg-3 col-md-3">
                                                                        <input type="text" class="form-control mb-1" name="model" value="{{$LOIItem->model}}"  readonly="true">
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3">
                                                                        <input type="text" class="form-control mb-1" name="sfx" value="{{$LOIItem->sfx}}"  readonly="true">
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3">
                                                                        <input type="text" class="form-control mb-1" name="varient" value="{{$LOIItem->variant_name}}" readonly="true">
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3">
                                                                        <input type="text" class="form-control mb-1" name="quantity" value="{{$LOIItem->quantity}}"  readonly="true">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal modalhide" id="view-approved-loi-doc-{{$letterOfIndent->id}}" >
                                        <div class="modal-header bg-primary modal-header-sticky">
                                            <h1 class="modal-title fs-5 text-white text-center" > LOI Documents</h1>
                                            <button type="button" class="btn-close close"  aria-label="Close"></button>
                                        </div>
                                        <div class="modal-content p-5">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    @foreach($letterOfIndent->LOIDocuments()->get() as $letterOfIndentDocument)
                                                        <div class="d-flex">
                                                            <div class="col-lg-12">
                                                                <div class="row p-2">
                                                                    <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  width="400" height="400"></embed>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
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
        <div class="tab-pane fade" id="supplier-rejected-LOI">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="supplier-rejected-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Dealer</th>
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                            <th>Review</th>
                            <th>LOI Items</th>
                            <th>LOI Documents</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($rejectedLOIs as $key => $letterOfIndent)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->supplier->supplier }}</td>
                                <td>{{ $letterOfIndent->dealers }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <th>{{ $letterOfIndent->review }}</th>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="rejected-loi-items-{{ $letterOfIndent->id }}" >View </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="rejected-loi-doc-{{ $letterOfIndent->id }}" >View </button>
                                </td>
                                <div class="modal modalhide" id="rejected-loi-items-{{$letterOfIndent->id}}" >
                                    <div class="modal-header bg-primary">
                                        <h1 class="modal-title fs-5 text-white text-center" > LOI Items</h1>
                                        <button type="button" class="btn-close close"  aria-label="Close"></button>
                                    </div>
                                    <div class="modal-content p-5">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Model</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">SFX</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Varients</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="basicpill-firstname-input" class="form-label">Qty</label>
                                                </div>
                                                @foreach($letterOfIndent->letterOfIndentItems()->get() as $LOIItem)
                                                    <div class="d-flex">
                                                        <div class="col-lg-12">
                                                            <div class= "row">
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="model" value="{{$LOIItem->model}}"  readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="sfx" value="{{$LOIItem->sfx}}"  readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="varient" value="{{$LOIItem->variant_name}}" readonly="true">
                                                                </div>
                                                                <div class="col-lg-3 col-md-3">
                                                                    <input type="text" class="form-control mb-1" name="quantity" value="{{$LOIItem->quantity}}"  readonly="true">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal modalhide" id="rejected-loi-doc-{{$letterOfIndent->id}}" >
                                    <div class="modal-header bg-primary modal-header-sticky">
                                        <h1 class="modal-title fs-5 text-white text-center" > LOI Documents</h1>
                                        <button type="button" class="btn-close close"  aria-label="Close"></button>
                                    </div>
                                    <div class="modal-content p-5">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                @foreach($letterOfIndent->LOIDocuments()->get() as $letterOfIndentDocument)
                                                    <div class="d-flex">
                                                        <div class="col-lg-12">
                                                            <div class="row p-2">
                                                                <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  width="200" height="600"></embed>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
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
    <script type="text/javascript">
        $(document).ready(function () {
            $('.status-reject-button').click(function (e) {
                var id = $('#id').val();
                var status = $('#status').val();
                statusChange(id,status)
            })
            function statusChange(id,status) {
               var review = $('#review').val();
                let url = '{{ route('letter-of-indents.status-change') }}';
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        id: id,
                        status: status,
                        review:review,
                        _token: '{{ csrf_token() }}'
                    },
                    success:function (data) {
                        window.location.reload();
                        alertify.success(status +" Successfully")
                    }
                });
            }
        })
    </script>
@endsection


















