@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Inventory List
        </h4>
    </div>
    <div class="card-body">
        @can('supplier-inventory-edit')
        <div class="ml-auto">
            <a href="{{ route('supplier-inventories.create') }}" class="btn btn-primary me-md-2">Upload CSV File</a>
        </div>
            <br>
        @endcan
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table table-condensed" style="">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>S.NO</th>
                    <th>Model</th>
                    <th>SFX</th>
                    <th>Variant</th>
                    <th>Total QTY</th>
                    <th>Actual QTY</th>
                    <th>Group By Colors</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($supplierInventories as $key => $supplierInventory)
                    <tr class="inventory-collapse" data-bs-toggle="collapse" data-bs-target="#get-child-rows-{{$key}}"
                        data-id="{{$supplierInventory->master_model_id}}" data-model="{{$supplierInventory->masterModel->model ?? ''}}"
                        data-sfx="{{ $supplierInventory->masterModel->sfx ?? ''}}" >
                        <td>{{ ++$i }}</td>
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
{{--                    </tr>--}}
                    <div class="collapse accordion-collapse row-add" id="get-child-rows-{{$key}}" data-key="{{$key}}" data-bs-parent=".table">
{{--                        @foreach($supplierInventory)--}}

{{--                        @endforeach--}}
                    </div>

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        {{--$(document).ready(function () {--}}
        {{--    $('.inventory-collapse').click(function() {--}}
        {{--       let masterModelId = $(this).attr('data-id');--}}
        {{--        let model = $(this).attr('data-model');--}}
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
        {{--                $('.row-add').empty();--}}
        {{--                $i =0;--}}
        {{--                jQuery.each(data, function(i,item){--}}
        {{--                    console.log(item.chasis);--}}
        {{--                    $('.row-add').append('<tr>','<td>'+ ++$i +'</td>','<td>'+model+'</td>','<td>'+sfx+'</td>','<td>'+item.chasis+'</td>','</tr>','</br>');--}}

        {{--                     //.appendTo('#records_table');--}}
        {{--                });--}}
        {{--            }--}}
        {{--        });--}}
        {{--    })--}}

        {{--});--}}
    </script>
@endsection

















