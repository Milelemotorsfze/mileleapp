@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Add New LOI</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

    </div>
    <div class="card-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
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
        <form id="form-create" action="{{ route('letter-of-indents.store') }}" method="POST" >
            @csrf
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 ">Select Country</label>
                        <select class="form-control" data-trigger name="country" id="country">
                            <option disabled>Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{$country}}"> {{ $country }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Customer Type</label>
                        <select class="form-control"name="customer_type" id="customer-type">
                            <option value="" disabled>Select The Type</option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}>{{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}</option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}>{{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}</option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}>{{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}</option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}>{{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13">Customer</label>
                        <select class="form-control text-dark" data-trigger name="customer_id" id="customer" >
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Date</label>
                        <input type="date" class="form-control text-dark" id="basicpill-firstname-input"  name="date">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Category</label>
                        <select class="form-control" name="category" id="choices-single-default">
                            <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}">
                                {{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}
                            </option>
                            <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}">
                                {{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 ">Supplier</label>
                        <select class="form-control" data-trigger name="supplier_id" id="supplier">
                            <option value="" disabled>Select The Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13">Dealers</label>
                        <select class="form-control" data-trigger name="dealers" >
                            <option value="Trans Cars">Trans Cars</option>
                            <option value="Milele Motors">Milele Motors</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13">Shipping Method</label>
                        <select class="form-control" data-trigger name="shipment_method">
                            <option value="CNF">CNF</option>
                            <option value="X work">X work</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="col-lg-12 col-md-12">
                    <button type="submit" class="btn btn-dark btncenter" >Next</button>
                </div>
            </div>
        </form>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        getCustomers();

        $("#form-create").validate({
            ignore: [],
            rules: {
                customer_id: {
                    required: true,
                },
                category: {
                    required: true,
                },
                customer_type: {
                    required: true,
                },
                date: {
                    required: true,
                },
                supplier_id:{
                    required:true
                },
                dealers:{
                    required:true
                },
                shipment_method:{
                    required: true
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    element = $("#select2-" + element.attr("id") + "-container").parent();
                    error.insertAfter(element).addClass('mt-2 mb-0 text-danger');
                }else {
                    error.insertAfter(element).addClass('text-danger');
                }
            }
        });
        $('#country').select2();

        $('#country').change(function (){
           getCustomers();
        });
        $('#customer-type').change(function (){
            getCustomers();
        });
        function getCustomers() {
            var country = $('#country').val();
            var customer_type = $('#customer-type').val();

            let url = '{{ route('letter-of-indents.get-customers') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    country: country,
                    customer_type: customer_type
                },
                success:function (data) {
                    $('#customer').empty();
                    $('#customer').html('<option value="">Select Customer</option>');
                    jQuery.each(data, function(key,value){
                        $('#customer').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        }
    </script>
@endpush

