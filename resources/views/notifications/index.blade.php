@extends('layouts.main')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="mb-4 text-center" style="font-size: 1.5rem; font-weight: 600; color: #343a40;">Notifications</h1>

            @if($notifications->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle-fill"></i> No new notifications.
                </div>
            @else
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <div class="list-group-item list-group-item-action mb-3 p-4 rounded shadow-sm border-0" style="background-color: #f8f9fa;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary text-white me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                        <i class="bi bi-bell-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0" style="font-size: 1rem; font-weight: 600; color: #007bff;">
                                            {{ $notification->module }} - {{ $notification->type }}
                                        </h5>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            {{ $notification->created_at->format('d M Y, h:i A') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            @php
                                $lines = explode("\n", str_replace('<br />', "\n", $notification->detail));
                            @endphp

                            <div class="row mt-3">
                                @foreach($lines as $index => $line)
                                    @php
                                        $line = trim($line);

                                        if (strpos($line, 'Order URL:') !== false) {
                                            continue;
                                        }

                                        if (strpos($line, ':') !== false) {
                                            $parts = explode(':', $line, 2);
                                            $label = trim($parts[0]);
                                            $value = trim($parts[1]);
                                        } else {
                                            $label = $line;
                                            $value = '';
                                        }
                                    @endphp
                                    @if(!empty($label))
                                        <div class="col-md-6 mb-2">
                                            <div class="p-2 rounded" style="background-color: #e9ecef; font-size: 0.875rem; color: #495057;">
                                                <strong>{{ $label }}:</strong> 
                                                {{ $value ?: 'N/A' }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            @if (preg_match('/Order URL: (.*)/', $notification->detail, $matches))
                                <div class="text-end mt-3">
                                    <a href="{{ $matches[1] }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                        <i class="bi bi-box-arrow-up-right"></i> View Order
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Display pagination links -->
                <div class="d-flex justify-content-end mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm">
            @if ($notifications->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $notifications->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo; Previous</span>
                    </a>
                </li>
            @endif

            @foreach ($notifications->links()->elements[0] as $page => $url)
                @if ($page == $notifications->currentPage())
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" href="#">{{ $page }}</a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            @if ($notifications->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $notifications->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">Next &raquo;</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
                </li>
            @endif
        </ul>
    </nav>
</div>
            @endif
        </div>
    </div>
</div>
@endsection
