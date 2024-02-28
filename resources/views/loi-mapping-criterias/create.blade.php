@extends('layouts.main')
@section('content')

    @can('create-loi-mapping-criterias')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-loi-mapping-criterias');
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
                <form id="form-create" action="{{ route('loi-mapping-criterias.store') }}" method="POST" >
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label"> Name</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label"> Type</label>
                               <select name="value_type" class="form-select"  id="value-type">
                                   <option value="{{ \App\Models\LOIMappingCriteria::TYPE_MONTH }}">
                                       {{ \App\Models\LOIMappingCriteria::TYPE_MONTH }}
                                   </option>
                                   <option value="{{ \App\Models\LOIMappingCriteria::TYPE_YEAR }}">
                                       {{ \App\Models\LOIMappingCriteria::TYPE_YEAR }}
                                   </option>
                               </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label"> Month/Year Value</label>
                                <input type="text" class="form-control" name="value" value="{{ old('value') }}" placeholder="Enter Month or Year Value">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Priority Number</label>
                                <input type="number" class="form-control" name="order" min="1" required  oninput="validity.valid||(value='');" value="{{ old('order') }}" placeholder="Enter Priority">
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
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

