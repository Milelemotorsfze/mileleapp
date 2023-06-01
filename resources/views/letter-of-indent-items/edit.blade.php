@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Update LOI Items</h4>
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
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13">Customer</label>
                    <select class="form-control" data-trigger name="customer_id" id="customer" readonly>
                        <option> {{ $letterOfIndent->customer->name }}</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Category</label>
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
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Date</label>
                    <input type="date" class="form-control" id="basicpill-firstname-input" readonly
                           value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}" name="date">
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="d-flex">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label">Model</label>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label  class="form-label">SFX</label>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label">Varients</label>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label class="form-label">Qty</label>
                        </div>

                    </div>
                </div>
            </div>

            @if($letterOfIndentItems->count() > 0)
                @foreach($letterOfIndentItems as $value => $letterOfIndentItem)
                    <div class="d-flex">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" value="{{ $letterOfIndentItem->model }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <input type="text" value="{{ $letterOfIndentItem->sfx }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" value="{{ $letterOfIndentItem->variant_name }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <input type="text" value="{{ $letterOfIndentItem->quantity }}" readonly class="form-control"> </br>
                                </div>
                                <div class="col-lg-2 col-md-2">
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
        </div>
        <form id="form-letter-of-indent-items" action="{{ route('letter-of-indent-items.store') }}" method="POST" >
            @csrf
            <div class="row">
                <div class="d-flex">
                    <div class="col-lg-12">
                        <div class="row">
                        </div>
                    </div>
                </div>
                <br>
                <input type="hidden" value="EDIT-PAGE" name="page_name">
                <div class="d-flex">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <select class="form-select" name="model" id="model">
                                    <option value="" >Select Model</option>
                                    @foreach($models as $model)
                                        <option value="{{ $model->model }}">{{ $model->model }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <select class="form-select" name="sfx" id="sfx">
                                    <option value="" >Select SFX</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <select class="form-select" name="variant" id="variant">
                                    <option value="" >Select Variant</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <input type="number" name="quantity" class="form-control" step="1" oninput="validity.valid||(value='');"
                                       min="0">
                            </div>
                            <div class="col-lg-1 col-md-1">
                                <label class="form-label">Inventory Qty</label>
                            </div>
                            <div class="col-lg-1 col-md-1">
                                <input type="number"  readonly id="inventory-quantity" value="" class="form-control">
                            </div>

                            <input type="hidden" value="{{ $letterOfIndent->id }}" name="letter_of_indent_id" id="letter_of_indent_id">
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="col-lg-12 col-md-12">
                <button type="submit" class="btn btn-dark ">Add New Item</button>
            </div>
        </form>
        <br>
        <div class="col-lg-12 col-md-12">
            <a href="{{ route('letter-of-indent-documents.edit', $letterOfIndent->id)}}">
                <button type="button" class="btn btn-dark btnright btn-deal-item-submit">Next</button>
            </a>
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
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
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
                    $('#variant').html('<option value=""> Select Variant </option>');
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
                    $('#variant').html('<option value=""> Select Variant </option>');
                    let quantity = data.quantity;
                    var data = data.variants;
                    $('#inventory-quantity').val(quantity);
                    jQuery.each(data, function(key,value){
                        $('select[name="variant"]').append('<option value="'+ value +'">'+ value +'</option>');
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

