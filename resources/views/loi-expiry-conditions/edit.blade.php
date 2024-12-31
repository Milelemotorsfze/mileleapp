@extends('layouts.main')
@section('content')
    @can('edit-loi-expiry-criterias')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-loi-expiry-criterias');
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
                <form id="form-create" action="{{ route('loi-expiry-conditions.update', $loiExpiryCondition->id ) }}" method="POST" >
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="row ">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Expiry Duration </label>
                                    <input type="number" class="form-control"  oninput="validity.valid||(value='');" id="duration" name="expiry_duration" min="1" 
                                    value="{{ old('expiry_duration', $loiExpiryCondition->expiry_duration) }}" placeholder="Enter Expiry Duration">
                                    <span id="duration-error" class="text-danger"> </span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label text-muted"> Expiry Duration Type</label>
                                        <select name="expiry_duration_type" class="form-select" id="expiry_duration_type" >
                                            <option value="{{ \App\Models\LOIExpiryCondition::LOI_DURATION_TYPE_YEAR }}"
                                                {{  \App\Models\LOIExpiryCondition::LOI_DURATION_TYPE_YEAR  == $loiExpiryCondition->expiry_duration_type ? 'selected' : '' }} >
                                                {{ \App\Models\LOIExpiryCondition::LOI_DURATION_TYPE_YEAR }}
                                            </option>
                                            <option value="{{ \App\Models\LOIExpiryCondition::LOI_DURATION_TYPE_MONTH }}"
                                                {{  \App\Models\LOIExpiryCondition::LOI_DURATION_TYPE_MONTH  == $loiExpiryCondition->expiry_duration_type ? 'selected' : '' }} >
                                                {{ \App\Models\LOIExpiryCondition::LOI_DURATION_TYPE_MONTH }}
                                            </option>
                                        </select>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" id="submit-button" class="btn btn-primary">Submit</button>
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
                expiry_duration: {
                    required: true,
                    number:true,
                    min:1,
                },
                expiry_duration_type: {
                    required: true,
                },
            }
            
        });
        $('#submit-button').click(function (e) {
            e.preventDefault();
            let month = "{{ \App\Models\LOIExpiryCondition::LOI_DURATION_TYPE_MONTH }}";
            let durationType = $('#expiry_duration_type').val();
            if(durationType == month) {
                let duration = $('#duration').val();
                if(duration > 12) {
                    document.getElementById("duration-error").textContent="Maximum value can be enter 12";
                    document.getElementById("duration").classList.add("is-invalid");
                    document.getElementById("duration-error").classList.add("paragraph-class");
                        e.preventDefault();
                }else{
                    document.getElementById("duration-error").textContent="";
                    document.getElementById("duration").classList.remove("is-invalid");
                    document.getElementById("duration-error").classList.remove("paragraph-class");
                    if($("#form-create").valid()) {
                        $('#form-create').unbind('submit').submit();
                    }
                }
            }else{
                if($("#form-create").valid()) {
                    $('#form-create').unbind('submit').submit();
                }
            }
        
        });
        
    </script>
@endpush

