@extends('layouts.main')
@section('content')
    @can('edit-loi-mapping-criterias')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-loi-mapping-criterias');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Add LOI Mapping Criteria</h4>
                <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                <form id="form-create" action="{{ route('loi-mapping-criterias.update', $loiMappingCriteria->id ) }}" method="POST" >
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="row ">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $loiMappingCriteria->name) }}" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Type</label>
                                    <select name="value_type" class="form-select" >
                                        <option value="{{ \App\Models\LOIMappingCriteria::TYPE_MONTH }}"
                                            {{  \App\Models\LOIMappingCriteria::TYPE_MONTH  == $loiMappingCriteria->valuetype ? 'selected' : '' }} >
                                            {{ \App\Models\LOIMappingCriteria::TYPE_MONTH }}
                                        </option>
                                        <option value="{{ \App\Models\LOIMappingCriteria::TYPE_YEAR }}"
                                            {{  \App\Models\LOIMappingCriteria::TYPE_YEAR  == $loiMappingCriteria->valuetype ? 'selected' : '' }} >
                                            {{ \App\Models\LOIMappingCriteria::TYPE_YEAR }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Month/Year Value</label>
                                    <input type="number" class="form-control" name="value" value="{{ old('value', $loiMappingCriteria->value) }}" placeholder="Enter Month or Year Value">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Priority Number</label>
                                    <input type="number" class="form-control" required oninput="validity.valid||(value='');" name="order" min="1" value="{{ old('order', $loiMappingCriteria->order) }}" placeholder="Enter Priority">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label text-muted"> Country</label>
                                    <select class="form-control" data-trigger name="country" id="choices-single-default">
                                        <option value='UAE' {{ $loiMappingCriteria->country == 'UAE' ? 'selected' : '' }} >UAE</option>
                                        <option value='Belgium' {{ $loiMappingCriteria->country == 'UAE' ? 'selected' : '' }} >Belgium</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
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
        $("#form-create").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    maxlength:255
                },
                value: {
                    required: true,
                },
                value_type: {
                    required: true,
                    maxlength:255
                },
            },
        });
    </script>
@endpush

