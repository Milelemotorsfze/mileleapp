<div class="topnav" style="overflow: unset;">
    <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
        <i class="fa fa-fw fa-bars"></i>
    </button>
<div class="dropdown d-inline-block" style="position: absolute; right: 0px; z-index: 500;">
@can('sales-view')
<div class="cart-icon-container">
  <a href="{{route('quotation.create')}}"><i class="fa fa-car fa-2x" aria-hidden="true"></i></a>
  <span class="cart-icon-number"></span>
</div>
@endcan

<div class="cart-icon-container">
  <a href=""><i class="fa fa-bell fa-2x" aria-hidden="true"></i></a>
  <span class="cart-icon-number"></span>
</div>
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
        @can('user-view')
        <a class="dropdown-item" href="{{ route('users.index') }}"><i class="fa fa-users" aria-hidden="true"></i> Users </a>
        <div class="dropdown-divider"></div>
        @endcan
        @can('role-list')
        <a class="dropdown-item" href="{{ route('roles.index') }}"><i class="fa fa-user-circle" aria-hidden="true"></i> Roles </a>
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
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="/" id="topnav-more" role="button">
                            <i data-feather="home"></i>
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
                    @can('warehouse-view')
                    @can('View-daily-movemnets')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('movements.index') }}" id="topnav-more" role="button">
                            <i data-feather="command"></i>
                            <span data-key="t-extra-pages">Movements</span>
                        </a>
					</li>
                    @endcan
                    @can('view-po-details')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('purchasing-order.index') }}" id="topnav-more" role="button">
                            <i data-feather="award"></i>
                            <span data-key="t-extra-pages">Purchasing Order</span>
                        </a>
					</li>
                    @endcan
                    @can('view-po-details')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('Vehicles.index') }}" id="topnav-more" role="button">
                            <i data-feather="sliders"></i>
                            <span data-key="t-extra-pages">Stocks</span>
                        </a>
					</li>
                    @endcan
                    @endcan
                    @can('variants-view')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('variant_pictures.index') }}" id="topnav-more" role="button">
                            <i data-feather="film"></i>
                            <span data-key="t-extra-pages">Add Pictures & Videos</span>
                        </a>
					</li>
                    @endcan
                    @can('sales-view')
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="{{ route('dailyleads.index') }}" id="topnav-more" role="button">
                            <i data-feather="film"></i>
                            <span data-key="t-extra-pages">Leads</span>
                        </a>
                    </li>
                    @endcan
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('vehicle-pictures.index') }}" id="topnav-more" role="button">
                            <i data-feather="film"></i>
                            <span data-key="t-extra-pages">Vehicle Pictures</span>
                        </a>
                    </li>
                    @can('demand-create')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                <i data-feather="file-text"></i>
                                <span data-key="t-extra-pages">Demand & Planning</span>
                                <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-more">
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('demand-planning-suppliers.create') }}"
                                       id="topnav-auth" role="button" >
                                        <span data-key="t-authentication">Supplier</span>
                                    </a>
                                </div>

                                @can('demand-create')
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                            <span data-key="t-utility">Demand</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                            <a href="{{route('demands.create')}}" class="dropdown-item" data-key="t-login">Add New Demand </a>
                                        </div>
                                    </div>
                                @endcan
                                @can('LOI-list')
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                            <span data-key="t-utility">LOI</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                            @can('LOI-create')
                                                <a href="{{route('letter-of-indents.create')}}" class="dropdown-item" data-key="t-login">Add New LOI</a>
                                            @endcan
                                            @can('LOI-list')
                                                <a href="{{route('letter-of-indents.index')}}" class="dropdown-item" data-key="t-login">LOI Info</a>
                                                <a href="{{route('letter-of-indents.get-suppliers-LOIs')}}" class="dropdown-item" data-key="t-login">Supplier LOIs </a>
                                            @endcan
                                        </div>
                                    </div>
                                @endcan
                                @can('supplier-inventory-list')
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                            <span data-key="t-utility">Supplier Inventory</span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                            <a href="{{route('supplier-inventories.index')}}" class="dropdown-item" data-key="t-login">Supplier Inventory</a>
                                            <a href="{{route('supplier-inventories.lists')}}" class="dropdown-item" data-key="t-login">Date Filter</a>
                                            <a href="{{route('supplier-inventories.file-comparision')}}" class="dropdown-item" data-key="t-login">File Comparison</a>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </li>
                    @endcan
                    @can('user-create')
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
{{--                                    @can('LOI-list')--}}
{{--                                       <a href="{{route('letter-of-indents.get-suppliers-LOIs')}}" class="dropdown-item" data-key="t-login">Supplier LOIs </a>--}}
{{--                                    @endcan--}}
                                </div>
                            </div>
                        </div>
                    </li>
                    @endcan
                    @can('HR-view')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('hiring.index') }}" id="topnav-more" role="button">
                            <i data-feather="file-text"></i>
                            <span data-key="t-extra-pages">Hiring</span>
                        </a>
                    </li>
                    </li>
                    @endcan
                    @can('Calls-view')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('lead_source.index') }}" id="topnav-more" role="button">
                            <i data-feather="server"></i>
                            <span data-key="t-extra-pages">Master Lead Source</span>
                        </a>
					</li>
                    @endcan
                    @can('user-view')
                    <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="grid"></i>
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
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                    <span data-key="t-utility">List Addons </span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="{{route('addon.list','P')}}" class="dropdown-item" data-key="t-login">Accessories</a>
                                    <!-- <a href="{{route('addon.list','D')}}" class="dropdown-item" data-key="t-login">Documentation</a>
                                    <a href="{{route('addon.list','DP')}}" class="dropdown-item" data-key="t-login">Documentation On Purchase</a>
                                    <a href="{{route('addon.list','E')}}" class="dropdown-item" data-key="t-login">Others</a>
                                    <a href="{{route('addon.list','S')}}" class="dropdown-item" data-key="t-login">Shipping Cost</a> -->
                                    <a href="{{route('addon.list','SP')}}" class="dropdown-item" data-key="t-login">Spare Parts</a>
                                    <a href="{{route('addon.list','W')}}" class="dropdown-item" data-key="t-login">Warranty</a>
                                    <a href="{{route('addon.list','K')}}" class="dropdown-item" data-key="t-login">Kit</a>
                                    <a href="{{route('addon.list','all')}}" class="dropdown-item" data-key="t-login">All</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endcan
                </ul>
            </div>
        </nav>
    </div>
</div>
@include('partials/pushveh')
