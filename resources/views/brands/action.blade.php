@can('master-brand-edit')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-brand-edit');
    @endphp
    @if ($hasPermission)
        <a data-placement="top" href="{{ route('brands.edit', $brand->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
    @endif
@endcan
