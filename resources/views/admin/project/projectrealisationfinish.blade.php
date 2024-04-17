@extends('layout.template')
@section('content')
	<style>
		.ui-datepicker {
			z-index: 9999
		}

		/* Kustomisasi dasar untuk elemen input */
		.custom-input {
			padding: 5px;
			border: 1px solid #ccc;
			border-radius: 5px;
			font-size: 11px;
			transition: border-color 0.3s;
		}

		/* Efek hover */
		.custom-input:hover {
			border-color: #999;
		}

		/* Efek focus */
		.custom-input:focus {
			outline: none;
			border-color: #007bff;
			box-shadow: 0 0 5px #007bff;
		}

		.tablematerial th,
		.tableupah th {
			position: sticky;
			top: 0;
			color: white;
			/* Menempatkan header tabel di bagian atas saat digulir */
			z-index: 1;
			border: 1px solid black;
			background-color: #808283;
			/* Mengatur z-index agar header muncul di atas konten */
		}
	</style>
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
								<li class="breadcrumb-item"><a href="{{ route('admin.projectrealisationview') }}">Project Realisation</a></li>
								<li class="breadcrumb-item active" aria-current="page">Finish</li>
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
						<div class="card">
							<div class="card-header border-0">
								<h3 class="mb-0"> PROJECT REALISATION FINISH</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Code Project</label>
												<input type="text" class="form-control form-control-sm inputcode" value="{{ $project['code'] }}" readonly
													id="example3cols1Input">
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Project Name</label>
												<input type="text" class="form-control form-control-sm inputname" value="{{ $project['name'] }}" readonly
													id="example3cols1Input">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Customer Name</label>
												<input type="text" class="form-control form-control-sm inputcustomername"
													value="{{ $project['customer_name'] }}" readonly id="example3cols1Input">
											</div>
										</div>
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Type Project</label>
												<input type="text" class="form-control form-control-sm inputtypeproject"
													value="{{ $project['type_project_name'] }}" readonly id="example3cols1Input">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-3">
										<div class="form-group">
											<label class="form-control-label" for="example3cols1Input">Finish Date</label>
											<div class="input-group date mr-2" id="dtpfinishdate" data-target-input="nearest">
												<input type="text" style="cursor: pointer" class="form-control form-control-sm inputfinishdate"
													data-target="#dtpfinishdate" readonly data-transdate="" />
												<div class="input-group-append" data-target="#dtpfinishdate" data-toggle="dtpfinishdate">
													<div class="input-group-text" style="height: 32px"><i class="fa fa-calendar"></i></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="d-lg-flex">
											<h5 class="text-primary">MATERIAL LIST</h5>
											<button class="btn btn-warning btnsetusedqtymaterial d-none btn-sm ml-3"><i class="fas fa-pencil-ruler"></i>
												Set Used
												Qty As
												Estimated
												Qty
											</button>
											<button class="btn btn-danger btnsetusedqtyzeromaterial d-none btn-sm ml-3"><i
													class="fas fa-pencil-ruler"></i>
												Set Used
												Qty = 0
											</button>
										</div>
										<div class="form-group">
											<div class="databahanbaku" data-bahanbaku="{{ json_encode($dataBahanBaku) }}">
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 table-responsive" style="max-height: 400px">
												<table border="1"
													style="width: 100%;  border: 1px solid #000;border-radius: 8px; background-color: #f9f9f9;"
													class="table-sm tablematerial">
													<thead style="background-color:#808283">
														<tr>
															<th class="text-left" style="font-size: 10px ;width:5%"><input type="checkbox"
																	class="checkedallmaterial">
															</th>
															<th class="text-left" style="font-size: 10px  ;width:5%">No</th>
															<th class="text-left" style="font-size: 10px  ;width:20%">Item Code</th>
															<th class="text-left" style="font-size: 10px ;width:25%">Item Name</th>
															<th style="font-size: 10px; width:15%">Unit</th>
															<th style="font-size: 10px; width:10%">Qty Estimated</th>
															<th style="font-size: 10px; width:10%">Used Qty</th>
															<th style="font-size: 10px; width:10%">Available Stocks</th>
														</tr>
													</thead>
													<tbody>

													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="col-lg-12 mt-5">
										<div class="d-lg-flex">
											<h5 class="text-primary">UPAH LIST</h5>
											<button class="btn btn-warning btnsetusedqtyupah d-none btn-sm ml-3"><i class="fas fa-pencil-ruler"></i>
												Set Used
												Qty As
												Estimated
												Qty
											</button>
											<button class="btn btn-danger btnsetusedqtyzeroupah d-none btn-sm ml-3"><i class="fas fa-pencil-ruler"></i>
												Set Used
												Qty = 0
											</button>
										</div>
										<div class="form-group">
											<div class="dataupah" data-upah="{{ json_encode($dataUpah) }}">
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 table-responsive" style="max-height: 400px">
												<table border="1"
													style="width: 100%;  border: 1px solid #000;border-radius: 8px; background-color: #f9f9f9"
													class="table-sm tableupah">
													<thead>
														<tr>
															<th class="text-left" style="font-size: 10px; width:5%"><input type="checkbox" class="checkedallupah">
															</th>
															<th class="text-left" style="font-size: 10px; width:5%">No</th>
															<th class="text-left" style="font-size: 10px;width:10%">Upah Code</th>
															<th class="text-left" style="font-size: 10px; width:15%">Job</th>
															<th class="text-left" style="font-size: 10px;width:5%">Unit</th>
															<th style="font-size: 10px; width:10%">Qty Estimated</th>
															<th style="font-size: 10px; width:10%">Qty Used</th>
															<th style="font-size: 10px; width:10%">Price</th>
															<th style="font-size: 10px; width:15%">Total Estimated</th>
															<th style="font-size: 10px;width:15%">Total Used</th>
														</tr>
													</thead>
													<tbody>

													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-lg-6">
										<button class="btn btn-primary btn-sm btn-submit">
											<i class="fas fa-check"></i>
											<span>
												Submit & Finish Project
											</span>
										</button>
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


	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/project/projectfinish.js" type="module"></script>
@endsection
