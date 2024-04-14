@extends('layout.template')
@section('content')
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
							<ol class="breadcrumb breadcrumb-links breadcrumb-dark">
								<li class="breadcrumb-item">
									<a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
								</li>
								<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Admin</a></li>
								<li class="breadcrumb-item"><a href="{{ route('admin.projectrecapview') }}">Project Recapitulation</a></li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Page content -->
	<div class="container-fluid mt--6">
		<div class="row">
			<div class="col-xl-12 col-lg-12" style="min-height: 800px">
				<div class="row">
					<div class="col">
						<div class="card" style="min-height: 800px">
							<!-- Card header -->
							<div class="card-header border-0">
								<h3 class="mb-0">PROJECT RECAPITULATION</h3>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-2">
											<div class="mb-2 mt-5" style="width: 200px">
												<h4>Status</h4>
												<select class="form-control" id="statusSelect">
													<option selected value="">All</option>
													<option value="0">Not Started</option>
													<option value="1"> On Progress</option>
													<option value="2">Done</option>
												</select>
											</div>
										</div>
										<div class="col-lg-5 ml-5">
											<div class="mr-1 mt-5">
												<h4>Transaction Date</h4>
												<div class="d-flex">
													<div class="input-group date mr-2" id="dtpstarttrans" data-target-input="nearest">
														<input type="text" class="form-control form-control-sm datetimepicker-input inputstartdatetrans"
															data-target="#dtpstarttrans" style="cursor: pointer" readonly />
														<div class="input-group-append" data-target="#dtpstarttrans" data-toggle="dtpstarttrans">
															<div class="input-group-text" style="height: 32px"><i class="fa fa-calendar"></i></div>
														</div>
													</div>
													<span class="mr-1">to </span>
													<div class="input-group date" id="dtplasttrans" data-target-input="nearest">
														<input type="text" style="cursor: pointer"
															class="form-control form-control-sm datetimepicker-input inputlastdatetrans" data-target="#dtplasttrans"
															readonly />
														<div class="input-group-append" data-target="#dtplasttrans" data-toggle="dtplasttrans">
															<div class="input-group-text" style="height: 32px"><i class="fa fa-calendar"></i></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row mt-4">
										<div class="col-lg-6">
											<div class="row">
												<div class="col-lg-6">
													@include('component.customerCode')
												</div>
												<div class="col-lg-6">
													@include('component.customerName')
												</div>
											</div>
											<button class="btn btn-primary btn-sm btnprint"><i class="fas fa-print mr-2"></i>Print</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		@include('layout.footer')
	</div>


	{{-- MODAL FORM --}}

	{{-- @include('searchModal.coaSearch') --}}

	@include('searchModal.customerSearch')
	{{-- Notif Flash Message --}}

	<script src="{{ asset('/') }}js/project/projectrecap.js" type="module"></script>
@endsection
