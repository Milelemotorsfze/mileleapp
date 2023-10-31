@can('add-new-addon-selling-price')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['add-new-addon-selling-price']);
@endphp
@if ($hasPermission)
  @if(!isset($addonsdata->SellingPrice) && !isset($addonsdata->PendingSellingPrice))
    <button type="button" title="Create Selling Price" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#create-selling-price-{{$addonsdata->id}}">
      <i class="fa fa-plus"></i>
    </button>
    <div class="modal fade" id="create-selling-price-{{$addonsdata->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog ">
        <form id="form-update1_{{$addonsdata->id}}" action="{{ route('addon.createSellingPrice', $addonsdata->id) }}" method="POST" >
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Create Selling Price</h1>
              <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        @if($addonsdata->addon_type_name == 'K')
                          <input id="least_purchase_price_a_{{$addonsdata->id}}" name="least_purchase_price" value="{{$addonsdata->LeastPurchasePrices ?? ''}} " hidden>
                        @elseif($addonsdata->addon_type_name == 'SP' OR $addonsdata->addon_type_name == 'P')
                          <input id="least_purchase_price_a_{{$addonsdata->id}}" name="least_purchase_price" value="{{$addonsdata->LeastPurchasePrices->purchase_price_aed ?? ''}}" hidden>
                        @endif
                        <input id="selling_price_a_{{$addonsdata->id}}" oninput="inputNumberAbs(this,{{$addonsdata->id}},'a')" class="form-control widthinput" name="selling_price" 
                        placeholder="Enter Selling Price" value="" autocomplete="selling_price" required>
                        <div class="input-group-append">
                          <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                        </div>
                      </div>
                      <span id="a_error_{{$addonsdata->id}}" class="error required-class paragraph-class" style="color:#fd625e; font-size:13px;"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm closeSelPrice" data-bs-dismiss="modal">Close</button>
              <button type="submit" id="submit_a_{{$addonsdata->id}}" class="btn btn-primary btn-sm createAddonId">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  @elseif(isset($addonsdata->SellingPrice))
    <button type="button" title="Add New Selling Price" class="btn btn-success btn-sm " data-bs-toggle="modal" 
        data-bs-target="#edit-selling-price-{{$addonsdata->SellingPrice->id}}">
      <i class="fa fa-plus"></i>
    </button>
    <div class="modal fade" id="edit-selling-price-{{$addonsdata->SellingPrice->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog ">
        <form id="form-update2_{{$addonsdata->id}}" class="" action="{{ route('addon.newSellingPriceRequest', $addonsdata->SellingPrice->id) }}" method="POST" >
        @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title fs-5" id="exampleModalLabel">Add New Selling Price</h5>
              <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    @if($addonsdata->addon_type_name == 'K')
                    <input id="least_purchase_price_b_{{$addonsdata->id}}" name="least_purchase_price" value="{{$addonsdata->LeastPurchasePrices ?? ''}}" hidden>
                        @elseif($addonsdata->addon_type_name == 'SP' OR $addonsdata->addon_type_name == 'P')
                          <input id="least_purchase_price_b_{{$addonsdata->id}}" name="least_purchase_price" value="{{$addonsdata->LeastPurchasePrices->purchase_price_aed ?? ''}}" hidden>
                        @endif
                                      <input value="{{$addonsdata->SellingPrice->id}}" name='id' id="createNew" hidden>
                                      <input id="selling_price_b_{{$addonsdata->id}}" oninput="inputNumberAbs(this,{{$addonsdata->id}},'b')" class="form-control widthinput @error('selling_price') is-invalid @enderror" 
                                            name="selling_price" placeholder="Enter Selling Price" value="" autocomplete="selling_price" required>
                                      <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                      </div>
                                    </div>
                                    <span id="b_error_{{$addonsdata->id}}" class="error required-class paragraph-class" style="color:#fd625e; font-size:13px;"></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary btn-sm closeSelPrice" data-bs-dismiss="modal">Close</button>
                              <button type="submit" id="submit_b_{{$addonsdata->id}}" class="btn btn-primary btn-sm createAddonId">Submit</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    @else
                    <button title="Add New Selling Price" type="button" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#edit-selling-price-{{$addonsdata->PendingSellingPrice->id}}">
                      <i class="fa fa-plus"></i>
                    </button>
                    <div class="modal fade" id="edit-selling-price-{{$addonsdata->PendingSellingPrice->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog ">
                        <form id="form-update3_{{$addonsdata->id}}" action="{{ route('addon.newSellingPriceRequest', $addonsdata->PendingSellingPrice->id) }}" method="POST" >
                          @csrf
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Selling Price</h1>
                              <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                      @if($addonsdata->addon_type_name == 'K')
                                        <input id="least_purchase_price_c_{{$addonsdata->id}}" name="least_purchase_price" value="{{$addonsdata->LeastPurchasePrices ?? ''}}" hidden>
                                      @elseif($addonsdata->addon_type_name == 'SP' OR $addonsdata->addon_type_name == 'P')
                                        <input id="least_purchase_price_c_{{$addonsdata->id}}" name="least_purchase_price" value="{{$addonsdata->LeastPurchasePrices->purchase_price_aed ?? ''}}" hidden>
                                      @endif
                                      <input value="{{$addonsdata->PendingSellingPrice->id}}" name='id' id="createNew" hidden>
                                        <input id="selling_price_c_{{$addonsdata->id}}" oninput="inputNumberAbs(this,{{$addonsdata->id}},'c')" class="form-control widthinput @error('selling_price') is-invalid @enderror" 
                                            name="selling_price" placeholder="Enter Selling Price" value="" autocomplete="selling_price" required>
                                        <div class="input-group-append">
                                          <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                        </div>
                                        <span id="c_error_{{$addonsdata->id}}" class="error required-class paragraph-class" style="color:#fd625e; font-size:13px;"></span>

                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary btn-sm closeSelPrice" data-bs-dismiss="modal">Close</button>
                              <button type="submit" id="submit_c_{{$addonsdata->id}}" class="btn btn-primary btn-sm createAddonId">Submit</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  @endif
                  @endif
                @endcan
@push('scripts')
  <script>
    function inputNumberAbs(currentPriceInput,index,type) { 
      var id = currentPriceInput.id;
      var input = document.getElementById(id);
      var val = input.value;
      val = val.replace(/^0+|[^\d.]/g, '');
      if(val.split('.').length>2) {
        val =val.replace(/\.+$/,"");
      }
      input.value = val;
      var currentInput = '';
      currentInput = input.value;
      var leastPurchasePrice = '';
      leastPurchasePrice = $("#least_purchase_price_"+type+"_"+index).val();
      // if (currentInput == Math.floor(currentInput)) {

      // alert("Integer")

      // } 
      // else {

      // alert("Decimal")

      // }
      if(currentInput == '') { 
        document.getElementById(type+'_error_'+index).textContent='';
        document.getElementById('submit_'+type+'_'+index).removeAttribute("disabled");
      }
      else {
        if(val < leastPurchasePrice) {
        document.getElementById(type+'_error_'+index).textContent='Enter greater amount than purchase price';
        document.getElementById('submit_'+type+'_'+index).setAttribute("disabled", "disabled");
        }
        else if(val >= leastPurchasePrice) {
          document.getElementById(type+'_error_'+index).textContent='';
          document.getElementById('submit_'+type+'_'+index).removeAttribute("disabled");
        }
         
      }
    }
  </script>
@endpush