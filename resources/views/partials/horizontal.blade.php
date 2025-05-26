<style>
    .dropdown-menu-scrollable {
        max-height: 80vh;
        overflow-y: auto;
    }
    .logo-img {
        width: auto;
        height: 40px;
        max-width: 100%;
        object-fit: contain;
    }
    #more-dropdown-menu .dropdown .dropdown-menu {
        left: inherit !important;
        right: 100% !important;
    }

    @supports (-webkit-touch-callout: none) {
        .logo-img {
            width: auto;
            height: 40px;
            object-fit: contain;
       }
    }

    .dropdown-item:hover {
        background-color: #1c6192 !important;
        color: white !important;
    }
    
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	/* color: black!important; */
	/* background-image: linear-gradient(to right,#4ba6ef,#4ba6ef,#0065ac)!important; */
	background: #072c47 !important;
	}

    .nav-item.dropdown.active {
        background-color:rgba(7, 44, 71, 0.88);
    }

    .nav-item.dropdown.active .nav-link.dropdown-toggle.arrow-none.active {
        color: white !important;
    }
    .nav-item.dropdown.active .nav-link.dropdown-toggle.arrow-none {
        color: white !important;
    }

    .badge-notification {
      top: -20;
      right: 0;
      transform: translate(50%, -10%);
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 0.3rem 0.6rem;
    }
    .badge-notificationing {
      top: 0;
      right: 0;
      transform: translate(50%, -10%);
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 0.3rem 0.6rem;
    }
    .approval-count {
        color:white!important;
        background-color:#fd625e!important;
        border-radius:50%!important;
        padding-right:3px!important;
        padding-left:3px!important;
    }

    #rolename-dropdown-menu {
        position: fixed; 
        z-index: 1050 !important; 
        min-width: 150px;
        max-height: 80vh;
        overflow-y: auto;
        right: 0;
        display: none; 
    }

    .container, .main-wrapper, .navbar, .header {
        overflow: visible !important; 
    }

    .username-toggle {
        background-color: #072c47 !important;
    }

    .rolename-toggle {
        background-color: #072c47 !important;
        border-color: #4ba6ef !important;

    }

    @media (max-width: 991.99px) {
    .dropdown-menu {
        right: 0;
        left: auto;
    }

    #rolename-dropdown-menu {
        top: 100%;
        left: 0;
        margin-top: 0.25rem;
        right: 30px !important;
    }
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
    @media (min-width: 992px) {
        .dropdown-menu.dropdown-menu-scrollable {
            right: 0px;
        }
        #rolename-dropdown-menu.dropdown-menu-scrollable {
            top: 55px !important;
        }
        .topnav .dropdown .dropdown-menu {
            margin-top: 0;
            border-radius: 0 0 .25rem .25rem;
            right: auto!important;
        }
    }
</style>
<script>
    function checkSession() {
        fetch('/session-status')
            .then(response => response.json())
            .then(data => {
                if (!data.authenticated) {
                    window.location.href = '/login';
                }
            })
            .catch(error => {
                console.error('Error checking session status:', error);
            });
    }

    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            checkSession(); // Check session when the page becomes visible again
        }
    });

    checkSession(); // Initial check immediately on page load
    setInterval(checkSession, 15000); // Check every 15 seconds
</script>
<div class="container">
    <div class="row topnav">
        <!-- <div class="topnav" style="overflow: unset;"> -->

        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-6 navbar-menus-main-div">

            <button type="button" class="btn btn-sm px-2 font-size-16 d-lg-none header-item waves-effect waves-light text-dark" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <div class="d-none d-lg-block">
                <div class="dropdown d-inline-block align-items-center" style="position: absolute; left: 13px; top:7px; z-index: 500;">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="mx-auto logo-img">
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



                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['company-domain-create','company-domain-list']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="grid"></i>
                                        <span data-key="t-extra-pages">Company Domains</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['company-domain-create']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('companyDomains.create')}}" id="topnav-auth" role="button">
                                                <span data-key="t-authentication">Create</span>
                                            </a>
                                        </div>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['company-domain-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('companyDomains.index') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Info </span>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </li>
                                @endif



                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-export-exw-wo','create-export-cnf-wo','create-local-sale-wo','create-lto-wo','list-export-exw-wo','view-current-user-export-exw-wo-list','view-current-user-export-exw-wo-list','list-export-cnf-wo','view-current-user-export-cnf-wo-list','list-export-local-sale-wo','view-current-user-local-sale-wo-list']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="file-text"></i>
                                        <span data-key="t-extra-pages">Work Order</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">

                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['list-export-exw-wo','view-current-user-export-exw-wo-list','list-export-exw-wo','view-current-user-export-exw-wo-list','list-export-local-sale-wo','view-current-user-local-sale-wo-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Report</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                <a href="{{route('work-order.index','status_report')}}" class="dropdown-item" data-key="t-login">Status Report</a>
                                                <a href="{{route('work-order.index','all')}}" class="dropdown-item" data-key="t-login">Full Data</a>
                                            </div>
                                        </div>
                                        @endif

                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-export-exw-wo','list-export-exw-wo','view-current-user-export-exw-wo-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Export EXW</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-export-exw-wo']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('work-order-create.create','export_exw')}}" class="dropdown-item" data-key="t-login">Create</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['list-export-exw-wo','view-current-user-export-exw-wo-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('work-order.index','export_exw') }}" class="dropdown-item" data-key="t-login">List</a>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-export-cnf-wo','list-export-cnf-wo','view-current-user-export-cnf-wo-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Export CNF</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-export-cnf-wo']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('work-order-create.create','export_cnf')}}" class="dropdown-item" data-key="t-login">Create</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-export-cnf-wo','list-export-cnf-wo','view-current-user-export-cnf-wo-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('work-order.index','export_cnf') }}" class="dropdown-item" data-key="t-login">List</a>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-export-cnf-wo','list-export-cnf-wo','view-current-user-export-cnf-wo-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Local Sale</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-local-sale-wo']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('work-order-create.create','local_sale')}}" class="dropdown-item" data-key="t-login">Create</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-local-sale-wo','list-export-local-sale-wo','view-current-user-local-sale-wo-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('work-order.index','local_sale') }}" class="dropdown-item" data-key="t-login">List</a>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-vehicle-penalty-report']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Customs Clearance</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                <a href="{{route('getBOEPenaltyReport')}}" class="dropdown-item" data-key="t-login">Penalized BOE</a>            
                                                <a href="{{route('getClearedPenalties')}}" class="dropdown-item" data-key="t-login">Cleared Penalties</a>
                                                <a href="{{route('getNoPenalties')}}" class="dropdown-item" data-key="t-login">No Penalties BOE</a>
                                            </div>
                                        </div>
                                        @endif

                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['can-view-vehicle-claims']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Claim</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                <a href="{{route('getPendingClaims')}}" class="dropdown-item" data-key="t-login">Pendings</a>            
                                                <a href="{{route('getSubmittedClaims')}}" class="dropdown-item" data-key="t-login">Submitted</a>
                                                <a href="{{route('getApprovedClaims')}}" class="dropdown-item" data-key="t-login">Approved</a>
                                                <a href="{{route('getCancelledClaims')}}" class="dropdown-item" data-key="t-login">Cancelled</a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </li>
                                @endif
                                <!-- HRM -->
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-employee-hiring-request','edit-employee-hiring-request','edit-current-user-hiring-request','view-all-pending-hiring-request-listing',
                                'view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing','view-all-on-hold-hiring-request-listing',
                                'view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing','view-pending-hiring-request-listing-of-current-user',
                                'view-approved-hiring-request-listing-of-current-user','view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
                                'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
                                'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-details-of-current-user'
                                ,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
                                ,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user'
                                ,'hiring-request-cancel-action','hiring-request-of-current-user-delete-action','hiring-request-close-action','hiring-request-on-hold-action',
                                'hiring-request-cancel-action','create-job-description'
                                ,'edit-job-description','edit-current-user-job-description','view-pending-job-description-list','view-current-user-pending-job-description-list','view-approved-job-description-list','view-current-user-approved-job-description-list','view-rejected-job-description-list','view-current-user-rejected-job-description-list','view-job-description-details','view-current-user-job-description-details','view-job-description-approvals-details','view-current-user-job-description-approvals-details',
                                'view-interview-summary-report-listing','requestedby-view-interview-summary-listing','organizedby-view-interview-summary-listing','create-interview-summary-report','requestedby-create-interview-summary','organizedby-create-interview-summary','view-division-listing','view-current-user-division'
                                ,'view-department-listing','view-current-user-department-lising','division-approval-listing','view-asset-allocation-request-listing'
                                ,'view-joining-report-listing','dept-emp-view-joining-report-listing','current-user-view-joining-report-listing','view-permanent-joining-report-listing','view-current-user-permanent-joining-report-listing','view-passport-request-list','current-user-view-passport-request-list'
                                ,'view-liability-list','current-user-view-liability-list','view-leave-list','view-current-user-leave-list'
                                ,'list-all-increment','list-current-user-increment','view-birthday-po-list','view-ticket-listing','view-ticket-listing-of-current-user','view-all-list-insurance'
                                ,'list-all-overtime','list-current-user-overtime','view-all-employee-listing']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="file-text"></i>
                                        <span data-key="t-extra-pages">
                                            HR
                                        </span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
                                        <div class="dropdown">
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-division-listing','view-current-user-division','division-approval-listing','view-department-listing','view-current-user-department-lising']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Approvals</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-division-listing','view-current-user-division']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('division.index') }}" class="dropdown-item" data-key="t-login">Division Approvals</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-department-listing','view-current-user-department-lising']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('department.index') }}" class="dropdown-item" data-key="t-login">Department Approvals</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['division-approval-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('designation-approvals.index') }}" class="dropdown-item" data-key="t-login">Designation Approvals</a>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-employee-listing']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('employee.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Employees</span>
                                                <!-- <div class="arrow-down"></div> -->
                                            </a>
                                            <!-- <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-division-listing','view-current-user-division']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('division.index') }}" class="dropdown-item" data-key="t-login">Division Approvals</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-department-listing','view-current-user-department-lising']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('department.index') }}" class="dropdown-item" data-key="t-login">Department Approvals</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['division-approval-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('designation-approvals.index') }}" class="dropdown-item" data-key="t-login">Designation Approvals</a>
                                                @endif
                                            </div> -->
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-employee-hiring-request','edit-employee-hiring-request','edit-current-user-hiring-request','view-all-pending-hiring-request-listing',
                                            'view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing','view-all-on-hold-hiring-request-listing',
                                            'view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing','view-pending-hiring-request-listing-of-current-user',
                                            'view-approved-hiring-request-listing-of-current-user','view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
                                            'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
                                            'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-details-of-current-user'
                                            ,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
                                            ,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user'
                                            ,'hiring-request-cancel-action','hiring-request-of-current-user-delete-action','hiring-request-close-action','hiring-request-on-hold-action','hiring-request-cancel-action'
                                            ,'create-questionnaire','edit-questionnaire','edit-current-user-questionnaire','view-questionnaire-details','view-current-user-questionnaire','create-job-description','edit-job-description','edit-current-user-job-description','view-pending-job-description-list','view-current-user-pending-job-description-list','view-approved-job-description-list','view-current-user-approved-job-description-list','view-rejected-job-description-list','view-current-user-rejected-job-description-list','view-job-description-details','view-current-user-job-description-details','view-job-description-approvals-details','view-current-user-job-description-approvals-details']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Employee Hiring</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-employee-hiring-request','edit-employee-hiring-request','edit-current-user-hiring-request',
                                                'view-all-pending-hiring-request-listing','view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing',
                                                'view-all-on-hold-hiring-request-listing','view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing',
                                                'view-pending-hiring-request-listing-of-current-user','view-approved-hiring-request-listing-of-current-user',
                                                'view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
                                                'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
                                                'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-details-of-current-user'
                                                ,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
                                                ,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user'
                                                ,'hiring-request-cancel-action','hiring-request-of-current-user-delete-action','hiring-request-close-action','hiring-request-on-hold-action',
                                                'hiring-request-cancel-action','create-questionnaire','edit-questionnaire','edit-current-user-questionnaire','view-questionnaire-details','view-current-user-questionnaire','create-job-description']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('employee-hiring-request.index') }}" class="dropdown-item" data-key="t-login">Hiring Requests</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-job-description','edit-job-description','edit-current-user-job-description','view-pending-job-description-list','view-current-user-pending-job-description-list','view-approved-job-description-list','view-current-user-approved-job-description-list','view-rejected-job-description-list','view-current-user-rejected-job-description-list','view-job-description-details','view-current-user-job-description-details','view-job-description-approvals-details','view-current-user-job-description-approvals-details']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('job_description.index') }}" class="dropdown-item" data-key="t-login">Job Descriptions</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing','requestedby-view-interview-summary-listing','organizedby-view-interview-summary-listing','create-interview-summary-report','requestedby-create-interview-summary','organizedby-create-interview-summary']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('interview-summary-report.index') }}" class="dropdown-item" data-key="t-login">Interview Summary</a>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-asset-allocation-request-listing']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> On Boarding</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','current-user-view-joining-report-listing','dept-emp-view-joining-report-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('employee_joining_report.index','new_employee') }}" class="dropdown-item" data-key="t-login">Joining Report</a>
                                                @endif
                                                <!--
                                                    @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-asset-allocation-request-listing']);
                                                    @endphp
                                                    @if ($hasPermission)
                                                    <a href="{{ route('asset_allocation.index') }}" class="dropdown-item" data-key="t-login">Asset Allocation</a>
                                                    @endif
                                                    -->
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole([]);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('interview-summary-report.index') }}" class="dropdown-item" data-key="t-login">HandOver Form</a>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-list','current-user-view-passport-request-list']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Passport Request</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-list','current-user-view-passport-request-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('passport_request.index') }}" class="dropdown-item" data-key="t-login">Passport Submit</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-list','current-user-view-passport-request-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('passport_release.index') }}" class="dropdown-item" data-key="t-login">Passport Release</a>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-liability-list','current-user-view-liability-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('employee_liability.index') }}" id="topnav-utility" role="button">
                                            <span data-key="t-utility">Liability</span>
                                        </a>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-leave-list','view-current-user-leave-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('employee_leave.index') }}" id="topnav-utility" role="button">
                                            <span data-key="t-utility">Leave</span>
                                        </a>
                                        @endif
                                        <div class="dropdown">
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','current-user-view-joining-report-listing','view-permanent-joining-report-listing','view-current-user-permanent-joining-report-listing','dept-emp-view-joining-report-listing']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Joining Report</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','current-user-view-joining-report-listing','dept-emp-view-joining-report-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('employee_joining_report.index','new_employee') }}" class="dropdown-item" data-key="t-login">New Employee</a>
                                                <a href="{{ route('employee_joining_report.index','temporary') }}" class="dropdown-item" data-key="t-login">Temporary Internal Transfer</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-permanent-joining-report-listing','view-current-user-permanent-joining-report-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('employee_joining_report.index','permanent') }}" class="dropdown-item" data-key="t-login">Permanent Internal Transfer</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','current-user-view-joining-report-listing','dept-emp-view-joining-report-listing']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('employee_joining_report.index','vacations_or_leave') }}" class="dropdown-item" data-key="t-login">Vacations Or Leave</a>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['list-all-increment','list-current-user-increment','view-all-list-insurance','view-birthday-po-list','view-ticket-listing','view-ticket-listing-of-current-user']);
                                            @endphp
                                            @if ($hasPermission)
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Compensation & Benefits</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['list-all-increment','list-current-user-increment']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('increment.index') }}" class="dropdown-item" data-key="t-login">Salary Increment</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-list-insurance','view-current-user-list-insurance']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('insurance.index') }}" class="dropdown-item" data-key="t-login">Insurance</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-birthday-po-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('birthday_gift.index') }}" class="dropdown-item" data-key="t-login">Birthday Gift</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-ticket-listing','view-ticket-listing-of-current-user']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('ticket_allowance.index') }}" class="dropdown-item" data-key="t-login">Ticket Allowance</a>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['list-all-overtime','list-current-user-overtime']);
                                        @endphp
                                        @if ($hasPermission)
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('overtime.index') }}" id="topnav-utility" role="button">
                                            <span data-key="t-utility">Over Time Application</span>
                                        </a>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['list-all-separation-employee-handover','list-current-user-separation-handover']);
                                        @endphp
                                        @if ($hasPermission)
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('separation-handover.index') }}" id="topnav-utility" role="button">
                                            <span data-key="t-utility">Separation Employee Handover</span>
                                        </a>
                                        @endif
                                    </div>
                                </li>
                                @endif
                                <!-- HRM -->


                                <!-- Employee Relations -->
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-menu','achievement-certificate-menu','reference-letter-menu']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="grid"></i>
                                        <span data-key="t-extra-pages">Employee Relation</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-menu','achievement-certificate-menu']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Certification</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-more">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-menu']);
                                                @endphp
                                                @if ($hasPermission)
                                                <div class="dropdown">
                                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                        <span data-key="t-utility">Salary Certification</span>
                                                        <div class="arrow-down"></div>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-create']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{ route('employeeRelation.salaryCertificate.create') }}" class="dropdown-item" data-key="t-login">Create</a>
                                                        @endif
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{ route('employeeRelation.salaryCertificate.index') }}" class="dropdown-item" data-key="t-login">List</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <div class="dropdown">
                                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                        <span data-key="t-utility">Achievement Certification</span>
                                                        <div class="arrow-down"></div>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{ route('employeeRelation.salaryCertificate.create') }}" class="dropdown-item" data-key="t-login">Create</a>
                                                        @endif
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{ route('employeeRelation.salaryCertificate.index') }}" class="dropdown-item" data-key="t-login">List</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif -->
                                            </div>
                                        </div>
                                        @endif

                                        <!-- @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Letter</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-more">
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create','warranty-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <div class="dropdown">
                                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                        <span data-key="t-utility">Reference Letter</span>
                                                        <div class="arrow-down"></div>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{ route('employeeRelation.salaryCertificate.create') }}" class="dropdown-item" data-key="t-login">Create</a>
                                                        @endif
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{ route('employeeRelation.salaryCertificate.index') }}" class="dropdown-item" data-key="t-login">List</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif -->
                                    </div>
                                </li>
                                @endif
                                <!-- End Employee Relations -->


                                <!-- @php
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
                                    </div>
                                </li>
                                @endif -->

                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create','warranty-list','addon-create','accessories-list','spare-parts-list','kit-list']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="file-text"></i>
                                        <span data-key="t-extra-pages">Vehicle Addons</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
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
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('warranty.create') }}" class="dropdown-item" data-key="t-login">Create Warranty</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-list']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('warranty.index') }}" class="dropdown-item" data-key="t-login">Warranty Info</a>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

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
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-create']);
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('addon.create') }}" class="dropdown-item" data-key="t-login">Create Addon</a>
                                                <a href="{{ route('kit.create') }}" class="dropdown-item" data-key="t-login">Create Kit</a>
                                                @endif
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
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['accessories-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{route('addon.list','P')}}" class="dropdown-item" data-key="t-login">Accessories</a>
                                                        @endif

                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['spare-parts-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{route('addon.list','SP')}}" class="dropdown-item" data-key="t-login">Spare Parts</a>
                                                        @endif
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['kit-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{route('addon.list','K')}}" class="dropdown-item" data-key="t-login">Kits</a>
                                                        @endif
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['accessories-list','spare-parts-list','kit-list']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <a href="{{route('addon.list','all')}}" class="dropdown-item" data-key="t-login">All</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </li>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-selling-price-approve','approve-addon-new-selling-price','supplier-price-action','verify-candidate-personal-information','send-personal-info-form-action']);
                                @endphp
                                <!-- UNCOMMENT BELOW LINE WHEN HR MODULE GO LIVE -->
                                <!-- OR Auth::user()->passport_submit_request_approval['can'] == true OR Auth::user()->passport_release_request_approval['can'] == true  -->
                                @if ($hasPermission OR Auth::user()->leave_request_approval['can'] == true OR Auth::user()->liability_request_approval['can'] == true
                                OR Auth::user()->hiring_request_approval['can'] == true OR Auth::user()->job_description_approval['can'] == true OR Auth::user()->candidate_docs_varify OR Auth::user()->candidate_personal_information_varify > 0 OR Auth::user()->joining_report_approval['count'])
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="grid"></i>
                                        <span data-key="t-extra-pages">Approvals @if((Auth::user()->joining_report_approval['count']+Auth::user()->candidate_personal_information_varify+Auth::user()->candidate_docs_varify+Auth::user()->overtime_request_approval['count']+Auth::user()->liability_request_approval['count']+Auth::user()->leave_request_approval['count']+Auth::user()->passport_submit_request_approval['count']+Auth::user()->passport_release_request_approval['count']+Auth::user()->hiring_request_approval['count']+Auth::user()->job_description_approval['count']+Auth::user()->interview_summary_report_approval['count']+Auth::user()->verify_offer_letters) > 0)
                                            <span class="approval-count">{{Auth::user()->overtime_request_approval['count']+Auth::user()->liability_request_approval['count']+Auth::user()->leave_request_approval['count']+Auth::user()->passport_submit_request_approval['count']+Auth::user()->passport_release_request_approval['count']+Auth::user()->hiring_request_approval['count']+Auth::user()->job_description_approval['count']+Auth::user()->interview_summary_report_approval['count']+Auth::user()->candidate_docs_varify+Auth::user()->candidate_personal_information_varify+Auth::user()->verify_offer_letters+Auth::user()->joining_report_approval['count']}}</span>
                                            @endif
                                        </span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
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

                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-candidates-documents','send-candidate-documents-request-form','verify-offer-letter-signature','verify-candidate-personal-information','send-personal-info-form-action']);
                                        @endphp
                                        @if(($hasPermission && (Auth::user()->can_show_offer_letter == true OR Auth::user()->can_show_info == true OR Auth::user()->can_show_docs == true OR Auth::user()->can_show_summary == true) )OR Auth::user()->hiring_request_approval['can'] == true OR Auth::user()->job_description_approval['can'] == true OR (Auth::user()->interview_summary_report_approval == true && Auth::user()->can_show_summary == true) OR (Auth::user()->candidate_docs_varify > 0 && Auth::user()->can_show_docs == true) OR (Auth::user()->candidate_personal_information_varify > 0 && Auth::user()->can_show_info == true))
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Employee Hiring @if((Auth::user()->candidate_personal_information_varify+Auth::user()->candidate_docs_varify+Auth::user()->hiring_request_approval['count']+Auth::user()->job_description_approval['count']+Auth::user()->interview_summary_report_approval['count']+Auth::user()->verify_offer_letters) > 0)
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
                                                @if(Auth::user()->job_description_approval['can'] == true)
                                                <a href="{{ route('employee-hiring-job-description.approval-awaiting') }}" class="dropdown-item" data-key="t-login">Job Descriptions
                                                    @if(Auth::user()->job_description_approval['count'] > 0) <span class="approval-count">{{Auth::user()->job_description_approval['count']}}<span> @endif
                                                </a>
                                                @endif
                                                @if(Auth::user()->interview_summary_report_approval == true && Auth::user()->can_show_summary == true)
                                                <a href="{{ route('interview-summary-report.approval-awaiting') }}" class="dropdown-item" data-key="t-login">InterviewSummary @if(Auth::user()->interview_summary_report_approval['count'] > 0)<span class="approval-count">{{Auth::user()->interview_summary_report_approval['count']}}</span> @endif
                                                </a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-candidates-documents','send-candidate-documents-request-form']);
                                                @endphp
                                                @if ($hasPermission && Auth::user()->can_show_docs == true)
                                                <a href="{{ route('candidate.listDocs') }}" class="dropdown-item" data-key="t-login">Candidate Docs @if(Auth::user()->candidate_docs_varify > 0)<span class="approval-count">{{Auth::user()->candidate_docs_varify}}</span> @endif</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-offer-letter-signature']);
                                                @endphp
                                                @if ($hasPermission && Auth::user()->can_show_offer_letter == true)
                                                <a href="{{ route('candidate.listOfferLetter') }}" class="dropdown-item" data-key="t-login">Offer Letter @if(Auth::user()->verify_offer_letters > 0)<span class="approval-count">{{Auth::user()->verify_offer_letters}}</span> @endif</a>
                                                @endif
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-candidate-personal-information','send-personal-info-form-action']);
                                                @endphp
                                                @if ($hasPermission && Auth::user()->can_show_info == true)
                                                <a href="{{ route('candidate.listingInfo') }}" class="dropdown-item" data-key="t-login">Candidate Info @if(Auth::user()->candidate_personal_information_varify > 0)<span class="approval-count">{{Auth::user()->candidate_personal_information_varify}}</span> @endif</a>
                                                @endif
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
                                        <!-- CAN UNCOMMENT WHEN PASSPORT REQUEST MODULE GET USE -->
                                        <!-- @if(Auth::user()->passport_submit_request_approval['can'] == true OR Auth::user()->passport_release_request_approval['can'] == true)
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
                                    @endif -->

                                        @if(Auth::user()->liability_request_approval['can'] == true OR Auth::user()->leave_request_approval['can'] == true OR Auth::user()->overtime_request_approval['can'] == true)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Employee @if((Auth::user()->liability_request_approval['count']) > 0 OR (Auth::user()->leave_request_approval['count']) > 0 OR (Auth::user()->overtime_request_approval['count']) > 0)
                                                    <span class="approval-count">{{Auth::user()->liability_request_approval['count']+Auth::user()->leave_request_approval['count']+Auth::user()->overtime_request_approval['count']}}</span>
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
                                                @if(Auth::user()->overtime_request_approval['can'] == true)
                                                <a href="{{ route('overtime.approvalAwaiting') }}" class="dropdown-item" data-key="t-login">Overtime
                                                    @if(Auth::user()->overtime_request_approval['count'] > 0) <span class="approval-count">{{Auth::user()->overtime_request_approval['count']}}</span> @endif
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        @if(Auth::user()->joining_report_approval['can'] == true)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('joiningReport.approvalAwaiting') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility"> Joining Report @if((Auth::user()->joining_report_approval['count']) > 0)
                                                    <span class="approval-count">{{Auth::user()->joining_report_approval['count']}}</span>
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
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('calls.leadsexport') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Leads Export</span>
                                            </a>
                                        </div>
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('calls.datacenter') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Google Review</span>
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

                                @can('pre-order')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('pre-order');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('preorder.index') }}" id="topnav-more" role="button">
                                        <i data-feather="award"></i>
                                        <span data-key="t-extra-pages">Pre Order</span>
                                    </a>
                                </li>
                                @endif
                                @endcan
                              
                                            <!-- po List -->
                                @canany(['view-po-details','demand-planning-po-list','create-po-details'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-po-details','demand-planning-po-list','create-po-details']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="award"></i>
                                        <span data-key="t-extra-pages"> @if(Auth::user()->empProfile->department->is_demand_planning == 1) DP @endif  Purchase Order</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                        @can('create-po-details')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-po-details');
                                        @endphp
                                            @if ($hasPermission)
                                            <a href="{{ route('purchasing-order.create') }}" class="dropdown-item" data-key="t-login">Create New</a>
                                            @endif
                                        @endcan
                                        
                                        <!-- Add summary Menu -->
                                        @canany(['view-po-details','demand-planning-po-list'])
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-po-details','demand-planning-po-list']);
                                        @endphp
                                            @if ($hasPermission)
                                                <a href="{{ route('purchasing-order.index') }}" class="dropdown-item" data-key="t-login"> List </a>
                                            @endif
                                        @endcanany
                                    </div>
                                </li>
                                @endif
                                @endcanany
                                            <!-- po end -->

                                @can('view-po-details')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po-details');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('grn.index') }}" id="topnav-more" role="button">
                                        <i data-feather="command"></i>
                                        <span data-key="t-extra-pages">GRN</span>
                                    </a>
                                </li>
                                @endif
                                @endcan

                                              <!-- LOI -->
                                @canany(['LOI-create','LOI-list','list-loi-expiry-conditions','loi-restricted-country-list'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['LOI-create','LOI-list','list-loi-expiry-conditions','loi-restricted-country-list']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="file"></i>
                                        <span data-key="t-extra-pages"> LOI</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                        @can('LOI-create')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-create');
                                        @endphp
                                            @if ($hasPermission)
                                            <a href="{{route('letter-of-indents.create')}}" class="dropdown-item" data-key="t-login">Add New</a>
                                            @endif
                                        @endcan

                                        @can('LOI-list')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
                                        @endphp
                                            @if ($hasPermission)
                                            <a href="{{ route('letter-of-indents.index') }}" class="dropdown-item" data-key="t-login"> List </a>
                                            @endif
                                        @endcan

                                        @can('loi-restricted-country-list')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-list');
                                        @endphp
                                            @if ($hasPermission)
                                            <a href="{{route('loi-country-criterias.index')}}" class="dropdown-item" data-key="t-login"> Country Restrictions </a>
                                            @endif
                                        @endcan
                                        
                                        @can('list-loi-expiry-conditions')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-loi-expiry-conditions');
                                        @endphp
                                            @if ($hasPermission)
                                            <a href="{{route('loi-expiry-conditions.index')}}" class="dropdown-item" data-key="t-login"> Expiry </a>
                                            @endif
                                        @endcan
                                    </div>
                                </li>
                                @endif
                                @endcanany
                                <!-- END LOI -->

                                  <!-- PFI -->
                                  @canany(['PFI-create','PFI-list'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['PFI-create','PFI-list']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="book-open"></i>
                                        <span data-key="t-extra-pages"> PFI</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                        @can('PFI-create')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-create');
                                        @endphp
                                            @if ($hasPermission)
                                            <a href="{{route('pfi.create')}}" class="dropdown-item" data-key="t-login">Add New</a>
                                            @endif
                                        @endcan

                                        @can('PFI-list')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-list');
                                        @endphp
                                            @if ($hasPermission)
                                            <a href="{{ route('pfi.index') }}" class="dropdown-item" data-key="t-login"> List </a>
                                            @endif
                                        @endcan
                                    </div>
                                </li>
                                @endif
                                @endcanany
                                <!-- END PFI -->

                                   <!-- Customers -->
                                   @canany(['create-customer','list-customer'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-customer','list-customer']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="user"></i>
                                        <span data-key="t-extra-pages"> Customers</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                        @can('create-customer')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-customer');
                                        @endphp
                                            @if ($hasPermission)
                                            <a href="{{ route('dm-customers.index') }}" class="dropdown-item" data-key="t-login">Add New</a>
                                            @endif
                                        @endcan

                                        @can('list-customer')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-customer');
                                        @endphp
                                            @if ($hasPermission)
                                            <a href="{{route('dm-customers.index')}}" class="dropdown-item" data-key="t-login"> List </a>
                                            @endif
                                        @endcan
                                    </div>
                                </li>
                                @endif
                                @endcanany
                                    <!-- end customers -->

                                            <!-- Vehicles -->
                                @canany(['stock-full-view','variants-view','master-brand-list','view-model-lines-list','list-master-models',
                                                'create-master-models'])
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['stock-full-view','variants-view','master-brand-list',
                                            'view-model-lines-list','list-master-models','create-master-models']);
                                    @endphp
                                    @if ($hasPermission)
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                                <i data-feather="grid"></i>
                                                <span data-key="t-extra-pages">Vehicles</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                            @can('stock-full-view')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
                                                @endphp
                                                    @if ($hasPermission)
                                                        <a href="{{route('vehicles.currentstatus')}}" class="dropdown-item" data-key="t-login">Track Status</a>
                                                    @endif
                                                @endcan
                                                @canany(['list-master-models','create-master-models'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-master-models','list-master-models']);
                                                @endphp
                                                    @if ($hasPermission)
                                                    <div class="dropdown">
                                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                            <span data-key="t-utility">@if(Auth::user()->empProfile->department->is_demand_planning == 1) DP @endif Vendor Model</span>
                                                            <div class="arrow-down"></div>
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                            @can('create-master-models')
                                                            @php
                                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-master-models');
                                                            @endphp
                                                            @if ($hasPermission)
                                                                <a href="{{route('master-models.create')}}" class="dropdown-item" data-key="t-login">
                                                                    Add New
                                                                </a>
                                                            @endif
                                                            @endcan
                                                            
                                                            @can('list-master-models')
                                                                @php
                                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-master-models');
                                                            @endphp
                                                                @if ($hasPermission)
                                                                <a href="{{route('master-models.index')}}" class="dropdown-item" data-key="t-login">
                                                                    List
                                                                </a>
                                                                @endif
                                                            @endcan
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endcanany
                                            @can('stock-full-view')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
                                                @endphp
                                                    @if ($hasPermission)
                                                        <div class="dropdown">
                                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                                <span data-key="t-utility">Stock</span>
                                                                <div class="arrow-down"></div>
                                                            </a>
                                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                                <a href="{{route('vehicles.availablevehicles')}}" class="dropdown-item" data-key="t-login">
                                                                    Incoming & In-stock
                                                                </a>
                                                                <a href="{{route('vehicles.deliveredvehicles')}}" class="dropdown-item" data-key="t-login">
                                                                    Delivered
                                                                </a>
                                                                <a href="{{route('vehicles.statuswise')}}" class="dropdown-item" data-key="t-login">
                                                                    List All
                                                                </a>
                                                                <a href="{{route('vehicles.dpvehicles')}}" class="dropdown-item" data-key="t-login">
                                                                    DP List All
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endcan
                                                @canany(['variants-view','master-brand-list','view-model-lines-list'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['variants-view','master-brand-list','view-model-lines-list']);
                                                @endphp
                                                    @if ($hasPermission)
                                                        <div class="dropdown">
                                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                                <span data-key="t-utility">@if(Auth::user()->empProfile->department->is_demand_planning == 1) DP Milele @endif  Variants</span>
                                                                <div class="arrow-down"></div>
                                                            </a>
                                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                                    @can('master-brand-list')
                                                                    @php
                                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-brand-list');
                                                                    @endphp
                                                                        @if ($hasPermission)
                                                                            <a href="{{ route('brands.index') }}" class="dropdown-item" data-key="t-login">
                                                                             @if(Auth::user()->empProfile->department->is_demand_planning == 1) DP @endif  Brands
                                                                            </a>
                                                                        @endif
                                                                    @endcan
                                                                    @can('view-model-lines-list')
                                                                    @php
                                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-model-lines-list');
                                                                    @endphp
                                                                        @if ($hasPermission)
                                                                        <a href="{{route('model-lines.index')}}" class="dropdown-item" data-key="t-login">
                                                                        @if(Auth::user()->empProfile->department->is_demand_planning == 1) DP @endif Model Lines
                                                                        </a>
                                                                        @endif
                                                                    @endcan

                                                                    @can('model-description-info')
                                                                    @php
                                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-description-info');
                                                                    @endphp
                                                                        @if ($hasPermission)
                                                                        <a href="{{ route('modeldescription.index') }}" class="dropdown-item" data-key="t-login">
                                                                        @if(Auth::user()->empProfile->department->is_demand_planning == 1) DP @endif  Model Descriptions
                                                                        </a>
                                                                    @endif
                                                                    @endcan 

                                                                    @can('variants-view')
                                                                    @php
                                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-view');
                                                                    @endphp
                                                                        @if ($hasPermission)
                                                                            <a href="{{ route('variants.index') }}" class="dropdown-item" data-key="t-login">
                                                                            @if(Auth::user()->empProfile->department->is_demand_planning == 1) DP @endif  Variants
                                                                            </a>
                                                                        @endif
                                                                    @endcan
                                                                </div>
                                                        </div>
                                                    @endif
                                                @endcan
                                            </div>
                                        </li>
                                    @endif
                                @endcanany

                                            <!-- end vehicles -->
                                          
                                            <!-- Vendor -->
                                @canany(['vendor-view','demand-planning-supplier-list','addon-supplier-create','addon-supplier-list'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['vendor-view','demand-planning-supplier-list','addon-supplier-create','addon-supplier-list']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="user"></i>
                                        <span data-key="t-extra-pages"> 
                                        @can('demand-planning-supplier-list')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-planning-supplier-list');
                                        @endphp
                                        @if ($hasPermission)
                                             DP
                                        @endif
                                        @endcan
                                         Vendors</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                        @canany(['addon-supplier-create'])
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-supplier-create']);
                                        @endphp
                                            @if ($hasPermission)
                                                <a  class="dropdown-item"  href="{{ route('suppliers.create') }}" id="topnav-auth">
                                                    <span data-key="t-authentication">Add New</span>
                                                </a>
                                            @endif
                                        @endcanany

                                        @canany(['vendor-view','addon-supplier-list','demand-planning-supplier-list'])
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['vendor-view','addon-supplier-list','demand-planning-supplier-list']);
                                        @endphp
                                            @if ($hasPermission)
                                                <a href="{{ route('suppliers.index') }}" class="dropdown-item" data-key="t-login">List</a>
                                            @endif
                                        @endcanany
                                    </div>
                                </li>
                                @endif
                                @endcanany
                                             <!-- end vendor -->

                                @php
                                $hasFullAccess = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
                                $hasLeadsViewOnly = Auth::user()->hasPermissionForSelectedRole('leads-view-only');
                                $hasSalesView = Auth::user()->hasPermissionForSelectedRole('sales-view');
                            @endphp

                            @if ($hasFullAccess || $hasLeadsViewOnly || $hasSalesView)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('dailyleads.index') }}" id="topnav-more" role="button">
                                        <i data-feather="film"></i>
                                        <span data-key="t-extra-pages">Leads</span>
                                    </a>
                                </li>
                                @if ($hasFullAccess || $hasSalesView)
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('salesorder.index') }}" id="topnav-more" role="button">
                                            <i data-feather="check-circle"></i>
                                            <span data-key="t-extra-pages">Sales Order</span>
                                        </a>
                                    </li>
                                @endif
                            @endif
                                <!-- @can('sales-view') -->
                                <!-- @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-view');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('salestargets.index') }}" id="topnav-more" role="button">
                                        <i data-feather="crosshair"></i>
                                        <span data-key="t-extra-pages">Sales Targets</span>
                                    </a>
                                </li>
                                @endif
                                @endcan -->
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('fin-vehicle-invoice');
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('vehicleinvoice.index') }}" id="topnav-more" role="button">
                                        <i data-feather="check-circle"></i>
                                        <span data-key="t-extra-pages">Vehicle Delivery Invoice</span>
                                    </a>
                                </li>
                                @endif
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
                                <!-- Demand planning Module -->
<!-- 
                                @canany(['demand-list','supplier-inventory-list','supplier-inventory-edit','supplier-inventory-list-with-date-filter',
                                'supplier-inventory-report-view','demand-planning-supplier-list','LOI-list','PFI-list',
                                'model-year-calculation-rules-list','model-year-calculation-categories-list','list-customer',
                                'list-master-models','list-color-code'])
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['demand-list','supplier-inventory-list','supplier-inventory-edit','supplier-inventory-list-with-date-filter',
                                'supplier-inventory-report-view','demand-planning-supplier-list','LOI-list','PFI-list',
                                'model-year-calculation-rules-list','model-year-calculation-categories-list','list-customer',
                                'list-master-models','list-color-code']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="file-text"></i>
                                        <span data-key="t-extra-pages">Demand & Planning</span>
                                        <div class="arrow-down"></div>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
                                        @canany(['model-year-calculation-rules-list','model-year-calculation-categories-list','list-customer',
                                        'list-master-models','list-color-code','demand-planning-supplier-list','list-list-loi-expiry-conditions'])
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['model-year-calculation-rules-list',
                                        'model-year-calculation-categories-list','list-customer','list-master-models','list-color-code',
                                        'list-list-loi-expiry-conditions','demand-planning-supplier-list']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Master Data</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @can('demand-planning-supplier-list')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-planning-supplier-list');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{ route('suppliers.index') }}" class="dropdown-item" data-key="t-login">Vendors</a>

                                                @endif
                                                @endcan
                                                @can('list-customer')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-customer');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('dm-customers.index')}}" class="dropdown-item" data-key="t-login"> Customers </a>
                                                @endif
                                                @endcan
                                                @can('list-color-code')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-color-code');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('colourcode.index')}}" class="dropdown-item" data-key="t-login"> Colours </a>
                                                @endif
                                                @endcan
                                                @can('list-master-models')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-master-models');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('master-models.index')}}" class="dropdown-item" data-key="t-login"> List Models </a>
                                                @endif
                                                @endcan
                                                @can('list-loi-mapping-criterias')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-loi-mapping-criterias');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('loi-mapping-criterias.index')}}" class="dropdown-item" data-key="t-login"> LOI Mapping Months </a>
                                                @endif
                                                @endcan
                                                @can('loi-restricted-country-list')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-list');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('loi-country-criterias.index')}}" class="dropdown-item" data-key="t-login"> LOI Country Criterias </a>
                                                @endif
                                                @endcan
                                                @can('list-loi-expiry-conditions')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-loi-expiry-conditions');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('loi-expiry-conditions.index')}}" class="dropdown-item" data-key="t-login"> LOI Expiry Conditions</a>
                                                @endif
                                                @endcan
                                                @canany(['model-year-calculation-rules-list','model-year-calculation-categories-list'])
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['model-year-calculation-rules-list','model-year-calculation-categories-list']);
                                                @endphp
                                                @if ($hasPermission)
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
                                                @endif
                                                @endcan

                                            </div>
                                        </div>
                                        @endif
                                        @endcanany
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
                                                @can('supplier-inventory-list-view-all')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list-view-all');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('supplier-inventories.view-all')}}" class="dropdown-item" data-key="t-login">Inventory Stock</a>
                                                @endif
                                                @endcan
                                                @can('supplier-inventory-list-with-date-filter')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list-with-date-filter');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('supplier-inventories.lists')}}" class="dropdown-item" data-key="t-login">Date Filter</a>
                                                @endif
                                                @endcan
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

                                        @canany(['PFI-list','PFI-create'])
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['PFI-list','PFI-create']);
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                <span data-key="t-utility">PFI</span>
                                                <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                @can('PFI-list')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-list');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('pfi.index')}}" class="dropdown-item" data-key="t-login">List PFI </a>
                                                @endif
                                                @endcan
                                                @can('PFI-create')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-create');
                                                @endphp
                                                @if ($hasPermission)
                                                <a href="{{route('pfi.create')}}" class="dropdown-item" data-key="t-login">Add New PFI Details </a>
                                                @endif
                                                @endcan
                                            </div>
                                        </div>
                                        @endif
                                        @endcan
                                    </div>
                                </li>
                                @endif
                                @endcanany -->

                                <!-- Demand Planning Module end -->
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
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po-details');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('paymentterms.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Payment Terms</span>
                                            </a>
                                        </div>
                                        @endif
                                        <!-- @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('variant-view');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('variants.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Variants </span>
                                            </a>
                                        </div>
                                        @endif -->
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-netsuite-price');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('vehiclenetsuitecost.index') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Netsuite Vehicle Price</span>
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
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-bank-accounts');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('bankaccounts.index') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Bank Accounts</span>
                                            </a>
                                        </div>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('vendor-accounts');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('vendoraccount.index') }}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Vendor Accounts</span>
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
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-brand-list');
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
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicle-selling-price');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('variantprices.allvariantprice')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Variant Price </span>
                                            </a>
                                        </div>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-grades-list');
                                        @endphp
                                        @if ($hasPermission)
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('mastergrade.index')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Grades </span>
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
                                        <span data-key="t-extra-pages">Lead Sources</span>
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
                                <!-- @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['stock-full-view']);
                                @endphp
                                @if ($hasPermission)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                        <i data-feather="server"></i>
                                        <span data-key="t-extra-pages">Stock Report</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-more">
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('vehicles.availablevehicles')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Incoming & Available Vehicles</span>
                                            </a>
                                        </div>
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('vehicles.deliveredvehicles')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">Delivered Vehicles</span>
                                            </a>
                                        </div>
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('vehicles.dpvehicles')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">DP Vehicles</span>
                                            </a>
                                        </div>
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('vehicles.statuswise')}}" id="topnav-utility" role="button">
                                                <span data-key="t-utility">All Vehicles</span>
                                            </a>
                                        </div>
                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('vehicles.currentstatus')}}" id="topnav-utility" role="button">
                                                <span data-key="t-vehicles">Vehicle Current Status</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                @endif -->
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

        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-6 rolename-username-main-div">
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
                @php
                    $assignedRoles = $assignedRoles->sortBy('name');
                @endphp
                <div class="nav-item rolename-button pb-2 pt-2" id="rolename-dropdown-button">
                    <button class="btn rolename-toggle btn-success" id="rolename-dropdown">
                        @php
                        $selectedrole = Auth::user()->selectedRole;
                        $selected = DB::table('roles')->where('id', $selectedrole)->first();
                        $roleselected = $selected ? $selected->name : null;
                        @endphp
                        {{ $roleselected }}
                    </button>
                    <div id="rolename-dropdown-menu" class="dropdown-menu dropdown-menu-end dropdown-menu-scrollable">
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
                    <button class="btn username-toggle header-item bg-soft-light" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 55px;">
                        <img class="rounded-circle header-profile-user" src="{{ auth()->user()->empProfile && auth()->user()->empProfile->image_path ? asset(auth()->user()->empProfile->image_path) : asset('images/users/avatar-1.jpg') }}" alt="Header Avatar" style="float: left;padding: 0px!important;">
                        <span class="d-none d-xl-inline-block fw-medium user-textname-div" style="line-height: 35px;">
                            @php
                            $userName = auth()->user()->name;
                            $maxNameLength = 12;
                            @endphp
                            @if (strlen($userName) <= $maxNameLength) {{ $userName }} @else {{ substr($userName, 0, $maxNameLength) . '..' }} @endif </span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div id="username-dropdown-menu" class="dropdown-menu dropdown-menu-end" style="top: 100%;">
                        <!-- <a class="dropdown-item" href="{{ route('profile.index') }}">
                            <i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profile
                        </a> -->
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
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('lead-notification');
                        @endphp
                        @if ($hasPermission)
                        <a class="dropdown-item" href="{{ route('leadsnotifications.index') }}">
                            <i class="fa fa-bullhorn" aria-hidden="true"></i> Notifications
                            @php
                            $notificationcount = DB::table('leads_notifications')
                            ->where('user_id', Auth::user()->id)
                            ->where('status', 'New')
                            ->count();
                            @endphp
                            <span class="badge badge-danger row-badge2 badge-notificationing">{{$notificationcount}}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('department-notification');
                        @endphp
                        @if ($hasPermission)
                        <a class="dropdown-item" href="{{ route('departmentnotifications.index') }}">
                            <i class="fa fa-bullhorn" aria-hidden="true"></i> Notifications
                            @php
                            $userDepartmentId = auth()->user()->empProfile->department_id;

                            $departmentnotificationscount = \App\Models\DepartmentNotifications::whereHas('departments', function($query) use ($userDepartmentId) {
                            $query->where('master_departments_id', $userDepartmentId);
                            })->whereDoesntHave('viewedLogs', function($query) {
                            $query->where('users_id', auth()->id());
                            })->count();
                            @endphp
                            <span class="badge badge-danger row-badge2 badge-notificationing">{{$departmentnotificationscount}}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
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
        const rolenameButton = document.querySelector(".rolename-button");
        const rolenameDropdown = document.getElementById("rolename-dropdown-menu");

        function isMobile() {
            return window.innerWidth < 992; 
        }

        rolenameButton.addEventListener("click", function(event) {
            event.stopPropagation();
            toggleDropdownVisibility();
        });

        if (!isMobile()) {
            rolenameButton.addEventListener("mouseenter", function() {
                showDropdown();
                adjustDropdownPosition(rolenameButton, rolenameDropdown);
            });

            rolenameButton.addEventListener("mouseleave", function() {
                setTimeout(hideDropdownIfOutside, 200);
            });

            rolenameDropdown.addEventListener("mouseleave", function() {
                setTimeout(hideDropdownIfOutside, 200); 
            });

            rolenameDropdown.addEventListener("mouseenter", function() {
                showDropdown(); 
            });

        }

        document.addEventListener("click", function() {
            hideDropdown();
        });

        function toggleDropdownVisibility() {
            if (window.innerWidth < 992) {
                const isVisible = rolenameDropdown.style.display === "block";
                hideDropdown(); 
                if (!isVisible) {
                    showDropdown();
                    adjustDropdownPosition(rolenameButton, rolenameDropdown);
                }
            }

            else {
                if (rolenameDropdown.style.display === "block") {
                    hideDropdown();
                } else {
                    showDropdown();
                    adjustDropdownPosition(rolenameButton, rolenameDropdown);
                }
            }
        }

        function showDropdown() {
            rolenameDropdown.style.display = "block";
        }

        function hideDropdown() {
            rolenameDropdown.style.display = "none";
        }

        function hideDropdownIfOutside() {
            if (!rolenameDropdown.matches(":hover") && !rolenameButton.matches(":hover")) {
                hideDropdown();
            }
        }

        function adjustDropdownPosition(button, dropdown) {
            const buttonRect = button.getBoundingClientRect();

            dropdown.style.top = `${buttonRect.bottom + window.scrollY}px`;
            dropdown.style.left = `${buttonRect.left + window.scrollX}px`;

            if (buttonRect.right + dropdown.offsetWidth > window.innerWidth) {
                dropdown.style.left = `${window.innerWidth - dropdown.offsetWidth - 10}px`;
            }
        }

        window.addEventListener("resize", function() {
            if (rolenameDropdown.style.display === "block") {
                adjustDropdownPosition(rolenameButton, rolenameDropdown);
            }
        });
    });
</script>

