@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Inventory List
        </h4>
    </div>
    <div class="card-body">
        <div class="ml-auto">
            <a href="{{ route('supplier-inventories.create') }}" class="btn btn-primary me-md-2">Upload CSV File</a>
        </div>
        <br>
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table">
                <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Model</th>
                    <th>SFX</th>
                    <th>Variant</th>
                    <th>Total QTY</th>
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
                        <td>
                            @foreach($supplierInventory->color_codes as $color_code)
                               {{  "(Colour Not Listed)  ". $color_code->color_code }} : {{$color_code->color_code_count  }}
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
