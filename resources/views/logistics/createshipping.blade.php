@extends('layouts.main')
@section('content')
<style>
        /* Add any additional styling here */
        .hidden {
            display: none;
        }
    </style>
<div class="card-header">
    <h4 class="card-title">Create Shipping & Documents</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="row">
        <form action="{{ route('Shipping.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <label for="addon-name-input" class="form-label">Addon Name</label>
                    <input type="text" name="addon_name" class="form-control" id="addon-name-input">
                </div>
                            <div class="col-lg-4 col-md-6">
                    <label for="category-input" class="form-label">Category</label>
                    <select name="category" class="form-control" id="category-input">
                    <option value="" disabled selected>Select Category</option>
                        <option value="Shipping">Shipping</option>
                        <option value="Shipping Documents">Shipping Documents</option>
                        <option value="Certificates">Certificates</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6" id="price-container">
                    <label for="price-input" class="form-label">Price</label>
                    <input type="text" name="price" class="form-control" id="price-input">
                </div>
                <div class="col-lg-8 col-md-6">
                    <label for="destruction-input" class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" id="description-input">
                </div>
            </div>
            <br><br>
            <div class="col-lg-12 col-md-12">
                <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter">
            </div>
        </form>
    </div>
    <br>
</div>
@endsection
@push('scripts')
<script>
    document.getElementById('category-input').addEventListener('change', function() {
        var categoryInput = document.getElementById('category-input');
        var priceContainer = document.getElementById('price-container');

        if (categoryInput.value === 'Shipping') {
            priceContainer.classList.add('hidden');
        } else {
            priceContainer.classList.remove('hidden');
        }
    });
</script>
@endpush