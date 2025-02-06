@extends('layouts.table')
@section('content')
    @can('view-model-description-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-model-description-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    Master Model Descriptions
                </h4>
            </div>
            <div class="card-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br>
                        <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('success') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Ref.No</th>
                            <th>Brand</th>
                            <th>Model Line</th>
                            <th>Model Description</th>
                            <th>Created By</th>
                            <th>Created At</th>
                           
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}

                            @foreach ($modelDescriptions as $key => $modelDescription)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $modelDescription->modelLine->brand->brand_name }}</td>
                                    <td>{{ $modelDescription->modelLine->model_line ?? ''}}</td>
                                    <td>{{ $modelDescription->model_description }}</td>
                                    <td>{{ $modelDescription->createdBy->name ?? '' }}</td>
                                    <td>{{ $modelDescription->created_at ?? '' }}</td>
                                    
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

       @endif
        @endcan
@endsection



