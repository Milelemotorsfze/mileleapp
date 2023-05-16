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
                        <td>
                            @if($letterOfIndent->letterOfIndentItems())
                                <button type="button" class="btn btn-primary modal-button" data-bs-toggle="modal"
                                        data-modal-id="viewdealinfo-{{ $letterOfIndent->id }}">View </button>
                            @endif
                        </td>
                        <div class="modal modalhide" id="viewdealinfo-{{$letterOfIndent->id}}" >
{{--                            <button src="<?php echo base_url(); ?>/public/icons/cancel.png" class="close">--}}
                            <div class="modal-header bg-primary">
                                <h1 class="modal-title fs-5 text-white text-center" > Deal Items</h1>
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
                                        <div class="col-lg-2 col-md-3">
                                            <label for="basicpill-firstname-input" class="form-label">Varients</label>
                                        </div>
                                        <div class="col-lg-2 col-md-3">
                                            <label for="basicpill-firstname-input" class="form-label">Colour</label>
                                        </div>
                                        <div class="col-lg-2 col-md-3">
                                            <label for="basicpill-firstname-input" class="form-label">Qty</label>
                                        </div>
                                          @foreach($letterOfIndent->letterOfIndentItems()  as $LOIItem)
                                    {{ $LOIItem->model }}
                                        xfjvgdlfk
                                         <div class="d-flex">
                                            <div class="col-lg-12">
                                                <div class= "row">
                                                    <div class="col-lg-3 col-md-3">
                                                        <input type="text" class="form-control mb-1" name="model"  readonly="true">
                                                    </div>
                                                    <div class="col-lg-3 col-md-3">
                                                        <input type="text" class="form-control mb-1" name="sfx"  readonly="true">
                                                    </div>
                                                    <div class="col-lg-2 col-md-3">
                                                        <input type="text" class="form-control mb-1" name="varient"readonly="true">
                                                    </div>
                                                    <div class="col-lg-2 col-md-3">
                                                        <input type="text" class="form-control mb-1" name="color"  readonly="true">
                                                    </div>
                                                    <div class="col-lg-2 col-md-3">
                                                        <input type="text" class="form-control mb-1" name="quantity"  readonly="true">
                                                    </div>
                                                </div>
                                            </div>
                                         </div>

                                          @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <td>
                            @if($letterOfIndent->LOIDocuments())
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">View </button>
                            @endif
                        </td>
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
                var modalId = $(this).data('modal-id');
                $('#viewdealinfo-' + modalId).addClass('modalshow');
                $('#viewdealinfo-' + modalId).removeClass('modalhide');
                console.log('Modal Show');
            });
            $('.close').on('click', function(){
                $('.modal').addClass('modalhide');
                $('.modal').removeClass('modalshow');
                // $('.modal').hide();
                console.log('Modal Hidden from close button');
            });
        });
        function closemodal()
        {
            $('.modal').addClass('modalhide');
            console.log('Modal Hidden from Body');
        }
    </script>
@endpush

















