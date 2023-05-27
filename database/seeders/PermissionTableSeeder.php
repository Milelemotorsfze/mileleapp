<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('modules')->delete();
        DB::table('permissions')->delete();

        $modules = [
            ['1','User'],
            ['2','Role'],
            ['3','Sales'],
            ['4','Procurement'],
            ['5','Marketing'],
            ['6','HR'],
            ['7','Demand Planning'],
            ];
        foreach ($modules as $key => $value):
        $module[] = [
            'id'       => $value[0],
            'name' => $value[1]
        ];
        endforeach ;
        DB::table('modules')->insert($module);

        $Permissions = [
            ['1','List Active User','user-list-active','To view the list of active users.'],
            ['1','List Inactive User','user-list-inactive','To view the list of inactive users.'],
            ['1','List Deleted User','user-list-deleted','To view the list of deleted users.'],
            ['1','Make User Inactive','user-make-inactive','To make an active user to inactive for preventing login and other activities.'],
            ['1','Make User Active','user-make-active','To make an inactive user to active for allow login and other activities.'],
            ['1','Restore User','user-restore','To restore a deleted user.'],
            ['1','View User Details','user-view','To view the user details.'],
            ['1','Create User','user-create','To create a new user.'],
            ['1','Edit User','user-edit','To edit a user.'],
            ['1','Delete User','user-delete','To delete a user.'],

            ['2','List Role','role-list','To view the list of all roles.'],
            ['2','View Role Details', 'role-view','To view the role details with permissions.'],
            ['2','Create Role','role-create','To create a new role with permissions.'],
            ['2','Edit Role','role-edit','To edit a role with permissions.'],
            ['2','Delete Role','role-delete','To delete a role.'],

            ['3','View Sales','sales-view', 'To View the sales department'],
            ['3','List Daily Leads','daily-leads-list', 'To View the List of the Daily Leads'],
            ['3','View  Quotation Details', 'daily-leads-view', 'To view the Quotation Details and Listing'],
            ['3','Create Quotation','daily-leads-create', 'To create New Quotation'],
            ['3','Edit Quotation','daily-leads-edit', 'To Edit the Quotation'],

            ['4','View Procurement','Procurement-view', 'To View the the Procurement Department'],
            ['4','List Procurement','Procurement-list', 'To View the List of the Procurement'],
            ['4','View  Procurement Details', 'Procurement-view-detail', 'To view the details of the Procurement'],
            ['4','Create Procurement (Only GCC)','Procurement-create-gcc', 'To Create the New Procurement GCC Only'],
            ['4','Create Procurement (Expect GCC)','Procurement-create-other', 'To Create the New Procurement For all Countries expect GCC'],
            ['4','Edit Procurement','Procurement-edit', 'To Edit his Own Procurement'],
            ['4','Delete Procurement','Procurement-delete', 'To Delete his Own Procurement'],

            ['5','View Calls','Calls-view', 'To View the Calls'],
            ['5','Add,Edit, Delete Calls','Calls-modified', 'Add, Edit, Delete Calls'],
            ['5','View Variants','variants-view', 'To View the Vairants'],
            ['5','List Variants','variants-list', 'To View the List of the Variants'],
            ['5','List With Missing Variants','variants-list-missing', 'To View the List of the Missing pictures and other data of Variants'],
            ['5','View Variants Details', 'variants-details-view', 'To view the details of the list of the Variants'],
            ['5','Update Variants Pictures','variants-update-pictures', 'To Update the Pictures of the Variants'],
            ['5','Update Variants Reals','variants-update-reals', 'To Update the reals of the Variants'],

            ['6','View HR','HR-view', 'To View the HR Module'],
            ['6','Edit HR','HR-edit', 'To Edit the HR Module'],
            ['6','Job Requirement','Job-Requirement', 'To Submit a Job Request From Department Head'],
            ['6','Job Requests Portal','Job-request-portal', 'To Handle The Job Requests Portal'],
            ['6','Employee Document Handling','document-handling', 'To Handle The Employee Documents'],

            ['7','List Demand','demand-list','To view the list of all demands.'],
            ['7','View Demand ', 'demand-view','To view the demand details with permissions.'],
            ['7','Create Demand','demand-create','To create new demand with permissions.'],
            ['7','Edit Demand','demand-edit','To edit demand with permissions.'],
            ['7','Delete Demand','demand-delete','To delete demand.'],

            ['7','Create LOI','LOI-create','To create new LOI with permissions.'],
            ['7','List LOI','LOI-list','To view the list of all LOI.'],
            ['7','View LOI ', 'LOI-view','To view the LOI details with permissions.'],
            ['7','Edit LOI','LOI-edit','To edit LOI with permissions.'],
            ['7','Delete LOI','LOI-delete','To delete LOI.'],

            ['7','Supplier Inventory List','supplier-inventory-list','To list supplier Inventory with permissions.'],
            ['7','Supplier Inventory Edit','supplier-inventory-edit','To add or update supplier Inventory with permissions.'],
            ['7','Approve LOI','LOI-approve','To Approve LOI with permissions.'],

            ];
        foreach ($Permissions as $key => $value):
        $permission[] = [
            'module_id'       => $value[0],
            'slug_name' => $value[1],
            'name' => $value[2],
            'guard_name' => 'web',
            'description' => $value[3]
        ];
        endforeach ;
        DB::table('permissions')->insert($permission);
    }
}

