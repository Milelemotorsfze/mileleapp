@if($letterOfIndent->is_expired == false)
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Status Update">
        <i class="fa fa-list" aria-hidden="true"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-start"> 
           
                @if($type == 'NEW')
                    @can('LOI-approve')
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-approve');
                        @endphp
                        @if ($hasPermission)
                        <li>
                            <button type="button" data-id="{{ $letterOfIndent->id }}" data-status="{{ \App\Models\LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL }}"
                            data-url="{{ route('letter-of-indent.request-approval') }}" style="width:100%; margin-top:2px; margin-bottom:2px;"
                                class="btn btn-warning btn-sm btn-request-supplier-approval" title="Send For Supplier Approval">Request Supplier Approval </button>              
                        </li>
                        @endif
                    @endcan
                @elseif($type == 'WAITING_FOR_TTC_APPROVAL')
                    <li>
                        <button type="button" data-id="{{ $letterOfIndent->id }}" data-status="{{ \App\Models\LetterOfIndent::LOI_STATUS_TTC_APPROVED }}"
                        data-message="Approve" data-url="{{ route('letter-of-indent.request-approval') }}" style="width:100%; margin-top:2px; margin-bottom:2px;"
                            class="btn btn-primary btn-sm ttc-approve-reject-btn" title="TTC Approval">Approve </button>  
                    </li>
                    <li>
                        <button type="button" class="btn btn-danger btn-sm mt-1 ttc-approve-reject-btn" data-url="{{ route('letter-of-indent.request-approval') }}"
                        data-id="{{ $letterOfIndent->id }}" data-message="Reject" data-status="{{ \App\Models\LetterOfIndent::LOI_STATUS_TTC_REJECTED }}"
                         style="width:100%; margin-top:2px; margin-bottom:2px;" > Reject </button> 
                    </li>    
                @elseif($type == 'WAITING_FOR_APPROVAL')
                    @can('loi-status-update')
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-status-update');
                        @endphp
                        @if ($hasPermission)
                        <li>
                            <button type="button" onclick="checkExistingInPFI({{$letterOfIndent->id}})" 
                            style="width:100%; margin-top:2px; margin-bottom:2px;" data-id="#update-loi-status-{{ $letterOfIndent->id }}"
                            class="btn btn-info btn-sm" title="Reverse Update of Status to New">Status Update</button>  
                        </li>            
                        @endif
                    @endcan
                    @can('loi-supplier-approve')
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-supplier-approve');
                        @endphp
                        @if ($hasPermission)
                        <li>
                            <button type="button" class="btn btn-primary modal-button btn-sm mt-1"
                            style="width:100%; margin-top:2px; margin-bottom:2px;" data-bs-toggle="modal"
                                    data-bs-target="#approve-LOI-{{ $letterOfIndent->id }}" > Approve </button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-danger modal-button btn-sm mt-1"
                            style="width:100%; margin-top:2px; margin-bottom:2px;" data-bs-toggle="modal"
                                    data-bs-target="#reject-LOI-{{$letterOfIndent->id}}"> Reject </button> 
                        </li>               
                        @endif
                    @endcan
                @endif
        </ul>
    </div>
    @endif
   
    <!-- To Reject LOI -->

    <div class="modal fade" id="reject-LOI-{{$letterOfIndent->id}}" data-bs-backdrop="static"
                                             tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Reject LOI </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                    <div class="modal-body p-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <dt class="form-label font-size-13 text-muted">Customer :</dt>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <dl> {{  $letterOfIndent->client->name }}</dl>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <dt class="form-label font-size-13 text-muted">Category :</dt>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <dl>{{ $letterOfIndent->category }} </dl>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <dt class="form-label font-size-13 text-muted">LOI Date :</dt>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <dl> {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }} </dl>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <dt class="form-label font-size-13 text-muted">So Number :</dt>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <dl>
                                                @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                    {{ $LoiSoNumber->so_number }}
                                                    @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                                @endforeach
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <dt class="form-label font-size-13 text-muted">Country :</dt>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <dl>{{ $letterOfIndent->country->name ?? '' }} </dl>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <dt class="form-label font-size-13 text-muted">Rejection Date :</dt>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <input type="date" id="rejection-date-{{$letterOfIndent->id }}" value=""
                                                    required class="form-control widthinput" max="{{ \Illuminate\Support\Carbon::today()->format('Y-m-d') }}" >
                                            <span id="rejection-date-error-{{ $letterOfIndent->id }}" class="text-danger"> </span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <dt class="form-label font-size-13 text-muted">Remark :</dt>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <textarea class="form-control" cols="75" name="review" id="rejection-review-{{ $letterOfIndent->id}}"  rows="5" required></textarea>
                                            <span id="rejection-review-error-{{ $letterOfIndent->id }}" class="text-danger"> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary " onclick="reject({{$letterOfIndent->id}})" >Submit</button>
                    </div>
            </div>
        </div>
    </div>
    <!-- To approve LOI -->
    <div class="modal fade" id="approve-LOI-{{$letterOfIndent->id}}" data-bs-backdrop="static"
            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Approve LOI </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
              
                <div class="modal-body p-3">
                    <div class="col-12">
                        <div class="row">

                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <dt class="form-label font-size-13 text-muted">Customer :</dt>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <dl> {{  $letterOfIndent->client->name }}</dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <dt class="form-label font-size-13 text-muted">Category :</dt>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <dl>{{ $letterOfIndent->category }} </dl>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <dt class="form-label font-size-13 text-muted">LOI Date :</dt>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <dl> {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d')  }} </dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <dt class="form-label font-size-13 text-muted">So Number :</dt>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <dl>
                                    @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                        {{ $LoiSoNumber->so_number }}
                                        @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                    @endforeach
                                </dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <dt class="form-label font-size-13 text-muted">Country :</dt>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <dl>{{ $letterOfIndent->country->name ?? '' }} </dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <dt class="form-label font-size-13 text-muted">Approval Date :</dt>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <input type="date" name="loi_approval_date" id="approval-date-{{$letterOfIndent->id}}"
                                        required class="form-control widthinput">
                                <span id="approval-date-error-{{$letterOfIndent->id}}" class="text-danger"> </span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <dt class="form-label font-size-13 text-muted">Remark :</dt>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <textarea class="form-control" cols="75" name="review" id="approval-review-{{ $letterOfIndent->id }}"  rows="5" required></textarea>
                                <span id="approval-review-error-{{$letterOfIndent->id}}" class="text-danger"> </span>
                            </div>
                        </div>
                      
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="approve({{ $letterOfIndent->id }})" >Submit</button>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- To Update status as New -->
    <div class="modal fade" id="update-loi-status-{{$letterOfIndent->id}}" data-bs-backdrop="static"
                                             tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Update Status </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('letter-of-indents.status-update', $letterOfIndent->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-12 col-sm-12">
                                <dt class="form-label font-size-13 text-muted mt-2">Status :</dt>
                            </div>
                            <div class="col-lg-10 col-md-12 col-sm-12">
                                <select class="form-control" name="status">
                                    <option value="{{ \App\Models\LetterOfIndent::LOI_STATUS_NEW }}">{{ \App\Models\LetterOfIndent::LOI_STATUS_NEW }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>               
            </div>
        </div>
    </div>
     <script>
        function checkExistingInPFI(id) {
             let url =  "{{ route('loi.get-is-editable') }}";
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success:function (data) {
                    if(data.is_editable == 1) {
                        $('#update-loi-status-'+id).modal('show');
                    }else{
                        alertify.confirm('This LOI is already used to create PFI, '+ data.is_editable +' Please remove these items from PFI to update this LOI.',function (e) {
                        });
                    }
                }
            });
        }
        $('.ttc-approve-reject-btn').on('click',function(){
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            let status =  $(this).attr('data-status');
            let msg =  $(this).attr('data-message');
            let successMsg = 'Approved';
            if(msg !== 'Approve') {
                successMsg = 'Rejected';
               
            }
            var confirm = alertify.confirm('Are you sure? Do You want to '+ msg +' this LOI?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            id: id,
                            status:status,
                            type:successMsg,
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            var table1 = $('.waiting-for-ttc-approval-table').DataTable();
                                table1.ajax.reload();
                            var table = $('.supplier-response-table').DataTable();
                                table.ajax.reload();
                            alertify.success('LOI '+ successMsg +' Successfully.');
                        }
                    });
                }
            }).set({title:"Confirm ?"})
        });
      
         $('.btn-request-supplier-approval').on('click',function(){
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            let status =  $(this).attr('data-status');
           
            var confirm = alertify.confirm('Are you sure? Do you want to send this LOI for supplier Approval?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            id: id,
                            status,status,
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            var table = $('.new-LOI-table').DataTable();
                                table.ajax.reload();
                            var table1 = $('.waiting-for-approval-table').DataTable();
                                table1.ajax.reload();
                            var table3 = $('.waiting-for-ttc-approval-table').DataTable();
                                table3.ajax.reload();
                            alertify.success('Supplier Approval Request Send Successfully.');
                        }
                    });
                }
            }).set({title:"Confirm ?"})
        });
      
        function approve(id){
            let review = $('#approval-review-'+id).val();
            let loi_approval_date = $('#approval-date-'+id).val();
               if(loi_approval_date.length > 0) {
                removeApprovalDateError(id,'APPROVE');
                    if(review.length > 0) {
                    removeReviewError(id,'APPROVE');

                        var confirm = alertify.confirm('Are you sure you ! Do you want to Approve this LOI ?',function (e) {
                        if (e) {
                            $('#approve-LOI-'+id).modal('hide');
                        
                            $.ajax({
                                url: "{{ route('letter-of-indents.supplier-approval') }}",
                                type: 'POST',
                                dataType:"json",
                                data: {
                                    id: id,
                                    status: 'APPROVE',
                                    loi_approval_date: loi_approval_date,
                                    review:review,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response)
                                {
                                    var table1 = $('.waiting-for-approval-table').DataTable();
                                        table1.ajax.reload();
                                    var table2 = $('.waiting-for-ttc-approval-table').DataTable();
                                        table2.ajax.reload();
                                    var table3 = $('.supplier-response-table').DataTable();
                                        table3.ajax.reload();
                                    alertify.success('Supplier Approved successfully.'+response);
                                    },
                                    error: function (error) {
                                    }
                                });
                        }
                        }).set({title:"Delete Item"})
                    }else{
                        let msg = "This field is required";
                        showReviewError(msg,id,'APPROVE');

                    }
                }else{
                    let msg = "This field is required";
                    showApprovalDateError(msg,id,'APPROVE')

                }
        }
        function reject(id){
            let review = $('#rejection-review-'+id).val();
            let loi_approval_date = $('#rejection-date-'+id).val();
               if(loi_approval_date.length > 0) {
                    removeApprovalDateError(id,'REJECT');
                    if(review.length > 0) {
                        removeReviewError(id,'REJECT');
                        var confirm = alertify.confirm('Are you sure you ! Do you want to Reject this LOI ?',function (e) {
                        if (e) {
                            $('#reject-LOI-'+id).modal('hide');
                        
                            $.ajax({
                                url: "{{ route('letter-of-indents.supplier-approval') }}",
                                type: 'POST',
                                dataType:"json",
                                data: {
                                    id: id,
                                    status: 'REJECTED',
                                    loi_approval_date: loi_approval_date,
                                    review:review,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response)
                                {
                                    var table1 = $('.waiting-for-approval-table').DataTable();
                                    table1.ajax.reload();
                                    var table3 = $('.supplier-response-table').DataTable();
                                        table3.ajax.reload();
                                    alertify.success('Supplier rejected successfully.');

                                    },
                                    error: function (error) {
                                    }
                                });
                        }
                        }).set({title:"Delete Item"})
                    }else{
                        let msg = "This field is required";
                        showReviewError(msg,id,'REJECT');

                    }
                }else{
                    let msg = "This field is required";
                    showApprovalDateError(msg,id,'REJECT')

                }
        }
        
        
    function showReviewError(msg,id,status)
    {
        if(status == 'APPROVE') {
            document.getElementById("approval-review-error-"+id).textContent=msg;
            document.getElementById("approval-review-"+id).classList.add("is-invalid");
            document.getElementById("approval-review-error-"+id).classList.add("paragraph-class");
        }else{
            document.getElementById("rejection-review-error-"+id).textContent=msg;
            document.getElementById("rejection-review-"+id).classList.add("is-invalid");
            document.getElementById("rejection-review-error-"+id).classList.add("paragraph-class");
        }
        
    }
    function removeReviewError(id,status)
	{
        if(status == 'APPROVE') {
            document.getElementById("approval-review-error-"+id).textContent="";
	        document.getElementById("approval-review-"+id).classList.remove("is-invalid");
	        document.getElementById("approval-review-error-"+id).classList.remove("paragraph-class");
        }else{
            document.getElementById("rejection-review-error-"+id).textContent="";
	        document.getElementById("rejection-review-"+id).classList.remove("is-invalid");
	        document.getElementById("rejection-review-error-"+id).classList.remove("paragraph-class");  
        }
	   
	}
    function showApprovalDateError(msg,id,status)
    {
        if(status == 'APPROVE') {
            document.getElementById("approval-date-error-"+id).textContent=msg;
            document.getElementById("approval-date-"+id).classList.add("is-invalid");
            document.getElementById("approval-date-error-"+id).classList.add("paragraph-class");
        }else{
            document.getElementById("rejection-date-error-"+id).textContent=msg;
            document.getElementById("rejection-date-"+id).classList.add("is-invalid");
            document.getElementById("rejection-date-error-"+id).classList.add("paragraph-class");
        }
        
    }
    function removeApprovalDateError(id,status)
	{
        if(status == 'APPROVE') {
            document.getElementById("approval-date-error-"+id).textContent="";
	        document.getElementById("approval-date-"+id).classList.remove("is-invalid");
	        document.getElementById("approval-date-error-"+id).classList.remove("paragraph-class");
        }else{
            document.getElementById("rejection-date-error-"+id).textContent="";
	        document.getElementById("rejection-date-"+id).classList.remove("is-invalid");
	        document.getElementById("rejection-date-error-"+id).classList.remove("paragraph-class");
        }
	   
	}
       
  </script> 
