@extends('layouts.main')
@section('content')

    @can('model-year-calculation-categories-create')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-categories-edit');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Edit Model Year Category</h4>
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
                <form id="form-create" action="{{ route('model-year-calculation-categories.update', $modelYearCalculationCategory->id) }}" method="POST" >
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="row ">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Name</label>
                                    <input type="text" class="form-control" name="name" style="height: 32px;" placeholder="Enter Name"
                                           value="{{ old('name', $modelYearCalculationCategory->name) }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Rule</label>
                                    <select name="model_year_rule_id" class="form-select" id="rule" multiple>
                                        @foreach($modelYearCalculationRules as $modelYearCalculationRule)
                                            <option value="{{ $modelYearCalculationRule->id }}"
                                                {{ $modelYearCalculationRule->id == $modelYearCalculationCategory->model_year_rule_id ? 'selected' : '' }} >
                                                {{ $modelYearCalculationRule->name }}
                                            </option>
                                        @endforeach
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
        $('#rule').select2({
            placeholder: 'Select Rule',
            allowClear: true,
            maximumSelectionLength: 1,
        });
        $("#form-create").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    maxlength:255
                },
                model_year_rule_id: {
                    required: true,
                },
            },
        });
    </script>
@endpush

