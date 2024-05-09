@extends('layouts.table')
<style>
    .date-time {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0.5rem;
    font-size: 0.8rem;
}
</style>
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">
        Recent
        </h4>
    </div>
    <div class="card-body">
    <div class="modal fade" id="notificationopenmodel" tabindex="-1" aria-labelledby="notificationopenmodelLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="notificationopenmodelLabel">Follow Up (Update)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="date" class="form-label">Date:</label>
            </div>
            <div class="col-md-8">
            <input type="date" class="form-control" id="date" value="{{ date('Y-m-d') }}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Time:</label>
            </div>
            <div class="col-md-8">
            <input type="time" class="form-control" id="time" value="{{ date('H:i') }}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="document-upload" class="form-label">Method:</label>
            </div>
            <div class="col-md-8">
            <select class="form-select" id="method">
            <option value="Email">Email</option>
            <option value="Call">Call</option>
            <option value="Direct">Direct</option>
            <option value="SMS">SMS</option>
            <option value="WhatsApp">WhatsApp</option>
          </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="document-upload" class="form-label">Sales Notes:</label>
            </div>
            <div class="col-md-8">
            <textarea class="form-control" id="sales-notesfoup"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="savefollowupdate()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
    @foreach ($leadsNotifications as $leadsNotifications)
    @php
        $createdAt = $leadsNotifications->created_at;
        $timeElapsed = \Carbon\Carbon::parse($createdAt)->diffForHumans();
    @endphp
    @if($leadsNotifications->category == "Pending Lead")
        <div class="alert alert-danger alert-dismissible rounded shadow" role="alert">
            <strong>Pending Lead:</strong> {{$leadsNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $leadsNotifications->calls_id]) }}?additional_param={{ 'Pending Lead' }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>
    @elseif ($leadsNotifications->category == "Fellow Up")
    <div class="alert alert-warning alert-dismissible rounded shadow" role="alert">
            <strong>Fellow Up:</strong> {{$leadsNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $leadsNotifications->calls_id]) }}?additional_param={{ 'Fellow Up' }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>
        @elseif ($leadsNotifications->category == "Quotation Fellow Up")
    <div class="alert alert-danger alert-dismissible rounded shadow" role="alert">
            <strong>Quotation Fellowup:</strong> {{$leadsNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $leadsNotifications->calls_id]) }}?additional_param={{ 'Quotation Fellow Up' }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>
        @elseif ($leadsNotifications->category == "Processed Lead")
    <div class="alert alert-info alert-dismissible rounded shadow" role="alert">
            <strong>Pending Prospecting:</strong> {{$leadsNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $leadsNotifications->calls_id]) }}?additional_param={{ 'Processed Lead' }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>
        @else
        <div class="alert alert-primary alert-dismissible rounded shadow" role="alert">
            <strong>Pending Task:</strong> {{$leadsNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $leadsNotifications->calls_id]) }}?additional_param={{ 'others' }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>  
    @endif
    @endforeach
    </div>
</div>
</div>
<div class="card">
    <div class="card-header">
        <h4 class="card-title">
        Earlier
        </h4>
    </div>
    <div class="card-body">
    @foreach ($seenNotifications as $seenNotifications)
    @php
        $createdAt = $seenNotifications->created_at;
        $timeElapsed = \Carbon\Carbon::parse($createdAt)->diffForHumans();
    @endphp
    @if($seenNotifications->category == "Pending Lead")
        <div class="alert alert-danger alert-dismissible rounded shadow" role="alert">
            <strong>Pending Leads:</strong> {{$seenNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $seenNotifications->calls_id]) }}?additional_param={{ 'Pending Lead' }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>
    @elseif ($seenNotifications->category == "Fellow Up")
    <div class="alert alert-warning alert-dismissible rounded shadow" role="alert">
            <strong>Fellow Up:</strong> {{$seenNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $seenNotifications->calls_id]) }}?additional_param={{ 'Fellow Up' }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>
        @elseif ($seenNotifications->category == "Quotation Fellow Up")
    <div class="alert alert-danger alert-dismissible rounded shadow" role="alert">
            <strong>Quotation Fellowup:</strong> {{$seenNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $seenNotifications->calls_id]) }}?additional_param={{ 'Quotation Fellow Up' }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>
        @elseif ($seenNotifications->category == "Processed Lead")
    <div class="alert alert-info alert-dismissible rounded shadow" role="alert">
            <strong>Pending Prospecting:</strong> {{$seenNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $seenNotifications->calls_id]) }}?additional_param={{ 'Processed Lead' }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>
        @else
        <div class="alert alert-primary alert-dismissible rounded shadow" role="alert">
            <strong>Pending Task:</strong> {{$seenNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $seenNotifications->calls_id]) }}?additional_param={{ 'others' }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>  
    @endif
    @endforeach
    </div>
</div>
</div>
<script>
// JavaScript/jQuery
$(document).ready(function(){
  $('.view-lead-btn').click(function(e){
    e.preventDefault();
    var callId = $(this).data('call-id');
    var additionalValue = $(this).data('additional-value');

    // AJAX request to fetch modal content
    $.ajax({
      url: '/leads/' + callId,
      type: 'GET',
      data: {
        additional_value: additionalValue
      },
      success: function(response){
        // Update modal content with response data
        $('.modal-body').html(response);
        // Show the modal
        $('#notificationopenmodel').modal('show');
      },
      error: function(xhr, status, error){
        // Handle error
        console.error(error);
      }
    });
  });
});
    function updateNotificationsStatus() {
        fetch('{{ route("update_notifications_status") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                console.log('Notifications status updated successfully.');
            } else {
                console.error('Failed to update notifications status.');
            }
        })
        .catch(error => {
            console.error('Error occurred while updating notifications status:', error);
        });
    }
    window.addEventListener('load', updateNotificationsStatus);
</script>
@endsection
