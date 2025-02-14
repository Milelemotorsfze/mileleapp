@extends('layouts.main')

<style>
    .custom-error {
        color: red;
        margin-top: 10px !important;
    }
</style>

@section('content')
    @can('master-model-lines-create')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-model-lines-create');
    @endphp
    @if ($hasPermission)
        <div class="card-header">
            <h4 class="card-title">Add Model Line</h4>
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
            <form id="form-create" action="{{ route('model-lines.store') }}" method="POST" >
                @csrf
                <div class="row">
                    <div class="row ">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label"> Model Line</label>
                                <input type="text" class="form-control" name="model_line" value="{{ old('model_line') }}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label"> Brand</label>
                               <select class="form-control" name="brand_id" id="brand_id">
                                   <option></option>
                                   @foreach($brands as $brand)
                                       <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                   @endforeach
                               </select>
                            </div>
                        </div>
                        </br>
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

        $('#brand_id').select2({
            placeholder: "Choose Brand"
        })
        $('#brand_id').on('change',function() {
            $('#brand_id-error').remove();
        })
        $("#form-create").validate({
            ignore: [],
            rules: {
                brand_name: {
                    required: true,
                    maxlength:255
                },
                brand_id: {
                    required:true
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('custom-error');
                if (element.attr("name") === "brand_id") {
                    error.insertAfter(element.next('.select2'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
    </script>
@endpush

