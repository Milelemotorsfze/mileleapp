@extends('layouts.table')
@section('content')

    <div class="card-header">
        <h4 class="card-title">
            Inventory List
        </h4>
    </div>
    <div class="card-header">
    </div>
    <div class="card-body">
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
                        <td>{{ $supplierInventory->model }}</td>
                        <td>{{ $supplierInventory->sfx }}</td>
                        <td>{{ $supplierInventory->sf }}</td>
                        <td>{{ $supplierInventory->supplier }}</td>
                        <td>{{ $supplierInventory->color_code }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
