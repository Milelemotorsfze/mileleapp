@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">
      Addon List
    </h4>
    <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('addon.create') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Addon
    </a>
    <a id="addonListTableButton" onclick="showAddonTable()" style="float: right; margin-right:5px;" class="btn btn-sm btn-info">
      <i class="fa fa-table" aria-hidden="true"></i>
    </a>  
    <a id="addonBoxButton" onclick="showAddonBox()" style="float: right; margin-right:5px;" class="btn btn-sm btn-info" hidden>
      <i class="fa fa-th-large" aria-hidden="true"></i>
    </a>  
  </div>
  <div class="card-body">
    <div class="list2" id="addonbox">
      <div class="row related-addon">
        @foreach($addon1 as $addonsdata)
          <div class="each-addon col-xxl-4 col-lg-4 col-md-6 col-sm-12">
            <div class="row">
              <div class="col-xxl-4 col-lg-4 col-md-4 col-sm-4" style="padding-right:3px; padding-left:3px;">
                <img src="{{ asset('addon_image/' . $addonsdata->image) }}" style="width:100%; height:115px;" alt="Addon Image" />
                <div class="labellist labeldesign col-xxl-12 col-lg-12 col-md-12">
                  <center>Additional Remarks</center>
                </div>
                <div class=" labellist databack1 col-xxl-12 col-lg-12 col-md-12">
                  {{$addonsdata->additional_remarks}}
                </div>
              </div>
              <div class="col-xxl-8 col-lg-8 col-md-8 col-sm-8" >
                <div class="row" style="padding-right:3px; padding-left:3px;">
                  <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-5">
                    Addon Name
                  </div>
                  <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-7">
                    {{$addonsdata->AddonName->name}}
                  </div>
                  <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-5">
                    Addon Code
                  </div>
                  <div class="labellist databack2 col-xxl-7 col-lg-6 col-md-7">
                    {{$addonsdata->addon_code}}
                  </div>
                  <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-5">
                    Purchase Price
                  </div>
                  <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-7">
                    {{$addonsdata->purchase_price}} AED
                  </div>
                  <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-7">
                    Selling Price
                  </div>
                  <div class="labellist databack2 col-xxl-7 col-lg-6 col-md-5">
                    {{$addonsdata->selling_price}} AED
                  </div>
                  <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-7">
                    Lead Time
                  </div>
                  <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-5">
                    {{$addonsdata->lead_time}}
                  </div>
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    <center>Brand</center>
                  </div>
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    <center>Model</center>
                  </div>
                  @foreach($addonsdata->AddonTypes as $AddonTypes)
                    <div class="divcolorclass" value="5" hidden>
                    </div>
                    <div class="divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                      {{$AddonTypes->brand_id}}
                    </div>
                    <div class="divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                      {{$AddonTypes->model_id}}
                    </div>
                  @endforeach
                </div>                     
              </div>          
            </div> 
            </br>
            <div class="row" style="position: absolute; bottom: 3px; right: 5px; ">
              <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12" >
                <a class="btn btn-sm btn-info" href="{{ route('addon.editDetails',$addonsdata->id) }}">
                  <i class="fa fa-edit" aria-hidden="true"></i> Edit
                </a>
              </div>     
            </div>
          </div>
          </br>
          
        @endforeach
        </br>
      </div>
    </div>
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
            <th>Purchase Price ( AED )</th>
            <th>Selling Price ( AED )</th>
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
              <td>{{ $addon->brand_id }}</td>
              <td>{{ $addon->model_id }}</td>
              <td>{{ $addon->lead_time }}</td>
              <td>{{ $addon->additional_remarks }}</td>
              <td>{{ $addon->purchase_price }}</td>
              <td>{{ $addon->selling_price }}</td>
              <td>
                <a class="btn btn-sm btn-info" href="{{ route('addon.editDetails',$addon->addon_details_table_id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

                                   



                                               <!-- <div class="card-body">
                                                      @foreach($addon1 as $addonsdata)
                                                      <div class="table-responsive">
                                                      <div class="each-addon col-xxl-4 col-lg-6 col-md-12">
                                                      <table id="" class="table table-striped table-editable table-edits table">
                                                  
                                                  <tbody>
                                                    <div hidden>{{$i=0;}}</div>
                                                        <tr data-id="1">
                                                        <th>No</th>
                                                          <td>{{ ++$i }}</td>
                                                        </tr>
                                                        </tbody>
                                                  </table>
                                                  </div>
                                                  </div>
                                                      @endforeach
                                            </div> -->