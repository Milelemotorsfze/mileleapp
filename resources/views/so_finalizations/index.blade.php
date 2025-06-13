@extends('layouts.table')

@section('content')
<div class="card-header">
    <h2 class="card-title"> Duplicate SO Numbers</h2>
    @if (Session::has('error'))
    <div class="alert alert-danger">
        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
        {{ Session::get('error') }}
    </div>
    @endif

    @if (Session::has('success'))
    <div class="alert alert-success" id="success-alert">
        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
        {{ Session::get('success') }}
    </div>
    @endif
</div>
<div class="card-body">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>SO Number</th>
                <th>Number of Duplicates</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($duplicates as $index => $dup)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $dup->display_so_number }}</td>
                <td>{{ $dup->count }}</td>
                <td>
                    <a href="{{ route('so_finalizations.edit', ['so_number' => $dup->display_so_number ?? '']) }}" class="btn btn-sm btn-primary">
                        Resolve Duplication
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@endsection