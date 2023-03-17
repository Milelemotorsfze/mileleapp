<div class="topnav">
					<?php 
                     $session    = session();
                     $username   = $session->get('username');
                     $permission = $session->get('permission');
                     $department = $session->get('department');
                    ?>
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
        
            <div class="collapse navbar-collapse" id="topnav-menu-content">
			<ul class="navbar-nav">
			<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url(); ?>/home" id="topnav-more" role="button">
                            <i data-feather="layout"></i><span data-key="t-extra-pages"><?= lang('Stock Report') ?></span>
                        </a>
            </li>
			<?php
			if(($department == "Purcahser" AND $permission == "Department Edit") OR $department == "Management" OR ($department == "Finance" AND $permission == "Department Edit"))
			{
				?>
			<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" id="topnav-more" role="button">
                            <i data-feather="file-text"></i><span data-key="t-extra-pages"><?= lang('Records') ?></span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
						<?php 
						if(($department == "Purcahser" AND $permission == "Department Edit") OR $department == "Management")
						{
							?>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="<?php echo base_url(); ?>/addnewrecord" id="topnav-auth" role="button">
                                    <span data-key="t-authentication"><?= lang('Create New Records') ?></span>
                                </a>
                            </div>
						<?php
						}
						?>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" href="<?php echo base_url(); ?>/recordsinfo" id="topnav-utility" role="button">
                              <span data-key="t-utility"><?= lang('Records Summary') ?></span>
                              </a>
                            </div>
                        </div>
            </li>
			<?php
			}
			?>
			<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" id="topnav-more" role="button">
                            <i data-feather="file-text"></i><span data-key="t-extra-pages"><?= lang('Summary') ?></span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="<?php echo base_url(); ?>/summaryreport" id="topnav-auth" role="button">
                                    <span data-key="t-authentication"><?= lang('Overall Summary') ?></span>
                                </a>
                            </div>
                        </div>
            </li>
						<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" id="topnav-more" role="button">
                            <i data-feather="file-text"></i><span data-key="t-extra-pages"><?= lang('Master Data') ?></span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" id="topnav-auth" role="button">
                                    <span data-key="t-authentication"><?= lang('Suppliers') ?></span><div class="arrow-down"></div>
                                </a>
									<div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="<?php echo base_url(); ?>/addnewsuppliers" class="dropdown-item" data-key="t-login"><?= lang('Add New Suplier') ?></a>
                                    <a href="<?php echo base_url(); ?>/suppliermapping" class="dropdown-item" data-key="t-login"><?= lang('Supplier Info') ?></a> 
                                </div>
                            </div>
                            <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" id="topnav-utility" role="button">
                              <span data-key="t-utility"><?= lang('Customers') ?></span><div class="arrow-down"></div>
                              </a>
							  <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="<?php echo base_url(); ?>/addnewcustomers" class="dropdown-item" data-key="t-login"><?= lang('Add New Customers') ?></a>
                                    <a href="<?php echo base_url(); ?>/customerinfo" class="dropdown-item" data-key="t-login"><?= lang('Customers Info') ?></a> 
                                </div>
                            </div>
							 <div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" id="topnav-utility" role="button">
                              <span data-key="t-utility"><?= lang('Variants') ?></span><div class="arrow-down"></div><div class="arrow-down"></div>
                              </a>
							  <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="<?php echo base_url(); ?>/addnewvariants" class="dropdown-item" data-key="t-login"><?= lang('Add New Variants') ?></a>
                                    <a href="<?php echo base_url(); ?>/variantinfo" class="dropdown-item" data-key="t-login"><?= lang('Variants Info') ?></a> 
                                </div>
                            </div>
							<div class="dropdown">
                             <a class="dropdown-item dropdown-toggle arrow-none" id="topnav-utility" role="button">
                              <span data-key="t-utility"><?= lang('Users') ?></span><div class="arrow-down"></div>
                              </a>
							  <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="<?php echo base_url(); ?>/addnewusers" class="dropdown-item" data-key="t-login"><?= lang('Add New Users') ?></a>
                                    <a href="<?php echo base_url(); ?>/usersinfo" class="dropdown-item" data-key="t-login"><?= lang('User Info') ?></a> 
                                </div>
                            </div>
                        </div>
						</li>
						<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" id="topnav-more" role="button">
                            <i data-feather="file-text"></i><span data-key="t-extra-pages"><?= lang('Daily Movements') ?></span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="<?php echo base_url(); ?>/dailymovemnet" id="topnav-auth" role="button">
                                    <span data-key="t-authentication"><?= lang('Add New Daily Movements') ?></span>
                                </a>
								<a class="dropdown-item dropdown-toggle arrow-none" href="<?php echo base_url(); ?>/dailymovemnetinfo" id="topnav-auth" role="button">
                                    <span data-key="t-authentication"><?= lang('Movements Info') ?></span>
                                </a>
                            </div>
                        </div>
            </li>
			</ul>
            </div>
            <i class="bi bi-list mobile-nav-toggle d-none"></i>
			<div class="d-flex alright">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">
					<?php 
                     $session    = session();
                     $username   = $session->get('username');
                     $permission = $session->get('permission');
                     $department = $session->get('department');
                     echo $department;
                    ?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="profile"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> <?= lang('Profile') ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url(); ?>/logout"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> <?= lang('Logout') ?></a>
                </div>
            </div>
            
        </div>
        </nav>
    </div>
</div>