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
                    <th>Production Month</th>
                    <th>po ams</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                    @foreach ($supplierInventories as $key => $supplierInventory)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>  {{ $supplierInventory->masterModel->model ?? '' }}</td>
                            <td> {{ $supplierInventory->masterModel->sfx ?? '' }}</td>
                            <td>
                                <select  class="model-year" data-field="model_year" data-id="{{ $supplierInventory->id }}" id="model_year-editable-{{$supplierInventory->id}}">
                                   @foreach($supplierInventory->modelYears as $modelYear)
                                       <option value="{{ $modelYear }}" {{ $modelYear == $supplierInventory->masterModel->model_year ? 'selected' : '' }}>
                                           {{ $modelYear }}
                                       </option>
                                   @endforeach
                                </select>
                            </td>
                            <td > {{ $supplierInventory->masterModel->variant->name ?? '' }}</td>
                            <td data-field="chasis" id="chasis-editable-{{$supplierInventory->id}}" contenteditable="true" data-id="{{$supplierInventory->id}}" > {{ $supplierInventory->chasis }} </td>
                            <td  data-field="engine_number" id="engine_number-editable-{{$supplierInventory->id}}"
                                 contenteditable="true" data-id="{{$supplierInventory->id}}" > {{ $supplierInventory->engine_number ?? '' }}</td>
                            <td class="eta-import">
                                <input type="date" class="eta-import form-control" data-field="eta_import" id="eta_import-editable-{{$supplierInventory->id}}"
                                       data-id="{{$supplierInventory->id}}"  value="{{ \Carbon\Carbon::parse($supplierInventory->eta_import)->format('d/m/yy') }} ">
{{--                                <span >{{ \Carbon\Carbon::parse($supplierInventory->eta_import)->format('d/mm/yyyy') }}</span>--}}
                            </td>
                            <td  data-field="pord_month" class="pord_month"  id="pord_month-editable-{{$supplierInventory->id}}"  contenteditable="true"
                                data-id="{{$supplierInventory->id}}" >{{$supplierInventory->pord_month}}</td>
                            <td>{{ $supplierInventory->po_arm }}</td>

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
        var updatedData = [];
        $(document).ready(function () {
            var table = $('#dtBasicExample3').DataTable();

            $('#dtBasicExample3 tbody').on('keyup', 'td', function () {

                var id = $(this).data('id');
                var field = $(this).data('field');
                addUpdatedData(id,field);
            });
            $('#dtBasicExample3 tbody td').on('keyup', 'pord_month', function () {
                var id = $(this).data('id');
                var field = $(this).data('field');
                // validation maxi
                addUpdatedData(id,field);
            });
            $('#dtBasicExample3 tbody td').on('change', '.model-year', function () {

                var id = $(this).data('id');
                var field = $(this).data('field');
                addUpdatedData(id,field);

            });
            $('#dtBasicExample3 tbody td').on('change', '.eta-import', function () {
                var id = $(this).data('id');
                var field = $(this).data('field');
                addUpdatedData(id,field);

            });
                $('.update-inventory-btn').on('click', function () {
                    var selectedUpdatedDatas = [];
                    $.each(updatedData,function(key,value) {
                        var splitValue = value.split('-');
                        var cellId = splitValue[1] +'-editable-' + splitValue[0];

                        if(splitValue[1] == 'model_year' ) {
                            var cellValue = $('#'+ cellId).val();
                        }else{
                            var cellValue = $('#'+ cellId).text();
                        }
                        selectedUpdatedDatas.push({id: splitValue[0],field: splitValue[1], value: cellValue});

                    });
                    // console.log("test data");
                    console.log(selectedUpdatedDatas);
                    let url = '{{ route('update-inventory') }}';
                    $.ajax({
                        type:"POST",
                        url: url,
                        data: {
                            selectedUpdatedDatas:  selectedUpdatedDatas,
                            _token: '{{csrf_token()}}'
                        },
                        dataType : 'json',
                        success: function(data) {
                            console.log("success");
                            alertify.success('Inventory Updated Successfully.');
                            location.reload();
                        }
                    });
                });
        });
        function addUpdatedData(id,field) {
            var arrayvalue = id + '-' + field;
            if ($.inArray(arrayvalue, updatedData) == -1) {
                updatedData.push(arrayvalue);
            }
            console.log(updatedData);
        }
    </script>
@endpush




