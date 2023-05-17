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
            top: 1;
            z-index: 1055; /* [2] */
        }
    </style>
    <div class="card-header">
        <h4 class="card-title">
            LOI Info
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive" >
            <table id="new-LOI-table" class="table table-striped table-editable table-edits table table-condensed" >
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
                                    data-modal-id="viewdealinfo-{{ $letterOfIndent->id }}" data-modal-type="ITEM">View </button>
                        </td>
                        <td><button type="button" class="btn btn-primary modal-button" data-bs-toggle="modal"
                                    data-modal-id="view-LOI-doc-{{ $letterOfIndent->id }}"  data-modal-type="DOC">View </button>
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
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#new-LOI-table').DataTable();
        })
        $(document).ready(function(){
            $('.modal-button').on('click', function(){
                alert("ok");
                var modalId = $(this).data('modal-id');
                var type = $(this).data('modal-type');

                if (type == 'ITEM') {
                    $('#' + modalId).addClass('modalshow');
                    $('#' + modalId).removeClass('modalhide');
                }else {
                    $('#' + modalId).addClass('modalshow');
                    $('#' + modalId).removeClass('modalhide');
                }
            });

            $('.close').on('click', function(){
                $('.modal').addClass('modalhide');
                $('.modal').removeClass('modalshow');
            });
        });

    </script>
@endpush

















