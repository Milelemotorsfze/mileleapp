@extends('layouts.table')
@section('content')
    <style>
        /* .modal {
            position: absolute;
            min-height: 500px;
        } */
        .widthinput{
            height:32px!important;

        }
        /* body.modal-open {
            overflow: hidden;
        } */
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
                    <!-- <a  class="btn btn-sm btn-secondary float-end mr-2" href="{{ route('migrations.index') }}" >
                    <i class="fa fa-check" aria-hidden="true"></i> Migration Check</a> -->

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
                        <a class="nav-link" data-bs-toggle="pill" href="#supplier-response-LOI">Supplier Response LOI</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#milele-partial-approved-LOI"> Utilization Initiated LOI</a>
                    </li> -->
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
                                    <th>Country</th>
                                    <th> Status</th>
                                    <th>Is Expired</th>
                                    <th>Supplier Approval</th>
                                    <th>LOI</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                </div>
                                @foreach ($newLOIs as $key => $letterOfIndent)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $letterOfIndent->uuid }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                        <td>{{ $letterOfIndent->client->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->category }}</td>
                                        <td>{{ $letterOfIndent->dealers }}</td>
                                        <td>
                                            @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                 {{ $LoiSoNumber->so_number }}
                                            @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                            @endforeach
                                         </td>
                                        <td>{{ $letterOfIndent->client->country->name ?? '' }}</td>
                                        <td>
                                             {{ $letterOfIndent->status }}
                                         
                                         </td>
                                         <td>  
                                            @if($letterOfIndent->is_loi_expired == true)
                                            Expired  @else  Not Expired @endif
                                        </td>
                                        <td>
                                            @can('LOI-approve')
                                                @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-approve');
                                                @endphp
                                                @if ($hasPermission)
                                                    @if($letterOfIndent->is_expired == false)
                                                        <button type="button" data-id="{{ $letterOfIndent->id }}" data-url="{{ route('letter-of-indent.request-supplier-approval') }}"
                                                                class="btn btn-warning btn-sm btn-request-supplier-approval" title="Send For Supplier Approval">Send Request</button>
                                                    @endif
                                                @endif
                                            @endcan
                                        </td>
                                        <td>
                                            @foreach($letterOfIndent->LOITemplates as $LOITemplate)
                                            <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => $LOITemplate->template_type ]) }}">
                                                {{ ucwords( str_replace('_', ' ', $LOITemplate->template_type) )}}
                                            </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('LOI-edit')
                                                @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-edit');
                                                @endphp
                                                @if ($hasPermission)
                                                    <a href="{{ route('letter-of-indents.edit',$letterOfIndent->id) }}">
                                                        <button type="button" class="btn btn-soft-green btn-sm mt-1" title="Edit LOI"><i class="fa fa-edit"></i></button>
                                                    </a>
                                                @endif
                                            @endcan

                                            <button type="button" class="btn btn-soft-violet btn-sm mt-1" title="View LOI Item Lists" data-bs-toggle="modal" data-bs-target="#view-loi-items-{{$letterOfIndent->id}}">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            <button type="button" class="btn btn-dark-blue btn-sm mt-1" title="View Customer Documents" data-bs-toggle="modal" data-bs-target="#view-loi-docs-{{$letterOfIndent->id}}">
                                                <i class="fa fa-file-pdf"></i>
                                            </button>
                                                @can('LOI-delete')
                                                    @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-delete');
                                                    @endphp
                                                    @if ($hasPermission)
                                                        <button type="button" class="btn btn-danger btn-sm loi-button-delete mt-1" title="Delete LOI"
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
                                                                                <dt>Model Line </dt>
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
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model Line</dt>
                                                                                    <dl>  {{ $LOIItem->masterModel->modelLine->model_line ?? '' }} </dl>
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
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel"> Customer Documents</h1>
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
                <div class="tab-pane fade show" id="waiting-for-approval-LOI">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="waiting-for-approval-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                                <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.NO:</th>
                                    <th>LOI Number</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Country</th>
                                    <th>LOI Quantity</th>
                                    <th>Status</th>
                                    <th>Is Expired</th>
                                    <th>LOI</th>
                                    <th>Remarks</th>
                                    <th>Approve/Reject</th>
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
                                        <td>{{ $letterOfIndent->client->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->category }}</td>
                                        <td>{{ $letterOfIndent->dealers }}</td>
                                        <td>
                                            @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                {{ $LoiSoNumber->so_number }}
                                                @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $letterOfIndent->client->country->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->total_loi_quantity }}</td>
                                        <td> 
                                             {{ $letterOfIndent->status }}
                                         
                                        </td>
                                        <td>  
                                            @if($letterOfIndent->is_loi_expired == true)
                                            Expired  @else  Not Expired @endif
                                        </td>
                                        <td>
                                           
                                            @foreach($letterOfIndent->LOITemplates as $LOITemplate)
                                                <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => $LOITemplate->template_type ]) }}">
                                                    {{ ucwords( str_replace('_', ' ', $LOITemplate->template_type) )}}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>{{ $letterOfIndent->review }}</td>
                                        <td>
                                            @can('loi-supplier-approve')
                                                @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-supplier-approve');
                                                @endphp
                                                @if ($hasPermission)
                                                    @if($letterOfIndent->is_expired == false)
                                                        <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#approve-LOI-{{ $letterOfIndent->id }}" > Approve </button>

                                                        <button type="button" class="btn btn-danger modal-button btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#reject-LOI-{{$letterOfIndent->id}}"> Reject </button>
                                                    
                                                    @endif
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
                                            <button type="button" class="btn btn-dark-blue btn-sm mt-1" title="View Customer Documents" data-bs-toggle="modal" data-bs-target="#view-loi-docs-{{$letterOfIndent->id}}">
                                                <i class="fa fa-file-pdf"></i>
                                            </button>
                                            @can('LOI-delete')
                                                @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-delete');
                                                @endphp
                                                @if ($hasPermission)
                                                    <button type="button" class="btn btn-danger btn-sm loi-button-delete mt-1" title="Delete LOI"
                                                            data-id="{{ $letterOfIndent->id }}" data-url="{{ route('letter-of-indents.destroy', $letterOfIndent->id) }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            @endcan
                                        </td>
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
                                                                            <dl> {{  $letterOfIndent->client->name }}</dl>
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
                                                                            <dt class="form-label font-size-13 text-muted">Country :</dt>
                                                                        </div>
                                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                                            <dl>{{ $letterOfIndent->client->country->name ?? '' }} </dl>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                                                            <dt class="form-label font-size-13 text-muted">Rejection Date :</dt>
                                                                        </div>
                                                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                                                            <input type="date" name="loi_approval_date" id="rejection-date"
                                                                                   required class="form-control widthinput" max="{{ \Illuminate\Support\Carbon::today()->format('Y-m-d') }}" >
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
                                                                    <dl> {{  $letterOfIndent->client->name }}</dl>
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
                                                                    <dt class="form-label font-size-13 text-muted">Country :</dt>
                                                                </div>
                                                                <div class="col-lg-8 col-md-12 col-sm-12">
                                                                    <dl>{{ $letterOfIndent->client->country->name ?? '' }} </dl>
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
                                                                                <dt>Model Line </dt>
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
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model Line</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->modelLine->model_line ?? '' }}</dl>
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
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel"> Customer Documents</h1>
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
                <div class="tab-pane fade" id="supplier-response-LOI">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="supplier-response-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
                                <thead class="bg-soft-secondary">
                                <tr>
                                    <th>LOI Number</th>
                                    <th>LOI Date</th>
                                    <th>Customer</th>
                                    <th>Customer Type</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Country</th>
                                    <th>LOI Quantity</th>
                                    <th>Utilized Quantity</th>
                                    <th>Approval Status</th>
                                    <th>Approved / Rejected Date</th>
                                    <th>Is Expired</th>
                                    <th>LOI</th>
                                    <th>Remarks</th>
                                    <th width="150px">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                </div>
                                @foreach ($supplierApprovedLOIs as $key => $letterOfIndent)
                                    <tr>
                                     
                                        <td> {{ $letterOfIndent->uuid }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }}</td>
                                        <td>{{ $letterOfIndent->client->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->client->customertype ?? '' }}</td>
                                        <td>{{ $letterOfIndent->category }}</td>
                                        <td>{{ $letterOfIndent->dealers }}</td>
                                        <td>
                                             @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                 {{ $LoiSoNumber->so_number }}
                                            @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $letterOfIndent->client->country->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->total_loi_quantity }}</td>
                                        <td> {{ $letterOfIndent->utilized_quantity }} </td>
                                       
                                        <td>{{ $letterOfIndent->submission_status }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->loi_approval_date)->format('Y-m-d')  }}</td>
                                       <td>  @if($letterOfIndent->is_loi_expired == true)
                                             Expired  @else  Not Expired @endif
                                        </td>
                                        <td>
                                            @foreach($letterOfIndent->LOITemplates as $LOITemplate)
                                                <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => $LOITemplate->template_type ]) }}">
                                                    {{ ucwords( str_replace('_', ' ', $LOITemplate->template_type) )}}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>{{ $letterOfIndent->review }}</td>                                   
                                        <td>
                                             @if($letterOfIndent->is_expired == false)
                                                <button type="button" title="Update Utilization Quantity" class="btn btn-soft-green btn-sm mt-1" data-bs-toggle="modal" 
                                                data-bs-target="#update-utilization-quantity-{{$letterOfIndent->id}}">
                                                    <i class="fa fa-save"></i>
                                                </button>
                                            @endif
                                            <!-- allow only not expired LOI to ctreate PFI -->
                                            @can('PFI-create')
                                                @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-create');
                                                @endphp
                                                @if ($hasPermission)
                                                   @if($letterOfIndent->is_expired == false)
                                                        <a href="{{ route('pfi.create',['id' => $letterOfIndent->id ]) }}">
                                                            <button type="button"  class="btn btn-soft-blue btn-sm mt-1">Add PFI</button>
                                                        </a>
                                                    @endif
                                                @endif
                                            @endcan
                                            <button type="button" class="btn btn-soft-violet primary btn-sm mt-1" title="View LOI Item Lists" data-bs-toggle="modal" data-bs-target="#view-supplier-response-loi-items-{{$letterOfIndent->id}}">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            <button type="button" class="btn btn-dark-blue btn-sm mt-1" title="View Customer Documents" data-bs-toggle="modal" data-bs-target="#view-supplier-response-loi-docs-{{$letterOfIndent->id}}">
                                                <i class="fa fa-file-pdf"></i>
                                            </button>
                                        </td>
                                        <div class="modal fade" id="view-supplier-response-loi-items-{{$letterOfIndent->id}}"
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
                                                                                <dt>Model Line</dt>
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
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model Line</dt>
                                                                                    <dl>  {{ $LOIItem->masterModel->modelLine->model_line ?? '' }}</dl>
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

                                        <div class="modal tall fade" id="view-supplier-response-loi-docs-{{$letterOfIndent->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable" >
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel"> Customer Documents</h1>
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
                                        <div class="modal fade " id="update-utilization-quantity-{{$letterOfIndent->id}}" data-bs-backdrop="static" 
                                                      tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog  modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel"> Update Utilized Quantity</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('utilization-quantity-update', $letterOfIndent->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="col-lg-12">
                                                                <div class="row p-2">
                                                                    <input type="number" min="0" placeholder="Utilized Quantity" required max="{{$letterOfIndent->total_quantity}}" name="utilized_quantity" class="form-control" >
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
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- <div class="tab-pane fade" id="milele-partial-approved-LOI">
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
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th>Total Quantity</th>
                                    <th>Utilized Quantity</th>
                                    <th>Update Utilized Quantity</th>
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
                                        <td>{{ $letterOfIndent->client->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->category }}</td>
                                        <td>{{ $letterOfIndent->dealers }}</td>
                                        <td>
                                            @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                 {{ $LoiSoNumber->so_number }}
                                            @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $letterOfIndent->client->country->name ?? '' }}</td>
                                        <td>{{ $letterOfIndent->status }}</td>
                                        <td> {{ $letterOfIndent->total_quantity }} </td>
                                        <td> {{ $letterOfIndent->utilized_quantity }} </td>
                                        <td>
                                            <button type="button" title="Update Utilization Quantity" class="btn btn-soft-green btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#update-utilization-quantity-{{$letterOfIndent->id}}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                         </td>
                                        <td>
                                            @foreach($letterOfIndent->LOITemplates as $LOITemplate)
                                                <a href="{{ route('letter-of-indents.generate-loi',['id' => $letterOfIndent->id,'type' => $LOITemplate->template_type ]) }}">
                                                    {{ ucwords( str_replace('_', ' ', $LOITemplate->template_type) )}}
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($letterOfIndent->is_pfi_pending_for_loi == true)
                                                <a href="{{ route('pfi.create',['id' => $letterOfIndent->id ]) }}">
                                                    <button type="button"  class="btn btn-soft-blue btn-sm">Add PFI</button>
                                                </a>
                                            @endif
       
                                            <button type="button" title="View LOI Items list" class="btn btn-soft-violet btn-sm" data-bs-toggle="modal" data-bs-target="#view-partial-approved-loi-items-{{$letterOfIndent->id}}">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            <button type="button" title="View Customer Documents" class="btn btn-dark-blue btn-sm" data-bs-toggle="modal" data-bs-target="#view-partial-approved-loi-docs-{{$letterOfIndent->id}}">
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
                                                                                <dt>Model Line</dt>
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
                                                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model Line</dt>
                                                                                    <dl> {{ $LOIItem->masterModel->modelLine->model_line ?? '' }} </dl>
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
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel"> Customer Documents</h1>
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
                                        <div class="modal fade " id="update-utilization-quantity-{{$letterOfIndent->id}}" data-bs-backdrop="static" 
                                                      tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog ">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel"> Update Utilized Quantity</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('utilization-quantity-update', $letterOfIndent->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="col-lg-12">
                                                                <div class="row p-2">
                                                                    <input type="number" min="0" placeholder="Utilized Quantity" required max="{{$letterOfIndent->total_quantity}}" name="utilized_quantity" class="form-control" >
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
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> -->
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#approval-date').change(function () {
                $msg = "";
                removeLOIApprovalDateError($msg)
            });
            $('#rejection-date').change(function () {
                $msg = "";
                removeLOIRejectionDateError($msg)
            });
            $('.status-change-button-approve').click(function () {
                var id = $('#id').val();
                var status = $('#status-approve').val();
                var date = document.getElementById("approval-date").value
                if (date.length > 0) {
                    statusChange(id, status, date);
                } else {
                    $msg = "This field is required";
                    showLOIApprovalDateError($msg)
                }
            })
            $('.status-reject-button').click(function (e) {
                var id = $('#id').val();
                var status = $('#status-reject').val();
                var date = document.getElementById("rejection-date").value
                if (date.length > 0) {
                    statusChange(id, status, date)
                } else {
                    $msg = "This field is required";
                    showLOIRejectionDateError($msg)
                }
            })

            function statusChange(id, status, date) {
                let url = '{{ route('letter-of-indents.supplier-approval') }}';
                if (status == 'REJECTED') {
                    var message = 'Reject';
                    var review = $('#review').val();
                } else {
                    var message = 'Approve';
                    var review = '';
                }
                var confirm = alertify.confirm('Are you sure you want to ' + message + ' this item ?', function (e) {
                    if (e) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: "json",
                            data: {
                                id: id,
                                status: status,
                                review: review,
                                loi_approval_date: date,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (data) {
                                window.location.reload();
                                alertify.success(status + " Successfully");
                            }
                        });
                    }
                }).set({title: "Status Change"})
            }
        });

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

        function showLOIApprovalDateError($msg)
        {
            console.log("element error function");
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
            console.log("rejection error");
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


















