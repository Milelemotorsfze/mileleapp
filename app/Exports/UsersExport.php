<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Modules;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\Permission\Models\Role;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $users;

    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    public function collection(): Collection
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Designation',
            'Department',
            'Access Level',
            'Status',
        ];
    }

    /**
     * @param \App\Models\User $user
     */
    public function map($user): array
    {
        $designation = optional(optional($user->empProfile)->designation)->name ?? '-';
        $department = optional(optional($user->empProfile)->department)->name ?? '-';
        $accessLevel = '-';

        if ($user->selected_role) {
            // Get unique modules for the role's permissions using a join query
            $modules = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('modules', 'permissions.module_id', '=', 'modules.id')
                ->where('role_has_permissions.role_id', $user->selected_role)
                ->whereNotNull('permissions.module_id')
                ->select('modules.name')
                ->distinct()
                ->pluck('name')
                ->values();
            
            $accessLevel = $modules->isNotEmpty() ? $modules->implode(', ') : '-';
        }

        return [
            $user->name,
            $designation,
            $department,
            $accessLevel,
            $user->status,
        ];
    }
}

