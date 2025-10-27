@extends('layouts.table')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script> -->

<style>
     #assignBy.select2-container {
        width: 300px !important; /* Adjust the width as needed */
    }
    #taskLogs {
    height: 400px;
    overflow-y: auto;
    border: 1px solid #ddd;
    padding: 10px;
    background-color: #f9f9f9;
}

.rich-text-content img{
    width: 100% !important;
}
#log-content {
    height: 400px;
    overflow-y: auto;
    border: 1px solid #ddd;
    padding: 10px;
    background-color: #f9f9f9;
}
     #conversationLogs {
        height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 10px;
        background-color: #f9f9f9;
    }
.card {
    border: 1px solid #ddd;
    border-radius: 5px;
}

.card-body {
    padding: 15px;
}

.card-body p {
    font-size: 16px;
}

.card-body .text-muted {
    font-size: 12px;
}

.card-body .d-flex {
    display: flex;
    justify-content: space-between;
}
        .file-item {
        position: relative;
        margin-bottom: 20px;
    }
.file-item button.remove-file {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}

.file-item img,
.file-item iframe {
    width: 100%;
    height: 300px;
    border: 1px solid #ccc;
}

.file-item p {
    margin-top: 10px;
    text-align: center;
}
    .comments-header {
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 10;
        border-bottom: 1px solid #dee2e6;
    }

    .fixed-height {
        height: 280px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
    }

    .message-card,
    .message-reply {
        margin-bottom: 1rem;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    .message-card .card-body,
    .message-reply {
        padding: 1rem;
    }

    .message-reply {
        margin-left: 3rem;
        margin-top: 0.5rem;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.5rem;
    }

    .reply-input {
        margin-left: 3rem;
        margin-top: 0.5rem;
        position: relative;
    }

    .avatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .avatar-small {
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
    }

    .user-info {
        display: flex;
        align-items: center;
    }

    .user-name {
        margin-left: 5px;
    }

    .send-icon {
        position: absolute;
        right: 1px;
        bottom: 2px;
        border: none;
        background: none;
        font-size: 0.1rem;
        color: #28a745;
        cursor: pointer;
    }

    .send-icongt {
        position: absolute;
        right: 1px;
        bottom: 1px;
        border: none;
        background: none;
        font-size: 0.01rem;
        color: #28a745;
        cursor: pointer;
    }

    .message-input-wrapper,
    .reply-input-wrapper {
        position: relative;
    }

    .message-input-wrapper textarea,
    .reply-input-wrapper textarea {
        padding-right: 40px;
        width: 100%;
        box-sizing: border-box;
    }

    .btn-danger {
        position: relative;
        z-index: 10;
    }

    .editing {
        background-color: white !important;
        border: 1px solid black !important;
    }

    .short-text {
        display: none;
    }

    .upernac {
        margin-top: 1.8rem !important;
    }

    .float-middle {
        float: none;
        display: block;
        margin: 0 auto;
    }

    .badge-large {
        font-size: 20px !important;
    }

    .bar {
        background-color: #778899;
        height: 30px;
        margin: 10px;
        text-align: center;
        color: white;
        line-height: 30px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .row-space {
        margin-bottom: 10px;
    }

    .progress-bar-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 20px 20px 0 20px;
        box-sizing: border-box;
        margin: 20px 0;
    }

    /* Container for the steps */
    .steps-container {
        display: flex;
        flex-grow: 1;
        width: 100%;
    }

    /* Single step (arrow-shaped) */
    .step {
        display: inline-block;
        position: relative;
        padding: 10px 10px;
        /* Reduced padding for smaller height */
        background-color: #f0f0f0;
        color: #333;
        font-weight: bold;
        text-decoration: none;
        text-align: center;
        transition: background-color 0.3s ease;
        flex: 1;
        border-right: 1px solid white;
        /* Separator between steps */
        cursor: pointer;
        margin: 6px;
    }

    /* Left and right arrows for each step */
    .step::before,
    .step::after {
        content: '';
        position: absolute;
        top: 0;
        width: 0;
        height: 0;
        border-top: 20px solid transparent;
        /* Reduced height for arrows */
        border-bottom: 20px solid transparent;
    }

    /* Left arrow (before the step) */
    .step::before {
        left: 0px;
        border-right: 10px solid #f0f0f0;
        transform: rotate(180deg);
        border-right-color: white;
    }

    /* Right arrow (after the step) */
    .step:not(:last-child)::after {
        right: -10px;
        border-left: 10px solid #f0f0f0;
    }

    /* First step (no left arrow) */
    .step:first-child::before {
        display: none;
    }

    /* Hover effect */
    .step:hover {
        background-color: #007bff;
        color: white;
        cursor: pointer;
    }

    .step:hover::after {
        border-left-color: #007bff;
    }

    /* Active step (current step) */
    .step.active {
        background-color: #007bff;
        color: white;
    }

    /* Hide step text when it's completed */
    .step.completed .text-step {
        display: none;
    }

    /* Show only the tick mark for completed steps */
    .step.completed .tick-mark {
        display: inline-block;
    }

    /* Hide tick mark when step is not completed */
    .step .tick-mark {
        display: none;
    }

    /* Show step text when not completed */
    .step:not(.completed) .text-step {
        display: inline-block;
    }

    .step.active::after {
        border-left-color: #007bff;
    }

    /* Completed step (marked as done) */
    .step.completed {
        background-color: #28a745;
        color: white;
    }

    .text-step.completed {
        display: none;
    }

    .step.completed::before {
        border-right-color: white;
    }

    .step.completed::after {
        border-left-color: #28a745;
    }

    /* Disqualified step */
    .step.disqualified {
        background-color: #dc3545;
        /* Red color for disqualified */
        color: white;
    }

    .step.disqualified::after {
        border-left-color: #dc3545;
    }

    /* Mark Status as Complete button */
    .completion-button {
        margin-left: 20px;
    }

    .completion-button .btn {
        font-size: 16px;
        padding: 10px 20px;
    }

    .steps-container .step:first-child {
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
    }

    .steps-container .step:last-child {
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .step {
            padding: 8px 20px;
            font-size: 12px;
        }

        .step::before,
        .step::after {
            border-top: 18px solid transparent;
            border-bottom: 18px solid transparent;
        }

        .completion-button .btn {
            font-size: 14px;
        }
    }
</style>
@section('content')
<!-- Modal for updating task status -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectionModalLabel">Rejection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="date-input" class="form-label">Date:</label>
          </div>
          <div class="col-md-8">
            <input type="date" class="form-control" id="date-input-reject" value="{{ date('Y-m-d') }}">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="reason" class="form-label">Reason:</label>
          </div>
          <div class="col-md-8">
            <select class="form-control" id="reason-reject">
              <option value="">Select Reason</option>
              <option value="Brand not available">Brand not available</option>
              <option value="Model not available">Model not available</option>
              <option value="Variant not available">Variant not available</option>
              <option value="Price Issue">Price Issue</option>
              <option value="Not Interested">Not Interested</option>
              <option value="Others">Others</option>
            </select>
            <input type="text" class="form-control mt-2" id="other-reason" style="display: none;" placeholder="Specify Other Reason">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="sales-notes" class="form-label">Sales Notes:</label>
          </div>
          <div class="col-md-8">
            <textarea class="form-control" id="salesnotes-reject"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveRejection()">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="updateTaskModal" tabindex="-1" role="dialog" aria-labelledby="updateTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateTaskModalLabel">Update Task Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="updateTaskForm">
    @csrf
    <input type="hidden" id="taskId" name="task_id">
    <div class="form-group">
        <label for="taskStatus">Task Status</label>
        <select class="form-control" id="taskStatus" name="status">
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
        </select>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
            </div>
        </div>
    </div>
</div>
    <!-- Page Header and Lead Title -->
    <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="card-title">Lead</h4>
    <h4 class="card-title">{{$lead->name}}</h4>
    
    <div>
    <a class="btn btn-sm btn-info" href="{{ route('dailyleads.index') }}">
            <i class="fa fa-arrow-left"></i> Back to Listing
        </a>
        @if($previousLead)
            <a href="{{ route('calls.leaddetailpage', $previousLead->id) }}" class="btn btn-sm btn-warning">
                <i class="fa fa-arrow-left"></i> Previous Lead
            </a>
        @endif

        @if($nextLead)
            <a href="{{ route('calls.leaddetailpage', $nextLead->id) }}" class="btn btn-sm btn-success">
                Next Lead <i class="fa fa-arrow-right"></i>
            </a>
        @endif
    </div>
</div>
<!-- Full-width container for the progress bar -->
<div class="progress-bar-container row">
    <div class="col-10">
        <div class="steps-container">
            <!-- Step 1: New -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'New' ? 'active' : ($lead->status === 'contacted' || $lead->status === 'working' || $lead->status === 'qualify' || $lead->status === 'Rejected' || $lead->status === 'converted' ? 'completed' : '') }}"  onclick="moveToStep(1)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">New</span>
                </span>
            </a>

            <!-- Step 2: Contacted -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'contacted' ? 'active' : ($lead->status === 'working' || $lead->status === 'qualify' || $lead->status === 'Rejected' || $lead->status === 'converted' ? 'completed' : '') }}" onclick="moveToStep(2)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">Contacted</span>
                </span>
            </a>

            <!-- Step 3: Working -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'working' ? 'active' : ($lead->status === 'qualify' || $lead->status === 'Rejected' || $lead->status === 'converted' ? 'completed' : '') }}" onclick="moveToStep(3)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">Working</span>
                </span>
            </a>

            <!-- Step 4: Qualify -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'qualify' ? 'active' : ($lead->status === 'Rejected' || $lead->status === 'converted' ? 'completed' : '') }}" onclick="moveToStep(4)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">Qualify</span>
                </span>
            </a>

            <!-- Step 5: Disqualify -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'Rejected' ? 'active disqualified' : '' }}" onclick="moveToStep(5)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">Disqualify</span>
                </span>
            </a>

            <!-- Step 6: Converted -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'converted' ? 'active' : ($lead->status === 'Rejected' ? 'disqualified' : '') }}" onclick="moveToStep(6)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">Converted</span>
                </span>
            </a>
        </div>
    </div>
    @php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
@endphp
@if ($hasPermission)
    <!-- Completion Button -->
    <div class="col-2">
    <div class="completion-button">
    <button id="completeButton" class="btn btn-primary btn-lg" onclick="markAsDone({{ $lead->id }})">
        Mark as Done
    </button>
    <!-- Quotation button with smaller size and positioned below -->
    <button id="quotationButton" class="btn btn-success btn-sm mt-2" style="display: none;" onclick="makeQuotation({{ $lead->id }})">
        Make a Quotation
    </button>
</div>
    </div>
    @endif
</div>

<div class="container mt-4">
    <!-- Tabs for navigation -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab">Customer Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="marketing-tab" data-toggle="tab" href="#marketing" role="tab">Vehicle Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab">Documents & Files</a>
        </li>
    </ul>
    <!-- Main row content -->
    <div class="row mt-3">
        <!-- Dynamic tab content (Left Section) -->
        <div class="col-md-8">
            <div class="tab-content" id="myTabContent">
                <!-- Details tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                    <h5>Lead Details</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table">
                                        <tr>
                                            <th>Client Name</th>
                                            <td id="name-field" data-field="name">{{ $lead->name }}</td>
                                            @php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
@endphp
@if ($hasPermission)
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Company</th>
                                            <td id="company_name-field" data-field="company_name">{{ $lead->company_name }}</td>
                                            @php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
@endphp
@if ($hasPermission)
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Phone</th>
                                            <td id="phone-field" data-field="phone">{{ $lead->phone }}</td>
                                            @php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
@endphp
@if ($hasPermission)
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td id="email-field" data-field="email">{{ $lead->email }}</td>
                                            @php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
@endphp
@if ($hasPermission)
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table">
                                        <tr>
                                            <th>Status</th>
                                            <td>{{ $lead->status }}</td>
                                        </tr>
                                        <tr>
                                            <th>Priority</th>
                                            <td id="priority-field" data-field="priority">{{ $lead->priority }}</td>
                                            @php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
@endphp
@if ($hasPermission)
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Language</th>
                                            <td id="language-field" data-field="language">{{ $lead->language }}</td>
                                            @php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
@endphp
@if ($hasPermission)
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Location</th>
                                            <td id="location-field" data-field="location">{{ $lead->location }}</td>
                                            @php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
@endphp
@if ($hasPermission)
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Details tab -->
                <div class="tab-pane fade" id="marketing" role="tabpanel" aria-labelledby="marketing-tab">
                    <h5 class="mt-3">Vehicle Details</h5>
                    <div id="existing-models" class="container my-3">
                    <div class="row">
                        @foreach($requirements as $requirement)
                            <div class="col-md-6 mb-3" id="requirement-{{ $requirement->id }}">
                                <div class="card shadow-sm position-relative">
                                    <div class="card-body">
                                        <!-- Buttons in the top-right corner -->
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="position-absolute top-0 end-0 p-2">
                                            <button class="btn btn-danger btn-sm me-1" onclick="removeModelLine({{ $requirement->id }})">
                                                <i class="fas fa-trash-alt"></i> Remove
                                            </button>
                                        </div>
                                        @endif
                                        <!-- Main content -->
                                        <h5 class="card-title mb-0">{{ $requirement->masterModelLine->brand->brand_name }}</h5>
                                        <p class="card-text text-muted">{{ $requirement->masterModelLine->model_line }}</p>
                                        <p class="card-text text-muted"><strong>Trim:</strong> {{ $requirement->trim }}</p>
                                        <p class="card-text text-muted"><strong>Variant:</strong> {{ $requirement->variant }}</p>
                                        <p class="card-text text-muted"><strong>Quantity:</strong> {{ $requirement->qty }}</p>
                                        <p class="card-text text-muted"><strong>Final Destination:</strong> {{ $requirement->country->name ?? 'N/A' }}</p>
                                        <p class="card-text text-muted"><strong>Asking Price:</strong> {{ $requirement->asking_price }}</p>
                                        <p class="card-text text-muted"><strong>Offer Price:</strong> {{ $requirement->offer_price }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                            <div class="col-md-6 col-sm-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
    <h5 class="text-center">Remarks</h5>
    <br>

    @php
    $text = $lead->remarks ?? '';
    $cleanText = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
    $hasContent = trim(strip_tags($cleanText)) ? true : false;
@endphp

<div class="rich-text-content">
    @if ($hasContent)
        @php
            $formatted = str_replace('###SEP###', '<br>', $cleanText);
            $formatted = preg_replace(
                '/^Lead Summary - Qualification Notes:/i',
                '<strong>Lead Summary - Qualification Notes:</strong><br>',
                $formatted
            );
            $formatted = preg_replace('/^(?=\d+\.\s)/m', '<br>', $formatted, 1);

            $formatted = preg_replace(
                '/(\d+\.\s*[^:\n]+:.*)(?=<br>General Remark|<br>General Remark|General Remark)/is',
                '$1<br>',
                $formatted
            );

            $formatted = preg_replace(
                '/(\d+\.\s*[^:\n]+):/',
                '<strong>$1:</strong>',
                $formatted
            );

            $formatted = preg_replace(
                '/(General Remark\s*\/\s*Additional Notes:)/i',
                '<strong>$1</strong>',
                $formatted
            );
        @endphp

        {!! nl2br($formatted) !!}
        
        @if($lead->csr_price)
        <br><br>
        <strong>CSR Price:</strong> <span style="display: inline-block; background-color: #007bff; color: white; padding: 3px 8px; border-radius: 3px; font-weight: bold;">{{ number_format($lead->csr_price, 0, '.', ',') }} {{ $lead->csr_currency ?? 'AED' }}</span>
        @endif
    @else
        <p class="text-muted">No remarks.</p>
        @if($lead->csr_price)
        <br><br>
        <strong>CSR Price:</strong> <span style="display: inline-block; background-color: #007bff; color: white; padding: 3px 8px; border-radius: 3px; font-weight: bold;">{{ number_format($lead->csr_price, 0, '.', ',') }} {{ $lead->csr_currency ?? 'AED' }}</span>
        @endif
    @endif
</div>

</div>

                                </div>
                            </div>
                    </div>
                </div>
                <hr>
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
                @endphp
                @if ($hasPermission)
                <div class="add-model-line mt-4">
                    <h6>Add More Model Line</h6>
                    <form id="addModelLineForm">
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                        <!-- One row for all inputs: Brand, Model Line, Trim, and Variant -->
                        <div class="row">
                            <!-- Brand input -->
                            <div class="col-md-3">
                                <select name="brand" class="form-control select2" id="brand" required>
                                    <option value="" disabled selected>Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Model Line input -->
                            <div class="col-md-3">
                                <select name="model_line" class="form-control select2" id="model_line" required disabled>
                                    <option value="" disabled selected>Select Model Line</option>
                                </select>
                            </div>

                            <!-- Trim dropdown -->
                            <div class="col-md-3">
                                <select name="trim" class="form-control select2" id="trim" required disabled>
                                    <option value="" disabled selected>Select Trim</option>
                                </select>
                            </div>

                            <!-- Variant dropdown -->
                            <div class="col-md-3">
                                <select name="variant" class="form-control select2" id="variant" required disabled>
                                    <option value="" disabled selected>Select Variant</option>
                                </select>
                            </div>
                            <div class="col-md-3 mt-4">
                        <input type="number" name="asking_price" class="form-control" id="asking_price" placeholder="Enter asking price" required>
                    </div>

                    <!-- Offer Price input -->
                    <div class="col-md-3 mt-4">
                        <input type="number" name="offer_price" class="form-control" id="offer_price" placeholder="Enter offer price" required>
                    </div>
                    <div class="col-md-3 mt-4">
                        <input type="number" name="qty" class="form-control" id="qty" placeholder="Enter Quanity" required>
                    </div>
                    <div class="col-md-3 mt-4">
                    <select name="countries_id" class="form-control select2" id="countries_id" required>
                        <option value="" disabled selected>Select Final Destination</option>
                        @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option> <!-- 'id' should match the foreign key -->
                @endforeach
                    </select>
                </div>
                    <div id="custom_trim_container" style="display:none;" class="col-md-3 mt-4">
                    <input type="text" id="custom_trim" name="custom_trim" placeholder="Enter custom trim">
                </div>

                <div id="custom_variant_container" style="display:none;" class="col-md-3 mt-4">
                    <input type="text" id="custom_variant" name="custom_variant" placeholder="Enter custom variant">
                </div>
                        </div>
                        <!-- Submit button in a new row -->
                        <div class="form-row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">Add Vehicles</button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
<div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
    <h5 class="mt-3">Documents & Files</h5>
    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
                        @endphp
                        @if ($hasPermission)
    <!-- File Upload Form -->
    <form id="fileUploadForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        <div class="form-group">
            <label for="file">Choose File (PDF, PNG, JPG):</label>
            <input type="file" class="form-control-file" id="file" name="file" accept="image/png, image/jpeg, application/pdf">
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    @endif
    <div class="row mt-4" id="uploadedFiles">
    @foreach($documents as $document)
        <div class="col-md-3" id="file-{{ $document->id }}">
            <div class="file-item" style="position: relative; margin-bottom: 20px;">
            @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
                        @endphp
                        @if ($hasPermission)
                <button class="btn btn-danger btn-sm remove-file" data-id="{{ $document->id }}" style="position: absolute; right: 10px; top: 10px;">
                    <i class="fas fa-times"></i>
                </button>
                @endif
                @if($document->document_type === 'pdf')
                    <iframe src="{{ url($document->document_path) }}" style="width: 100%; height: 300px; border: 1px solid #ccc;"></iframe>
                @else
                    <img src="{{ url($document->document_path) }}" class="img-fluid" style="width: 100%; height: 300px; object-fit: cover;" alt="{{ $document->document_name }}">
                @endif
                <p class="mt-2">{{ $document->document_name }}</p>
            </div>
        </div>
    @endforeach
</div>
</div>
            </div>
        </div>
<div class="col-md-4">
    <h5>Activity</h5>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" id="activityTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="activity-activity-tab" data-toggle="tab" href="#activity-tab-content" role="tab" aria-controls="activity-tab-content" aria-selected="true">Activity</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="chatter-tab" data-toggle="tab" href="#chatter-tab-content" role="tab" aria-controls="chatter-tab-content" aria-selected="false">Chatter</a>
                </li>
            </ul>
            <div class="tab-content" id="activityTabContent">
                <div class="tab-pane fade show active" id="activity-tab-content" role="tabpanel" aria-labelledby="activity-activity-tab">
                    <div class="mt-3">
                        <ul class="nav nav-pills" id="activity-inner-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="logcall-tab" data-toggle="pill" href="#logcall-content" role="tab">Record Activities</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="task-tab" data-toggle="pill" href="#task-content" role="tab">Task</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="task-tab" data-toggle="pill" href="#log-content" role="tab">Logs</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="activity-inner-tabs-content">
                            <div class="tab-pane fade show active" id="logcall-content" role="tabpanel">
                            @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view') || Auth::user()->hasPermissionForSelectedRole('leads-view-only');
                        @endphp
                        @if ($hasPermission)
                                <div class="form-group">
                                    <label for="logCall">Log a Conversation</label>
                                    <textarea class="form-control" id="logCall" rows="3" placeholder="Recording Activity..."></textarea>
                                    <input type="hidden" id="lead_id" value="{{ $lead->id }}">
                                    <button class="btn btn-primary mt-2" id="addLogBtn">Add</button>
                                </div>
                                @endif
                                <div class="mt-4" id="conversationLogs"></div>
                            </div>
                            <div class="tab-pane fade" id="task-content" role="tabpanel">
                                <div class="form-group">
        <label for="assignBy">Assign By</label>
        <select class="form-control" id="assignBy" name="assign_by">
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="task">Task Message</label>
        <textarea class="form-control" id="task" rows="3" placeholder="Describe your task..."></textarea>
    </div>
    <button class="btn btn-primary mt-2" id="addTaskBtn">Add Task</button>
    <div class="mt-4" id="taskLogs"></div>
                            </div>
                            <div class="tab-pane fade" id="log-content" role="tabpanel">
    @if($logs->isEmpty())
        <p>No logs available.</p>
    @else
        @foreach($logs as $log)
            <div class="card mt-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $log->user_name }}</strong>
                        <small class="text-muted">{{ $log->created_at }}</small>
                    </div>
                    <p class="mb-1">{{ $log->activity }}</p>
                </div>
            </div>
        @endforeach
    @endif
</div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="chatter-tab-content" role="tabpanel" aria-labelledby="chatter-tab">
                    <div id="messages" class="fixed-height"></div>
                    <div class="message-input-wrapper mb-3">
                        <textarea id="message" class="form-control main-message" placeholder="Type a message..." rows="1"></textarea>
                        <button id="send-message" class="btn btn-success send-icon">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let currentStep = 1;
    let status = '{{ $lead->status }}';

    document.addEventListener('DOMContentLoaded', () => {
        if (status === 'converted') {
            document.getElementById('quotationButton').style.display = 'block';
        }
    });
    function moveToStep(step) {
        currentStep = step;
        const steps = document.querySelectorAll('.step');
        steps.forEach((stepElem, index) => {
            stepElem.classList.remove('completed', 'active', 'disqualified', 'converted');
            if (index < currentStep - 1) {
                stepElem.classList.add('completed');
            } else if (index === currentStep - 1) {
                stepElem.classList.add('active');
            }
        });
    }
    function makeQuotation(leadId) {
        const url = `{{ route('qoutation.proforma_invoice', ['callId' => ':leadId']) }}`.replace(':leadId', leadId);
        window.location.href = url;
    }
    function markAsDone(leadId) {
        if (currentStep === 2) {
        const name = document.getElementById('name-field').innerText.trim();
        const companyName = document.getElementById('company_name-field').innerText.trim();
        const phone = document.getElementById('phone-field').innerText.trim();
        const email = document.getElementById('email-field').innerText.trim();
        if (!name || !phone) {
            alert('Please ensure that Name, Company Name, Phone, and Email fields are filled out before marking as contacted.');
            return;
        }
    }
    else if (currentStep === 3) {
        const requirements = document.querySelectorAll('[id^="requirement-"]');
        if (requirements.length === 0) {
            alert('Please add at least one requirement before marking this lead as "working."');
            return;
        }
    }
        let newStatus = '';
        if (currentStep === 1) newStatus = 'new';
        else if (currentStep === 2) newStatus = 'contacted';
        else if (currentStep === 3) newStatus = 'working';
        else if (currentStep === 4) newStatus = 'qualify';
        else if (currentStep === 5 && status !== 'disqualify') {
            newStatus = 'disqualify';
            $('#rejectionModal').data('callId', leadId).modal('show');
            return;
        } else if (currentStep === 5 && status === 'disqualify') {
            newStatus = 'converted';
        } else if (currentStep === 6 && status !== 'converted') {
            newStatus = 'converted';
        } else if (currentStep === 6 && status === 'converted') {
            newStatus = 'disqualify';
            $('#rejectionModal').data('callId', leadId).modal('show');
            return;
        }
        fetch(`/leads/${leadId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status updated to: ' + newStatus);
                if (newStatus === 'converted') {
                    document.querySelector('.step:nth-child(5)').classList.add('disqualified');
                    document.querySelector('.step:nth-child(6)').classList.add('converted');
                    document.getElementById('completeButton').style.display = 'none';
                    document.getElementById('quotationButton').style.display = 'block';
                } else if (newStatus === 'disqualify') {
                    document.querySelector('.step:nth-child(5)').classList.add('disqualified');
                    $('#rejectionModal').modal('show');
                } else {
                    window.location.reload();
                }
                status = newStatus;
            } else {
                alert('Failed to update status.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status.');
        });
    }
    function saveRejection() {
        const reason = document.getElementById('reason-reject').value;
        const callId = $('#rejectionModal').data('callId');
        const date = document.getElementById('date-input-reject').value;
        let salesNotes = document.getElementById('salesnotes-reject').value;

        if (!reason) {
            alert('Please provide a reason');
            return;
        }

        let rejectionReason = reason;
        if (reason === "Others") {
            rejectionReason = document.getElementById('other-reason').value;
            if (!rejectionReason) {
                alert('Please specify the other reason');
                return;
            }
        }

        const formData = new FormData();
        formData.append('callId', callId);
        formData.append('date', date);
        formData.append('reason', rejectionReason);
        formData.append('salesNotes', salesNotes);

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route('sales.rejection') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertify.success('Rejection saved successfully');
                $('#rejectionModal').modal('hide');
                window.location.reload();
            } else {
                alert('Error saving rejection');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the rejection.');
        });
    }
    document.getElementById('reason-reject').addEventListener('change', function() {
        const otherReasonInput = document.getElementById('other-reason');
        otherReasonInput.style.display = this.value === 'Others' ? 'block' : 'none';
    });
</script>
<script>



$(document).ready(function() {
    // var itiSecondary;

    $('.edit-btn').on('click', function() {
    var td = $(this).closest('tr').find('td').first();
    var field = td.data('field');
    var currentValue = td.text().trim();
    var editButton = $(this); 

    if (td.find('select').length === 0 && td.find('input').length === 0) {
        if (field === 'priority') {
            var normalizedValue = currentValue?.toLowerCase().trim();
            var dropdown = `
                <select class="form-control">
                    <option value="Hot" ${normalizedValue === 'hot' ? 'selected' : ''}>Hot</option>
                    <option value="Normal" ${normalizedValue === 'normal' ? 'selected' : ''}>Normal</option>
                    <option value="Low" ${normalizedValue === 'low' ? 'selected' : ''}>Low</option>
                    <option value="Regular" ${normalizedValue === 'regular' ? 'selected' : ''}>Regular</option>
                    <option value="High" ${normalizedValue === 'high' ? 'selected' : ''}>High</option>
                </select>`;
            td.html(dropdown);
            editButton.html('<i class="fas fa-save"></i>');
            // } else if (field === 'secondary_phone_number') {
            //     td.html('<input type="tel" id="secondary-phone-input" class="form-control" value="' + currentValue + '">');
            //     editButton.html('<i class="fas fa-save"></i>');
            //     setTimeout(function() {
            //         var secondaryPhoneInputField = document.querySelector("#secondary-phone-input");
            //         itiSecondary = window.intlTelInput(secondaryPhoneInputField, {
            //             utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js",
            //             separateDialCode: false,
            //             autoFormat: false,
            //             nationalMode: false
            //         });
            //     }, 100);

        } else if (field === 'language') {
            var dropdown = '<select class="form-control">';
            @foreach($languages as $language)
            dropdown += `<option value="{{ $language->name }}" ${currentValue === '{{ $language->name }}' ? 'selected' : ''}>{{ $language->name }}</option>`;
            @endforeach
            dropdown += '</select>';
            td.html(dropdown);
            editButton.html('<i class="fas fa-save"></i>');
        } else if (field === 'location') {
            var dropdown = '<select class="form-control">';
            @foreach($countries as $country)
                dropdown += `<option value="{{ $country['name'] }}" ${currentValue === '{{ $country['name'] }}' ? 'selected' : ''}>{{ $country['name'] }}</option>`;
                @endforeach
                dropdown += '</select>';
                td.html(dropdown);
                editButton.html('<i class="fas fa-save"></i>');
        } else {
            var currentValue = td.text().trim();
            td.html('<input type="text" class="form-control" value="' + currentValue + '">');
            $(this).html('<i class="fas fa-save"></i>'); // Change icon to save
        }
    } 
    else {
        var newValue;
        
        if (td.find('select').length > 0) {
            newValue = td.find('select').val();
        } else if (td.find('input').length > 0) {
            newValue = td.find('input').val().trim();
        }

        if (newValue !== '') {
            $.ajax({
                url: '/leads/update',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    field: field,
                    value: newValue,
                    lead_id: '{{ $lead->id }}'
                },
                success: function(response) {
                    td.html(newValue);
                    $(this).html('<i class="fas fa-edit"></i>');
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });
        } else {
            console.log('Input is empty');
        }
    }
    });
});
// Function to remove model line
function removeModelLine(requirementId) {
    if (confirm('Are you sure you want to remove this model line?')) {
        $.ajax({
            url: '/remove-model-line/' + requirementId, // Define this route in your controller
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(result) {
                $('#requirement-' + requirementId).remove();
            },
            error: function(err) {
                alert('Error removing model line');
            }
        });
    }
}
$('#addModelLineForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serializeArray();
    if ($('#trim').val() === 'other') {
        formData.push({ name: 'custom_trim', value: $('#custom_trim').val() });
    }
    if ($('#variant').val() === 'other') {
        formData.push({ name: 'custom_variant', value: $('#custom_variant').val() });
    }
    $.ajax({
        url: '/add-model-line',
        type: 'POST',
        data: formData,
        success: function(response) {
            $('#existing-models').append(
                '<div class="col-md-6 mb-3" id="requirement-' + response.id + '">' +
                '<div class="card shadow-sm">' +
                '<div class="card-body d-flex justify-content-between align-items-center">' +
                '<div>' +
                '<div class="position-absolute top-0 end-0 p-2">'+
                 '<button class="btn btn-danger btn-sm me-1" onclick="removeModelLine(' + response.id + ')">' +
                '<i class="fas fa-trash-alt"></i> Remove' +
                '</button>' +
                '</div>'+
                '<h5 class="card-title mb-0">' + response.brand + '</h5>' +
                '<p class="card-text text-muted">' + response.model_line + '</p>' +
                '<p class="card-text text-muted"><strong>Trim:</strong> ' + response.trim + '</p>' +
                '<p class="card-text text-muted"><strong>Variant:</strong> ' + response.variant + '</p>' +
                '<p class="card-text text-muted"><strong>Quantity:</strong> ' + response.qty + '</p>' +
                '<p class="card-text text-muted"><strong>Asking Price:</strong> ' + response.asking_price + '</p>' +
                '<p class="card-text text-muted"><strong>Offer Price:</strong> ' + response.offer_price + '</p>' +
                '<p class="card-text text-muted"><strong>Country:</strong> ' + response.country + '</p>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>'
            );
            // Clear the form fields after successful submission
            $('#addModelLineForm')[0].reset(); // Resets the form
            $('#custom_trim').val('').parent().hide(); // Hide custom trim input
            $('#custom_variant').val('').parent().hide(); // Hide custom variant input
        },
        error: function(err) {
            alert('Error adding model line');
        }
    });
});
</script>
<script>
$(document).ready(function() {
    const leadid = {{ $lead->id }};
    const userId = {{ auth()->id() }};
    const userName = "{{ auth()->user()->name }}";
    const userAvatar = "{{ auth()->user()->avatar }}"; // Assuming user has an avatar field

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function loadMessages() {
        $.get(`/messages/${leadid}`, function(data) {
            $('#messages').empty();
            data.forEach(function(message) {
                displayMessage(message);
            });
            scrollToBottom();
        });
    }

    function scrollToBottom() {
        $('#messages').scrollTop($('#messages')[0].scrollHeight);
    }

    function formatTimeAgo(date) {
        const now = new Date();
        const messageDate = new Date(date);
        const diff = Math.floor((now - messageDate) / 1000);
        if (diff < 60) return `${diff} seconds ago`;
        if (diff < 3600) return `${Math.floor(diff / 60)} minutes ago`;
        if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;
        return `${Math.floor(diff / 86400)} days ago`;
    }

    function getInitials(name) {
        return name.charAt(0).toUpperCase();
    }

    function getAvatarColor(name) {
        const colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8'];
        const charCode = name.charCodeAt(0);
        return colors[charCode % colors.length];
    }

    function displayMessage(message) {
        console.log(message);
        const replies = message.replies || [];
        const messageTime = formatTimeAgo(message.created_at);
        const userInitial = getInitials(message.user.name);
        const userColor = getAvatarColor(message.user.name);
        const messageHtml = `
            <div class="card message-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="user-info">
                            <div class="avatar" style="background-color: ${userColor};">${userInitial}</div>
                            <strong class="user-name">${message.user.name}</strong>
                        </div>
                        <small class="text-muted">${messageTime}</small>
                    </div>
                    <p class="mt-2">${message.message}</p>
                    <div id="replies-${message.id}">
                        ${replies.map(reply => `
                            <div class="message-reply">
                                <div class="d-flex justify-content-between">
                                    <div class="user-info">
                                        <div class="avatar avatar-small" style="background-color: ${getAvatarColor(reply.user.name)};">${getInitials(reply.user.name)}</div>
                                        <strong class="user-name">${reply.user.name}</strong>
                                    </div>
                                    <small class="text-muted">${formatTimeAgo(reply.created_at)}</small>
                                </div>
                                <p class="mt-1">${reply.reply}</p>
                            </div>
                        `).join('')}
                    </div>
                    <a href="javascript:void(0)" class="reply-link" data-message-id="${message.id}">Reply</a>
                    <div class="reply-input-wrapper input-group mt-2" style="display:none;" id="reply-input-${message.id}">
                        <textarea class="form-control reply-message" placeholder="Reply..." rows="1"></textarea>
                        <button class="btn btn-success btn-sm send-reply send-icongt" data-message-id="${message.id}">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#messages').append(messageHtml);
    }

    function sendMessage() {
        const message = $('#message').val();
        if (message.trim() !== '') {
            $.post('/messages', { leadid: leadid, message: message }, function(data) {
                displayMessage(data);
                $('#message').val('');
                scrollToBottom();
            });
        }
    }

    function sendReply(messageId) {
        const reply = $(`#reply-input-${messageId}`).find('.reply-message').val();
        if (reply.trim() !== '') {
            $.post('/replies', { message_id: messageId, reply: reply }, function(data) {
                const replyHtml = `
                    <div class="message-reply">
                        <div class="d-flex justify-content-between">
                            <div class="user-info">
                                <div class="avatar avatar-small" style="background-color: ${getAvatarColor(data.user.name)};">${getInitials(data.user.name)}</div>
                                <strong class="user-name">${data.user.name}</strong>
                            </div>
                            <small class="text-muted">${formatTimeAgo(data.created_at)}</small>
                        </div>
                        <p class="mt-1">${data.reply}</p>
                    </div>
                `;
                $(`#replies-${messageId}`).append(replyHtml);
                $(`#reply-input-${messageId}`).find('.reply-message').val('');
                $(`#reply-input-${messageId}`).hide();
            });
        }
    }

    $('#send-message').on('click', function() {
        sendMessage();
    });

    $('#message').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            sendMessage();
            e.preventDefault();
        }
    });

    $(document).on('click', '.reply-link', function() {
        const messageId = $(this).data('message-id');
        $(`#reply-input-${messageId}`).toggle();
    });

    $(document).on('keypress', '.reply-message', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            const messageId = $(this).closest('.reply-input-wrapper').attr('id').split('-')[2];
            sendReply(messageId);
            e.preventDefault();
        }
    });
    $(document).on('click', '.send-reply', function() {
        const messageId = $(this).data('message-id');
        sendReply(messageId);
    });
    loadMessages();
});
</script>
<script>
$(document).ready(function() {
    $('.select2').select2();
    // When brand is selected, fetch model lines via AJAX
    $('#brand').on('change', function() {
        var brandId = $(this).val();
        
        if (brandId) {
            // Enable the model line dropdown
            $('#model_line').removeAttr('disabled').html('<option value="" disabled selected>Loading...</option>');

            // Fetch model lines based on the selected brand
            $.ajax({
                url: '/get-model-lines/' + brandId, // Define the route in your controller
                type: 'GET',
                success: function(data) {
                    $('#model_line').html('<option value="" disabled selected>Select Model Line</option>');
                    $.each(data, function(index, modelLine) {
                        $('#model_line').append('<option value="' + modelLine.id + '">' + modelLine.model_line + '</option>');
                    });
                    $('#model_line').select2(); // Reinitialize Select2
                }
            });
        }
    });

    // Fetch trims and variants based on model line
    $('#model_line').on('change', function() {
        var modelLineId = $(this).val();
        if (modelLineId) {
            $('#trim').removeAttr('disabled').html('<option value="" disabled selected>Loading...</option>');
            $('#variant').removeAttr('disabled').html('<option value="" disabled selected>Loading...</option>');
            $.ajax({
                url: '/get-trim-variants/' + modelLineId,
                type: 'GET',
                success: function(data) {
                    $('#trim').html('<option value="" disabled selected>Select Trim</option>');
                    $.each(data.trims, function(index, trim) {
                        $('#trim').append('<option value="' + trim.model_detail + '">' + trim.model_detail + '</option>');
                    });
                    $('#trim').append('<option value="other">Other</option>'); // Add "Other" option for Trim
                    $('#trim').select2(); // Reinitialize Select2
                    $('#variant').html('<option value="" disabled selected>Select Variant</option>');
                    $.each(data.variants, function(index, variant) {
                        $('#variant').append('<option value="' + variant.name + '">' + variant.name + '</option>');
                    });
                    $('#variant').append('<option value="other">Other</option>');
                    $('#variant').select2(); // Reinitialize Select2
                }
            });
        }
    });

    // Show input for "Other" Trim
    $('#trim').on('change', function() {
        var trimValue = $(this).val();
        if (trimValue === 'other') {
            $('#custom_trim_container').show();
        } else {
            $('#custom_trim_container').hide();
        }
    });

    // Show input for "Other" Variant
    $('#variant').on('change', function() {
        var variantValue = $(this).val();
        if (variantValue === 'other') {
            $('#custom_variant_container').show(); // Show the input field for custom Variant
        } else {
            $('#custom_variant_container').hide(); // Hide the input field if "Other" is not selected
        }
    });
});
</script>
<script>
$(document).ready(function () {
    // Handle file upload via AJAX
    $('#fileUploadForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: '{{ route('leadsfile.upload') }}', // route for handling the upload
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.success) {
                    displayUploadedFile(response.file);
                } else {
                    alert(response.message);
                }
            },
            error: function (error) {
                alert('File upload failed.');
            }
        });
    });

    // Function to display uploaded files dynamically
    function displayUploadedFile(file) {
        let fileElement = '';
        if(file.type === 'pdf') {
            fileElement = `
                <div class="col-md-3">
                    <a href="${file.url}" target="_blank">
                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                    </a>
                    <p>${file.name}</p>
                </div>
            `;
        } else if (file.type === 'image') {
            fileElement = `
                <div class="col-md-3">
                    <img src="${file.url}" class="img-fluid" alt="${file.name}">
                    <p>${file.name}</p>
                </div>
            `;
        }
        $('#uploadedFiles').append(fileElement); // Append new file to the existing list
    }
});
</script>
<script>
$(document).ready(function () {
    // Handle file removal via AJAX
    $('.remove-file').on('click', function (e) {
        e.preventDefault();
        var fileId = $(this).data('id'); // Get the file ID from the button data attribute

        if(confirm('Are you sure you want to delete this file?')) {
            $.ajax({
                url: '{{ route('leadsfile.remove') }}', // Define a route to handle the deletion
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: fileId
                },
                success: function (response) {
                    if (response.success) {
                        // Remove the file element from the DOM
                        $('#file-' + fileId).remove();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (error) {
                    alert('Failed to delete the file.');
                }
            });
        }
    });
});
</script>
<script>
$(document).ready(function() {
    // Load existing logs when the page loads
    var leadId = $('#lead_id').val();
    loadLogs(leadId);

    // Handle the log submission
    $('#addLogBtn').on('click', function(e) {
        e.preventDefault();

        var conversation = $('#logCall').val();

        if(conversation.trim() === '') {
            alert('Please enter a conversation.');
            return;
        }

        $.ajax({
            url: '{{ route('store.log') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                lead_id: leadId,
                conversation: conversation
            },
            success: function(response) {
                if (response.success) {
                    $('#logCall').val('');
                    prependLog(response.log.conversation, response.formatted_time, response.relative_time);
                }
            },
            error: function(error) {
                alert('Failed to log conversation.');
            }
        });
    });
    function loadLogs(leadId) {
        $.ajax({
            url: '{{ url("/get-logs") }}/' + leadId,
            type: 'GET',
            success: function(logs) {
                logs.forEach(function(log) {
                    var formattedTime = moment(log.created_at).format('HH:mm:ss D MMM YYYY');
                    var relativeTime = moment(log.created_at).fromNow();
                    prependLog(log.conversation, formattedTime, relativeTime);
                });
            },
            error: function(error) {
                console.log('Failed to load logs.');
            }
        });
    }
    function prependLog(conversation, formattedTime, relativeTime) {
        var logHtml = `
            <div class="card mt-2">
                <div class="card-body">
                    <p class="mb-1">${conversation}</p>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">${formattedTime}</small>
                        <small class="text-muted">${relativeTime}</small>
                    </div>
                </div>
            </div>
        `;
        $('#conversationLogs').prepend(logHtml);
    }
});
$(document).ready(function() {
    var leadId = '{{ $lead->id }}';
    loadTasks(leadId);
    $('#addTaskBtn').on('click', function(e) {
        e.preventDefault();

        var taskMessage = $('#task').val();
        var assignedBy = $('#assignBy').val();

        if (taskMessage.trim() === '' || !assignedBy) {
            alert('Please enter a task and assign it.');
            return;
        }

        $.ajax({
            url: '{{ route("taskstore.task") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                lead_id: leadId,
                assign_by: assignedBy,
                task_message: taskMessage
            },
            success: function(response) {
                if (response.success) {
                    $('#task').val('');
                    prependTask(
                        response.task.task_message,
                        'Pending',
                        response.task.id,
                        response.created_at,
                        response.relative_time,
                        response.assigner_name
                    );
                }
            },
            error: function(error) {
                alert('Failed to create task.');
            }
        });
    });
    function loadTasks(leadId) {
        $.ajax({
            url: '{{ url("/get-tasks") }}/' + leadId,
            type: 'GET',
            success: function(tasks) {
                tasks.forEach(function(task) {
                    prependTask(
                        task.task_message,
                        task.status,
                        task.id,
                        moment(task.created_at).format('HH:mm:ss D MMM YYYY'),
                        moment(task.created_at).fromNow(),
                        task.assigner ? task.assigner.name : 'Unknown'
                    );
                });
            },
            error: function(error) {
                console.log('Failed to load tasks.');
            }
        });
    }
    function prependTask(taskMessage, status, taskId, createdAt, relativeTime, assignerName) {
        function getStatusColorClass(status) {
        switch(status) {
            case 'Pending':
                return 'text-danger'; // Red for Pending
            case 'In Progress':
                return 'text-warning'; // Yellow for In Progress
            case 'Completed':
                return 'text-success'; // Green for Completed
            default:
                return 'text-muted'; // Default color
        }
    }
        var taskHtml = `
            <div class="card mt-2" id="task-${taskId}">
                <div class="card-body">
                    <p class="mb-1">${taskMessage}</p>
                     <div class="d-flex justify-content-start">
                        <small class="text-muted">Assigned To: ${assignerName}</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">${createdAt}</small>
                        <small class="text-muted">${relativeTime}</small>
                        <small class="${getStatusColorClass(status)}">${status}</small>
                    </div>
                    <button 
                    class="btn btn-secondary btn-sm mt-2" 
                    onclick="setTaskIdInModal(${taskId})" 
                    data-toggle="modal" 
                    data-target="#updateTaskModal">
                    Update Status
                </button>
                </div>
            </div>
        `;
        $('#taskLogs').prepend(taskHtml);
    }
});
</script>
<script>
$(document).ready(function() {
    $('#updateTaskForm').on('submit', function(e) {
        e.preventDefault();
        const taskId = $('#taskId').val();
        const status = $('#taskStatus').val();
        $.ajax({
            url: '{{ route("leads-tasks.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                task_id: taskId,
                status: status
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                    $(`#task-${taskId} .task-status`).text(response.updated_status);
                    $('#updateTaskModal').modal('hide');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Failed to update task status. Please try again.');
            }
        });
    });
});
function setTaskIdInModal(taskId) {
    $('#taskId').val(taskId);
}
</script>
<script>
    $(document).ready(function() {
        $('#assignBy').select2();
    });
</script>
@endsection