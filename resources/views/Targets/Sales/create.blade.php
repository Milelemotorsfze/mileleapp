@extends('layouts.main')
@section('content')
<style>
        .hidden {
            display: none;
        }
    </style>
<div class="card-header">
    <h4 class="card-title">Create New Sales Target</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('salestargets.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="row">
        <form action="{{ route('salestargets.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <label for="addon-name-input" class="form-label">Sales Person</label>
                    <select class="form-control" name="sales_person_id" id="sales_person_id">
                    @foreach ($sales_persons as $sales_person)
                        <option value="{{ $sales_person->user->id }}" data-id="{{ $sales_person->model_id }}">
                            {{ $sales_person->user->name }}
                        </option>
                    @endforeach
                </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="target-input" class="form-label">Target Month</label>
                    <input type="month" id="start" name="month" class="form-control" min="" value="" />
                </div>
                </div>
                <hr>
                <br>
            <h4 class="card-title">Lead Time</h4>
            <div class="containerleads">
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <label for="addon-name-input" class="form-label">Lead From</label>
            <select name="leadfrom[]" class="form-control" id="leadfrom">
                <option value="" disabled selected>Select Category</option>
                <option value="Pending">Pending</option>
                <option value="Prospecting">Prospecting</option>
                <option value="Demands">Demands</option>
                <option value="Quotation">Quotation</option>
                <option value="Negotiation">Negotiation</option>
            </select>
        </div>
        <div class="col-lg-4 col-md-6">
            <label for="category-input" class="form-label">Lead To</label>
            <select name="leadto[]" class="form-control" id="leadto">
                <option value="" disabled selected>Select Category</option>
                <option value="Prospecting">Prospecting</option>
                <option value="Demands">Demands</option>
                <option value="Quotation">Quotation</option>
                <option value="Negotiation">Negotiation</option>
                <option value="Sales Order">Sales Order</option>
            </select>
        </div>
        <div class="col-lg-4 col-md-6" id="price-container">
            <label for="price-input" class="form-label">Number of Days</label>
            <input type="number" name="leadsdays[]" class="form-control" id="leadsdays">
        </div>
    </div>
</div>
<br>
<div class="btn btn-primary" id="addMore" data-row="1">
         <i class="fas fa-plus"></i> Add Vehicles
        </div>
        <hr>
        <div class="row">
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Self / Walking Leads</label>
            <input type = "Number" class="form-control" name="walkingleads"/>
        </div>
        <div class="col-lg-2 col-md-6 mt-3">
    <label for="addon-name-input" class="form-label">Market Target Idea</label>
    <div class="mt-2">
        <label class="form-check-label"><input type="radio" name="marketTarget" value="yes" class="form-check-input" checked> Yes</label>
        <label class="form-check-label"><input type="radio" name="marketTarget" value="no" class="form-check-input"> No</label>
    </div>
</div>
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Product Base Marketing</label>
            <div class="mt-2">
        <label class="form-check-label"><input type="radio" name="productbasemarketing" value="yes" class="form-check-input" checked> Yes</label>
        <label class="form-check-label"><input type="radio" name="productbasemarketing" value="no" class="form-check-input"> No</label>
    </div>
        </div>
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Export Sale</label>
            <input type = "Number" class="form-control" name="exportsale"/>
        </div>
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Local Sale</label>
            <input type = "Number" class="form-control" name="localsale"/>
        </div>
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Lease to Own</label>
            <input type = "Number" class="form-control" name="lease"/>
        </div>
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Google Review</label>
            <input type = "Number" class="form-control" name="googlereview"/>
        </div>
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Serive Kits</label>
            <input type = "Number" class="form-control" name="kits"/>
        </div>
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Shipping (In AED)</label>
            <input type = "Number" class="form-control" name="shipping"/>
        </div>
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Spare Parts (In AED)</label>
            <input type = "Number" class="form-control" name="spareparts"/>
        </div>
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Accessiores (In AED)</label>
            <input type = "Number" class="form-control" name="accessiores"/>
        </div>
        <div class="col-lg-2 col-md-6 mt-3">
            <label for="addon-name-input" class="form-label">Unique Customers Sales Orders</label>
            <input type = "Number" class="form-control" name="uniquecustomers"/>
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
    $('#sales_person_id').select2({
            placeholder: 'Select Sales Person'
        })
</script>
<script>
    $(document).ready(function(){
        $("#addMore").click(function(){
            var newRow = `<div class="row mt-3">
                    <div class="col-lg-4 col-md-6">
                        <select name="leadfrom[]" class="form-control" id="leadfrom">
                            <option value="" disabled selected>Select Category</option>
                            <option value="Pending">Pending</option>
                            <option value="Prospecting">Prospecting</option>
                            <option value="Demands">Demands</option>
                            <option value="Quotation">Quotation</option>
                            <option value="Negotiation">Negotiation</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <select name="leadto[]" class="form-control" id="leadto">
                            <option value="" disabled selected>Select Category</option>
                            <option value="Pending">Pending</option>
                            <option value="Prospecting">Prospecting</option>
                            <option value="Demands">Demands</option>
                            <option value="Quotation">Quotation</option>
                            <option value="Negotiation">Negotiation</option>
                            <option value="Sales Order">Sales Order</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6" id="price-container">
                        <input type="number" name="leadsdays[]" class="form-control" id="leadsdays">
                    </div>
                </div>
            `;
            $(".containerleads").append(newRow);
        });
    });
</script>
<script>
  var currentDate = new Date();
  var formattedDate = currentDate.toISOString().slice(0, 7);
  document.getElementById("start").min = formattedDate;
  document.getElementById("start").value = formattedDate;
</script>
@endpush