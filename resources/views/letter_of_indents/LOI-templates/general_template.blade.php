@extends('layouts.main')
@section('content')

   <style>
     @page { size: 700pt }
     @media only screen and (min-device-width: 1200px)
        {
            .container{
                max-width: 850px; !important;
            }
        }
        iframe{
            height: 400px;
        }
         .border-outline {
            border: 1px solid #0f0f0f;
            padding: 10px !important;
        }
    </style>
    @can('LOI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">LOI Template</h4>
                <a  class="btn btn-sm btn-info float-end" href="{{ route('letter-of-indents.index', ['tab' => 'NEW']) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            </div>
            <div class="card-body">
                <div class="container justify-content-center" style="padding-bottom: 0px;">
                    <form action="{{ route('letter-of-indents.generate-loi') }}" id="form-create">
                        <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
                        <input type="hidden" name="type" value="general">
                        <input type="hidden" name="download" value="1">

                        <div class="row justify-content-center">
                            @if($isCustomerPassport)
                                <div class="col-md-6 col-lg-3  text-center">
                                    <label>Passport</label>
                                    <select class="form-control widthinput validate-input" name="passport_order">
                                        <option value="1" {{ $isCustomerPassport->order == '1' ? 'selected' : ""}}>Order 1</option>
                                        <option value="2" {{ $isCustomerPassport->order == '2' ? 'selected' : ""}}>Order 2</option>
                                        <option value="3" {{ $isCustomerPassport->order == '3' ? 'selected' : ""}}>Order 3</option>
                                    </select>
                                </div>
                            @endif
                            @if($isCustomerTradeLicense)
                                <div class="col-md-6 col-lg-3  text-center">
                                    <label>Trade License</label>
                                    <select class="form-control widthinput validate-input" name="trade_license_order" >
                                        <option value="1" {{ $isCustomerTradeLicense->order == '1' ? 'selected' : ""}}>Order 1</option>
                                        <option value="2" {{ $isCustomerTradeLicense->order == '2' ? 'selected' : ""}}>Order 2</option>
                                        <option value="3" {{ $isCustomerTradeLicense->order == '3' ? 'selected' : ""}}>Order 3</option>
                                    </select>
                                </div>
                            @endif
                            @if($customerOtherDocAdded->count() > 0)
                                <div class="col-md-6 col-lg-3  text-center">
                                    <label>Other Document</label>
                                    <select class="form-control widthinput validate-input" name="other_document_order" >
                                        <option value="1" {{ $customerOtherDocAdded[0]->order == '1' ? 'selected' : ""}}>Order 1</option>
                                        <option value="2" {{ $customerOtherDocAdded[0]->order == '2' ? 'selected' : ""}} >Order 2</option>
                                        <option value="3" {{ $customerOtherDocAdded[0]->order == '3' ? 'selected' : ""}}>Order 3</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="row justify-content-center mt-2">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary" id="submit-button"> Download <i class="fa fa-download"></i></button>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="card-body text-center">
                        @if($letterOfIndent->LOIDocuments->count() > 0)
                            <h5 class="fw-bold ">Customer Document</h5>
                            @if($isCustomerPassport)
                                <iframe src="{{ url('storage/app/public/passports/'.$isCustomerPassport->loi_document_file) }}" ></iframe>
                            @endif
                            @if($isCustomerTradeLicense)
                                <iframe src="{{ url('storage/app/public/tradelicenses/'.$isCustomerTradeLicense->loi_document_file) }}" ></iframe>
                            @endif
                            @foreach($customerOtherDocAdded as $letterOfIndentDocument)
                                <div class="mt-3" >
                                <iframe src="{{ url('customer-other-documents/'.$letterOfIndentDocument->loi_document_file) }}" ></iframe>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endcan
    <script type="text/javascript">
         
         function isArrayUnique(arr) {
            return new Set(arr).size === arr.length;
        }
        $('#form-create').on('submit', function(e) {
            e.preventDefault(); // Prevent form submission

            let values = [];
            $('.validate-input').each(function() {
            let value = $(this).val();
           
            if (!value) {
                alertify.confirm("All fields must be filled.",function (e) {
                }).set({title:"Error"});
            }else{
                values.push(value);
            }
        });
        if (isArrayUnique(values)) {
            this.submit();
        } else {
            alertify.confirm( "Each field must have a unique Order",function (e) {
            }).set({title:"Error"});
        }
    });      

    </script>
@endsection


