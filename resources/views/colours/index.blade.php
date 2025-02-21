@extends('layouts.table')

@section('content')

<div class="card-header">
    <h4 class="card-title">
        Color Codes List
        <a class="btn btn-sm btn-info float-end mr-3" href="{{ route('colourcode.create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Create</a>

        <a class="btn btn-sm btn-primary float-end pr-3" style="margin-right:5px;" href="{{ route('parentColours.index') }}" title="Parent Color List">
            <i class="fa fa-table" aria-hidden="true"></i> Parent Color List</a>
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
    <div class="table-responsive">
        <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>S.No.</th>
                    <th>Color Codes</th>
                    <th>Name</th>
                    <th>Belong To</th>
                    <th>Parent Colour</th>
                    <!-- <th>Status</th> -->
                    <th>Created By</th>
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('colour-edit');
                    @endphp
                    @if ($hasPermission)
                    <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($colorcodes as $key => $colorcode)
                <tr data-id="1">
                    <td>{{ $colorcode->id }}</td>
                    <td>{{ $colorcode->dpCodes }}</td>
                    <td>{{ $colorcode->name ?? ''}}</td>
                    <td>
                        @if ($colorcode->belong_to == 'ex')
                        Exterior
                        @elseif ($colorcode->belong_to == 'int')
                        Interior
                        @else
                        {{ $colorcode->belong_to ?? '' }}
                        @endif
                    </td>
                    <td>{{ $colorcode->parentColour ?  $colorcode->parentColour->name : 'N/A' }}</td>

                    <td>
                        {{ $colorcode->createdBy ? $colorcode->createdBy->name : 'N/A' }}
                    </td>

                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('colour-edit');
                    @endphp
                    @if ($hasPermission)
                    <td>
                        <a data-placement="top" href="{{ route('colourcode.edit', $colorcode->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>
                        </a>
                    </td>
                    @endif

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#dtBasicExample3').DataTable({
            "order": [],
            "paging": true,
            "searching": true
        });
    });
</script>

<script>
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let url = $(this).attr('data-url');
        var confirm = alertify.confirm('Are you sure you want to Delete this item ?', function(e) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        _method: 'DELETE',
                        id: 'id',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        location.reload();
                        alertify.success('Item Deleted successfully.');
                    }
                });
            }
        }).set({
            title: "Delete Item"
        })
    });
</script>
@endsection