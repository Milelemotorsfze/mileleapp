<button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
    <i class="fa fa-fw fa-bars"></i>
</button>
<div class="dropdown d-inline-block" style="position: absolute; right: 0px; z-index: 500;">
    <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 55px;">
        <img class="rounded-circle header-profile-user" src="{{asset ('images/users/avatar-1.jpg')}}" alt="Header Avatar" style="float: left;">
        <span class="d-none d-xl-inline-block ms-1 fw-medium" style="line-height: 35px;">
            @if(auth()->user()->name) {{ auth()->user()->name }} @endif
        </span>
        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a class="dropdown-item" href="profile">
            <i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profile
        </a>
        <div class="dropdown-divider"></div>
        @can('user-view')
        <a class="dropdown-item" href="{{ route('users.index') }}"><i class="fa fa-users" aria-hidden="true"></i> Users </a>
        <div class="dropdown-divider"></div>
        @endcan
        @can('role-list')
        <a class="dropdown-item" href="{{ route('roles.index') }}"><i class="fa fa-user-circle" aria-hidden="true"></i> Roles </a>
        <div class="dropdown-divider"></div>
        @endcan
        @can('addon-view')
        <a class="dropdown-item" href="{{ route('addon.index') }}"><i class="fa fa-user-circle" aria-hidden="true"></i> Addon </a>
        <div class="dropdown-divider"></div>
        @endcan
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="mdi mdi-logout font-size-16 align-middle me-1"></i>Logout
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </a>
    </div>
</div>
<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="/" id="topnav-more" role="button">
                            <i data-feather="file-text"></i>
                            <span data-key="t-extra-pages">Dashboard</span>
                        </a>
                    </li>
                    @can('Calls-view')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('calls.index') }}" id="topnav-more" role="button">
                            <i data-feather="phone-call"></i>
                            <span data-key="t-extra-pages">Messages & Calls</span>
                        </a>
					</li>
                    @endcan
                    @can('variants-view')
                    <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="{{ route('variant_pictures.index') }}" id="topnav-more" role="button">
                            <i data-feather="film"></i>
                            <span data-key="t-extra-pages">Variants</span>
                        </a>
                    </li>
                    @endcan
                    @can('sales-view')
                    <li class="nav-item dropdown">
    					<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="file-text"></i>
                            <span data-key="t-extra-pages">Sales</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('calls.index') }}" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Daily Calls</span>
                                </a>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('dailyleads.index') }}" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Leads</span>
                                </a>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Sales</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="file-text"></i>
                            <span data-key="t-extra-pages">BL Form</span><div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('blform.index') }}" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Create New BL</span>
                                </a>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">View VINs</span>
                                </a>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Change Country</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    @endcan
                    @can('user-create')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="file-text"></i>
                            <span data-key="t-extra-pages">Demand & Planning</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button" >
                                    <span data-key="t-authentication">Forecast</span>
                                </a>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Demand</span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="{{route('demands.create')}}" class="dropdown-item" data-key="t-login">Add New Demand </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Deals</span>
                                </a>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('supplier-inventories.index') }}" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Supplier Inventory</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="grid"></i>
                            <span data-key="t-extra-pages">Master Data</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none"  href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Models </span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="" class="dropdown-item" data-key="t-login">Add New Models </a>
                                    <a href="" class="dropdown-item" data-key="t-login">Model Info </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Variants </span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="" class="dropdown-item" data-key="t-login">Add New Variants </a>
                                    <a href="" class="dropdown-item" data-key="t-login">Variants Info </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none"  href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Colours </span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="/addnewsuppliers" class="dropdown-item" data-key="t-login">Add New Colours </a>
                                    <a href="/suppliermapping" class="dropdown-item" data-key="t-login">Colours Info </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none"  href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Garages </span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="/addnewsuppliers" class="dropdown-item" data-key="t-login">Add New Garages </a>
                                    <a href="/suppliermapping" class="dropdown-item" data-key="t-login">Garages Info </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Warehouse </span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="/addnewcustomers" class="dropdown-item" data-key="t-login">Add New Warehouse </a>
                                    <a href="/customerinfo" class="dropdown-item" data-key="t-login">Warehouse Info </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Supplier </span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="{{route('suppliers.create')}}" class="dropdown-item" data-key="t-login">Add New Supplier </a>
                                    <a href="{{route('suppliers.index')}}" class="dropdown-item" data-key="t-login">Supplier Info </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endcan
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="file-text"></i>
                            <span data-key="t-extra-pages">Addons</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('addon.create') }}" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Create Addons</span>
                                </a>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('addon.index') }}" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">List Addons</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
