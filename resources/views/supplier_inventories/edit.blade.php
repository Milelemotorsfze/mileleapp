@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Add / Update Supplier Inventory Record</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

    </div>
    <div class="card-body">
            @if (Session::has('error'))
                <div class="alert alert-danger" >
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert"></button>
                    {{ Session::get('error') }}
                </div>
            @endif
            @if (Session::has('message'))
                <div class="alert alert-success" id="success-alert">
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert"> </button>
                    {{ Session::get('message') }}
                </div>
            @endif
        <form id="form-update" action="{{ route('supplier-inventories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted"> Supplier</label>
                        <select class="form-control" autofocus name="supplier_id" id="supplier">
                            <option value="" disabled>Select The Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted"> Dealers</label>
                        <select class="form-control" data-trigger name="whole_sales" id="wholesaler">
                            <option value="{{ \App\Models\SupplierInventory::DEALER_TRANS_CARS }}">Trans Cars</option>
                            <option value="{{\App\Models\SupplierInventory::DEALER_MILELE_MOTORS}}">Milele Motors</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted"> Country</label>
                        <select class="form-control" data-trigger name="country" id="choices-single-default">
                            <option value='UAE'>UAE</option>
                            <option value='Belguim'>Belguim</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="mb-3">
                    <label for="choices-single-default" class="form-label text-muted">Choose File</label>
                        <input type="file" name="file" class="form-control text-dark" >
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <label class="form-label"></label>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_add_new" id="is_add_new" {{ old('is_add_new') ? 'checked' : '' }} />
                        <label class="form-check-label" for="remember-check">
                            Is Adding New Supplier List ?
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary "> Upload </button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        $("#form-update").validate({
            ignore: [],
            rules: {
                file: {
                    required: true,
                    extension: "csv|xlsx|xls"
                }
            },
            messages: {
                file: {
                    extension: "Please upload valid excel file(eg: csv,xlsx,xls..)"
                }
            }
        });
    </script>
@endpush

