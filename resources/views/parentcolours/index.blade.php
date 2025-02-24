@extends('layouts.table')

<style>
    .error {
        color: red;
    }
</style>

@section('content')

<div class="card-header">
    <h4 class="card-title">
        Parent Colors List
        <a class="btn btn-sm btn-info float-end mr-3" href="#" data-bs-toggle="modal" data-bs-target="#addParentColorModal">
            <i class="fa fa-plus" aria-hidden="true"></i> Add Parent Color
        </a>

        <a class="btn btn-sm btn-primary float-end pr-3" style="margin-right:5px;" href="{{ route('colourcode.index') }}" title="Colors List">
            <i class="fa fa-table" aria-hidden="true"></i> Colors List
        </a>
</div>

<div class="card-body">
    @if (Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif

    <div class="table-responsive">
        <table id="parentColorsTable" class="table table-striped table-editable table-edits table">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>S.No.</th>
                    <th>Parent Color Name</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($parentColours as $key => $parentColour)
                <tr>
                    <td>{{ $parentColour->id }}</td>
                    <td>{{ $parentColour->name }}</td>
                    <td>{{ $parentColour->createdBy ? $parentColour->createdBy->name : 'N/A' }}</td>
                    <td>
                        <button class="btn btn-sm btn-info edit-parent-color" data-id="{{ $parentColour->id }}">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-parent-color" data-id="{{ $parentColour->id }}">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('parentcolours.modal_add')

    <div class="modal fade" id="editParentColorModal" tabindex="-1" role="dialog" aria-labelledby="editParentColorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="form-edit-parent-color" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Parent Color</h5>
                        <button type="button" class="close close-button" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-white">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_parent_color_id" name="id">
                        <div class="form-group">
                            <label for="edit_parent_color_name"><strong>Color Name:</strong></label>
                            <input type="text" class="form-control" id="edit_parent_color_name" name="name" required>
                            <div id="editParentColorError" class="error mt-1" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#parentColorsTable').DataTable({
            "order": [],
            "paging": true,
            "searching": true
        });
    });
</script>


<script>
    function validateColorName(name) {
        const regex = /^(?! )[A-Za-z]+(?:\s[A-Za-z]+)*(?:\s\/\s[A-Za-z]+(?:\s[A-Za-z]+)*)*(?! )$/;
        return regex.test(name);
    }

    function formatColorName(name) {
        return name
            .replace(/\s+/g, ' ')
            .replace(/\s*\/\s*/g, ' / ')
            .trim()
            .replace(/\b\w/g, char => char.toUpperCase())
            .replace(/\B[A-Z]/g, char => char.toLowerCase());
    }

    function validateParentColorName(inputId, errorId) {
        let parentName = $(inputId).val().trim();

        if (parentName.length === 0) {
            $(errorId).text('Parent color name is required.').show();
            return false;
        } else if (!validateColorName(parentName)) {
            $(errorId).text('Invalid format! Only alphabets and single "/" allowed with proper spacing.').show();
            return false;
        }

        $(errorId).text('').hide();
        return true;
    }

    function attachAutoFormat(inputSelector) {
        $(inputSelector).on('blur', function() {
            let formatted = formatColorName($(this).val());
            $(this).val(formatted);
        });
    }

    $(document).ready(function() {
        attachAutoFormat('#parent_color_name');
        attachAutoFormat('#edit_parent_color_name');

        $('#form-add-parent-color').on('submit', function(e) {
            e.preventDefault();

            let isValid = validateParentColorName('#parent_color_name', '#parentColorError');
            if (!isValid) return;

            let parentColorName = formatColorName($('#parent_color_name').val().trim());
            $('#parent_color_name').val(parentColorName);

            let formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: '{{ route("parentColours.store") }}',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    $('#parentColorError').text(xhr.responseJSON.message).show();
                }
            });
        });

        $(document).on('click', '.edit-parent-color', function() {
            let id = $(this).data('id');

            $.ajax({
                url: '/parentColours/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    $('#edit_parent_color_id').val(data.id);
                    $('#edit_parent_color_name').val(data.name);
                    $('#editParentColorModal').modal('show');
                }
            });
        });

        $(document).on('click', '.delete-parent-color', function() {
            let id = $(this).data('id');
            let token = '{{ csrf_token() }}';

            if (confirm('Are you sure you want to delete this Parent Color?')) {
                $.ajax({
                    url: '{{ route("parentColours.destroy", ":id") }}'.replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: token
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errorMessage = xhr.responseJSON.message;
                            alert(errorMessage);
                        } else {
                            alert('An error occurred while deleting the parent color.');
                        }
                    }
                });
            }
        });

        $('#form-edit-parent-color').on('submit', function(e) {
            e.preventDefault();

            let isValid = validateParentColorName('#edit_parent_color_name', '#editParentColorError');
            if (!isValid) return;

            let parentColorName = formatColorName($('#edit_parent_color_name').val().trim());
            $('#edit_parent_color_name').val(parentColorName);

            let id = $('#edit_parent_color_id').val();
            let formData = $(this).serialize();

            $.ajax({
                url: '/parentColours/' + id + '/update',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    $('#editParentColorError').text(xhr.responseJSON.message).show();
                }
            });
        });
    });
</script>

@endsection