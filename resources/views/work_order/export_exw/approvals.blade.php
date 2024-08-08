
@if(isset($workOrder) && isset($workOrder->financePendingApproval))
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['do-finance-approval']);
	@endphp
	@if ($hasPermission)
		<a title="Finance Approval" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-info" 
		data-bs-toggle="modal" data-bs-target="#financeApprovalModal">
			<i class="fas fa-hourglass-start" title="Finance Approval"></i> Finance Approval
		</a>
	@endif
    <!-- Modal -->
    <div class="modal fade" id="financeApprovalModal" tabindex="-1" aria-labelledby="financeApprovalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="financeApprovalModalLabel">Finance Approval</h5>
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
@elseif(isset($workOrder) && $workOrder->finance_approval_status == 'Approved')	
	<a style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-success" >
		<i class="fa fa-check-square" aria-hidden="true"></i> Finance Approved
	</a>
@elseif(isset($workOrder) && $workOrder->finance_approval_status == 'Rejected')	
	<a style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-danger" >
		<i class="fa fa-times" aria-hidden="true"></i> Finance Rejected
	</a>
@endif
@if(isset($workOrder) && isset($workOrder->cooPendingApproval))
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['do-coo-office-approval']);
	@endphp
	@if ($hasPermission)
		<a title="COO Office Approval" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-info" 
		data-bs-toggle="modal" data-bs-target="#cooApprovalModal">
			<i class="fas fa-hourglass-start" title="COO Office Approval"></i> COO Office Approval
		</a>
	@endif
    <!-- Modal -->
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
@elseif(isset($workOrder) && $workOrder->coo_approval_status == 'Approved')	
	<a style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-success" >
		<i class="fa fa-check-square" aria-hidden="true"></i> COO Approved
	</a>
@elseif(isset($workOrder) && $workOrder->coo_approval_status == 'Rejected')	
	<a style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-danger" >
		<i class="fa fa-times" aria-hidden="true"></i> COO Rejected
	</a>
@endif

<!-- Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="approvalModalLabel">COO Office Direct Approval</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <!-- <span aria-hidden="true">&times;</span> -->
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
            <label for="approvalComments">Comments</label>
            <textarea class="form-control" id="approvalComments" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
            <button type="button" class="btn btn-primary" id="submitApproval">Submit</button>
        </div>
        </div>
    </div>
</div>
@if(isset($workOrder))
@if($workOrder->coo_approval_status != '')
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-coo-approval-history']);
	@endphp
	@if ($hasPermission)
		<a style="margin-top:0px;margin-bottom:1.25rem; float:right!important; margin-left:3px!important;" class="btn btn-sm btn-info"
			href="{{route('fetchCooApprovalHistory',$workOrder->id)}}">
			<i class="fas fa-eye"></i> COO Approval History
		</a>
	@endif
@endif
@if($workOrder->finance_approval_status != '')
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-finance-approval-history']);
	@endphp
	@if ($hasPermission)
		<a style="margin-top:0px;margin-bottom:1.25rem; float:right!important;" class="btn btn-sm btn-info" 
			href="{{route('fetchFinanceApprovalHistory',$workOrder->id)}}">
			<i class="fas fa-eye"></i> Finance Approval History
		</a>
	@endif
@endif
@endif
<script type="text/javascript">

    $(document).ready(function () { 
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
			var comments = $('#financeComment').val();
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