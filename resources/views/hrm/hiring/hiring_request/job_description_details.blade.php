@if(isset($data->jobDescription))
    <div class="col-xxl-6 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-header" style="background-color:#e8f3fd;">
                <div class="row">
                    <div class="col-lg-8 col-md-3 col-sm-6">
                        <h4 class="card-title"><center>Job Description</center></h4>
                    </div>
                    <div class="col-lg-4 col-md-3 col-sm-6">                       
                        @if(isset($data->jobDescription->is_auth_user_can_approve) && $data->jobDescription->is_auth_user_can_approve != '')
                            @if(isset($data->jobDescription->is_auth_user_can_approve['can_approve']))
                                @if($data->jobDescription->is_auth_user_can_approve['can_approve'] == true)
                                    <button style="float:right;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#reject-hiring-job-description-{{$data->jobDescription->id}}">
                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
                                    </button>
                                    <button style="float:right;margin-right:5px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
                                        data-bs-target="#approve-hiring-job-description-{{$data->jobDescription->id}}">
                                        <i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
                                    </button>
                                @endif
                            @endif
                        @endif
                        @if($data->jobDescription->status == 'pending' OR $data->jobDescription->status == 'rejected')
                        <a style="float:right;margin-right:5px;" title="Edit Job Description" class="btn btn-sm btn-info" href="{{route('employee-hiring-job-description.create-or-edit',[$data->jobDescription->id,$data->id])}}">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                    </a>
                        @endif
                        <div class="modal fade" id="approve-hiring-job-description-{{$data->jobDescription->id}}"
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Job Description Approval</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-3">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row mt-2">
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <label class="form-label font-size-13">Approval By Position</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            @if(isset($data->jobDescription->is_auth_user_can_approve['current_approve_position']))
                                                            {{$data->jobDescription->is_auth_user_can_approve['current_approve_position']}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <label class="form-label font-size-13">Approval By Name</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            @if(isset($data->jobDescription->is_auth_user_can_approve['current_approve_person']))
                                                            {{$data->jobDescription->is_auth_user_can_approve['current_approve_person']}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if(isset($data->jobDescription->is_auth_user_can_approve['current_approve_position']))
                                                    <input hidden id="current_approve_position_{{$data->jobDescription->id}}" name="current_approve_position" value="{{$data->jobDescription->is_auth_user_can_approve['current_approve_position']}}">
                                                    @endif
                                                    <div class="row mt-2">
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <label class="form-label font-size-13">Comments</label>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <textarea rows="5" id="comment-{{$data->jobDescription->id}}" class="form-control" name="comment">
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-success status-approve-button"
                                            data-id="{{ $data->jobDescription->id }}" data-status="approved">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="reject-hiring-job-description-{{$data->jobDescription->id}}"
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Rejection</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-3">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row mt-2">
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <label class="form-label font-size-13">Rejection By Position</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            @if(isset($data->jobDescription->is_auth_user_can_approve['current_approve_position']))
                                                            {{$data->jobDescription->is_auth_user_can_approve['current_approve_position']}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <label class="form-label font-size-13">Rejection By Name</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            @if(isset($data->jobDescription->is_auth_user_can_approve['current_approve_person']))
                                                            {{$data->jobDescription->is_auth_user_can_approve['current_approve_person']}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if(isset($data->jobDescription->is_auth_user_can_approve['current_approve_position']))
                                                    <input hidden id="current_approve_position_{{$data->jobDescription->id}}" name="current_approve_position" value="{{$data->jobDescription->is_auth_user_can_approve['current_approve_position']}}">
                                                    @endif
                                                    <div class="row mt-2">
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <label class="form-label font-size-13">Comments</label>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <textarea rows="5" id="comment-{{$data->jobDescription->id}}" class="form-control" name="comment">
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-danger  status-reject-button" data-id="{{ $data->jobDescription->id }}"
                                            data-status="rejected">Reject</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Job Title :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->jobTitle->name ?? ''}}</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Department :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->department->name ?? ''}}</span>
                    </div> -->
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Location :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->location->name ?? ''}}, {{$data->jobDescription->location->address ?? ''}}</span>
                    </div>
                    <!-- <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Reporting To :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->reportingTo->name ?? ''}} ( {{$data->jobDescription->reportingTo->empProfile->designation->name ?? ''}},{{$data->jobDescription->reportingTo->email ?? ''}} )</span>
                    </div> -->
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Job Purpose :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->job_purpose ?? ''}}</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Duties and Responsibilities (Generic) of the position :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->duties_and_responsibilities ?? ''}}</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Skills required at fulfill the position :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->skills_required ?? ''}}</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Position Qualifications (Academic & Professional) :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->position_qualification ?? ''}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" style="background-color:#e8f3fd;">
                <h4 class="card-title"><center>Job Description Approvals By</center></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <center><h4 class="card-title">Team Lead / Reporting Manager</h4></center>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Name :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->jobDescription->departmentHeadName->name ?? ''}}
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Status :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                    <label class="badge texttransform @if($data->jobDescription->action_by_department_head =='pending') badge-soft-info 
                                    @elseif($data->jobDescription->action_by_department_head =='approved') badge-soft-success 
                                    @else badge-soft-danger @endif">{{$data->jobDescription->action_by_department_head ?? ''}}</label>
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Date & Time :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        @if($data->jobDescription->department_head_action_at != '')
                                    {{ \Carbon\Carbon::parse($data->jobDescription->department_head_action_at)->format('d M Y, H:i:s') }}
                                    @endif
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Comments :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->jobDescription->comments_by_department_head ?? ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <center><h4 class="card-title">HR Manager</h4></center>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Name :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->jobDescription->hrManagerName->name ?? ''}}
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Status :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                    <label class="badge texttransform @if($data->jobDescription->action_by_hr_manager =='pending') badge-soft-info 
                                    @elseif($data->jobDescription->action_by_hr_manager =='approved') badge-soft-success 
                                    @else badge-soft-danger @endif">{{$data->jobDescription->action_by_hr_manager ?? ''}}</label>
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Date & Time :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        @if($data->jobDescription->hr_manager_action_at != '')
                                    {{ \Carbon\Carbon::parse($data->jobDescription->hr_manager_action_at)->format('d M Y, H:i:s') }}
                                    @endif
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        Comments :
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12">
                                        {{$data->jobDescription->comments_by_hr_manager ?? ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <script type="text/javascript">
    $(document).ready(function () {
        $('.status-reject-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        approveOrRejectHiringrequest(id, status)
	    })
	    $('.status-approve-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        approveOrRejectHiringrequest(id, status)
	    })
        function approveOrRejectHiringrequest(id, status) {
			var comment = $("#comment-"+id).val();
			var current_approve_position = $("#current_approve_position_"+id).val();
	        let url = '{{ route('employee-hiring-job-description.request-action') }}';
	        if(status == 'rejected') {
	            var message = 'Reject';
	        }else{
	            var message = 'Approve';
	        }
	        var confirm = alertify.confirm('Are you sure you want to '+ message +' this employee hiring job description ?',function (e) {
	            if (e) {
	                $.ajax({
	                    type: "POST",
	                    url: url,
	                    dataType: "json",
	                    data: {
	                        id: id,
	                        status: status,
	                        comment: comment,
							current_approve_position: current_approve_position,
	                        _token: '{{ csrf_token() }}'
	                    },
	                    success: function (data) {
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {

							}
	                    }
	                });
	            }
	
	        }).set({title:"Confirmation"})
	    }
    });
</script>      
@endif