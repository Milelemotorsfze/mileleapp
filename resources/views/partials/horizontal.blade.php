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
    <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
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
                <i class="fa fa-users" aria-hidden="true"></i> {{ $role->name }}
            </a>
            <div class="dropdown-divider"></div>
        @endforeach
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
                            <span data-key="t-extra-pages">Supplier</span>
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
                                    <span data-key="t-authentication">Create Supplier</span>
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
                                    <span data-key="t-utility">Supplier Info </span>
                                </a>
                            </div>
                            @endif
                            @endcan
                        </div>
                    </li>
                    @endif
                    @endcanany
                    @canany(['warranty-create', 'warranty-list'])
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create', 'warranty-list']);
                    @endphp
                    @if ($hasPermission)
                    <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="grid"></i>
                            <span data-key="t-extra-pages">Warranty</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            @can('warranty-create')
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create']);
                            @endphp
                            @if ($hasPermission)
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('warranty.create') }}" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Create Warranty</span>
                                </a>
                            </div>
                            @endif
                            @endcan
                            @can('warranty-list')
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-list']);
                            @endphp
                            @if ($hasPermission)
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('warranty.index') }}" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Warranty Info </span>
                                </a>
                            </div>
                            @endif
                            @endcan
                        </div>
                    </li>
                    @endif
                    @endcanany
                    @canany(['addon-create','accessories-list','spare-parts-list','kit-list'])
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-create','accessories-list','spare-parts-list','kit-list']);
                    @endphp
                    @if ($hasPermission)
                    <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="grid"></i>
                            <span data-key="t-extra-pages">Addons</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            @can('addon-create')
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-create']);
                            @endphp
                            @if ($hasPermission)
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('addon.create') }}" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Create Addons</span>
                                </a>
                            </div>
                            @endif
                            @endcan
                            @canany(['addon-create','accessories-list','spare-parts-list','kit-list'])
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-create','accessories-list','spare-parts-list','kit-list']);
                            @endphp
                            @if ($hasPermission)
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                    <span data-key="t-utility">List Addons </span>
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
                                    <!-- <a href="{{route('addon.list','D')}}" class="dropdown-item" data-key="t-login">Documentation</a>
                                    <a href="{{route('addon.list','DP')}}" class="dropdown-item" data-key="t-login">Documentation On Purchase</a>
                                    <a href="{{route('addon.list','E')}}" class="dropdown-item" data-key="t-login">Others</a>
                                    <a href="{{route('addon.list','S')}}" class="dropdown-item" data-key="t-login">Shipping Cost</a> -->
                                    @can('spare-parts-list')
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['spare-parts-list']);
                                    @endphp
                                    @if ($hasPermission)
                                    <a href="{{route('addon.list','SP')}}" class="dropdown-item" data-key="t-login">Spare Parts</a>
                                    @endif
                                    @endcan
                                    <!-- <a href="{{route('addon.list','W')}}" class="dropdown-item" data-key="t-login">Warranty</a> -->
                                    @can('kit-list')
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['kit-list']);
                                    @endphp
                                    @if ($hasPermission)
                                    <a href="{{route('addon.list','K')}}" class="dropdown-item" data-key="t-login">Kit</a>
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
                    </li>
                    @endif
                    @endcanany
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
                    @can('stock-full-view')
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
                    @endphp
                    @if ($hasPermission)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('Vehicles.index') }}" id="topnav-more" role="button">
                            <i data-feather="sliders"></i>
                            <span data-key="t-extra-pages">Full Stocks</span>
                        </a>
					</li>
                    @endif
                    @endcan
                    @can('edit-po-details')
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-details');
                    @endphp
                    @if ($hasPermission)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('demand-planning-suppliers.create') }}" id="topnav-more" role="button">
                            <i data-feather="sliders"></i>
                            <span data-key="t-extra-pages">Supplier</span>
                        </a>
					</li>
                    @endif
                    @endcan
                    @can('variants-view')
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-view');
                    @endphp
                    @if ($hasPermission)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('variant_pictures.index') }}" id="topnav-more" role="button">
                            <i data-feather="film"></i>
                            <span data-key="t-extra-pages">Add Pictures & Videos</span>
                        </a>
					</li>
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
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-picture-view');
                    @endphp
                    @if ($hasPermission)
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('vehicle-pictures.index') }}" id="topnav-more" role="button">
                            <i data-feather="film"></i>
                            <span data-key="t-extra-pages">Vehicle Pictures</span>
                        </a>
                    </li>
                    @endif
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
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('variant-edit');
                    @endphp
                    @if ($hasPermission)
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('variants.index')}}" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Variants </span>
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
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-view');
                    @endphp
                    @if ($hasPermission)
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('vendors.index')}}" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Vendors </span>
                                </a>
                            </div>
                            @endif
                            @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vendor-edit');
                    @endphp
                    @if ($hasPermission)
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{route('variant-prices.index')}}" id="topnav-utility" role="button">
                                    <span data-key="t-utility">Variant Prices </span>
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

                </ul>
            </div>
        </nav>
    </div>
</div>
@include('partials/pushveh')