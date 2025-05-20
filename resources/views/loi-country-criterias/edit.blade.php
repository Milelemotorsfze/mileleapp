@extends('layouts.main')
@section('content')
    <style>
        .widthinput
        {
            height:32px!important;
        }
        .custom-error {
            color: red;
        }
    </style>
    @can('loi-restricted-country-edit')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-edit');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Edit LOI Restricted Countries</h4>
                <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('loi-country-criterias.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                <form id="form-create" action="{{ route('loi-country-criterias.update', $loiCountryCriteria->id) }}" method="POST" >
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Country </label>
                                    <select class="form-control widthinput" multiple name="country_id" id="country" autofocus>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ $country->id == $loiCountryCriteria->country_id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Restricted Model Lines </label>
                                    <select class="form-control widthinput" multiple name="restricted_master_model_line_ids[]" id="restricted_model_line" autofocus>
                                        @foreach($modelLines as $modelLine)
                                            <option value="{{ $modelLine->id }}"
                                            {{ (in_array ($modelLine->model_line, $loiCountryCriteria->restricted_model_lines)) ? 'selected' : ''  }}>  {{ $modelLine->model_line }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Allowed Model Lines </label>
                                    <select class="form-control widthinput" multiple name="allowed_master_model_line_ids[]" id="allowed_model_line" autofocus>
                                        @foreach($modelLines as $modelLine)
                                            <option value="{{ $modelLine->id }}"
                                            {{ (in_array ($modelLine->model_line, $loiCountryCriteria->allowed_model_lines)) ? 'selected' : ''  }}> {{ $modelLine->model_line }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Maximum QTY/Passport </label>
                                    <input type="number" class="form-control widthinput"  step="1" oninput="validity.valid||(value='');" min="1"
                                           name="max_qty_per_passport" placeholder="Enter Maximum Quantity / Passport" value="{{ old('max_qty_per_passport', $loiCountryCriteria->max_qty_per_passport) }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Minimum QTY/ Company </label>
                                    <input type="number" class="form-control widthinput" placeholder="Enter Minimum Quantity / Company"  step="1" oninput="validity.valid||(value='');" min="1"
                                           name="min_qty_for_company" value="{{ old('min_qty_for_company', $loiCountryCriteria->min_qty_for_company) }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Maximum QTY/ Company </label>
                                    <input type="number" class="form-control widthinput"  placeholder="Enter Maximum Quantity / Company" step="1" oninput="validity.valid||(value='');"
                                           min="1"  name="max_qty_for_company" value="{{ old('max_qty_for_company', $loiCountryCriteria->max_qty_for_company) }}" >
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label for="choices-single-default" class="form-label"> Is LOI Can be Created for Only Company? </label>
                                <select class="form-control widthinput" multiple name="is_only_company_allowed" id="is_only_company_allowed" autofocus>
                                    <option value="{{ \App\Models\LoiCountryCriteria::YES }}"
                                        {{ $loiCountryCriteria->is_only_company_allowed == \App\Models\LoiCountryCriteria::YES ? 'selected'  : ''}}>Yes</option>
                                    <option value="{{ \App\Models\LoiCountryCriteria::NO }}"
                                        {{ $loiCountryCriteria->is_only_company_allowed == \App\Models\LoiCountryCriteria::NO ? 'selected'  : '' }} > No </option>
                                    <option value="{{ \App\Models\LoiCountryCriteria::NONE }}"
                                        {{ $loiCountryCriteria->is_only_company_allowed == ' '  ? 'selected'  : ''}} > None </option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label for="choices-single-default" class="form-label"> Steering </label>
                                <select class="form-control widthinput" multiple name="steering" id="steering" autofocus>
                                    <option value="LHD" {{ $loiCountryCriteria->steering == 'LHD' ? 'selected' : '' }}>LHD</option>
                                    <option value="RHD" {{ $loiCountryCriteria->steering == 'RHD' ? 'selected' : '' }}>RHD </option>
                                </select>
                            </div>
                           
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Comment </label>
                                    <textarea cols="25" rows="5" class="form-control" name="comment"> {{ old('comment', $loiCountryCriteria->comment) }} </textarea>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label class="form-label"></label>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="is_loi_restricted" id="is_loi_restricted"
                                           value="1" {{ true == $loiCountryCriteria->is_loi_restricted  ? 'checked' : ''}}  />
                                    <label class="form-check-label" for="is_loi_restricted">
                                        Is LOI Restricted ?
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">TTC Approval Models </label>
                                    <select class="form-control widthinput" multiple name="ttc_approval_models[]" id="ttc_approval_models" autofocus>
                                        @foreach($models as $model)
                                            <option value="{{ $model->id }}"   {{ (in_array ($model->id, $TTCApprovalModels)) ? 'selected' : ''  }}> {{ $model->model }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary ">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        $('#country').select2({
            placeholder : 'Select Country',
            allowClear: true,
            maximumSelectionLength: 1
        });
        $('#steering').select2({
            placeholder : 'Select Steering',
            allowClear: true,
            maximumSelectionLength: 1
        });
        $('#restricted_model_line').select2({
            placeholder : 'Select Restricted Model Lines',
            allowClear: true,
        });
        $('#allowed_model_line').select2({
            placeholder : 'Select Allowed Model Lines',
            allowClear: true,
        });
        $('#is_only_company_allowed').select2({
            placeholder : 'Select Option',
            allowClear: true,
            maximumSelectionLength: 1
        });
        $('#is_inflate_qty').select2({
            placeholder : 'Select Option',
            allowClear: true,
            maximumSelectionLength: 1
        });
        $('#is_longer_lead_time').select2({
            placeholder : 'Select Option',
            allowClear: true,
            maximumSelectionLength: 1
        });
        $('#ttc_approval_models').select2({
            placeholder : 'Select Model',
            allowClear: true,
        });
        $("#form-create").validate({
            ignore: [],
            rules: {
                country_id: {
                    required: true,
                },
            },
            errorPlacement: function(error, element) {
                error.addClass('custom-error');
                if (element.attr("name") === "country_id") {
                    error.insertAfter(element.next('.select2'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
    </script>
@endpush

