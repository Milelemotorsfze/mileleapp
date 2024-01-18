<style>
    .approval-count {
        color:white!important;
        background-color:#fd625e!important;
        border-radius:50%!important;
        padding-right:3px!important;
        padding-left:3px!important;
    }
    @media only screen and (max-device-width: 280px) {
        .responsiveButton {
            position: absolute;
            left: 50px;
            z-index: 500;
            top: 10px;
        }
    }

    @media only screen and (max-device-width: 1200px) {
        .responsiveButton {
            position: absolute;
            right: 50px;
            z-index: 500;
            top: 10px;
        }
    }

    @media only screen and (min-device-width: 1200px) {
        .responsiveButton {
            position: absolute;
            right: 160px;
            z-index: 500;
            top: 10px;
        }

        .container,
        .container-lg,
        .container-md,
        .container-sm,
        .container-xl,
        .container-xxl {
            max-width: 3000px;
        }

    }
</style>

<div class="container">
    <div class="row topnav">
        <!-- <div class="topnav" style="overflow: unset;"> -->

        <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 navbar-menus-main-div">

            <button type="button" class="btn btn-sm px-2 font-size-16 d-lg-none header-item waves-effect waves-light text-dark" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <div class="d-none d-lg-block">
                <div class="dropdown d-inline-block align-items-center" style="position: absolute; left: 13px; top:7px; z-index: 500;">
                    <img src="{{ asset('logo.png') }}" width="20" height="40" alt="Logo" class="mx-auto">
                </div>
            </div>



            <!-- <div class="container-fluid"> -->
            <div class="container navbar-main-container">
                <div class="row">

                    <nav class="navbar navbar-light navbar-expand-lg navbar-main-div">

                        <div class="collapse navbar-collapse menu-container-div " id="topnav-menu-content">
                            <ul class="navbar-nav nav nav-pills" id="menu-items">
                                <li class="nav-item dropdown dashboard-menu-div">
                                    <a class="nav-link dropdown-toggle arrow-none ml-3" href="/" id="topnav-more" role="button">
                                        <i data-feather="home"></i>
                                        <span data-key="t-extra-pages">Dashboard</span>
                                    </a>
                                </li>
                                <!-- HRM -->
                                @canany(['create-employee-hiring-request','edit-employee-hiring-request','view-all-pending-hiring-request-listing',
                                            'view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing','view-all-on-hold-hiring-request-listing',
                                            'view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing','view-pending-hiring-request-listing-of-current-user',
                                            'view-approved-hiring-request-listing-of-current-user','view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
                                            'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
                                            'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-details-of-current-user'
                                            ,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
                                            ,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user'
                                            ,'hiring-request-cancel-action','hiring-request-of-current-user-delete-action','hiring-request-close-action','hiring-request-on-hold-action','hiring-request-cancel-action','create-job-description'
                                            ,'edit-job-description','view-pending-job-description-list','view-approved-job-description-list','view-rejected-job-description-list','view-job-description-details','view-job-description-approvals-details',
                                            'view-interview-summary-report-listing','create-interview-summary-report'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-employee-hiring-request','edit-employee-hiring-request','view-all-pending-hiring-request-listing',
                                            'view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing','view-all-on-hold-hiring-request-listing',
                                            'view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing','view-pending-hiring-request-listing-of-current-user',
                                            'view-approved-hiring-request-listing-of-current-user','view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
                                            'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
                                            'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-details-of-current-user'
                                            ,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
                                            ,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user'
                                            ,'hiring-request-cancel-action','hiring-request-of-current-user-delete-action','hiring-request-close-action','hiring-request-on-hold-action',
                                            'hiring-request-cancel-action','create-job-description'
                                            ,'edit-job-description','view-pending-job-description-list','view-approved-job-description-list','view-rejected-job-description-list','view-job-description-details','view-job-description-approvals-details',
                                            'view-interview-summary-report-listing','create-interview-summary-report']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="file-text"></i>
                                        <span data-key="t-extra-pages">
                                            @if(auth()->user()->selected_role == 20 OR auth()->user()->selected_role == 19 OR auth()->user()->selected_role == 1)
                                            HR
                                            @elseif(auth()->user()->selected_role == 30)
                                            Employee
                                            @endif
                                        </span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">

                                        <!-- <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('employee.index') }}"  id="topnav-utility" role="button">
                                            <span data-key="t-utility">Employee Relation</span>
                                        </a> -->
                                        <div class="dropdown">
                                            @canany(['view-division-listing','view-department-listing','view-current-user-department-lising','view-current-user-division'])
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-division-listing','view-current-user-division','view-department-listing','view-current-user-department-lising']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Masters</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @canany(['view-division-listing','view-current-user-division'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-division-listing','view-current-user-division']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('division.index') }}" class="dropdown-item" data-key="t-login">Divisions</a>
                                                @endif
                                                @endcanany    

                                                @canany(['view-department-listing','view-current-user-department-lising'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-department-listing','view-current-user-department-lising']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('department.index') }}" class="dropdown-item" data-key="t-login">Department</a>
                                                @endif
                                                @endcanany                                                                                        
                                            </div>
                                            @endif
                                            @endcanany
                                        </div>
                                        <div class="dropdown">
                                            @canany(['create-employee-hiring-request','edit-employee-hiring-request','view-all-pending-hiring-request-listing',
                                            'view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing','view-all-on-hold-hiring-request-listing',
                                            'view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing','view-pending-hiring-request-listing-of-current-user',
                                            'view-approved-hiring-request-listing-of-current-user','view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
                                            'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
                                            'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-details-of-current-user'
                                            ,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
                                            ,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user'
                                            ,'hiring-request-cancel-action','hiring-request-of-current-user-delete-action','hiring-request-close-action','hiring-request-on-hold-action','hiring-request-cancel-action'
                                            ,'create-questionnaire','edit-questionnaire','view-questionnaire-details','create-job-description','edit-job-description','view-pending-job-description-list','view-approved-job-description-list','view-rejected-job-description-list','view-job-description-details','view-job-description-approvals-details'])
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-employee-hiring-request','edit-employee-hiring-request','view-all-pending-hiring-request-listing',
                                            'view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing','view-all-on-hold-hiring-request-listing',
                                            'view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing','view-pending-hiring-request-listing-of-current-user',
                                            'view-approved-hiring-request-listing-of-current-user','view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
                                            'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
                                            'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-details-of-current-user'
                                            ,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
                                            ,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user'
                                            ,'hiring-request-cancel-action','hiring-request-of-current-user-delete-action','hiring-request-close-action','hiring-request-on-hold-action','hiring-request-cancel-action'
                                            ,'create-questionnaire','edit-questionnaire','view-questionnaire-details','create-job-description','edit-job-description','view-pending-job-description-list','view-approved-job-description-list','view-rejected-job-description-list','view-job-description-details','view-job-description-approvals-details']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Employee Hiring</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @canany(['create-employee-hiring-request','edit-employee-hiring-request','view-all-pending-hiring-request-listing',
                                            'view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing','view-all-on-hold-hiring-request-listing',
                                            'view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing','view-pending-hiring-request-listing-of-current-user',
                                            'view-approved-hiring-request-listing-of-current-user','view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
                                            'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
                                            'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-details-of-current-user'
                                            ,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
                                            ,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user'
                                            ,'hiring-request-cancel-action','hiring-request-of-current-user-delete-action','hiring-request-close-action','hiring-request-on-hold-action',
                                            'hiring-request-cancel-action','create-questionnaire','edit-questionnaire','view-questionnaire-details','create-job-description'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-employee-hiring-request','edit-employee-hiring-request',
                                                'view-all-pending-hiring-request-listing','view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing',
                                                'view-all-on-hold-hiring-request-listing','view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing',
                                                'view-pending-hiring-request-listing-of-current-user','view-approved-hiring-request-listing-of-current-user',
                                                'view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
                                                'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
                                                'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-details-of-current-user'
                                                ,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
                                                ,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user'
                                                ,'hiring-request-cancel-action','hiring-request-of-current-user-delete-action','hiring-request-close-action','hiring-request-on-hold-action',
                                                'hiring-request-cancel-action','create-questionnaire','edit-questionnaire','view-questionnaire-details','create-job-description']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('employee-hiring-request.index') }}" class="dropdown-item" data-key="t-login">Hiring Requests</a>
                                                @endif
                                                @endcanany
                                                @canany(['create-job-description','edit-job-description','view-pending-job-description-list','view-approved-job-description-list','view-rejected-job-description-list','view-job-description-details','view-job-description-approvals-details'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-job-description','edit-job-description','view-pending-job-description-list','view-approved-job-description-list','view-rejected-job-description-list','view-job-description-details','view-job-description-approvals-details']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('job_description.index') }}" class="dropdown-item" data-key="t-login">Job Descriptions</a>
                                                @endif
                                                @endcanany
                                                @canany(['view-interview-summary-report-listing','create-interview-summary-report'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing','create-interview-summary-report']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('interview-summary-report.index') }}" class="dropdown-item" data-key="t-login">Interview Summary</a>
                                                @endif
                                                @endcanany

                                            </div>
                                            @endif
                                            @endcanany
                                        </div>
                                        <div class="dropdown">
                                            @canany(['view-asset-allocation-request-listing'])
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-asset-allocation-request-listing']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> On Boarding</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @canany(['view-joining-report-listing','current-user-view-joining-report-listing'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','current-user-view-joining-report-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('employee_joining_report.index','new_employee') }}" class="dropdown-item" data-key="t-login">Joining Report</a>
                                                
                                                @endif
                                                @endcanany
                                                @canany(['view-asset-allocation-request-listing'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-asset-allocation-request-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('asset_allocation.index') }}" class="dropdown-item" data-key="t-login">Asset Allocation</a>
                                                @endif
                                                @endcanany
                                                @canany([])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole([]);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('interview-summary-report.index') }}" class="dropdown-item" data-key="t-login">HandOver Form</a>
                                                @endif
                                                @endcanany

                                            </div>
                                            @endif
                                            @endcanany
                                        </div>
                                        <div class="dropdown">
                                            @canany(['view-passport-request-list','current-user-view-passport-request-list'])
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-list','current-user-view-passport-request-list']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Passport Request</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @canany(['view-passport-request-list','current-user-view-passport-request-list'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-list','current-user-view-passport-request-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('passport_request.index') }}" class="dropdown-item" data-key="t-login">Passport Submit</a>
                                                @endif
                                                @endcanany
                                                @canany(['view-passport-request-list','current-user-view-passport-request-list'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-list','current-user-view-passport-request-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('passport_release.index') }}" class="dropdown-item" data-key="t-login">Passport Release</a>
                                                @endif
                                                @endcanany
                                            </div>
                                            @endif
                                            @endcanany
                                        </div>
                                        @canany(['view-liability-list','current-user-view-liability-list'])
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-liability-list','current-user-view-liability-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('employee_liability.index') }}"  id="topnav-utility" role="button">
                                            <span data-key="t-utility">Liability</span>
                                        </a>
                                        @endif
                                        @endcanany
                                        @canany(['view-leave-list','view-current-user-leave-list'])
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-leave-list','view-current-user-leave-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('employee_leave.index') }}"  id="topnav-utility" role="button">
                                            <span data-key="t-utility">Leave</span>
                                        </a>
                                        @endif
                                        @endcanany
                                        <div class="dropdown">
                                            @canany(['view-joining-report-listing','current-user-view-joining-report-listing'])
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','current-user-view-joining-report-listing']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Joining Report</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @canany(['view-joining-report-listing','current-user-view-joining-report-listing'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','current-user-view-joining-report-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('employee_joining_report.index','new_employee') }}" class="dropdown-item" data-key="t-login">New Employee</a>
                                                @endif
                                                @endcanany
                                                @canany(['view-joining-report-listing','current-user-view-joining-report-listing'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','current-user-view-joining-report-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('employee_joining_report.index','internal_transfer') }}" class="dropdown-item" data-key="t-login">Internal Transfer</a>
                                                @endif
                                                @endcanany
                                                @canany(['view-joining-report-listing','current-user-view-joining-report-listing'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','current-user-view-joining-report-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('employee_joining_report.index','vacations_or_leave') }}" class="dropdown-item" data-key="t-login">Vacations Or Leave</a>
                                                @endif
                                                @endcanany
                                            </div>
                                            @endif
                                            @endcanany
                                        </div>
                                        <div class="dropdown">
                                            @canany(['list-all-increment','list-current-user-increment','view-birthday-po-list','view-ticket-listing','view-ticket-listing-of-current-user','view-all-list-insurance'])
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['list-all-increment','list-current-user-increment','view-all-list-insurance','view-birthday-po-list','view-ticket-listing','view-ticket-listing-of-current-user']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Compensation & Benefits</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @canany(['list-all-increment','list-current-user-increment'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['list-all-increment','list-current-user-increment']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('increment.index') }}" class="dropdown-item" data-key="t-login">Salary Increment</a>
                                                @endif
                                                @endcanany
                                                @canany(['view-all-list-insurance','view-current-user-list-insurance'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-list-insurance','view-current-user-list-insurance']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('insurance.index') }}" class="dropdown-item" data-key="t-login">Insurance</a>
                                                @endif
                                                @endcanany
                                                @canany(['view-birthday-po-list'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-birthday-po-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('birthday_gift.index') }}" class="dropdown-item" data-key="t-login">Birthday Gift</a>
                                                @endif
                                                @endcanany
                                                @canany(['view-ticket-listing','view-ticket-listing-of-current-user'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-ticket-listing','view-ticket-listing-of-current-user']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('ticket_allowance.index') }}" class="dropdown-item" data-key="t-login">Ticket Allowance</a>
                                                @endif
                                                @endcanany
                                                
                                            </div>
                                            @endif
                                            @endcanany
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @endcanany
                                <!-- HRM -->
                                @canany(['addon-supplier-create', 'addon-supplier-list'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-supplier-create','addon-supplier-list']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="grid"></i>
                                        <span data-key="t-extra-pages">Vendor</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
                                        @can('addon-supplier-create')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-supplier-create']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('suppliers.create') }}" id="topnav-auth" role="button">
                                                <span data-key="t-authentication">Create Vendor</span>
                                            </a>
                                        </div>
                                        @endif
                                        @endcan
                                        @can('addon-supplier-list')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-supplier-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('suppliers.index') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Vendor Info </span>
                                            </a>
                                        </div>
                                        @endif
                                        @endcan
                                    </div>
                                </li>
                                @endif
                                @endcanany



                                @canany(['warranty-create', 'warranty-list','addon-create','accessories-list','spare-parts-list','kit-list'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create','warranty-list','addon-create','accessories-list','spare-parts-list','kit-list']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="file-text"></i>
                                        <span data-key="t-extra-pages">Vehicles</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
                                        @canany(['warranty-create', 'warranty-list'])
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create','warranty-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Warranty</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @can('warranty-create')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('warranty.create') }}" class="dropdown-item" data-key="t-login">Create Warranty</a>
                                                @endif
                                                @endcan
                                                @can('warranty-list')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('warranty.index') }}" class="dropdown-item" data-key="t-login">Warranty Info</a>
                                                @endif
                                                @endcan
                                            </div>
                                        </div>
                                        @endif
                                        @endcanany

                                        @canany(['addon-create','accessories-list','spare-parts-list','kit-list'])
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-create','accessories-list','spare-parts-list','kit-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Addons</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @can('addon-create')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-create']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('addon.create') }}" class="dropdown-item" data-key="t-login">Create Addon</a>
                                                <a href="{{ route('kit.create') }}" class="dropdown-item" data-key="t-login">Create Kit</a>
                                                @endif
                                                @endcan
                                                @canany(['addon-create','accessories-list','spare-parts-list','kit-list'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['accessories-list','spare-parts-list','kit-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <div class="dropdown">
                                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                        <span data-key="t-utility"> Addons Types</span>
                                                        <div class="arrow-down"></div>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                        @can('accessories-list')
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['accessories-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{route('addon.list','P')}}" class="dropdown-item" data-key="t-login">Accessories</a>
                                                        @endif
                                                        @endcan

                                                        @can('spare-parts-list')
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['spare-parts-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{route('addon.list','SP')}}" class="dropdown-item" data-key="t-login">Spare Parts</a>
                                                        @endif
                                                        @endcan
                                                        @can('kit-list')
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['kit-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{route('addon.list','K')}}" class="dropdown-item" data-key="t-login">Kits</a>
                                                        @endif
                                                        @endcan
                                                        @canany(['accessories-list','spare-parts-list','kit-list'])
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['accessories-list','spare-parts-list','kit-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{route('addon.list','all')}}" class="dropdown-item" data-key="t-login">All</a>
                                                        @endif
                                                        @endcanany
                                                    </div>
                                                </div>
                                                @endif
                                                @endcanany
                                            </div>
                                        </div>
                                        @endif
                                        @endcanany
                                    </div>
                                </li>
                                @endif
                                @endcanany
                            @canany(['warranty-selling-price-approve','approve-addon-new-selling-price','supplier-price-action','verify-candidate-personal-information','send-personal-info-form-action'])
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-selling-price-approve','approve-addon-new-selling-price','supplier-price-action','verify-candidate-personal-information','send-personal-info-form-action']);
                            @endphp
                            @if ($hasPermission OR Auth::user()->leave_request_approval['can'] == true OR Auth::user()->liability_request_approval['can'] == true OR Auth::user()->passport_submit_request_approval['can'] == true OR Auth::user()->passport_release_request_approval['can'] == true OR Auth::user()->hiring_request_approval['can'] == true OR Auth::user()->job_description_approval['can'] == true OR Auth::user()->candidate_docs_varify OR Auth::user()->candidate_personal_information_varify > 0 OR Auth::user()->joining_report_approval['count'])
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                    <i data-feather="grid"></i>
                                    <span data-key="t-extra-pages">Approvals @if((Auth::user()->liability_request_approval['count']+Auth::user()->leave_request_approval['count']+Auth::user()->passport_submit_request_approval['count']+Auth::user()->passport_release_request_approval['count']+Auth::user()->hiring_request_approval['count']+Auth::user()->job_description_approval['count']+Auth::user()->interview_summary_report_approval['count']) > 0)
                                    <span class="approval-count">{{Auth::user()->liability_request_approval['count']+Auth::user()->leave_request_approval['count']+Auth::user()->passport_submit_request_approval['count']+Auth::user()->passport_release_request_approval['count']+Auth::user()->hiring_request_approval['count']+Auth::user()->job_description_approval['count']+Auth::user()->interview_summary_report_approval['count']+Auth::user()->candidate_docs_varify+Auth::user()->candidate_personal_information_varify+Auth::user()->verify_offer_letters+Auth::user()->joining_report_approval['count']}}</span>
                                                @endif
                                            </span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-more">
                                    @canany(['approve-addon-new-selling-price','supplier-price-action'])
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-price-action','approve-addon-new-selling-price',]);
                                    @endphp
                                    @if ($hasPermission)
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('addon.approval','P') }}" id="topnav-auth" role="button">
                                            <span data-key="t-authentication">Accessories</span>
                                        </a>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('addon.approval','SP') }}" id="topnav-utility" role="button">
                                            <span data-key="t-utility">Spare Parts</span>
                                        </a>
                                    </div>
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('addon.approval','K') }}" id="topnav-utility" role="button">
                                            <span data-key="t-utility">Kits</span>
                                        </a>
                                    </div>
                                    @endif
                                    @endcanany

                                    @canany(['warranty-selling-price-approve'])
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-selling-price-approve']);
                                    @endphp
                                    @if ($hasPermission)
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('warranty-selling-price-histories.index') }}" id="topnav-utility" role="button">
                                            <span data-key="t-utility">Warranties</span>
                                        </a>
                                    </div>
                                    @endif
                                    @endcanany

                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-candidate-personal-information','send-personal-info-form-action']);
                                    @endphp
                                    @if($hasPermission OR Auth::user()->hiring_request_approval['can'] == true OR Auth::user()->job_description_approval['can']  == true OR Auth::user()->interview_summary_report_approval == true OR Auth::user()->candidate_docs_varify > 0 OR Auth::user()->candidate_personal_information_varify > 0)
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                            <span data-key="t-utility"> Employee Hiring @if((Auth::user()->hiring_request_approval['count']+Auth::user()->job_description_approval['count']+Auth::user()->interview_summary_report_approval['count']) > 0)
                                            <span class="approval-count">{{Auth::user()->hiring_request_approval['count']+Auth::user()->job_description_approval['count']+Auth::user()->interview_summary_report_approval['count']+Auth::user()->candidate_docs_varify+Auth::user()->candidate_personal_information_varify+Auth::user()->verify_offer_letters}}</span>
                                                @endif
                                            </span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                            @if(Auth::user()->hiring_request_approval['can'] == true)
                                            <a href="{{ route('employee-hiring-request.approval-awaiting') }}" class="dropdown-item" data-key="t-login">Hiring Requests
                                                @if(Auth::user()->hiring_request_approval['count'] > 0) <span class="approval-count">{{Auth::user()->hiring_request_approval['count']}}</span> @endif
                                            </a>
                                            @endif
                                            @if(Auth::user()->job_description_approval['can']  == true)
                                            <a href="{{ route('employee-hiring-job-description.approval-awaiting') }}" class="dropdown-item" data-key="t-login">Job Descriptions
                                            @if(Auth::user()->job_description_approval['count'] > 0) <span class="approval-count">{{Auth::user()->job_description_approval['count']}}<span> @endif
                                            </a>
                                            @endif
                                            @if(Auth::user()->interview_summary_report_approval == true)
                                            <a href="{{ route('interview-summary-report.approval-awaiting') }}" class="dropdown-item" data-key="t-login">InterviewSummary @if(Auth::user()->interview_summary_report_approval['count'] > 0)<span class="approval-count">{{Auth::user()->interview_summary_report_approval['count']}}</span> @endif
                                            </a>
                                            @endif
                                            @canany(['verify-candidates-documents','send-candidate-documents-request-form'])
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-candidates-documents','send-candidate-documents-request-form']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a href="{{ route('candidate.listDocs') }}" class="dropdown-item" data-key="t-login">Candidate Docs @if(Auth::user()->candidate_docs_varify > 0)<span class="approval-count">{{Auth::user()->candidate_docs_varify}}</span> @endif</a>
                                            @endif
                                            @endcanany
                                            @canany(['verify-offer-letter-signature'])
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-offer-letter-signature']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a href="{{ route('candidate.listOfferLetter') }}" class="dropdown-item" data-key="t-login">Offer Letter @if(Auth::user()->verify_offer_letters > 0)<span class="approval-count">{{Auth::user()->verify_offer_letters}}</span> @endif</a>
                                            @endif
                                            @endcanany
                                            @canany(['verify-candidate-personal-information','send-personal-info-form-action'])
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-candidate-personal-information','send-personal-info-form-action']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a href="{{ route('candidate.listingInfo') }}" class="dropdown-item" data-key="t-login">Candidate Info @if(Auth::user()->candidate_personal_information_varify > 0)<span class="approval-count">{{Auth::user()->candidate_personal_information_varify}}</span> @endif</a>
                                            @endif
                                            @endcanany
                                        </div>
                                    </div>
                                    @endif


                                    <!-- @if(Auth::user()->joining_report_approval['can'] == true)
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                            <span data-key="t-utility"> On Boarding @if((Auth::user()->joining_report_approval['count']) > 0)
                                            <span class="approval-count">{{Auth::user()->joining_report_approval['count']}}</span>
                                                @endif
                                            </span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                            @if(Auth::user()->joining_report_approval['can'] == true)
                                            <a href="{{ route('joiningReport.approvalAwaiting') }}" class="dropdown-item" data-key="t-login">Joining Report
                                                @if(Auth::user()->joining_report_approval['count'] > 0) <span class="approval-count">{{Auth::user()->joining_report_approval['count']}}</span> @endif
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif -->

                                    @if(Auth::user()->passport_submit_request_approval['can'] == true OR Auth::user()->passport_release_request_approval['can'] == true)
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                            <span data-key="t-utility"> Passport Request @if((Auth::user()->passport_submit_request_approval['count']) > 0 OR (Auth::user()->passport_release_request_approval['count']) > 0)
                                            <span class="approval-count">{{Auth::user()->passport_submit_request_approval['count']+Auth::user()->passport_release_request_approval['count']}}</span>
                                                @endif
                                            </span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                            @if(Auth::user()->passport_submit_request_approval['can'] == true)
                                            <a href="{{ route('passportSubmit.approvalAwaiting') }}" class="dropdown-item" data-key="t-login">Passport Submit
                                                @if(Auth::user()->passport_submit_request_approval['count'] > 0) <span class="approval-count">{{Auth::user()->passport_submit_request_approval['count']}}</span> @endif
                                            </a>
                                            @endif
                                            @if(Auth::user()->passport_release_request_approval['can'] == true)
                                            <a href="{{ route('passportRelease.approvalAwaiting') }}" class="dropdown-item" data-key="t-login">Passport Release
                                                @if(Auth::user()->passport_release_request_approval['count'] > 0) <span class="approval-count">{{Auth::user()->passport_release_request_approval['count']}}</span> @endif
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    @if(Auth::user()->liability_request_approval['can'] == true OR Auth::user()->leave_request_approval['can'] == true)
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                            <span data-key="t-utility"> Employee @if((Auth::user()->liability_request_approval['count']) > 0 OR (Auth::user()->leave_request_approval['count']) > 0)
                                            <span class="approval-count">{{Auth::user()->liability_request_approval['count']+Auth::user()->leave_request_approval['count']}}</span>
                                                @endif
                                            </span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                            @if(Auth::user()->liability_request_approval['can'] == true)
                                            <a href="{{ route('liability.approvalAwaiting') }}" class="dropdown-item" data-key="t-login">Liability
                                                @if(Auth::user()->liability_request_approval['count'] > 0) <span class="approval-count">{{Auth::user()->liability_request_approval['count']}}</span> @endif
                                            </a>
                                            @endif
                                            @if(Auth::user()->leave_request_approval['can'] == true)
                                            <a href="{{ route('leave.approvalAwaiting') }}" class="dropdown-item" data-key="t-login">Leave
                                                @if(Auth::user()->leave_request_approval['count'] > 0) <span class="approval-count">{{Auth::user()->leave_request_approval['count']}}</span> @endif
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    @if(Auth::user()->joining_report_approval['can'] == true)
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('joiningReport.approvalAwaiting') }}" id="topnav-utility" role="button">
                                            <span data-key="t-utility"> Joining Report @if((Auth::user()->joining_report_approval['count']) > 0)
                                            <span class="approval-count">{{Auth::user()->joining_report_approval['count']+Auth::user()->leave_request_approval['count']}}</span>
                                                @endif
                                            </span>
                                            <!-- <div class="arrow-down"></div> -->
                                        </a>
                                        <!-- <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                            @if(Auth::user()->joining_report_approval['can'] == true)
                                            <a href="{{ route('joiningReport.approvalAwaiting') }}" class="dropdown-item" data-key="t-login">New Employee
                                                @if(Auth::user()->joining_report_approval['count'] > 0) <span class="approval-count">{{Auth::user()->joining_report_approval['count']}}</span> @endif
                                            </a>
                                            <a href="{{ route('joiningReport.approvalAwaiting') }}" class="dropdown-item" data-key="t-login">Internal Transfer
                                                @if(Auth::user()->joining_report_approval['count'] > 0) <span class="approval-count">{{Auth::user()->joining_report_approval['count']}}</span> @endif
                                            </a>
                                            <a href="{{ route('joiningReport.approvalAwaiting') }}" class="dropdown-item" data-key="t-login">Vacations Or Leave
                                                @if(Auth::user()->joining_report_approval['count'] > 0) <span class="approval-count">{{Auth::user()->joining_report_approval['count']}}</span> @endif
                                            </a>
                                            @endif
                                        </div> -->
                                    </div>
                                    @endif


                                </div>
                            </li>
                            @endif
                            @endcanany

                                @can('Calls-view')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="phone-call"></i>
                                        <span data-key="t-extra-pages">Messages & Calls</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('calls.index') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Pending Leads</span>
                                            </a>
                                        </div>
										<div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('calls.inprocess') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">In Process Leads</span>
                                            </a>
                                        </div>
										<div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('calls.converted') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Converted Leads</span>
                                            </a>
                                        </div>
										<div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('calls.rejected') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Rejected Leads</span>
                                            </a>
                                        </div>
										<div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('calls.datacenter') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Leads Data Center</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('sale_person_status.index') }}" id="topnav-more" role="button">
                                        <i data-feather="user"></i>
                                        <span data-key="t-extra-pages">Sales Persons</span>
                                    </a>
                                </li>
                                @endif
                                @endcan
                                @can('View-daily-movemnets')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('View-daily-movemnets');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('movement.index') }}" id="topnav-more" role="button">
                                        <i data-feather="command"></i>
                                        <span data-key="t-extra-pages">Movements</span>
                                    </a>
                                </li>
                                @endif
                                @endcan
                                @can('view-po-details')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po-details');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('purchasing-order.index') }}" id="topnav-more" role="button">
                                        <i data-feather="award"></i>
                                        <span data-key="t-extra-pages">Purchase Order</span>
                                    </a>
                                </li>
                                @endif
                                @endcan

                                @can('variants-view')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-view');
                                @endphp
                                @if ($hasPermission)

                                @endif
                                @endcan
                                @can('sales-view')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-view');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('dailyleads.index') }}" id="topnav-more" role="button">
                                        <i data-feather="film"></i>
                                        <span data-key="t-extra-pages">Leads</span>
                                    </a>
                                </li>
                                @endif
                                @endcan
                                @can('part-input-incident')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('part-input-incident');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('incident.index') }}" id="topnav-more" role="button">
                                        <i data-feather="check-circle"></i>
                                        <span data-key="t-extra-pages">Inspection</span>
                                    </a>
                                </li>
                                @endif
                                @endcan
                                @can('inspection-edit')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-edit');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('inspection.index') }}" id="topnav-more" role="button">
                                        <i data-feather="check-circle"></i>
                                        <span data-key="t-extra-pages">Inspection</span>
                                    </a>
                                </li>
                                @endif
                                @endcan

                                @can('demand-list')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-list');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="file-text"></i>
                                        <span data-key="t-extra-pages">Demand & Planning</span>
                                        <div class="arrow-down"></div>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
                                        @can('demand-planning-supplier-list')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-planning-supplier-list');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('suppliers.index') }}" id="topnav-auth" role="button">
                                                <span data-key="t-authentication">Vendors</span>
                                            </a>
                                        </div>
                                        @endif
                                        @endcan
                                        @can('demand-create')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-create');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Demand</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @can('demand-list')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-list');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('demands.index')}}" class="dropdown-item" data-key="t-login">Demand Lists </a>
                                                @endif
                                                @endcan
                                                <a href="{{route('demands.create')}}" class="dropdown-item" data-key="t-login">Add New Demand </a>
                                            </div>
                                        </div>
                                        @endif
                                        @endcan
                                        @can('LOI-list')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility">LOI</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @can('LOI-create')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-create');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('letter-of-indents.create')}}" class="dropdown-item" data-key="t-login">Add New LOI</a>
                                                @endif
                                                @endcan
                                                @can('LOI-list')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('letter-of-indents.index')}}" class="dropdown-item" data-key="t-login">LOI Lists</a>
                                                @endif
                                                @endcan
                                                @can('loi-supplier-approve')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-supplier-approve');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('letter-of-indents.get-suppliers-LOIs')}}" class="dropdown-item" data-key="t-login">Supplier Approval </a>
                                                @endif
                                                @endcan
                                            </div>
                                        </div>
                                        @endif
                                        @endcan

                                        @canany(['supplier-inventory-list','supplier-inventory-edit','supplier-inventory-list-with-date-filter',
                                            'supplier-inventory-report-view'])
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-inventory-list','supplier-inventory-edit',
                                        'supplier-inventory-list-with-date-filter','supplier-inventory-report-view']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Supplier Inventory</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @can('supplier-inventory-list')
                                                    @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list');
                                                    @endphp
                                                    @if ($hasPermission)
                                                        <a href="{{route('supplier-inventories.index')}}" class="dropdown-item" data-key="t-login">Supplier Inventory</a>
                                                    @endif
                                                @endcan
{{--                                                 @can('supplier-inventory-list-view-all')--}}
{{--                                                     @php--}}
{{--                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list-view-all');--}}
{{--                                                     @endphp--}}
{{--                                                     @if ($hasPermission)--}}
                                                        <a href="{{route('supplier-inventories.view-all')}}" class="dropdown-item" data-key="t-login">Inventory Stock</a>
{{--                                                     @endif--}}
{{--                                                 @endcan--}}
                                                {{-- @can('supplier-inventory-list-with-date-filter')--}}
                                                {{-- @php--}}
                                                {{-- $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list-with-date-filter');--}}
                                                {{-- @endphp--}}
                                                {{-- @if ($hasPermission)--}}
                                                {{-- <a href="{{route('supplier-inventories.lists')}}" class="dropdown-item" data-key="t-login">Date Filter</a>--}}
                                                {{-- @endif--}}
                                                {{-- @endcan--}}
                                                @can('supplier-inventory-report-view')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-report-view');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('supplier-inventories.file-comparision')}}" class="dropdown-item" data-key="t-login">File Comparison</a>
                                                @endif
                                                @endcan
                                            </div>
                                        </div>
                                        @endif
                                        @endcan
                                        @can('PFI-list')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-list');
                                        @endphp
                                            @if ($hasPermission)
                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                    <span data-key="t-utility">PFI</span>
                                                    <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                    <a href="{{route('pfi.index')}}" class="dropdown-item" data-key="t-login">List PFI </a>
                                                </div>
                                            </div>
                                            @endif
                                        @endcan

                                        @canany(['model-year-calculation-rules-list','model-year-calculation-categories-list','list-customer','list-master-models'])
                                            @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['model-year-calculation-rules-list',
                                                'model-year-calculation-categories-list','list-customer','list-master-models']);
                                            @endphp
                                            @if ($hasPermission)
                                                <div class="dropdown">
                                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                        <span data-key="t-utility">Master Data</span>
                                                        <div class="arrow-down"></div>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                        @can('list-customer')
                                                            @php
                                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-customer');
                                                            @endphp
                                                            @if ($hasPermission)
                                                                <a href="{{route('dm-customers.index')}}" class="dropdown-item" data-key="t-login">List Customers </a>
                                                            @endif
                                                        @endcan
                                                        @can('list-master-models')
                                                            @php
                                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-master-models');
                                                            @endphp
                                                            @if ($hasPermission)
                                                                <a href="{{route('master-models.index')}}" class="dropdown-item" data-key="t-login"> Model Lists</a>
                                                            @endif
                                                        @endcan
                                                        <div class="dropdown">
                                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                                <span data-key="t-utility">Model Year</span>
                                                                <div class="arrow-down"></div>
                                                            </a>
                                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                                @can('model-year-calculation-rules-list')
                                                                    @php
                                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-categories-list');
                                                                    @endphp
                                                                    @if ($hasPermission)
                                                                        <a href="{{route('model-year-calculation-rules.index')}}" class="dropdown-item" data-key="t-login">
                                                                            List Rules
                                                                        </a>
                                                                    @endif
                                                                @endcan
                                                                @can('model-year-calculation-categories-list')
                                                                    @php
                                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-year-calculation-categories-list');
                                                                    @endphp
                                                                    @if ($hasPermission)
                                                                        <a href="{{route('model-year-calculation-categories.index')}}" class="dropdown-item" data-key="t-login">
                                                                            List Categories
                                                                        </a>
                                                                    @endif
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endcan
                                    </div>
                                </li>
                                @endif
                                @endcan
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-view');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="grid"></i>
                                        <span data-key="t-extra-pages">Master Data</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
                                    <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('paymentterms.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Payment Terms</span>
                                            </a>
                                        </div>
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('variant-view');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('variants.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Variants </span>
                                            </a>
                                        </div>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('shipping-master');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('Shipping.index') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Shipping </span>
                                            </a>
                                        </div>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('agents');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('agents.index') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Agents </span>
                                            </a>
                                        </div>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('vendor-view');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('suppliers.index') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Vendors </span>
                                            </a>
                                        </div>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('colour-edit');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('colourcode.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Colours </span>
                                            </a>
                                        </div>
                                        @endif

                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-edit');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('warehouse.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Warehouse </span>
                                            </a>
                                        </div>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('brands.index');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('brands.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Brands </span>
                                            </a>
                                        </div>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-model-lines-list');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('model-lines.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Model Lines </span>
                                            </a>
                                        </div>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-view');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('variant-prices.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Variant Price </span>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </li>
                                @endif
                                @can('HR-view')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('HR-view');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('hiring.index') }}" id="topnav-more" role="button">
                                        <i data-feather="file-text"></i>
                                        <span data-key="t-extra-pages">Hiring</span>
                                    </a>
                                </li>
                                </li>
                                @endif
                                @endcan
                                @can('Calls-view')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('lead_source.index') }}" id="topnav-more" role="button">
                                        <i data-feather="server"></i>
                                        <span data-key="t-extra-pages">Master </span>
                                    </a>
                                </li>
                                @endif
                                @endcan
                                @can(['master-addons-list'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-addons-list']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('master-addons.index') }}" id="topnav-more" role="button">
                                        <i data-feather="server"></i>
                                        <span data-key="t-extra-pages">Master Addons</span>
                                    </a>
                                </li>
                                @endif
                                @endcan
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('vehicles.viewall') }}" id="topnav-more" role="button">
                                        <i data-feather="server"></i>
                                        <span data-key="t-extra-pages">View All</span>
                                    </a>
                                </li>
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['document-edit']);
                                @endphp
                                @if ($hasPermission)
                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('logisticsdocuments.index') }}" id="topnav-more" role="button">
                                        <i data-feather="list"></i>
                                        <span data-key="t-extra-pages">Documents</span>
                                    </a>
                                </li>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['approve-reservation']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('booking.index') }}" id="topnav-more" role="button">
                                        <i data-feather="list"></i>
                                        <span data-key="t-extra-pages">Booking</span>
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </nav>

                </div>
            </div>
        </div>
        <!-- </div> -->

        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3  rolename-username-main-div">
            <div class="dropdown d-flex align-items-center justify-content-center more-button-username-rolename-container">

                <!-- First div with three dots -->
                <div class="nav-item dropdown more-button" id="more-dropdown-button">
                    <button class="btn dropdown-toggle arrow-none additional-menus-dots" id="more-dropdown" data-toggle="dropdown">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div id="more-dropdown-menu" class="dropdown-menu">
                    </div>
                </div>

                <!-- Second div with role name -->
                <div class="nav-item rolename-button" id="rolename-dropdown-button">
                    <button class="btn rolename-toggle btn-success" id="rolename-dropdown">
                        @php
                        $selectedrole = Auth::user()->selectedRole;
                        $selected = DB::table('roles')->where('id', $selectedrole)->first();
                        $roleselected = $selected ? $selected->name : null;
                        @endphp
                        {{ $roleselected }}
                    </button>
                    <div id="rolename-dropdown-menu" class="dropdown-menu dropdown-menu-end">
                        @foreach ($assignedRoles as $role)
                        <a class="dropdown-item" href="{{ route('users.updateRole', $role->id) }}">
                            <i class="fa fa-user-circle" aria-hidden="true"></i> {{ $role->name }}
                        </a>
                        <div class="dropdown-divider"></div>
                        @endforeach
                    </div>
                </div>

                <!-- Third div with username -->
                <div class="nav-item dropdown username-button" id="username-dropdown-button">
                    <button class="btn username-toggle header-item bg-soft-light border-start border-end" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 55px;">
                        <img class="rounded-circle header-profile-user" src="{{ asset('images/users/avatar-1.jpg') }}" alt="Header Avatar" style="float: left;">
                        <span class="d-none d-xl-inline-block fw-medium user-textname-div" style="line-height: 35px;">
                            @php
                            $userName = auth()->user()->name;
                            $maxNameLength = 12;
                            @endphp
                            @if (strlen($userName) <= $maxNameLength) {{ $userName }} @else {{ substr($userName, 0, $maxNameLength) . '..' }} @endif </span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div id="username-dropdown-menu" class="dropdown-menu dropdown-menu-end" style="top: 100%;">
                        <a class="dropdown-item" href="{{ route('profile.index') }}">
                            <i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        @canany(['user-list-active', 'user-list-inactive', 'user-list-deleted', 'user-create'])
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-list-active', 'user-list-inactive', 'user-list-deleted', 'user-create']);
                        @endphp
                        @if ($hasPermission)
                        <a class="dropdown-item" href="{{ route('users.index') }}">
                            <i class="fa fa-users" aria-hidden="true"></i> Users
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
                        @endcanany

                        @canany(['role-list', 'role-create'])
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['role-list', 'role-create']);
                        @endphp
                        @if ($hasPermission)
                        <a class="dropdown-item" href="{{ route('roles.index') }}">
                            <i class="fa fa-user-circle" aria-hidden="true"></i> Roles
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
                        @endcanany
                        @canany(['master-module-list', 'master-module-create', 'master-module-edit'])
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-module-list', 'master-module-create', 'master-module-edit']);
                        @endphp
                        @if ($hasPermission)
                        <a class="dropdown-item" href="{{ route('modules.index') }}">
                            <i class="fa fa-book" aria-hidden="true"></i> Modules
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
                        @endcanany
                        @canany(['master-permission-list', 'master-permission-create', 'master-permission-edit'])
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-permission-list', 'master-permission-create', 'master-permission-edit']);
                        @endphp
                        @if ($hasPermission)
                        <a class="dropdown-item" href="{{ route('permissions.index') }}">
                            <i class="fa fa-door-open" aria-hidden="true"></i> Permissions
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
                        @endcanany
                        @canany(['view-log-activity'])
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-log-activity']);
                        @endphp
                        @if ($hasPermission)
                        <a class="dropdown-item" href="{{ route('listUsers') }}">
                            <i class="fa fa-user-circle" aria-hidden="true"></i> Login Activity
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
                        @endcanany
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout font-size-16 align-middle me-1"></i>Logout
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- </div> -->
</div>
@include('partials/pushveh')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function moveMenuItems() {
            const menuContainer = document.getElementById("menu-items");
            const moreDropdownMenu = document.getElementById("more-dropdown-menu");

            if (!menuContainer || !moreDropdownMenu) {
                return;
            }

            let availableSpace = menuContainer.offsetWidth;
            let itemsToMove = [];

            for (let i = 0; i < menuContainer.children.length; i++) {
                let item = menuContainer.children[i];
                if (item.offsetWidth > availableSpace) {
                    itemsToMove.push(item);
                } else {
                    availableSpace -= item.offsetWidth;
                }
            }

            itemsToMove = itemsToMove.reverse();
            itemsToMove.forEach(item => {
                let clonedItem = item.cloneNode(true);
                moreDropdownMenu.appendChild(clonedItem);
                item.remove();
            });

            if (moreDropdownMenu.children.length > 0) {
                const moreDropdownElement = document.getElementById("more-dropdown");
                if (moreDropdownElement) {
                    moreDropdownElement.classList.remove("d-none");
                }
            }
        }

        function moveItemsBack() {
            const menuContainer = document.getElementById("menu-items");
            const moreDropdownMenu = document.getElementById("more-dropdown-menu");

            if (!menuContainer || !moreDropdownMenu) {
                return;
            }

            let availableSpace = menuContainer.offsetWidth;
            let itemsToMoveBack = [];

            for (let i = 0; i < moreDropdownMenu.children.length; i++) {
                let item = moreDropdownMenu.children[i];
                if (item.offsetWidth > availableSpace) {
                    break;
                }
                itemsToMoveBack.push(item);
                availableSpace -= item.offsetWidth;
            }

            itemsToMoveBack.forEach(item => {
                let clonedItem = item.cloneNode(true);
                menuContainer.appendChild(clonedItem);
                item.remove();
            });

            if (moreDropdownMenu.children.length === 0) {
                const moreDropdownElement = document.getElementById("more-dropdown");
                if (moreDropdownElement) {
                    moreDropdownElement.classList.add("d-none");
                }
            }
        }

        moveMenuItems();
        window.addEventListener("resize", moveItemsBack);
        window.addEventListener("resize", moveMenuItems);
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function checkAvailableSpace() {
            const menuContainer = document.getElementById("menu-items");
            const moreDropdownButton = document.getElementById("more-dropdown-button");
            const moreDropdownMenu = document.getElementById("more-dropdown-menu");

            if (menuContainer && moreDropdownButton) {
                let availableSpace = menuContainer.offsetWidth;
                let totalWidth = 0;
                let lastVisibleIndex = -1;

                for (let i = 0; i < menuContainer.children.length; i++) {
                    let item = menuContainer.children[i];
                    totalWidth += item.offsetWidth;

                    if (totalWidth > availableSpace) {
                        moveItemsToMoreDropdown(menuContainer, moreDropdownMenu, lastVisibleIndex + 1);
                        moreDropdownButton.style.display = "block";
                        return;
                    }

                    lastVisibleIndex = i;
                }

                if (moreDropdownMenu.children.length === 0) {
                    moreDropdownButton.style.display = "none";
                } else {
                    moreDropdownButton.style.display = "block";
                }
            }
        }

        // Helper function to move items to the "more" dropdown menu
        function moveItemsToMoreDropdown(menuContainer, moreDropdownMenu, startIndex) {
            for (let i = startIndex; i < menuContainer.children.length; i++) {
                let item = menuContainer.children[i];
                let clonedItem = item.cloneNode(true);
                moreDropdownMenu.appendChild(clonedItem);
                item.remove();
            }
        }

        // Call the function to check available space when the page loads and on window resize
        checkAvailableSpace();
        window.addEventListener("resize", checkAvailableSpace);
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const moreButton = document.querySelector(".more-button");
        const rolenameButton = document.querySelector(".rolename-button");
        const usernameButton = document.querySelector(".username-button");
        const moreDropdown = document.getElementById("more-dropdown-menu");
        const rolenameDropdown = document.getElementById("rolename-dropdown-menu");
        const usernameDropdown = document.getElementById("username-dropdown-menu");

        // Function to close all dropdowns
        function closeDropdowns() {
            moreDropdown.classList.remove("show");
            rolenameDropdown.classList.remove("show");
            usernameDropdown.classList.remove("show");
        }

        moreButton.addEventListener("mouseenter", function() {
            closeDropdowns();
            moreDropdown.classList.add("show");
        });

        rolenameButton.addEventListener("mouseenter", function() {
            closeDropdowns();
            rolenameDropdown.classList.add("show");
        });

        usernameButton.addEventListener("mouseenter", function() {
            closeDropdowns();
            usernameDropdown.classList.add("show");
        });

        moreButton.addEventListener("mouseleave", function() {
            moreDropdown.classList.remove("show");
        });

        rolenameButton.addEventListener("mouseleave", function() {
            rolenameDropdown.addEventListener("mouseenter", function() {
                rolenameDropdown.classList.add("show");
            });
            rolenameDropdown.addEventListener("mouseleave", function() {
                rolenameDropdown.classList.remove("show");
            });
        });

        usernameButton.addEventListener("mouseleave", function() {
            usernameDropdown.classList.remove("show");
        });

        // Toggle rolename dropdown on button click
        moreButton.addEventListener("click", function() {
            if (moreDropdown.classList.contains("show")) {
                moreDropdown.classList.remove("show");
            } else {
                closeDropdowns();
                moreDropdown.classList.add("show");
            }
        });

        rolenameButton.addEventListener("click", function() {
            if (rolenameDropdown.classList.contains("show")) {
                rolenameDropdown.classList.remove("show");
            } else {
                closeDropdowns();
                rolenameDropdown.classList.add("show");
            }
        });

        usernameButton.addEventListener("click", function() {
            if (usernameDropdown.classList.contains("show")) {
                usernameDropdown.classList.remove("show");
            } else {
                closeDropdowns();
                usernameDropdown.classList.add("show");
            }
        });

        // Close dropdowns when clicking anywhere on the document
        document.addEventListener("click", function() {
            closeDropdowns();
        });
    });
</script>
