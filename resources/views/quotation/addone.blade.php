<!doctype html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    @include('partials/head-css')
</head>
<body data-layout="horizontal">
    <div id="layout-wrapper">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Addone Details</h4>
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                                <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
                                        <thead>
                                       <tr>
                                       <th>Image</th>
                                       <th>Addon Name</th>
                                       <th>Addon Code</th>
                                       <th>Lead Time</th>
                                       <th>Additional Remarks</th>
                                       <th>Price(AED)</th>
                                       <th>Action</th>
                                       </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($result as $key => $addon)
										<tr>
                                        <td>
                                        @if (file_exists(public_path().'/addon_image/'.$addon->image))
                                        <img src="{{ $addon->image }}" alt="Addon thumbnail" style="width: 100px;">
                                    @else
                                    <img src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class"
                                    style="max-height:159px; max-width:232px;" alt="Addon Image"  />
                                    @endif
                                            
                                        </td>
                                        <td>{{ $addon->name }}</td>
                                        <td>{{ $addon->addon_code }}</td>
                                        <td>{{ $addon->lead_time }}</td>
                                        <td>{{ $addon->additional_remarks }}</td>
                                        <td>Selling Price</td>
                                        <td>
                                        <a href="#" class="plus-circle-link addadones" id="addadones_{{ $addon->idp }}">
                                         <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                         </a>
                                         <input type="hidden" class="vehiclesid" value="{{ $VehiclesId }}" />
                                         </td>
                                         </tr>
                                         @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
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
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.addadones').click(function(){
                var id = this.id;
                var split_id = id.split("_");
                var actiond = split_id[0];
                var addon_id = split_id[1];
                var vehiclesid = $('.vehiclesid').val();
                let url = "{{ route('quotation.addone-insert') }}";
               if(vehiclesid != '')
             {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
                 });
                 $.ajax({
                     url: url,
                     method:"POST",
                     data:{vehiclesid:vehiclesid, actiond:actiond, addon_id:addon_id},
                     dataType:"JSON",
                     success:function(data)
                     {
                     //console.log(data);
                    // console.log("123");
                    $('#addadones_' + addon_id).closest('.addadones').remove();
                     }
                }); 
            }
        });
});
</script>
</body>
</html>