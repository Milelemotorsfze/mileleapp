@extends('layouts.table')
@section('content')
    @can('view-model-lines-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-model-lines-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    Master Model Lines Specification
                </h4>
                <a  class="btn btn-sm btn-info float-end" href="{{ route('model-lines.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
                <a class="btn btn-sm btn-success float-end" href="#" onclick="openAddSpecificationModal()"><i class="fa fa-plus" aria-hidden="true"></i> Add New Specification</a>
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
                <!-- Your modal structure -->
                <div class="modal fade" id="addSpecificationModal" tabindex="-1" aria-labelledby="addSpecificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal content goes here -->
            <div class="modal-header">
                <h5 class="modal-title" id="addSpecificationModalLabel">Add New Specification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Text input for the new specification -->
                <div class="mb-3">
                    <label for="newSpecificationName" class="form-label">Specification Name</label>
                    <input type="text" class="form-control" id="newSpecificationName">
                    <input type="hidden" class="form-control" id="model_line_id" value="{{$model_line_id}}">
                </div>
            </div>
            <div class="modal-footer">
                <!-- Save button -->
                <button type="button" class="btn btn-primary" onclick="saveNewSpecification()">Save</button>
                <!-- Close button -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
                <div class="modal fade incidents-modal" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="updateModalTitleop"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <table id="optionsTable" class="table">
            <!-- Table headers go here -->
            <tbody id="optionsBody">
                <!-- Table body goes here -->
            </tbody>
        </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="updateSpecification">Add New Option:</label>
                <input type="text" class="form-control" id="updateSpecification">
                <input type="hidden" class="form-control" id="updateModalId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="savenewoptions()">Save</button>
            </div>
        </div>
    </div>
</div>
                <div class="table-responsive">
                    <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Ref.No</th>
                            <th>Specification</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                            @foreach ($specifications as $key => $specifications)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $specifications->name }}</td>
                                    <td>
                                        <a data-placement="top" href="#" class="btn btn-info btn-sm view-btn" data-id="{{ $specifications->id }}" data-name="{{ $specifications->name }}" title="View Options">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a data-placement="top" href="#" class="btn btn-success btn-sm update-btn" data-id="{{ $specifications->id }}" data-name="{{ $specifications->name }}" title="Add Options">
    <i class="fa fa-plus"></i>
</a>
</td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

       @endif
        @endcan
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('.view-btn').on('click', function (e) {
    e.preventDefault();
    var specificationId = $(this).data('id');
    var specificationName = $(this).data('name'); // Store the name in a variable

    $.ajax({
        url: '/model-lines/viewspec/' + specificationId,
        type: 'GET',
        success: function (data) {
            populateModal(data);
            // Use the stored specificationName variable here
            $('#updateModalTitleop').text('Options: ' + specificationName);
            $('#myModal').modal('show');
        }
    });
});
        $('.update-btn').on('click', function (e) {
    e.preventDefault();
    var specificationId = $(this).data('id');
    var specificationName = $(this).data('name');
    $('#updateModalTitle').text('Add New Options:  ' + specificationName);
    $('#updateModalId').val(specificationId);
    $('#updateModal').modal('show');
});
        function populateModal(data) {
            var optionsTable = $('#optionsTable');
            var optionsBody = $('#optionsBody');
            optionsBody.empty();
            $.each(data.options, function (index, option) {
                var row = '<tr>' +
                    '<td>' + option.name + '</td>' +
                    '</tr>';
                optionsBody.append(row);
            });
        }
        $(document).on('click', function (e) {
            if ($(e.target).is('#myModal')) {
                $('#myModal').modal('hide');
            }
        });
    });
</script>
<script>
    function savenewoptions() {
        var specificationId = $('#updateModalId').val();
        var newOption = $('#updateSpecification').val();
        console.log(specificationId);
        if (newOption.trim() === '') {
            alert('Please enter a valid option.');
            return;
        }
        $.ajax({
            url: '{{ route('variants.saveOption') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                specificationId: specificationId,
                newOption: newOption
            },
            success: function (response) {
                alertify.success('Specification Option successfully Added');
                $('#updateModal').modal('hide');
            },
            error: function (error) {
                alertify.warning('Error adding option: ' + error.responseText);
            }
        });
    }
</script>
<script>
    function openAddSpecificationModal() {
        $('#addSpecificationModal').modal('show');
    }
    function saveNewSpecification() {
        var newSpecificationName = $('#newSpecificationName').val();
        var model_line_id = $('#model_line_id').val();
        // console.log("Saving new specification: " + newSpecificationName);
        // console.log("Saving new specification: " + model_line_id);
        if (newSpecificationName.trim() === '') {
            alert('Please enter a valid Specification.');
            return;
        }
        $.ajax({
            url: '{{ route('variants.savespecification') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                newSpecificationName: newSpecificationName,
                model_line_id: model_line_id
            },
            success: function (response) {
                alertify.success('Specification successfully Added');
                $('#addSpecificationModal').modal('hide');
                setTimeout(function() {
                    window.location.reload();
        }, 1000);
            },
            error: function (error) {
                alertify.warning('Error adding option: ' + error.responseText);
            }
        });
    }
</script>
@endpush