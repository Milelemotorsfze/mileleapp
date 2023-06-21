<style>
 .showImage
    {
        width: auto;
        height: auto;
        max-width:1200px;
        max-height:1200px;
    }
    .modal-xl
    {
        max-width: 99% !important;
    }
    
            .related-addon-header
            {
                background-color:#5156be;
            }
            .related-addon-h4
            {
                padding-top:8px;
                padding-bottom:8px;
                text-align:center;
                color:white;
            }
            .related-addon .each-addon
            {
                border-style: solid;
                border-width: 1px;
                border-color: white;
                border-radius: 5px;
                /* margin-top: 10px; */
                padding-top:10px;
                padding-bottom:10px;
                background-color:#f2f2f2;
            }
            .related-addon input
            {
                padding-top:0px;
                padding-bottom:0px;
                padding-right:0px;
                padding-left:0px;
                /* height:50%; */
            }
            .related-label
            {
                padding-top:0px;
                padding-bottom:0px;
            }
             /* .related-addon .related-input-div
            {
                margin-top:0px;
                margin-bottom:0px;
                margin-right:0px;
                margin-left:0px;
            } */
            .list2
            {
              margin-right:10px;
                  margin-left:10px;
            }
            .labellist
            {
              border-style: solid;
                  border-width: 1px;
                  border-color: #5156be;
                  border-radius: 5px;
            }
            .labeldesign
            {
              background-color:#6266c4;
              color:white;
              border-color: white;
            }
            .databack1
            {
              background-color:#e6e6ff;
              border-color: white;
            }
            .databack2
            {
              background-color:#f2f2f2;
              border-color: white;
            }
              /* #addonListTable{
            display: none;
          } */
          .overlay
          {
              position: fixed; /* Positioning and size */
              top: 0;
              left: 0;
              width: 100vw;
              height: 100vh;
              background-color: rgba(128,128,128,0.5); /* color */
              display: none; /* making it hidden by default */
          }
</style>
@if($addon1)
        @if(count($addon1) > 0)
        
<div class="list2" id="addonbox">
      <div class="row related-addon">
       
        @foreach($addon1 as $addonsdata)
          <div id="{{$addonsdata->id}}" class="each-addon col-xxl-4 col-lg-4 col-md-6 col-sm-12">  
            <div class="row">
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                Addon Name
              </div>
              <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
                @if($addonsdata->AddonName->name != '')
                {{$addonsdata->AddonName->name}}
                @endif
              </div>
              @if($addonsdata->payment_condition)
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
              Payment Condition
              </div>
              <div class="labellist databack2 col-xxl-8 col-lg-8 col-md-8">
              {{$addonsdata->payment_condition}}
              </div>
              @endif
              @if($addonsdata->additional_remarks)
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
              Additional Remarks
              </div>
              <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
              {{$addonsdata->additional_remarks}}
              </div>
              @endif 
              <!-- @if($addonsdata->payment_condition)
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
              Payment Condition
              </div>
              <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
              {{$addonsdata->payment_condition}}
              @endif
              @if($addonsdata->additional_remarks)
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
              Additional Remarks
              </div>
              <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
              {{$addonsdata->additional_remarks}}
              </div>
              @endif -->
              <div class="col-xxl-5 col-lg-5 col-md-4 col-sm-4" style="padding-right:3px; padding-left:3px;">
             <!-- Extra large modal -->
                @if($addonsdata->image)

                <img id="{{$addonsdata->id}}" src="{{ asset('addon_image/' . $addonsdata->image) }}" style="width:100%; height:155px;" alt="Addon Image" class="modal-button" data-modal-id="showImageModal{{$addonsdata->id}}" />
                
                <div class="modal" id="showImageModal{{$addonsdata->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenteredLabel" style="text-align:center;"> Addon Image </h5>
                            <button type="button" class="btn btn-secondary btn-sm close form-control" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row modal-row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <center>
                                            <img id="showImage{{$addonsdata->id}}" src="{{ asset('addon_image/' . $addonsdata->image) }}" alt="your image" class="showImage" />
                                        </center>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
           
                @endif
                <!-- @if($addonsdata->additional_remarks)
                <div class="labellist labeldesign col-xxl-12 col-lg-12 col-md-12">
                  <center>Additional Remarks</center>
                </div>
                <div class=" labellist databack1 col-xxl-12 col-lg-12 col-md-12">
                  {{$addonsdata->additional_remarks}}
                </div>
                @endif -->
                <!-- @if($addonsdata->payment_condition)
                <div class="labellist labeldesign col-xxl-12 col-lg-12 col-md-12">
                  <center>Payment Condition</center>
                </div>
                <div class=" labellist databack1 col-xxl-12 col-lg-12 col-md-12">
                  {{$addonsdata->payment_condition}}
                </div>
                @endif -->
                
              </div>
              
              <div class="col-xxl-7 col-lg-7 col-md-8 col-sm-8" >
                <div class="row" style="padding-right:3px; padding-left:3px;">
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">
                    Addon Code
                  </div>
                  <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->addon_code}}
                  </div>
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Addon Type
                  </div>
                  <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                    @if($addonsdata->addon_type_name == 'K')
                    Kit
                    @elseif($addonsdata->addon_type_name == 'P')
                    Accessories
                    @elseif($addonsdata->addon_type_name == 'SP')
                    Spare Parts
                    @endif
                  </div>
                  @if($content == '')
                  @if($addonsdata->PurchasePrices!= null)
                  @if($addonsdata->PurchasePrices->purchase_price_aed != '')
                  @can('supplier-addon-purchase-price-view')
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Purchase Price
                  </div>
                  <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->PurchasePrices->purchase_price_aed}} AED
                  </div>
                  @endcan
                  @endif
                  @endif
                  @endif
                  @if($addonsdata->LeastPurchasePrices!= null)
                  @if($addonsdata->LeastPurchasePrices->purchase_price_aed != '')
                  @can('addon-least-purchase-price-view')
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Least Purchase Price
                  </div>
                  <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->LeastPurchasePrices->purchase_price_aed}} AED
                  </div>
                  @endcan
                  @endif
                  @endif
                  @if($addonsdata->SellingPrice!= null)
                  @if($addonsdata->SellingPrice->selling_price != '')
                  @can('addon-selling-price-view')
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Selling Price 
                  </div>
                  <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->SellingPrice->selling_price}} AED
                  </div>
                  @endcan
                  @endif
                  @endif
                  @if($addonsdata->fixing_charge_amount)
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                  Fixing Charge
                  </div>
                  <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->fixing_charge_amount}} AED
                  </div>
                  @endif
                  @if($addonsdata->lead_time)
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Lead Time
                  </div>
                  <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->lead_time}} Days
                  </div>
                  @endif
                  @if($addonsdata->part_number)
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                  Part Number
                  </div>
                  <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->part_number}} 
                  </div>
                  @endif
                
                  
                 
                </div>                     
              </div> 
               
              @if($addonsdata->is_all_brands == 'yes')
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Brand
                  </div>
                  <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                   All Brands
                  </div>
                  @else
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    <center>Brand</center>
                  </div>
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    <center>Model Line</center>
                  </div>
                  @foreach($addonsdata->AddonTypes as $AddonTypes)
                    <div class="divcolorclass" value="5" hidden>
                    </div>
                    <div class="divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                      {{$AddonTypes->brands->brand_name}}
                    </div>                   
                    <div class="divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                    @if($AddonTypes->is_all_model_lines == 'yes')
                    All Model Lines
                    @else
                      {{$AddonTypes->modelLines->model_line}}
                    @endif
                    </div>
                  @endforeach
                  @endif      
            </div> 
            </br>
            <div class="row" style="position: absolute; bottom: 3px; right: 5px; ">
              <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12" >
                @if($addonsdata->addon_type_name == 'K')
                @can('view-kit-item-details')
                <a class="btn btn-sm btn-info" href="{{ route('addon.kitItems',$addonsdata->id) }}">
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i> Items
                </a>
                @endcan
                @endif
                @can('add-new-addon-selling-price')
                <button type="button" class="btn btn-success btn-sm " data-bs-toggle="modal"
                                                    data-bs-target="#edit-selling-price-{{$addonsdata->SellingPrice->id}}">
                                                <i class="fa fa-plus"></i> Price</button>
@endcan
                                                <div class="modal fade" id="edit-selling-price-{{$addonsdata->SellingPrice->id}}"  tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <form id="form-update" action="{{ route('addon.newSellingPriceRequest', $addonsdata->SellingPrice->id) }}"
                                                  method="POST" >
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Selling Price</h1>
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
                                                                        <input value="{{$addonsdata->SellingPrice->id}}" name='id' id="createNew" hidden>

                                                                        <input id="selling_price" type="number" min="0" step="any" class="form-control widthinput @error('selling_price') is-invalid @enderror" name="selling_price" placeholder="Enter Selling Price" value="{{ old('selling_price') }}" autocomplete="selling_price">

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
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit"  class="btn btn-primary createAddonId">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

@can('view-addon-selling-price-history')
            <a title="History" class="btn btn-sm btn-info modal-button" href="{{ route('suppliers.sellingPriceHistory',$addonsdata->id) }}">
                         <i class="fa fa-history" aria-hidden="true"></i> History</a>
                         @endcan
                  @can('addon-view')       
                <a class="btn btn-sm btn-success" href="{{ route('addon.view',$addonsdata->id) }}">
                  <i class="fa fa-eye" aria-hidden="true"></i> View
                </a>
                @endcan
                @can('addon-edit')
                <a class="btn btn-sm btn-info" href="{{ route('addon.editDetails',$addonsdata->id) }}">
                  <i class="fa fa-edit" aria-hidden="true"></i> Edit
                </a>
                @endcan
              </div>     
            </div>
          </div>
          <!-- </br> -->
          
        @endforeach
       
        <!-- </br> -->
      </div>
    </div>
    <div class="overlay"></div>
    @endif
        @endif
        <script type="text/javascript">
        //    $('.createAddonId').on('click', function()
        // {

        //     // create new addon and list new addon in addon list
        //     var value = $('#selling_price').val();
        //     var id = $('#createNew').val();
        //     alert(id);
        //         $.ajax
        //         ({
        //             url:"{{url('newSellingPriceRequest')}}",
        //             type: "POST",
        //             data:
        //             {
        //                 name: value,
        //                 id: id,
        //                 _token: '{{csrf_token()}}'
        //             },
        //             dataType : 'json',
        //             success: function(result)
        //             {
        //                 $('.overlay').hide();
        //                 $('.modal').removeClass('modalshow');
        //                 $('.modal').addClass('modalhide');
        //                 // $('#addon_id').append("<option value='" + result.id + "'>" + result.name + "</option>");
        //                 // $('#addon_id').val(result.id);
        //                 // var selectedValues = new Array();
        //                 // resetSelectedSuppliers(selectedValues);
        //                 // $('#addnewAddonButton').hide();
        //                 // $('#new_addon_name').val("");
        //                 // document.getElementById("newAddonError").textContent='';
        //                 // $msg = "";
        //                 // removeAddonNameError($msg);
        //             }
        //         });
            
        // });
        // function showImage(ImgId)
        // {
        //     var modal = document.getElementById("showImageModal");
        //     var img = document.getElementById(ImgId);
        //     var image = document.getElementById("image");
        //     var modalImg = document.getElementById("showImage");
        //     var modalImg = document.getElementById("showImage");
        //     $('.overlay').show();
        //     $('#showImageModal').addClass('modalshow');
        //     $('#showImageModal').removeClass('modalhide');
        //     modalImg.src = img.src;
        // }
        $('.modal-button').on('click', function()
      {
                $('.overlay').show();
            $('#showImageModal').addClass('modalshow');
            $('#showImageModal').removeClass('modalhide');
      });
          $('.close').on('click', function()
        {
            $('.modal').addClass('modalhide');
            $('.modal').removeClass('modalshow');
            $('.overlay').hide();
        });
        </script>