<!-- Topnav -->
<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
	<div class="container-fluid">
		<div class="navbar-collapse collapse" id="navbarSupportedContent">
			<!-- Navbar links -->
			<ul class="navbar-nav align-items-center ml-md-auto">
			</ul>
			<ul class="navbar-nav align-items-center ml-md-0 ml-auto">
				<li class="nav-item dropdown">
					<a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
						aria-expanded="false">
						<div class="media align-items-center">
							<span class="avatar avatar-sm rounded-circle">
								<img alt="Image placeholder" src="{{ asset('/') }}assets/favicon_gmj/user_7718888.png" />
							</span>
							<div class="media-body d-none d-lg-block ml-2">
								<span class="font-weight-bold mb-0 text-sm">{{ $users->name }}</span>
							</div>
						</div>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<div class="dropdown-header noti-title">
							<h6 class="text-overflow m-0">Welcome!</h6>
						</div>
						<a href="#!" class="dropdown-item">
							<i class="ni ni-single-02"></i>
							<span>My profile</span>
						</a>
						<a href="#!" class="dropdown-item">
							<i class="ni ni-settings-gear-65"></i>
							<span>Settings</span>
						</a>
						<a href="#!" class="dropdown-item">
							<i class="ni ni-calendar-grid-58"></i>
							<span>Activity</span>
						</a>
						<a href="#!" class="dropdown-item">
							<i class="ni ni-support-16"></i>
							<span>Support</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="{{ url('logout') }}" class="dropdown-item">
							<i class="ni ni-user-run"></i>
							<span>Logout</span>
						</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
</nav>
