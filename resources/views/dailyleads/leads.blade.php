@extends('layouts.main')
<style>
/* Full-width container for the progress bar */
.progress-bar-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding: 0 20px;
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
    padding: 10px 10px; /* Reduced padding for smaller height */
    background-color: #f0f0f0;
    color: #333;
    font-weight: bold;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s ease;
    flex: 1;
    border-right: 1px solid white; /* Separator between steps */
    cursor: pointer;
}

/* Left and right arrows for each step */
.step::before,
.step::after {
    content: '';
    position: absolute;
    top: 0;
    width: 0;
    height: 0;
    border-top: 25px solid transparent; /* Reduced height for arrows */
    border-bottom: 25px solid transparent;
}

/* Left arrow (before the step) */
.step::before {
    left: -10px;
    border-right: 10px solid #f0f0f0;
}

/* Right arrow (after the step) */
.step::after {
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

.step:hover::before {
    border-right-color: #007bff;
}

.step:hover::after {
    border-left-color: #007bff;
}

/* Active step (current step) */
.step.active {
    background-color: #007bff;
    color: white;
}

.step.active::before {
    border-right-color: #007bff;
}

.step.active::after {
    border-left-color: #007bff;
}

/* Completed step (marked as done) */
.step.completed {
    background-color: #28a745;
    color: white;
}

.step.completed::before {
    border-right-color: #28a745;
}

.step.completed::after {
    border-left-color: #28a745;
}

/* Disqualified step */
.step.disqualified {
    background-color: #dc3545; /* Red color for disqualified */
    color: white;
}

.step.disqualified::before {
    border-right-color: #dc3545;
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .step {
        padding: 8px 20px;
        font-size: 12px;
    }

    .step::before, .step::after {
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
        <h4 class="card-title">Customer Name</h4>
        <a class="btn btn-sm btn-info" href="{{ url()->previous() }}">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>
<!-- Full-width container for the progress bar -->
<div class="progress-bar-container">
    <div class="steps-container">
        <!-- Step 1: New -->
        <a href="javascript:void(0)" class="step {{ $lead->status === 'new' ? 'active' : ($lead->status !== 'new' ? 'completed' : '') }}" onclick="moveToStep(1)">
            <span class="step-content">{{ $lead->status !== 'new' ? '✔ ' : '' }}New</span>
        </a>
        
        <!-- Step 2: Contacted -->
        <a href="javascript:void(0)" class="step {{ $lead->status === 'contacted' ? 'active' : ($lead->status !== 'new' && $lead->status !== 'contacted' ? 'completed' : '') }}" onclick="moveToStep(2)">
            <span class="step-content">{{ $lead->status !== 'new' && $lead->status !== 'contacted' ? '✔ ' : '' }}Contacted</span>
        </a>

        <!-- Step 3: Working -->
        <a href="javascript:void(0)" class="step {{ $lead->status === 'working' ? 'active' : ($lead->status !== 'new' && $lead->status !== 'contacted' && $lead->status !== 'working' ? 'completed' : '') }}" onclick="moveToStep(3)">
            <span class="step-content">{{ $lead->status === 'working' || in_array($lead->status, ['qualify', 'disqualify', 'converted']) ? '✔ ' : '' }}Working</span>
        </a>
        
        <!-- Step 4: Qualify -->
        <a href="javascript:void(0)" class="step {{ $lead->status === 'qualify' ? 'active' : ($lead->status === 'disqualify' || $lead->status === 'converted' ? 'completed' : '') }}" onclick="moveToStep(4)">
            <span class="step-content">{{ $lead->status === 'qualify' ? '✔ ' : '' }}Qualify</span>
        </a>

        <!-- Step 5: Disqualify -->
        <a href="javascript:void(0)" class="step {{ $lead->status === 'disqualify' ? 'active disqualified' : '' }}" onclick="moveToStep(5)">
            <span class="step-content">{{ $lead->status === 'disqualify' ? '✔ ' : '' }}Disqualify</span>
        </a>

        <!-- Step 6: Converted -->
        <a href="javascript:void(0)" class="step {{ $lead->status === 'converted' ? 'active' : ($lead->status === 'disqualify' ? 'disqualified' : '') }}" onclick="moveToStep(6)">
            <span class="step-content">{{ $lead->status === 'converted' ? '✔ ' : '' }}Converted</span>
        </a>
    </div>

    <!-- Completion Button -->
    <div class="completion-button">
        <button id="completeButton" class="btn btn-primary btn-lg" onclick="markAsDone({{ $lead->id }})" 
            @if($lead->status === 'disqualify') disabled @endif>
            @if($lead->status === 'converted' || $lead->status === 'disqualify') Mark as Done @else Continue @endif
        </button>
    </div>
</div>


    <!-- Lead Details Section -->
    <div class="card-body">
        <div class="row">
            <!-- Lead Details -->
            <div class="col-md-8">
                <h5>Lead Details</h5>
                <table class="table table-striped">
                    <tr>
                        <th>Name</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Company</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td></td>
                    </tr>
                </table>
            </div>

            <!-- Engagement History Section -->
            <div class="col-md-4">
                <h5>Engagement History</h5>
                <ul class="list-group">
                        <li class="list-group-item">
                            <strong>:</strong>
                            <br>
                            <span>List:</span>
                        </li>
                </ul>
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

    // Update button text based on the step
    const button = document.getElementById('completeButton');
    if (currentStep === 6) {
        button.textContent = 'Mark as Done';
    } else {
        button.textContent = 'Continue';
    }
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
@endsection