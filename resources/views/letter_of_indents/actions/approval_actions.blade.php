    <style>

        </style>
    @if($letterOfIndent->is_expired == false)
        @if($type == 'NEW')
            @can('LOI-approve')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-approve');
                @endphp
                @if ($hasPermission)
                    <button type="button" data-id="{{ $letterOfIndent->id }}" data-url="{{ route('letter-of-indent.request-supplier-approval') }}"
                            class="btn btn-warning btn-sm btn-request-supplier-approval" title="Send For Supplier Approval">Send Request</button>              
                @endif
            @endcan
        @endif
        @if($type == 'WAITING_FOR_APPROVAL')
            @can('loi-supplier-approve')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-supplier-approve');
                @endphp
                @if ($hasPermission)
                    <button type="button" class="btn btn-primary modal-button btn-sm mt-1" data-bs-toggle="modal"
                            data-bs-target="#approve-LOI-{{ $letterOfIndent->id }}" > Approve </button>

                    <button type="button" class="btn btn-danger modal-button btn-sm mt-1" data-bs-toggle="modal"
                            data-bs-target="#reject-LOI-{{$letterOfIndent->id}}"> Reject </button>                
                @endif
            @endcan
        @endif
        @if($type == 'SUPPLIER_RESPONSE')
            @can('LOI-list')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
                @endphp
                @if ($hasPermission)
                    <button type="button" title="Update Utilization Quantity" class="btn btn-soft-green btn-sm mt-1" data-bs-toggle="modal" 
                    data-bs-target="#update-utilization-quantity-{{$letterOfIndent->id}}">
                        <i class="fa fa-save"></i>
                    </button>                   
                @endif
            @endcan
        @endif
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
                <form id="loi-reject-form" action="{{ route('letter-of-indents.supplier-approval') }}" method="POST">
                    @csrf
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
                                            <dl>{{ $letterOfIndent->client->country->name ?? '' }} </dl>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <dt class="form-label font-size-13 text-muted">Rejection Date :</dt>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <input type="date" name="loi_approval_date" id="rejection-date" value=""
                                                    required class="form-control widthinput" max="{{ \Illuminate\Support\Carbon::today()->format('Y-m-d') }}" >
                                            <span id="loi-rejection-date-error" class="text-danger"> </span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <dt class="form-label font-size-13 text-muted">Reason :</dt>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <textarea class="form-control" cols="75" name="review" id="review"  rows="5" required></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ $letterOfIndent->id }}" name="id">
                                    <input type="hidden" value="REJECTED" id="status-reject" name="status">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary " id="status-reject-button">Submit</button>
                    </div>
                </form>               
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
                <form id="loi-approve-form" action="{{ route('letter-of-indents.supplier-approval') }}" method="POST">
                    @csrf
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
                                <dl>{{ $letterOfIndent->client->country->name ?? '' }} </dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <dt class="form-label font-size-13 text-muted">Approval Date :</dt>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <input type="date" name="loi_approval_date" id="approval-date"
                                        required class="form-control widthinput">
                                <span id="loi-approval-date-error" class="text-danger"> </span>
                            </div>
                        </div>
                        <input type="hidden" value="{{ $letterOfIndent->id }}" id="id" name="id">
                        <input type="hidden" value="APPROVE" id="status-approve" name="status">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary " id="status-change-button-approve">Submit</button>
                </div>
                </form>
                
            </div>
        </div>
    </div>
    
    <!-- To Update Utilization Quantity -->
    <div class="modal fade " id="update-utilization-quantity-{{$letterOfIndent->id}}" data-bs-backdrop="static" 
      tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Update Utilized Quantity</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('utilization-quantity-update', $letterOfIndent->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row p-2">
                                <input type="number" min="0" placeholder="Utilized Quantity" required max="{{$total_loi_quantity}}" name="utilized_quantity" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
    <script type="text/javascript">
        $(document).ready(function () { 
            $('#status-change-button-approve').click(function () {
                console.log("button clicked");
                // var id = $('#id').val();
                // var status = $('#status-approve').val();
                // var date = document.getElementById("approval-date").value
                // if (date.length > 0) {
                    statusChange();
                // } else {
                //     $msg = "This field is required";
                //     showLOIApprovalDateError($msg)
                // }
            })
            $('#status-reject-button').click(function (e) {
                var id = $('#id').val();
                var status = $('#status-reject').val();
                var date = document.getElementById("rejection-date").value
                if (date.length > 0) {
                    statusChange(id, status, date)
                } else {
                    $msg = "This field is required";
                    showLOIRejectionDateError($msg)
                }
            });
           

            $('.btn-request-supplier-approval').on('click',function(){

                let id = $(this).attr('data-id');
                let url =  $(this).attr('data-url');
                console.log(url);
                console.log(id);
                var confirm = alertify.confirm('Are you sure you want to send this LOI for supplier Approval?',function (e) {
                    if (e) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: "json",
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}'
                            },
                            success:function (data) {
                                alertify.success('Approval Request Send Successfully.');
                                let table = $('.new-LOI-table').DataTable();
                                table.ajax.reload();
                                // location.reload();
                            
                            }
                        });
                    }
                }).set({title:"Delete Item"})
                });
            });

            function statusChange() {
                console.log("reched function");
                let url = '{{ route('letter-of-indents.supplier-approval') }}';
                if (status == 'REJECTED') {
                    var message = 'Reject';
                    var review = $('#review').val();
                } else {
                    var message = 'Approve';
                    var review = '';
                }
                var confirm = alertify.confirm('Are you sure you want to ' + message + ' this item ?', function (e) {
                    if (e) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: "json",
                            data: $("#loi-approve-form").serialize(),
                            success: function (data) {
                                console.log("updated");
                                window.location.reload();
                                alertify.success(status + " Successfully");
                            }
                        });
                    }
                }).set({title: "Status Change"})
            }

        

        function showLOIApprovalDateError($msg)
        {
            console.log("element error function");
            document.getElementById("loi-approval-date-error").textContent=$msg;
            document.getElementById("approval-date").classList.add("is-invalid");
            document.getElementById("loi-approval-date-error").classList.add("paragraph-class");
        }
        function removeLOIApprovalDateError($msg)
        {
            document.getElementById("loi-approval-date-error").textContent="";
            document.getElementById("approval-date").classList.remove("is-invalid");
            document.getElementById("loi-approval-date-error").classList.remove("paragraph-class");
        }
        function showLOIRejectionDateError($msg)
        {
            console.log("rejection error");
            document.getElementById("loi-rejection-date-error").textContent=$msg;
            document.getElementById("rejection-date").classList.add("is-invalid");
            document.getElementById("loi-rejection-date-error").classList.add("paragraph-class");
        }
        function removeLOIRejectionDateError($msg)
        {
            document.getElementById("loi-rejection-date-error").textContent="";
            document.getElementById("rejection-date").classList.remove("is-invalid");
            document.getElementById("loi-rejection-date-error").classList.remove("paragraph-class");
        }

 
       
  </script> 
