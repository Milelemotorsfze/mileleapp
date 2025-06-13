@extends('layouts.table')
@section('content')
@can('edit-so')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
    @endphp
    @if ($hasPermission)
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
            Quotation File Versions
                <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            </h4>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row gy-3">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-5 col-md-5">
                                <strong>Document Type:</strong>
                            </div>
                            <div class="col-7 col-md-7">
                                <div>
                                    <label class="form-check-label" for="inlineCheckbox2">
                                        {{$quotation->document_type}} 
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-4 col-md-4">
                                <strong>Currency:</strong>
                            </div>
                            <div class="col-8 col-md-8">
                                <label class="form-check-label" for="inlineCheckbox2">{{$quotation->currency}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-4 col-md-4">
                                <strong>SO Number:</strong>
                            </div>
                            <div class="col-8 col-md-8">
                                <label class="form-check-label" for="inlineCheckbox2">{{$so->so_number}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-4 col-md-4">
                                <strong>SO Date:</strong>
                            </div>
                            <div class="col-8 col-md-8">
                                <label class="form-check-label" for="inlineCheckbox2">{{$so->so_date}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class=" mt-4">
                    <div class="row gy-3">
                        <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <strong>Client's Details</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Client Category:</strong></div>
                                        <div class="col-sm-6">
                                            @if(!$call->company_name)
                                            <label class="form-check-label">Individual</label>
                                            @else
                                            <label class="form-check-label">Company</label>
                                            @endif
                                        </div>
                                    </div>
                                  
                                    <div class="row mb-2" id="company-div">
                                        <div class="col-sm-6"><strong>Company:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">{{$call->company_name}}</label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Customer:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">{{$call->name}}</label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Contact Number:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">{{$call->phone}}</label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Email:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">{{$call->email ?? ''}}</label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Address:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">{{$call->address}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <strong>Document Details</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Document Validity:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">{{$quotationDetail->document_validity}}</label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Sales Person:</strong></div>
                                        <div class="col-sm-6">{{ $quotation->createdBy->name ?? '' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Sales Office:</strong></div>
                                        <div class="col-sm-6">{{ isset($empProfile->office) ? $empProfile->office : '' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Sales Email ID:</strong></div>
                                        <div class="col-sm-6">{{ $quotation->createdBy->email ?? '' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Sales Contact No:</strong></div>
                                        <div class="col-sm-6">{{ isset($empProfile->phone) ? $empProfile->phone : '' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <strong>Delivery Details</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Final Destination:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">
                                                @if ($quotationDetail->country_id)
                                                {{ $quotationDetail->country->name }}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Incoterm:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">{{$quotationDetail->incoterm}}</label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Port of Discharge:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">
                                                @if ($quotationDetail->shippingPort)
                                                {{$quotationDetail->shippingPort->name ?? ''}}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Port of Loading:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">
                                                @if ($quotationDetail->shippingPortOfLoad)
                                                {{$quotationDetail->shippingPortOfLoad->name ?? ''}}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class=" mt-4">
                    <div class="row gy-3">
                        <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <strong>Payment Details</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Payment Terms:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">
                                                @if ($quotationDetail->paymentterms)
                                                {{$quotationDetail->paymentterms->name}}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row mb-2" id="advance-amount-div" hidden>
                                        <div class="col-sm-6"><strong>Advance Amount:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">{{$quotationDetail->advance_amount}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <strong>Client's Representative</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Rep Name:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">{{$quotationDetail->representative_name}}</label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-6"><strong>Rep No:</strong></div>
                                        <div class="col-sm-6">
                                            <label class="form-check-label">{{$quotationDetail->representative_number}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                @if($quotationVersionFiles->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Quotation Files</h5>
                        </div>
                        @foreach($quotationVersionFiles ?? [] as $index => $doc)
                            <div class="col-lg-4 col-sm-12 col-md-4 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title">Document {{ $index + 1 }}</h6>
                                        <embed src="{{ asset('storage/quotation_files/' . $doc->file_name) }}"
                                            type="application/pdf"
                                            class="w-100"
                                            style="height: 400px; border: 1px solid #ccc;" />
                                    </div>
                                    <div class="card-footer d-flex justify-content-end">
                                        <a href="{{ asset('storage/quotation_files/' . $doc->file_name) }}" target="_blank" class="btn btn-sm btn-primary"
                                        style="margin-right:5px;">
                                            View
                                        </a>
                                        <a href="{{ asset('storage/quotation_files/' . $doc->file_name) }}" download class="btn btn-sm btn-secondary">
                                        <i class="fa fa-download" aria-hidden="true"></i>  Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
@endcan
@endsection