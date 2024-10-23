@extends('layouts.table')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style>
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
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
@endphp

@if ($hasPermission)
    <!-- Page Header and Lead Title -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Lead</h4>
        <h4 class="card-title">{{$lead->name}}</h4>
        <a class="btn btn-sm btn-info" href="{{ url()->previous() }}">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>
<!-- Full-width container for the progress bar -->
<div class="progress-bar-container row">
    <div class="col-10">
        <div class="steps-container">
            <!-- Step 1: New -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'new' ? 'active' : ($lead->status !== 'new' ? 'completed' : '') }}" onclick="moveToStep(1)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">New</span>
                </span>
            </a>

            <!-- Step 2: Contacted -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'contacted' ? 'active' : ($lead->status !== 'new' && $lead->status !== 'contacted' ? 'completed' : '') }}" onclick="moveToStep(2)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">Contacted</span>
                </span>
            </a>

            <!-- Step 3: Working -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'working' ? 'active' : ($lead->status !== 'new' && $lead->status !== 'contacted' && $lead->status !== 'working' ? 'completed' : '') }}" onclick="moveToStep(3)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">Working</span>
                </span>
            </a>

            <!-- Step 4: Qualify -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'qualify' ? 'active' : ($lead->status === 'disqualify' || $lead->status === 'converted' ? 'completed' : '') }}" onclick="moveToStep(4)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">Qualify</span>
                </span>
            </a>

            <!-- Step 5: Disqualify -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'disqualify' ? 'active disqualified' : '' }}" onclick="moveToStep(5)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">Disqualify</span>
                </span>
            </a>

            <!-- Step 6: Converted -->
            <a href="javascript:void(0)" class="step {{ $lead->status === 'converted' ? 'active' : ($lead->status === 'disqualify' ? 'disqualified' : '') }}" onclick="moveToStep(6)">
                <span class="step-content">
                    <span class="tick-mark">✔</span>
                    <span class="text-step">Converted</span>
                </span>
            </a>
        </div>
    </div>

    <!-- Completion Button -->
    <div class="col-2">
        <div class="completion-button">
            <button id="completeButton" class="btn btn-primary btn-lg" onclick="markAsDone({{ $lead->id }})"
                @if($lead->status === 'disqualify') disabled @endif>
                Mark as Done
            </button>
        </div>
    </div>
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
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Company</th>
                                            <td id="company_name-field" data-field="company_name">{{ $lead->company_name }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Phone</th>
                                            <td id="phone-field" data-field="phone">{{ $lead->phone }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td id="email-field" data-field="email">{{ $lead->email }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
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
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Language</th>
                                            <td id="language-field" data-field="language">{{ $lead->language }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Location</th>
                                            <td id="location-field" data-field="location">{{ $lead->location }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-link edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
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
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm" id="requirement-{{ $requirement->id }}">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">{{ $requirement->masterModelLine->brand->brand_name }}</h5>
                            <p class="card-text text-muted">{{ $requirement->masterModelLine->model_line }}</p>
                            <p class="card-text text-muted"><strong>Trim:</strong> {{ $requirement->model_line }}</p>
                                <p class="card-text text-muted"><strong>Variant:</strong> {{ $requirement->model_line }}</p>
                        </div>
                        <button class="btn btn-danger btn-sm" onclick="removeModelLine({{ $requirement->id }})">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="add-model-line mt-4">
    <h6>Add More Model Line</h6>
    <form id="addModelLineForm">
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">

        <!-- One row for all inputs: Brand, Model Line, Trim, and Variant -->
        <div class="row">
            <!-- Brand input -->
            <div class="col-md-3">
                <label for="brand">Brand</label>
                <select name="brand" class="form-control" id="brand" required>
                    <option value="" disabled selected>Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Model Line input -->
            <div class="col-md-3">
                <label for="model_line">Model Line</label>
                <select name="model_line" class="form-control" id="model_line" required disabled>
                    <option value="" disabled selected>Select Model Line</option>
                </select>
            </div>

            <!-- Trim dropdown -->
            <div class="col-md-3">
                <label for="trim">Trim</label>
                <select name="trim" class="form-control" id="trim" required disabled>
                    <option value="" disabled selected>Select Trim</option>
                </select>
            </div>

            <!-- Variant dropdown -->
            <div class="col-md-3">
                <label for="variant">Variant</label>
                <select name="variant" class="form-control" id="variant" required disabled>
                    <option value="" disabled selected>Select Variant</option>
                </select>
            </div>
            <div class="col-md-3 mt-4">
        <label for="asking_price">Asking Price</label>
        <input type="number" name="asking_price" class="form-control" id="asking_price" placeholder="Enter asking price" required>
    </div>

    <!-- Offer Price input -->
    <div class="col-md-3 mt-4">
        <label for="offer_price">Offer Price</label>
        <input type="number" name="offer_price" class="form-control" id="offer_price" placeholder="Enter offer price" required>
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
</div>
                <!-- Documents & Files tab -->
                <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                    <h5 class="mt-3">Documents & Files</h5>
                    <p>Upload files and add notes related to the lead here.</p>
                </div>
            </div>
        </div>

        <!-- Activity section (Right Section, constant across all tabs) -->
        <div class="col-md-4">
            <h5>Activity</h5>
            <div class="card">
                <div class="card-body">
                    <!-- Nested tabs for Activity and Chatter -->
                    <ul class="nav nav-tabs" id="activityTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="activity-activity-tab" data-toggle="tab" href="#activity-tab-content" role="tab">Activity</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="chatter-tab" data-toggle="tab" href="#chatter-tab-content" role="tab">Chatter</a>
                        </li>
                    </ul>

                    <!-- Nested tab content for Activity and Chatter -->
                    <div class="tab-content" id="activityTabContent">
                        <!-- Activity tab content -->
                        <div class="tab-pane fade show active" id="activity-tab-content" role="tabpanel">
                            <div class="mt-3">
                                <!-- Inner tabs for Log a Call, Task, Event -->
                                <ul class="nav nav-pills" id="activity-inner-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="logcall-tab" data-toggle="pill" href="#logcall-content" role="tab">Log a Call</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="task-tab" data-toggle="pill" href="#task-content" role="tab">Task</a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3" id="activity-inner-tabs-content">
                                    <div class="tab-pane fade show active" id="logcall-content" role="tabpanel">
                                        <div class="form-group">
                                            <label for="logCall">Log a Conversation</label>
                                            <textarea class="form-control" id="logCall" rows="3" placeholder="Recap your call..."></textarea>
                                            <button class="btn btn-primary mt-2">Add</button>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="task-content" role="tabpanel">
                                        <div class="form-group">
                                            <label for="task">Create a Task</label>
                                            <textarea class="form-control" id="task" rows="3" placeholder="Describe your task..."></textarea>
                                            <button class="btn btn-primary mt-2">Add Task</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="chatter-tab-content" role="tabpanel">
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
    </div>
</div>
@else
@php
redirect()->route('home')->send();
@endphp
@endif
<script>
    let currentStep = 1;
    let status = '{{ $lead->status }}';

    // Function to update steps on the page
    function moveToStep(step) {
        // Prevent moving forward if disqualified
        if (status === 'disqualify') {
            return;
        }

        // Set the current step number
        currentStep = step;

        // Get all steps
        const steps = document.querySelectorAll('.step');

        // Reset all steps and mark them as active/completed
        steps.forEach((stepElem, index) => {
            stepElem.classList.remove('completed', 'active', 'disqualified');
            if (index < currentStep - 1) {
                stepElem.classList.add('completed');
            } else if (index === currentStep - 1) {
                stepElem.classList.add('active');
            }
        });

        // Handle if the current step is Disqualify
        if (currentStep === 5) {
            status = 'disqualify'; // Set status to disqualified
            document.querySelector('.step:last-child').classList.add('disqualified'); // Mark Converted as red
            document.getElementById('completeButton').disabled = true; // Disable button
        }
    }

    // Function to send the status update to the server
    function markAsDone(leadId) {

        const steps = document.querySelectorAll('.step');
        let lastCompletedStep = -1;

        steps.forEach((stepElem, index) => {
            if (stepElem.classList.contains('completed')) {
                lastCompletedStep = index;
            }
        });

        if (lastCompletedStep !== -1 && lastCompletedStep + 1 < steps.length) {
            moveToStep(lastCompletedStep + 2); // Move to the next step
        }

        // Determine the new status based on the current step
        let newStatus = '';
        if (currentStep === 1) {
            newStatus = 'new';
        } else if (currentStep === 2) {
            newStatus = 'contacted';
        } else if (currentStep === 3) {
            newStatus = 'working';
        } else if (currentStep === 4) {
            newStatus = 'qualify';
        } else if (currentStep === 5) {
            newStatus = 'disqualify';
        } else if (currentStep === 6) {
            newStatus = 'converted';
        }

        // Send the new status to the server
        fetch(`/leads/${leadId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated to: ' + newStatus);
                    // Optionally reload or refresh the page to reflect changes
                    window.location.reload();
                } else {
                    alert('Failed to update status.');
                }
            });
    }


    document.addEventListener('DOMContentLoaded', () => {
        const steps = document.querySelectorAll('.step');
        let lastCompletedStep = -1;

        steps.forEach((stepElem, index) => {
            if (stepElem.classList.contains('completed')) {
                lastCompletedStep = index;
            }
        });

        if (lastCompletedStep !== -1 && lastCompletedStep + 1 < steps.length) {
            moveToStep(lastCompletedStep + 2);
        }
    });

    // Handle if the current step is Disqualify
    if (currentStep === 5) {
        status = 'disqualify'; // Set status to disqualified
        document.querySelector('.step:last-child').classList.add('disqualified'); // Mark Converted as red
        document.getElementById('completeButton').disabled = true; // Disable button
    }

    // Update button text based on the step
    const button = document.getElementById('completeButton');
    if (currentStep === 6) {
        button.textContent = 'Mark as Done';
    } else {
        button.textContent = 'Continue';
    }
// Function to send the status update to the server
function markAsDone(leadId) {
    // Determine the new status based on the current step
    let newStatus = '';
    if (currentStep === 1) {
        newStatus = 'new';
    } else if (currentStep === 2) {
        newStatus = 'contacted';
    } else if (currentStep === 3) {
        newStatus = 'working';
    } else if (currentStep === 4) {
        newStatus = 'qualify';
    } else if (currentStep === 5) {
        newStatus = 'disqualify';
    } else if (currentStep === 6) {
        newStatus = 'converted';
    }

    // Send the new status to the server
    fetch(`/leads/${leadId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status updated to: ' + newStatus);
            // Optionally reload or refresh the page to reflect changes
            window.location.reload();
        } else {
            alert('Failed to update status.');
        }
    });
}
    </script>
<script>
$(document).ready(function() {
    $('.edit-btn').on('click', function() {
    var td = $(this).closest('tr').find('td').first();
    var field = td.data('field');
    var currentValue = td.text().trim();

    if (td.find('select').length === 0 && td.find('input').length === 0) {
        if (field === 'priority') {
            var dropdown = `
                <select class="form-control">
                    <option value="Hot" ${currentValue === 'Hot' ? 'selected' : ''}>Hot</option>
                    <option value="Normal" ${currentValue === 'Normal' ? 'selected' : ''}>Normal</option>
                    <option value="Low" ${currentValue === 'Low' ? 'selected' : ''}>Low</option>
                    <option value="Regular" ${currentValue === 'Regular' ? 'selected' : ''}>Regular</option>
                    <option value="High" ${currentValue === 'High' ? 'selected' : ''}>High</option>
                </select>`;
            td.html(dropdown);
            $(this).html('<i class="fas fa-save"></i>');
        } else if (field === 'language') {
            var dropdown = '<select class="form-control">';
            @foreach($languages as $language)
            dropdown += `<option value="{{ $language->name }}" ${currentValue === '{{ $language->name }}' ? 'selected' : ''}>{{ $language->name }}</option>`;
            @endforeach
            dropdown += '</select>';
            td.html(dropdown);
            $(this).html('<i class="fas fa-save"></i>');
        } else if (field === 'location') {
            var dropdown = '<select class="form-control">';
            @foreach($countries as $code => $name)
            dropdown += `<option value="{{ $name }}" ${currentValue === '{{ $name }}' ? 'selected' : ''}>{{ $name }}</option>`;
            @endforeach
            dropdown += '</select>';
            td.html(dropdown);
            $(this).html('<i class="fas fa-save"></i>');
        } else {
            var currentValue = td.text().trim();
            td.html('<input type="text" class="form-control" value="' + currentValue + '">');
            $(this).html('<i class="fas fa-save"></i>'); // Change icon to save
        }
    } else {
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
// Handle the form submission to add a new model line
$('#addModelLineForm').submit(function(e) {
    e.preventDefault();

    $.ajax({
        url: '/add-model-line', // Define this route in your controller
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            // Append the new model line to the existing list
            $('#existing-models').append(
                '<div class="model-line" id="requirement-' + response.id + '">' +
                '<span>' + response.brand + ' - ' + response.model_line + '</span>' +
                '<button class="btn btn-danger btn-sm" onclick="removeModelLine(' + response.id + ')">X</button>' +
                '</div>'
            );

            // Clear the form fields
            $('#brand').val('');
            $('#model_line').val('');
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
                }
            });
        }
    });
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
                    $('#variant').html('<option value="" disabled selected>Select Variant</option>');
                    $.each(data.variants, function(index, variant) {
                        $('#variant').append('<option value="' + variant.id + '">' + variant.name + '</option>');
                    });
                }
            });
        }
    });
});
</script>
@endsection