<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Modules;
use App\Models\Permission;

class HRMSPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::find(1);
        $modules = [
            ['28', 'HRM - Employee Hiring Request'],
            ['29', 'HRM - Employee Hiring Questionnaire'],
            ['30', 'HRM - Employee Hiring Job Description'],
            ['31', 'HRM - Interview Summary Details'],
            ['32', 'HRM - Personal Information Form'],
            ['33', 'HRM - Candidate Documents Request'],
            ['34', 'HRM - Candidate Job Offer Letter'],
            ['35', 'HRM - Joining Report'],
            ['36', 'HRM - Asset Allocation Request'],
            ['37', 'HRM - HandOver'],
            ['38', 'HRM - Passport Request'],
            ['39', 'HRM - Liability'],
            ['40', 'HRM - Leave'],
            ['41', 'HRM - Master Division'],
            ['42', 'HRM - Master Department'],
            ['43', 'HRM - Employee Birthday Gift PO'],
            ['44', 'HRM - Employee Ticket Allowance PO'],
            ['45', 'HRM - Employee Insurance'],
            ['46', 'HRM - Employee Salary Increment'],
            ['47', 'HRM - Overtime Application'],
            ['48', 'HRM - Separation Employee Handover'],
        ];
        foreach ($modules as $key => $value):
            $module[] = [
                'id'       => $value[0],
                'name' => $value[1]
            ];
        endforeach;
        DB::table('modules')->insert($module);
        $Permissions = [
            ['create-employee-hiring-request', 'Create Employee Hiring Request', 'web', 'To Create Employee Hiring Request', '2023-11-13 13:02:21', '2023-12-05 13:39:05', 28],
            ['edit-employee-hiring-request', 'Edit Employee Hiring Request', 'web', 'To Edit Employee Hiring Request', '2023-12-05 13:38:46', '2023-12-05 13:38:46', 28],
            ['view-all-pending-hiring-request-listing', 'View All Pending Hiring Request Listing', 'web', 'To View All Pending Hiring Request Listing', '2023-12-06 06:15:17', '2023-12-06 06:15:17', 28],
            ['view-all-approved-hiring-request-listing', 'View All Approved Hiring Request Listing', 'web', 'To View All Approved Hiring Request Listing', '2023-12-06 06:15:54', '2023-12-06 06:15:54', 28],
            ['view-all-closed-hiring-request-listing', 'View All Closed Hiring Request Listing', 'web', 'To View All Closed Hiring Request Listing', '2023-12-06 06:18:28', '2023-12-06 06:18:28', 28],
            ['view-all-on-hold-hiring-request-listing', 'View All On Hold Hiring Request Listing', 'web', 'To View All On Hold Hiring Request Listing', '2023-12-06 06:18:58', '2023-12-06 06:18:58', 28],
            ['view-all-cancelled-hiring-request-listing', 'View All Cancelled Hiring Request Listing', 'web', 'To View All Cancelled Hiring Request Listing', '2023-12-06 06:22:58', '2023-12-06 06:22:58', 28],
            ['view-all-rejected-hiring-request-listing', 'View All Rejected Hiring Request Listing', 'web', 'To View All Rejected Hiring Request Listing', '2023-12-06 06:23:57', '2023-12-06 06:23:57', 28],
            ['view-pending-hiring-request-listing-of-current-user', 'View  Pending Hiring Request Listing  Of Current User', 'web', 'To View  Pending Hiring Request Listing  Of Current User', '2023-12-06 06:30:01', '2023-12-06 06:30:01', 28],
            ['view-approved-hiring-request-listing-of-current-user', 'View Approved Hiring Request Listing Of Current User', 'web', 'To View Approved Hiring Request Listing Of Current User', '2023-12-06 06:30:22', '2023-12-06 06:30:22', 28],
            ['view-closed-hiring-request-listing-of-current-user', 'View Closed Hiring Request Listing Of Current User', 'web', 'To View Closed Hiring Request Listing Of Current User', '2023-12-06 06:32:09', '2023-12-06 06:32:09', 28],
            ['view-on-hold-hiring-request-listing-of-current-user', 'View On Hold Hiring Request Listing Of Current User', 'web', 'To View On Hold Hiring Request Listing Of Current User', '2023-12-06 06:32:29', '2023-12-06 06:32:29', 28],
            ['view-cancelled-hiring-request-listing-of-current-user', 'View Cancelled Hiring Request Listing Of Current User', 'web', 'To View Cancelled Hiring Request Listing Of Current User', '2023-12-06 06:32:54', '2023-12-06 06:32:54', 28],
            ['view-rejected-hiring-request-listing-of-current-user', 'View Rejected Hiring Request Listing  Of Current User', 'web', 'To View Rejected Hiring Request Listing  Of Current User', '2023-12-06 06:33:27', '2023-12-06 06:33:27', 28],
            ['view-deleted-hiring-request-listing-of-current-user', 'View Deleted Hiring Request Listing  Of Current User', 'web', 'To View Deleted Hiring Request Listing  Of Current User', '2023-12-06 07:21:39', '2023-12-06 07:21:39', 28],
            ['view-all-deleted-hiring-request-listing', 'View All Deleted Hiring Request Listing', 'web', 'To View All Deleted Hiring Request Listing', '2023-12-06 07:22:23', '2023-12-06 07:22:23', 28],
            ['view-all-hiring-request-details', 'View All Hiring Request Details', 'web', 'To View All Hiring Request Details', '2023-12-06 08:06:51', '2023-12-06 08:06:51', 28],
            ['view-hiring-request-details-of-current-user', 'View Hiring Request Details Of Current User', 'web', 'To View Hiring Request Details Of Current User', '2023-12-06 08:07:46', '2023-12-06 08:07:46', 28],
            ['view-all-hiring-request-history', 'View All Hiring Request History', 'web', 'To View All Hiring Request History', '2023-12-06 08:12:43', '2023-12-06 08:12:43', 28],
            ['view-all-hiring-request-approval-details', 'View All Hiring Request Approval Details', 'web', 'To View All Hiring Request Approval Details', '2023-12-06 08:14:59', '2023-12-06 08:14:59', 28],
            ['view-hiring-request-history-of-current-user', 'View Hiring Request History Of Current User', 'web', 'To View Hiring Request History Of Current User', '2023-12-06 08:54:06', '2023-12-06 08:54:06', 28],
            ['view-hiring-request-approval-details-of-current-user', 'View Hiring Request Approval Details Of Current User', 'web', 'To View Hiring Request Approval Details Of Current User', '2023-12-06 08:54:22', '2023-12-06 08:54:22', 28],
            ['all-hiring-request-delete-action', 'All Hiring Request Delete Action', 'web', 'To Delete All Hiring Request', '2023-12-06 11:04:12', '2023-12-06 11:04:12', 28],
            ['hiring-request-of-current-user-delete-action', 'Hiring Request Of Current User Delete Action', 'web', 'To Delete Hiring Request Of Current User', '2023-12-06 11:04:41', '2023-12-06 11:04:41', 28],
            ['hiring-request-close-action', 'Hiring Request Close Action', 'web', 'To Close Hiring Request', '2023-12-06 11:05:07', '2023-12-06 11:05:07', 28],
            ['hiring-request-on-hold-action', 'Hiring Request On Hold Action', 'web', 'To On Hold Hiring Request', '2023-12-06 11:05:28', '2023-12-06 11:05:28', 28],
            ['hiring-request-cancel-action', 'Hiring Request Cancel Action', 'web', 'To Cancel Hiring Request', '2023-12-06 11:09:25', '2023-12-06 11:09:25', 28],
            ['create-questionnaire', 'Create Questionnaire', 'web', 'To create employee hiring questionnaire', '2023-12-06 11:29:29', '2023-12-06 11:29:29', 29],
            ['edit-questionnaire', 'Edit Questionnaire', 'web', 'To edit employee hiring questionnaire', '2023-12-06 11:30:04', '2023-12-06 11:30:04', 29],
            ['view-questionnaire-details', 'View Questionnaire Details', 'web', 'To view questionnaire details', '2023-12-06 11:31:17', '2023-12-06 11:31:17', 29],
            ['create-job-description', 'Create Job Description', 'web', 'To Create Employee Hiring Job Description', '2023-12-06 12:34:18', '2023-12-06 12:34:18', 30],
            ['edit-job-description', 'Edit Job Description', 'web', 'To Edit Employee Hiring job Description', '2023-12-06 12:34:41', '2023-12-06 12:34:41', 30],
            ['view-pending-job-description-list', 'View Pending  Job Description List', 'web', 'To View Pending  Job Description List', '2023-12-06 12:35:02', '2023-12-06 12:35:02', 30],
            ['view-approved-job-description-list', 'View Approved  Job Description List', 'web', 'To View Approved  Job Description List', '2023-12-06 12:35:26', '2023-12-06 12:35:26', 30],
            ['view-rejected-job-description-list', 'View Rejected  Job Description List', 'web', 'To View Rejected  Job Description List', '2023-12-06 12:36:00', '2023-12-06 12:36:00', 30],
            ['view-job-description-details', 'View Job Description Details', 'web', 'To View Job Description Details', '2023-12-06 12:54:29', '2023-12-06 12:54:29', 30],
            ['view-job-description-approvals-details', 'View Job Description Approvals Details', 'web', 'To View Job Description Approvals Details', '2023-12-06 12:55:06', '2023-12-06 12:55:06', 30],
            ['view-addon-supplier-info', 'View Addon Supplier Info', 'web', 'to view addon supplier info', '2023-12-14 08:56:45', '2023-12-14 08:56:45', 17],
            ['view-interview-summary-report-listing', 'View Interview Summary Report Listing', 'web', 'To View Interview Summary Report Listing', '2023-12-18 05:20:30', '2023-12-18 05:20:30', 31],
            ['create-interview-summary-report', 'Create Interview Summary Report', 'web', 'To Create Interview Summary Report', '2023-12-18 05:20:59', '2023-12-18 05:20:59', 31],
            ['edit-interview-summary-report', 'Edit Interview Summary Report', 'web', 'To Edit Interview Summary Report', '2023-12-18 05:21:24', '2023-12-18 05:21:24', 31],
            ['view-interview-summary-report-details', 'View Interview Summary Report Details', 'web', 'To View Interview Summary Report Details', '2023-12-18 05:21:53', '2023-12-18 05:21:53', 31],
            ['send-personal-info-form-action', 'Send Personal Info Form Action', 'web', 'To Send Personal Information Form To Candidate', '2023-12-18 06:22:39', '2023-12-18 06:22:39', 32],
            ['view-personal-information-form-listing', 'View Personal Information Form Listing', 'web', 'To View Personal Information Form Listing', '2023-12-18 06:23:12', '2023-12-18 06:23:12', 32],
            ['view-personal-information-details', 'View Personal Information Details', 'web', 'To View Personal Information Details', '2023-12-18 06:23:32', '2023-12-18 06:23:32', 32],
            ['verify-candidate-personal-information', 'Verify Candidate Personal Information', 'web', 'To Verify Candidate Personal Information', '2023-12-21 12:23:58', '2023-12-22 10:51:59', 32],
            ['send-candidate-documents-request-form', 'Send Candidate Documents Request Form', 'web', 'To Send Candidate Documents Request Form', '2023-12-22 10:58:50', '2023-12-22 10:58:50', 33],
            ['view-candidate-documents', 'View Candidate Documents', 'web', 'To View Candidate Documents', '2023-12-22 11:01:02', '2023-12-22 11:01:02', 33],
            ['verify-candidates-documents', 'Verify Candidates Documents', 'web', 'To Verify Candidates Documents', '2023-12-22 11:02:07', '2023-12-22 11:02:07', 33],
            ['send-offer-letter', 'Send Offer Letter', 'web', 'To Send Job Offer Letter To Candidate', '2023-12-27 12:45:13', '2023-12-27 12:45:13', 34],
            ['verify-offer-letter-signature', 'Verify Offer Letter Signature', 'web', 'To Verify Offer Letter Signature', '2023-12-29 11:03:04', '2023-12-29 11:03:04', 34],
            ['view-joining-report-listing', 'View Joining Report Listing', 'web', 'To View All Joining Report Listing', '2024-01-02 11:10:34', '2024-01-13 08:15:00', 35],
            ['create-joining-report', 'Create Joining Report', 'web', 'To Create Joining Report', '2024-01-02 11:13:44', '2024-01-02 11:13:44', 35],
            ['edit-joining-report', 'Edit Joining Report', 'web', 'To Edit Joining Report', '2024-01-02 11:14:09', '2024-01-02 11:14:09', 35],
            ['view-joining-report-details', 'View Joining Report Details', 'web', 'To View Joining Report Details', '2024-01-02 11:14:56', '2024-01-02 11:14:56', 35],
            ['view-asset-allocation-request-listing', 'View Asset Allocation Request Listing', 'web', 'To View Asset Allocation Request Listing', '2024-01-02 11:16:10', '2024-01-02 11:16:10', 36],
            ['create-asset-allocation-request', 'Create Asset Allocation Request', 'web', 'To Create Asset Allocation Request', '2024-01-02 11:16:28', '2024-01-02 11:16:28', 36],
            ['edit-asset-allocation-request', 'Edit Asset Allocation Request', 'web', 'To Edit Asset Allocation Request', '2024-01-02 11:16:44', '2024-01-02 11:16:44', 36],
            ['view-asset-allocation-details', 'View Asset Allocation Details', 'web', 'To View Asset Allocation Details', '2024-01-02 11:17:00', '2024-01-02 11:17:00', 36],
            ['create-passport-request', 'Create Passport Request', 'web', 'To Create Passport Request', '2024-01-05 12:03:22', '2024-01-05 12:03:22', 38],
            ['edit-passport-request', 'Edit Passport Request', 'web', 'To Edit Passport Request', '2024-01-05 12:04:20', '2024-01-05 12:04:20', 38],
            ['view-passport-request-details', 'View Passport Request Details', 'web', 'To View Passport Request Details', '2024-01-05 12:04:37', '2024-01-05 12:04:37', 38],
            ['view-passport-request-list', 'View Passport Request List', 'web', 'To View All Passport Request List', '2024-01-05 12:04:53', '2024-01-13 08:12:55', 38],
            ['create-liability', 'Create Liability', 'web', 'To Create Liability', '2024-01-05 12:05:08', '2024-01-05 12:05:08', 39],
            ['edit-liability', 'Edit Liability', 'web', 'To Edit Liability', '2024-01-05 12:05:24', '2024-01-05 12:05:24', 39],
            ['view-liability-details', 'View Liability Details', 'web', 'To View Liability Details', '2024-01-05 12:05:41', '2024-01-05 12:05:41', 39],
            ['view-liability-list', 'View Liability List', 'web', 'To View All Liability List', '2024-01-05 12:05:56', '2024-01-13 08:12:00', 39],
            ['create-leave', 'Create Leave', 'web', 'To Create Leave', '2024-01-05 12:06:14', '2024-01-05 12:06:14', 40],
            ['edit-leave', 'Edit Leave', 'web', 'To Edit Leave', '2024-01-05 12:06:29', '2024-01-05 12:06:29', 40],
            ['view-leave-details', 'View Leave Details', 'web', 'To View Leave Details', '2024-01-05 12:06:46', '2024-01-05 12:06:46', 40],
            ['view-leave-list', 'View Leave List', 'web', 'To View All Leave List', '2024-01-05 12:07:04', '2024-01-13 07:42:44', 40],
            ['view-current-user-leave-list', 'View Current User Leave List', 'web', 'To View Leave List Of Current User', '2024-01-13 07:48:23', '2024-01-13 07:48:23', 40],
            ['current-user-view-joining-report-listing', 'Current User View Joining Report Listing', 'web', 'To View All Joining Report Listing Of Current User', '2024-01-13 08:26:17', '2024-01-13 08:26:17', 35],
            ['current-user-create-joining-report', 'Current User Create Joining Report', 'web', 'To Create Joining Report Of Current User', '2024-01-13 08:26:45', '2024-01-13 08:26:45', 35],
            ['current-user-edit-joining-report', 'Current User Edit Joining Report', 'web', 'To Edit Joining Report Of Current User', '2024-01-13 08:27:11', '2024-01-13 08:27:11', 35],
            ['current-user-view-joining-report-details', 'Current User View Joining Report Details', 'web', 'To View Joining Report Details Of Current User', '2024-01-13 08:27:35', '2024-01-13 08:27:35', 35],
            ['current-user-create-passport-request', 'Current User Create Passport Request', 'web', 'To Create Passport Request Of Current User', '2024-01-13 08:28:05', '2024-01-13 08:28:05', 38],
            ['current-user-edit-passport-request', 'Current User Edit Passport Request', 'web', 'To Edit Passport Request Of Current User', '2024-01-13 08:28:27', '2024-01-13 08:28:27', 38],
            ['current-user-view-passport-request-details', 'Current User View Passport Request Details', 'web', 'To View Passport Request Details Of Current User', '2024-01-13 08:28:48', '2024-01-13 08:28:48', 38],
            ['current-user-view-passport-request-list', 'Current User View Passport Request List', 'web', 'To View All Passport Request List Of Current User', '2024-01-13 08:29:12', '2024-01-13 08:29:12', 38],
            ['current-user-create-liability', 'Current User Create Liability', 'web', 'To Create Liability Of Current User', '2024-01-13 08:30:19', '2024-01-13 08:30:19', 39],
            ['current-user-edit-liability', 'Current User Edit Liability', 'web', 'To Edit Liability Of Current User', '2024-01-13 08:31:32', '2024-01-13 08:31:32', 39],
            ['current-user-view-liability-details', 'Current User View Liability Details', 'web', 'To View Liability Details Of Current User', '2024-01-13 08:31:52', '2024-01-13 08:31:52', 39],
            ['current-user-view-liability-list', 'Current User View Liability List', 'web', 'To View All Liability List Of Current User', '2024-01-13 08:32:12', '2024-01-13 08:32:12', 39],
            ['current-user-create-leave', 'Current User Create Leave', 'web', 'To Create Leave Of Current User', '2024-01-13 08:32:51', '2024-01-13 08:32:51', 40],
            ['current-user-edit-leave', 'Current User Edit Leave', 'web', 'To Edit Leave Of Current User', '2024-01-13 08:33:11', '2024-01-13 08:33:11', 40],
            ['current-user-view-leave-details', 'Current User View Leave Details', 'web', 'To View Leave Details Of Current User', '2024-01-13 08:33:31', '2024-01-13 08:33:31', 40],
            ['view-division-listing', 'View Division Listing', 'web', 'To View Master Division Listing', '2024-01-16 07:04:11', '2024-01-16 07:04:11', 41],
            ['edit-division', 'Edit Division', 'web', 'To Edit Master Division', '2024-01-16 07:04:39', '2024-01-16 07:04:39', 41],
            ['view-department-listing', 'View Department Listing', 'web', 'To View Department Listing', '2024-01-16 07:05:16', '2024-01-16 07:05:16', 42],
            ['create-department', 'Create Department', 'web', 'To Create Department', '2024-01-16 07:05:38', '2024-01-16 07:05:38', 42],
            ['edit-department', 'Edit Department', 'web', 'To Edit Department', '2024-01-16 07:05:59', '2024-01-16 07:05:59', 42],
            ['view-department-details', 'View Department Details', 'web', 'To View Department Details', '2024-01-16 07:06:20', '2024-01-16 07:06:20', 42],
            ['view-division-details', 'View Division Details', 'web', 'To View Master Division Details', '2024-01-16 07:41:53', '2024-01-16 07:41:53', 41],
            ['view-current-user-division', 'View Current User Division', 'web', 'To View Master Division Listing Of Current User', '2024-01-16 08:01:18', '2024-01-16 08:01:18', 41],
            ['edit-current-user-division', 'Edit Current User Division', 'web', 'To Edit Master Division Of Current User', '2024-01-16 08:01:43', '2024-01-16 08:01:43', 41],
            ['view-current-user-department-lising', 'View Current User Department Lising', 'web', 'To View Current User Department Listing', '2024-01-16 08:02:15', '2024-01-16 08:02:15', 42],
            ['edit-current-user-department', 'Edit Current User Department', 'web', 'To Edit Current User Department', '2024-01-16 08:02:38', '2024-01-16 08:02:38', 42],
            ['create-birthday-po', 'Create Birthday PO', 'web', 'To Create Employee Birthday Gift PO', '2024-01-17 07:18:13', '2024-01-17 07:18:13', 43],
            ['view-birthday-po-list', 'View Birthday PO List', 'web', 'To View Birthday Gift PO Listing', '2024-01-17 07:18:38', '2024-01-17 07:18:38', 43],
            ['edit-birthday-po', 'Edit Birthday PO', 'web', 'To Edit Birthday Gift PO', '2024-01-17 07:19:03', '2024-01-17 07:19:03', 43],
            ['view-birthday-po-details', 'View Birthday PO Details', 'web', 'To View Birthday Gift PO Details', '2024-01-17 07:19:25', '2024-01-17 07:19:25', 43],
            ['create-ticket-po', 'Create Ticket PO', 'web', 'To Create Employee Ticket Allowance PO', '2024-01-17 07:19:54', '2024-01-17 07:19:54', 44],
            ['edit-ticket-po', 'Edit Ticket PO', 'web', 'To Edit Employee Ticket Allowance PO', '2024-01-17 07:20:16', '2024-01-17 07:20:16', 44],
            ['view-ticket-details', 'View Ticket Details', 'web', 'To View Employee Ticket Allowance PO Details', '2024-01-17 07:20:38', '2024-01-17 07:20:38', 44],
            ['view-ticket-listing', 'View Ticket Listing', 'web', 'To View Employee Ticket Allowance PO Listing', '2024-01-17 07:21:00', '2024-01-17 07:21:00', 44],
            ['view-ticket-details-of-current-user', 'View Ticket Details Of Current User', 'web', 'To View Employee Ticket Allowance PO Details Of Current User', '2024-01-17 07:21:29', '2024-01-17 07:21:29', 44],
            ['view-ticket-listing-of-current-user', 'View Ticket Listing Of Current User', 'web', 'To View Employee Ticket Allowance PO Listing Of Current User', '2024-01-17 07:21:58', '2024-01-17 07:21:58', 44],
            ['create-insurance', 'Create Insurance', 'web', 'To Create Employee Insurance', '2024-01-18 08:18:08', '2024-01-18 08:18:08', 45],
            ['edit-insurance', 'Edit Insurance', 'web', 'To Edit Employee Insurance', '2024-01-18 08:18:33', '2024-01-18 08:18:33', 45],
            ['view-all-list-insurance', 'View All List Insurance', 'web', 'To View Insurance List Of All Employees', '2024-01-18 08:19:03', '2024-01-18 08:19:03', 45],
            ['view-current-user-list-insurance', 'View Current User List Insurance', 'web', 'To View Insurance List Of Current User', '2024-01-18 08:19:27', '2024-01-18 08:19:27', 45],
            ['view-all-insurance-details', 'View All Insurance Details', 'web', 'To View Insurance Details Of All Users', '2024-01-18 08:19:58', '2024-01-18 08:19:58', 45],
            ['view-current-user-insurance-details', 'View Current User Insurance Details', 'web', 'To View Insurance Details Of Current User', '2024-01-18 08:20:23', '2024-01-18 08:20:23', 45],
            ['create-increment', 'Create Increment', 'web', 'To Create Employee Salary Increment Details', '2024-01-18 13:05:32', '2024-01-18 13:05:32', 46],
            ['edit-increment', 'Edit Increment', 'web', 'To Edit Employee Salary Increment Details', '2024-01-18 13:06:07', '2024-01-18 13:06:07', 46],
            ['list-all-increment', 'List All Increment', 'web', 'To View List of Increment Of All Users', '2024-01-18 13:06:37', '2024-01-18 13:06:37', 46],
            ['list-current-user-increment', 'List Current User Increment', 'web', 'To View List Of Increment Of Current User', '2024-01-18 13:07:04', '2024-01-18 13:07:04', 46],
            ['all-increment-details', 'All Increment Details', 'web', 'To View Increment Details Of All User', '2024-01-18 13:07:26', '2024-01-18 13:07:26', 46],
            ['current-user-increment-details', 'Current User Increment Details', 'web', 'To View Increment Details Of Current User', '2024-01-18 13:07:52', '2024-01-18 13:07:52', 46],
            ['create-overtime', 'Create Overtime', 'web', 'To Create Overtime Application', '2024-01-22 08:11:14', '2024-01-22 08:11:14', 47],
            ['edit-overtime', 'Edit Overtime', 'web', 'To Edit Overtime Application', '2024-01-22 08:11:35', '2024-01-22 08:11:35', 47],
            ['list-all-overtime', 'List All Overtime', 'web', 'To View List Of All Overtimes', '2024-01-22 08:11:57', '2024-01-22 08:11:57', 47],
            ['list-current-user-overtime', 'List Current User Overtime', 'web', 'To View List Of  Overtimes Of Current User', '2024-01-22 08:12:17', '2024-01-22 08:12:17', 47],
            ['all-overtime-details', 'All Overtime Details', 'web', 'To View Overtime Details Of All Users', '2024-01-22 08:12:37', '2024-01-22 08:12:37', 47],
            ['current-user-overtime-details', 'Current User Overtime Details', 'web', 'To View Overtime Details Of Current user', '2024-01-22 08:12:55', '2024-01-22 08:12:55', 47],
            ['create-separation-employee-handover', 'Create Separation Employee Handover', 'web', 'To Create Separation Employee Handover', '2024-02-06 13:06:49', '2024-02-06 13:06:49', 48],
            ['edit-separation-employee-handover', 'Edit Separation Employee Handover', 'web', 'To Edit Separation Employee Handover', '2024-02-06 13:07:08', '2024-02-06 13:07:08', 48],
            ['list-all-separation-employee-handover', 'List All Separation Employee Handover', 'web', 'To View All Separation Employee Handover', '2024-02-06 13:07:27', '2024-02-06 13:07:27', 48],
            ['list-current-user-separation-handover', 'List Current User Separation Handover', 'web', 'To View List Of Current User Separation Handover', '2024-02-06 13:07:44', '2024-02-06 13:07:44', 48],
            ['all-separation-employee-handover-details', 'All Separation Employee Handover Details', 'web', 'To View Details Of All Separation Employee Handover', '2024-02-06 13:08:00', '2024-02-06 13:08:00', 48],
            ['current-user-separation-handover-details', 'Current User Separation Handover Details', 'web', 'To View Details Of Separation Handover Of Current User', '2024-02-06 13:08:17', '2024-02-06 13:08:17', 48],

        ];
        // create entry to permission table and assign permission to admin
        foreach ($Permissions as $key => $value){

            $permission = new Permission();

            $permission->module_id = $value[6];
            $permission->slug_name = $value[1];
            $permission->name = $value[0];
            $permission->guard_name =  'web';
            $permission->description = $value[3];
            $permission->save();

            $data = [
                'permission_id' => $permission->id,
                'role_id' => $adminRole->id
            ];

            DB::table('role_has_permissions')->insert($data);
        }
    }
}
