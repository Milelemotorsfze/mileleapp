@extends('layouts.main')
@section('content')
<style>
  /* .nav-fill .nav-item .nav-link, .nav-justified .nav-item .nav-link
  {
    width :20%;
  } */
  .widthinput
    {
        height:32px!important;
    }
  </style>
  <style>
body {font-family: Arial, Helvetica, sans-serif;}

#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modalForImage {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 10px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: black; /* Fallback color */
  background-color: rgba(128,128,128,0.5);/* Black w/ opacity */
}

/* Modal Content (image) */
.modalContentForImage {
  padding-top: 100px; /* Location of the box */
  margin: auto;
  display: block;
  width: 100%!important;
  height:auto!important;
  max-width: 700px!important;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 100%!important;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modalContentForImage, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: fixed;
  top: 50px;
  right: 50px;
  color: black;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modalContentForImage {
    width: 100%;
  }
}
</style>
  @canany(['addon-create', 'accessories-list', 'spare-parts-list', 'kit-list'])
  @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-create','accessories-list','spare-parts-list','kit-list']);
  @endphp
  @if ($hasPermission)
  <div class="card-header">
    <h4 class="card-title">
      Addon Info
    </h4>
    <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('addon.create') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Addon
    </a>
    @canany(['accessories-list', 'spare-parts-list', 'kit-list'])
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['accessories-list','spare-parts-list','kit-list']);
    @endphp
    @if ($hasPermission)
    <a id="addonListTableButton" onclick="showAddonTable()" style="float: right; margin-right:5px;" class="btn btn-sm btn-info">
      <i class="fa fa-table" aria-hidden="true"></i>
    </a>  
    <a id="addonBoxButton" onclick="showAddonBox()" style="float: right; margin-right:5px;" class="btn btn-sm btn-info" hidden>
      <i class="fa fa-th-large" aria-hidden="true"></i>
    </a> 
    @endif
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
        <h6><center>Addon Filters</center></h6>
        <div class="col-xxl-4 col-lg-4 col-md-6 col-sm-12">
        <label class="col-form-label text-md-end">{{ __('Choose Addon Name') }}</label>
          <select id="fltr-addon-code" multiple="true" style="width: 100%;">
            @foreach($addonMasters as $addonMaster)
              <option value="{{$addonMaster->id}}">{{$addonMaster->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-xxl-4 col-lg-4 col-md-6 col-sm-12">
        <label class="col-form-label text-md-end">{{ __('Choose Brand Name') }}</label>
          <select id="fltr-brand" multiple="true" style="width: 100%;">
          <option id="allBrandsFilter" value="yes">All Brands</option>
            @foreach($brandMatsers as $brandMatser)
              <option class="allBrandsFilterClass" value="{{$brandMatser->id}}">{{$brandMatser->brand_name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-xxl-4 col-lg-4 col-md-6 col-sm-12" id="ModelLineDiv">
        <label class="col-form-label text-md-end">{{ __('Choose Model Line') }}</label>
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
  
  <div id="myModal" class="modal modalForImage">
  <span class="closeImage close">&times;</span>
  <img class="modalContentForImage" id="img01">
  <div id="caption"></div>
</div>
    @include('addon.listbox')
    @include('addon.table')
@endif
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
    // show image in large view
    $('.image-click-class').click(function (e) 
    {
        var id =  $(this).attr('id');
        var src = $(this).attr('src');
        var modal = document.getElementById("myModal");
        var img = document.getElementById(id);
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        modal.style.display = "block";
        modalImg.src = src;
        captionText.innerHTML = this.alt;
      })
      $('.closeImage').click(function (e) 
      {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
      })   
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
          });
          $.each(result.addonsTable, function (index, value)
          {
            if(value.is_all_brands == 'yes')
            {
              $("."+value.id+"_allbrands").show();
            }
            else
            {
              $.each(value.addon_types, function (index, val)
              {
                if(val.is_all_model_lines == 'yes')
                {
                  $("."+value.id+"_"+val.brand_id+"_all_model_lines").show();
                }
                else
                {
                  $("."+value.id+"_"+val.brand_id+"_"+val.model_id).show();
                }
              });
            }
          });
        }
      });
    }
  </script>
@endsection

