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
								<li class="breadcrumb-item active" aria-current="page">Inventory Out</li>
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
			<div class="col-xl-12 col-lg-12">
				<div class="row">
					<div class="col">
						<div class="card" style="min-height: 800px">
							<!-- Card header -->
							<div class="card-header">
								<h3 class="mb-0">Inventory OUT</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12 d-lg-flex justify-content-between">
										<div>
											<div class="mb-3 mr-1">
												<h4>Date Item OUT</h4>
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
													<button class="btn btn-sm btn-outline-primary btnprint ml-3"><i class="fas fa-print"></i> Print</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<table class="align-items-center table-flush iouttable w-100 table">

								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('layout.footer')
	</div>

	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/inventory/inventoryout.js" type="module"></script>
@endsection
