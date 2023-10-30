@extends('layouts.table')
@section('content')
    @can('supplier-inventory-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list');
        @endphp
        @if ($hasPermission)
        <div class="card-header">
        <h4 class="card-title">
            Inventory Lists
        </h4>
            @can('supplier-inventory-edit')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-edit');
                @endphp
                @if ($hasPermission)
                    <div class="ml-auto float-end">
                        <a href="{{ route('supplier-inventories.create') }}" class="btn btn-primary me-md-2">Upload CSV File</a>
                    </div>
                    <br>
                @endif
            @endcan
    </div>
        <div class="card-body">
            <form id="form-list" action="{{route('supplier-inventories.index')}}" >
                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label text-muted">Vendor</label>
                            <select class="form-control" data-trigger name="supplier_id" id="supplier">
                                <option value="" >Select The Vendor</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $supplier->id == request()->supplier_id ? 'selected'  : ''}}>{{ $supplier->supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label text-muted">Dealer</label>

                                <select class="form-control" data-trigger name="dealers" >
                                    <option value="" >Select The Dealer</option>
                                    <option value="Trans Cars" {{ 'Trans Cars' == request()->dealers ? 'selected'  : ''}}>Trans Cars</option>
                                    <option value="Milele Motors" {{ 'Milele Motors' == request()->dealers ? 'selected'  : '' }}>Milele Motors</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit"  class="btn btn-primary mt-4 search">Search</button>
                        <a href="{{route('supplier-inventories.index')}}">
                            <button type="button"  class="btn btn-primary mt-4 ">Refresh</button>
                        </a>
                    </div>
                </div>
            </form>
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table table-condensed" >
                <thead class="bg-soft-secondary">
                <tr>
                    <th>S.NO</th>
                    <th>Vendor</th>
                    <th>Dealer</th>
                    <th>Model</th>
                    <th>SFX</th>
                    <th>Variant</th>
                    <th>Total QTY</th>
                    <th>Actual QTY</th>
                    <th>Group By Colors</th>
                    <th>View Items</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($supplierInventories as $key => $supplierInventory)
                    <tr class="inventory-collapse" data-bs-toggle="collapse" data-bs-target="#get-child-rows-{{$key}}" id="row-{{$key}}"
                        data-id="{{$supplierInventory->master_model_id}}" data-model="{{$supplierInventory->masterModel->model ?? ''}}"
                        data-sfx="{{ $supplierInventory->masterModel->sfx ?? ''}}" >
                        <td>{{ ++$i }}</td>

                        <td>{{ $supplierInventory->supplier->supplier ?? ''}}</td>
                        <td>{{ $supplierInventory->whole_sales ?? ''}}</td>
                        <td>{{ $supplierInventory->masterModel->model ?? '' }}</td>
                        <td>{{ $supplierInventory->masterModel->sfx ?? '' }}</td>
                        <td>{{ $supplierInventory->masterModel->variant->name ?? 'Variant Listed But Blanked' }}</td>
                        <td>{{ $supplierInventory->total_quantity }}</td>
                        <td>{{ $supplierInventory->actual_quantity }}</td>
                        <td>
                            @foreach($supplierInventory->color_codes as $row)
                                @php
                                    $color_code = $row->color_code;
                                    $color_codeqty = $row->color_code_count;
                                    $code_nameex = "(Colour Not Listed)  ".$color_code;
                                    $colourcode = $color_code;
                                    $colourcodecount = strlen($colourcode);
                                    $extcolour = NULL;
                                    if($colourcodecount == 5)
                                    {
                                    $extcolour = substr($colourcode, 0, 3);
                                    }
                                    if ($colourcodecount == 4)
                                    {
                                    $altercolourcode = "0".$colourcode;
                                    $extcolour = substr($altercolourcode, 0, 3);
                                    }

                                    $query =  \Illuminate\Support\Facades\DB::table('color_codes')
                                    ->select('parent')
                                    ->where('code','=', $extcolour)
                                    ->groupBy('parent')
                                    ->get();

                                    foreach ($query as $row)
                                    {
                                        $code_nameex = $row->parent;
                                    }
                                @endphp
                               {{  $code_nameex }} : {{$color_codeqty }}
                                <br>
                            @endforeach
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#view-details-{{$supplierInventory->master_model_id}}">
                                View Items
                            </button>
                        </td>
                        <div class="modal fade" id="view-details-{{$supplierInventory->master_model_id}}"
                             tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">LOI Items</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-3">
                                        @if($supplierInventory->childRows->count() > 0)
                                            <div class="row  d-none d-lg-block d-xl-block d-xxl-block">
                                                <div class="d-flex">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-12 col-sm-12">
                                                                <label class="form-label">Chasis</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-12 col-sm-12">
                                                                <label  class="form-label">Engine Number</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                                <label class="form-label">Color Code</label>
                                                            </div>
                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                <label class="form-label">PO Arm</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach($supplierInventory->childRows as $value => $data)
                                                <div class="row">
                                                    <div class="d-flex">
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <div class="row mt-3">
                                                                <div class="col-lg-3 col-md-12 col-sm-12">
                                                                    <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                                                    <input type="text" value="{{ $data->chasis ?? ''}}" readonly class="form-control" >
                                                                </div>
                                                                <div class="col-lg-3 col-md-12 col-sm-12">
                                                                    <label  class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                                                    <input type="text" value="{{ $data->engine_number ?? '' }}" readonly class="form-control">
                                                                </div>
                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                    <label class="form-label d-lg-none d-xl-none d-xxl-none">Variant</label>
                                                                    <input type="text" value="{{ $data->color_code ?? '' }}" readonly class="form-control">
                                                                </div>
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                                    <input type="text" value="{{ $data->po_arm }}" readonly class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-center"> No Data Available! </span>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
        @endif
        @endcan
    <script type="text/javascript">
        $(document).ready(function () {

        {{--$('#dtBasicSupplierInventory').on( 'click', 'tr', function () {--}}
        {{--       let masterModelId = $(this).attr('data-id');--}}
        {{--        let model = $(this).attr('data-model');--}}
        {{--        alert(model);--}}
        {{--        let sfx = $(this).attr('data-sfx');--}}
        {{--        let key = $(this).attr('data-key');--}}
        {{--       let url = '{{ route('supplier-inventories.get-child-rows') }}';--}}
        {{--        $.ajax({--}}
        {{--            type: "GET",--}}
        {{--            url: url,--}}
        {{--            dataType: "json",--}}
        {{--            data: {--}}
        {{--                master_model_id: masterModelId--}}
        {{--            },--}}
        {{--            success:function (data) {--}}
        {{--                console.log(data);--}}
        {{--                $('.row-add').empty();--}}
        {{--                $i =0;--}}
        {{--                jQuery.each(data, function(i,item){--}}

        {{--                    $('#dtBasicSupplierInventory ').append('<table width="100%" ><tr><th>Chaisis </th>'+--}}
        {{--                        '<th>Engine </th>' +--}}
        {{--                        '<th>Colour Code </th>' +--}}
        {{--                        '<th>Po Arm </th></tr><tdody><tr>' +--}}
        {{--                        '<td>'+item.chasis +'</td><td>'+item.engine_number+'</td><td>'+item.color_code+'</td><td>'+item.po_arm+'</td></tbody></tr></table></br>');--}}

        {{--                     //.appendTo('#records_table');--}}
        {{--                });--}}
        {{--            }--}}
        {{--        });--}}
        {{--    })--}}

        });
    </script>
@endsection

















