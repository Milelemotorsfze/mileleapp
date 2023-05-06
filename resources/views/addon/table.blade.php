<style>
    .modal-content {
            position:fixed;
            top: 50%;
            left: 50%;
            width:30em;
            height:18em;
            margin-top: -9em; /*set to a negative number 1/2 of your height*/
            margin-left: -15em; /*set to a negative number 1/2 of your width*/
            border: 2px solid #e3e4f1;
            background-color: white;
        }
        .modal-title {
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .modal-paragraph {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
        .modal-button-class {
            margin-top: 20px;
            margin-left: 20px;
            margin-right: 20px;
        }
        .icon-right {
            z-index: 10;
            position: absolute;
            right: 0;
            top: 0;
        }
</style>

<div class="table-responsive" id="addonListTable" hidden>     
      <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
        <thead>
          <tr>
            <th>No</th>
            <th>Image</th>
            <th>Addon Name</th>
            <th>Addon Code</th>
            <th>Brand</th>
            <th>Model Line</th>
            <th>Lead Time</th>
            <th>Additional Remarks</th>
            <th>Purchase Price(AED)</th>
            <th>Selling Price(AED)</th>
            <th>Payment Condition</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <div hidden>{{$i=0;}}
          </div>
          @foreach ($addons as $key => $addon)
            <tr data-id="1">
              <td>{{ ++$i }}</td>
              <td><img src="{{ asset('addon_image/' . $addon->image) }}" style="width:100%; height:100px;" /></td>
              <td>{{ $addon->name }}</td>
              <td>{{ $addon->addon_code }}</td>
              <td>{{ $addon->brand_name }}</td>
              <td>{{ $addon->model_line }}</td>
              <td>{{ $addon->lead_time }}</td>
              <td>{{ $addon->additional_remarks }}</td>
              <td>{{ $addon->purchase_price }}</td>
              <td>{{ $addon->selling_price }}</td>
              <td>{{ $addon->payment_condition }}</td>
              <td>
                <a class="btn btn-sm btn-success" href="{{ route('addon.view',$addon->addon_details_table_id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                <a class="btn btn-sm btn-info" href="{{ route('addon.editDetails',$addon->addon_details_table_id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                @if( $addon->status == 'active')
                <a data-toggle="popover" data-trigger="hover" title="Make Inactive" data-placement="top" class="btn btn-sm btn-secondary modal-button" data-modal-id="makeInactiveAddon{{$addon->addon_details_table_id}}"><i class="fa fa-ban" aria-hidden="true"></i></a>
                        <div class="modal modal-class" id="makeInactiveAddon{{$addon->addon_details_table_id}}" >
                          <div class="modal-content">
                            <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                            <h3 class="modal-title" style="text-align:center;"> Make Inactive Addon </h3>
                            <div class="dropdown-divider"></div>
                            <h4 class="modal-paragraph"> Are you sure,</h4>
                            <h5 class="modal-paragraph"> You want to make inactive ?</h5>
                            <div class="dropdown-divider"></div>
                            <div class="row modal-button-class">                                           
                              <div class="col-xs-6 col-sm-6 col-md-6">
                                <a href="" style="float: right;" class="btn btn-sm btn-success "><i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                              </div>
                            </div>                                          
                          </div>
                        </div>
                        @else
                        <a data-toggle="popover" data-trigger="hover" title="Make Active" data-placement="top" class="btn btn-sm btn-primary modal-button" data-modal-id="makeActiveAddon{{$addon->addon_details_table_id}}"><i class="fa fa-check" aria-hidden="true"></i></a>
                            <div class="modal modal-class" id="makeActiveAddon{{$addon->addon_details_table_id}}" >
                              <div class="modal-content">
                                <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                                <h3 class="modal-title" style="text-align:center;"> Make Active Addon </h3>
                                <div class="dropdown-divider"></div>
                                <h4 class="modal-paragraph"> Are you sure,</h4>
                                <h5 class="modal-paragraph"> You want to make active ?</h5>
                                <div class="dropdown-divider"></div>
                                <div class="row modal-button-class">                                           
                                  <div class="col-xs-6 col-sm-6 col-md-6">
                                    <a href="" style="float: right;" class="btn btn-sm btn-success "><i class="fa fa-check" aria-hidden="true"></i> Confirm</a>        
                                  </div>
                                </div>                                          
                              </div>
                            </div>
                        @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>