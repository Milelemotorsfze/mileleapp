@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
   .select2-container {
      z-index: 2050; /* Adjust this value as needed */
      width: 100% !important;
    }
    .select2-selection {
      width: 100% !important;
    }
    .modal-dialog {
      max-width: 90%;
    }
    .dataTables_wrapper {
      width: 100%;
    }
.comments-header {
    position: sticky;
    top: 0;
    background-color: #fff;
    z-index: 10;
    border-bottom: 1px solid #dee2e6;
}

.fixed-height {
    height: 280px; /* Adjust the height as needed */
    overflow-y: auto;
    border: 1px solid #dee2e6;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}

.message-card, .message-reply {
    margin-bottom: 1rem;
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
}

.message-card .card-body, .message-reply {
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
.message-input-wrapper, .reply-input-wrapper {
    position: relative;
}

.message-input-wrapper textarea, .reply-input-wrapper textarea {
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
        border: 1px solid black  !important;
    }
    .short-text {
        display: none;
    }
    .upernac {
        margin-top: 1.8rem!important;
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
</style>
@section('content')
<!-- Modal Structure -->
<div class="modal fade" id="addDNModalUnique" tabindex="-1" aria-labelledby="addDNModalUniqueLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDNModalUniqueLabel">Add DN Numbers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dropdown to select Full PO or Individual Vehicles -->
                <div class="mb-3">
                    <label for="dnSelection" class="form-label">Select Option</label>
                    <select id="dnSelection" class="form-select">
                        <option value="full">Full PO</option>
                        <option value="vehicle">Select Vehicles Individually</option>
                    </select>
                </div>

                <!-- Full PO Input Field (Initially Hidden) -->
                <div id="fullPOInput" class="mb-3 d-none">
                    <label for="dnNumberFullPO" class="form-label">DN Number</label>
                    <input type="text" id="dnNumberFullPO" class="form-control">
                </div>

                <!-- Vehicles Table (Initially Hidden) -->
                <div id="vehicleTabledn" class="table-responsive d-none">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Vehicle ID</th>
                                <th>Model</th>
                                <th>Variant</th>
                                <th>VIN</th>
                                <th>DN Number</th>
                            </tr>
                        </thead>
                        <tbody id="vehicleTableBodydn">
                            <!-- Vehicle rows will be inserted here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveDNButton">Save DN Numbers</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Structure -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Initiate Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="paymentForm" enctype="multipart/form-data">
          @csrf <!-- Laravel CSRF token -->
          <input type="hidden" id="transitionId" name="transitionId">
          <div class="mb-3">
            <label for="bank" class="form-label">Select Bank</label>
            <select class="form-select" id="bank" name="bank" onchange="fetchBankAccounts()">
              <!-- Options to be populated dynamically -->
            </select>
          </div>
          <div class="mb-3">
            <label for="bankAccount" class="form-label">Select Bank Account</label>
            <select class="form-select" id="bankAccount" name="bankAccount">
              <!-- Options to be populated dynamically -->
            </select>
          </div>
          <div class="mb-3">
            <label for="file" class="form-label">Upload File</label>
            <input type="file" class="form-control" id="file" name="file">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="submitPaymentForm()">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="purchaseOrderModal" tabindex="-1" role="dialog" aria-labelledby="purchaseOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseOrderModalLabel">Initiate Payments</h5>
                <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-md-3">
                        <div class="form-check form-check-inline">
                            <input type="radio" id="radioPurchasedOrder" name="paymentOption" value="purchasedOrder" class="form-check-input">
                            <label for="radioPurchasedOrder" class="form-check-label">Initiate payment for Purchased Order</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check form-check-inline">
                            <input type="radio" id="radioVehicle" name="paymentOption" value="vehicle" class="form-check-input">
                            <label for="radioVehicle" class="form-check-label">Vehicle</label>
                        </div>
                    </div>
                    <div class="col-md-3" id="paymentAdjustmentContainer">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" id="paymentAdjustmentCheckbox" class="form-check-input">
                            <label for="paymentAdjustmentCheckbox" class="form-check-label">Enter Adjustment Amount</label>
                        </div>
                    </div>
                    <div class="col-md-4" id="paymentAdjustmentInput" style="display: none;">
                        <label for="adjustmentAmount" class="form-check-label">Enter Adjustment Amount:</label>
                        <input type="number" id="adjustmentAmount" name="adjustmentAmount" class="form-control">
                        <p id="adjustmentAmountError" class="text-danger" style="display: none;">Amount cannot exceed the current balance.</p>
                    </div>
                </div>
                <div id="purchasedOrderOptions" style="display: none;">
                    <div class="form-group">
                        <label for="purchasedOrderDropdown">Select Payment Option:</label>
                        <select id="purchasedOrderDropdown" class="form-control">
                            <option value="">Select an option</option>
                            <option value="equalDivided">Equal Divided to All Vehicles</option>
                            <option value="purchasedOrderPayment">Purchased Order Payment</option>
                        </select>
                    </div>
                    <div id="amountInput" class="form-group" style="display: none;">
                        <label for="amount">Enter Amount:</label>
                        <input type="number" id="amount" name="amount" class="form-control">
                        <p id="amountDivisionResult" style="display: none;" class="mt-2"></p>
                    </div>
                </div>
                <br>
                <div id="vehicleOptions" style="display: none;">
                    <div class="form-group">
                        <label for="vehicleDropdown">Select Vehicle Option:</label>
                        <select id="vehicleDropdown" class="form-control">
                            <option value="">Select an option</option>
                            <option value="allVehicles">All Vehicles</option>
                            <option value="oneByOne">One by One</select>
                    </div>
                    <div id="vehicleSelection" class="form-group" style="display: none;">
                        <label for="vehicleSelectDropdown">Select Vehicle:</label>
                        <select id="vehicleSelectDropdown" class="form-control" multiple="multiple">
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                </div>
                <div class="table-responsive" id="vehicleTableContainer" style="display: none;">
                    <table id="vehicleTable" class="table table-striped table-bordered mt-3">
                        <thead class="bg-soft-secondary">
                            <tr>
                                <th>Ref No</th>
                                <th>Brand</th>
                                <th>Model Line</th>
                                <th>Variant</th>
                                <th>VIN</th>
                                <th>Total Price</th>
                                <th>Initiate Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="vehicleTableBody">
                            <!-- Rows will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="saveDetails">Save</button>
                <button type="button" class="btn btn-primary" id="submitPercentage">Submit</button>
            </div>
        </div>
    </div>
</div>
<div id="percentageModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="percentageModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Percentage</h5>
        <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="percentageForm">
          <div class="form-group">
            <label for="percentageSelect">Percentage</label>
            <select class="form-control" id="percentageSelect">
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="submitPercentage">Submit</button>
      </div>
    </div>
  </div>
</div>
<div id="repercentageModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="repercentageModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Percentage</h5>
        <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="percentageForm">
          <div class="form-group">
            <label for="percentageSelect">Percentage</label>
            <select class="form-control" id="repercentageSelect">
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="resubmitPercentage">Submit</button>
      </div>
    </div>
  </div>
</div>
<div id="percentageModalremaining" class="modal" tabindex="-1" role="dialog" aria-labelledby="percentageModalremainingLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Percentage</h5>
        <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="percentageForm">
          <div class="form-group">
            <label for="percentageSelectremaining">Percentage</label>
            <select class="form-control" id="percentageSelectremaining">
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="submitPercentageremaining">Submit</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="vehicleModal" tabindex="-1" role="dialog" aria-labelledby="vehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vehicleModalLabel">Active Vehicles Details</h5>
                <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="savePricesBtn">Update Prices</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="vehicleModalvariant" tabindex="-1" role="dialog" aria-labelledby="vehicleModalvariantLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vehicleModalvariantLabel">Active Vehicles Details</h5>
                <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="savevariantBtn">Update Variant</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Payment Information</h5>
        <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Supplier ID: <span id="supplierId"></span></p>
        <p>Current Amount Status of the Vendor: <span id="currentAmount"></span></p>
        <p>Total Amount: <span id="totalAmount"></span></p>
        <p>Requested Amount: <span id="requestedCost"></span></p>
        
        
        <div class="form-check">
          <input class="form-check-input" type="radio" name="paymentOption" id="adjustmentOption" value="adjustment">
          <label class="form-check-label" for="adjustmentOption">
            Use the amount as adjustment
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="paymentOption" id="payBalanceOption" value="payBalance">
          <label class="form-check-label" for="payBalanceOption">
            Pay the balance with this PO
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="paymentOption" id="partialpayment" value="partialpayment">
          <label class="form-check-label" for="partialpayment">
          Partial payment
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="paymentOption" id="noAdjustmentOption" value="noAdjustment" checked>
          <label class="form-check-label" for="noAdjustmentOption">
            No adjustment
          </label>
        </div>
        <div id="adjustmentInputContainer" style="display:none;">
          <label for="adjustmentAmount" id="adjustmentLabel">Adjustment Amount:</label>
          <input type="number" id="adjustmentAmount" class="form-control" min="0">
        </div>
        <br>
        <p>Initiated / Balance / Exceed Amount / : <span id="remainingAmount"></span></p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirmPaymentButton">Confirm Payment</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="remarksModalLabel">Enter Remarks</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <textarea id="remarks" class="form-control" rows="3"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitRemarks">Submit</button>
      </div>
    </div>
  </div>
</div>
<div id="remarksrejModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Payment Release</h5>
                <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="remarksRejForm">
                    @csrf
                    <input type="hidden" id="vehicleId" name="vehicleId">
                    <div class="form-group">
                        <label for="remarksrej">Remarks</label>
                        <textarea id="remarksrej" name="remarks" class="form-control" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitRemarksrej" class="btn btn-primary">Submit Remarks</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="fileUploadModal" tabindex="-1" role="dialog" aria-labelledby="fileUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileUploadModalLabel">Swift Copy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="fileUploadForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file">Uploading PDF of Swift Copy</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>
                    <br>
                    <input type="hidden" id="status" name="status">
                    <input type="hidden" id="orderId" name="orderId">
                    <button type="submit" class="btn btn-primary">Upload & Complete Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="fileUploadModaladditional" tabindex="-1" role="dialog" aria-labelledby="fileUploadModaladditionalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileUploadModaladditionalLabel">Swift Copy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="fileUploadFormadditional" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file">Uploading PDF of Swift Copy</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>
                    <br>
                    <input type="hidden" id="statusadditional" name="statusadditional" value="">
                    <input type="hidden" id="orderIdadditional" name="orderIdadditional" value="">
                    <button type="submit" class="btn btn-primary">Upload & Complete Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="swiftUploadModal" tabindex="-1" aria-labelledby="swiftUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="swiftUploadModalLabel">Upload Swift File</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="swiftUploadForm" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="transition_id" id="transition_id" value="">
          <div class="form-group">
            <label for="swiftFile">Choose file</label>
            <input type="file" class="form-control-file" id="swiftFile" name="swiftFile" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="uploadSwiftButton">Upload</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="swiftDetailsModal" tabindex="-1" aria-labelledby="swiftDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="swiftDetailsModalLabel">Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="swiftDetailsContent">
          <!-- Swift file details will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <div class="card-header">
        <!-- @if ($previousId)
            <a class="btn btn-sm btn-info" href="{{ route('purchasing-order.show', $previousId) }}">
          <i class="fa fa-arrow-left" aria-hidden="true"></i>
      </a>
      @endif -->
        <b>Purchase Order Number : {{$purchasingOrder->po_number}}</b>
        <a class="btn btn-sm btn-info float-end" href="{{ route('purchasing-order.index') }}">
    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
</a>
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('po-cancel');
                    @endphp
                    @if ($hasPermission)
@php
    $purchasedordergrn = DB::table('vehicles')
    ->where('vehicles.purchasing_order_id', $purchasingOrder->id)
    ->whereNotNull('vehicles.grn_id')
    ->count();
        @endphp      
        @if($purchasedordergrn == 0)
@if($purchasingOrder->status != "Cancel Request" && $purchasingOrder->status != "Cancelled")
<a id="cancelButton" class="btn btn-sm btn-danger float-end me-4" href="{{ route('purchasing_order.cancelpo', ['id' => $purchasingOrder->id]) }}">
    <i class="fa fa-times" aria-hidden="true"></i> PO Cancel
</a>
@endif
@endif
@endif
        <!-- @if ($nextId)
            <a class="btn btn-sm btn-info" href="{{ route('purchasing-order.show', $nextId) }}">
         <i class="fa fa-arrow-right" aria-hidden="true"></i>
      </a>
      @endif -->
    </div>
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Confirm Cancellation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to cancel this PO?</p>
        <div class="form-group">
          <label for="remarkspo">Remarks:</label>
          <textarea class="form-control" id="remarkspo" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="confirmCancel">Yes, Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="confirmationvehModal" tabindex="-1" role="dialog" aria-labelledby="confirmationvehModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationvehModalLabel">Confirm Cancellation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to cancel this Vehicle?</p>
        <div class="form-group">
          <label for="remarkspo">Remarks:</label>
          <textarea class="form-control" id="remarksveh" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="confirmvehCancel">Yes, Send Cancel Request</button>
      </div>
    </div>
  </div>
</div>
    @php
    $exColours = \App\Models\ColorCode::where('belong_to', 'ex')
    ->get(['id', 'name', 'code']) // Fetch the 'id', 'name', and 'code' attributes
    ->mapWithKeys(function ($color) {
        $formattedName = $color->code ? $color->name . ' (' . $color->code . ')' : $color->name;
        return [$color->id => $formattedName];
    })
    ->toArray();
    $intColours = \App\Models\ColorCode::where('belong_to', 'int')
    ->get(['id', 'name', 'code']) // Fetch the 'id', 'name', and 'code' attributes
    ->mapWithKeys(function ($color) {
        // Combine 'name' and 'code' and use 'id' as the key
        $formattedName = $color->code ? $color->name . ' (' . $color->code . ')' : $color->name;
        return [$color->id => $formattedName];
    })
    ->toArray();
    @endphp
    <div class="card-body">
    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                    @endphp
                    @if ($hasPermission)
                    <div class ="row">
                    <div class="col-lg-9 col-md-6 col-sm-6">
    @php
    $purchasedordergrn = DB::table('vehicles')
    ->where('vehicles.purchasing_order_id', $purchasingOrder->id)
    ->whereNotNull('vehicles.grn_id')
    ->count();
        @endphp      
        @if($purchasedordergrn == 0)
                    <a href="#" class="btn btn-sm btn-primary float-end edit-basic-btn" data-purchase-id="{{ $purchasingOrder->id }}">Edit Basic Details</a>
        @endif
                    <a href="#" class="btn btn-sm btn-success float-end update-basic-btn" style="display: none;">Update Basic Details</a>
                    </div>
                    </div>
                    @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger" >
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('error') }}
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('success') }}
            </div>
        @endif
        <div class="card-body">
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="price-update-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="form-update_basicdetails" action="{{ route('purchasing-order.updatebasicdetails') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title fs-5" id="adoncode">Edit Basic Details <span id="addonId"></span></h5>
          <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
    <div class="container">
        <div class="row">
        <div class="col-md-4 p-3">
                <label for="netsuitpo" class="form-label font-size-13 text-center">Netsuit PO:</label>
            </div>
            <div class="col-md-8 p-3">
            <input type="text" id="po_number" name="po_number" class="form-control" placeholder="PO Number" value="{{$purchasingOrder->po_number}}">
            </div>
            <div class="col-md-4 p-3">
                <label for="vendorName" class="form-label font-size-13 text-center">Vendor Name:</label>
            </div>
            <div class="col-md-8 p-3">
            <input type="hidden" id="purchasing_order_id" name="purchasing_order_id" class="form-control" value="{{ $purchasingOrder->id }}">
            <select class="form-control" autofocus name="vendors_id" id="vendors" required>
                <option value="" disabled>Select The Vendor</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ ($vendor->supplier === $vendorsname) ? 'selected' : '' }}>{{ $vendor->supplier }}</option>
                @endforeach
            </select>
            </div>
            <div class="col-md-4 p-3">
                <label for="paymentTerms" class="form-label font-size-13 text-center">Payment Terms:</label>
            </div>
            <div class="col-md-8 p-3">
            <select name="payment_term_id" class="form-select" id="payment_term" required>
                                <option value="" disabled>Select Payment Term</option>
                                @foreach($payments as $payment)
                                    <option value="{{ $payment->id }}"{{ ($payment->id === $paymentterms->id) ? 'selected' : '' }}>{{ $payment->name }}</option>
                                @endforeach
                            </select>
            </div>
            <div class="col-md-4 p-3">
                <label for="currency" class="form-label font-size-13 text-center">Currency:</label>
            </div>
            <div class="col-md-8 p-3">
                <select class="form-control" autofocus name="currency" required>
                <option value="AED" {{ $purchasingOrder->currency === 'AED' ? 'selected' : '' }}>AED</option>
                <option value="USD" {{ $purchasingOrder->currency === 'USD' ? 'selected' : '' }}>USD</option>
                <option value="EUR" {{ $purchasingOrder->currency === 'EUR' ? 'selected' : '' }}>EUR</option>
                <option value="GBP" {{ $purchasingOrder->currency === 'GBP' ? 'selected' : '' }}>GBP</option>
                <option value="JPY" {{ $purchasingOrder->currency === 'JPY' ? 'selected' : '' }}>JPY</option>
                <option value="CAD" {{ $purchasingOrder->currency === 'CAD' ? 'selected' : '' }}>CAD</option>
            </select>
            </div>
            <div class="col-md-4 p-3">
                <label for="shippingMethod" class="form-label font-size-13 text-center">Shipping Method:</label>
            </div>
            <div class="col-md-8 p-3">
            <select class="form-control" id="shippingmethod" name="shippingmethod">
                <option value="EXW" {{ $purchasingOrder->shippingmethod === 'EXW' ? 'selected' : '' }}>EXW</option>
                <option value="CNF" {{ $purchasingOrder->shippingmethod === 'CNF' ? 'selected' : '' }}>CNF</option>
                <option value="CIF" {{ $purchasingOrder->shippingmethod === 'CIF' ? 'selected' : '' }}>CIF</option>
                <option value="FOB" {{ $purchasingOrder->shippingmethod === 'FOB' ? 'selected' : '' }}>FOB</option>
                <option value="Local" {{ $purchasingOrder->shippingmethod === 'Local' ? 'selected' : '' }}>Local</option>
            </select>
            </div>
            <div class="col-md-4 p-3">
                <label for="shippingCost" class="form-label font-size-13 text-center">Shipping Cost:</label>
            </div>
            <div class="col-md-8 p-3">
            <input type="number" id="shippingcost" name="shippingcost" class="form-control" placeholder="Shipping Cost" value="{{$purchasingOrder->shippingcost}}">
            </div>
            <div class="col-md-4 p-3">
            <label for="Incoterm" class="form-label">Port of Loading:</label>
            </div>
            <div class="col-md-8 p-3">
            <select name="pol" class="form-control" id="pol">
                <option value="">Select the Port of Loading</option>
                @foreach ($ports as $port)
                    <option value="{{ $port->id }}" {{ $port->id == $purchasingOrder->pol ? 'selected' : '' }}>
                        {{ $port->name }} - {{ $port->country->name }}
                    </option>
                @endforeach
            </select>
            </div>
            <div class="col-md-4 p-3">
            <label for="Incoterm" class="form-label">Port of Discharge:</label>
            </div>
            <div class="col-md-8 p-3">
            <select name="pod" class="form-control" id="pod">
                <option value="">Select the Port of Loading</option>
                @foreach ($ports as $port)
                    <option value="{{ $port->id }}" {{ $port->id == $purchasingOrder->pod ? 'selected' : '' }}>
                        {{ $port->name }} - {{ $port->country->name }}
                    </option>
                @endforeach
            </select>
            </div>
            <div class="col-md-4 p-3">
            <label for="Incoterm" class="form-label">Preferred Destination:</label>
            </div>
            <div class="col-md-8 p-3">
            <select name="fd" class="form-control" id="fd">
            <option value="">Select the Preferred Destination</option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}" {{ $country->id == $purchasingOrder->fd ? 'selected' : '' }}>
                    {{ $country->name }}
                </option>
            @endforeach
        </select>
            </div>
            <div class="col-md-4 p-3">
                <label for="shippingCost" class="form-label font-size-13 text-center">PFI Number:</label>
            </div>
            <div class="col-md-8 p-3">
            <input type="text" id="pl_number" name="pl_number" class="form-control" placeholder="PFI Number" value="{{$purchasingOrder->pl_number}}">
            </div>
            <div class="col-md-4 p-3">
                <label for="shippingCost" class="form-label font-size-13 text-center">Upload PFI:</label>
            </div>
            <div class="col-md-8 p-3">
            <input type="file" id="uploadPL" name="uploadPL" class="form-control" placeholder="Choose file">
            </div>
        </div>
        </div>
            </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm closeSelPrice" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="submit_b_492" class="btn btn-primary btn-sm createAddonId">Submit</button>
                    </div>
                </div>
                </form>
            </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>PO Type</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{ $purchasingOrder->po_type }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>PO Date</strong></label>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <span>{{date('d-M-Y', strtotime($purchasingOrder->po_date))}}</span>
                        </div>
                    </div>
                    @if($purchasingOrder->is_demand_planning_purchase_order == true)
                        <div class="row">
                            <div class="col-lg-4 col-md-3 col-sm-12">
                                <label for="choices-single-default" class="form-label"><strong>PFI Number</strong></label>
                            </div>
                            <div class="col-lg-6 col-md-3 col-sm-12">
                                <span> {{ $purchasingOrder->LOIPurchasingOrder->approvedLOI->pfi->pfi_reference_number ?? '' }} </span>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Vendor Name</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{ucfirst(strtolower($vendorsname))}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Vendor Account Status</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{ $vendorDisplay }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Total Vehicles / Cost</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{ count($vehicles) }} /  
                            {{ number_format($purchasingOrder->totalcost, 0, '.', ',') }} - {{ $purchasingOrder->currency }}
                        </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Already Paid Amount</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                        <span> {{ number_format($alreadypaidamount, 0, '.', ',') }} - {{ $purchasingOrder->currency }}</span>
                        </div>
                    </div>
                    @if($totalSurcharges)
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Price Increase</strong></label>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                        <div style="background-color: red; color: white; padding: 1px; border-radius: 3px; display: inline-block;">
                        {{ number_format($totalSurcharges, 0, '.', ',') }} - {{ $purchasingOrder->currency }}
                        </div>
                        </div>
                        </div>
                        @endif
                    @if($totalDiscounts)
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Discounts</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">  
                        <div style="background-color: green; color: white; padding: 1px; border-radius: 3px; display: inline-block;">
                        {{ number_format($totalDiscounts, 0, '.', ',') }} - {{ $purchasingOrder->currency }}
                        </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Requested Initiated Amount</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ number_format($intialamount, 0, '.', ',') }} - {{ $purchasingOrder->currency }}</span>
                        </div>
                    </div>
                    @if($vendorPaymentAdjustments)
                    <div class="row">
    <div class="col-lg-4 col-md-3 col-sm-12">
        <label for="choices-single-default" class="form-label"><strong>Requested Released Amount</strong></label>
    </div>
    <div class="col-lg-6 col-md-9 col-sm-12">
        @if ($vendorPaymentAdjustments->isNotEmpty())
            <span>
                @foreach ($vendorPaymentAdjustments as $adjustment)
                {{ number_format($adjustment->total_amount, 0, '.', ',') }} - {{ $purchasingOrder->currency }} 
                        ({{ $adjustment->type }})
                    @if (!$loop->last)
                        , 
                    @endif
                                    @endforeach
                                </span>
                                <strong>Total : {{ number_format($totalSum, 0, '.', ',') }} - {{ $purchasingOrder->currency }} </strong>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Payment Terms</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{ $paymentterms->name }} - {{ $paymentterms->description }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Shipping Method</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{$purchasingOrder->shippingmethod}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Shipping Cost</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{$purchasingOrder->shippingcost}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>POL / POD / PD</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                        <span> {{ $purchasingOrder->polPort->name ?? '' }} / {{ $purchasingOrder->podPort->name ?? '' }} / {{ $purchasingOrder->fdCountry->name ?? '' }}</span>
                        </div>
                    </div>
                    @php
                        $statuses = [
                'Payment Release Approved', 
                'Payment Completed', 
                'Vendor Confirmed', 
                'Incoming Stock'
            ];
                        $purchasingOrderIdforcheck = $purchasingOrder->id;
                        $vehiclesAlreadyPaid = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrderIdforcheck)
                            ->where(function($query) use ($statuses) {
                                $query->whereIn('payment_status', $statuses)
                                    ->where('purchased_paid_percentage', 100)
                                    ->whereNull('remaining_payment_status');
                            })
                            ->first();

                        $vehiclesAlreadyPaidOrRemainingInStatuses = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrderIdforcheck)
                            ->where(function($query) use ($statuses) {
                                $query->whereIn('payment_status', $statuses)
                                    ->where('purchased_paid_percentage', 100)
                                    ->orWhereIn('remaining_payment_status', $statuses);
                            })
                            ->first();
                        @endphp
                        @if (!empty($vehiclesAlreadyPaid) && !empty($vehiclesAlreadyPaidOrRemainingInStatuses))
                    @php
    $additionalpaymentpendFormatted = $additionalpaymentpend > 0 ? number_format($additionalpaymentpend, 0, '', ',') : null;
    $additionalpaymentintFormatted = $additionalpaymentintreq > 0 ? number_format($additionalpaymentintreq, 0, '', ',') : null;
    $additionalpaymentapprovedFormatted = $additionalpaymentint > 0 ? number_format($additionalpaymentint, 0, '', ',') : null;
    $additionalpaymentalreadyappFormatted = $additionalpaymentpapproved > 0 ? number_format($additionalpaymentpapproved, 0, '', ',') : null;
    $payments = array_filter([$additionalpaymentpendFormatted, $additionalpaymentintFormatted, $additionalpaymentapprovedFormatted, $additionalpaymentalreadyappFormatted]);
@endphp
@if(!empty($payments))
    <div class="row">
        <div class="col-lg-4 col-md-3 col-sm-12">
            <label for="choices-single-default" class="form-label">
                <strong>Additional Payment</strong>
            </label>
        </div>
        <div class="col-lg-4 col-md-9 col-sm-12">
            <span>
                @if($additionalpaymentpendFormatted)
                    Pending Request: {{ $additionalpaymentpendFormatted }} - {{ $purchasingOrder->currency }}
                @endif
                @if($additionalpaymentpendFormatted && ($additionalpaymentintFormatted || $additionalpaymentapprovedFormatted))
                </br>
                @endif
                @if($additionalpaymentintFormatted)
                Pending Initiated: {{ $additionalpaymentintFormatted }} - {{ $purchasingOrder->currency }}
                @endif
                @if($additionalpaymentintFormatted && $additionalpaymentapprovedFormatted)
                        </br>
                @endif
                @if($additionalpaymentapprovedFormatted)
                    Pending Released: {{ $additionalpaymentapprovedFormatted }} - {{ $purchasingOrder->currency }}
                @endif
                @if($additionalpaymentalreadyappFormatted)
                Pending Complete: {{ $additionalpaymentalreadyappFormatted }} - {{ $purchasingOrder->currency }}
                @endif
            </span>
        </div>
        @if($additionalpaymentpendFormatted)
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('request-additional-payment');
        @endphp
        @if ($hasPermission)
        <!-- <div class="col-lg-3 col-md-4 col-sm-6">
        <button id="approval-btn" class="btn btn-success btn-sm" onclick="requestForAdditionalPayment({{ $purchasingOrder->id }})">Request For Initiated</button>
        </div> -->
        @endif
        @endif
        @if($additionalpaymentintFormatted)
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('initiated-additional-payment');
        @endphp
        @if ($hasPermission)
        <div class="col-lg-3 col-md-4 col-sm-6">
        <button id="approval-btn" class="btn btn-success btn-sm" onclick="requestForinitiatedPayment({{ $purchasingOrder->id }})">Initiated Payment</button>
        </div>
        @endif
        @endif
        @if($additionalpaymentapprovedFormatted)
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('approved-additional-payment');
        @endphp
        @if ($hasPermission)
        <div class="col-lg-3 col-md-4 col-sm-6">
        <button id="approval-btn" class="btn btn-success btn-sm" onclick="requestForreleasedPayment({{ $purchasingOrder->id }})">Released</button>
        </div>
        @endif
        @endif
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('complete-additional-payment');
        @endphp
        @if ($hasPermission)
        @if($additionalpaymentalreadyappFormatted)
        <div class="col-lg-3 col-md-4 col-sm-6">
        <button id="approval-btn" class="btn btn-success btn-sm" onclick="completedadditionalpayment({{ $purchasingOrder->id }})">Complete Payment</button>
        </div>
        @endif
        @endif
    </div>
@endif
                        @endif
                    @if ($purchasingOrder->pl_number)
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>PFI Number</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                        <span> {{ $purchasingOrder->pl_number ?? '' }}</span>
                        </div>
                    </div>
                    @endif
                    @if ($purchasingOrder->pl_file_path)
<div class="row">
    <div class="col-lg-4 col-md-3 col-sm-12">
        <label for="choices-single-default" class="form-label"><strong>PFI Document</strong></label>
    </div>
    <div class="col-lg-6 col-md-9 col-sm-12">
        <button type="button" class="btn btn-primary btn-sm view-doc-btn" data-toggle="modal" data-target="#viewdocModal">
            <i class="fas fa-file-pdf mr-2"></i> View PFI
        </button>
    </div>
</div>
<div class="modal fade" id="viewdocModal" tabindex="-1" role="dialog" aria-labelledby="viewdocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewdocModalLabel">PL Viewer</h5>
                <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe src="{{ asset($purchasingOrder->pl_file_path) }}" frameborder="0" style="height: 500px;"></iframe>
            </div>
        </div>
    </div>
</div>
@endif

@foreach ($oldPlFiles as $index => $oldPlFile)
<div class="row mt-2">
    <div class="col-lg-4 col-md-3 col-sm-12">
        <label for="choices-single-default" class="form-label"><strong>Old PFI Document {{ $index + 1 }}</strong></label>
    </div>
    <div class="col-lg-6 col-md-9 col-sm-12">
        <button type="button" class="btn btn-secondary btn-sm view-old-doc-btn" data-toggle="modal" data-target="#viewOldDocModal{{ $index }}" data-index="{{ $index }}">
            <i class="fas fa-file-pdf mr-2"></i> View Old PFI {{ $index + 1 }}
        </button>
    </div>
</div>
<div class="modal fade" id="viewOldDocModal{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="viewOldDocModalLabel{{ $index }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewOldDocModalLabel{{ $index }}">Old PL Viewer {{ $index + 1 }}</h5>
                <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe src="{{ asset($oldPlFile->file_path) }}" frameborder="0" style="height: 500px;"></iframe>
            </div>
        </div>
    </div>
</div>
@endforeach

                    @if ($purchasingOrderSwiftCopies->count() > 0)
                    <br>
        <div class="row mb-2">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"><strong>Swift Copy</strong></label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
            @foreach ($purchasingOrderSwiftCopies as $swiftCopy)
                <button type="button" class="btn btn-primary btn-sm view-swift-btn" data-file-path="{{ asset($swiftCopy->file_path) }}">
                    <i class="fas fa-file-pdf mr-2"></i> Swift Copy - {{ $swiftCopy->batch_no }}
                </button>
                @endforeach
            </div>
        </div>
@endif

<!-- Modal -->
<div class="modal fade" id="swiftCopyModal" tabindex="-1" role="dialog" aria-labelledby="swiftCopyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="swiftCopyModalLabel">Swift Copy</h5>
                <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="swiftCopyContainer"></div>
            </div>
        </div>
    </div>
</div>
                    @if($purchasingOrder->status === 'Cancel Request')
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Cancel Remarks</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{$purchasingOrder->remarks}}</span>
                        </div>
                    </div>
                    @else
                    <div class="row">
                    </div>
                    <br>
                    <br>
                    @endif
                </div>
                @php
                           $vehicleIds = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Approved')->whereNull('deleted_at')->pluck('id');
                            $vehiclescancelcount = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->whereNotNull('deleted_at')->count();
                            $vehiclesapprovedcount = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Approved')->whereNull('deleted_at')->count();
                            $vehiclesrejectedcount = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Rejected')->whereNull('deleted_at')->count();
                            $vehiclescountnotapproved = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where(function ($query) {$query->where('status', 'Not Approved')->orWhere('status', 'New Changes');})->whereNull('deleted_at')->count();
                            $vehiclescountpaymentreq = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Payment Requested')->whereNull('deleted_at')->count();
                            $vehiclescountpaymentrej = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Payment Rejected')->whereNull('deleted_at')->count();
                            $vehiclescountpaymentcom = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Payment Completed')->whereNull('deleted_at')->count();
                            $vehiclescountpaymentincom = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Incoming Stock')->whereNull('deleted_at')->count();
                            $vehiclescountrequestpay = DB::table('vehicles_supplier_account_transaction')->whereIn('vehicles_id', $vehicleIds)->where('status', 'pending')->groupBy('vehicles_id')->selectRaw('count(*) as total')->count();
                            $vehiclescountintitail = DB::table('vehicles_supplier_account_transaction')->whereIn('vehicles_id', $vehicleIds)->where('status', 'Request For Payment')->groupBy('vehicles_id')->selectRaw('count(*) as total')->count();
                            $vehiclescountintitailreq = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Initiate Request Rejected')->whereNull('deleted_at')->count();
                            $vehiclescountintitailapp = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Initiate Request Approved')->whereNull('deleted_at')->count();
                            $vehiclescountintitailrelreq = DB::table('vehicles_supplier_account_transaction')->whereIn('vehicles_id', $vehicleIds)->where('status', 'Initiate')->groupBy('vehicles_id')->selectRaw('count(*) as total')->count();
                            $vehiclescountintitailrelapp = DB::table('vehicles_supplier_account_transaction')->whereIn('vehicles_id', $vehicleIds)->where('status', 'Approved')->groupBy('vehicles_id')->selectRaw('count(*) as total')->count();
                            $vehiclescountintitailrelrej = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Release Rejected')->whereNull('deleted_at')->count();
                            $vehiclescountintitailpaycomp = DB::table('vehicles_supplier_account_transaction')->whereIn('vehicles_id', $vehicleIds)->where('status', 'Paid')->groupBy('vehicles_id')->selectRaw('count(*) as total')->count();
                            $vendorpaymentconfirm = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Vendor Confirmed')->whereNull('deleted_at')->count();
                            $vendorpaymentincoming = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Incoming Stock')->whereNull('deleted_at')->count();
                            $vendorandincomingconfirm = $vendorpaymentconfirm + $vendorpaymentincoming ;
                            $serialNumber = 1;
                            $serialNumberpayments = 1;   
                            @endphp
                            <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="comments-header">
                <h6>Comments & Remarks</h6>
            </div>
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
                <div class="col-lg-3 col-md-3 col-sm-12">
                <table id="dtBasicExample90" class="table table-striped table-editable table-edits table table-bordered table-sm">
                        <thead class="bg-soft-secondary" style="background-color: darkred;">
                        <th style="font-size: 12px;">S.No</th>
                        <th style="font-size: 12px;">Status</th>
                        <th style="font-size: 12px;">Qty</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Not Approved Vehicles</td>
                            <td style="font-size: 12px;">{{ $vehiclescountnotapproved }}</td>
                        </tr>
                        <tr> 
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;"> Approved Vehicles</td>
                            <td style="font-size: 12px;">{{ $vehiclesapprovedcount }}</td>
                        </tr>
                        <tr>
                        <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Cancel Vehicles</td>
                            <td style="font-size: 12px;">{{ $vehiclescancelcount }}</td>
                        </tr>
                        </tbody>
                    </table>
                    <table id="dtBasicExample93" class="table table-striped table-editable table-edits table table-bordered table-sm">
                        <thead class="bg-soft-secondary" style="background-color: darkred;">
                        <th style="font-size: 12px;">S.No</th>
                        <th style="font-size: 12px;">Status</th>
                        <th style="font-size: 12px;">Qty</th>
                        <th style="font-size: 12px;">Pending With Department</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumberpayments++ }}</td>
                            <td style="font-size: 12px;">Payment Initiation Requested</td>
                            <td style="font-size: 12px;">{{ $vehiclescountrequestpay }}</td>
                            <td style="font-size: 12px;">Procurement Manager</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumberpayments++ }}</td>
                            <td style="font-size: 12px;">Payment Initiated Request</td>
                            <td style="font-size: 12px;">{{ $vehiclescountintitail }}</td>
                            <td style="font-size: 12px;">Finance</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumberpayments++ }}</td>
                            <td style="font-size: 12px;">Payment Initiated</td>
                            <td style="font-size: 12px;">{{ $vehiclescountintitailrelreq }}</td>
                            <td style="font-size: 12px;">CEO Office</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumberpayments++ }}</td>
                            <td style="font-size: 12px;">Payment Released</td>
                            <td style="font-size: 12px;">{{ $vehiclescountintitailrelapp }}</td>
                            <td style="font-size: 12px;">Finance</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumberpayments++ }}</td>
                            <td style="font-size: 12px;">Payment Release Rejected</td>
                            <td style="font-size: 12px;">{{ $vehiclescountintitailrelrej }}</td>
                            <td style="font-size: 12px;">Procurement</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumberpayments++ }}</td>
                            <td style="font-size: 12px;">Payment Acknowledged</td>
                            <td style="font-size: 12px;">{{ $vehiclescountintitailpaycomp }}</td>
                            <td style="font-size: 12px;">Procurement</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                        <div class="col-lg-1 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>PO Status</strong></label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            @if ($purchasingOrder->status === 'Pending Approval')
                                <span id="status-badge" class="badge badge-soft-info float-middle badge-large">Not Approved</span>
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('delete-po-details');
                                @endphp
                                @if ($hasPermission)
                                    <button id="rejection-btn" class="btn btn-danger" onclick="deletepo({{ $purchasingOrder->id }})">Delete</button>
                                @endif
                            @endif
                            @if ($purchasingOrder->status === 'Approved')
                                <span id="status-badge" class="badge badge-soft-success float-middle badge-large">Approved</span>
                            @elseif ($purchasingOrder->status === 'Rejected')
                                <span id="status-badge" class="badge badge-soft-danger float-middle badge-large">Rejected</span>
                                @elseif ($purchasingOrder->status === 'Cancel Request')
                                <span id="status-badge" class="badge badge-soft-danger float-middle badge-middle">Cancel Request</span>
                                @elseif ($purchasingOrder->status === 'Cancelled')
                                <span id="status-badge" class="badge badge-soft-danger float-middle badge-large">Cancelled</span>
                            @endif
                            @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('po-approval');
                            @endphp
                            @if ($hasPermission)
                                @if ($purchasingOrder->status === 'Pending Approval')
                                    <button id="approval-btn" class="btn btn-success" onclick="updateStatus('Approved', {{ $purchasingOrder->id }})">Approve</button>
                                    <button id="rejection-btn" class="btn btn-danger" onclick="updateStatus('Rejected', {{ $purchasingOrder->id }})">Reject</button>
                                    @elseif ($purchasingOrder->status === 'Cancel Request')
                                    <button id="approvalrej-btn" class="btn btn-success" onclick="updateStatusrej('Approved', {{ $purchasingOrder->id }})">Approve</button>
                                    <button id="rejectionrej-btn" class="btn btn-danger" onclick="updateStatusrej('Rejected', {{ $purchasingOrder->id }})">Reject</button>
                                @endif
                            @endif
                        </div>
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-request-approval');
                        @endphp
                        @if ($hasPermission)
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('status', 'Request for Payment'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Forward Payment Request</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                    <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfin('Approved', {{ $purchasingOrder->id }})">Approval</button>
                                    <button id="allpaymentintreqfinreq-btn" class="btn btn-danger" onclick="allpaymentintreqfin('Rejected', {{ $purchasingOrder->id }})">Reject</button>
                                    </div>
                                @endif
                            @endif
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('remaining_payment_status', 'Request for Payment'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Forward Reamining Payment Request</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                    <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfinremaing('Approved', {{ $purchasingOrder->id }})">Approval</button>
                                    <button id="allpaymentintreqfinreq-btn" class="btn btn-danger" onclick="allpaymentintreqfinremaing('Rejected', {{ $purchasingOrder->id }})">Reject</button>
                                    </div>
                                @endif
                            @endif
                            @endif
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-initiated');
                        @endphp
                        @if ($hasPermission)
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('payment_status', 'Payment Initiated Request'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Payment Initiation Request</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                    <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfinpay('Approved', {{ $purchasingOrder->id }})">Approval All</button>
                                    </div>
                                @endif
                            @endif
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('remaining_payment_status', 'Payment Requested'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Remaining Payment Initiation Request</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                    <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfinpay('Approved', {{ $purchasingOrder->id }})">Approval All</button>
                                    </div>
                                @endif
                            @endif
                            @endif
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-release-approval');
                        @endphp
                        @if ($hasPermission)
    @if ($purchasingOrder->status === 'Approved')
        @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) 
            && ($vehicles->contains('payment_status', 'Payment Initiated') 
                || $vehicles->contains('remaining_payment_status', 'Payment Initiated')))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Payment Release Request</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <button id="approval-btn" class="btn btn-success" onclick="updateallStatusrel('Approved', {{ $purchasingOrder->id }})">Approve All</button>
                                        <button id="rejection-btn" class="btn btn-danger" onclick="updateallStatusrel('Rejected', {{ $purchasingOrder->id }})">Reject All</button>
                                    </div>
                                @endif
                            @endif
                            @endif
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                        @endphp
                        @if ($hasPermission)
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('status', 'Approved'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Initiate Payment Request</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                    <button id="approval-btn" class="btn btn-success" onclick="openPurchaseOrderModal({{ $purchasingOrder->id }})">Request for All</button>
                                    </div>
                                @endif
                            @endif
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('status', 'Payment Completed'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Vendor Confirmed & Incoming</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqpocomp('Approved', {{ $purchasingOrder->id }})">Confirmed All</button>
                                    </div>
                                @endif
                            @endif
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('status', 'Vendor Confirmed'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Incoming Stock</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqpocompin('Approved', {{ $purchasingOrder->id }})">Incoming All</button>
                                    </div>
                                @endif
                            @endif
                        @endif
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('re-payment-request');
                        @endphp
                        @if ($hasPermission)
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('payment_status', 'Payment Release Rejected'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Re-Request for Payment</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <button id="approval-btn" class="btn btn-success" onclick="rerequestpayment('Approved', {{ $purchasingOrder->id }})">Re-Request All</button>
                                    </div>
                                @endif
                            @endif
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('payment_status', 'Payment Initiate Request Approved'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Initiate Payment</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfinpay('Approved', {{ $purchasingOrder->id }})">Initiatie for All</button>
                                    </div>
                                @endif
                            @endif
                        @endif
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-initiated');
                        @endphp
                        @if ($hasPermission)
                        @if ($purchasingOrder->status === 'Approved')
                            @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && 
                                ($vehicles->contains('payment_status', 'Payment Release Approved') || 
                                $vehicles->contains('remaining_payment_status', 'Payment Release Approved')))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Payment Completed</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfinpaycomp({{ $purchasingOrder->id }})">Complete All Payments</button>
                                    </div>
                                @endif
                            @endif
                            @endif
                    </div>
                    <br>
                    <br>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Active Vehicle's Details</h4>
                    <div id="flash-message" class="alert alert-success" style="display: none;"></div>
                    @if($purchasingOrder->status != "Cancelled")
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                    @endphp
                    @if ($hasPermission)
                    <a href="#" class="btn btn-sm btn-primary float-end edit-btn me-2">Edit</a>
                    <a href="#" class="btn btn-sm btn-success float-end update-btn me-2" style="display: none;">Update</a>
                    @endif
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('update-po-price');
                    @endphp
                    @if ($hasPermission)
                    <a href="#" class="btn btn-sm btn-primary float-left updateprice-btn me-2" data-id="{{ $purchasingOrder->id }}">Price Update</a>
                    @endif
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('update-variant-vehicles');
                    @endphp
                    @if ($hasPermission)
                    <a href="#" class="btn btn-sm btn-primary float-left updatevariant-btn me-2" data-id="{{ $purchasingOrder->id }}">Variant Update</a>
                    @endif
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive" >
                        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
                            <thead class="bg-soft-secondary">
                            <tr>
                                <th id="serno" style="vertical-align: middle;">Ref No:</th>
                                @can('view-vehicle-model-sfx')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-vehicle-model-sfx');
                                    @endphp
                                    @if ($hasPermission)
                                        <th>Model - SFX</th>
                                   @endif
                                @endcan

                                <th>Brand</th>
                                <th>Model Line</th>
                                <th>Variant</th>
                                <th id="variants_detail">Variants Detail</th>
                                <th>Price</th>
                                <th style="vertical-align: middle;" id="int_color">Exterior Color</th>
                                <th style="vertical-align: middle;" id="ex_color">Interior Color</th>
                                <th>Engine Number</th>
                                <th>VIN Number</th>
                                <th>Territory</th>
                                <th style="vertical-align: middle;" id="estimated">Estimated Arrival</th>
                                <th>Production Date</th>
                                <th id="serno" style="vertical-align: middle;">Vehicle Status:</th>
                                <!-- @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-po-payment-details', 'po-approval', 'edit-po-colour-details', 'cancel-vehicle-purchased-order']);
                                @endphp
                                @if ($hasPermission)
                                    <th>Payment Status</th>
                                @endif -->
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['po-approval', 'edit-po-colour-details']);
                                @endphp
                                @if ($hasPermission)
                                    <th id="action" style="vertical-align: middle; text-align: center;">Action</th>
                                @endif
                                <th style="vertical-align: middle; text-align: center;">Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehicles as $vehicles)
                                <tr>
{{--                                @php--}}
{{--                            $variant = DB::table('varaints')->where('id', $vehicles->varaints_id)->first();--}}
{{--                            $name = $variant->name;--}}
{{--                            $exColour = $vehicles->ex_colour ? DB::table('color_codes')->where('id', $vehicles->ex_colour)->first() : null;--}}
{{--                            $ex_colours = $exColour ? $exColour->name : null;--}}
{{--                            $intColour = $vehicles->int_colour ? DB::table('color_codes')->where('id', $vehicles->int_colour)->first() : null;--}}
{{--                            $int_colours = $intColour ? $intColour->name : null;--}}
{{--                            $detail = $variant->detail;--}}
{{--                            $brands_id = $variant->brands_id;--}}
{{--                            $master_model_lines_id = $variant->master_model_lines_id;--}}
{{--                            $brand = DB::table('brands')->where('id', $brands_id)->first();--}}
{{--                            $brand_names = $brand->brand_name;--}}
{{--                            $master_model_lines_ids = DB::table('master_model_lines')->where('id', $master_model_lines_id)->first();--}}
{{--                            $model_line = $master_model_lines_ids->model_line;--}}
{{--                            @endphp--}}
                            <td>{{ $vehicles->id }}</td>
                            @can('view-vehicle-model-sfx')
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-vehicle-model-sfx');
                                @endphp
                                @if ($hasPermission)
                                    <td>
                                        @if($vehicles->model_id)
                                            {{ $vehicles->masterModel->model ?? ''  }} - {{ $vehicles->masterModel->sfx ?? '' }}
                                        @endif
                                    </td>
                                @endif
                            @endcan
                            <td>{{ ucfirst(strtolower($vehicles->variant->brand->brand_name)) }}</td>
                            <td>{{ ucfirst(strtolower($vehicles->variant->master_model_lines->model_line)) }}</td>
                            <td>{{ ucfirst($vehicles->variant->name) }}</td>
                            @php
                        $words = explode(' ', ucfirst(strtolower($vehicles->variant->detail)));
                        $shortDetail = implode(' ', array_slice($words, 0, 3));
                        $remainingDetail = implode(' ', array_slice($words, 3));
                        @endphp
                        <td>
                            <span class="short-detail">{{ $shortDetail }}</span>
                            @if(count($words) > 5)
                            <span class="remaining-detail" style="display:none;">{{ $remainingDetail }}</span>
                            <a href="javascript:void(0);" class="read-more" data-full-detail="{{ ucfirst(strtolower($vehicles->variant->detail)) }}">Read more</a>
                            @endif
                        </td>
                        <td>{{ number_format($vehicles->VehiclePurchasingCost->unit_price, 0, '.', ',') }}</td>
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                            @endphp
                            @if ($hasPermission)
                            @if($vehicles->grn_id === null)
							@if ($vehicles->status != 'cancel')
                            <td class="editable-field ex_colour" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                <select name="ex_colour[]" class="form-control" placeholder="Exterior Color" disabled>
                                    <option value="">Exterior Color</option>
                                    @foreach ($exColours as $id => $exColour)
                                        @if ($id == $vehicles->ex_colour)
                                            <option value="{{ $id }}" selected>{{ $exColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $exColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td class="editable-field int_colour" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                <select name="int_colour[]" class="form-control" placeholder="Interior Color" disabled>
                                    <option value="">Interior Color</option>
                                    @foreach ($intColours as $id => $intColour)
                                        @if ($id == $vehicles->int_colour)
                                            <option value="{{ $id }}" selected>{{ $intColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $intColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td class="editable-field engine" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->engine }}</td>
                            <td class="editable-field vin" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->vin }}</td>
                            <td class="editable-field territory" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ ucfirst(strtolower($vehicles->territory)) }}</td>
                            <td class="editable-field estimation_date" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->estimation_date }}</td>
                            <td class="editable-field ppmmyyy" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->ppmmyyy }}</td>
                            @else
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                <select name="ex_colour[]" class="form-control" placeholder="Exterior Color" disabled>
                                    <option value="">Exterior Color</option>
                                    @foreach ($exColours as $id => $exColour)
                                        @if ($id == $vehicles->ex_colour)
                                            <option value="{{ $id }}" selected>{{ $exColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $exColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                <select name="int_colour[]" class="form-control" placeholder="Interior Color" disabled>
                                    <option value="">Interior Color</option>
                                    @foreach ($intColours as $id => $intColour)
                                        @if ($id == $vehicles->int_colour)
                                            <option value="{{ $id }}" selected>{{ $intColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $intColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->engine }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->vin }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ ucfirst(strtolower($vehicles->territory)) }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->estimation_date }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->ppmmyyy }}</td>
                            @endif
                            @else
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                <select name="ex_colour[]" class="form-control" placeholder="Exterior Color" disabled>
                                    <option value="">Exterior Color</option>
                                    @foreach ($exColours as $id => $exColour)
                                        @if ($id == $vehicles->ex_colour)
                                            <option value="{{ $id }}" selected>{{ $exColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $exColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                <select name="int_colour[]" class="form-control" placeholder="Interior Color" disabled>
                                    <option value="">Interior Color</option>
                                    @foreach ($intColours as $id => $intColour)
                                        @if ($id == $vehicles->int_colour)
                                            <option value="{{ $id }}" selected>{{ $intColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $intColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->engine }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->vin }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ ucfirst(strtolower($vehicles->territory)) }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->estimation_date }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->ppmmyyy }}</td>
							@endif
                            @endif
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-po-payment-details', 'po-approval']);
                            @endphp
                            @if ($hasPermission)
                            <td>
                                <select name="ex_colour[]" class="form-control" placeholder="Exterior Color" disabled>
                                    <option value="">Exterior Color</option>
                                    @foreach ($exColours as $id => $exColour)
                                        @if ($id == $vehicles->ex_colour)
                                            <option value="{{ $id }}" selected>{{ $exColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $exColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="int_colour[]" class="form-control" placeholder="Interior Color" disabled>
                                    <option value="">Interior Color</option>
                                    @foreach ($intColours as $id => $intColour)
                                        @if ($id == $vehicles->int_colour)
                                            <option value="{{ $id }}" selected>{{ $intColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $intColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>{{ $vehicles->engine }}</td>
                            <td>{{ $vehicles->vin }}</td>
                            <td>{{ ucfirst(strtolower($vehicles->territory)) }}</td>
                                <td>{{ $vehicles->estimation_date }}</td>
                                <td>{{ $vehicles->ppmmyyy }}</td>
                            @endif
                            <td>
                            @if($vehicles->status ==="Approved")
                            {{ ucfirst(strtolower( $vehicles->status)) }}
                            @elseif($vehicles->status ==="New Changes")
                            {{ ucfirst(strtolower( $vehicles->status)) }}
                            @elseif($vehicles->status ==="New Vehicles")
                            {{ ucfirst(strtolower( $vehicles->status)) }}
                            @elseif($vehicles->status ==="Rejected")
                            {{ ucfirst(strtolower( $vehicles->status)) }}
                            @else
                            {{ ucfirst(strtolower( $vehicles->status)) }}
                            @endif
                            </td>
                            <!-- @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-po-payment-details', 'po-approval', 'edit-po-colour-details']);
                            @endphp
                            @if ($hasPermission)
                            <td>
    @if($vehicles->purchased_paid_percentage != 100 && $vehicles->purchased_paid_percentage != "")
        {{ 'Partial Payment' }}
    @elseif($vehicles->purchased_paid_percentage == 100)
        @if(!$vehicles->remaining_payment_status)
            {{ ucfirst(strtolower($vehicles->payment_status)) }}
        @else
            {{ 'Partial Payment' }}
        @endif
    @else
        {{ ucfirst(strtolower($vehicles->payment_status)) }}
    @endif
    
    @if($vehicles->purchased_paid_percentage)
        ({{ $vehicles->purchased_paid_percentage }}%)
    @endif
</td>

                                @endif -->
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['po-approval', 'edit-po-colour-details']);
                                @endphp
                                @if ($hasPermission)
                                <td style ="width:160px;">
                                <div class="row">
                                <div class="col-lg-12" style="display: inline-flex;">
                                <div class="col-lg-8">
								@if ($vehicles->status === 'Not Approved')
                                <a class='holdButtonveh btn btn-sm btn-secondary' title="Cancel" data-placement="top" class="btn btn-sm btn-danger" href="javascript:void(0);" style="white-space: nowrap; margin-bottom: 10px;" data-url="{{ route('vehicles.hold', $vehicles->id) }}" data-status="hold">
                            Hold
                            </a>
                            @elseif($vehicles->status === 'Hold')
                            <a class='holdButtonveh btn btn-sm btn-secondary' title="Cancel" data-placement="top" class="btn btn-sm btn-danger" href="javascript:void(0);" style="white-space: nowrap; margin-bottom: 10px;" data-url="{{ route('vehicles.hold', $vehicles->id) }}" data-status="unhold">
                                                Un-Hold
                                            </a>
                            @endif
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('po-approval');
                        @endphp
                        @if ($hasPermission)
                        @if($vehicles->status == 'Request for Cancel' && is_null($vehicles->grn_id))
                        <a title="Reject" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.approvedcancel', ['id' => $vehicles->id]) }}" style="white-space: nowrap;">
                            Approved Cancel
                        </a>
                        @elseif ($vehicles->status != 'Rejected' && $vehicles->status != 'Request for Payment' && is_null($vehicles->grn_id))
                        <form id="cancel-form-{{ $vehicles->id }}" action="{{ route('vehicles.cancel', $vehicles->id) }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                        <a class='cancelButtonveh btn btn-sm btn-danger' title="Cancel" data-placement="top" class="btn btn-sm btn-danger" href="javascript:void(0);" style="white-space: nowrap;" data-url="{{ route('vehicles.cancel', $vehicles->id) }}">
                            Reject / Cancel
                        </a>
                        @elseif ($vehicles->status == 'Rejected')
                        <a title="UnReject" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.unrejecteds', $vehicles->id) }}" onclick="return confirmunRejected();" style="white-space: nowrap;">
                            Un-Reject
                        </a>
                        @endif
                        @endif
                        <!-- @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-release-approval');
                        @endphp
                        @if ($hasPermission)
                        @if ($vehicles->payment_status === 'Payment Initiated')
                        <div style="display: flex; gap: 10px;">
                        <a title="Payment Release Approved" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentreleasesconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px;">
                        Approved
                        </a>
                        <button data-placement="top" class="btn btn-sm btn-danger" onclick="return openModal('{{ $vehicles->id }}');" style="margin-right: 10px;">Reject</button>
                        </div>
                        @endif
                        @endif -->
											<!-- @php
											$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
											@endphp
											@if ($hasPermission)
											@if ($purchasingOrder->status === 'Approved')
											@if ($vehicles->payment_status === 'Vendor Confirmed')
											<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentrelconfirmincoming', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Incoming Confirmed
											</a>
                                            @endif
											@endif
											@endif -->
											<!-- @php
											$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
											@endphp
											@if ($hasPermission)
											@if ($purchasingOrder->status === 'Approved')
											@if ($vehicles->payment_status === 'Payment Completed')
											<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentrelconfirmvendors', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Incoming Confirmed
											</a>
                                           @endif
											@endif
											@endif -->
                                        <!-- @php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
										@endphp
										@if ($hasPermission)
										@if ($purchasingOrder->status === 'Approved')
										@if ($vehicles->payment_status != '' && $vehicles->purchased_paid_percentage != 100 && $vehicles->remaining_payment_status != 'Request for Payment')
										<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentremanings', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Initiate Remainings
										</a>
										@endif
										@endif
										@endif -->
                            <!-- @if ($vehicles->payment_status === 'Payment Initiated Request')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-initiated');
                        @endphp
                        @if ($hasPermission)
                        <div style="display: flex; gap: 10px;">
                       <a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentrelconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
                        Approved
                        </a>
                        @endif
                        @endif -->
                                            <!-- @php
											$hasPermission = Auth::user()->hasPermissionForSelectedRole('re-payment-request');
											@endphp
											@if ($hasPermission)
											@if ($purchasingOrder->status === 'Approved')
											@if ($vehicles->payment_status === 'Payment Release Rejected')
											<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.repaymentintiation', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Re-Payment Request
											</a>
											@endif
											@endif
											@endif -->
								<!-- @php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-payment-details');
								@endphp
								@if ($hasPermission)
								@if ($purchasingOrder->status === 'Approved')
								@if ($vehicles->payment_status === 'Payment Initiate Request Approved')
								<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentrelconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
								Payment Initiated
								</a>
								@endif
								@endif
								@endif -->
									<!-- @php
									$hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-initiated');
									@endphp
									@if ($hasPermission)
									@if ($purchasingOrder->status === 'Approved')
									@if ($vehicles->payment_status === 'Payment Release Approved')
                                    <div class="modal fade" id="fileUploadModalsingle" tabindex="-1" role="dialog" aria-labelledby="fileUploadModalsingleLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="paymentForm" action="{{ route('vehicles.paymentrelconfirmdebited', 0) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="fileUploadModalsingleLabel">Swift Copy</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="paymentFile">Upload File</label>
            <input type="file" class="form-control" id="paymentFile" name="paymentFile" required>
          </div>
          <input type="hidden" id="vehicleId" name="vehicleId" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div> -->
                                        <!-- File Upload Modal -->
                                        <!-- <div class="modal fade" id="fileUploadModalsingle" tabindex="-1" role="dialog" aria-labelledby="fileUploadModalsingleLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                            <form id="paymentForm" action="{{ route('vehicles.paymentrelconfirmdebited', 0) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="fileUploadModalsingleLabel">Upload Payment Confirmation</h5>
                                                <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="paymentFile">Upload File</label>
                                                    <input type="file" class="form-control" id="paymentFile" name="paymentFile" required>
                                                </div>
                                                <input type="hidden" id="vehicleId" name="vehicleId" value="">
                                                </div>
                                                <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                        </div> -->
                                    <!-- <a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="#" onclick="allpaymentintreqfinpaycompsingle({{ $vehicles->id }});" style="margin-right: 10px; white-space: nowrap;">
    Payment Completed
</a> -->
									<!-- <a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentrelconfirmdebited', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
									Payment Completed
									</a> -->
									<!-- @endif
									@endif
									@endif
									</div> -->
                            <div class="col-lg-4">
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole('cancel-vehicle-purchased-order');
								@endphp
								@if ($hasPermission)
								@if ($purchasingOrder->status === 'Approved'  || $purchasingOrder->status === 'Pending Approval' && $vehicles->payment_status === '')
                                @if($vehicles->status !== "Request for Cancel" && is_null($vehicles->grn_id))
								<form id="cancel-form-{{ $vehicles->id }}" action="{{ route('vehicles.cancel', $vehicles->id) }}" method="POST" style="display:none;">
                                    @csrf
                                </form>
                                <a class='cancelButtonveh btn btn-sm btn-danger' title="Cancel" data-placement="top" class="btn btn-sm btn-danger" href="javascript:void(0);" style="white-space: nowrap;" data-url="{{ route('vehicles.cancel', $vehicles->id) }}">
                                    Cancel
                                </a>
                                @endif
								@elseif ($vehicles->status === 'Pending Approval' && is_null($vehicles->grn_id))
								<a title="Delete" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.deletevehicles', $vehicles->id) }}" onclick="return confirmDelete();" style="white-space: nowrap;">
									Delete
								</a>
								@endif
								@endif
                        </div>
                        </div>
							</div>
                        </td>
                        @endif
                        <td>
                        <!-- @if ($vehicles->payment_status != '' && $vehicles->purchased_paid_percentage != 100 && $vehicles->remaining_payment_status === 'Request for Payment')
						Remainings Payment Requested
						@endif -->
                            {{ ucfirst(strtolower($vehicles->procurement_vehicle_remarks)) }}</td>
                        </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if($purchasingOrder->status != "Cancelled")
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('add-more-vehicles-po');
            @endphp
            @if ($hasPermission)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add More Vehicles</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::model($purchasingOrder, ['route' => ['purchasing-order.update', $purchasingOrder->id], 'method' => 'PATCH', 'id' => 'purchasing-order']) !!}
                        <div id="variantRowsContainer" style="display: none;">
                            <div class="table-responsive" >
                                <table id="dtBasicExampledata" class="table table-striped table-editable table-edits table table-bordered">
                                    <thead class="bg-soft-secondary">
                                    <tr >
                                        <th>Variants</th>
                                        <th>Brand</th>
                                        <th>Model Line</th>
                                        <th>Exterior Color</th>
                                        <th>Interior Color</th>
                                        <th>Unit Price</th>
                                        <th>Estimated Arrival</th>
                                        <th>Engine Number</th>
                                        <th>VIN</th>
                                        <th>Territory</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6">
                                <label for="brandInput" class="form-label">Variants:</label>
                                <input type="text" placeholder="Select Variants" name="variant_ider[]" list="variantslist" class="form-control mb-1" id="variants_id" autocomplete="off">
                                <datalist id="variantslist">
                                    @foreach ($variants as $variant)
                                        <option value="{{ $variant->name }}" data-value="{{ $variant->id }}" data-detail="{{ $variant->detail }}" data-brands_id="{{ $variant->brand_name }}" data-master_model_lines_id="{{ $variant->model_line }}">{{ $variant->name }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="QTY" class="form-label">Brand:</label>
                                <input type="text" id="brands_id" name="brands_id" class="form-control" placeholder="Brand" readonly>
                                <input type="hidden" id="currency" name="currency" class="form-control" readonly value="{{$purchasingOrder->currency}}">
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label for="QTY" class="form-label">Model Line:</label>
                                <input type="text" id="master_model_lines_id" name="master_model_lines_id" class="form-control" placeholder="Model Line" readonly>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <label for="QTY" class="form-label">Variants Detail:</label>
                                <input type="text" id="details" name="details" class="form-control" placeholder="Variants Detail" readonly>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="unitprice" class="form-label">Unit Price:</label>
                                <input type="number" id="unit_price" name="unit_price" class="form-control" placeholder="Unit Price">
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="QTY" class="form-label">QTY:</label>
                                <input type="number" id="QTY" name="QTY" class="form-control" placeholder="QTY">
                            </div>
                            <div class="col-lg-1 col-md-6 upernac">
                                <div class="btn btn-primary add-row-btn">
                                    <i class="fas fa-plus"></i> Add More
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="col-lg-12 col-md-12">
                            <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" id="submit-button"/>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            @endif

            @can('edit-demand-planning-po')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-demand-planning-po');
                @endphp
                @if ($hasPermission)
{{--                    @if($variantCount > 0)--}}
                       @include('purchase-order.po_add_vehicles')
{{--                    @endif--}}
                @endif
            @endcan
            @endif
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cancel Vehicle's Details</h4>
                </div>
                <div class="card-body">
                <ul class="nav nav-tabs" id="vehicleTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="cancel-vehicle-tab" data-bs-toggle="tab" href="#cancel-vehicle" role="tab" aria-controls="cancel-vehicle" aria-selected="true">Cancel Vehicle's Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="dn-vehicle-tab" data-bs-toggle="tab" href="#dn-vehicle" role="tab" aria-controls="dn-vehicle" aria-selected="false">DN Vehicles</a>
            </li>
        </ul>
<!-- Modal Structure -->
        <div class="tab-content mt-3">
            <!-- Cancel Vehicle's Details Tab -->
            <div class="tab-pane fade show active" id="cancel-vehicle" role="tabpanel" aria-labelledby="cancel-vehicle-tab">
                    <div class="table-responsive" >
                        <table id="dtBasicExample5" class="table table-striped table-editable table-edits table table-bordered">
                            <thead class="bg-soft-secondary">
                            <tr>  
                            <th>Ref No</th>
                            <th>Brand</th>
                            <th>Model Line</th>
                            <th>Variant</th>
                            <th>Variants Detail</th>
                            <th>Price</th>
                            <th>Exterior Colour</th>
                            <th>Interior Colour</th>
                            <th>VIN Number</th>
                            <th>Territory</th>
                            <th>Estimated Arrival</th>
                            <th>Payment Status</th>
                            <th>Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehiclesdel as $vehiclesdel)
                                <tr>
                                <td>{{ $vehiclesdel->id }}</td>
                                @can('view-vehicle-model-sfx')
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-vehicle-model-sfx');
                                @endphp
                                @if ($hasPermission)
                                    <td>
                                        @if($vehiclesdel->model_id)
                                            {{ $vehiclesdel->masterModel->model ?? ''  }} - {{ $vehiclesdel->masterModel->sfx ?? '' }}
                                        @endif
                                    </td>
                                @endif
                            @endcan
                            <td>{{ ucfirst(strtolower($vehiclesdel->variant->brand->brand_name)) }}</td>
                            <td>{{ ucfirst(strtolower($vehiclesdel->variant->master_model_lines->model_line)) }}</td>
                            <td>{{ ucfirst($vehiclesdel->variant->name) }}</td>
                        @php
                        $words = explode(' ', ucfirst(strtolower($vehiclesdel->variant->detail)));
                        $shortDetail = implode(' ', array_slice($words, 0, 3));
                        $remainingDetail = implode(' ', array_slice($words, 3));
                        @endphp
                        <td>
                            <span class="short-detail">{{ $shortDetail }}</span>
                            @if(count($words) > 5)
                            <span class="remaining-detail" style="display:none;">{{ $remainingDetail }}</span>
                            <a href="javascript:void(0);" class="read-more" data-full-detail="{{ ucfirst(strtolower($vehiclesdel->variant->detail)) }}">Read more</a>
                            @endif
                        </td>
                        @if (!is_null($vehiclesdel->VehiclePurchasingCost->unit_price) && !is_numeric($vehiclesdel->VehiclePurchasingCost->unit_price))
                        <td>{{ isset($vehiclesdel->VehiclePurchasingCost->unit_price) ? number_format($vehiclesdel->VehiclePurchasingCost->unit_price, 0, '', ',') : '' }}</td>
                        @else
                        <td>{{ $vehiclesdel->VehiclePurchasingCost->unit_price }}</td>
                        @endif
                          <td>{{ ucfirst($vehiclesdel->exterior->name ?? '') }}</td>
                          <td>{{ ucfirst($vehiclesdel->interior->name ?? '') }}</td>
                          <td>{{ ucfirst($vehiclesdel->vin ?? '') }}</td>
                          <td>{{ ucfirst($vehiclesdel->territory ?? '') }}</td>
                          <td>{{ $vehiclesdel->estimation_date ? \Carbon\Carbon::parse($vehiclesdel->estimation_date)->format('d-M-Y') : '' }}</td>
                          <td>{{ $vehiclesdel->ppmmyyy ? \Carbon\Carbon::parse($vehiclesdel->ppmmyyy)->format('d-M-Y') : '' }}</td>
                          <td>{{ ucfirst(strtolower($vehiclesdel->payment_status)) ?? '' }}</td>
                         <td>{{ ucfirst(strtolower($vehiclesdel->procurement_vehicle_remarks ?? '')) }}</td>
                                </tr>
                                @endforeach
                    </tbody>
                    </table>
                    </div>
                    </div>
                    <div class="tab-pane fade" id="dn-vehicle" role="tabpanel" aria-labelledby="dn-vehicle-tab">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div></div> <!-- Empty div to take up space on the left -->
        <a href="#" class="btn btn-sm btn-success adddnnumberbutton-btn" data-id="{{ $purchasingOrder->id }}" data-bs-toggle="modal" data-bs-target="#addDNModalUnique">Add New DN Numbers</a>
    </div>
    
            </div>
        </div>
    </div>
</div>
            @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('purchased-order-transition-view');
  @endphp
  @if ($hasPermission)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">PO Transitions</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dtBasicExample6" class="table table-striped table-editable table-edits table table-bordered">
                            <thead class="bg-soft-secondary">
                            <tr>
                                <th>Transaction Number</th>
                                <th>Transaction At</th>
                                <th>Transaction Type</th>
                                <th>Transaction Amount</th>
                                <th>Currency</th>
                                <th>Transaction By</th>
                                <th>Vehicle Count</th>
                                <th>Remarks</th>
                                <th>View Details</th>
                                @php
                                $hasTransitionPermission = Auth::user()->hasPermissionForSelectedRole('transition-approved');
                                $hasPaymentPermission = Auth::user()->hasPermissionForSelectedRole('payment-request-approval');
                                $hasdetailsPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                                $haspaymentinitiatedPermission = Auth::user()->hasPermissionForSelectedRole('payment-initiated');
                                $haspaymentreleasePermission = Auth::user()->hasPermissionForSelectedRole('payment-release-approval');
                                @endphp
                                @if ($hasTransitionPermission || $hasPaymentPermission || $hasdetailsPermission || $haspaymentinitiatedPermission || $haspaymentreleasePermission)
                                <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($transitions as $transition)
                            <tr data-transition-id="{{ $transition->id }}">
                                <td>{{ $transition->purchaseOrder->po_number ?? 'No Order Number' }} - {{ $transition->row_number }}</td>
                                <td>{{ $transition->created_at->format('d M Y') }}</td>
                                <td>{{ $transition->transaction_type }}</td>
                                <td>{{ number_format($transition->transaction_amount, 0, '', ',') }}</td>
                                <td>{{ $transition->account_currency }}</td>
                                <td>{{ $transition->user->name }}</td>
                                <td>{{ $transition->vehicle_count }}</td>
                                <td>{{ $transition->remarks }}</td>
                                <td>
                                @php
                                $haspostdebit = $transition->transaction_type == "Pre-Debit";
                                $hasreleased = $transition->transaction_type == "Released";
                                $hasdebit = $transition->transaction_type == "Debit";
                                @endphp
                                @if ($haspostdebit || $hasreleased || $hasdebit)
                                <button class="btn btn-info btn-sm" onclick="openSwiftDetailsModal({{ $transition->id }})">
                                    <i class="fa fa-eye"></i> View
                                </button>
                                @endif
                            </td>
                            <!-- @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('transition-approved');
                            @endphp
                            @if ($hasPermission)
                                <td>
                                    @if($transition->transaction_type == "Pre-Debit" && $transition->status == "pending")
                                        <button class="btn btn-success btn-sm" onclick="handleAction('approve', {{ $transition->id }})">Approve</button>
                                        <button class="btn btn-danger btn-sm" onclick="showRejectModal({{ $transition->id }})">Reject</button>
                                    @endif
                                </td>
                            @endif -->
                            @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-request-approval');
                            @endphp
                            @if ($hasPermission)
                                <td>
                                    @if($transition->transaction_type == "Initiate Payment Request")
                                        <button class="btn btn-success btn-sm" onclick="handleActioninitiate('approve', {{ $transition->id }})">Approve</button>
                                        <button class="btn btn-danger btn-sm" onclick="showRejectModalinitiate({{ $transition->id }})">Reject</button>
                                    @endif
                                </td>
                            @endif
                            @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                            @endphp
                            @if ($hasPermission)
                                <td>
                                    @if($transition->created_by == auth()->user()->id && $transition->transaction_type == "Draft")
                                        <button class="btn btn-primary btn-sm" onclick="submitpayment('approve', {{ $transition->id }})">Submit</button>
                                    @endif
                                    @if($transition->transaction_type == "Debit" && $transition->vendor_payment_status == Null)
                                        <button class="btn btn-primary btn-sm" onclick="paymentconfirm('approve', {{ $transition->id }})">Vendor Payment Confirm</button>
                                    @endif
                                </td>
                            @endif
                            @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-release-approval');
                            @endphp
                            @if ($hasPermission)
                                <td>
                                    @if($transition->transaction_type == "Pre-Debit")
                                        <button id="approveButton" class="btn btn-success btn-sm" data-transition-id="{{ $transition->id }}">Released</button>
                                        <button class="btn btn-danger btn-sm" data-reject-id="{{ $transition->id }}" onclick="showRejectModalreleased({{ $transition->id }})">Reject</button>
                                        <!-- <button class="btn btn-danger btn-sm" onclick="showRejectModalreleased({{ $transition->id }})">Reject</button> -->
                                    @endif
                                </td>
                            @endif
                            @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-initiated');
                            @endphp
                            @if ($hasPermission)
                                <td>
                                    @if($transition->transaction_type == "Released")
                                        <button class="btn btn-success btn-sm" onclick="openSwiftUploadModal({{ $transition->id }})" data-transition-id="{{ $transition->id }}">Uploading Swift</button>
                                    @elseif($transition->transaction_type == "Request For Payment")
                                        <button class="btn btn-success btn-sm" onclick="modalforinitiated({{ $transition->id }})" data-transition-id="{{ $transition->id }}">Initiated</button>
                                        <button class="btn btn-danger btn-sm" onclick="showRejectModalinitiate({{ $transition->id }})">Reject</button>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<div id="rejectModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">Reject Transition</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <textarea id="rejectRemarks" class="form-control" placeholder="Enter remarks"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="submitReject" type="button" class="btn btn-danger">Reject</button>
      </div>
    </div>
  </div>
</div>
<div id="rejectModallinitiate" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="rejectModallinitiateLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModallinitiateLabel">Reject Transition</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <textarea id="rejectRemarkslinitiate" class="form-control" placeholder="Enter remarks"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="submitRejectlinitiate" type="button" class="btn btn-danger">Reject</button>
      </div>
    </div>
  </div>
</div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Vehicles Log Details</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered">
                            <thead class="bg-soft-secondary">
                            <tr>
                                <th>Ref</th>
                                <th>Field</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                                <th>Updated Date</th>
                                <th>Updated By</th>
                                <th>Role</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehicleslog as $vehicleslog)
                                <tr>
                                    <td>{{ $vehicleslog->vehicles_id}}</td>
                                    <td>
                                        @if($vehicleslog->field === "int_colour")
                                            Interior Colour
                                        @elseif($vehicleslog->field === "ex_colour")
                                            Exterior Colour
                                        @elseif($vehicleslog->field === "payment_status")
                                            Payment Status
                                        @elseif($vehicleslog->field === "estimation_date")
                                            Estimated Date
                                            @elseif($vehicleslog->field === "ppmmyyy")
                                            Production Month
                                        @elseif($vehicleslog->field === "territory")
                                            Territory
                                        @elseif($vehicleslog->field === "vin")
                                            VIN
                                        @else
                                            {{$vehicleslog->field}}
                                        @endif
                                    </td>
                                    @if($vehicleslog->field === "int_colour" || $vehicleslog->field === "ex_colour")
                                        @php
                                            $old_value = $vehicleslog->old_value ? DB::table('color_codes')->where('id', $vehicleslog->old_value)->first() : null;
                                            $colourold = $old_value ? $old_value->name : null;
                                            $new_value = $vehicleslog->new_value ? DB::table('color_codes')->where('id', $vehicleslog->new_value)->first() : null;
                                            $colournew = $new_value ? $new_value->name : null;
                                        @endphp
                                        <td>{{$colourold}}</td>
                                        <td>{{$colournew}}</td>
                                    @elseif($vehicleslog->field === "estimation_date")
                                        @if($vehicleslog->old_value)
                                            <td>{{ date('d-M-Y', strtotime($vehicleslog->old_value)) }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @elseif($vehicleslog->field === "ppmmyyy")
                                        @if($vehicleslog->old_value)
                                            <td>{{ date('d-M-Y', strtotime($vehicleslog->old_value)) }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ date('d-M-Y', strtotime($vehicleslog->new_value)) }}</td>
                                    @else
                                        <td>{{$vehicleslog->old_value}}</td>
                                        <td>{{$vehicleslog->new_value}}</td>
                                    @endif
                                    <td>{{ date('d-M-Y', strtotime($vehicleslog->date)) }} {{$vehicleslog->time}}</td>
                                    <td>@php
                                            $change_by = DB::table('users')->where('id', $vehicleslog->created_by)->first();
                                            $change_bys = $change_by->name;
                                        @endphp
                                        {{ ucfirst(strtolower($change_bys)) }}
                                    </td>
                                    <td>
                                        @php
                                            $selected = DB::table('roles')->where('id', $vehicleslog->role)->first();
                                            $roleselected = $selected ? $selected->name : null;
                                        @endphp
                                        {{$roleselected}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <div class="card">
                <div class="card-header">
                    <h4 class="card-title">PO's Log Details</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered">
                            <thead class="bg-soft-secondary">
                            <tr>
                                <th>Action</th>
                                <th>Variant</th>
                                <th>Exterior Color</th>
                                <th>Interior Color</th>
                                <th>QTY</th>
                                <th>Territory</th>
                                <th>Estimated Arrival</th>
                                <th>Update Date</th>
                                <th>Updated By</th>
                                <th>Role</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $previousLog = null;
                                $qty = 0;
                            @endphp
                            @foreach($purchasinglog as $log)
                                @php
                                    // Check if current row is the same as the previous row
                                    $isSameAsPrevious = ($previousLog &&
                                    $log->created_by== $previousLog->created_by &&
                                    $log->estimation_date == $previousLog->estimation_date &&
                                    $log->territory == $previousLog->territory &&
                                    $log->ex_colour == $previousLog->ex_colour &&
                                    $log->int_colour == $previousLog->int_colour &&
                                    $log->variant == $previousLog->variant &&
                                    $log->status == $previousLog->status &&
                                    $log->role == $previousLog->role);
                                    if (!$isSameAsPrevious) {
                                    if ($qty > 0) {
                                    // Output the row with the previous quantity count
                                    echo '
                                    <tr>
                                       ';
                                       $change_by = DB::table('users')->where('id', $previousLog->created_by)->first();
                                       $change_bys = $change_by->name;
                                       $variant = $previousLog->variant ? DB::table('varaints')->where('id', $previousLog->variant)->first() : null;
                                        $variant_name = $variant ? $variant->name: null;
                                       echo '
                                       <td>'. $previousLog->status .'</td>
                                       ';
                                       echo '
                                       <td>'. ucfirst(strtolower($variant_name)) .'</td>
                                       ';
                                       $exColour = $previousLog->ex_colour ? DB::table('color_codes')->where('id', $previousLog->ex_colour)->first() : null;
                                       $ex_colours = $exColour ? $exColour->name : null;
                                       $intColour = $previousLog->int_colour ? DB::table('color_codes')->where('id', $previousLog->int_colour)->first() : null;
                                       $int_colours = $intColour ? $intColour->name : null;
                                       echo '
                                       <td>'. $ex_colours .'</td>
                                       ';
                                       echo '
                                       <td>'. $int_colours .'</td>
                                       ';
                                       echo '
                                       <td>'. $qty .'</td>
                                       ';
                                       echo '
                                       <td>'. ucfirst(strtolower($previousLog->territory)) .'</td>
                                       ';
                                       echo '
                                       <td>'. $previousLog->estimation_date .'</td>
                                       ';
                                       echo '
                                       <td>'. date('d-M-Y', strtotime($previousLog->date)) .' '. $previousLog->time .'</td>
                                       ';
                                       echo '
                                       <td>'. ucfirst(strtolower($change_bys)) .'</td>
                                       ';
                                       $selected = DB::table('roles')->where('id', $previousLog->role)->first();
                                       $roleselected = $selected ? $selected->name : null;
                                       echo '
                                       <td>'. $roleselected .'</td>
                                       ';
                                       echo '
                                    </tr>
                                    ';
                                    }
                                    // Reset the quantity count and update the previous log
                                    $qty = 1;
                                    $previousLog = $log;
                                    } else {
                                    $qty++; // Increment the quantity count
                                    }
                                @endphp
                            @endforeach
                            {{-- Output the last group if it exists --}}
                            @if ($qty > 0)
                                <tr>
                                    @php
                                        $change_by = DB::table('users')->where('id', $previousLog->created_by)->first();
                                        $change_bys = $change_by->name;
                                        $variant = $previousLog->variant ? DB::table('varaints')->where('id', $previousLog->variant)->first() : null;
                                        $variant_name = $variant ? $variant->name: null;
                                        $exColour = $previousLog->ex_colour ? DB::table('color_codes')->where('id', $previousLog->ex_colour)->first() : null;
                                        $ex_colours = $exColour ? $exColour->name : null;
                                        $intColour = $previousLog->int_colour ? DB::table('color_codes')->where('id', $previousLog->int_colour)->first() : null;
                                        $int_colours = $intColour ? $intColour->name : null;
                                        $selected = DB::table('roles')->where('id', $previousLog->role)->first();
                                        $roleselected = $selected ? $selected->name : null;
                                    @endphp
                                    <td>{{ $previousLog->status }}</td>
                                    <td>{{ ucfirst(strtolower($variant_name)) }}</td>
                                    <td>{{ $ex_colours }}</td>
                                    <td>{{ $int_colours }}</td>
                                    <td>{{ $qty }}</td>
                                    <td>{{ ucfirst(strtolower($previousLog->territory)) }}</td>
                                    <td>{{ $previousLog->estimation_date }}</td>
                                    <td>{{ date('d-M-Y', strtotime($previousLog->date)) }} {{ $previousLog->time }}</td>
                                    <td>{{ ucfirst(strtolower($change_bys)) }}</td>
                                    <td>{{ $roleselected }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">PO Events Log</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dtBasicExample7" class="table table-striped table-editable table-edits table table-bordered">
                            <thead class="bg-soft-secondary">
                            <tr>
                                <th>Date</th>
                                <th>Updated By</th>
                                <th>Event Type</th>
                                <th>Field</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                                <th>Description / Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchasedorderevents as $purchasedorderevents)
                            <tr>
                            @php
                                        $username = DB::table('users')->where('id', $purchasedorderevents->created_by)->first();
                                        $usernames = $username->name;
                            @endphp
                            <td>{{ $purchasedorderevents->created_at->format('d-M-Y') }}</td>
                                <td>{{$usernames }}</td>
                                <td>{{$purchasedorderevents->event_type }}</td>
                                <td>{{$purchasedorderevents->field }}</td>
                                <td>{{$purchasedorderevents->old_value }}</td>
                                <td>{{$purchasedorderevents->new_value }}</td>
                                <td>{{$purchasedorderevents->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            let targetUrl;
    function openModal(id) {
        targetUrl = window.location.href; 
        $('#vehicleId').val(id);
        $('#remarksrejModal').modal('show');
        return false;  // Prevent default button behavior
    }

    $('#submitRemarksrej').click(function() {
        var vehicleId = $('#vehicleId').val();
        var remarks = $('#remarksrej').val();
        var token = $('input[name="_token"]').val();
        var url = '{{ route("vehicles.paymentreleasesrejected", ":id") }}';
        url = url.replace(':id', vehicleId);

        if (remarks) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: token,
                    remarks: remarks
                },
                success: function(response) {
                    // Handle the response here
                    $('#remarksrejModal').modal('hide');
                    alert('Remarks submitted successfully');
                    // Redirect to the target URL after successful submission
                    window.location.href = targetUrl;
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    alert('An error occurred. Please try again.');
                }
            });
        } else {
            alert('Please provide remarks.');
        }
    });
</script>
        <script>
            $(document).ready(function() {
                $('.select2').select2();

                // Table #dtBasicExample2
                var dataTable2 = $('#dtBasicExample2').DataTable({
                    "order": [[4, "desc"]],
                    pageLength: 10,
                    initComplete: function() {
                        this.api().columns().every(function(d) {
                            var column = this;
                            var columnId = column.index();
                            var columnName = $(column.header()).attr('id');
                            if (columnName === "statuss") {
                                return;
                            }

                            var selectWrapper = $('<div class="select-wrapper"></div>');
                            var select = $('<select class="form-control my-1" multiple></select>')
                                .appendTo(selectWrapper)
                                .select2({
                                    width: '100%',
                                    dropdownCssClass: 'select2-blue'
                                });
                            select.on('change', function() {
                                var selectedValues = $(this).val();

                                // Check if the blank option is selected
                                if (selectedValues && selectedValues.includes('')) {
                                    column.search('^$', true, false); // Filter blank values
                                } else {
                                    column.search(selectedValues ? selectedValues.join('|') : '', true, false); // Filter other selected values
                                }

                                column.draw();
                            });

                            selectWrapper.appendTo($(column.header()));
                            $(column.header()).addClass('nowrap-td');

                            column.data().unique().sort().each(function(d, j) {
                                // Add option for blank value
                                var optionValue = d === null ? '' : d;
                                var optionText = d === null ? 'Blank' : d === '' ? 'Null' : d;
                                select.append('<option value="' + optionValue + '">' + optionText + '</option>');
                            });
                        });
                    }
                });

                // Table #dtBasicExample2
                var dataTable2 = $('#dtBasicExample1').DataTable({
                    "order": [[4, "desc"]],
                    pageLength: 10,
                    initComplete: function() {
                        this.api().columns().every(function(d) {
                            var column = this;
                            var columnId = column.index();
                            var columnName = $(column.header()).attr('id');
                            if (columnName === "estimated") {
                                return;
                            }
                            if (columnName === "action") {
                                return;
                            }
                            if (columnName === "int_color") {
                                return;
                            }
                            if (columnName === "ex_color") {
                                return;
                            }
                            if (columnName === "variants_detail") {
                                return;
                            }
                            var selectWrapper = $('<div class="select-wrapper"></div>');
                            var select = $('<select class="form-control my-1" multiple></select>')
                                .appendTo(selectWrapper)
                                .select2({
                                    width: '100%',
                                    dropdownCssClass: 'select2-blue'
                                });
                            select.on('change', function() {
                                var selectedValues = $(this).val();

                                // Check if the blank option is selected
                                if (selectedValues && selectedValues.includes('')) {
                                    column.search('^$', true, false); // Filter blank values
                                } else {
                                    column.search(selectedValues ? selectedValues.join('|') : '', true, false); // Filter other selected values
                                }

                                column.draw();
                            });

                            selectWrapper.appendTo($(column.header()));
                            $(column.header()).addClass('nowrap-td');

                            column.data().unique().sort().each(function(d, j) {
                                // Add option for blank value
                                var optionValue = d === null ? '' : d;
                                var optionText = d === null ? 'Blank' : d === '' ? 'Null' : d;
                                select.append('<option value="' + optionValue + '">' + optionText + '</option>');
                            });
                        });
                    }
                });
                // Table #dtBasicExample3
                var dataTable3 = $('#dtBasicExample3').DataTable({
                    "order": [[7, "desc"]],
                    pageLength: 10,
                    initComplete: function() {
                        this.api().columns().every(function(d) {
                            var column = this;
                            var columnId = column.index();
                            var columnName = $(column.header()).attr('id');
                            if (columnName === "statuss") {
                                return;
                            }

                            var selectWrapper = $('<div class="select-wrapper"></div>');
                            var select = $('<select class="form-control my-1" multiple></select>')
                                .appendTo(selectWrapper)
                                .select2({
                                    width: '100%',
                                    dropdownCssClass: 'select2-blue'
                                });
                            select.on('change', function() {
                                var selectedValues = $(this).val();

                                // Check if the blank option is selected
                                if (selectedValues && selectedValues.includes('')) {
                                    column.search('^$', true, false); // Filter blank values
                                } else {
                                    column.search(selectedValues ? selectedValues.join('|') : '', true, false); // Filter other selected values
                                }

                                column.draw();
                            });

                            selectWrapper.appendTo($(column.header()));
                            $(column.header()).addClass('nowrap-td');

                            column.data().unique().sort().each(function(d, j) {
                                // Add option for blank value
                                var optionValue = d === null ? '' : d;
                                var optionText = d === null ? 'Blank' : d === '' ? 'Null' : d;
                                select.append('<option value="' + optionValue + '">' + optionText + '</option>');
                            });
                        });
                    }
                });

                $('.dataTables_filter input').on('keyup', function() {
                    dataTable2.search(this.value).draw();
                    dataTable3.search(this.value).draw();
                });
            });
        </script>
         @php
       $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-release-approval');
       @endphp
       @if (!$hasPermission)
        <script>
            const editableFields = document.querySelectorAll('.editable-field');
            const editBtn = document.querySelector('.edit-btn');
            const updateBtn = document.querySelector('.update-btn');
            if (editBtn && updateBtn) {
            editBtn.addEventListener('click', () => {
                editBtn.style.display = 'none';
                updateBtn.style.display = 'block';
                editableFields.forEach(field => {
                    field.contentEditable = true;
                    field.classList.add('editing');
                    const selectElement = field.querySelector('select');
                    if (selectElement) {
                        selectElement.removeAttribute('disabled');
                    }
                    const isEstimationDateColumn = field.classList.contains('estimation_date');
                    const isPpmmyyyDateColumn = field.classList.contains('ppmmyyy');
                    const fieldValue = field.innerText.trim();
                    const isNullValue = fieldValue === '';
                    if (isNullValue && !isEstimationDateColumn && !isPpmmyyyDateColumn) {
                        return;
                    }
                    if (isEstimationDateColumn) {
                        const inputField = document.createElement('input');
                        inputField.type = 'date';
                        inputField.name = 'oldestimated_arrival[]';
                        inputField.value = fieldValue;
                        inputField.classList.add('form-control');
                        field.innerHTML = '';
                        field.appendChild(inputField);
                    }
                    if (isPpmmyyyDateColumn) {
                        const inputField = document.createElement('input');
                        inputField.type = 'date';
                        inputField.name = 'ppmmyyy[]';  // Adjust this name as needed
                        inputField.value = fieldValue;
                        inputField.classList.add('form-control');
                        field.innerHTML = '';
                        field.appendChild(inputField);
                    }
                });
            });
            updateBtn.addEventListener('click', () => {
    checkDuplicateVIN(function(vinCheckResult) {
        console.log(vinCheckResult);
        if (vinCheckResult === false) {
            return;
        } else {
            updateBtn.style.display = 'none';
            editBtn.style.display = 'block';
            editableFields.forEach(field => {
                field.contentEditable = false;
                field.classList.remove('editing');
                const selectElement = field.querySelector('select');
                if (selectElement) {
                    selectElement.setAttribute('disabled', 'disabled');
                }
                const inputField = field.querySelector('input[type="date"]');
                if (inputField) {
                    const fieldValue = inputField.value;
                    field.innerHTML = fieldValue;
                }
            });
            const updatedData = [];
            editableFields.forEach(field => {
                const fieldName = field.classList[1];
                const fieldValue = field.innerText.trim();
                const selectElement = field.querySelector('select');
                if (selectElement) {
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const selectedValue = selectedOption.value;
                    const selectedText = selectedOption.text;
                    updatedData.push({ id: field.getAttribute('data-vehicle-id'), name: fieldName, value: selectedValue });
                } else {
                    updatedData.push({ id: field.getAttribute('data-vehicle-id'), name: fieldName, value: fieldValue });
                }
            });
            console.log(updatedData);
            fetch('{{ route('purchasing.updateData') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(updatedData)
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                const flashMessage = document.getElementById('flash-message');
                flashMessage.textContent = 'Data updated successfully';
                flashMessage.style.display = 'block';

                // Hide the flash message after 5 seconds
                setTimeout(() => {
                    flashMessage.style.display = 'none';
                }, 2000);
            })
            .catch(error => {
                // Handle any errors that occur during the request
                console.error(error);
            });
        }
    });
});
}
        </script>
        @endif
        <script>
            $(document).ready(function() {
                $('#variants_id').on('input', function() {
                    var selectedVariant = $(this).val();
                    var variantOption = $('#variantslist').find('option[value="' + selectedVariant + '"]');
                    if (variantOption.length > 0) {
                        var detail = variantOption.data('detail');
                        var brands_id = variantOption.data('brands_id');
                        var master_model_lines_id = variantOption.data('master_model_lines_id');
                        $('#details').val(detail);
                        $('#brands_id').val(brands_id);
                        $('#master_model_lines_id').val(master_model_lines_id);
                        $('#SelectVariantsId').val(selectedVariant);
                    }
                });


                $('.add-row-btn').click(function() {
                    var selectedVariant = $('#variants_id').val();
                    var variantOption = $('#variantslist').find('option[value="' + selectedVariant + '"]');
                    if (variantOption.length === 0) {
                        alert('Invalid variant selected');
                        return;
                    }
                    var qty = $('#QTY').val();
                    var unitPrice = $('#unit_price').val();
                    console.log(unitPrice);
                    var detail = variantOption.data('detail');
                    var brand = variantOption.data('brands_id');
                    var masterModelLine = variantOption.data('master_model_lines_id');
                    $('.bar').show();

                    // Move the declaration and assignment inside the click event function
                    var exColours = <?= json_encode($exColours) ?>;
                    var intColours = <?= json_encode($intColours) ?>;

                    for (var i = 0; i < qty; i++) {
                        var newRow = $('<tr></tr>');
                        var variantCol = $('<td><input type="hidden" name="variant_id[]" value="' + selectedVariant + '" class="form-control" readonly></div>' + selectedVariant + '</td>');
                        var brandCol = $('<td>' + brand + '</td>');
                        var masterModelLineCol = $('<td>' + masterModelLine + '</td>');
                        var estimatedCol = $('<td><input type="date" name="estimated_arrival[]" class="form-control"></td>');
                        var territoryCol = $('<td><input type="text" name="territory[]" class="form-control"></td>');
                        var exColourCol = $('<td><select name="ex_colour[]" class="form-control"><option value="">Exterior Color</option></select></td>');
                        var intColourCol = $('<td><select name="int_colour[]" class="form-control"><option value="">Interior Color</option></select></td>');
                        var unitPriceCol = $('<td><input type="text" name="unit_prices[]" value="' + unitPrice + '" class="form-control" readonly></td>');
                        var vinCol = $('<td><input type="text" name="vin[]" class="form-control" placeholder="VIN"></td>');
                        var territory = $('<td><input type="text" name="territory[]" class="form-control"></td>');
                        var engineCol = $('<td><input type="text" name="engine_number[]" class="form-control" placeholder="Engine"></td>');
                        var removeBtnCol = $('<td><button type="button" class="btn btn-danger remove-row-btn"><i class="fas fa-times"></i></button></td>');
                        var exColourDropdown = exColourCol.find('select');
                        for (var id in exColours) {
                            if (exColours.hasOwnProperty(id)) {
                                exColourDropdown.append($('<option></option>').attr('value', id).text(exColours[id]));
                            }
                        }
                        var intColourDropdown = intColourCol.find('select');
                        for (var id in intColours) {
                            if (intColours.hasOwnProperty(id)) {
                                intColourDropdown.append($('<option></option>').attr('value', id).text(intColours[id]));
                            }
                        }
                        newRow.append(variantCol, brandCol, masterModelLineCol,exColourCol, intColourCol, unitPriceCol, estimatedCol,engineCol, vinCol, territory, removeBtnCol);
                        $('#dtBasicExampledata tbody').append(newRow);
                    }
                    $('#variants_id').val('');
                    $('#QTY').val('');
                    $('#variantRowsContainer').show();
                });
                $(document).on('click', '.remove-row-btn', function() {
                    var rowToRemove = $(this).closest('tr');
                    var variant = rowToRemove.find('input[name="variant_id[]"]').val();
                    var existingOption = $('#variantslist').find('option[value="' + variant + '"]');
                    if (existingOption.length === 0) {
                        var variantOption = $('<option value="' + variant + '">' + variant + '</option>');
                        $('#variantslist').append(variantOption);
                    }
                    rowToRemove.remove();
                    $('.row-space').each(function() {
                        if ($(this).next().length === 0) {
                            $(this).removeClass('row-space');
                        }
                    });
                    if ($('#variantRowsContainer').find('.row').length === 1) {
                        $('.bar').hide();
                        $('#variantRowsContainer').hide();
                    }
                });
            });
        </script>
       @php
       $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-release-approval');
       @endphp
       @if (!$hasPermission)
        <script>
            var input = document.getElementById('variants_id');
            var dataList = document.getElementById('variantslist');
            input.addEventListener('input', function() {
                var inputValue = input.value;
                var options = dataList.getElementsByTagName('option');
                var matchFound = false;
                for (var i = 0; i < options.length; i++) {
                    var option = options[i];
                    if (inputValue === option.value) {
                        matchFound = true;
                        break;
                    }
                }
                if (!matchFound) {
                    input.setCustomValidity("Please select a value from the list.");
                } else {
                    input.setCustomValidity('');
                }
            });
        </script>
        @endif
        <script>
            function updateStatusrej(status, orderId) {
                let url = '{{ route('purchasing.updateStatuscancel') }}';
                let data = { status: status, orderId: orderId };

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Status update successful');
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error updating status:', error);
                    });
            }
            function updateStatus(status, orderId) {
                let url = '{{ route('purchasing.updateStatus') }}';
                let data = { status: status, orderId: orderId };

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Status update successful');
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error updating status:', error);
                    });
            }
            function updateallStatus(status, orderId) {
                let url = '{{ route('purchasing.updateallStatus') }}';
                let data = { status: status, orderId: orderId };

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Status update successful');
                        window.location.reload();
                    })
                    .catch(error => {
                        window.location.reload();
                    });
            }
            function updateallStatusrel(status, orderId) {
    if (status === 'Rejected') {
        let remarksModal = new bootstrap.Modal(document.getElementById('remarksModal'));
        remarksModal.show();

        document.getElementById('submitRemarks').onclick = function() {
            let remarks = document.getElementById('remarks').value;

            if (remarks.trim() === '') {
                alert('Please enter remarks.');
                return;
            }
            postUpdateStatus(status, orderId, remarks);
            remarksModal.hide();
        };
    } else {
        postUpdateStatus(status, orderId);
    }
}
function postUpdateStatus(status, orderId, remarks = '') {
    let url = '{{ route('purchasing.updateallStatusrel') }}';
    let data = { status: status, orderId: orderId, remarks: remarks };
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Status update successful');
        window.location.reload();
    })
    .catch(error => {
        window.location.reload();
    });
}
            function deletepo(id) {
                let url = '{{ route('purchasing-order.deletes', ['id' => ':id']) }}';
                url = url.replace(':id', id);
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        window.location.href = '{{ route('purchasing-order.index') }}';
                    })
                    .catch(error => {
                        window.location.href = '{{ route('purchasing-order.index') }}';
                    });
            }

        </script>
  <script>
$(document).ready(function() {
    var cancelUrl;

    $('.cancelButtonveh').click(function(event) {
        event.preventDefault(); // Prevent the default action (navigation)
        cancelUrl = $(this).data('url'); // Store the URL to redirect to after confirmation
        $('#confirmationvehModal').modal('show'); // Show the modal

        // Attach the confirmation event handler inside the click handler
        $('#confirmvehCancel').off('click').on('click', function() {
            var remarks = $('#remarksveh').val(); // Get the remarks from the textarea
            if (!remarks) {
                alert('Please provide remarks.');
                return; // Do not proceed if remarks are empty
            }

            // Make an AJAX POST request with the remarks and purchasing order ID
            $.ajax({
                url: cancelUrl, // Use the stored URL
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    remarks: remarks
                },
                success: function(response) {
                    $('#confirmationvehModal').modal('hide'); // Hide the modal
                    alert('Remarks submitted successfully');
                    window.location.href = window.location.href; // Redirect to the current URL
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
});
</script>
        <script>
            function confirmPayment() {
                var confirmDialog = confirm("Are you sure you want to Payment this Vehicles?");
                if (confirmDialog) {
                    return true;
                } else {
                    return false;
                }
            }
            function confirmPaymentrej() {
                var confirmDialog = confirm("Are you sure you want to Rejected Payment Request this Vehicles?");
                if (confirmDialog) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>
        <script>
            function confirmRejected() {
                var confirmDialog = confirm("Are you sure you want to Reject this Vehicles?");
                if (confirmDialog) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>
        <script>
            function confirmApprovedcancel() {
                var confirmDialog = confirm("Are you sure you want to Approved this Vehicles Cancel?");
                if (confirmDialog) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>
        <script>
            function confirmunRejected() {
                var confirmDialog = confirm("Are you sure you want to Un-Reject this Vehicles?");
                if (confirmDialog) {
                    return true;
                } else {
                    return false;
                }
            }
            function allpaymentintreq(status, orderId) {
                let url = '{{ route('purchasing.allpaymentreqss') }}';
                let data = { status: status, orderId: orderId };
                console.log(data);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Status update successful');
                        window.location.reload();
                    })
                    .catch(error => {
                        window.location.reload();
                    });
            }
    document.addEventListener('DOMContentLoaded', (event) => {
    // Populate the percentage dropdown
    let percentageSelect = document.getElementById('percentageSelect');
    for (let i = 5; i <= 100; i += 5) {
        let option = document.createElement('option');
        option.value = i;
        option.text = i + '%';
        if (i === 100) {
            option.selected = true; // Set default to 100%
        }
        percentageSelect.appendChild(option);
    }

    // Add event listener to the submit button
    document.getElementById('submitPercentage').addEventListener('click', function() {
        let percentage = document.getElementById('percentageSelect').value;
        let status = document.getElementById('percentageModal').dataset.status;
        let orderId = document.getElementById('percentageModal').dataset.orderId;

        let url = '{{ route('purchasing.allpaymentreqssfin') }}';
        let data = { status: status, orderId: orderId, percentage: percentage };

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Status update successful');
            window.location.reload();
        })
        .catch(error => {
            window.location.reload();
        });
    });
});
document.addEventListener('DOMContentLoaded', (event) => {
    // Populate the percentage dropdown
    let percentageSelectremaining = document.getElementById('percentageSelectremaining');
    for (let i = 5; i <= 100; i += 5) {
        let option = document.createElement('option');
        option.value = i;
        option.text = i + '%';
        if (i === 100) {
            option.selected = true; // Set default to 100%
        }
        percentageSelectremaining.appendChild(option);
    }

    // Add event listener to the submit button
    document.getElementById('submitPercentageremaining').addEventListener('click', function() {
        let percentage = document.getElementById('percentageSelectremaining').value;
        let status = document.getElementById('percentageModalremaining').dataset.status;
        let orderId = document.getElementById('percentageModalremaining').dataset.orderId;

        let url = '{{ route('purchasing.allpaymentreqssfinremainig') }}';
        let data = { status: status, orderId: orderId, percentage: percentage };
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Status update successful');
            window.location.reload();
        })
        .catch(error => {
            window.location.reload();
        });
    });
});
function allpaymentintreqfinremaing(status, orderId) {
    if(status == "Rejected")
    {
                let url = '{{ route('purchasing.allpaymentreqssfinremainig') }}';
                let data = { status: status, orderId: orderId };
                console.log(data);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Status update successful');
                        window.location.reload();
                    })
                    .catch(error => {
                        window.location.reload();
                    });
                }
                else
                {
                    let modal = document.getElementById('percentageModalremaining');
                    modal.dataset.status = status;
                    modal.dataset.orderId = orderId;
                    $('#percentageModalremaining').modal('show'); // Use jQuery to show the modal
                }
            }
            function allpaymentintreqfin(status, orderId) {
    if(status == "Rejected")
    {
                let url = '{{ route('purchasing.allpaymentreqssfin') }}';
                let data = { status: status, orderId: orderId };
                console.log(data);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Status update successful');
                        window.location.reload();
                    })
                    .catch(error => {
                        window.location.reload();
                    });
                }
                else
                {
                    let modal = document.getElementById('percentageModal');
                    modal.dataset.status = status;
                    modal.dataset.orderId = orderId;
                    $('#percentageModal').modal('show'); // Use jQuery to show the modal
                }
            }
            // function allpaymentintreqfinpay(status, orderId) {
            //     let url = '{{ route('purchasing.allpaymentreqssfinpay') }}';
            //     let data = { status: status, orderId: orderId };
            //     console.log(data);
            //     fetch(url, {
            //         method: 'POST',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //         },
            //         body: JSON.stringify(data),
            //     })
            //         .then(response => response.json())
            //         .then(data => {
            //             console.log('Status update successful');
            //             window.location.reload();
            //         })
            //         .catch(error => {
            //             window.location.reload();
            //         });
            // }

            // function rerequestpayment(status, orderId) {
            //     let url = '{{ route('purchasing.rerequestpayment') }}';
            //     let data = { status: status, orderId: orderId };
            //     fetch(url, {
            //         method: 'POST',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //         },
            //         body: JSON.stringify(data),
            //     })
            //         .then(response => response.json())
            //         .then(data => {
            //             console.log('Status update successful');
            //             window.location.reload();
            //         })
            //         .catch(error => {
            //             window.location.reload();
            //         });
            // }

            // Function to open the modal and set dataset attributes
function rerequestpayment(status, orderId) {
    let modal = document.getElementById('repercentageModal');
    modal.dataset.status = status;
    modal.dataset.orderId = orderId;
    $('#repercentageModal').modal('show'); // Use jQuery to show the modal
}

document.addEventListener('DOMContentLoaded', (event) => {
    // Populate the percentage dropdown
    let repercentageSelect = document.getElementById('repercentageSelect');
    for (let i = 5; i <= 100; i += 5) {
        let option = document.createElement('option');
        option.value = i;
        option.text = i + '%';
        if (i === 100) {
            option.selected = true; // Set default to 100%
        }
        repercentageSelect.appendChild(option);
    }

    // Add event listener to the submit button
    document.getElementById('resubmitPercentage').addEventListener('click', function() {
        let percentage = document.getElementById('repercentageSelect').value;
        let status = document.getElementById('repercentageModal').dataset.status;
        let orderId = document.getElementById('repercentageModal').dataset.orderId;

        let url = '{{ route('purchasing.rerequestpayment') }}';
        let data = { status: status, orderId: orderId, percentage: percentage };
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Status update successful');
            window.location.reload();
        })
        .catch(error => {
            window.location.reload();
        });
    });
});
            function allpaymentintreqfinpaycomp(orderId) {
    document.getElementById('status').value = 'Approved';
    document.getElementById('orderId').value = orderId;
    $('#fileUploadModal').modal('show');
}
document.getElementById('fileUploadForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let formData = new FormData(this);
    let url = '{{ route('purchasing.allpaymentreqssfinpaycomp') }}';
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        console.log('File upload successful');
        window.location.reload();
    })
    .catch(error => {
        console.log('File upload failed', error);
        window.location.reload();
    });
});
            function allpaymentintreqpocomp(status, orderId) {
                let url = '{{ route('purchasing.allpaymentintreqpocomp') }}';
                let data = { status: status, orderId: orderId };
                console.log(data);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Status update successful');
                        window.location.reload();
                    })
                    .catch(error => {
                        window.location.reload();
                    });
            }
            function allpaymentintreqpocompin(status, orderId) {
                let url = '{{ route('purchasing.allpaymentintreqpocompin') }}';
                let data = { status: status, orderId: orderId };
                console.log(data);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Status update successful');
                        window.location.reload();
                    })
                    .catch(error => {
                        window.location.reload();
                    });
            }
            $(document).ready(function() {
                var maxLength = 50;
                var $text = $('.full-text');
                var $shortText = $('.short-text');
                var fullText = $text.text();
                var shortText = fullText.substr(0, maxLength);
                $text.text(shortText);
                $shortText.text(fullText.substring(maxLength));

                $('.read-more').click(function(event) {
                    event.preventDefault();
                    var $this = $(this);
                    if ($this.text() === 'Read more') {
                        $this.text('Read less');
                        $text.hide();
                        $shortText.show();
                    } else {
                        $this.text('Read more');
                        $text.show();
                        $shortText.hide();
                    }
                });
            });
        </script>
        <script>
    function checkDuplicateVIN(callback) {
        var vinValues = Array.from(document.querySelectorAll('.editable-field.vin')).map(function(field) {
            return field.innerText.trim();
        });
        var allBlank = vinValues.every(function(value) {
            return value.trim() === '';
        });
        if (allBlank) {
            console.log("All VIN values are blank");
            callback(1);
        } else {
            var Po = "{{$purchasingOrder->id}}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route('vehicles.check-create-vins-inside') }}',
                method: 'POST',
                data: { vins: vinValues, po: Po },
                success: function(response) {
                    if (response === 'duplicate') {
                        alert('Duplicate VIN values found in the database. Please ensure all VIN values are unique.');
                        callback(false);
                    } else {
                        callback(1);
                    }
                },
                error: function() {
                    alert('An error occurred while checking for VIN duplication. Please try again.');
                    callback(false);
                }
            });
        }
    }
</script>
<script>
$(document).ready(function() {
  $('.edit-basic-btn').on('click', function() {
    var purchaseId = $(this).data('purchase-id');
    $('#purchaseId').val(purchaseId);
    $('#editModal').modal('show');
  });
  $('.view-doc-btn').on('click', function() {
    $('#viewdocModal').modal('show');
  });
  $(document).on('click', '.view-old-doc-btn', function() {
    var index = $(this).data('index');
    $('#viewOldDocModal' + index).modal('show');
});
});
</script>
<script>
$(document).ready(function() {
    $('#form-update_basicdetails').submit(function(event) {
        event.preventDefault();

        // Create a FormData object
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            contentType: false, // Important for file upload
            processData: false, // Important for file upload
            success: function(response) {
                alert('Purchase order details updated successfully!');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('An error occurred while updating purchase order details.');
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    var cancelUrl;
    $('#cancelButton').click(function(event) {
        event.preventDefault(); // Prevent the default action (navigation)
        cancelUrl = $(this).attr('href'); // Store the URL to redirect to after confirmation
        $('#confirmationModal').modal('show'); // Show the modal
    });

    $('#confirmCancel').click(function() {
        var remarks = $('#remarkspo').val(); // Get the remarks from the textarea
        if (!remarks) {
            alert('Please provide remarks.');
            return; // Do not proceed if remarks are empty
        }

        // Make an AJAX POST request with the remarks and purchasing order ID
        $.ajax({
            url: cancelUrl, // Use the stored URL
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                remarks: remarks
            },
            success: function(response) {
                $('#confirmationModal').modal('hide'); // Hide the modal
                alert('Remarks submitted successfully');
                window.location.href = window.location.href; // Redirect to the cancel URL
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.read-more').forEach(function(element) {
        element.addEventListener('click', function() {
            var fullDetail = this.getAttribute('data-full-detail');
            alert(fullDetail);
        });
    });
});
</script>
<script>
$(document).ready(function() {
    $('.view-swift-btn').on('click', function() {
        var filePath = $(this).data('file-path');
        $('#swiftCopyContainer').html(`<embed src="${filePath}" type="application/pdf" width="100%" height="500px" />`);
        $('#swiftCopyModal').modal('show');
    });

    $('#swiftCopyModal').on('hidden.bs.modal', function () {
        $('#swiftCopyContainer').html('');
    });
});
</script>
<script>
function allpaymentintreqfinpaycompsingle(vehicleId) {
    // Update the form action URL with the correct vehicle ID
    var form = document.getElementById('paymentForm');
    var actionTemplate = "{{ route('vehicles.paymentrelconfirmdebited', ':id') }}";
    var action = actionTemplate.replace(':id', vehicleId);
    form.setAttribute('action', action);
    // Open the modal
    $('#fileUploadModalsingle').modal('show');
}
</script>
<script>
function allpaymentintreqfinpay(status, orderId) {
    // Open the modal
    $('#paymentModal').modal('show');

    // Fetch the supplier_id, current amount, and total amount
    fetch(`/get-supplier-and-amount/${orderId}`)
        .then(response => response.json())
        .then(data => {
            const { supplier_id, current_amount, totalamount, requestedcost } = data;
            const currentAmountNumber = Number(current_amount);
            const requestedCostNumber = Number(requestedcost);

            // Populate the modal with the supplier_id, current_amount, and totalamount
            document.getElementById('supplierId').innerText = supplier_id;
            document.getElementById('currentAmount').innerText = current_amount;
            document.getElementById('totalAmount').innerText = totalamount;
            document.getElementById('requestedCost').innerText = requestedcost;
            document.getElementById('remainingAmount').innerText = requestedcost;

            // Set initial state of adjustment input
            const adjustmentInputContainer = document.getElementById('adjustmentInputContainer');
            adjustmentInputContainer.style.display = 'none';

            const adjustmentLabel = document.getElementById('adjustmentLabel');
            const adjustmentAmountInput = document.getElementById('adjustmentAmount');
            const remainingAmountSpan = document.getElementById('remainingAmount');
            adjustmentAmountInput.value = '';

            // Disable options based on current amount
            const adjustmentOption = document.getElementById('adjustmentOption');
            const payBalanceOption = document.getElementById('payBalanceOption');

            adjustmentOption.disabled = current_amount <= 0;
            payBalanceOption.disabled = current_amount >= 0;

            // Function to update the remaining amount
            function updateRemainingAmount() {
                const adjustmentAmount = parseFloat(adjustmentAmountInput.value) || 0;
                if (document.getElementById('partialpayment').checked) {
                    remainingAmountSpan.innerText = adjustmentAmount.toFixed(2);
                } else if (document.getElementById('payBalanceOption').checked) {
                    const remainingAmount = adjustmentAmount - requestedCostNumber;
                    remainingAmountSpan.innerText = remainingAmount.toFixed(2);
                } else {
                    const remainingAmount = requestedCostNumber - adjustmentAmount;
                    remainingAmountSpan.innerText = remainingAmount.toFixed(2); // Update remaining amount
                }
            }

            // Attach event listeners to checkboxes and input fields
            document.getElementById('adjustmentOption').onclick = function() {
                if (current_amount > 0 && totalamount > 0) {
                    adjustmentInputContainer.style.display = 'block';
                    adjustmentLabel.innerText = 'Adjustment Amount:';
                    adjustmentAmountInput.max = current_amount;
                    adjustmentAmountInput.oninput = updateRemainingAmount;
                } else {
                    adjustmentInputContainer.style.display = 'none';
                    alert('Insufficient balance or total amount.');
                }
            };

            document.getElementById('payBalanceOption').onclick = function() {
                adjustmentInputContainer.style.display = 'block';
                adjustmentLabel.innerText = 'Adding Amount:';
                adjustmentAmountInput.value = '';
                adjustmentAmountInput.max = Number.MAX_SAFE_INTEGER;  // No limit on the entered amount
                adjustmentAmountInput.oninput = function() {
                    const adjustmentAmount = parseFloat(adjustmentAmountInput.value) || 0;
                        updateRemainingAmount();
                };
            };

            document.getElementById('partialpayment').onclick = function() {
                adjustmentInputContainer.style.display = 'block';
                adjustmentLabel.innerText = 'Partial Payment Amount:';
                adjustmentAmountInput.max = requestedCostNumber - 0.01; // Set max to less than the requested cost
                adjustmentAmountInput.oninput = function() {
                    const adjustmentAmount = parseFloat(adjustmentAmountInput.value) || 0;
                    if (adjustmentAmount >= requestedCostNumber) {
                        alert('Partial payment amount cannot be equal to or greater than the requested amount.');
                        adjustmentAmountInput.value = '';
                    } else {
                        updateRemainingAmount();
                    }
                };
            };

            document.getElementById('noAdjustmentOption').onclick = function() {
                adjustmentInputContainer.style.display = 'none';
                remainingAmountSpan.innerText = totalamount.toFixed(2); // Reset remaining amount
            };

            // Attach the confirm payment handler
            document.getElementById('confirmPaymentButton').onclick = function() {
                confirmPayment(status, orderId, current_amount, totalamount, remainingAmountSpan.innerText);
            };
        })
        .catch(error => {
            console.error('Error fetching supplier and amount:', error);
        });
}

function confirmPayment(status, orderId, current_amount, totalamount, remainingAmount) {
    const selectedOption = document.querySelector('input[name="paymentOption"]:checked').value;
    let adjustmentAmount = 0;

    if (selectedOption === 'adjustment' && current_amount > 0) {
        adjustmentAmount = parseFloat(document.getElementById('adjustmentAmount').value);
        if (adjustmentAmount > current_amount) {
            alert('Adjustment amount cannot exceed the current amount.');
            return;
        }
    } else if (selectedOption === 'payBalance') {
        adjustmentAmount = parseFloat(document.getElementById('adjustmentAmount').value);
       
    } else if (selectedOption === 'partialpayment') {
        adjustmentAmount = parseFloat(document.getElementById('adjustmentAmount').value);
    }

    let url = '{{ route('purchasing.allpaymentreqssfinpay') }}';
    let data = { 
        status: status, 
        orderId: orderId, 
        selectedOption: selectedOption, 
        adjustmentAmount: adjustmentAmount,
        remainingAmount: parseFloat(remainingAmount)
    };

    console.log(data);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Status update successful');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        window.location.reload();
    });
}
</script>
<script>
    $(document).ready(function(){
        $('.updateprice-btn').click(function(e){
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id);

            $.ajax({
                url: '{{ route("vehicles.vehiclesdatagetting", ["id" => ":id"]) }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    var tableId = 'dtBasicExample6_' + id; // Unique table ID
                    var tableHtml = '<div class="table-responsive"><table id="' + tableId + '" class="table table-striped table-editable table-edits table table-bordered"><thead class="bg-soft-secondary"><tr><th>Ref No</th><th>VIN</th><th>Current Price</th><th>New Price</th></tr></thead><tbody>';
                    $.each(response, function(index, vehicle) {
                        var vin = vehicle.vin ? vehicle.vin : '';
                        tableHtml += '<tr><td>' + vehicle.vehicle_id + '</td><td>' + vin + '</td><td>' + vehicle.price + '</td><td><input type="text" class="form-control new-price" data-vehicle-id="' + vehicle.vehicle_id + '" value="' + vehicle.price + '"></td></tr>';
                    });
                    tableHtml += '</tbody><tfoot><tr><th colspan="3" class="text-right">Total Price:</th><th id="totalPrice">0.00</th></tr></tfoot></table></div>';
                    
                    $('#vehicleModal .modal-body').html(tableHtml);
                    $('#vehicleModal').modal('show');

                    // Initialize DataTables for the unique table ID
                    $('#' + tableId).DataTable({
                        "paging": true,
                        "searching": true,
                        "ordering": true
                    });

                    // Add event listener for new price inputs
                    $('.new-price').on('input', function() {
                        calculateTotalPrice();
                    });

                    // Store the purchasing order id in the modal for later use
                    $('#vehicleModal').data('purchasingOrderId', id);

                    // Calculate initial total price
                    calculateTotalPrice();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
        var preloadedVariants = @json($variants->map(function($variant) {
    return [
        'id' => $variant->id,
        'name' => $variant->name
    ];
}));
$('.updatevariant-btn').click(function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $.ajax({
        url: '{{ route("vehicles.vehiclesdatagettingvariants", ["id" => ":id"]) }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            var tableId = 'dtBasicExample8_' + id; // Unique table ID
            var tableHtml = '<div class="table-responsive"><table id="' + tableId + '" class="table table-striped table-editable table-edits table-bordered"><thead class="bg-soft-secondary"><tr><th>Ref No</th><th>VIN</th><th>Variant</th><th>New Variant</th></tr></thead><tbody>';
            $.each(response, function(index, vehicle) {
                var vin = vehicle.vin ? vehicle.vin : '';
                tableHtml += '<tr><td>' + vehicle.vehicle_id + '</td><td>' + vin + '</td><td>' + vehicle.variant_name + '</td><td><select class="form-control variant-select" data-vehicle-id="' + vehicle.vehicle_id + '">';
                $.each(preloadedVariants, function(i, variant) {
                    var selected = variant.name === vehicle.variant_name ? 'selected' : '';
                    tableHtml += '<option value="' + variant.id + '" ' + selected + '>' + variant.name + '</option>';
                });
                tableHtml += '</select></td></tr>';
            });
            tableHtml += '</tbody></table></div>';

            $('#vehicleModalvariant .modal-body').html(tableHtml);
            $('#vehicleModalvariant').modal('show');

            // Initialize DataTables for the unique table ID
            $('#' + tableId).DataTable({
                "paging": true,
                "searching": true,
                "ordering": true
            });
            $('#vehicleModalvariant').data('purchasingOrderId', id);
            // Initialize Select2 for the dropdowns with z-index adjustment
            $('.variant-select').select2({
                dropdownParent: $('#vehicleModalvariant')
            });

        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});
$('#savevariantBtn').click(function(){
    var selectedVariants = [];
    $('.variant-select').each(function() {
        var vehicleId = $(this).data('vehicle-id');
        var selectedVariant = $(this).val();
        selectedVariants.push({
            vehicle_id: vehicleId,
            variant_id: selectedVariant
        });
    });
    var purchasingOrderId = $('#vehicleModalvariant').data('purchasingOrderId'); // Retrieve the stored id

    $.ajax({
        url: '{{ route("vehicles.updateVariants") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            variants: selectedVariants,
            purchasing_order_id: purchasingOrderId // Pass the purchasing order ID
        },
        success: function(response) {
            alert('Variants updated successfully!');
            $('#vehicleModalvariant').modal('hide');
            window.location.reload();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});
        function calculateTotalPrice() {
            var totalPrice = 0;
            $('.new-price').each(function() {
                var price = parseFloat($(this).val());
                if (!isNaN(price)) {
                    totalPrice += price;
                }
            });
            $('#totalPrice').text(totalPrice.toFixed(2)); // Update the total price in the table footer
        }

        $('#savePricesBtn').click(function(){
            var newPrices = [];
            $('.new-price').each(function() {
                var vehicleId = $(this).data('vehicle-id');
                var newPrice = $(this).val();
                newPrices.push({
                    vehicle_id: vehicleId,
                    new_price: newPrice
                });
            });

            var totalPrice = $('#totalPrice').text();
            var purchasingOrderId = $('#vehicleModal').data('purchasingOrderId'); // Retrieve the stored id

            $.ajax({
                url: '{{ route("vehicles.updatePrices") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    prices: newPrices,
                    total_price: totalPrice,
                    purchasing_order_id: purchasingOrderId // Pass the purchasing order ID
                },
                success: function(response) {
                    alert('Prices updated successfully!');
                    $('#vehicleModal').modal('hide');
                    $('#totalPriceDisplay').text(totalPrice);
                    window.location.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
<script>
$(document).ready(function() {
    const purchaseOrderId = {{ $purchasingOrder->id }};
    const userId = {{ auth()->id() }};
    const userName = "{{ auth()->user()->name }}";
    const userAvatar = "{{ auth()->user()->avatar }}"; // Assuming user has an avatar field

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function loadMessages() {
        $.get(`/messages/${purchaseOrderId}`, function(data) {
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
            $.post('/messages', { purchase_order_id: purchaseOrderId, message: message }, function(data) {
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
    function handleActioninitiate(action, transitionId, remarks = '') {
    $.ajax({
        url: '{{ route("transition.actioninitiate") }}', // Update this route to your controller method
        type: 'POST',
        data: {
            id: transitionId,
            action: action,
            remarks: remarks
        },
        success: function(response) {
            alertify.success('Transitions Updated Successfully');
            setTimeout(function() {
            window.location.reload();
        }, 500);
            var row = $('button[data-reject-id="' + transitionId + '"]').closest('tr');
      row.find('.btn').hide();
            updateTableRow(transitionId);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}
function submitpayment(action, transitionId) {
    $.ajax({
        url: '{{ route("transition.submitforpayment") }}', // Update this route to your controller method
        type: 'POST',
        data: {
            id: transitionId,
            action: action
        },
        success: function(response) {
            alertify.success('Transitions Updated Successfully');
            var buttonRow = $('button[data-transition-id="' + transitionId + '"]').closest('td');
            buttonRow.find('.btn').hide();
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}
function paymentconfirm(action, transitionId) {
    $.ajax({
        url: '{{ route("transition.paymentconfirm") }}', // Update this route to your controller method
        type: 'POST',
        data: {
            id: transitionId,
            action: action
        },
        success: function(response) {
            alertify.success('Transitions Confirmation Successfully');
            var buttonRow = $('button[data-transition-id="' + transitionId + '"]').closest('td');
            buttonRow.find('.btn').hide();
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

function updateTableRow(transitionId) {
    let row = $(`#row-${transitionId}`);
    row.find('button').hide(); // Hide all buttons in the row
    // You can also update the row content if needed, for example:
    // row.find('.status-cell').text('Approved');
}
function handleAction(action, transitionId, remarks = '') {
    $.ajax({
        url: '{{ route("transition.action") }}', // Update this route to your controller method
        type: 'POST',
        data: {
            id: transitionId,
            action: action,
            remarks: remarks
        },
        success: function(response) {
            alertify.success('Transitions Updated Successfully');
            setTimeout(function() {
            window.location.reload();
        }, 500);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

function showRejectModal(transitionId) {
    $('#rejectTransitionId').val(transitionId);
    $('#rejectModal').modal('show');
}
function showRejectModalinitiate(transitionId) {
    $('#submitRejectlinitiate').data('transition-id', transitionId);
    $('#rejectModallinitiate').modal('show');
}
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
</script>
<script>
function requestForAdditionalPayment(orderId) {
    $.ajax({
        url: '/request-additional-payment',
        type: 'POST',
        data: {
            id: orderId,
            status: 'Approved',
            _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
        },
        success: function(response) {
            alertify.success('Request successfully sent');
            setTimeout(function() {
            window.location.reload();
        }, 500);
        },
        error: function(xhr) {
            alert('An error occurred');
        }
    });
}
function requestForinitiatedPayment(orderId) {
    $.ajax({
        url: '/request-initiated-payment',
        type: 'POST',
        data: {
            id: orderId,
            status: 'Approved',
            _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
        },
        success: function(response) {
            alertify.success('Iniatiated Request successfully sent');
            setTimeout(function() {
            window.location.reload();
        }, 500);
        },
        error: function(xhr) {
            alert('An error occurred');
        }
    });
}
function requestForreleasedPayment(orderId) {
    $.ajax({
        url: '/request-released-payment',
        type: 'POST',
        data: {
            id: orderId,
            status: 'Approved',
            _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
        },
        success: function(response) {
            alertify.success('Released Payment successfully sent');
            setTimeout(function() {
            window.location.reload();
        }, 500);
        },
        error: function(xhr) {
            alert('An error occurred');
        }
    });
}
function completedadditionalpayment(orderId) {
    document.getElementById('statusadditional').value = 'Approved';
    document.getElementById('orderIdadditional').value = orderId;
    console.log(orderId);
    $('#fileUploadModaladditional').modal('show');
}
document.getElementById('fileUploadFormadditional').addEventListener('submit', function(event) {
    event.preventDefault();
    let formData = new FormData(this);
    let url = '{{ route('purchasing.completedadditionalpayment') }}';
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        console.log('File upload successful');
        window.location.reload();
    })
    .catch(error => {
        console.log('File upload failed', error);
        window.location.reload();
    });
});
</script>
<script>
    let vehicleTable;
    let currentBalance = 0;
    function openPurchaseOrderModal(purchaseOrderId) {
        $('#purchaseOrderModal').data('purchaseOrderId', purchaseOrderId);
        $('#purchaseOrderModal').modal('show');
        $.ajax({
            url: `/get-vendor-and-balance/${purchaseOrderId}`,
            method: 'GET',
            success: function(response) {
                const { supplier_account_id, current_balance } = response;
                if (supplier_account_id) {
                    console.log(current_balance);
                    currentBalance = current_balance;
                    if (current_balance > 0) {
                        $('#paymentAdjustmentContainer').show();
                    } else {
                        $('#paymentAdjustmentContainer').hide();
                    }
                }
            }
        });
    }
    $(document).ready(function() {
        $('#vehicleSelectDropdown').select2({
            width: '100%'
        });

        $('input[name="paymentOption"]').change(function() {
            if (this.value === 'purchasedOrder') {
                $('#purchasedOrderOptions').show();
                $('#vehicleOptions').hide();
                $('#vehicleTableContainer').hide();
            } else if (this.value === 'vehicle') {
                $('#purchasedOrderOptions').hide();
                $('#vehicleOptions').show();
            }
        });

        $('#purchasedOrderDropdown').change(function() {
            if (this.value === 'equalDivided' || this.value === 'purchasedOrderPayment') {
                $('#amountInput').show();
            } else {
                $('#amountInput').hide();
                $('#amountDivisionResult').hide();
            }
        });

        $('#amount').on('input', function() {
            const amount = parseFloat(this.value);
            const purchaseOrderId = $('#purchaseOrderModal').data('purchaseOrderId');
            if ($('#purchasedOrderDropdown').val() === 'equalDivided') {
                $.ajax({
                    url: `/getVehicles/${purchaseOrderId}`,
                    method: 'GET',
                    success: function(response) {
                        const vehicleCount = response.length;
                        const dividedAmount = amount / vehicleCount;
                        $('#amountDivisionResult').text(`Amount per vehicle: ${dividedAmount}`).show();
                    }
                });
            } else {
                $('#amountDivisionResult').hide();
            }
        });

        $('#vehicleDropdown').change(function() {
            if (this.value === 'allVehicles') {
                $('#vehicleSelection').hide();
                const purchaseOrderId = $('#purchaseOrderModal').data('purchaseOrderId');
                $.ajax({
                    url: `/getVehicles/${purchaseOrderId}`,
                    method: 'GET',
                    success: function(response) {
                        if (!$.fn.DataTable.isDataTable('#vehicleTable')) {
                            vehicleTable = $('#vehicleTable').DataTable({
            "pageLength": 10, // This will display all rows by default
            "lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]] // Adding "All" option in the length menu
        });
                        } else {
                            vehicleTable.clear().draw();
                        }
                        response.forEach(vehicle => {
                            const formattedPrice = formatPrice(vehicle.vehicle_purchasing_cost ? vehicle.vehicle_purchasing_cost.unit_price : 'N/A');
                            const paidamount = formatPrice(vehicle.vehicle_purchasing_cost ? vehicle.vehicle_purchasing_cost.amount : 'N/A');
                            vehicleTable.row.add([
                                vehicle.id,
                                vehicle.variant.brand.brand_name,
                                vehicle.variant.master_model_lines.model_line,
                                vehicle.variant.name,
                                vehicle.vin,
                                formattedPrice,
                                `<input type="number" class="form-control" name="newPrice${vehicle.id}" id="newPrice${vehicle.id}" value="${vehicle.vehicle_purchasing_cost ? vehicle.vehicle_purchasing_cost.unit_price : ''}">`,
                                `<button class="btn btn-danger btn-sm" onclick="removeVehicleRow(this, ${vehicle.id})">X</button>`
                            ]).draw(false);
                        });
                        $('#vehicleTableContainer').show();
                    }
                });
            } else if (this.value === 'oneByOne') {
                $('#vehicleSelection').show();
                const purchaseOrderId = $('#purchaseOrderModal').data('purchaseOrderId');
                $.ajax({
                    url: `/getVehicles/${purchaseOrderId}`,
                    method: 'GET',
                    success: function(response) {
                        const vehicleSelectDropdown = $('#vehicleSelectDropdown');
                        vehicleSelectDropdown.empty();
                        response.forEach(vehicle => {
                            vehicleSelectDropdown.append(`<option value="${vehicle.id}">${vehicle.id}</option>`);
                        });
                        $('#vehicleTableContainer').show();
                        if (!$.fn.DataTable.isDataTable('#vehicleTable')) {
                                    vehicleTable = $('#vehicleTable').DataTable({
            "pageLength": 10, // This will display all rows by default
            "lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]] // Adding "All" option in the length menu
        });
                        }
                    }
                });
            } else {
                $('#vehicleSelection').hide();
                $('#vehicleTableContainer').hide();
            }
        });

        $('#vehicleSelectDropdown').change(function() {
            const selectedVehicles = $(this).val();
            selectedVehicles.forEach(vehicleId => {
                $.ajax({
                    url: `/getVehicleDetails/${vehicleId}`,
                    method: 'GET',
                    success: function(response) {
                        const formattedPrice = formatPrice(response.vehicle_purchasing_cost ? response.vehicle_purchasing_cost.unit_price : 'N/A');
                        vehicleTable.row.add([
                            response.id,
                            response.variant.brand.brand_name,
                            response.variant.master_model_lines.model_line,
                            response.variant.name,
                            response.vin,
                            formattedPrice,
                            `<input type="number" class="form-control" name="newPrice${response.id}" id="newPrice${response.id}" value="${response.vehicle_purchasing_cost ? response.vehicle_purchasing_cost.unit_price : ''}">`,
                            `<button class="btn btn-danger btn-sm" onclick="removeVehicleRow(this, ${response.id})">X</button>`
                        ]).draw(false);
                        $(`#vehicleSelectDropdown option[value="${response.id}"]`).prop('disabled', true);
                        $('#vehicleTableContainer').show();
                        $('#vehicleSelectDropdown').select2();
                    }
                });
            });
        });

        $('#vehicleSelectDropdown').on('select2:unselect', function(e) {
            const vehicleId = e.params.data.id;
            removeVehicleRowById(vehicleId);
        });
        $('#paymentAdjustmentCheckbox').change(function() {
            if ($(this).is(':checked')) {
                $('#paymentAdjustmentInput').show();
            } else {
                $('#paymentAdjustmentInput').hide();
            }
        });

        $('#adjustmentAmount').on('input', function() {
            const amount = parseFloat(this.value);
            if (amount > currentBalance) {
                $('#adjustmentAmountError').show();
            } else {
                $('#adjustmentAmountError').hide();
            }
        });
        $('#saveDetails').click(function() {
            const data = gatherFormData();
            $.ajax({
                url: '/savePaymentDetails',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    alert('Details saved successfully!');
                    $('#purchaseOrderModal').modal('hide');
                    location.reload();
                },
                error: function() {
                    alert('Failed to save details.');
                }
            });
        });

        $('#submitPercentage').click(function() {
            const data = gatherFormData();
            $.ajax({
                url: '/submitPaymentDetails',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    alert('Details submitted successfully!');
                    $('#purchaseOrderModal').modal('hide');
                    location.reload();
                },
                error: function() {
                    alert('Failed to submit details.');
                }
            });
        });
    });

    function removeVehicleRow(button, vehicleId) {
        const row = $(button).closest('tr');
        const table = $('#vehicleTable').DataTable();
        table.row(row).remove().draw();
        $(`#vehicleSelectDropdown option[value="${vehicleId}"]`).prop('disabled', false);
        $('#vehicleSelectDropdown').select2();
    }

    function removeVehicleRowById(vehicleId) {
        const table = $('#vehicleTable').DataTable();
        table.rows().every(function() {
            if (this.data()[0] == vehicleId) {
                this.remove().draw();
            }
        });
        $(`#vehicleSelectDropdown option[value="${vehicleId}"]`).prop('disabled', false);
        $('#vehicleSelectDropdown').select2();
    }

    function gatherFormData() {
        const paymentOption = $('input[name="paymentOption"]:checked').val();
        const purchaseOrderId = $('#purchaseOrderModal').data('purchaseOrderId');
        const data = {
            paymentOption: paymentOption,
            purchaseOrderId: purchaseOrderId,
            adjustmentAmount: $('#adjustmentAmount').val()
        };

        if (paymentOption === 'purchasedOrder') {
            data.purchasedOrderOption = $('#purchasedOrderDropdown').val();
            data.amount = $('#amount').val();
        } else if (paymentOption === 'vehicle') {
            data.vehicles = [];
            $('#vehicleTable tbody tr').each(function() {
                const row = $(this);
                const vehicleId = row.find('td').eq(0).text();
                const initiatedPrice = row.find('input').val();
                data.vehicles.push({
                    vehicleId: vehicleId,
                    initiatedPrice: initiatedPrice
                });
            });
        }
        if ($('#paymentAdjustmentCheckbox').is(':checked')) {
            data.adjustmentAmount = $('#adjustmentAmount').val();
        }
        return data;
    }

    function formatPrice(price) {
        if (price === 'N/A') return price;
        return parseInt(price).toLocaleString('en-US');
    }
    function modalforinitiated(transitionId) {
  $('#paymentModal').modal('show');
  document.getElementById('transitionId').value = transitionId;
  fetch('/api/banks')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok ' + response.statusText);
      }
      return response.json();
    })
    .then(data => {
      const bankSelect = document.getElementById('bank');
      bankSelect.innerHTML = '<option value="">Select Bank</option>';
      data.forEach(bank => {
        const option = document.createElement('option');
        option.value = bank.id;
        option.textContent = bank.bank_name;
        bankSelect.appendChild(option);
      });
    })
    .catch(error => console.error('There was a problem with the fetch operation:', error));
}

function fetchBankAccounts() {
  const bankId = document.getElementById('bank').value;
  
  if (bankId) {
    fetch(`/api/bank-accounts?bank_id=${bankId}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
      })
      .then(data => {
        const bankAccountSelect = document.getElementById('bankAccount');
        bankAccountSelect.innerHTML = '<option value="">Select Bank Account</option>';
        data.forEach(account => {
          const option = document.createElement('option');
          option.value = account.id;
          option.textContent = account.account_number;
          bankAccountSelect.appendChild(option);
        });
      })
      .catch(error => console.error('There was a problem with the fetch operation:', error));
  }
}

function submitPaymentForm() {
  const formData = new FormData(document.getElementById('paymentForm'));

  fetch('/submit-payment', {
    method: 'POST',
    body: formData,
    headers: {
      'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
    }
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok ' + response.statusText);
    }
    return response.json();
  })
  .then(data => {
    if (data.success) {
        alertify.success('Payment submitted successfully');
      $('#paymentModal').modal('hide');
      const transitionId = formData.get('transitionId');
      const button = document.querySelector(`button[data-transition-id="${transitionId}"]`);
      if (button) {
        button.style.display = 'none';
      }
    } else {
      alert('Error submitting payment: ' + data.message);
    }
  })
  .catch(error => console.error('There was a problem with the fetch operation:', error));
}
</script>
<script>
    $(document).ready(function() {
        $('#approveButton').click(function() {
            var transitionId = $(this).data('transition-id');
            $.ajax({
                url: '{{ route("approve.transition") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    transition_id: transitionId
                },
                success: function(response) {
                    if(response.success) {
                        alertify.success('Transitions approved Successfully');
                        $(`button[data-transition-id="${transitionId}"]`).hide();
                        $(`button[data-reject-id="${transitionId}"]`).hide();
                    } else {
                        alert('Failed to approve transition.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('An error occurred while processing your request.');
                }
            });
        });
    });
    function showRejectModalreleased(transitionId) {
  $('#rejectModal').modal('show');
  $('#submitReject').data('transition-id', transitionId);
}
$(document).ready(function() {
  $('#submitReject').click(function() {
    var transitionId = $(this).data('transition-id');
    var remarks = $('#rejectRemarks').val();

    $.ajax({
      url: '/reject-transition', // The URL to send the request to
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        transition_id: transitionId,
        remarks: remarks
      },
      success: function(response) {
        $('#rejectModal').modal('hide');
        // Handle the response from the controller
        alertify.success('Transitions rejected Successfully');
        // Hide the buttons
        var buttonRow = $('button[data-transition-id="' + transitionId + '"]').closest('td');
        buttonRow.find('.btn').hide();
      },
      error: function(xhr, status, error) {
        // Handle any errors
        alert('An error occurred: ' + xhr.responseText);
      }
    });
  });
});
$(document).ready(function() {
  $('#submitRejectlinitiate').click(function() {
    var transitionId = $(this).data('transition-id');
    var remarks = $('#rejectRemarkslinitiate').val();
    $.ajax({
      url: '/reject-transition-linitiate',
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        transition_id: transitionId,
        remarks: remarks
      },
      success: function(response) {
        $('#rejectModallinitiate').modal('hide');
        alertify.success('Transitions rejected Successfully');
        var row = $('button[data-reject-id="' + transitionId + '"]').closest('tr');
        row.find('.btn').hide();
      },
      error: function(xhr, status, error) {
        alert('An error occurred: ' + xhr.responseText);
      }
    });
  });
});
function openSwiftUploadModal(transitionId) {
    $('#transition_id').val(transitionId);
    $('#swiftUploadModal').modal('show');
}

$('#uploadSwiftButton').on('click', function() {
    var formData = new FormData($('#swiftUploadForm')[0]);
    var transitionId = $('#transition_id').val();

    $.ajax({
        url: '/upload-swift-file',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            $('#swiftUploadModal').modal('hide');
            $('button[data-transition-id="' + transitionId + '"]').hide();
            alert('File uploaded successfully!');
        },
        error: function(response) {
            alert('Failed to upload file. ' + response.responseJSON.message);
        }
    });
});
function openSwiftDetailsModal(transitionId) {
    $.ajax({
        url: '/get-swift-details/' + transitionId,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                let contentHtml = `
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                <tr>
                                    <th>Account Number</th>
                                    <td>${data.account_number}</td>
                                </tr>
                                <tr>
                                    <th>Currency</th>
                                    <td>${data.currency}</td>
                                </tr>
                                <tr>
                                    <th>Current Balance</th>
                                    <td>${data.current_balance}</td>
                                </tr>
                                <tr>
                                    <th>Bank Name</th>
                                    <td>${data.bank_name}</td>
                                </tr>`;

                if (data.transition_file) {
                    contentHtml += `
                        <tr>
                            <th>Transaction File</th>
                            <td><iframe src="${data.transition_file}" width="100%" height="400px"></iframe></td>
                        </tr>
                    `;
                }

                if (data.swift_copy_file) {
                    contentHtml += `
                        <tr>
                            <th>Swift Copy File</th>
                            <td><iframe src="${data.swift_copy_file}" width="100%" height="400px"></iframe></td>
                        </tr>
                    `;
                }
                contentHtml += `
                            </tbody>
                        </table>
                    </div>
                `;

                $('#swiftDetailsContent').html(contentHtml);
                $('#swiftDetailsModal').modal('show');
            } else {
                alert('Failed to fetch details: ' + response.message);
            }
        },
        error: function(response) {
            alert('Failed to fetch details: ' + response.responseJSON.message);
        }
    });
}
</script>
<script>
$(document).ready(function() {
    $('.holdButtonveh').on('click', function(e) {
        e.preventDefault();
        
        var url = $(this).data('url');
        var status = $(this).data('status');
        var $row = $(this).closest('tr'); // Get the closest row

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                // Handle success
                alert('Status updated successfully!');
                location.reload();
            },
            error: function(xhr) {
                // Handle error
                alert('Failed to update status!');
            }
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dnSelection = document.getElementById('dnSelection');
    const fullPOInput = document.getElementById('fullPOInput');
    const vehicleTabledn = document.getElementById('vehicleTabledn');
    const vehicleTableBodydn = document.getElementById('vehicleTableBodydn');
    const saveDNButton = document.getElementById('saveDNButton');
    
    dnSelection.addEventListener('change', function () {
        if (dnSelection.value === 'full') {
            fullPOInput.classList.remove('d-none');
            vehicleTabledn.classList.add('d-none');
        } else {
            fullPOInput.classList.add('d-none');
            vehicleTabledn.classList.remove('d-none');
            // Fetch vehicle data from server using the existing route
            const purchasingOrderId = "{{ $purchasingOrder->id }}"; // Ensure this variable is correctly populated in your Blade template

            fetch(`/getVehicles/${purchasingOrderId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Clear existing table data
                    vehicleTableBodydn.innerHTML = '';

                    // Populate table with vehicle data
                    data.forEach(vehicle => {
                        const vin = vehicle.vin ? vehicle.vin : ''; // Check if VIN is null, and if so, display as blank
                        const row = `<tr>
                            <td>${vehicle.id}</td>
                            <td>${vehicle.variant.master_model_lines.model_line}</td>
                            <td>${vehicle.variant.name}</td>
                            <td>${vin}</td>
                            <td><input type="text" class="form-control dn-number-input" data-vehicle-id="${vehicle.id}"></td>
                        </tr>`;
                        vehicleTableBodydn.insertAdjacentHTML('beforeend', row);
                    });
                    console.log('Vehicle table body:', vehicleTableBodydn.innerHTML);
                })
                .catch(error => {
                    console.error('Error fetching vehicle data:', error);
                });
        }
    });

    saveDNButton.addEventListener('click', function () {
        const purchasingOrderId = "{{ $purchasingOrder->id }}"; // Ensure this variable is correctly populated in your Blade template

        if (dnSelection.value === 'full') {
            const dnNumberFullPO = document.getElementById('dnNumberFullPO').value;

            // Send Full PO DN Number to controller
            fetch('/save-dn-numbers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    purchasingOrderId: purchasingOrderId,
                    dnNumber: dnNumberFullPO,
                    type: 'full'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Handle success response
                console.log('DN number saved successfully:', data);
            })
            .catch(error => {
                console.error('Error saving DN number:', error);
            });
        } else {
            const vehicleInputs = document.querySelectorAll('.dn-number-input');
            const vehicleDNData = [];

            vehicleInputs.forEach(input => {
                const vehicleId = input.getAttribute('data-vehicle-id');
                const dnNumber = input.value;
                vehicleDNData.push({ vehicleId: vehicleId, dnNumber: dnNumber });
            });

            // Send Vehicle DN Numbers to controller
            fetch('/save-dn-numbers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    purchasingOrderId: purchasingOrderId,
                    vehicleDNData: vehicleDNData,
                    type: 'vehicle'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Handle success response
                console.log('Vehicle DN numbers saved successfully:', data);
            })
            .catch(error => {
                console.error('Error saving DN numbers:', error);
            });
        }
    });
});
</script>
@endsection
