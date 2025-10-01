@include('work_order.export_exw.update_status')
@if(isset($workOrder))
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-wo-status-log']);
	@endphp
	@if ($hasPermission)
		<a class="me-2 btn btn-sm btn-info"
			href="{{route('woStatusHistory',$workOrder->id)}}">
			<i class="fas fa-eye"></i> Status Log
		</a>
	@endif
@endif
@if(isset($workOrder) && $workOrder->sales_support_data_confirmation_at != '' && $workOrder->can_revert_confirmation == 'yes')
	<a title="Revert Sales Support Data Confirmation" class="me-2 btn btn-sm btn-info revert-btn-sales-approval" data-id="{{ $workOrder->id }}">
		<i class="fas fa-hourglass-start" title="Revert Sales Support Data Confirmation"></i> Revert Sales Support Data Confirmation
	</a>
@elseif(isset($workOrder) && $workOrder->sales_support_data_confirmation_at == '')
	<a title="Sales Support Data Confirmation" class="me-2 btn btn-sm btn-info btn-sales-approval" data-id="{{ isset($workOrder) ? $workOrder->id : '' }}">
	<i class="fas fa-hourglass-start"></i> Sales Support Data Confirmation</a>
@endif
@if(isset($workOrder) && isset($workOrder->financePendingApproval) && $workOrder->can_show_fin_approval == 'yes')
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['do-finance-approval']);
	@endphp
	@if ($hasPermission)
		<a title="Finance Approval" class="me-2 btn btn-sm btn-info" 
		data-bs-toggle="modal" data-bs-target="#financeApprovalModal">
			<i class="fas fa-hourglass-start" title="Finance Approval"></i> Fin. Approval
		</a>
	@endif
    <div class="modal fade" id="financeApprovalModal" tabindex="-1" aria-labelledby="financeApprovalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="financeApprovalModalLabel">Fin. Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="financeComment" class="form-label">Comments</label>
                        <textarea class="form-control" id="financeComment" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-danger btn-finance-approval" id="rejectButton" 
					data-id="{{ $workOrder->financePendingApproval->id}}"
					data-status="reject">Reject</button>
                    <button type="button" class="btn btn-sm btn-success btn-finance-approval" id="approveButton" 
					data-id="{{ $workOrder->financePendingApproval->id}}"
					data-status="approve">Approve</button>
                </div>
            </div>
        </div>
    </div>
@endif
@if(isset($workOrder))
@if($workOrder->coo_approval_status != '')
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-coo-approval-history']);
	@endphp
	@if ($hasPermission)
		<a class="me-2 btn btn-sm btn-info"
			href="{{route('fetchCooApprovalHistory',$workOrder->id)}}">
			<i class="fas fa-eye"></i> COO Approval Log
		</a>
	@endif
@endif
@endif
@if(isset($workOrder) && isset($workOrder->cooPendingApproval) && $workOrder->can_show_coo_approval == 'yes')
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['do-coo-office-approval']);
	@endphp
	@if ($hasPermission)
		<a title="COO Office Approval" class="me-2 btn btn-sm btn-info" 
		data-bs-toggle="modal" data-bs-target="#cooApprovalModal">
			<i class="fas fa-hourglass-start" title="COO Office Approval"></i> COO Office Approval
		</a>
	@endif
    <div class="modal fade" id="cooApprovalModal" tabindex="-1" aria-labelledby="cooApprovalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cooApprovalModalLabel">COO Office Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cooComment" class="form-label">Comments</label>
                        <textarea class="form-control" id="cooComment" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-danger btn-coe-office-approval" id="rejectButton" 
					data-id="{{ $workOrder->cooPendingApproval->id}}"
					data-status="reject">Reject</button>
                    <button type="button" class="btn btn-sm btn-success btn-coe-office-approval" id="approveButton" 
					data-id="{{ $workOrder->cooPendingApproval->id}}"
					data-status="approve">Approve</button>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="approvalModalLabel">COO Office Direct Approval</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
            <label for="approvalComments">Comments</label>
            <textarea class="form-control" id="approvalComments" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="submitApproval">Submit</button>
        </div>
        </div>
    </div>
</div>
@if(isset($workOrder))
@if($workOrder->finance_approval_status != '')
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-finance-approval-history']);
	@endphp
	@if ($hasPermission)
		<a class="me-2 btn btn-sm btn-info" 
			href="{{route('fetchFinanceApprovalHistory',$workOrder->id)}}">
			<i class="fas fa-eye"></i> Fin. Approval Log
		</a>
	@endif
@endif
@endif

@include('work_order.export_exw.doc_approval')
@if(isset($workOrder))
	@if($workOrder->sales_support_data_confirmation_at != '' && $workOrder->coo_approval_status == 'Approved' && $workOrder->finance_approval_status == 'Approved')
	@php
		$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-doc-status-log']);
		@endphp
		@if ($hasPermission)
			<a class="me-2 btn btn-sm btn-info"
				href="{{route('docStatusHistory',$workOrder->id)}}">
				<i class="fas fa-eye"></i> Doc Status Log
			</a>
		@endif
	@endif
@endif
<script type="text/javascript">

    $(document).ready(function () { 
		$('.btn-sales-approval').click(function (e) { 
			var id = $(this).attr('data-id');
			let url = '{{ route('work-order.sales-approval') }}';
			var confirm = alertify.confirm('Are you sure you want to confirm this work order ?',function (e) {
				if (e) {
					$.ajax({
						type: "POST",
						url: url,
						dataType: "json",
						data: {
							id: id,
							_token: '{{ csrf_token() }}'
						},
						success: function (data) {						
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {
								window.location.reload();
								alertify.error("Can't Confirm, It was Confirmed already..")
							}
						}
					});
				}
			}).set({title:"Confirmation"})
		})
		$('.revert-btn-sales-approval').click(function (e) { 
			var id = $(this).attr('data-id');
			let url = '{{ route('work-order.revert-sales-approval') }}';
			var confirm = alertify.confirm('Are you sure you want to revert this work order confirmation ?',function (e) {
				if (e) {
					$.ajax({
						type: "POST",
						url: url,
						dataType: "json",
						data: {
							id: id,
							_token: '{{ csrf_token() }}'
						},
						success: function (data) {						
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {
								window.location.reload();
								alertify.error("Can't Revert, It was reverted already..")
							}
						}
					});
				}
			}).set({title:"Confirmation"})
		})
		$('.btn-finance-approval').click(function (e) { 
			var id = $(this).attr('data-id');
			var status = $(this).attr('data-status');
			var comments = $('#financeComment').val();
			let url = '{{ route('work-order.finance-approval') }}';
			var confirm = alertify.confirm('Are you sure you want to '+status+' this work order ?',function (e) {
				if (e) {
					$.ajax({
						type: "POST",
						url: url,
						dataType: "json",
						data: {
							id: id,
							status: status,
							comments: comments,
							_token: '{{ csrf_token() }}'
						},
						success: function (data) {						
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {
								window.location.reload();
								alertify.error("Can't Approve, It was approved already..")
							}
						}
					});
				}
			}).set({title:"Confirmation"})
		})
		$('.btn-coe-office-approval').click(function (e) { 
			var id = $(this).attr('data-id');
			var status = $(this).attr('data-status');
			var comments = $('#cooComment').val();
			let url = '{{ route('work-order.coe-office-approval') }}';
			var confirm = alertify.confirm('Are you sure you want to '+status+' this work order ?',function (e) {
				if (e) {
					$.ajax({
						type: "POST",
						url: url,
						dataType: "json",
						data: {
							id: id,
							status: status,
							comments: comments,
							_token: '{{ csrf_token() }}'
						},
						success: function (data) {						
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {
								window.location.reload();
								alertify.error("Can't Approve, It was approved already..")
							}
						}
					});
				}
			}).set({title:"Confirmation"})
		})
		$('.btn-coe-office-direct-approval').click(function (e) {
			var id = $(this).attr('data-id');
			$('#approvalModal').data('id', id).modal('show');
		});

		$('#submitApproval').click(function (e) {
			var id = $('#approvalModal').data('id');
			var comments = $('#approvalComments').val();
			let url = '{{ route('work-order.coe-office-approval') }}';

			if (!comments) {
			alertify.error("Please add comments.");
			return;
			}

			var confirm = alertify.confirm('Are you sure you want to approve this work order?', function (e) {
			if (e) {
				$.ajax({
				type: "POST",
				url: url,
				dataType: "json",
				data: {
					id: id,
					comments: comments,
					_token: '{{ csrf_token() }}'
				},
				success: function (data) {
					$('#approvalModal').modal('hide');
					if (data == 'success') {
					window.location.reload();
					alertify.success("Successfully Approved");
					} else if (data == 'error') {
					window.location.reload();
					alertify.error("Can't Approve, It was approved already.");
					}
				}
				});
			}
			}).set({ title: "Confirmation" });
		});
    });

</script>