

    @if($type !== 'SUPPLIER_RESPONSE')
        @can('LOI-edit')
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-edit');
            @endphp
            @if ($hasPermission)
                <a href="{{ route('letter-of-indents.edit',$letterOfIndent->id) }}">
                    <button type="button" class="btn btn-soft-green btn-sm mt-1" title="Edit LOI"><i class="fa fa-edit"></i></button>
                </a>
            @endif
        @endcan
        @can('LOI-delete')
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-delete');
            @endphp
            @if ($hasPermission)
                <button type="button" class="btn btn-danger btn-sm loi-button-delete mt-1" title="Delete LOI"
                        data-id="{{ $letterOfIndent->id }}" data-url="{{ route('letter-of-indents.destroy', $letterOfIndent->id) }}">
                    <i class="fa fa-trash"></i>
                </button>
            @endif
        @endcan
    @endif
 

    @can('LOI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
        @endphp
        @if ($hasPermission)
           

        <!-- @can('PFI-create')
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-create');
            @endphp
            @if ($hasPermission)
                @if($letterOfIndent->is_expired == false &&  $type == 'SUPPLIER_RESPONSE')
                    <a href="{{ route('pfi.create',['id' => $letterOfIndent->id ]) }}">
                        <button type="button"  class="btn btn-soft-blue btn-sm mt-1">Add PFI</button>
                    </a>
                @endif
            @endif
        @endcan -->
        <button type="button" class="btn btn-soft-violet primary btn-sm mt-1" title="View LOI Item Lists" data-bs-toggle="modal" data-bs-target="#view-loi-items-{{$letterOfIndent->id}}">
            <i class="fa fa-list"></i>
        </button>
        <button type="button" class="btn btn-dark-blue btn-sm mt-1" title="View Customer Documents" data-bs-toggle="modal" data-bs-target="#view-loi-docs-{{$letterOfIndent->id}}">
            <i class="fa fa-file-pdf"></i>
        </button>
    @endif
@endcan


    <!-- To View LOI Documents -->
    <div class="modal fade" id="view-loi-items-{{$letterOfIndent->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">LOI Items</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    @if($letterOfIndent->letterOfIndentItems->count() > 0)
                        <div class="row d-none d-lg-block d-xl-block d-xxl-block">
                            <div class="d-flex">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                            <dt>Model</dt>
                                        </div>
                                        <div class="col-lg-1 col-md-12 col-sm-12">
                                            <dt>SFX </dt>
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <dt>Model Line </dt>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <dt>LOI Description</dt>
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <dt>Quantity</dt>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($letterOfIndent->letterOfIndentItems as $value => $LOIItem)
                            <div class="row">
                                <div class="d-flex">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <hr>
                                        <div class="row mt-3">
                                            <div class="col-lg-3 col-md-12 col-sm-12">
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
                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                <dt class="d-lg-none d-xl-none d-xxl-none ">LOI Description</dt>
                                                <dl> {{ $LOIItem->loi_description ?? '' }} </dl>
                                            </div>
                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                <dt class="d-lg-none d-xl-none d-xxl-none">Quantity</dt>
                                                <dl>{{ $LOIItem->quantity }}</dl>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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
                            location.reload();
                            alertify.success('LOI Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
    </script>

   
    