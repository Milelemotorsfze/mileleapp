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
                <a  class="btn btn-sm btn-secondary float-end mr-2" href="{{ route('migrations.index') }}" >
                <i class="fa fa-check" aria-hidden="true"></i> Migration Check</a>
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
                    <div class="alert alert-danger mt-3 mb-0" >
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success mt-3 mb-0" id="success-alert">
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
                            <th>Expiry Duration (Years)</th>
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
                                    <td>{{ $loiExpiryCondition->expiry_duration_year }}</td>
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
                                            
                                                <button type="button" title="Update LOI Expiry Duration Year" class="btn btn-soft-green btn-sm" data-bs-toggle="modal" 
                                                        data-bs-target="#update-loi-expiry-duration-{{$loiExpiryCondition->id}}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            @endif
                                        @endcan
                                       
                                    </td>
                                    <div class="modal fade " id="update-loi-expiry-duration-{{$loiExpiryCondition->id}}" data-bs-backdrop="static" 
                                                      tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog  modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel"> Update Expiry Duration(Years)</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('loi-expiry-conditions.update', $loiExpiryCondition->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="col-lg-12">
                                                                <div class="row p-2">
                                                                    <input type="number" min="0" placeholder="Expiry Duration(Year)" required name="expiry_duration_year" class="form-control" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-info">Update</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        $('.btn-delete').on('click',function(e){
            e.preventDefault();
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            _method: 'DELETE',
                            id: 'id',
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            alertify.success('Item Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
    </script>
@endpush



