<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
	<div class="scrollbar-inner">
		<!-- Brand -->
		<div class="sidenav-header d-flex align-items-center">
			<a class="navbar-brand" href="./pages/dashboards/dashboard.html">
				<h4 style="color: blue">PT GMJ DASHBOARD</h4>
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
			<div class="navbar-collapse collapse" id="sidenav-collapse-main">
				<!-- Nav items -->
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link {{ $sessionRoute == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
							<i class="ni ni-shop text-primary"></i>
							<span class="nav-link-text">Dashboards</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#navbar-projects" data-toggle="collapse" role="button" aria-expanded="false"
							aria-controls="navbar-projects">
							<i class="ni ni-settings text-orange"></i>
							<span class="nav-link-text">Projects</span>
						</a>
						<div class="collapse" id="navbar-projects">
							<ul class="nav nav-sm flex-column">
								<li class="nav-item">
									<a href="./pages/examples/pricing.html" class="nav-link">Project Type</a>
								</li>
								<li class="nav-item">
									<a href="./pages/examples/pricing.html" class="nav-link">Project</a>
								</li>
								<li class="nav-item">
									<a href="./pages/examples/login.html" class="nav-link">Project Realisation</a>
								</li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#navbar-master" data-toggle="collapse" role="button" aria-expanded="false"
							aria-controls="navbar-examples">
							<i class="ni ni-ungroup text-orange"></i>
							<span class="nav-link-text">Master</span>
						</a>
						<div class="collapse" id="navbar-master">
							<ul class="nav nav-sm flex-column">
								<li class="nav-item">
									<a href="./pages/examples/pricing.html" class="nav-link">Master Supplier</a>
								</li>
								<li class="nav-item">
									<a href="./pages/examples/pricing.html" class="nav-link">Master Category Item</a>
								</li>
								<li class="nav-item">
									<a href="./pages/examples/login.html" class="nav-link">Master Item</a>
								</li>
								<li class="nav-item">
									<a href="./pages/examples/register.html" class="nav-link">Master Customer</a>
								</li>
								<li class="nav-item">
									<a href="./pages/examples/lock.html" class="nav-link">Master Unit</a>
								</li>
								<li class="nav-item">
									<a href="./pages/examples/lock.html" class="nav-link">Master User</a>
								</li>
								<li class="nav-item">
									<a href="./pages/examples/lock.html" class="nav-link">Master Role</a>
								</li>
								<li class="nav-item">
									<a href="./pages/examples/lock.html" class="nav-link">Master COA</a>
								</li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#navbar-transaction" data-toggle="collapse" role="button" aria-expanded="false"
							aria-controls="navbar-transaction">
							<i class="ni ni-ui-04 text-info"></i>
							<span class="nav-link-text">Transactions</span>
						</a>
						<div class="collapse" id="navbar-transaction">
							<ul class="nav nav-sm flex-column">
								<li class="nav-item">
									<a href="./pages/components/buttons.html" class="nav-link">Purchase Transaction</a>
								</li>
								<li class="nav-item">
									<a href="./pages/components/cards.html" class="nav-link">Invoice</a>
								</li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#navbar-finance" data-toggle="collapse" role="button" aria-expanded="false"
							aria-controls="navbar-finance">
							<i class="ni ni-money-coins text-info"></i>
							<span class="nav-link-text">Finance</span>
						</a>
						<div class="collapse" id="navbar-finance">
							<ul class="nav nav-sm flex-column">
								<li class="nav-item">
									<a href="./pages/components/buttons.html" class="nav-link">Payment</a>
								</li>
								<li class="nav-item">
									<a href="./pages/components/cards.html" class="nav-link">Receipt</a>
								</li>
								<li class="nav-item">
									<a href="./pages/components/cards.html" class="nav-link">Cash Book</a>
								</li>
								<li class="nav-item">
									<a href="./pages/components/cards.html" class="nav-link">Advance Receipt</a>
								</li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#navbar-forms" data-toggle="collapse" role="button" aria-expanded="false"
							aria-controls="navbar-forms">
							<i class="ni ni-single-copy-04 text-pink"></i>
							<span class="nav-link-text">Accounting</span>
						</a>
						<div class="collapse" id="navbar-forms">
							<ul class="nav nav-sm flex-column">
								<li class="nav-item">
									<a href="./pages/forms/elements.html" class="nav-link">Journal</a>
								</li>
								<li class="nav-item">
									<a href="./pages/forms/components.html" class="nav-link">Ledger Report</a>
								</li>
								<li class="nav-item">
									<a href="./pages/forms/validation.html" class="nav-link">Trial Balance Report</a>
								</li>
								<li class="nav-item">
									<a href="./pages/forms/validation.html" class="nav-link">Profit & Loss Report</a>
								</li>
								<li class="nav-item">
									<a href="./pages/forms/validation.html" class="nav-link">Balance Sheet Report</a>
								</li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#navbar-tables" data-toggle="collapse" role="button" aria-expanded="false"
							aria-controls="navbar-tables">
							<i class="ni ni-box-2 text-default"></i>
							<span class="nav-link-text">Inventory</span>
						</a>
						<div class="collapse" id="navbar-tables">
							<ul class="nav nav-sm flex-column">
								<li class="nav-item">
									<a href="./pages/tables/tables.html" class="nav-link">Inventory In</a>
								</li>
								<li class="nav-item">
									<a href="./pages/tables/sortable.html" class="nav-link">Inventory Out</a>
								</li>
								<li class="nav-item">
									<a href="./pages/tables/datatables.html" class="nav-link">Stocks</a>
								</li>
								<li class="nav-item">
									<a href="./pages/tables/datatables.html" class="nav-link">Stock Reminder</a>
								</li>
								<li class="nav-item">
									<a href="./pages/tables/datatables.html" class="nav-link">Purchase Request</a>
								</li>
							</ul>
						</div>
					</li>
				</ul>
				<!-- Divider -->
				<hr class="my-3" />
			</div>
		</div>
</nav>
