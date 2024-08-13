    
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
        @elseif($type == 'WAITING_FOR_APPROVAL')
            @can('loi-status-update')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-status-update');
                @endphp
                @if ($hasPermission)
                    <button type="button" data-bs-toggle="modal"  data-bs-target="#update-loi-status-{{ $letterOfIndent->id }}"
                    class="btn btn-warning btn-sm " title="Reverse Update of Status to New">Status Update</button>              
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
                                            <dt class="form-label font-size-13 text-muted">Remark :</dt>
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
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <dt class="form-label font-size-13 text-muted">Remark :</dt>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <textarea class="form-control" cols="75" name="review" id="review"  rows="5" required></textarea>
                            </div>
                        </div>
                        <input type="hidden" value="{{ $letterOfIndent->id }}" id="id" name="id">
                        <input type="hidden" value="APPROVE" id="status-approve" name="status">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Submit</button>
                </div>
                </form>
                
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
         $('.btn-request-supplier-approval').on('click',function(){
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
           
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
                            var table = $('.new-LOI-table').DataTable();
                            table.ajax.reload();
                            alertify.success('Approval Request Send Successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
        // $('#loi-approve-form').submit(function (event) {
        //     event.preventDefault();
        //     var formData = $(this).serialize();
        //     console.log("clicekd");
        //     console.log(formData);
        //     $.ajax({
        //         url: $(this).attr('action'),
        //         type: 'POST',
        //         dataType:"json"
        //         data: formData,
        //           _token: '{{ csrf_token() }}'\
        
        //         success: function (response)
        //          {
        //             console.log(response);
        //             var table1 = $('.waiting-for-approval-table').DataTable();
        //             table1.ajax.reload();
        //             alertify.success('Supplier Approved successfully.');

        //             },
        //             error: function (error) {
        //         console.error(error);
        //     }
        //      });
        // });
  </script> 
