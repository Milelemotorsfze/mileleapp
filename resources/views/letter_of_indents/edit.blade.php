@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Edit LOI</h4>
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
        <form action="{{ route('letter-of-indents.update', $letterOfIndent->id) }}" method="POST" >
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 ">Select Country</label>
                        <select class="form-control" data-trigger name="country" id="country">
                            <option disabled>Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{$country}}" {{ $country == $letterOfIndent->customer->country ? 'selected' : '' }} > {{ $country }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Customer Type</label>
                        <select class="form-control"name="customer_type" id="customer-type">
                            <option value="" disabled>Select The Type</option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}
                                {{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL == $letterOfIndent->customer->type ? 'selected' : ''}}>
                                {{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}
                            </option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}
                                {{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY == $letterOfIndent->customer->type ? 'selected' : ''}}>
                                {{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}
                            </option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}
                                {{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT == $letterOfIndent->customer->type ? 'selected' : ''}} >
                                {{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}
                            </option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}
                                {{ \App\Models\Customer::CUSTOMER_TYPE_NGO == $letterOfIndent->customer->type ? 'selected' : ''}} >
                                {{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13">Customer</label>
                        <select class="form-control" data-trigger name="customer_id" id="customer" >
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Category</label>
                        <select class="form-control" name="category" id="choices-single-default">
                            <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}"
                                {{ \App\Models\LetterOfIndent::LOI_CATEGORY_REAL == $letterOfIndent->category ? 'selected' : ''}} >
                                {{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}
                            </option>
                            <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}"
                                {{ \App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL == $letterOfIndent->category ? 'selected' : ''}} >
                                {{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Date</label>
                        <input type="date" class="form-control" id="basicpill-firstname-input" name="date"
                               value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13">Dealers</label>
                        <select class="form-control" data-trigger name="dealers" >
                            <option value="Trans Cars" {{ 'Trans Cars' == $letterOfIndent->dealers ? 'selected' : '' }}>Trans Cars</option>
                            <option value="Milele Motors" {{ 'Milele Motors' == $letterOfIndent->dealers ? 'selected' : '' }}>Milele Motors</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13">Shipping Mwthod</label>
                        <select class="form-control" data-trigger name="shipment_method">
                            <option value="CNF" {{ 'CNF' == $letterOfIndent->shipment_method ? 'selected' : '' }}>CNF</option>
                            <option value="X work" {{ 'X work' == $letterOfIndent->shipment_method ? 'selected' : '' }}>X work</option>
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
@endsection
@push('scripts')
    <script>
        // $(document).ready(function() {
            getCustomers();
            $('#country').change(function () {
                getCustomers();
            });
            $('#customer-type').change(function () {
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
                    success: function (data) {
                        $('#customer').empty();
                        // $('#customer').html('<option value=""></option>');
                        jQuery.each(data, function (key, value) {
                            console.log(data);
                            var selectedId = '{{ $letterOfIndent->customer_id }}'
                            if(value.id == selectedId) {
                                // $('#customer').append('<option value="' + selectedId + ' " >' + value.name + '</option>');
                            }
                            $('#customer').append('<option value="' + value.id + ' " >' + value.name + '</option>');
                        });
                    }
                });
            }
        // })
    </script>
@endpush

