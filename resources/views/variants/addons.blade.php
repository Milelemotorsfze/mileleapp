@extends('layouts.main')
@section('content')
    @can('variants-create')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-create');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Create Modification Variant</h4>
                @can('variants-list')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-list');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    @endif
                @endcan
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
                <form id="form-create" action="{{ route('variants.variantmodifications') }}" method="POST">
                    @csrf
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Brand</label>
                                    <input class="form-control" type="text" class="" value="{{$brand->brand_name}}" readonly/>
                                    <input type="hidden" name="varaint" value="{{$variant->id}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Model Line</label>
                                    <select class="form-control" name="master_model_lines_id" id="model" readonly>
                                    <option value="{{$masterModelLine->id}}">{{$masterModelLine->model_line}}</option>
                                </select>
                                </div>
                            </div>
                            </div>
                            <div id="accessiores-container">
                        <div class="row" id="accessiores">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <select class="form-control" autofocus name="attributes[]" id="attributes">
                                    <option disabled selected>Please Select the Attributes</option>
                                        @foreach ($modelspecifications as $modelspecifications)
                                            <option value="{{ $modelspecifications->id }}">{{ $modelspecifications->name }}</option>
                                        @endforeach
                                        <option value="addons">New Addon</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <select class="form-control" name="accessories[]" id="accessories">
                                    <option disabled selected>Please Select the Accessories</option>
                                        @foreach ($addonsaccessores as $addon)
                                            @php
                                                $addonName = DB::table('addons')->where('id', $addon->addon_id)->first();
                                            @endphp 
                                            <option value="{{ $addonName->id }}">{{ $addonName->name }} - {{ $addon->addon_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="addNewRow('accessiores')">Add More Accessories</button>
                            <hr>
                            <div id="spareparts-container">
                                <div class="row" id="spareparts">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <select class="form-control" name="spareparts[]" id="spareparts">
                                        <option disabled selected>Please Select the Spare Parts</option>
                                            @foreach ($spareparts as $sparepart)
                                                @php
                                                    $sparepartName = DB::table('addons')->where('id', $sparepart->addon_id)->first()->name;
                                                @endphp 
                                                <option value="{{ $sparepart->addon_id }}">{{ $sparepartName }} - {{ $sparepart->addon_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="addNewRow('spareparts')">Add More Spare Parts</button>
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
        function addNewRow(type) {
        var container = document.getElementById(type + '-container');
        var newRow = container.querySelector('.row').cloneNode(true);
        var removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.className = "btn btn-danger";
        removeButton.textContent = "X";
        removeButton.onclick = function () {
            newRow.parentNode.removeChild(newRow);
        };
        newRow.querySelector('.mb-3').appendChild(removeButton);
        container.querySelector('.row').parentNode.appendChild(newRow);
        }
        </script>
        @endpush