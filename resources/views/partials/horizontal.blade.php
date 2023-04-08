<button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
<i class="fa fa-fw fa-bars"></i>
</button>
<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
					@can('sales-view')
                    <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="/" id="topnav-more" role="button">
                            <i data-feather="file-text"></i><span data-key="t-extra-pages">Dashboard</span>
                        </a>
					</li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="file-text"></i><span data-key="t-extra-pages">Sales</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('calls.index') }}" id="topnav-auth" role="button" >
                                    <span data-key="t-authentication">Daily Calls</span>
                                </a>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('dailyleads.index') }}" id="topnav-auth" role="button" >
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
                    @endcan
                    @can('user-create')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="file-text"></i><span data-key="t-extra-pages">Demand & Planning</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button" >
                                    <span data-key="t-authentication">Forecast</span>
                                </a>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                <span data-key="t-utility">Demand</span><div class="arrow-down"></div>
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
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="file-text"></i><span data-key="t-extra-pages">Procurment</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">LOI</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="{{route('letter-of-indents.create')}}" class="dropdown-item"
                                       data-key="t-login">Add New LOI </a>
                                </div>
                            </div>
                        </div>
						</li>
						<li class="nav-item dropdown">
    						<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                <i data-feather="file-text"></i><span data-key="t-extra-pages">Sales</span> <div class="arrow-down"></div>
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
                                <i data-feather="file-text"></i><span data-key="t-extra-pages">BL Form</span><div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-more">
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('blfrom.index') }}" id="topnav-auth" role="button">
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
                                <i data-feather="file-text"></i><span data-key="t-extra-pages">Demand & Planning</span> <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-more">
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                        <span data-key="t-authentication">Forecast</span>
                                    </a>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                                        <span data-key="t-utility">Demand</span>
                                    </a>
                                </div>
    							<div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                                        <span data-key="t-utility">Deals</span>
                                    </a>
>>>>>>> 1ed2aeb44ece3829b5729d39db730717e60af421
                                </div>
                            </div>
						</li>
                        <li class="nav-item dropdown">
    						<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                                <i data-feather="file-text"></i><span data-key="t-extra-pages">Addons</span> <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-more">
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('addon.create') }}" id="topnav-auth" role="button">
                                        <span data-key="t-authentication">Create Addon</span>
                                    </a>
                                </div>
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="{{ route('addon.index') }}" id="topnav-auth" role="button">
                                        <span data-key="t-authentication">List Addon</span>
                                    </a>
                                </div>

                            </div>
						</li>
						<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="grid"></i><span data-key="t-extra-pages">Master Data</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none"  href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Models </span><div class="arrow-down"></div>
                                </a>
									<div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="" class="dropdown-item" data-key="t-login">Add New Models </a>
                                    <a href="" class="dropdown-item" data-key="t-login">Model Info </a>
                                </div>
                            </div>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                              <span data-key="t-utility">Variants </span><div class="arrow-down"></div><div class="arrow-down"></div>
                              </a>
							  <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="" class="dropdown-item" data-key="t-login">Add New Variants </a>
                                    <a href="" class="dropdown-item" data-key="t-login">Variants Info </a>
                                </div>
                            </div>
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none"  href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Colours </span><div class="arrow-down"></div>
                                </a>
									<div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="/addnewsuppliers" class="dropdown-item" data-key="t-login">Add New Colours </a>
                                    <a href="/suppliermapping" class="dropdown-item" data-key="t-login">Colours Info </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none"  href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Garages </span><div class="arrow-down"></div>
                                </a>
									<div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="/addnewsuppliers" class="dropdown-item" data-key="t-login">Add New Garages </a>
                                    <a href="/suppliermapping" class="dropdown-item" data-key="t-login">Garages Info </a>
                                </div>
                            </div>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                              <span data-key="t-utility">Warehouse </span><div class="arrow-down"></div>
                              </a>
							  <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="/addnewcustomers" class="dropdown-item" data-key="t-login">Add New Warehouse </a>
                                    <a href="/customerinfo" class="dropdown-item" data-key="t-login">Warehouse Info </a>
                                </div>
                            </div>
                            </div>
						</li>
						<li class="nav-item dropdown">
												<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="user"></i><span data-key="t-extra-pages">Users</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Customers </span><div class="arrow-down"></div>
                                </a>
									<div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="/addnewsuppliers" class="dropdown-item" data-key="t-login">Customer info</a>
                                    <a href="/suppliermapping" class="dropdown-item" data-key="t-login">Customer reports </a>
                                </div>
                            </div>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                              <span data-key="t-utility">Suppliers</span><div class="arrow-down"></div><div class="arrow-down"></div>
                              </a>
							  <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="/addnewvariants" class="dropdown-item" data-key="t-login">Supplier Inventory </a>
                                    <a href="/variantinfo" class="dropdown-item" data-key="t-login">Vehicles Suppliers </a>
                                    <a href="/variantinfo" class="dropdown-item" data-key="t-login">Parts Suppliers </a>
                                    <a href="/variantinfo" class="dropdown-item" data-key="t-login">Supplier PDIs </a>
                                    <a href="/variantinfo" class="dropdown-item" data-key="t-login">Supplier Deals </a>
                                    <a href="/variantinfo" class="dropdown-item" data-key="t-login">Supplier mapping </a>
                                    <a href="/variantinfo" class="dropdown-item" data-key="t-login">Supplier reports </a>
                                </div>
                            </div>
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none"  href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Vendors </span><div class="arrow-down"></div>
                                </a>
									<div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="/addnewsuppliers" class="dropdown-item" data-key="t-login">Add New Vendors </a>
                                    <a href="/suppliermapping" class="dropdown-item" data-key="t-login">Vendors Info </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none"  href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Employees </span><div class="arrow-down"></div>
                                </a>
									<div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="/addnewsuppliers" class="dropdown-item" data-key="t-login">Add New Employees </a>
                                    <a href="/suppliermapping" class="dropdown-item" data-key="t-login">Employees Info </a>
                                </div>
                            </div>
                            </div>
						</li>
						<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="shopping-bag"></i><span data-key="t-extra-pages">Purchase </span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">PO </span>
                                </a>
                            </div>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">PO Reports</span>
                              </a>
                            </div>
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Inquiry Forms </span>
                                </a>

                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Account Report </span>
                                </a>
                            </div>
                            </div>
						</li>
						<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="box"></i><span data-key="t-extra-pages">Warehouse </span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Daily Movement </span>
                                </a>
                            </div>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">BOD Car Maintains</span>
                              </a>
                            </div>
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Incidents </span>
                                </a>
                            </div>
							 <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Spare Parts </span>
                                </a>
                            </div>
                            </div>
						</li>
						<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="git-pull-request"></i><span data-key="t-extra-pages">Vehicles </span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Incoming Vehicles</span>
                                </a>
                            </div>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Inventory</span>
                              </a>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Urgent Vehicles</span>
                              </a>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Other Warehouse Stocks</span>
                              </a>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Other Country Vehicles</span>
                              </a>
                            </div>
                            </div>
						</li>
						<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="life-buoy"></i><span data-key="t-extra-pages">Logistics </span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Imports Shipment</span>
                                </a>
                            </div>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Export Document Record</span>
                              </a>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Document Expire Record</span>
                              </a>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Export Shipment</span>
                              </a>
                            </div>
                            </div>
						</li>
						<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="slack"></i><span data-key="t-extra-pages">Sales </span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">SO</span>
                                </a>
                            </div>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">SO Report</span>
                              </a>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Sales Report</span>
                              </a>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Booking</span>
                              </a>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Daily Leads</span>
                              </a>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Account Report</span>
                              </a>
                            </div>
                            </div>
						</li>
						<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="slack"></i><span data-key="t-extra-pages">QC</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                        <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">QC Reports</span>
                                </a>
                            </div>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">PDIs</span>
                              </a>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Modifications</span>
                              </a>
                            </div>
                            </div>
						</li>
						<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="hard-drive"></i><span data-key="t-extra-pages">HR</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
						<div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">Leaves</span>
                                </a>
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Payroll</span>
                              </a>
                             <a class="dropdown-item dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-utility" role="button">
                              <span data-key="t-utility">Renewals & Documentations</span>
                              </a>
							  </div>
							  </div>
						</li>
						<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="/addnewvariants" id="topnav-more" role="button">
                            <i data-feather="folder"></i><span data-key="t-extra-pages">Work Orders</span>
                        </a>

						</li>
                        @endcan
            </ul>
        </div>
            <div class="d-flex">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{asset ('images/users/avatar-1.jpg')}}" alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">@if(auth()->user()->name) {{ auth()->user()->name }} @endif
					</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="profile"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profile </a>
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
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    </a>
                </div>
                </div>
                </div>

        </nav>
    </div>
</div>
