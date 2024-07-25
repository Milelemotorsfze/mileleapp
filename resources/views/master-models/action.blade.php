@can('edit-master-models')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-master-models');
    @endphp
    @if ($hasPermission)
        <a  href="{{ route('master-models.edit', $masterModel->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
    @endif
@endcan
@can('delete-master-model')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('delete-master-model');
    @endphp
    @if ($hasPermission)
        @if($masterModel->is_deletable == true)
            <button data-url="{{ route('master-models.destroy', $masterModel->id) }}" data-id="{{ $masterModel->id }}"
                class="btn btn-danger btn-sm btn-delete mt-1"><i class="fa fa-trash"></i></button>
        @endif
    @endif
@endcan

