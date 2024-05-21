@extends('layouts.table')
@section('content')
    <style>
        .modal {
            position: absolute;
            min-height: 500px;
        }
    </style>
    @can('LOI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    LOI Lists
                </h4>
                @can('LOI-create')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-create');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end" href="{{ route('letter-of-indents.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
                    @endif
                @endcan

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
                        <a class="nav-link " data-bs-toggle="pill" href="#waiting-for-approval-LOI">Waiting For Approval</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#supplier-approved-LOI">Supplier Approved LOI</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#milele-partial-approved-LOI"> Utilization Initiated LOI</a>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" data-bs-toggle="pill" href="#milele-approved-LOI">Fully Utilized LOI</a>--}}
{{--                    </li>--}}
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
                                    <th>LOI Number</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Destination</th>
                                    <th>Prefered Location</th>
                                    <th> Status</th>
                                    <th>Supplier Approval</th>
{{--                                    <th>LOI</th>--}}
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                </div>
                                @foreach ($newLOIs as $key => $letterOfIndent)
                                    <tr>
                                        <td> {{ ++$i }}</td>
                                        <td> {{ $letterOfIndent->uuid }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                        <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->category }}</td>
                                        <td>{{ $letterOfIndent->dealers }}</td>
                                        <td>
                                            @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                 {{ $LoiSoNumber->so_number }}
                                            @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                            @endforeach
                                         </td>
                                        <td>{{ $letterOfIndent->destination }}</td>
                                        <td>{{ $letterOfIndent->prefered_location }}</td>
                                        <td>{{ $letterOfIndent->status }}</td>
{{--                                        <td>--}}
{{--                                            <select class="form-control" onchange="location = this.value;">--}}
{{--                                                <option value="">Select Template</option>--}}
{{--                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'TRANS_CAR' ]) }}">--}}
{{--                                                 Trans Car Template</option>--}}
{{--                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'MILELE_CAR' ]) }}">Milele Car Template</option>--}}
{{--                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'BUSINESS' ]) }}">Business</option>--}}
{{--                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'INDIVIDUAL' ]) }}">Individual</option>--}}
{{--                                            </select>--}}
{{--                                        </td>--}}
                                        <td>
                                            @can('LOI-approve')
                                                @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-approve');
                                                @endphp
                                                @if ($hasPermission)
                                                        <button type="button" data-id="{{ $letterOfIndent->id }}" data-url="{{ route('letter-of-indent.request-supplier-approval') }}"
                                                                class="btn btn-warning btn-sm btn-request-supplier-approval" title="Send For Supplier Approval">Send Request</button>
                                                @endif
                                            @endcan
                                        </td>
                                        <td>
                                            @can('LOI-edit')
                                                @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-edit');
                                                @endphp
                                                @if ($hasPermission)
                                                    <a href="{{ route('letter-of-indents.edit',$letterOfIndent->id) }}">
                                                        <button type="button" class="btn btn-soft-green btn-sm " title="Edit LOI"><i class="fa fa-edit"></i></button>
                                                    </a>
                                                @endif
                                            @endcan

                                            <button type="button" class="btn btn-soft-violet btn-sm" title="View LOI Item Lists" data-bs-toggle="modal" data-bs-target="#view-loi-items-{{$letterOfIndent->id}}">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            <button type="button" class="btn btn-dark-blue btn-sm" title="View LOI Documents" data-bs-toggle="modal" data-bs-target="#view-loi-docs-{{$letterOfIndent->id}}">
                                                <i class="fa fa-file-pdf"></i>
                                            </button>
                                                @can('LOI-delete')
                                                    @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-delete');
                                                    @endphp
                                                    @if ($hasPermission)
                                                        <button type="button" class="btn btn-danger btn-sm loi-button-delete" title="Delete LOI"
                                                                data-id="{{ $letterOfIndent->id }}" data-url="{{ route('letter-of-indents.destroy', $letterOfIndent->id) }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                        </td>

                                        <div class="modal fade" id="view-loi-items-{{$letterOfIndent->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">LOI Items</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-3">
                                                        @if($letterOfIndent->letterOfIndentItems->count() > 0)
                                                            <div class="row d-none d-lg-block d-xl-block d-xxl-block">
                                                                <div class="d-flex">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="row">
                                                                            <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                <dt>Model</dt>
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <dt>SFX </dt>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt>Model Year </dt>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                <dt>LOI Description</dt>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt>Quantity</dt>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @foreach($letterOfIndent->letterOfIndentItems as $value => $LOIItem)
                                                                <div class="row">
                                                                    <div class="d-flex">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                                            <hr>
                                                                            <div class="row mt-3">
                                                                                <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Model</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->model ?? ''}} </dl>
                                                                                </div>
                                                                                <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                    <dt  class=" d-lg-none d-xl-none d-xxl-none">SFX</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->sfx ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model Year</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->model_year ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">LOI Description</dt>
                                                                                    <dl> {{ $LOIItem->loi_description ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Quantity</dt>
                                                                                    <dl>{{ $LOIItem->quantity }}</dl>
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
                                                                @if($letterOfIndent->signature)
                                                                    <div class="col-lg-12 m-5">
                                                                        <label class="form-label fw-bold">Signature</label>
                                                                        <img src="{{ url('LOI-Signature/'.$letterOfIndent->signature) }}">
                                                                    </div>
                                                                @endif
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
                <div class="tab-pane fade show " id="waiting-for-approval-LOI">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="waiting-for-approval-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                                <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.NO</th>
                                    <th>LOI Number</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Destination</th>
                                    <th>Prefered Location</th>
                                    <th> Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                </div>
                                @foreach ($approvalWaitingLOIs as $key => $letterOfIndent)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td> {{ $letterOfIndent->uuid }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                        <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->category }}</td>
                                        <td>{{ $letterOfIndent->dealers }}</td>
                                        <td>
                                            @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                {{ $LoiSoNumber->so_number }}
                                                @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $letterOfIndent->destination }}</td>
                                        <td>{{ $letterOfIndent->prefered_location }}</td>
                                        <td>{{ $letterOfIndent->status }}</td>
                                        <td>
                                            @can('LOI-edit')
                                                @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-edit');
                                                @endphp
                                                @if ($hasPermission)
                                                    <a href="{{ route('letter-of-indents.edit',$letterOfIndent->id) }}">
                                                        <button type="button" class="btn btn-soft-green btn-sm " title="Edit LOI"><i class="fa fa-edit"></i></button>
                                                    </a>
                                                @endif
                                            @endcan

                                            <button type="button" class="btn btn-soft-violet btn-sm" title="View LOI Item Lists" data-bs-toggle="modal" data-bs-target="#view-loi-items-{{$letterOfIndent->id}}">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            <button type="button" class="btn btn-dark-blue btn-sm" title="View LOI Documents" data-bs-toggle="modal" data-bs-target="#view-loi-docs-{{$letterOfIndent->id}}">
                                                <i class="fa fa-file-pdf"></i>
                                            </button>
                                            @can('LOI-delete')
                                                @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-delete');
                                                @endphp
                                                @if ($hasPermission)
                                                    <button type="button" class="btn btn-danger btn-sm loi-button-delete" title="Delete LOI"
                                                            data-id="{{ $letterOfIndent->id }}" data-url="{{ route('letter-of-indents.destroy', $letterOfIndent->id) }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            @endcan
                                        </td>

                                        <div class="modal fade" id="view-loi-items-{{$letterOfIndent->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">LOI Items</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-3">
                                                        @if($letterOfIndent->letterOfIndentItems->count() > 0)
                                                            <div class="row d-none d-lg-block d-xl-block d-xxl-block">
                                                                <div class="d-flex">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="row">
                                                                            <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                <dt>Model</dt>
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <dt>SFX </dt>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt>Model Year </dt>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                <dt>LOI Description</dt>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt>Quantity</dt>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @foreach($letterOfIndent->letterOfIndentItems as $value => $LOIItem)
                                                                <div class="row">
                                                                    <div class="d-flex">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                                            <hr>
                                                                            <div class="row mt-3">
                                                                                <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Model</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->model ?? ''}} </dl>
                                                                                </div>
                                                                                <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                    <dt  class=" d-lg-none d-xl-none d-xxl-none">SFX</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->sfx ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model Year</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->model_year ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">LOI Description</dt>
                                                                                    <dl> {{ $LOIItem->loi_description ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Quantity</dt>
                                                                                    <dl>{{ $LOIItem->quantity }}</dl>
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
                                                                @if($letterOfIndent->signature)
                                                                    <div class="col-lg-12 m-5">
                                                                        <label class="form-label fw-bold">Signature</label>
                                                                        <img src="{{ url('LOI-Signature/'.$letterOfIndent->signature) }}">
                                                                    </div>
                                                                @endif
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
                                    <th>S.NO:</th>
                                    <th>LOI Number</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Destination</th>
                                    <th>Prefered Location</th>
                                    <th> Status</th>
                                    @can('LOI-approve')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-approve');
                                        @endphp
                                        @if ($hasPermission)
                                             <th>Utilization Qty Update</th>
                                        @endif
                                    @endcan
{{--                                    <th>LOI</th>--}}
                                    <th width="150px">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                </div>
                                @foreach ($supplierApprovedLOIs as $key => $letterOfIndent)
                                    <tr>
                                        <td> {{ ++$i }}</td>
                                        <td> {{ $letterOfIndent->uuid }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                        <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->category }}</td>
                                        <td>{{ $letterOfIndent->dealers }}</td>
                                        <td>
                                             @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                 {{ $LoiSoNumber->so_number }}
                                            @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $letterOfIndent->destination }}</td>
                                        <td>{{ $letterOfIndent->prefered_location }}</td>
                                        <td>{{ $letterOfIndent->status }}</td>

                                        @can('LOI-approve')
                                            @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-approve');
                                            @endphp
                                            @if ($hasPermission)
                                                <td>
                                                    @if($letterOfIndent->total_loi_quantity > $letterOfIndent->total_approved_quantity)
                                                        <a href="{{ route('letter-of-indents.milele-approval',['id' => $letterOfIndent->id ]) }}">
                                                            <button type="button" class="btn btn-soft-green btn-sm" title="Update Utilization Quantity" >
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                        </a>
                                                     @endif
                                                </td>
                                            @endif
                                        @endcan

{{--                                        <td>--}}
{{--                                            <select class="form-control" onchange="location = this.value;">--}}
{{--                                                <option value="">Select Template</option>--}}
{{--                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'TRANS_CAR' ]) }}">--}}
{{--                                                    Trans Car Template</option>--}}
{{--                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'MILELE_CAR' ]) }}">Milele Car Template</option>--}}
{{--                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'BUSINESS' ]) }}">Business</option>--}}
{{--                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'INDIVIDUAL' ]) }}">Individual</option>--}}
{{--                                            </select>--}}
{{--                                        </td>--}}
                                        <td>

                                            <button type="button" class="btn btn-soft-violet primary btn-sm" title="View LOI Item Lists" data-bs-toggle="modal" data-bs-target="#view-supplier-approved-loi-items-{{$letterOfIndent->id}}">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            <button type="button" class="btn btn-dark-blue btn-sm" title="View LOI Documents" data-bs-toggle="modal" data-bs-target="#view-supplier-approved-loi-docs-{{$letterOfIndent->id}}">
                                                <i class="fa fa-file-pdf"></i>
                                            </button>
                                        </td>
                                        <div class="modal fade" id="view-supplier-approved-loi-items-{{$letterOfIndent->id}}"
                                             tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable ">
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
                                                                                <dt>Model</dt>
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <dt>SFX</dt>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt>Model Year</dt>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                <dt>LOI Description</dt>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt>Quantity</dt>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @foreach($letterOfIndent->letterOfIndentItems as $value => $LOIItem)
                                                                <div class="row">
                                                                    <div class="d-flex">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                                            <hr>
                                                                            <div class="row mt-3">
                                                                                <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Model</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->model ?? ''}} </dl>
                                                                                </div>
                                                                                <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">SFX</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->sfx ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model Year</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->model_year ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">LOI Description</dt>
                                                                                    <dl> {{ $LOIItem->loi_description ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none fw-bold">Quantity</dt>
                                                                                    <dl>{{ $LOIItem->quantity }}</dl>
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

                                        <div class="modal tall fade" id="view-supplier-approved-loi-docs-{{$letterOfIndent->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable" >
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
                                                                                <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}" ></embed>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                @if($letterOfIndent->signature)
                                                                    <div class="col-lg-12 m-5">
                                                                        <label class="form-label fw-bold">Signature</label>
                                                                        <img src="{{ url('LOI-Signature/'.$letterOfIndent->signature) }}" width="100px;" height="100px">
                                                                    </div>
                                                                @endif
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
                                    <th>S.NO:</th>
                                    <th>LOI Number</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Category</th>
                                    <th>Dealer</th>
                                    <th>So Number</th>
                                    <th>Destination</th>
                                    <th>Prefered Location</th>
                                    <th>Status</th>
                                    @can('LOI-approve')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-approve');
                                        @endphp
                                        @if ($hasPermission)
                                            <th>Utilization Qty Update</th>
                                        @endif
                                    @endcan
                                    <th>Total Quantity</th>
                                    <th>Utilized Quantity</th>
                                    <th width="200">LOI </th>
                                    <th width="150px">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                </div>
                                @foreach ($partialApprovedLOIs as $key => $letterOfIndent)
                                    <tr>
                                        <td> {{ ++$i }}</td>
                                        <td> {{ $letterOfIndent->uuid }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                        <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->category }}</td>
                                        <td>{{ $letterOfIndent->dealers }}</td>
                                        <td>
                                            @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                 {{ $LoiSoNumber->so_number }}
                                            @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $letterOfIndent->destination }}</td>
                                        <td>{{ $letterOfIndent->prefered_location }}</td>
                                        <td>{{ $letterOfIndent->status }}</td>

                                        @can('LOI-approve')
                                            @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-approve');
                                            @endphp
                                            @if ($hasPermission)
                                            <td>
                                                @if($letterOfIndent->total_loi_quantity > $letterOfIndent->total_approved_quantity)
                                                    <a href="{{ route('letter-of-indents.milele-approval',['id' => $letterOfIndent->id ]) }}">
                                                        <button type="button" class="btn btn-soft-green btn-sm" title="Utilization Quantity Update" >
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    </a>
                                                 @endif
                                            </td>
                                            @endif
                                        @endcan

                                        <td> {{ $letterOfIndent->total_quantity }} </td>
                                        <td> {{ $letterOfIndent->utilized_quantity }} </td>
                                        <td>
                                            <select class="form-control" onchange="location = this.value;">
                                                <option value="">Select Template</option>
                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'TRANS_CAR' ]) }}">
                                                    Trans Car Template</option>
                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'MILELE_CAR' ]) }}">Milele Car Template</option>
                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'BUSINESS' ]) }}">Business</option>
                                                <option value="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => 'INDIVIDUAL' ]) }}">Individual</option>
                                            </select>
                                        </td>
                                        <td>
                                            @if($letterOfIndent->is_pfi_pending_for_loi == true)
                                                <a href="{{ route('pfi.create',['id' => $letterOfIndent->id ]) }}">
                                                    <button type="button"  class="btn btn-soft-blue btn-sm">Add PFI</button>
                                                </a>
                                            @endif
        {{--                                    <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id ]) }}">--}}
        {{--                                        <button type="button" class="btn btn-primary btn-sm">LOI PDF</button>--}}
        {{--                                    </a>--}}
                                            <button type="button" title="View LOI Items list" class="btn btn-soft-violet btn-sm" data-bs-toggle="modal" data-bs-target="#view-partial-approved-loi-items-{{$letterOfIndent->id}}">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            <button type="button" title="View LOI Documents" class="btn btn-dark-blue btn-sm" data-bs-toggle="modal" data-bs-target="#view-partial-approved-loi-docs-{{$letterOfIndent->id}}">
                                                <i class="fa fa-file-pdf"></i>
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
                                                                                <dt>Model</dt>
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <dt>SFX</dt>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt>Model Year</dt>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                <dt>LOI Description</dt>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt>Quantity</dt>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @foreach($letterOfIndent->letterOfIndentItems as $value => $LOIItem)
                                                                <div class="row">
                                                                    <div class="d-flex">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                                            <hr>
                                                                            <div class="row mt-3">
                                                                                <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->model ?? ''}} </dl>
                                                                                </div>
                                                                                <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                    <dt  class="d-lg-none d-xl-none d-xxl-none ">SFX</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->sfx ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model Year</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->model_year ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none">LOI Description</dt>
                                                                                    <dl> {{ $LOIItem->loi_description ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Quantity</dt>
                                                                                    <dl>{{ $LOIItem->quantity }}</dl>
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
                                                                @if($letterOfIndent->signature)
                                                                    <div class="col-lg-12 m-5">
                                                                        <label class="form-label fw-bold">Signature</label>
                                                                            <img src="{{ url('LOI-Signature/'.$letterOfIndent->signature) }}" width="100px;" height="100px">
                                                                    </div>
                                                                @endif
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
                                    <th>S.NO:</th>
                                    <th>LOI Number</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Category</th>
                                    <th>Dealer</th>
                                    <th>So Number</th>
                                    <th>Destination</th>
                                    <th>Prefered Location</th>
                                    <th>Status</th>
                                    <th>Review</th>
                                    <th width="100px">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                </div>
                                @foreach ($rejectedLOIs as $key => $letterOfIndent)
                                    <tr>
                                        <td> {{ ++$i }}</td>
                                        <td> {{ $letterOfIndent->uuid }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                        <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->category }}</td>
                                        <td>{{ $letterOfIndent->dealers }}</td>
                                        <td>
                                            @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                 {{ $LoiSoNumber->so_number }}
                                            @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $letterOfIndent->destination }}</td>
                                        <td>{{ $letterOfIndent->prefered_location }}</td>
                                        <td>{{ $letterOfIndent->status }}</td>
                                        <th>{{ $letterOfIndent->review }}</th>
                                        <td>
                                            <button type="button" class="btn btn-soft-violet btn-sm" title="View LOI Item Lists" data-bs-toggle="modal" data-bs-target="#view-rejected-loi-items-{{$letterOfIndent->id}}">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            <button type="button" title="View LOI Documents" class="btn btn-dark-blue btn-sm" data-bs-toggle="modal" data-bs-target="#view-rejected-loi-docs-{{$letterOfIndent->id}}">
                                                <i class="fa fa-file-pdf"></i>
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
                                                                                <dt>Model</dt>
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <dt>SFX</dt>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt>Model Year</dt>
                                                                            </div>
                                                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                <dt>LOI Description</dt>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <dt>Quantity</dt>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @foreach($letterOfIndent->letterOfIndentItems as $value => $LOIItem)
                                                                <div class="row">
                                                                    <div class="d-flex">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                                            <hr>
                                                                            <div class="row mt-3">
                                                                                <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Model</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->model ?? ''}} </dl>
                                                                                </div>
                                                                                <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                    <dt  class="d-lg-none d-xl-none d-xxl-none">SFX</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->sfx ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model Year</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->model_year ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none">LOI Description</dt>
                                                                                    <dl> {{ $LOIItem->loi_description ?? '' }} </dl>
                                                                                </div>
                                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Quantity</dt>
                                                                                    <dl>{{ $LOIItem->quantity }}</dl>
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
                                                                @if($letterOfIndent->signature)
                                                                    <div class="col-lg-12 m-5">
                                                                        <label class="form-label fw-bold">Signature</label>
                                                                        <img src="{{ url('LOI-Signature/'.$letterOfIndent->signature) }}" width="100px;" height="100px">
                                                                    </div>
                                                                @endif
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
        @endif
    @endcan
   <script type="text/javascript">
       $('.btn-request-supplier-approval').on('click',function(){
           let id = $(this).attr('data-id');
           let url =  $(this).attr('data-url');
           console.log(url);
           console.log(id);
           var confirm = alertify.confirm('Are you sure you want to send this LOI for supplier Approval?',function (e) {
               if (e) {
                   $.ajax({
                       type: "POST",
                       url: url,
                       dataType: "json",
                       data: {
                           id: id,
                           _token: '{{ csrf_token() }}'
                       },
                       success:function (data) {
                           location.reload();
                           alertify.success('Approval Request Send Successfully.');
                       }
                   });
               }
           }).set({title:"Delete Item"})
       });

        $('.loi-button-delete').on('click',function(){
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
                            alertify.success('LOI Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
   </script>

@endsection


















