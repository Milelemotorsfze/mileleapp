@extends('layouts.table')
@section('content')
    @can('list-loi-expiry-conditions')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-loi-expiry-conditions');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    LOI Expiry Criteria
                </h4>
         
                <a  class="btn btn-sm btn-info float-end" href="{{ route('letter-of-indents.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                @if (Session::has('error'))
                    <div class="alert alert-danger mt-3 mb-2" >
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success mt-3 mb-2" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('success') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.No</th>
                            <th>Catgeory Name</th>
                            <th>Expiry Duration</th>
                            <th>Expiry Duration Type</th>
                            <th>Created At</th>
                            <th>Created By</th>
                            <th>Updated At</th>
                            <th>Updated By</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                            @foreach ($loiExpiryConditions as $key => $loiExpiryCondition)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $loiExpiryCondition->category_name }}</td>
                                    <td>{{ $loiExpiryCondition->expiry_duration }}</td>
                                    <td>{{ $loiExpiryCondition->expiry_duration_type }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($loiExpiryCondition->created_at)->format('d M Y') ?? '' }}</td>
                                    <td>{{ $loiExpiryCondition->createdBy->name ?? '' }}</td> 
                                     <td>{{ \Illuminate\Support\Carbon::parse($loiExpiryCondition->updated_at)->format('d M Y') ?? '' }}</td>
                                     <td>{{ $loiExpiryCondition->updatedBy->name ?? '' }}</td>
                                    <td>
                                        @can('edit-loi-expiry-criterias')
                                            @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-loi-expiry-criterias');
                                            @endphp
                                            @if ($hasPermission)
                                            
                                                <a href="{{ route('loi-expiry-conditions.edit', $loiExpiryCondition->id) }}" 
                                                title="Update LOI Expiry Duration Year" class="btn btn-soft-green btn-sm" >
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endif
    @endcan
@endsection


