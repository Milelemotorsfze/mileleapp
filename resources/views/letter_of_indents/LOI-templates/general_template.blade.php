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
    </style>

    <div class="container justify-content-center" style="padding-bottom: 0px;">
        <form action="{{ route('letter-of-indents.generate-loi') }}" id="form-create">
            <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
            <input type="hidden" name="type" value="general">
            <input type="hidden" name="download" value="1">

            <div class="row mb-4 mt-4 mb-4">
                    @if($isCustomerPassport)
                        <div class="col-md-3 col-lg-3">
                            <label>Passport</label>
                            <select class="form-control widthinput" name="passport_order" id="passport-order" >
                                <option value="1" {{ $isCustomerPassport->order == '1' ? 'selected' : ""}}>Order 1</option>
                                <option value="2" {{ $isCustomerPassport->order == '2' ? 'selected' : ""}}>Order 2</option>
                                <option value="3" {{ $isCustomerPassport->order == '3' ? 'selected' : ""}}>Order 3</option>
                            </select>
                        </div>
                    @endif
                    @if($isCustomerTradeLicense)
                        <div class="col-md-3 col-lg-3">
                            <label>Trade License</label>
                            <select class="form-control widthinput" name="trade_license_order" id="trade-license-order" >
                                <option value="1" {{ $isCustomerTradeLicense->order == '1' ? 'selected' : ""}}>Order 1</option>
                                <option value="2" {{ $isCustomerTradeLicense->order == '2' ? 'selected' : ""}}>Order 2</option>
                                <option value="3" {{ $isCustomerTradeLicense->order == '3' ? 'selected' : ""}}>Order 3</option>
                            </select>
                        </div>
                    @endif
                    @if($customerOtherDocAdded->count() > 0)
                        <div class="col-md-3 col-lg-3">
                            <label>Other Document</label>
                            <select class="form-control widthinput" name="other_document_order" id="other-document-order" >
                                <option value="1" {{ $customerOtherDocAdded[0]->order == '1' ? 'selected' : ""}}>Order 1</option>
                                <option value="2" {{ $customerOtherDocAdded[0]->order == '2' ? 'selected' : ""}} >Order 2</option>
                                <option value="3" {{ $customerOtherDocAdded[0]->order == '3' ? 'selected' : ""}}>Order 3</option>
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3 col-lg-3 mt-4">
                        <a  class="btn  btn-info float-end " style="margin-left: 2px;" href="{{ route('letter-of-indents.index', ['tab' => 'NEW']) }}" >
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                        <button type="submit" class="btn btn-primary" id="submit-button"> Download <i class="fa fa-download"></i></button>
                        </button>
                    </div>
                </div>
        </form>
        <dv class="card-body text-center">
            @if($letterOfIndent->LOIDocuments->count() > 0)
                <h5 class="fw-bold ">Customer Document</h5>
                @if($isCustomerPassport)
                    <iframe src="{{ url('storage/app/public/passports/'.$isCustomerPassport->loi_document_file) }}"   height="500px;"></iframe>
                @endif
                @if($isCustomerTradeLicense)
                    <iframe src="{{ url('storage/app/public/tradelicenses/'.$isCustomerTradeLicense->loi_document_file) }}"  height="500px;"></iframe>
                @endif
                @foreach($customerOtherDocAdded as $letterOfIndentDocument)
                    <div class="mt-3" >
                    <iframe src="{{ url('customer-other-documents/'.$letterOfIndentDocument->loi_document_file) }}"   height="500px;"></iframe>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <script type="text/javascript">
        $('#submit-button').click(function (e) {
            e.preventDefault();

            let val1 = $('#passport-order').val();
            let val2 = $('#trade-license-order').val();
            let val3 = $('#other-document-order').val();
            // check the form
            if (val1 === val2 || val1 === val3 || val2 === val3) {
                alertify.error('You are not allowed to choose same orders');
                e.preventDefault();
            }else{
                $('#form-create').unbind('submit').submit();
            }
        
        });
    </script>
@endsection


