@extends('layouts.main')
@section('content')
    @can('LOI-edit')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-edit');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Update LOI Items</h4>
                <a  class="btn btn-sm btn-info float-end" href="{{ route('letter-of-indents.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

            </div>
            <div class="card-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-2 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Customer</label>
                            <select class="form-control" data-trigger name="customer_id" id="customer" readonly>
                                <option> {{ $letterOfIndent->customer->name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">LOI Category</label>
                            <select class="form-control" name="category" readonly >
                                <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}"
                                    {{$letterOfIndent->category == \App\Models\LetterOfIndent::LOI_CATEGORY_REAL ? 'selected' : " "}}  >
                                    {{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}
                                </option>
                                <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}"
                                    {{$letterOfIndent->category == \App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL ? 'selected' : " "}}>
                                    {{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label ">LOI Date</label>
                            <input type="date" class="form-control" id="basicpill-firstname-input" readonly
                                   value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}" name="date">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Dealer</label>
                            <input type="text" class="form-control" value="{{ $letterOfIndent->dealers }}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Vendor</label>
                            <input type="text" class="form-control" value="{{ $letterOfIndent->supplier->supplier ?? '' }}" readonly>
                        </div>
                    </div>
                </div>
                    @if($letterOfIndentItems->count() > 0)
                        <div class="row d-none d-sm-block">
                            <div class="d-flex">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <label class="form-label">Model</label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label  class="form-label">SFX</label>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-12">
                                            <label class="form-label">LOI Description</label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <label class="form-label">Quantity</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($letterOfIndentItems as $value => $letterOfIndentItem)
                            <div class="d-flex">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3">
                                            <label class="form-label d-block d-sm-none">Model</label>
                                            <input type="text" value="{{ $letterOfIndentItem->masterModel->model }}" readonly class="form-control">
                                        </div>
                                        <div class="col-lg-2 col-md-2">
                                            <label class="form-label d-block d-sm-none">SFX</label>
                                            <input type="text" value="{{ $letterOfIndentItem->masterModel->sfx }}" readonly class="form-control">
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <label class="form-label d-block d-sm-none">LOI Description</label>
                                            <input type="text" value="{{ $letterOfIndentItem->loi_description ?? '' }}" readonly class="form-control">
                                        </div>
                                        <div class="col-lg-2 col-md-2">
                                            <label class="form-label d-block d-sm-none">Quantity</label>
                                            <input type="text" value="{{ $letterOfIndentItem->quantity }}" readonly class="form-control"> </br>
                                        </div>
                                        <div class="col-lg-1 col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm loi-item-button-delete"
                                                    data-id="{{ $letterOfIndentItem->id }}" data-url="{{ route('letter-of-indent-items.destroy', $letterOfIndentItem->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                <form id="form-update" action="{{ route('letter-of-indent-items.store') }}" method="POST" >
                    @csrf
                    <div class="row">
                        <input type="hidden" value="EDIT-PAGE" name="page_name">
                        <div class="d-flex">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row mt-3">
                                    <div class="col-lg-3 col-md-3 mb-3">
                                        <label class="form-label @if($letterOfIndentItems->count() > 0) d-block d-sm-none @endif ">Model</label>
                                        <select class="form-select" name="model" id="model" autofocus onclick="focusOnInput()" >
                                            <option value="" >Select Model</option>
                                            @foreach($models as $model)
                                                <option value="{{ $model->model }}">{{ $model->model }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 mb-3">
                                        <label class="form-label @if($letterOfIndentItems->count() > 0) d-block d-sm-none @endif">SFX</label>
                                        <select class="form-select" name="sfx" id="sfx">
                                            <option value="" >Select SFX</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-4">
                                        <label class="form-label @if($letterOfIndentItems->count() > 0) d-block d-sm-none @endif">LOI Description</label>
                                        <select class="form-select" name="variant" id="variant">
                                            <option value="" >Select LOI Description</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <label class="form-label @if($letterOfIndentItems->count() > 0) d-block d-sm-none @endif">Quantity</label>
                                        <input type="number" name="quantity" placeholder="Quantity" class="form-control" step="1" oninput="validity.valid||(value='');"
                                               min="0">
                                    </div>
                                    <div class="col-lg-1 md-mt-20 col-sm-12">
                                        <label class="form-label d-none d-lg-block d-xl-block d-xxl-block" @if($letterOfIndentItems->count() <= 0) style="margin-top: 30px" @endif >
                                            Inventory Quantity
                                        </label>
                                    </div>
                                    <div class="col-lg-1 col-md-2 md-mt-20 col-sm-12">
                                        <label class="form-label d-lg-none d-xl-none d-xxl-none">Inventory Qty</label>
                                        <input type="number" readonly id="inventory-quantity" value="" class="form-control"
                                               @if($letterOfIndentItems->count() <= 0) style="margin-top: 30px" @endif >
                                    </div>
                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-info"> <span class="fw-bold">Save & Add New </span></button>
                                        <a href="{{ route('letter-of-indents.index') }}"> <button type="button" class="btn btn-primary">Finish</button></a>
                                    </div>
                                    <input type="hidden" value="{{ $letterOfIndent->id }}" name="letter_of_indent_id" id="letter_of_indent_id">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </form>
                <br>
                <div class="col-lg-12 col-md-12">

                </div>
                <div class="row" id="deal-doc-upload-div" hidden>
                    <div class="col-2">
                        <label class="form-label">Choose Document</label>
                    </div>
                    <div class="col-4">
                        <input type="file" name="loi_document_file" class="form-control">
                    </div>
                </div>
            </div>
        @endif
        @endcan
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            $('#model').select2({
                placeholder: 'Select Model'
            }).on('change', function() {
                $(this).valid();
            });
            $('#sfx').select2({
                placeholder: 'Select SFX'
            }).on('change', function() {
                $(this).valid();
            });

            $("#form-update").validate({
                ignore: [],
                rules: {
                    model: {
                        required: true,
                    },
                    sfx: {
                        required: true,
                    },
                    variant: {
                        required: true,
                    },
                    quantity:{
                        required:true
                    }
                },
            });

        $('#model').on('change',function(){
            let model = $(this).val();
            let id = $('#letter_of_indent_id').val();
            let url = '{{ route('demand.get-sfx') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    model: model,
                    module: 'LOI',
                    letter_of_indent_id: id
                },
                success:function (data) {
                    $('#inventory-quantity').val(0);
                    $('select[name="sfx"]').empty();
                    $('select[name="variant"]').empty();
                    $('#sfx').html('<option value=""> Select SFX </option>');
                    $('#variant').html('<option value=""> Select LOI Description </option>');
                    jQuery.each(data, function(key,value){
                        $('select[name="sfx"]').append('<option value="'+ value +'">'+ value +'</option>');
                    });
                }
            });
        });
        $('#sfx').on('change',function(){
            let sfx = $(this).val();
            let model = $('#model').val();
            let url = '{{ route('demand.get-variant') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    sfx: sfx,
                    model:model,
                    module: 'LOI',
                },
                success:function (data) {
                    $('select[name="variant"]').empty();
                    $('#variant').html('<option value=""> Select LOI Description </option>');
                    let quantity = data.quantity;
                    var data = data.variants;
                    $('#inventory-quantity').val(quantity);
                    jQuery.each(data, function(key,value){
                        $('select[name="variant"]').append('<option value="'+ value.id +'">'+ value.name +" - (" + value.master_model.steering +" "
                            + value.master_model_lines.model_line +" " + value.engine  + " " + value.fuel_type +" )" +'</option>');
                    });
                }
            });
        });
        $('.loi-item-button-delete').on('click',function(){
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
                            alertify.success('Item Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });

        })
    </script>
@endpush

