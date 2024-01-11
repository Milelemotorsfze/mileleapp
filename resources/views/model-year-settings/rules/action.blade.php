
@can('model-year-calculation-rules-edit')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-rules-edit');
    @endphp
    @if ($hasPermission)
        <a data-placement="top" href="{{ route('model-year-calculation-rules.edit', $modelYearCalculationRule->id) }}"
           class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
    @endif
@endcan
@can('model-year-calculation-rules-delete')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-rules-delete');
    @endphp
    @if ($hasPermission)
        <a data-placement="top" href="#" data-url="{{ route('model-year-calculation-rules.destroy', $modelYearCalculationRule->id) }}"
           class="btn btn-danger delete-button btn-sm"><i class="fa fa-trash"></i></a>
    @endif
@endcan

