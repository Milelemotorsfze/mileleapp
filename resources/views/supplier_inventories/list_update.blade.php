@extends('layouts.table')
@section('content')
{{--    @php--}}
{{--        $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list-view-all');--}}
{{--    @endphp--}}
{{--    @if ($hasPermission)--}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                Inventory Stock
            </h4>
{{--            @can('supplier-inventory-list-edit')--}}
{{--                @php--}}
{{--                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list-view-all');--}}
{{--                @endphp--}}
{{--                @if ($hasPermission)--}}
                    <a  class="btn btn-sm btn-info float-end update-inventory-btn" href="#" > Update</a>
{{--                @endif--}}
{{--            @endcan--}}
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
            @if (Session::has('success'))
                <div class="alert alert-success" id="success-alert">
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                    {{ Session::get('success') }}
                </div>
            @endif
        </div>
        <div class="table-responsive p-2">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>S.No</th>
                    <th>Model</th>
                    <th>SFX</th>
                    <th>Model Year</th>
                    <th>Variant</th>
                    <th>Chasis</th>
                    <th>Engine Number</th>
                    <th>ETA Import Date</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}

                    @foreach ($supplierInventories as $key => $supplierInventory)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>  {{ $supplierInventory->masterModel->model ?? '' }}</td>
                            <td> {{ $supplierInventory->masterModel->sfx ?? '' }}</td>
                            <td contenteditable="true"> {{ $supplierInventory->masterModel->model_year ?? '' }}</td>
                            <td> {{ $supplierInventory->masterModel->variant->name ?? '' }}</td>
                            <td data-field="chasis" id="chasis-editable-{{$supplierInventory->id}}" contenteditable="true" data-id="{{$supplierInventory->id}}" > {{ $supplierInventory->chasis }} </td>
                            <td class="editable-field engine_number"  contenteditable="true" data-id="{{$supplierInventory->id}}" > {{ $supplierInventory->engine_number ?? '' }}</td>
                            <td contenteditable="true"> {{ \Carbon\Carbon::parse($supplierInventory->eta_import)->format('d M Y') }}</td>

                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
{{--    @endif--}}
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            var table = $('#dtBasicExample3').DataTable();

            var updatedData = [];

            $('#dtBasicExample3 tbody').on('keyup', 'td', function () {
                var id = $(this).data('id');
                var field = $(this).data('field');

                updatedData.push({id: id, name: field });
                console.log(id);

                console.log(updatedData);
                {{--const updateDataUrl = '{{ route('vehicles.updatedata') }}';--}}
                {{--const updatedData = [];--}}
                {{--editableFields.forEach(field => {--}}
                {{--    console.log(field);--}}
                {{--    const Id = field.getAttribute('data-id');--}}
                {{--    const fieldName = field.classList[1];--}}
                //     const fieldValue = editableFields.innerText.trim();
                //     console.log(fieldValue);

                {{--    const selectElement = field.querySelector('select');--}}
                {{--    if (selectElement) {--}}
                {{--        const selectedOption = selectElement.options[selectElement.selectedIndex];--}}
                {{--        const selectedValue = selectedOption.value;--}}
                {{--        updatedData.push({id: Id, name: fieldName, value: selectedValue});--}}
                {{--    } else {--}}
                {{--        updatedData.push({id: Id, name: fieldName, value: fieldValue});--}}
                {{--    }--}}

                {{--});--}}
                // console.log(updatedData);
                $('.update-inventory-btn').on('click', function () {
                    var selectedUpdatedDatas = [];
                    $.each(updatedData,function(key,value) {
                        console.log(value.name);
                        var cellId = value.name +'-editable-' + value.id;
                        console.log(cellId);
                        var cellValue = $('#'+ cellId).text();
                        console.log("test");
                        console.log(cellValue);
                        selectedUpdatedDatas.push({id: value.id,field: value.name, value: cellValue});

                    });
                    // console.log("test data");
                    console.log(selectedUpdatedDatas);
                });
            });

            // function editRow(row){
            //     console.log("tested");
            //     console.log(row);
            // };
        });
    </script>
@endpush




