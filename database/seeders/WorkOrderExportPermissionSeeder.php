<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkOrderExportPermissionSeeder extends Seeder
{
    /**
     * Grant 'work-order-export' to every role that can already view a work order list.
     * Idempotent - safe to run more than once.
     */
    public function run(): void
    {
        $exportPermissionId = DB::table('permissions')->where('name', 'work-order-export')->value('id');

        if (!$exportPermissionId) {
            $this->command->error("Permission 'work-order-export' not found. Nothing to do.");
            return;
        }

        // Same permission set the WO listing page uses to decide who sees the list.
        $listPermissions = [
            'list-export-exw-wo',
            'view-current-user-export-exw-wo-list', 
            'list-export-cnf-wo',
            'view-current-user-export-cnf-wo-list',
            'list-export-local-sale-wo',
            'view-current-user-local-sale-wo-list',
            'list-lto-wo',
        ];

        $roleIds = DB::table('role_has_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->whereIn('permissions.name', $listPermissions)
            ->distinct()
            ->pluck('role_has_permissions.role_id');

        $granted = 0;

        foreach ($roleIds as $roleId) {
            $alreadyHas = DB::table('role_has_permissions')
                ->where('role_id', $roleId)
                ->where('permission_id', $exportPermissionId)
                ->exists();

            if ($alreadyHas) {
                continue;
            }

            DB::table('role_has_permissions')->insert([
                'permission_id' => $exportPermissionId,
                'role_id'       => $roleId,
            ]);

            $granted++;
        }

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info("Work order export granted to {$granted} additional role(s).");
    }
}
