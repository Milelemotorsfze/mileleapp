@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Inventory List
        </h4>
    </div>
    <div class="card-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br>
                <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="form-list" action="{{route('supplier-inventories.lists')}}" >
            <div class="row">
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted">Start Date</label>
                        <input type="date" id="start-date" name="start_date" value="{{ old('start_date',$startDate) }}"
                               class="form-control" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted">End Date</label>
                        <input type="date" id="end-date" value="{{ old('end_date',$endDate) }}" name="end_date" class="form-control" />
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit"  class="btn btn-primary mt-4 search">Serach</button>
                    <a href="{{route('supplier-inventories.lists')}}">
                        <button type="button"  class="btn btn-primary mt-4 ">Refresh</button>
                    </a>
                </div>
            </div>
        </form>
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table">
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
                    <tr data-id="1">
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
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        jQuery.validator.addMethod("greaterStart", function (value, element, params) {
            var startDate = $('#start-date').val();
            var endDate = $('#end-date').val();

            if( startDate >= endDate) {
                return false;
            }else{
                return true;
            }
        },'Must be greater than start date.');

        $("#form-list").validate({
            ignore: [],
            rules: {
                start_date: {
                    required: true,

                },
                end_date: {
                    required: true,
                    greaterStart: true
                },
            },
        });
    </script>
@endpush

