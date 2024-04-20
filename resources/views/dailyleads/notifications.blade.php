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
    @foreach ($leadsNotifications as $leadsNotifications)
    @php
        $createdAt = $leadsNotifications->created_at;
        $timeElapsed = \Carbon\Carbon::parse($createdAt)->diffForHumans();
    @endphp
    @if($leadsNotifications->category == "Pending Lead")
        <div class="alert alert-danger alert-dismissible rounded shadow" role="alert">
            <strong>Pending Lead:</strong> {{$leadsNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $leadsNotifications->calls_id]) }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>
    @elseif ($leadsNotifications->category == "Fellow Up")
    <div class="alert alert-warning alert-dismissible rounded shadow" role="alert">
            <strong>Fellow Up:</strong> {{$leadsNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
        </div>
        @elseif ($leadsNotifications->category == "Quotation Fellow Up")
    <div class="alert alert-danger alert-dismissible rounded shadow" role="alert">
            <strong>Quotation Fellowup:</strong> {{$leadsNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
        </div>
        @elseif ($leadsNotifications->category == "Processed Lead")
    <div class="alert alert-info alert-dismissible rounded shadow" role="alert">
            <strong>Pending Prospecting:</strong> {{$leadsNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
        </div>
        @else
        <div class="alert alert-primary alert-dismissible rounded shadow" role="alert">
            <strong>Pending Task:</strong> {{$leadsNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $leadsNotifications->calls_id]) }}" class="btn btn-sm btn-outline-primary">View Lead</a>
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
        </div>
    @elseif ($seenNotifications->category == "Fellow Up")
    <div class="alert alert-warning alert-dismissible rounded shadow" role="alert">
            <strong>Fellow Up:</strong> {{$seenNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
        </div>
        @elseif ($seenNotifications->category == "Quotation Fellow Up")
    <div class="alert alert-danger alert-dismissible rounded shadow" role="alert">
            <strong>Quotation Fellowup:</strong> {{$seenNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
        </div>
        @elseif ($seenNotifications->category == "Processed Lead")
    <div class="alert alert-info alert-dismissible rounded shadow" role="alert">
            <strong>Pending Prospecting:</strong> {{$seenNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
        </div>
        @else
        <div class="alert alert-primary alert-dismissible rounded shadow" role="alert">
            <strong>Pending Task:</strong> {{$seenNotifications->remarks}}
            <span class="date-time">{{$timeElapsed}}</span>
            <a href="{{ route('view_lead', ['call_id' => $seenNotifications->calls_id]) }}" class="btn btn-sm btn-outline-primary">View Lead</a>
        </div>  
    @endif
    @endforeach
    </div>
</div>
</div>
<script>
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
