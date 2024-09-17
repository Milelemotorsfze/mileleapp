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
        <form action="{{ route('letter-of-indents.generate-loi') }}">
            <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
            <input type="hidden" name="type" value="general">
            <input type="hidden" name="download" value="1">

            <div class="text-end mb-3 mt-3">
                <a  class="btn  btn-info float-end " style="margin-left: 10px;" href="{{ route('letter-of-indents.index', ['tab' => 'NEW']) }}" >
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                <button type="submit" class="btn btn-primary "> Download <i class="fa fa-download"></i></button>
                </button>
            </div>
        </form>
        <dv class="card-body text-center">
            @if($letterOfIndent->LOIDocuments->count() > 0)
                    <h5 class="fw-bold ">Customer Document</h5>
                @foreach($letterOfIndent->LOIDocuments as $key => $letterOfIndentDocument)
                    <div class="text-center" id="remove-doc-{{$letterOfIndentDocument->id}}">
                        @if($letterOfIndentDocument->is_passport)
                            <iframe src="{{ url('storage/app/public/passports/'.$letterOfIndentDocument->loi_document_file) }}" height="500px;"></iframe>
                        @elseif($letterOfIndentDocument->is_trade_license)
                            <iframe src="{{ url('storage/app/public/tradelicenses/'.$letterOfIndentDocument->loi_document_file) }}" height="500px;"></iframe>
                        @else
                            <iframe src="{{ url('customer-other-documents/'.$letterOfIndentDocument->loi_document_file) }}" height="500px;"></iframe>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>

@endsection


