@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create New PFI</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

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
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13">Customer</label>
                    <select class="form-control" data-trigger name="customer_id" id="customer" readonly>
                        <option> {{ $letterOfIndent->customer->name }}</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
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
            <div class="col-lg-2 col-md-2">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Date</label>
                    <input type="date" class="form-control" id="basicpill-firstname-input" readonly
                           value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}" name="date">
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13">Dealers</label>
                    <input type="text" class="form-control" value="{{ $letterOfIndent->dealers }}" readonly>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13">Shipping Method</label>
                    <input type="text" class="form-control" value="{{ $letterOfIndent->shipment_method }}" readonly>
                </div>
            </div>
            <div class="col-lg-1 col-md-1">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 ">Supplier</label>
                    <input type="text" class="form-control" value="{{ $letterOfIndent->supplier->supplier ?? '' }}" readonly>
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

{{--            @if($letterOfIndentItems->count() > 0)--}}
{{--                @foreach($letterOfIndentItems as $value => $letterOfIndentItem)--}}
{{--                    <div class="d-flex">--}}
{{--                        <div class="col-lg-12">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-lg-3 col-md-3">--}}
{{--                                    <input type="text" value="{{ $letterOfIndentItem->model }}" readonly class="form-control">--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-2 col-md-2">--}}
{{--                                    <input type="text" value="{{ $letterOfIndentItem->sfx }}" readonly class="form-control">--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-3 col-md-3">--}}
{{--                                    <input type="text" value="{{ $letterOfIndentItem->variant_name }}" readonly class="form-control">--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-2 col-md-2">--}}
{{--                                    <input type="text" value="{{ $letterOfIndentItem->quantity }}" readonly class="form-control"> </br>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-2 col-md-2">--}}
{{--                                    <button type="button" class="btn btn-danger btn-sm loi-item-button-delete"--}}
{{--                                            data-id="{{ $letterOfIndentItem->id }}" data-url="{{ route('letter-of-indent-items.destroy', $letterOfIndentItem->id) }}">--}}
{{--                                        <i class="fa fa-trash"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            @endif--}}
        </div>

            <div class="row">
                <div class="d-flex">
                    <div class="col-lg-12">
                        <div class="row">
                        </div>
                    </div>
                </div>
                <br>
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
                                       min="0" >
                            </div>
                            <div class="col-lg-1 col-md-1">
                                <label class="form-label">Inventory Qty</label>
                            </div>
                            <div class="col-lg-1 col-md-1">
                                <input type="number"  readonly id="inventory-quantity"
                                       value="" class="form-control">
                            </div>

                            <input type="hidden" value="{{ request()->id }}" name="letter_of_indent_id" id="letter_of_indent_id">
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="col-lg-12 col-md-12">
                <button type="button" class="btn btn-dark ">Add New Item</button>
            </div>
        <br>
        <div class="col-lg-12 col-md-12">
            <a href="{{ route('letter-of-indent-documents.create',['letter_of_indent_id' => request()->id ])}}">
                <button type="button" class="btn btn-dark btnright btn-deal-item-submit">Finish</button>
            </a>
        </div>
    </div>
@endsection


