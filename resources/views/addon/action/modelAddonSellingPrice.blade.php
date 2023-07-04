@can('add-new-addon-selling-price')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['add-new-addon-selling-price']);
@endphp
@if ($hasPermission)
@if(!isset($addonsdata->SellingPrice) && !isset($addonsdata->PendingSellingPrice))
    <button type="button" title="Create Selling Price" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#table-selling-price-{{$addonsdata->id}}_{{$AddonTypes->brand_id}}_{{$AddonTypes->model_id}}">
      <i class="fa fa-plus"></i> 
    </button>
    <div class="modal fade" id="table-selling-price-{{$addonsdata->id}}_{{$AddonTypes->brand_id}}_{{$AddonTypes->model_id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog ">
        <form id="form-update" action="{{ route('addon.createSellingPrice', $addonsdata->id) }}" method="POST" >
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Create Selling Price</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
              <div class="col-lg-12">
                <div class="row">
                  <div class="row mt-2">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                      <label class="form-label font-size-13 text-muted">Selling Price</label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="input-group">
                        <input id="selling_price" type="number" min="0" step="any" class="form-control widthinput" name="selling_price" placeholder="Enter Selling Price" 
                            value="" autocomplete="selling_price">
                        <div class="input-group-append">
                          <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
              <button type="submit"  class="btn btn-primary btn-sm createAddonId">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    @elseif(isset($addonsdata->SellingPrice))
    <button type="button" title="Add New Selling Price" class="btn btn-success btn-sm " data-bs-toggle="modal" 
        data-bs-target="#edit-table-selling-price-{{$addonsdata->id}}_{{$AddonTypes->brand_id}}_{{$AddonTypes->model_id}}">
      <i class="fa fa-plus"></i> 
    </button>
    <div class="modal fade" id="edit-table-selling-price-{{$addonsdata->id}}_{{$AddonTypes->brand_id}}_{{$AddonTypes->model_id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog ">
        <form id="form-update" action="{{ route('addon.newSellingPriceRequest', $addonsdata->SellingPrice->id) }}" method="POST" >
        @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title fs-5" id="exampleModalLabel">Add New Selling Price</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
              <div class="col-lg-12">
                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="choices-single-default" class="form-label">Current Selling Price :</label>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <span>{{$addonsdata->SellingPrice->selling_price}} AED</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-md-12 col-sm-12">
                    <label class="form-label font-size-13 text-center">New Selling Price</label>
                  </div>
                  <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="input-group">
                                      <input value="{{$addonsdata->SellingPrice->id}}" name='id' id="createNew" hidden>
                                      <input id="selling_price" type="number" min="0" step="any" class="form-control widthinput @error('selling_price') is-invalid @enderror" 
                                            name="selling_price" placeholder="Enter Selling Price" value="" autocomplete="selling_price">
                                      <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                              <button type="submit"  class="btn btn-primary btn-sm createAddonId">Submit</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    @else
                    <button title="Add New Selling Price" type="button" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#edit-table-selling-price-{{$addonsdata->id}}_{{$AddonTypes->brand_id}}_{{$AddonTypes->model_id}}">
                      <i class="fa fa-plus"></i> 
                    </button>
                    <div class="modal fade" id="edit-table-selling-price-{{$addonsdata->id}}_{{$AddonTypes->brand_id}}_{{$AddonTypes->model_id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog ">
                        <form id="form-update" action="{{ route('addon.newSellingPriceRequest', $addonsdata->PendingSellingPrice->id) }}" method="POST" >
                          @csrf
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Selling Price</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-3">
                              <div class="col-lg-12">
                                <div class="row">
                                  <div class="col-lg-4 col-md-4 col-sm-12">
                                      <label for="choices-single-default" class="form-label">Selling Price :</label>
                                  </div>
                                  <div class="col-lg-8 col-md-8 col-sm-12">
                                      <span>{{$addonsdata->PendingSellingPrice->selling_price}} AED </span> <span style="color:#fd625e; font-size:13px;">( Approval Awaiting)</span>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-lg-4 col-md-12 col-sm-12">
                                    <label class="form-label font-size-13 text-center">New Selling Price</label>
                                  </div>
                                  <div class="col-lg-8 col-md-12 col-sm-12">
                                    <div class="input-group">
                                    <input value="{{$addonsdata->PendingSellingPrice->id}}" name='id' id="createNew" hidden>
                                        <input id="selling_price" type="number" min="0" step="any" class="form-control widthinput @error('selling_price') is-invalid @enderror" 
                                            name="selling_price" placeholder="Enter Selling Price" value="" autocomplete="selling_price">
                                        <div class="input-group-append">
                                          <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                              <button type="submit"  class="btn btn-primary btn-sm createAddonId">Submit</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  @endif
                  @endif
                @endcan