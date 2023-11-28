<div class="modal" id="createNewJobPosition" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenteredLabel" style="text-align:center;"> Create New Job Title </h5>
				<button type="button" class="btn btn-secondary btn-sm close form-control" data-dismiss="modal" aria-label="Close" onclick="closemodal()">
				<span aria-hidden="true">X</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" enctype="multipart/form-data" id="createNewJobTitleForm">
					<div class="row modal-row">
						<div class="col-xxl-12 col-lg-12 col-md-12">
							<span class="error">* </span>
							<label for="Job Title" class="col-form-label text-md-end ">Job Title</label>
						</div>
						<div class="col-xxl-12 col-lg-12 col-md-12">
							<input type="text" id="new_job_title" class="form-control @error('new_job_title') is-invalid @enderror" name="new_job_title" value="" required>
							<span id="newTitleError" class="required-class paragraph-class"></span>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" onclick="closemodal()"><i class="fa fa-times"></i> Close</button>
				<button type="button" class="btn btn-primary btn-sm" id="createTitleId" style="float: right;">
				<i class="fa fa-check" aria-hidden="true"></i> Submit</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.modal-button').on('click', function() {
		$('#createNewJobPosition').modal('show');
	});
    });   	
	function closemodal() {
	    $('.overlay').hide();
		$('#createNewJobPosition').modal('hide');
	}
    $('#createTitleId').on('click', function() {
	    var value = $('#new_job_title').val();
	    if(value == '') {
	        $msg = 'Job Title is Required';
	        showNewTitleError($msg);
	    }
	    else {
	        $.ajax({
	            url:"{{url('master-job-position')}}",
	            type: "POST",
	            data:{
	                name: value,
	                _token: '{{csrf_token()}}'
	            },
	            dataType : 'json',
	            success: function(result) { console.log(result);
	                if(result.error) {
	                    $msg = result.error;
	                    showNewTitleError($msg);
	                }
					else {
	                    $('.overlay').hide();
						closemodal();
	                    $('#requested_job_title').append("<option value='" + result.id + "'>" + result.name + "</option>");
	                    $('#requested_job_title').val(result.id);
	                    removeNewTitleError();						
	                }
	            }
	        });
	    }
	});
	function showNewTitleError($msg) {
		document.getElementById("newTitleError").textContent=$msg;
	    document.getElementById("new_job_title").classList.add("is-invalid");
	    document.getElementById("newTitleError").classList.add("paragraph-class");

		document.getElementById("newTitleError").style.color = "red";

	}
	function removeNewTitleError() {
	    document.getElementById("newTitleError").textContent="";
	    document.getElementById("new_job_title").classList.remove("is-invalid");
	    document.getElementById("newTitleError").classList.remove("paragraph-class");
	}
</script>