@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
  @endphp
  @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">
            Master Lead Source
        </h4>
        @can('Calls-modified')
        <a class="btn btn-sm btn-info float-end" href="{{ route('strategy.index') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Strategies Report
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-success float-end" href="{{ route('lead_source.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Master Lead Source
      </a>
      <div class="clearfix"></div>
<br>
    @endcan
    </div>
    <div class="card-body">
    <div class="modal fade addpriorityModal-modal" id="addpriorityModal" tabindex="-1" aria-labelledby="addpriorityModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addpriorityModalLabel">Update Priority</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                <input type="hidden" id="leadSourceId" value="">
                <label for="addpriorityModallabel">Select Priority:</label>
                <select class="form-control" id="priority">
                    <option value="Normal">Normal</option>
                    <option value="Low">Low</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="savePriority()">Update</button>
            </div>
        </div>
    </div>
</div>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($data as $lead_source)
                    <tr data-id="1">
                        <td>{{ $lead_source->id}}</td>
                        <td>{{ $lead_source->source_name }}</td>
                        @if($lead_source->status == "inactive")
                    <td><label class="badge badge-soft-danger">In Active</label></td>
                @else 
                <td><label class="badge badge-soft-success">Active</label></td>
                @endif
                
                        @if($lead_source->priority == "High")
                        <td><span class="badge badge-soft-danger">High</span></td>
                        @elseif($lead_source->priority == "Normal")
                        <td><span class="badge badge-soft-success">Normal</span></td>
                        @elseif($lead_source->priority == "Low")
                        <td><span class="badge badge-soft-warning">Low</span></td>
                        @else
                        <td><span class="badge badge-soft-secondary">{{ $lead_source->priority }}</span></td>
                        @endif
                        <td><a title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('lead_source.edit',$lead_source->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @if($lead_source->status == "active")
                        <a title="Create Strategy" data-placement="top" class="btn btn-sm btn-success" href="{{ route('strategy.edit',$lead_source->id) }}"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        <a title="Set Priority" data-placement="top" class="btn btn-sm btn-warning" href="#" data-toggle="modal" onclick="addpriority({{$lead_source->id}})"><i class="fa fa-check-square" aria-hidden="true"></i></a></td>
                    @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
<script>
    function addpriority(leadSourceId) {
        $('#leadSourceId').val(leadSourceId);
    console.log('Lead Source ID:', leadSourceId);
    $('#addpriorityModal').modal('show');
}
function closemodal()
{
    $('#addpriorityModal').modal('hide');
}
    function savePriority(leadSourceId) {
        var leadSourceId = $('#leadSourceId').val();
    // Retrieve other values as needed (e.g., priority)
    var priority = $('#priority').val();
        // Perform AJAX request
        $.ajax({
            url: '{{ route("strategy.updatePriority") }}',
            type: 'POST',
            data: {
                lead_source_id: leadSourceId,
                priority: priority,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
                alertify.success('Update The Lead Source Priority Successfully');
        setTimeout(function() {
          window.location.reload();
        }, 1000);
                $('#addpriorityModal').modal('hide');
            },
            error: function (error) {
                console.error(error);
            }
        });
    }
</script>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
