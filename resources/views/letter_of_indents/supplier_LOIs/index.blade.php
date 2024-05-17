@extends('layouts.table')
@section('content')
    <style>
        .modal {
            position: absolute;
            float: left;
            left: 50%;
            top: 50%;
            min-height: 500px;
            transform: translate(-50%, -50%);
        }

        body.modal-open {
            overflow: hidden;
        }
        .widthinput{
            height:32px!important;

        }
    </style>

    <div class="card-header">
        <h4 class="card-title">
           Supplier LOI Info

        </h4>
    </div>
{{--    <div class="card-body">--}}
{{--        <form id="form-search" action="{{ route('letter-of-indents.get-suppliers-LOIs') }}" >--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-3 col-md-3">--}}
{{--                    <div class="mb-3">--}}
{{--                        <label for="choices-single-default" class="form-label">Vendor</label>--}}
{{--                        <select class="form-control" autofocus  name="supplier_id"  id="supplier" required>--}}
{{--                            <option></option>--}}
{{--                            @foreach($suppliers as $supplier)--}}
{{--                                <option value="{{ $supplier->id }}" @if($supplierId){{ $supplier->id == $supplierId ? 'selected' : '' }} @endif>{{ $supplier->supplier }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-lg-3 col-md-3">--}}
{{--                    <div class="mt-4">--}}
{{--                        <button type="submit" class="btn btn-primary search-button" >Search</button>--}}
{{--                        <a href="{{ route('letter-of-indents.get-suppliers-LOIs') }}">--}}
{{--                            <button type="button" class="btn btn-secondary "> Refresh </button>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </div>--}}

    <div class="portfolio">
        <ul class="nav nav-pills nav-fill" id="my-tab">
            <li class="nav-item">
                <a class="nav-link tab-1 active" data-bs-toggle="tab" data-tab="PENDING" href="#pending-approved-LOI">Pending </a>
            </li>
            <li class="nav-item">
                <a class="nav-link tab-2" data-bs-toggle="tab" data-tab="APPROVED" href="#approved-LOI"> Approved </a>
            </li>
            <li class="nav-item">
                <a class="nav-link tab-3" data-bs-toggle="tab" data-tab="REJECTED" href="#rejected-LOI">Rejected</a>
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
                            <th>Category</th>
                            <th>So Number</th>
                            <th>Destination</th>
                            <th>Prefered Location</th>
                            <th>Submission Status</th>
                            <th>Approval Status</th>
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
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>
                                    @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                        {{ $LoiSoNumber->so_number }}
                                    @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                    @endforeach
                                </td>
                                <td>{{ $letterOfIndent->destination }}</td>
                                <td>{{ $letterOfIndent->prefered_location }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-soft-violet btn-sm" data-bs-toggle="modal" title="View LOI Item Lists"
                                            data-bs-target="#view-new-loi-items-{{$letterOfIndent->id}}"><i class="fa fa-list"></i>
                                    </button>
                                    <button type="button" class="btn btn-dark-blue btn-sm" data-bs-toggle="modal" title="View LOI Documents"
                                            data-bs-target="#view-new-loi-docs-{{$letterOfIndent->id}}"><i class="fa fa-file"></i>
                                    </button>
                                    @can('loi-supplier-approve')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-supplier-approve');
                                        @endphp
                                        @if ($hasPermission)
                                            <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#approve-LOI-{{ $letterOfIndent->id }}" > Approve </button>

                                            <button type="button" class="btn btn-danger modal-button btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#reject-LOI-{{$letterOfIndent->id}}"> Reject </button>
                                        @endif
                                    @endcan
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
                                                                            <dt  class=" d-lg-none d-xl-none d-xxl-none">Model Year</dt>
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
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                    <dt class="form-label font-size-13 text-muted">Customer :</dt>
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-sm-12">
                                                                    <dl> {{  $letterOfIndent->customer->name }}</dl>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                    <dt class="form-label font-size-13 text-muted">Category :</dt>
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-sm-12">
                                                                    <dl>{{ $letterOfIndent->category }} </dl>
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                    <dt class="form-label font-size-13 text-muted">LOI Date :</dt>
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-sm-12">
                                                                    <dl> {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }} </dl>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                    <dt class="form-label font-size-13 text-muted">So Number :</dt>
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-sm-12">
                                                                    <dl>
                                                                         @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                                        {{ $LoiSoNumber->so_number }}
                                                                        @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                                                        @endforeach
                                                                </dl>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                    <dt class="form-label font-size-13 text-muted">Destination :</dt>
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-sm-12">
                                                                    <dl>{{ $letterOfIndent->destination }} </dl>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                    <dt class="form-label font-size-13 text-muted">Prefered Location :</dt>
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-sm-12">
                                                                    <dl>{{ $letterOfIndent->prefered_location }} </dl>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                    <dt class="form-label font-size-13 text-muted">Rejection Date :</dt>
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-sm-12">
                                                                    <input type="date" name="loi_approval_date" id="rejection-date"
                                                                           required class="form-control widthinput" >
                                                                    <span id="loi-rejection-date-error" class="text-danger"> </span>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                    <dt class="form-label font-size-13 text-muted">Reason :</dt>
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-sm-12">
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
                                                 <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                            <dt class="form-label font-size-13 text-muted">Customer :</dt>
                                                        </div>
                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                            <dl> {{  $letterOfIndent->customer->name }}</dl>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                            <dt class="form-label font-size-13 text-muted">Category :</dt>
                                                        </div>
                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                            <dl>{{ $letterOfIndent->category }} </dl>
                                                        </div>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                            <dt class="form-label font-size-13 text-muted">LOI Date :</dt>
                                                        </div>
                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                            <dl> {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }} </dl>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                            <dt class="form-label font-size-13 text-muted">So Number :</dt>
                                                        </div>
                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                            <dl>
                                                                @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                                    {{ $LoiSoNumber->so_number }}
                                                                @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                                                @endforeach
                                                             </dl>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                            <dt class="form-label font-size-13 text-muted">Destination :</dt>
                                                        </div>
                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                            <dl>{{ $letterOfIndent->destination }} </dl>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                            <dt class="form-label font-size-13 text-muted">Prefered Location :</dt>
                                                        </div>
                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                            <dl>{{ $letterOfIndent->prefered_location }} </dl>
                                                        </div>
                                                    </div>
                                                     <div class="row">
                                                         <div class="col-lg-4 col-md-12 col-sm-12">
                                                             <dt class="form-label font-size-13 text-muted">Approval Date :</dt>
                                                         </div>
                                                         <div class="col-lg-8 col-md-12 col-sm-12">
                                                             <input type="date" name="loi_approval_date" id="approval-date"
                                                                    required class="form-control widthinput">
                                                             <span id="loi-approval-date-error" class="text-danger"> </span>
                                                         </div>
                                                     </div>
                                                    <input type="hidden" value="{{ $letterOfIndent->id }}" id="id">
                                                    <input type="hidden" value="APPROVE" id="status-approve">
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
                            <th>S.No:</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>So Number</th>
                            <th>Destination</th>
                            <th>Prefered Location</th>
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                            <th>Approved Date</th>
                            <th>Actions</th>

                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($approvedLOIs as $key => $letterOfIndent)
                            <tr>
                                <td> {{ ++$i }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>
                                    @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                        {{ $LoiSoNumber->so_number }}
                                    @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                    @endforeach
                                </td>
                                <td>{{ $letterOfIndent->destination }}</td>
                                <td>{{ $letterOfIndent->prefered_location }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->loi_approval_date)->format('d m Y') }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-soft-violet btn-sm" data-bs-toggle="modal" title="View LOI Item Lists"
                                            data-bs-target="#supplier-approved-loi-items-{{$letterOfIndent->id}}"><i class="fa fa-list"></i>
                                    </button>
                                    <button type="button" class="btn btn-dark-blue btn-sm" data-bs-toggle="modal" title="View LOI Documents"
                                            data-bs-target="#supplier-approved-loi-doc-{{$letterOfIndent->id}}"><i class="fa fa-file"></i>
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
                                                                            <dt  class=" d-lg-none d-xl-none d-xxl-none">Model Year</dt>
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
                            <th>S.No:</th>
                            <th>LOI Date</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>So Number</th>
                            <th>Destination</th>
                            <th>Prefered Location</th>
                            <th>Submission Status</th>
                            <th>Approval Status</th>
                            <th>Rejected Date</th>
                            <th>Review</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($rejectedLOIs as $key => $letterOfIndent)
                            <tr>
                                <td> {{ ++$i }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                <td>{{ $letterOfIndent->customer->name ?? '' }}</td>
                                <td>{{ $letterOfIndent->category }}</td>
                                <td>
                                    @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                        {{ $LoiSoNumber->so_number }}
                                    @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                    @endforeach
                                </td>
                                <td>{{ $letterOfIndent->destination }}</td>
                                <td>{{ $letterOfIndent->prefered_location }}</td>
                                <td>{{ $letterOfIndent->submission_status }}</td>
                                <td>{{ $letterOfIndent->status }}</td>
                                <td> {{ \Illuminate\Support\Carbon::parse($letterOfIndent->loi_approval_date)->format('d m Y') }}</td>
                                <td>{{ $letterOfIndent->review }}</td>
                                <td>
                                    <button type="button" class="btn btn-soft-violet btn-sm" data-bs-toggle="modal" title="View LOI Item Lists"
                                            data-bs-target="#supplier-rejected-loi-items-{{$letterOfIndent->id}}"><i class="fa fa-list"></i>
                                    </button>
                                    <button type="button" class="btn btn-dark-blue btn-sm" data-bs-toggle="modal" title="View LOI Documents"
                                            data-bs-target="#supplier-rejected-loi-doc-{{$letterOfIndent->id}}"><i class="fa fa-file-pdf"></i>
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
                                                                            <dt  class=" d-lg-none d-xl-none d-xxl-none">Model Year</dt>
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
                placeholder:'Select Vendor'
            })
            $("#form-search").validate({
            rules: {
                supplier_id: {
                    required: true,
                    },
                },
            });
                $('#approval-date').change(function () {
                    $msg= "";
                    removeLOIApprovalDateError($msg)
                });
                $('#rejection-date').change(function () {
                    $msg= "";
                    removeLOIRejectionDateError($msg)
                });

            $('.status-change-button-approve').click(function () {
                var id = $('#id').val();
                var status = $('#status-approve').val();
                var date = document.getElementById("approval-date").value
                if(date.length > 0) {
                    statusChange(id,status,date);
                }else{
                    $msg = "This field is required";
                    showLOIApprovalDateError($msg)
                }
            })
            $('.status-reject-button').click(function (e) {
                var id = $('#id').val();
                var status = $('#status-reject').val();
                var date = document.getElementById("rejection-date").value
                if(date.length > 0) {
                    statusChange(id,status,date)
                }else{
                    $msg = "This field is required";
                    showLOIRejectionDateError($msg)
                }
            })
            function statusChange(id,status,date) {

                let url = '{{ route('letter-of-indents.supplier-approval') }}';
                if(status == 'REJECTED') {
                        var message = 'Reject';
                        var review = $('#review').val();
                    }else{
                        var message = 'Approve';
                        var review = '';
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
                            review:review,
                            loi_approval_date:date,
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

            function showLOIApprovalDateError($msg)
            {
                document.getElementById("loi-approval-date-error").textContent=$msg;
                document.getElementById("approval-date").classList.add("is-invalid");
                document.getElementById("loi-approval-date-error").classList.add("paragraph-class");
            }
            function removeLOIApprovalDateError($msg)
            {
                document.getElementById("loi-approval-date-error").textContent="";
                document.getElementById("approval-date").classList.remove("is-invalid");
                document.getElementById("loi-approval-date-error").classList.remove("paragraph-class");
            }
            function showLOIRejectionDateError($msg)
            {
                document.getElementById("loi-rejection-date-error").textContent=$msg;
                document.getElementById("rejection-date").classList.add("is-invalid");
                document.getElementById("loi-rejection-date-error").classList.add("paragraph-class");
            }
            function removeLOIRejectionDateError($msg)
            {
                document.getElementById("loi-rejection-date-error").textContent="";
                document.getElementById("rejection-date").classList.remove("is-invalid");
                document.getElementById("loi-rejection-date-error").classList.remove("paragraph-class");
            }
    </script>
@endpush

















