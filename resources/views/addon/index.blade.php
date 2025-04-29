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
    /* .page-overlay {
        z-index: 9999;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        display: block;
        text-align: center;
        background-color: rgba(128,128,128,0.5);
    } */
}
</style>
  @canany(['addon-create', 'accessories-list', 'spare-parts-list', 'kit-list'])
  @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-create','accessories-list','spare-parts-list','kit-list']);
  @endphp
  @if ($hasPermission)

  <div class="card-header">
    <h4 class="card-title">
      @if($data == 'P') Accessories @elseif($data == 'SP') Spare Parts @elseif($data == 'K') Kits @elseif($data == 'all') Addons @endif
       Info
    </h4>

    @canany(['addon-create'])
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-create']);
    @endphp
    @if ($hasPermission)
    <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('addon.create') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Addon
    </a>
    @endif
    @endcanany

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
        <input type="hidden" id="is-addon-box-view" value="1">
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
  <!-- <div class="page-overlay"></div> -->
  <div class="card-header">
    <form>
      <input type="text" value="{{$data}}" id="data" hidden>
      <div class="row">
        <h6><center> @if($data == 'P') Accessories @elseif($data == 'SP') Spare Parts @elseif($data == 'K') Kits @elseif($data == 'all') Addons @endif Filters</center></h6>
        <div class="col-xxl-4 col-lg-4 col-md-6 col-sm-12">
        <label class="col-form-label text-md-end">{{ __('Choose Addon Name') }}</label>
          <select id="fltr-addon-code" multiple="true" style="width: 100%;">
            @foreach($addonMasters as $addonMaster)
              <option value="{{$addonMaster->id}}">{{$addonMaster->addon->name}} @if($addonMaster->description != '')- {{$addonMaster->description}} @endif</option>
            @endforeach
          </select>
        </div>
        <div class="col-xxl-4 col-lg-4 col-md-6 col-sm-12">
        <label class="col-form-label text-md-end">{{ __('Choose Brand Name') }}</label>
          <select id="fltr-brand" multiple="true" style="width: 100%;">
              @if($data == 'P' || $data == 'all')
                  <option id="allBrandsFilter" value="yes">All Brands</option>
              @endif

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
  <input type="hidden" id="start" value="0">
  <input type="hidden" id="serial_number" value="0">
  <input type="hidden" id="rowperpage" value="{{ $rowperpage }}">
  <input type="hidden" id="totalrecords" value="{{$rowperpage}}">
  <input type="hidden" name="addon_type" id="addon_type" value="{{$data}}">
 @endcanany
  <script type="text/javascript">
    var brandMatsers = {!! json_encode($brandMatsers) !!};
var currentOnChange = '';
    $(document).ready(function ()
    {
      // console.log(addon[0]);
      $("#fltr-addon-code").attr("data-placeholder","Choose Addon Name....     Or     Type Here To Search....");
      $("#fltr-addon-code").select2();
      $("#fltr-brand").attr("data-placeholder","Choose Brand....    Or     Type Here To Search....");
      $("#fltr-brand").select2();
      $("#fltr-model-line").attr("data-placeholder","Choose Model Line....     Or     Type Here To Search....");
      $("#fltr-model-line").select2();
      $('#fltr-addon-code').change(function(e) {
        currentOnChange = 'addon_code';
          var start = 0;
          var totalrecords = 0;

          $('#start').val(start);
          $('#totalrecords').val(totalrecords);
          $('#serial_number').val(0);
          $('.each-addon').attr('hidden', true);
          $(".each-addon-table-row").attr('hidden', true);

          if($(window).scrollTop() + $(window).height() >= $(document).height()) {
              fetchData(start,totalrecords);
          }
      });
      $('#fltr-brand').change(function(e) {
        currentOnChange = 'brand';
          var BrandIds = $(this).val();
          var totalrecords = 0;
          var start = 0;

          $('#start').val(start);
          $('#totalrecords').val(totalrecords);
          $('#serial_number').val(0);

          $('.each-addon').attr('hidden', true);
          $(".each-addon-table-row").attr('hidden', true);
          if (BrandIds === undefined || BrandIds.length == 0) {
              $('#allBrandsFilter').prop("disabled", false);
              $('.allBrandsFilterClass').prop("disabled", false);
              $('#ModelLineDiv').show();
              // getRelatedModelLines(BrandIds);
              if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                  fetchData(0,0);
                  // $('.page-overlay').show();
              }
          } else {
              if (BrandIds.includes('yes')) {
                  $('.allBrandsFilterClass').prop("disabled", true);
                  $("#fltr-model-line option:selected").prop("selected", false);
                  $("#fltr-model-line").trigger('change.select2');
                  $('#ModelLineDiv').hide();
                  if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                      fetchData(start,totalrecords);
                  }
              }
              else {
                  $('#allBrandsFilter').prop("disabled", true);
                  if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                      fetchData(0,0);
                      // $('.page-overlay').show();
                  }
                  // getRelatedModelLines(BrandIds)
              }
          }

      });

      $('#fltr-model-line').change(function(e) {
        currentOnChange = 'model_line';
          e.preventDefault();
            // set total record and start = 0
          var start = 0;
          var totalrecords = 0;

          $('#start').val(start);
          $('#totalrecords').val(totalrecords);
          $('#serial_number').val(0);
          $('.each-addon').attr('hidden', true);
          $(".each-addon-table-row").attr('hidden', true);
          if($(window).scrollTop() + $(window).height() >= $(document).height()) {
              fetchData(start,totalrecords);
              // $('.page-overlay').show();
          }
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
      $('#is-addon-box-view').val(0);

      $('#start').val(0);
      $('#totalrecords').val(0);
      $('#serial_number').val(0);

        $(".each-addon-table-row").attr('hidden', true);
        if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            fetchData(0,0);
            // $('.page-overlay').show();
        }
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
        $('#is-addon-box-view').val(1);
        $('#start').val(0);
        $('#totalrecords').val(0);
        $('#serial_number').val(0);

        $(".each-addon").attr('hidden', true);
        if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            fetchData(0,0);
            // $('.page-overlay').show();
        }
    }

    function onScroll(){

      if (Math.ceil($(window).scrollTop() + $(window).height()) >= $(document).height()) {
            var start = Number($('#start').val());
            var totalrecords = Number($('#totalrecords').val());
            var rowperpage = Number($('#rowperpage').val());
            start = start + rowperpage;
            if(start <= totalrecords) {
                $('#start').val(start);
            }
            fetchData(start,totalrecords);
        }
    }
    $(window).scroll(function(){
        onScroll();
    });
    function fetchData(start,totalrecords) {

        var isAddonBoxView = $('#is-addon-box-view').val();
        var AddonIds = $('#fltr-addon-code').val();
        var BrandIds = $('#fltr-brand').val();

        var ModelLineIds = $('#fltr-model-line').val();
        var addon_type = $('#addon_type').val();

        var serial_number = $('#serial_number').val();

        var rowperpage = Number($('#rowperpage').val());
        $('.overlay').show();

            $.ajax({
                url:"{{url('getAddonlists')}}",
                method:'GET',
                data: {
                    start:start,
                    addon_type:addon_type,
                    AddonIds: AddonIds,
                    BrandIds: BrandIds,
                    ModelLineIds: ModelLineIds,
                    isAddonBoxView:isAddonBoxView,
                    serial_number:serial_number
                },

                dataType: 'json',
                success: function(response){
                  var myEle = document.getElementById("noData");
                  if(myEle) {
                      myEle.remove();
                  }
                    $('#serial_number').val(response.serial_number);
                    var total = parseInt(rowperpage) + parseInt(totalrecords);
                    $('#totalrecords').val(total);

                    $(".each-addon:last").after(response.addon_box_html).show().fadeIn("slow");
                    $(".each-addon-table-row:last").after(response.table_html).show().fadeIn("slow");
                   // checkWindowSize();
                    var addonIds = response.addonIds;
                    hideModelDescription(addonIds);
                    // console.log(response.model_lines);
                    var modelLines = response.model_lines;

                        if(currentOnChange == 'brand') {
                        $("#fltr-model-line").html("");
                        let BrandModelLines = [];
                        BrandModelLines.push
                        ({
                            id: 'allmodellines',
                            text: 'All Model Lines'
                        });
                        $.each(response.model_lines, function (key, value) {
                            BrandModelLines.push
                            ({
                                id: value.id,
                                text: value.model_line
                            });
                        });

                        $('#fltr-model-line').select2
                        ({
                            placeholder: 'Choose Model Line....     Or     Type Here To Search....',
                            allowClear: true,
                            data: BrandModelLines,
                        });
                        if(ModelLineIds != null)
                        {
                            var setSelected = [];
                            for(let i=0; i<BrandModelLines.length; i++)
                            {
                                currentModelId = '';
                                currentModelId = BrandModelLines[i].id;
                                for(let j=0; j<ModelLineIds.length; j++)
                                {
                                    if(ModelLineIds[j] == currentModelId)
                                    {
                                        setSelected.push(currentModelId);
                                    }
                                }
                            }
                            $("#fltr-model-line").select2().val(setSelected).trigger('change');
                        }
                    }

                    $('.overlay').hide();

                }
            });
        }
  </script>
@endsection

