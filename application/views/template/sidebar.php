<?php 
	$this->load->model("Menu_model","menu");
	$active = explode("/",uri_string());
	$menu = $this->session->userdata('menu')?$this->session->userdata('menu'):[];
?>
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
	<div class="scrollbar-inner">
		<!-- Brand -->
		<div class="sidenav-header d-flex align-items-center">
			<a class="navbar-brand" href="./pages/dashboards/dashboard.html">
				<img src="<?= base_url() ?>/assets/admin/img/brand/blue.png" class="navbar-brand-img" alt="...">
			</a>
			<div class="ml-auto">
				<!-- Sidenav toggler -->
				<div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
					<div class="sidenav-toggler-inner">
						<i class="sidenav-toggler-line"></i>
						<i class="sidenav-toggler-line"></i>
						<i class="sidenav-toggler-line"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="navbar-inner">
			<!-- Collapse -->
			<div class="collapse navbar-collapse" id="sidenav-collapse-main">
				<!-- Nav items -->
				<!-- <ul class="navbar-nav"> -->
				<?= buildMenu($menu,$active) ?>
				<!-- </ul> -->
				<hr class="my-3">
				<!-- Navigation -->
				<ul class="navbar-nav mb-md-3">
					<li class="nav-item">
						<a class="nav-link" href="<?=base_url('admin/logout')?>">
							<i class="ni ni-chart-pie-35"></i>
							<span class="nav-link-text">Logout</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>
