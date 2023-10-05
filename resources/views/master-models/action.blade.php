@can('edit-master-models')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-master-models');
    @endphp
    @if ($hasPermission)
        <a data-placement="top" href="{{ route('master-models.edit', $masterModel->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
    @endif
@endcan
