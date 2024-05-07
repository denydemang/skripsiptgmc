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
								<li class="breadcrumb-item active" aria-current="page">Stock Card</li>
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
								<h3 class="mb-0">Stock Card</h3>
							</div>
							<div class="card-body">
								<div class="col-lg-6">
									<h5>Date</h5>
									<div class="d-flex mb-3">
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
									<h5>Item</h5>
									<div class="d-flex">
										<input type="text" class="form-control form-control-sm inputitemcode mr-2" style="width:30%" readonly>
										@include('component.btnadditem')
									</div>
									<input type="text" class="form-control form-control-sm inputitemname mt-2" style="width: 70%" readonly>
									<button class="btn btn-primary btn-sm btnprint mt-2"><i class="fas fa-print"></i> Print</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('layout.footer')
	@include('searchModal.itemssearch')
	</div>

	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/inventory/stockcard.js" type="module"></script>
@endsection
