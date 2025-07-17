@extends('layouts.table')

@section('content')
<div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="card-title mb-0">Countries</h2>
        <a href="{{ route('countries.create') }}" class="btn btn-primary">+ Add New Country</a>
    </div>

    @if (count($errors) > 0)
    <div class="alert alert-danger mt-3 mb-0">
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
    <div class="alert alert-success mt-3 mb-0" id="success-alert">
        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
        {{ Session::get('success') }}
    </div>
    @endif
</div>
<div class="card-body">

    <div class="table-responsive">
        <table id="PFI-table" class="table table-striped table-editable table-edits table table-condensed">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Nationality</th>
                    <th>ISO Code</th>
                    <th>Is African?</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($countries as $index => $country)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $country->name }}</td>
                    <td>{{ $country->nationality }}</td>
                    <td>{{ $country->iso_3166_code }}</td>
                    <td>{{ $country->is_african_country ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('countries.edit', $country->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>

                        {{-- Delete button --}}
                        <form action="{{ route('countries.destroy', $country->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this country?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection