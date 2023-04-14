<!doctype html>
<html lang="en">
    <head>
    @include('partials/head-css')
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<!--These jQuery libraries for chosen need to be included-->
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css" /> -->
		<!--These jQuery libraries for select2 need to be included-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
		<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" /> -->
        <!-- <script>
			$(document).ready(function () {
				//Select2
				$(".country").select2({
					maximumSelectionLength: 2,
				});
				//Chosen
				$(".country1").chosen({
					max_selected_options: 2,
				});
			});
		</script> -->
        <meta charset="utf-8">
        <meta name="csrf-token" content="content">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                background-color:#f2f2f2;
                border-style: solid;
                border-width: 1px;
                border-color: white;
                border-radius: 5px;
                margin-left:0px;
                margin-right:0px;
                margin-top:5px;
                margin-bottom:5px;
                /* padding-left:1px;
                padding-right:1px; */
                /* margin-top: 10px; */
                padding-top:10px;
                padding-bottom:10px;
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
          .databack1{
            background-color:#e6e6ff;
            border-color: white;
          }
          .databack2{
            background-color:#f2f2f2;
            border-color: white;
          }
          /* #addonListTable{
            display: none;
          } */
        </style>
    </head>
    <body data-layout="horizontal">
        <div id="layout-wrapper">
            @include('partials/horizontal')
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('partials/footer')
            </div>
        </div>
        @include('partials/right-sidebar')
        @include('partials/vendor-scripts')
        <script src="{{ asset('libs/table-edits/build/table-edits.min.js')}}"></script>
        <script src="{{ asset('js/pages/table-editable.int.js')}}"></script>
        <script src="{{ asset('js/app.js')}}"></script>
        <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <!-- <script>
$(document).ready(function() {
$('#country-dropdown').on('change', function() {
var country_id = this.value;
$("#state-dropdown").html('');
$.ajax({
url:"{{url('get-states-by-country')}}",
type: "POST",
data: {
country_id: country_id,
_token: '{{csrf_token()}}'
},
dataType : 'json',
success: function(result){
$('#state-dropdown').html('<option value="">Select State</option>');
$.each(result.states,function(key,value){
$("#state-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
});
$('#city-dropdown').html('<option value="">Select State First</option>');
}
});
});
$('#state-dropdown').on('change', function() {
var state_id = this.value;
$("#city-dropdown").html('');
$.ajax({
url:"{{url('get-cities-by-state')}}",
type: "POST",
data: {
state_id: state_id,
_token: '{{csrf_token()}}'
},
dataType : 'json',
success: function(result){
$('#city-dropdown').html('<option value="">Select City</option>');
$.each(result.cities,function(key,value){
$("#city-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
});
}
});
});
});
</script> -->
        <script>
        $(document).ready(function ()
        {

            var oldValue = new Array(1,2,5);
            // filters on addon list
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
//             $('.select2-selection__choice__remove').on('click', function()
//             {
// console.log('jiiiii');
//             });
// var oldValue = [1,1];
console.log(oldValue);
            function addonFilter()
            {
                var oldValue = new Array(1,2);
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
                        oldValue: globalThis.oldValue,
                        _token: '{{csrf_token()}}'
                    },
                    dataType : 'json',
                    success: function(result)
                    {

                        // if(globalThis.oldValue)
                        // {
                        //     console.log('hi');

                        //     $.each(globalThis.oldValue,function(oldValue)
                        // {
                        //     console.log(oldValue);
                        //     $("#"+oldValue).show();
                        // });
                        // }
                        // $.each(result.oldValue,function(oldValue)
                        // {

                        //     $("#"+oldValue).show();

                        //     // console.log(value);
                        // });
                        // globalThis.oldValue = [];
                        $.each(result.addonIds,function(key,value)
                        {

                            $("#"+value).hide();
                            // globalThis.oldValue = categories.push($(this).text());
                            console.log(oldValue);
                            // globalThis.oldValue .push(value);
                            // console.log(value);
                        });
                        console.log(globalThis.oldValue);
                        // var oldValue = result;
                    }
                });
            }
			// datatables
            $('#dtBasicExample').DataTable();
            $('#dtBasicSupplierInventory').DataTable()
            $('#dtBasicExample1').DataTable();
            $('#dtBasicExample2').DataTable();
            $('#dtBasicExample3').DataTable();
            $('.modal-button').on('click', function()
            {
                var modalId = $(this).data('modal-id');
                $('#' + modalId).addClass('modalshow');
                $('#' + modalId).removeClass('modalhide');
                console.log('Modal Show');
                });
                $('.close').on('click', function(){
                    $('.modal').addClass('modalhide');
                    $('.modal').removeClass('modalshow');
                    // $('.modal').hide();
                    console.log('Modal Hidden from close button');
                    });
                    // div colour
                    // alert($('.divcolorclass').val());

                    // show addon list table

                    // $('#addonListTableButton').on('click', function()
                    // {

                    //     // $('#addonListTable').attr("hidden", true);
                    //     // $('#addonListTable').attr("hidden", false);
                    //     // $('#addonListTable').removeAttribute("hidden");
                    //     // alert('hiis');
                    //     // $('#addonListTable').style.display = "block";
                    // });
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
        </script>
    </body>
</html>
