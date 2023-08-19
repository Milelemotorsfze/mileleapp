@extends('layouts.table')
@section('content')
        <div class="card-header">
            <h4 class="card-title">Master Addon Info</h4>
            <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger" >
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                    {{ Session::get('error') }}
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-success" id="success-alert">
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                    {{ Session::get('success') }}
                </div>
            @endif
        </div>
{{--        @can('warranty-selling-price-histories-list')--}}
{{--        @php--}}
{{--        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-selling-price-histories-list']);--}}
{{--        @endphp--}}
{{--        @if ($hasPermission)--}}
        <div class="portfolio">
            <ul class="nav nav-pills nav-fill" id="my-tab">
                <li class="nav-item">
                    <a class="nav-link @if($addonType == 'P' ) active @endif " data-bs-toggle="pill" href="#accessories">Accessories </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($addonType == 'SP') active @endif " data-bs-toggle="pill" href="#spare-parts">Spare Parts </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($addonType == 'K') active @endif " data-bs-toggle="pill" href="#kits">Kit </a>
                </li>
            </ul>
        </div>
        <div class="tab-content"  >
            <div class="tab-pane fade show @if($addonType == 'P' ) active @endif" id="accessories">
                <div class="card-body">
                    <div class="table-responsive" >
                        <table id="accessories-table" class="table table-striped table-editable table-edits table table-condensed">
                            <thead class="bg-soft-secondary">
                            <tr>
                                <th>S.NO</th>
                                <th>Name</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                            @foreach ($accessories as $key => $data)
                                <tr >
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->createdUser->name ?? '' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</td>
                                    <td>
                                        <a title="Edit Accessories" class="btn btn-sm btn-info" href="{{ route('master-addons.edit', $data->id) }}">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                </a>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show  @if($addonType == 'SP') active @endif" id="spare-parts">
                <div class="card-body">
                    <div class="table-responsive" >
                        <table id="spare-parts-table" class="table table-striped table-editable table-edits table table-condensed">
                            <thead class="bg-soft-secondary">
                            <tr>
                                <th>S.NO</th>
                                <th>Name</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                            @foreach ($spareParts as $key => $data)
                                <tr >
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->createdUser->name ?? '' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</td>
                                    <td>
                                        <a title="Edit SpareParts" class="btn btn-sm btn-info" href="{{ route('master-addons.edit', $data->id) }}">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                </a>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show  @if($addonType == 'K') active @endif" id="kits">
                <div class="card-body">
                    <div class="table-responsive" >
                        <table id="kits-table" class="table table-striped table-editable table-edits table table-condensed">
                            <thead class="bg-soft-secondary">
                            <tr>
                                <th>S.NO</th>
                                <th>Name</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                            @foreach ($kits as $key => $data)
                                <tr >
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->createdUser->name ?? '' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</td>
                                    <td>
                                        <a title="Edit Accessories" class="btn btn-sm btn-info" href="{{ route('master-addons.edit', $data->id) }}">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                </a>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
{{--       @endif--}}
{{--       @endcan--}}
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.status-reject-button').click(function (e) {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                updateSellingPrice(id, status)
            })
            $('.status-approve-button').click(function (e) {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                updateSellingPrice(id, status)
            })
            function updateSellingPrice(id, status) {

                var updated_price = $('#updated-price').val();
                let url = '{{ route('warranty-brands.update-selling-price') }}';
                if(status == 'rejected') {
                    var message = 'Reject';
                }else{
                    var message = 'Approve';
                }
                var confirm = alertify.confirm('Are you sure you want to '+ message +' this item ?',function (e) {
                    if (e) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: "json",
                            data: {
                                id: id,
                                status: status,
                                updated_price: updated_price,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (data) {
                                window.location.reload();
                                alertify.success(status + " Successfully")
                            }
                        });
                    }

                }).set({title:"Update Status"})
            }
        })
        function inputNumberAbs(currentPriceInput)
        {
            var id = currentPriceInput.id;
            var input = document.getElementById(id);
            var val = input.value;
            val = val.replace(/^0+|[^\d.]/g, '');
            if(val.split('.').length>2)
            {
                val =val.replace(/\.+$/,"");
            }
            input.value = val;
        }
    </script>
@endpush
