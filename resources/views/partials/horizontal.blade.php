<style>
    @media only screen and (max-device-width: 280px)
    {
        .responsiveButton
        {
            position: absolute; left: 50px; z-index: 500; top: 10px;
        }
    }
    @media only screen and (max-device-width: 1280px)
    {
        .responsiveButton
        {
            position: absolute; right: 50px; z-index: 500; top: 10px;
        }
    }
    @media only screen and (min-device-width: 1280px)
    {
        .responsiveButton
        {
            position: absolute; right: 161px; z-index: 500; top: 10px;
        }
    }
</style>
<div class="topnav" style="overflow: unset;">
    <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light text-dark" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
        <i class="fa fa-fw fa-bars"></i>
    </button>
    <div class="dropdown d-inline-block responsiveButton">
    <div class="cart-icon-containerss">
        @php
        $selectedrole = Auth::user()->selectedRole;
        $selected = DB::table('roles')->where('id', $selectedrole)->first();
        $roleselected = $selected ? $selected->name : null;
        @endphp
        <button type="button" class="btn btn-success">{{ $roleselected }}</button>
    </div>
    <div class="dropdown-menu">
        @foreach ($assignedRoles as $role)
            <a class="dropdown-item" href="{{ route('users.updateRole', $role->id) }}">
                <i class="fa fa-user-circle" aria-hidden="true"></i> {{ $role->name }}
            </a>
            <div class="dropdown-divider">
            </div>
        @endforeach
    </div>
</div>
<div class="d-none d-lg-block">
<div class="dropdown d-inline-block" style="position: absolute; left: 0px; z-index: 500;">
<img src="{{ asset('logo.png') }}" width="35" height="53" alt="Logo">
</div>
</div>
    <div class="dropdown d-inline-block" style="position: absolute; right: 0px; z-index: 500;">
<!-- <div class="cart-icon-container">
  <a href=""><i class="fa fa-bell fa-2x" aria-hidden="true"></i></a>
  <span class="cart-icon-number"></span>
</div> -->
        <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 55px;">
            <img class="rounded-circle header-profile-user" src="{{asset ('images/users/avatar-1.jpg')}}" alt="Header Avatar" style="float: left;">
            <span class="d-none d-xl-inline-block ms-1 fw-medium" style="line-height: 35px;">
            @if(auth()->user()->name) {{ auth()->user()->name }} @endif
        </span>
        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
    <a class="dropdown-item" href="{{ route('profile.index') }}">
        <i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profile
    </a>
    <div class="dropdown-divider"></div>
    @canany(['user-list-active', 'user-list-inactive', 'user-list-deleted', 'user-create'])
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-list-active','user-list-inactive','user-list-deleted','user-create']);
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
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['role-list','role-create']);
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
            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-module-list', 'master-module-create','master-module-edit']);
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
            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-permission-list', 'master-permission-create','master-permission-edit']);
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
    <a class="dropdown-item" href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="mdi mdi-logout font-size-16 align-middle me-1"></i>Logout
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </a>
</div>
               </div>
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav ">
                    <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="/" id="topnav-more" role="button">
                            <i data-feather="home"></i>
                            <span data-key="t-extra-pages">Dashboard</span>
                        </a>
                    </li>
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


                        <!-- <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="grid"></i>
                            <span data-key="t-extra-pages">Approvals</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
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
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('suppliers.index') }}" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Warranties</span>
                                </a>
                            </div>
                        </div>
                    </li> -->


                    @can('Calls-view')
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
                    @endphp
                    @if ($hasPermission)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('calls.index') }}" id="topnav-more" role="button">
                            <i data-feather="phone-call"></i>
                            <span data-key="t-extra-pages">Messages & Calls</span>
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
                    <!-- @can('stock-full-view')
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
                    @endphp
                    @if ($hasPermission)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('Vehicles.index') }}" id="topnav-more" role="button">
                            <i data-feather="sliders"></i>
                            <span data-key="t-extra-pages"> Stock Report</span>
                        </a>
					</li>
                    @endif
                    @endcan -->
                    <!-- @can('edit-po-details')
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-details');
                    @endphp
                    @if ($hasPermission)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="sliders"></i>
                            <span data-key="t-extra-pages">Supplier</span>
                        </a>
					</li>
                    @endif
                    @endcan -->
                    @can('variants-view')
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-view');
                    @endphp
                    @if ($hasPermission)
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('variant_pictures.index') }}" id="topnav-more" role="button">
                            <i data-feather="film"></i>
                            <span data-key="t-extra-pages">Add Pictures & Videos</span>
                        </a>
					</li> -->
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
                    <!-- @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-picture-view');
                    @endphp
                    @if ($hasPermission)
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('vehicle-pictures.index') }}" id="topnav-more" role="button">
                            <i data-feather="film"></i>
                            <span data-key="t-extra-pages">QC Pictures Upload</span>
                        </a>
                    </li>
                    @endif -->
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
                                @can('demand-planning-supplier-create')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-planning-supplier-create');
                                    @endphp
                                    @if ($hasPermission)
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('suppliers.index') }}"
                                           id="topnav-auth" role="button" >
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
                                                    <a href="{{route('letter-of-indents.get-suppliers-LOIs')}}" class="dropdown-item" data-key="t-login">Supplier LOIs </a>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                    @endif
                                @endcan
                                    @can('list-master-models')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-master-models');
                                        @endphp
                                        @if ($hasPermission)
                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                    <span data-key="t-utility">Master Model</span>
                                                    <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                    @can('create-master-models')
                                                        @php
                                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-master-models');
                                                        @endphp
                                                        @if ($hasPermission)
                                                            <a href="{{route('master-models.create')}}" class="dropdown-item" data-key="t-login">Add New</a>
                                                        @endif
                                                    @endcan
                                                    @can('list-master-models')
                                                        @php
                                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-master-models');
                                                        @endphp
                                                        @if ($hasPermission)
                                                            <a href="{{route('master-models.index')}}" class="dropdown-item" data-key="t-login"> Lists</a>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </div>
                                        @endif
                                    @endcan

                                    @canany(['supplier-inventory-list','supplier-inventory-edit','supplier-inventory-list-with-date-filter','supplier-inventory-report-view'])
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
{{--                                                @can('supplier-inventory-list-with-date-filter')--}}
{{--                                                    @php--}}
{{--                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-list-with-date-filter');--}}
{{--                                                    @endphp--}}
{{--                                                    @if ($hasPermission)--}}
{{--                                                        <a href="{{route('supplier-inventories.lists')}}" class="dropdown-item" data-key="t-login">Date Filter</a>--}}
{{--                                                        @endif--}}
{{--                                                @endcan--}}
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
                                    @can('create-customer')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-customer');
                                        @endphp
                                        @if ($hasPermission)
                                            <div class="dropdown">
                                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                                    <span data-key="t-utility">Customer</span>
                                                    <div class="arrow-down"></div>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                                    <a href="{{route('customers.index')}}" class="dropdown-item" data-key="t-login">List Customer </a>
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
                            <!-- <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('variants.index')}}" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Garages </span>
                                </a>
                            </div> -->
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
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-lines.index');
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
                            <!-- <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Supplier </span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="{{route('suppliers.create')}}" class="dropdown-item" data-key="t-login">Add New Supplier </a>
                                    <a href="{{route('suppliers.index')}}" class="dropdown-item" data-key="t-login">Supplier Info </a>
{{--@can('LOI-list')--}}
{{--                                       <a href="{{route('letter-of-indents.get-suppliers-LOIs')}}" class="dropdown-item" data-key="t-login">Supplier LOIs </a>--}}
{{--                                    @endcan--}}
                                </div>
                            </div> -->
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
                            <span data-key="t-extra-pages">Master Lead Source</span>
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
@include('partials/pushveh')
