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
  <div class="card-header">
    <form>
      <div class="row">
        <div class="col-xxl-4 col-lg-4 col-md-6 col-sm-12">
          <select id="fltr-addon-code" multiple="true" style="width: 100%;">
            @foreach($addonMasters as $addonMaster)
              <option value="{{$addonMaster->id}}">{{$addonMaster->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-xxl-4 col-lg-4 col-md-6 col-sm-12">
          <select id="fltr-brand" multiple="true" style="width: 100%;">
            @foreach($brandMatsers as $brandMatser)
              <option value="{{$brandMatser->brand_name}}">{{$brandMatser->brand_name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-xxl-4 col-lg-4 col-md-6 col-sm-12">
          <select id="fltr-model-line" multiple="true" style="width: 100%;">
          @foreach($modelLineMasters as $modelLineMaster)
          <option value="{{$modelLineMaster->id}}">{{$modelLineMaster->model_line}}</option>
          @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="card-body">
    <div class="list2" id="addonbox">
      <div class="row related-addon">
        @foreach($addon1 as $addonsdata)
          <div id="{{$addonsdata->id}}" class="each-addon col-xxl-4 col-lg-4 col-md-6 col-sm-12">  
            <div class="row">
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                Addon Name
              </div>
              <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
                {{$addonsdata->AddonName->name}}
              </div>
              <div class="col-xxl-4 col-lg-4 col-md-4 col-sm-4" style="padding-right:3px; padding-left:3px;">
                <img src="{{ asset('addon_image/' . $addonsdata->image) }}" style="width:100%; height:115px;" alt="Addon Image" />
                @if($addonsdata->additional_remarks)
                <div class="labellist labeldesign col-xxl-12 col-lg-12 col-md-12">
                  <center>Additional Remarks</center>
                </div>
                <div class=" labellist databack1 col-xxl-12 col-lg-12 col-md-12">
                  {{$addonsdata->additional_remarks}}
                </div>
                @endif
              </div>
              <div class="col-xxl-8 col-lg-8 col-md-8 col-sm-8" >
                <div class="row" style="padding-right:3px; padding-left:3px;">
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
                  @if($addonsdata->lead_time)
                  <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-7">
                    Lead Time
                  </div>
                  <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-5">
                    {{$addonsdata->lead_time}}
                  </div>
                  @endif
                  @if($addonsdata->is_all_brands == 'yes')
                  <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-7">
                    Brand
                  </div>
                  <div class="labellist databack2 col-xxl-7 col-lg-6 col-md-5">
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
            <th>Purchase Price(AED)</th>
            <th>Selling Price(AED)</th>
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
              <td>
                <a class="btn btn-sm btn-info" href="{{ route('addon.editDetails',$addon->addon_details_table_id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function ()
    {
      $("#fltr-addon-code").attr("data-placeholder","Choose Addon Code....     Or     Type Here To Search....");
      $("#fltr-addon-code").select2();
      $("#fltr-brand").attr("data-placeholder","Choose Brand....    Or     Type Here To Search....");
      $("#fltr-brand").select2();
      $("#fltr-model-line").attr("data-placeholder","Choose Model Line....     Or     Type Here To Search....");
      $("#fltr-model-line").select2();
      $('#fltr-addon-code').change(function() 
      {
        addonFilter();
      });
      $('#fltr-brand').change(function() 
      {
        addonFilter();
      });
      $('#fltr-model-line').change(function() 
      {
        addonFilter();
      });
      $('.modal-button').on('click', function()
      {
        var modalId = $(this).data('modal-id');
        $('#' + modalId).addClass('modalshow');
        $('#' + modalId).removeClass('modalhide');
      });
      $('.close').on('click', function()
      {
        $('.modal').addClass('modalhide');
        $('.modal').removeClass('modalshow');
      });
    });
    function closemodal()
    {
      $('.modal').removeClass('modalshow');
      $('.modal').addClass('modalhide');
    }
    function showAddonTable()
    {
      let addonTable = document.getElementById('addonListTable');
      addonTable.hidden = false
      let addonListTableButton = document.getElementById('addonListTableButton');
      addonListTableButton.hidden = true
      let addonbox = document.getElementById('addonbox');
      addonbox.hidden = true
      let addonBoxButton = document.getElementById('addonBoxButton');
      addonBoxButton.hidden = false
    }
    function showAddonBox()
    {
      let addonTable = document.getElementById('addonListTable');
      addonTable.hidden = true
      let addonListTableButton = document.getElementById('addonListTableButton');
      addonListTableButton.hidden = false
      let addonbox = document.getElementById('addonbox');
      addonbox.hidden = false
      let addonBoxButton = document.getElementById('addonBoxButton');
      addonBoxButton.hidden = true
    }
    function addonFilter(global)
    {
      var AddonIds = [];
      var BrandIds = [];
      var ModelLineIds = [];
      var AddonIds = $('#fltr-addon-code').val();
      var BrandIds = $('#fltr-brand').val();
      var ModelLineIds = $('#fltr-model-line').val();
      $.ajax
      ({
        url:"{{url('addonFilters')}}",
        type: "POST",
        data: 
        {
          AddonIds: AddonIds,
          BrandIds: BrandIds,
          ModelLineIds: ModelLineIds,
          _token: '{{csrf_token()}}' 
        },
        dataType : 'json',
        success: function(result)
        {
          $.each(globalThis.OldAddons,function(key,value)
          {  
            // $("#"+value).show();
          });
          globalThis.OldAddons = [];                     
          $.each(result,function(key,value)
          {  
            globalThis.OldAddons .push(value);
            // $("#"+value).hide();
            $("#"+value).addClass('hide');
          });
        }
      });
    }
  </script>
@endsection
<style>
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
</style>