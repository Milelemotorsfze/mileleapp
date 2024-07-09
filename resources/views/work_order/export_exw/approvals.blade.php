@if(isset($workOrder) && $workOrder->sales_support_data_confirmation_at != '')
    @if($workOrder->finance_approved_at != '' || $workOrder->coe_office_approved_at != '')
        <a title="Sales Support Data Confirmed" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-success">
            <i class="fas fa-check-circle" title="Sales Support Data Confirmed"></i> Sales Support Data Confirmed
        </a>
    @elseif($workOrder->finance_approved_at == '' && $workOrder->coe_office_approved_at == '')
        <a title="Revert Sales Support Data Confirmation" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-info revert-btn-sales-approval" data-id="{{ $workOrder->id }}">
            <i class="fas fa-hourglass-start" title="Revert Sales Support Data Confirmation"></i> Revert Sales Support Data Confirmation
        </a>
    @endif
@elseif(isset($workOrder) && $workOrder->sales_support_data_confirmation_at == '')
    <a  title="Sales Support Data Confirmation" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-info btn-sales-approval" data-id="{{ isset($workOrder) ? $workOrder->id : '' }}">
    <i class="fas fa-hourglass-start" title="Sales Support Data Confirmation"></i> Sales Support Data Confirmation
    </a>
@endif
@if(isset($workOrder) && $workOrder->finance_approved_at != '')
    <a  title="Finance Approved" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-success">
    <i class="fas fa-check-circle" title="Finance Approved"></i> Finance Approved
    </a>
@elseif(isset($workOrder) && $workOrder->sales_support_data_confirmation_at != '' && $workOrder->coe_office_approved_at == '')
    <a  title="Finance Approval" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-info btn-finance-approval" data-id="{{ isset($workOrder) ? $workOrder->id : '' }}">
    <i class="fas fa-hourglass-start" title="Finance Approval"></i> Finance Approval
    </a>
@endif
@if(isset($workOrder) && $workOrder->coe_office_approved_at != '')
    <a  title="COO Office" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-success">
    <i class="fas fa-check-circle" title="COO Office"></i> COO Office Approved
    </a>
@elseif(isset($workOrder) && $workOrder->sales_support_data_confirmation_at != '' && $workOrder->finance_approved_at != '')
    <a  title="COO Office Approval" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-info btn-coe-office-approval" data-id="{{ isset($workOrder) ? $workOrder->id : '' }}">
        <i class="fas fa-hourglass-start" title="COO Office Approval"></i> COO Office Approval
    </a>
@elseif(isset($workOrder) && $workOrder->sales_support_data_confirmation_at != '' && $workOrder->finance_approved_at == '')
    <a  title="COO Office Approval" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-info btn-coe-office-direct-approval" data-id="{{ isset($workOrder) ? $workOrder->id : '' }}">
        <i class="fas fa-hourglass-start" title="COO Office Approval"></i> COO Office Direct Approval
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
			let url = '{{ route('work-order.finance-approval') }}';
			var confirm = alertify.confirm('Are you sure you want to approve this work order ?',function (e) {
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
								alertify.error("Can't Approve, It was approved already..")
							}
						}
					});
				}
			}).set({title:"Confirmation"})
		})
		$('.btn-coe-office-approval').click(function (e) { 
			var id = $(this).attr('data-id');
			let url = '{{ route('work-order.coe-office-approval') }}';
			var confirm = alertify.confirm('Are you sure you want to approve this work order ?',function (e) {
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