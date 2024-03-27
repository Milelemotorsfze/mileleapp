@can('loi-restricted-country-edit')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-edit');
    @endphp
    @if ($hasPermission)
        <a data-placement="top" href="{{ route('loi-country-criterias.edit', $loiCountryCriteria->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
    @endif
@endcan
@can('loi-restricted-country-active-inactive')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-active-inactive');
    @endphp
    @if ($hasPermission)
        @if($loiCountryCriteria->status == \App\Models\LoiCountryCriteria::STATUS_ACTIVE)
            <button data-url="{{ route('loi-country-criterias.active-inactive') }}" title="Make Inactive"  data-id="{{ $loiCountryCriteria->id }}"
                    data-status="{{ \App\Models\LoiCountryCriteria::STATUS_INACTIVE }}"  class="btn btn-success btn-sm btn-status-change"><i class="fa fa-check"></i></button>
        @else
            <button data-url="{{ route('loi-country-criterias.active-inactive') }}" title="Make Active"  data-id="{{ $loiCountryCriteria->id }}"
                    data-status="{{ \App\Models\LoiCountryCriteria::STATUS_ACTIVE }}" class="btn btn-secondary btn-sm btn-status-change"><i class="fa fa-times"></i></button>
        @endif
    @endif
@endcan
@can('loi-restricted-country-delete')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-delete');
    @endphp
    @if ($hasPermission)
        <button data-url="{{ route('loi-country-criterias.destroy', $loiCountryCriteria->id ) }}" class="btn btn-danger btn-sm btn-delete">
            <i class="fa fa-trash"></i></button>
    @endif
@endcan
