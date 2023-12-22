@extends('layouts.main')
@section('content')
    @can('LOI-approve')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-approve');
        @endphp
        @if ($hasPermission)
        <div class="card-header">
            <h4 class="card-title">Approve LOI</h4>
            <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                <div class="col-sm-4 mt-3">
                    <div class="row mt-2">
                        <div class="col-sm-6 col-md-6 col-lg-3 fw-bold">
                            Customer :
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            {{ $letterOfIndent->customer->name ?? '' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-3 fw-bold">
                            Dealers :
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            {{ $letterOfIndent->dealers }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-6 col-lg-3 fw-bold">
                            So Number :
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            {{ $letterOfIndent->so_number }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 mt-3">
                    <div class="row mt-2">
                        <div class="col-sm-6 col-md-6 col-lg-4 fw-bold">
                            Perefered Location :
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            {{ $letterOfIndent->prefered_location }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-4 fw-bold">
                            LOI Category :
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            {{ $letterOfIndent->category }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 mt-3">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-3 fw-bold">
                            LOI Date :
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            {{ Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-3 fw-bold">
                            Destination :
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            {{ $letterOfIndent->destination }}
                        </div>
                    </div>
                </div>
            </div>
                <hr>
                <div class="row d-none d-lg-block d-xl-block d-xxl-block">
                    <div class="d-flex">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label fw-bold">Model</label>
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <label  class="form-label fw-bold">SFX</label>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <label class="form-label fw-bold">LOI Description</label>
                                </div>
                                <div class="col-lg-1 col-md-3">
                                    <label class="form-label fw-bold">LOI Qty</label>
                                </div>
                                <div class="col-lg-1 col-md-3">
                                    <label class="form-label fw-bold">Balance Qty</label>
                                </div>
                                <div class="col-lg-1 col-md-3">
                                    <label class="form-label fw-bold">Inventory Qty</label>
                                </div>
                                <div class="col-lg-1 col-md-3">
                                    <label class="form-label fw-bold">PFI Qty</label>
                                </div>
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
                                    <div class="col-lg-3 col-md-4">
                                        <label class="form-label d-lg-none d-xl-none fw-bold">Model</label>
                                        <input type="text" value="{{ $letterOfIndentItem->masterModel->model ?? '' }}" readonly class="form-control">
                                    </div>
                                    <div class="col-lg-1 col-md-4">
                                        <label class="form-label d-lg-none d-xl-none fw-bold">SFX</label>
                                        <input type="text" value="{{ $letterOfIndentItem->masterModel->sfx ?? ''}}" readonly class="form-control">
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <label class="form-label d-lg-none d-xl-none fw-bold">LOI Description</label>
                                        <input type="text" value="{{ $letterOfIndentItem->loi_description ?? '' }}" readonly class="form-control">
                                    </div>
                                    <div class="col-lg-1 col-md-3">
                                        <label class="form-label d-lg-none d-xl-none fw-bold">LOI Qty</label>
                                        <input type="text" value="{{ $letterOfIndentItem->quantity }}"
                                               readonly class="form-control">
                                    </div>
                                    <div class="col-lg-1 col-md-3">
                                        <label class="form-label d-lg-none d-xl-none fw-bold">Balance Qty</label>
                                        <input type="text" value="{{ $letterOfIndentItem->balance_quantity }}" id="balance-qty-{{$key}}"
                                               readonly class="form-control">
                                    </div>
                                    <div class="col-lg-1 col-md-3">
                                        <label class="form-label d-lg-none d-xl-none fw-bold">Inventory Qty</label>
                                        <input type="text" value="{{ $letterOfIndentItem->inventory_quantity }}" id="inventory-qty-{{$key}}"
                                               readonly class="form-control">
                                    </div>
                                    <div class="col-lg-1 col-md-3">
    {{--                                    Show LOI QTY as PFI QTY when inventory qty is > loi qty--}}
                                        <?php
                                            if($letterOfIndentItem->balance_quantity < $letterOfIndentItem->quantity) {
                                                $count = $letterOfIndentItem->balance_quantity;

                                            }else{

                                                $count = $letterOfIndentItem->quantity;
                                            }
//                                            $count = $letterOfIndentItem->balance_quantity;
                                            ?>
{{--                                        {{ $count }}--}}

                                        <label class="form-label d-lg-none d-xl-none fw-bold">PFI Qty</label>
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
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Approve</button>
                    </div>
                @endif
                </form>
            </div>
        </div>
        @endif
    @endcan
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


