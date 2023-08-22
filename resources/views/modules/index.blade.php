@extends('layouts.table')
@section('content')
    {{--    @php--}}
    {{--        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-brand-list');--}}
    {{--    @endphp--}}
    {{--    @if ($hasPermission)--}}
    <div class="card-header">
        <h4 class="card-title">
            Modules
        </h4>
        {{--        @php--}}
        {{--            $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-brand-create');--}}
        {{--        @endphp--}}
        {{--        @if ($hasPermission)--}}
        <a  class="btn btn-sm btn-info float-end" href="{{ route('modules.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
        {{--        @endif--}}
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
            <table id="module-table" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>S.No:</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}

                    @foreach ($modules as $key => $module)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $module->name ?? ''}}</td>
                            <td>{{\Carbon\Carbon::parse($module->created_at)->format('d-m-y') }}</td>
                            <td>
                                {{--                            @php--}}
                                {{--                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-brand-edit');--}}
                                {{--                            @endphp--}}
                                {{--                            @if ($hasPermission)--}}
                                <a data-placement="top" href="{{ route('modules.edit', $module->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>
                                </a>
                                {{--                            @endif--}}
                            </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{--@endif--}}
@endsection



