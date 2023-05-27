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
           Supplier LOI Info
        </h4>
    </div>
    <div class="card-body">
        <form action="{{ route('letter-of-indents.get-suppliers-LOIs') }}" >
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 ">Supplier</label>
                        <select class="form-control" data-trigger name="supplier_id" id="supplier">
                            <option >Select The Supplier</option>
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
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="viewdealinfo-{{ $letterOfIndent->id }}" data-modal-type="ITEM">View </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="view-LOI-doc-{{ $letterOfIndent->id }}"  data-modal-type="DOC">View </button>
                                </td>
                                <td>
                                    <button type="button" class=" btn btn-primary btn-sm status-change-button-approve" data-id="{{ $letterOfIndent->id }}"
                                            data-status="{{ \App\Models\LetterOfIndent::LOI_STATUS_APPROVED }}">
                                        Approval
                                    </button>
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
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="choices-single-default" class="form-label font-size-13 text-muted">Reason</label>
                                                    <textarea cols="75" name="review" id="review" rows="5" ></textarea>
                                                </div>
                                                <input type="hidden" value="{{ $letterOfIndent->id }}" id="id">
                                                <input type="hidden" value="{{ \App\Models\LetterOfIndent::LOI_STATUS_REJECTED }}" id="status">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary btnright status-reject-button">UPDATE</button>
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
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="supplier-approved-loi-items-{{ $letterOfIndent->id }}" >View </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="supplier-approved-loi-doc-{{ $letterOfIndent->id }}" >View </button>
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
        <div class="tab-pane fade" id="rejected-LOI">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="milele-partial-approved-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
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
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="partial-approved-loi-items-{{ $letterOfIndent->id }}" >View </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary modal-button btn-sm" data-bs-toggle="modal"
                                            data-modal-id="partial-approved-loi-doc-{{ $letterOfIndent->id }}" >View </button>
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

    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#my-tab a[href="' + activeTab + '"]').tab('show');
            }

            $('.status-change-button-approve').click(function () {

                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                statusChange(id,status);
            })
            $('.status-reject-button').click(function (e) {
                var id = $('#id').val();
                var status = $('#status').val();
                statusChange(id,status)
            })
            function statusChange(id,status) {
                let url = '{{ route('letter-of-indents.status-change') }}';
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        id: id,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success:function (data) {
                        window.location.reload();
                        alertify.success(status +" Successfully");
                    }
                });
            }
        })
    </script>
@endsection


















