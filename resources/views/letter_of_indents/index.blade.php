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


    </style>
    <div class="card-header">
        <h4 class="card-title">
            LOI Info
        </h4>
        <a  class="btn btn-sm btn-info float-end" href="{{route('letter-of-indents.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
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
    <div class="portfolio">
        <ul class="nav nav-pills nav-fill" id="my-tab">
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
                                    <a href="{{ route('letter-of-indents.edit',$letterOfIndent->id) }}">
                                        <button type="button" class="btn btn-primary btn-sm "><i class="fa fa-edit"></i></button>
                                    </a>
                                    <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class="btn btn-primary btn-sm">LOI PDF</button>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#reject-LOI-{{$letterOfIndent->id}}">
                                        Reject
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-loi-items-{{$letterOfIndent->id}}">
                                        View LOI Items
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-loi-docs-{{$letterOfIndent->id}}">
                                        View LOI Docs
                                    </button>
                                </td>
                                <div class="modal fade" id="reject-LOI-{{$letterOfIndent->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog ">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Reject LOI</h1>
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
                                                            <input type="hidden" value="{{ \App\Models\LetterOfIndent::LOI_STATUS_REJECTED }}" id="status">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary  status-reject-button">Submit</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="view-loi-items-{{$letterOfIndent->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <div class="modal fade" id="view-loi-docs-{{$letterOfIndent->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                        <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  width="400" height="600"></embed>
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
                            <th>Milele Approval</th>
                            <th width="150px">Actions</th>
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
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-supplier-approved-loi-items-{{$letterOfIndent->id}}">
                                        View LOI Items
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-supplier-approved-loi-docs-{{$letterOfIndent->id}}">
                                        View LOI Docs
                                    </button>
                                </td>
                                <div class="modal fade" id="view-supplier-approved-loi-items-{{$letterOfIndent->id}}"
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
                                <div class="modal fade" id="view-supplier-approved-loi-docs-{{$letterOfIndent->id}}"
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
                            <th>Approval</th>
                            <th>PFI</th>
                            <th width="150px">Actions</th>
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
                                    <a href="{{ route('letter-of-indents.milele-approval',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class=" btn btn-primary btn-sm" >Approve</button>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('pfi.create',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class=" btn btn-info btn-sm" >Add PFI</button>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class="btn btn-primary btn-sm">LOI PDF</button>
                                    </a>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-partial-approved-loi-items-{{$letterOfIndent->id}}">
                                        View LOI Items
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-partial-approved-loi-docs-{{$letterOfIndent->id}}">
                                        View LOI Docs
                                    </button>
                                </td>
                                <div class="modal fade" id="view-partial-approved-loi-items-{{$letterOfIndent->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <div class="modal fade" id="view-partial-approved-loi-docs-{{$letterOfIndent->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                        <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  width="400" height="600"></embed>
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
                            <th width="150px">Actions</th>
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
                                    <a href="{{ route('pfi.create',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class="btn btn-info btn-sm" >Add PFI</button>
                                    </a>
                                    <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id ]) }}">
                                        <button type="button" class="btn btn-primary btn-sm">LOI PDF</button>
                                    </a>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-approved-loi-items-{{$letterOfIndent->id}}">
                                        View LOI Items
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-approved-loi-docs-{{$letterOfIndent->id}}">
                                        View LOI Docs
                                    </button>
                                </td>
                                <div class="modal fade" id="view-approved-loi-items-{{$letterOfIndent->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <div class="modal fade" id="view-approved-loi-docs-{{$letterOfIndent->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
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
                                                                        <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  width="400" height="600"></embed>
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
                            <th width="100px">Actions</th>
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
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-rejected-loi-items-{{$letterOfIndent->id}}">
                                        View LOI Items
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-rejected-loi-docs-{{$letterOfIndent->id}}">
                                        View LOI Docs
                                    </button>
                                </td>
                                <div class="modal fade" id="view-rejected-loi-items-{{$letterOfIndent->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <div class="modal fade" id="view-rejected-loi-docs-{{$letterOfIndent->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                        <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  width="400" height="600"></embed>
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


















