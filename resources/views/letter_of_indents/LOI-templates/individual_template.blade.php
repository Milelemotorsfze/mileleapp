@extends('layouts.main')
@section('content')

    <style>
        @page { size: 700pt }
        .content{
            font-family: arial, sans-serif;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
        table ,td,th{
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            padding:10px;
            border: 1px solid #1c1b1b;
            /*background-color: #0a58ca;*/

        }

        {{--.overlay-image {--}}
        {{--    position: absolute;--}}
        {{--    top: {{ $height }}px;--}}
        {{--    left: {{ $width }}px;--}}
        {{--    z-index: 1;--}}
        {{--}--}}

        .last{
            text-align: end;
            margin-left: 20px;
        }
        .fw-bold{
            font-weight: bold;
        }
        @media only screen and (min-device-width: 1200px)
        {
            .container{
                max-width: 1000px; !important;
            }
        }
    </style>

    <div class="container" style="padding-bottom: 0px;">
        <div class="content" style="padding-right: 0px;padding-left: 0px;margin-top: 10px">
            <form action="{{ route('letter-of-indents.generate-loi') }}">
                <input type="hidden" name="height" id="total-height" value="">
                <input type="hidden" name="width" id="width" value="">
                <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
                <input type="hidden" name="type" value="INDIVIDUAL">
                <input type="hidden" name="download" value="1">

                <div class="text-end mb-3">
                    <a  class="btn  btn-info float-end " style="margin-left: 10px;" href="{{ url()->previous() }}" >
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    <button type="submit" class="btn btn-primary "> Download <i class="fa fa-download"></i></button>
                    </button>
                </div>
            </form>

            <p class="last">Date:{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}} </p>
            <p> <span class="fw-bold">Subject: </span> Letter of Intent to Purchase Vehicle</p>
            <p style="margin-bottom: 0px;"> <span class="fw-bold">Full Name: </span> {{ ucfirst($letterOfIndent->customer->name ?? '') }} </p>
            <p> <span class="fw-bold">Address: </span>  Dubai, UAE</p>
            <p>Dear Milele Motors,</p>
            <p>I, ({{ ucfirst($letterOfIndent->customer->name) ?? 'Customer Name'}}) am writing this letter to express my sincere intention to purchase the following models from your company.</p>
            <h5 class="fw-bold" style="margin-bottom: 15px;text-decoration: underline;color: black">Requirements:</h5>
                <div style="list-style-type: none;">
                    @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
                        <li>{{$key + 1}}.&nbsp;
                            <span class="fw-bold">Model Name:</span>
                            {{ $letterOfIndentItem->masterModel->variant->brand->brand_name ?? ''}},
                            {{ $letterOfIndentItem->masterModel->variant->master_model_lines->model_line ?? '' }}
                            {{ $letterOfIndentItem->masterModel->variant->my ?? ''}}
                        </li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fw-bold">Type: </span> Brand New</li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fw-bold">Drive: </span>
                            @if($letterOfIndentItem->masterModel->steering == 'LHD')
                                Left Hand Drive
                            @elseif($letterOfIndentItem->masterModel->steering == 'RHD')
                                Right Hand Drive
                            @endif

                        </li>
                        <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fw-bold">Engine: </span>
                        {{ $letterOfIndentItem->masterModel->variant->engine ?? ''}}</li>
                        <li style="margin-bottom: 10px;">&nbsp;&nbsp;&nbsp;&nbsp&nbsp;<span class="fw-bold">Quantity: </span>
                            {{ $letterOfIndentItem->quantity ?? '' }}
                        </li>
                    @endforeach
                </div>
            <br>
            <p>
                I understand that this Letter of Intent is not legally binding and merely expresses my genuine interest in
                purchasing your vehicle under the specified terms. A formal Purchase Agreement will be prepared once this letter is accepted. Furthermore,
                I would like to declare that the purchased automobile(s) will be registered within the designated country,
                and this acquisition is not intended for resale purposes.
            </p>
            <p style="margin-bottom: 5px;">Sincerely,</p>
            <p> {{ ucfirst($letterOfIndent->customer->name ?? '') }} </p>
        </div>
    </div>

@endsection

{{--<script type="text/javascript">--}}

{{--    var height = document.getElementById('so-items').offsetHeight;--}}
{{--    const values = ["200", "500", "300", "400", "600"];--}}
{{--    const random = Math.floor(Math.random() * values.length);--}}
{{--    var pixel = values[random];--}}

{{--    var imagePosition = 500;--}}

{{--    $('.overlay-image').css('left', pixel+'px');--}}
{{--    // $('.overlay-image').css('top', imagePosition+'px' )--}}
{{--</script>--}}

