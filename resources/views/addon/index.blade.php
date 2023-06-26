@extends('layouts.table')
@section('content')
<style>
  .nav-fill .nav-item .nav-link, .nav-justified .nav-item .nav-link
  {
    width :20%;
  }
  </style>
  @canany(['addon-create', 'accessories-list', 'spare-parts-list', 'kit-list'])
  <div class="card-header">
    <h4 class="card-title">
      Addon Info
    </h4>
    <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('addon.create') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Addon
    </a>
    @canany(['accessories-list', 'spare-parts-list', 'kit-list'])
    <a id="addonListTableButton" onclick="showAddonTable()" style="float: right; margin-right:5px;" class="btn btn-sm btn-info">
      <i class="fa fa-table" aria-hidden="true"></i>
    </a>  
    <a id="addonBoxButton" onclick="showAddonBox()" style="float: right; margin-right:5px;" class="btn btn-sm btn-info" hidden>
      <i class="fa fa-th-large" aria-hidden="true"></i>
    </a> 
    @endcanany
    <ul class="nav nav-pills nav-fill">
      <!-- <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Active Addons</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Inactive Addons</a>
      </li> -->
    </ul> 
  </div>
  <div class="card-header">
    <form>
      <input type="text", value="{{$data}}" id="data" hidden>
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
          <option id="allBrandsFilter" value="yes">All Brands</option>
            @foreach($brandMatsers as $brandMatser)
              <option class="allBrandsFilterClass" value="{{$brandMatser->id}}">{{$brandMatser->brand_name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-xxl-4 col-lg-4 col-md-6 col-sm-12" id="ModelLineDiv">
          <select id="fltr-model-line" multiple="true" style="width: 100%;">
          <option id="allMoLiId" value="yes">All Model Lines</option>
          @foreach($modelLineMasters as $modelLineMaster)
          <option class="allMoLiClass" value="{{$modelLineMaster->id}}">{{$modelLineMaster->model_line}}</option>
          @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  
    @include('addon.listbox')
    @include('addon.table')
 @endcanany
  <script type="text/javascript">
    var brandMatsers = {!! json_encode($brandMatsers) !!};
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
      var Data = '';
      var AddonIds = $('#fltr-addon-code').val();
      var BrandIds = $('#fltr-brand').val();
      if (BrandIds === undefined || BrandIds.length == 0) 
      {
        $('#allBrandsFilter').prop("disabled", false);
        $('.allBrandsFilterClass').prop("disabled", false);
        $('#ModelLineDiv').show();
      }
      else
      {
        if(BrandIds.includes('yes'))
        {
          $('.allBrandsFilterClass').prop("disabled", true);
          $('#ModelLineDiv').hide();
        }
        else
        {
          $('#allBrandsFilter').prop("disabled", true);
        }
      } 

      var ModelLineIds = $('#fltr-model-line').val();
      if (ModelLineIds === undefined || ModelLineIds.length == 0) 
      {
        $('#allMoLiId').prop("disabled", false);
        $('.allMoLiClass').prop("disabled", false);
      }
      else
      {
        if(ModelLineIds.includes('yes'))
        {
          $('.allMoLiClass').prop("disabled", true);
        }
        else
        {
          $('#allMoLiId').prop("disabled", true);
        }
      }

      var Data = $('#data').val();
      $.ajax
      ({
        url:"{{url('addonFilters')}}",
        type: "POST",
        data: 
        {
          AddonIds: AddonIds,
          BrandIds: BrandIds,
          ModelLineIds: ModelLineIds,
          Data: Data,
          _token: '{{csrf_token()}}' 
        },
        dataType : 'json',
        success: function(result)
        {
          $(".each-addon").hide();
          $(".tr").hide();
          $.each(result.addonsBox, function (index, value)
          {
            $("#"+value).show();
            $("."+value).show();
          });
       
          // location.reload();
          // console.log(result);
          
          // $.each(globalThis.OldAddons,function(key,value)
          // {  
          //   $("#"+value).show();
          // });
          // globalThis.OldAddons = [];                     
          // $.each(result,function(key,value)
          // {  
          //   globalThis.OldAddons .push(value);
          //   $("#"+value).hide();
          //   // $("#"+value).addClass('hide');
          // });
        }
      });
    }
  </script>
@endsection
