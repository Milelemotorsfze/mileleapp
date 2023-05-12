@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Add New LOI Items</h4>
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
            <div class="row">
                @if($letterOfIndentItems->count() > 0)
                    @foreach($letterOfIndentItems as $value => $letterOfIndentItem)
                        <div class="d-flex">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3">
                                        <input type="text" value="{{ $letterOfIndentItem->model }}" readonly class="form-control">
                                    </div>
                                    <div class="col-lg-3 col-md-3">
                                        <input type="text" value="{{ $letterOfIndentItem->sfx }}" readonly class="form-control">
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <input type="text" value="{{ $letterOfIndentItem->variant_name }}" readonly class="form-control">
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <input type="text" value="{{ $letterOfIndentItem->color }}" readonly class="form-control">
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <input type="text" value="{{ $letterOfIndentItem->quantity }}" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                    <div class="d-flex">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" value="" readonly class="form-control">
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" value="{{ $letterOfIndentItem->sfx }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <input type="text" value="{{ $letterOfIndentItem->variant_name }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <input type="text" value="{{ $letterOfIndentItem->color }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <input type="text" value="{{ $letterOfIndentItem->quantity }}" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </div>
            <div class="col-lg-12 col-md-12">
                <button type="submit" class="btn btn-dark btncenter" >Submit</button>
            </div>
        </div>
    </div>
@endsection

