@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Approve LOI</h4>
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
                    <label for="choices-single-default" class="form-label font-size-13 ">Select Country</label>
                    <select class="form-control" data-trigger name="country" readonly id="country">
                       <option> {{ $letterOfIndent->customer->country ?? '' }} </option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 text-muted">Customer Type</label>
                    <select class="form-control"name="customer_type" readonly>
                        <option>{{  $letterOfIndent->customer->type ?? '' }}</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13">Customer</label>
                    <select class="form-control" data-trigger name="customer_id" readonly >
                        <option>{{ $letterOfIndent->customer->name ?? '' }}</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 ">Supplier</label>
                    <select class="form-control" data-trigger name="supplier_id" readonly>
                            <option>{{ $letterOfIndent->supplier->supplier ?? '' }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Category</label>
                    <select class="form-control" name="category"  readonly>
                        <option>{{ $letterOfIndent->category }}</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Date</label>
                    <input type="date" class="form-control" id="basicpill-firstname-input" name="date" readonly
                           value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13">Dealers</label>
                    <select class="form-control" name="dealers" readonly >
                        <option>{{ $letterOfIndent->dealers }}</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13">Shipping Method</label>
                    <select class="form-control" name="shipment_method" readonly>
                        <option> {{ $letterOfIndent->shipment_method }}</option>
                    </select>
                </div>
            </div>
            <br>
        </div>
        <div class="row">
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
                            <label class="form-label">Varient</label>
                        </div>
                        <div class="col-lg-1 col-md-1">
                            <label class="form-label">LOI Qty</label>
                        </div>
                        <div class="col-lg-1 col-md-1">
                            <label class="form-label">Balance Qty</label>
                        </div>
                        <div class="col-lg-1 col-md-1">
                            <label class="form-label">Inventory Qty</label>
                        </div>
                        <div class="col-lg-1 col-md-1">
                            <label class="form-label">PFI Qty</label>
                        </div>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('approve-loi-items', ['id' => $letterOfIndent->id] ) }}"  >
                @csrf
                @method('POST')
                @if($letterOfIndentItems->count() > 0)
                @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
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
                                <div class="col-lg-1 col-md-1">
                                    <input type="text" value="{{ $letterOfIndentItem->quantity }}"
                                           readonly class="form-control">
                                </div>
                                <div class="col-lg-1 col-md-1">
                                    <input type="text" value="{{ $letterOfIndentItem->balance_quantity }}" id="balance-qty-{{$key}}"
                                           readonly class="form-control">
                                </div>
                                <div class="col-lg-1 col-md-1">
                                    <input type="text" value="{{ $letterOfIndentItem->inventory_quantity }}" id="inventory-qty-{{$key}}"
                                           readonly class="form-control">
                                </div>
                                <div class="col-lg-1 col-md-1">
                                    <?php
                                        if($letterOfIndentItem->inventory_quantity <= $letterOfIndentItem->quantity) {
                                            if($letterOfIndentItem->approved_quantity > 0) {
                                                $count = $letterOfIndentItem->balance_quantity;
                                            }else{
                                                $count = $letterOfIndentItem->inventory_quantity;

                                            }
                                        }else{

                                            $count = $letterOfIndentItem->quantity;
                                        }
//                                        if($letterOfIndentItem->approved_quantity >= 1) {
//                                            $count = $count - $letterOfIndentItem->approved_quantity;
//                                            if($count < 0) {
//                                                $count = 0;
//                                            }
//                                        }
                                        ?>
                                    <select name="quantities[]" class="form-control approve-quantity" id="quantity-{{$key}}" data-key="{{$key}}"
                                    data-balance-qty="{{$letterOfIndentItem->balance_quantity}}" data-inventory-qty="{{ $letterOfIndentItem->inventory_quantity }}">
                                        @for($i=0;$i <= $count;$i++)

                                            <option> {{ $i }} </option>
                                        @endfor
                                    </select> </br>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                    <div class="col-lg-12 col-md-12">
                        <button class="btn btn-secondary btncenter" type="submit">Approve</button>
                    </div>
            @endif
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
    $('.approve-quantity').change(function () {
       let key = $(this).attr('data-key');
       let inventoryQuantity = $(this).attr('data-inventory-qty');
       let balanceQuantity = $(this).attr('data-balance-qty');
       let quantity = $('#quantity-'+key).val();
       let updatedBalanceQty = balanceQuantity - quantity;
       let updatedInventoryQty = inventoryQuantity - quantity;
        $('#inventory-qty-'+key).val(updatedInventoryQty);
        $('#balance-qty-'+key).val(updatedBalanceQty);
    })
    </script>
@endpush


