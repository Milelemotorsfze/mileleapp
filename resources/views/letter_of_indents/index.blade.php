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
    </div>
    <div class="portfolio">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="pill" href="#new-LOI">New Deals</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#supplier-approved-LOI">Supplier Approved Deals</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#milele-partial-approved-LOI">Milele Partial Approved Deals</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#milele-approved-LOI">Milele Approved Deals</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#supplier-rejected-LOI">Supplier Rejected Deals</a>
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
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                            <th>Deal Items</th>
                            <th>Deal Documents</th>
                            <th>LOI</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($letterOfIndents as $key => $letterOfIndent)
                            <tr>
                                <td> {{ ++$i }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td><button type="button" class="btn btn-primary modal-button" data-bs-toggle="modal"
                                            data-modal-id="viewdealinfo-{{ $letterOfIndent->id }}" data-modal-type="ITEM">View </button>
                                </td>
                                <td><button type="button" class="btn btn-primary modal-button" data-bs-toggle="modal"
                                            data-modal-id="view-LOI-doc-{{ $letterOfIndent->id }}"  data-modal-type="DOC">View </button>
                                </td>
                                <td><a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id ]) }}"> LOI</a> </td>
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
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                            <th>Deal Items</th>
                            <th>Deal Documents</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($letterOfIndents as $key => $letterOfIndent)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td><button type="button" class="btn btn-primary modal-button" data-bs-toggle="modal"
                                            data-modal-id="viewdealinfo-{{ $letterOfIndent->id }}" >View </button>
                                </td>
                                <td><button type="button" class="btn btn-primary modal-button" data-bs-toggle="modal"
                                            data-modal-id="view-LOI-doc-{{ $letterOfIndent->id }}" >View </button>
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
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($letterOfIndents as $key => $letterOfIndent)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
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
                    <table id="milele-approved-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($letterOfIndents as $key => $letterOfIndent)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
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
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($letterOfIndents as $key => $letterOfIndent)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


















