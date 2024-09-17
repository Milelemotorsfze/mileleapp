
<div class="dropdown">
    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
    <i class="fa fa-bars" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-start"> 
    @can('LOI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
        @endphp
        @if ($hasPermission)       
        <li>   
            <button type="button" class="btn btn-soft-violet primary btn-sm mt-1" style="width:100%; margin-top:2px; margin-bottom:2px;" 
             title="View LOI Item Deatails & Update Utilized Quantity" data-bs-toggle="modal" data-bs-target="#view-loi-items-{{$letterOfIndent->id}}">
                <i class="fa fa-list"></i> View LOI Items & Update Utilized Qty
            </button>
        </li>
        <li>
            <button type="button" class="btn btn-dark-blue btn-sm mt-1" title="View Customer Documents" style="width:100%; margin-top:2px; margin-bottom:2px;" 
            data-bs-toggle="modal" data-bs-target="#view-loi-docs-{{$letterOfIndent->id}}">
                <i class="fa fa-file-pdf"></i> View Customer Doc
            </button>
        </li>
        <li>
            <button type="button" class="btn btn-secondary primary btn-sm mt-1" title="Update Comment" style="width:100%; margin-top:2px; margin-bottom:2px;" 
             data-bs-toggle="modal" data-bs-target="#update-loi-comment-{{$letterOfIndent->id}}">
                <i class="fa fa-comment"></i> Update Comment
            </button>
        </li>
        @endif
    @endcan
    @if($type !== 'SUPPLIER_RESPONSE')
        @can('LOI-edit')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-edit');
        @endphp
            @if ($hasPermission)
            <li>
                <a href="{{ route('letter-of-indents.edit',$letterOfIndent->id) }}" class="btn btn-info btn-sm mt-1"
                title="Edit LOI" style="width:100%; margin-top:2px; margin-bottom:2px;" >
                 <i class="fa fa-edit"></i> Edit
                </a>
            </li>
            @endif
        @endcan
        @can('LOI-delete')
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-delete');
            @endphp
            @if ($hasPermission)
            <li>
                <button type="button" class="btn btn-danger btn-sm loi-button-delete mt-1" title="Delete LOI" style="width:100%; margin-top:2px; margin-bottom:2px;" 
                        data-id="{{ $letterOfIndent->id }}" data-url="{{ route('letter-of-indents.destroy', $letterOfIndent->id) }}">
                    <i class="fa fa-trash"></i> Delete
                </button>
                <li>
            @endif
        @endcan
    @endif
  
       
    </ul>
</div>
    
  

    <!-- To View LOI Items -->
    <div class="modal fade" id="view-loi-items-{{$letterOfIndent->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
           
            <div class="modal-content">
            <form action="{{route('utilization-quantity-update', $letterOfIndent->id) }}" method="POST">
            @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">LOI Items</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                    <div class="modal-body pl-2 pr-2" style="font-size:12px; overflow-y: auto;max-height: 300px;">
                        @if($letterOfIndent->letterOfIndentItems->count() > 0)
                            <div class="row d-none d-lg-block d-xl-block d-xxl-block mt-1">
                                <div class="d-flex">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt>Model</dt>
                                            </div>
                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                <dt>SFX </dt>
                                            </div>
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt>Model Line </dt>
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-sm-12">
                                                <dt>LOI Description</dt>
                                            </div>
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt>Quantity</dt>
                                            </div>
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt>Utilized Quantity</dt>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
                            @foreach($letterOfIndent->letterOfIndentItems as $value => $LOIItem)
                                <div class="row">
                                    <div class="d-flex">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <hr class="mt-1">
                                            <div class="row">
                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Model</dt>
                                                    <dl> {{ $LOIItem->masterModel->model ?? ''}} </dl>
                                                </div>
                                                <div class="col-lg-1 col-md-12 col-sm-12">
                                                    <dt  class=" d-lg-none d-xl-none d-xxl-none">SFX</dt>
                                                    <dl> {{ $LOIItem->masterModel->sfx ?? '' }} </dl>
                                                </div>
                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">Model Line</dt>
                                                    <dl>  {{ $LOIItem->masterModel->modelLine->model_line ?? '' }} </dl>
                                                </div>
                                                <div class="col-lg-3 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none ">LOI Description</dt>
                                                    <dl> {{ $LOIItem->loi_description ?? '' }} </dl>
                                                </div>
                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Quantity</dt>
                                                    <dl>{{ $LOIItem->quantity }}</dl>
                                                </div>
                                                
                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                
                                                    <dt class="d-lg-none d-xl-none d-xxl-none">Utilized Quantity</dt>
                                                    <input type="hidden" name="letter_of_indent_item_ids[]" value="{{ $LOIItem->id}}" >
                                                    <input type="number" min="0" placeholder="Utilized Quantity" 
                                                    @if($type == 'NEW')  readonly @endif 
                                                    required max="{{$LOIItem->quantity}}" name="utilized_quantity[]" value="{{ $LOIItem->utilized_quantity }}" class="form-control" >
                                            
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                           
                        @else
                            <span class="text-center"> No Data Available! </span>
                        @endif

                    </div>
                    <div class="modal-footer">
                       @if($type !== 'NEW' && $letterOfIndent->is_expired == false)
                            <button type="submit" class="btn btn-info" 
                           data-url="{{route('utilization-quantity-update', $letterOfIndent->id) }}">Update</button> 
                        @endif 
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>
              
            </div> 
    </div>
    <!-- To view LOI Document -->
    <div class="modal fade" id="view-loi-docs-{{$letterOfIndent->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Customer Documents</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="row p-2">
                            @foreach($letterOfIndent->LOIDocuments as $letterOfIndentDocument)
                                <div class="d-flex">
                                    <div class="col-lg-12">
                                        <div class="row p-2">
                                            <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  width="400" height="600"></embed>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if($letterOfIndent->signature)
                                <div class="col-lg-12 m-5">
                                    <label class="form-label fw-bold">Signature</label>
                                    <img src="{{ url('LOI-Signature/'.$letterOfIndent->signature) }}">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
     <!-- to update Comment -->
    <div class="modal fade" id="update-loi-comment-{{$letterOfIndent->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Update Comment</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 m-1">
                                <label class="form-label fw-bold">Comment</label>
                                <textarea rows="5" cols="20" class="form-control" required name="comments" id="comment-{{ $letterOfIndent->id }}"
                                placeholder="Comment Here..">{{ $letterOfIndent->comments }}</textarea>
                                <span id="comment-error-{{ $letterOfIndent->id }}" class="text-danger"> </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" onclick="updateComment({{ $letterOfIndent->id }})">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
        </div>
    </div>
    
    <script>
         $('.loi-button-delete').on('click',function(){
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            _method: 'DELETE',
                            id: 'id',
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            var table1 = $('.new-LOI-table').DataTable();
                            table1.ajax.reload();
                            alertify.success('LOI Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
        $('.loi-expiry-status-update').on('click',function(){
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to make this LOI Expired?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {

                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            var table1 = $('.new-LOI-table').DataTable();
                            table1.ajax.reload();
                            var table2 = $('.waiting-for-approval-table').DataTable();
                            table2.ajax.reload();
                            var table3 = $('.supplier-response-table').DataTable();
                            table3.ajax.reload();
                            alertify.success('Expiry Status updated as "Expired" successfully.');
                        }
                    });
                }
            }).set({title:"Update Expired Status"})
        });

       function updateComment(id){
            let url =  "{{ route('update-loi-comment')}}";
            let comment = $('#comment-'+id).val();
            if(comment.length > 0) {
                document.getElementById("comment-error-"+id).textContent="";
	            document.getElementById("comment-"+id).classList.remove("is-invalid");
	            document.getElementById("comment-error-"+id).classList.remove("paragraph-class");

                var confirm = alertify.confirm('Are you sure ? Do you want to Update Comment of this item ?',function (e) {
                if (e) {
                    $('#update-loi-comment-'+id).modal('hide');
                        
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            id: id,
                            comments:comment,
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            var table1 = $('.new-LOI-table').DataTable();
                            table1.ajax.reload();
                            var table2 = $('.waiting-for-approval-table').DataTable();
                            table2.ajax.reload();
                            var table3 = $('.supplier-response-table').DataTable();
                            table3.ajax.reload();
                            alertify.success('LOI Comment updated successfully.');

                        }
                    });
                }
                }).set({title:"Delete Item"})
            }else{
                let msg = "This filed is required";
                document.getElementById("comment-error-"+id).textContent=msg;
                document.getElementById("comment-"+id).classList.add("is-invalid");
                document.getElementById("comment-error-"+id).classList.add("paragraph-class");
            }
            
        }
        
    </script>

   
    