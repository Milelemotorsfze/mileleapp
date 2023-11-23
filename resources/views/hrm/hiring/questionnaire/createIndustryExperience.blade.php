<div class="modal" id="createNewIndustryExperience" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenteredLabel" style="text-align:center;"> Create New Industry Experience </h5>
				<button type="button" class="btn btn-secondary btn-sm close form-control" data-dismiss="modal" aria-label="Close" onclick="closemodal()">
				<span aria-hidden="true">X</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" enctype="multipart/form-data" id="createNewIndustryExperienceForm">
					<div class="row modal-row">
						<div class="col-xxl-12 col-lg-12 col-md-12">
							<span class="error">* </span>
							<label for="Industry-Experience" class="col-form-label text-md-end ">Industry Experience</label>
						</div>
						<div class="col-xxl-12 col-lg-12 col-md-12">
							<input type="text" id="new_industry_experience" class="form-control @error('new_industry_experience') is-invalid @enderror" name="new_industry_experience" value="" required>
							<span id="NewIndustryExperienceError" class="required-class paragraph-class"></span>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" onclick="closemodal()"><i class="fa fa-times"></i> Close</button>
				<button type="button" class="btn btn-primary btn-sm" id="createIndustryExpId" style="float: right;">
				<i class="fa fa-check" aria-hidden="true"></i> Submit</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.modal-button').on('click', function() {
		$('#createNewIndustryExperience').modal('show');
	});
    });   	
	function closemodal() {
	    $('.overlay').hide();
		$('#createNewIndustryExperience').modal('hide');
	}
    $('#createIndustryExpId').on('click', function() {
	    var value = $('#new_industry_experience').val();
	    if(value == '') {
	        $msg = 'Industry Experience is Required';
	        showNewIndustryExperienceError($msg);
	    }
	    else {
	        $.ajax({
	            url:"{{url('industry-experience')}}",
	            type: "POST",
	            data:{
	                name: value,
	                _token: '{{csrf_token()}}'
	            },
	            dataType : 'json',
	            success: function(result) { console.log(result);
	                if(result.error) {
	                    $msg = result.error;
	                    showNewIndustryExperienceError($msg);
	                }
					else {
	                    $('.overlay').hide();
						closemodal();
	                    $('#requested_industry_experience').append("<option value='" + result.id + "'>" + result.name + "</option>");
	                    $('#requested_industry_experience').val(result.id);
	                    removeNewIndustryExperienceError();						
	                }
	            }
	        });
	    }
	});
	function showNewIndustryExperienceError($msg) {
		document.getElementById("newIndustryExperienceError").textContent=$msg;
	    document.getElementById("new_industry_experience").classList.add("is-invalid");
	    document.getElementById("newIndustryExperienceError").classList.add("paragraph-class");
	}
	function removeNewIndustryExperienceError() {
	    document.getElementById("newIndustryExperienceError").textContent="";
	    document.getElementById("new_industry_experience").classList.remove("is-invalid");
	    document.getElementById("newIndustryExperienceError").classList.remove("paragraph-class");
	}
</script>