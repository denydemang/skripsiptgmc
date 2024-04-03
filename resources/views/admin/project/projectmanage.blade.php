@extends('layout.template')
@section('content')
	<style>
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
								<li class="breadcrumb-item"><a href="{{ route('admin.project') }}">Project</a></li>
								<li class="breadcrumb-item active" aria-current="page">Add</li>
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
							<!-- Card header -->
							<div class="card-header border-0">
								@if ($sessionRoute == 'admin.addProject')
									<h3 class="mb-0">ADD NEW PROJECTS</h3>
								@else
									<h3 class="mb-0">EDIT PROJECTS</h3>
								@endif

								<div class="card-body">
									<div class="row">
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Code <span style="color: red">*</span></label>
												<input type="text" class="form-control form-control-sm inputcode"
													{{ $sessionRoute == 'admin.addProject' ? 'readonly' : '' }}
													value="{{ $sessionRoute == 'admin.addProject' ? 'AUTO' : '' }}" id="example3cols1Input">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">PIC <span style="color: red">*</span></label>
												<input type="text" class="form-control form-control-sm inputpic" id="example3cols1Input">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Transaction Date <span
														style="color: red">*</span></label>
												<div class="input-group date mr-2" id="dtptransdate" data-target-input="nearest">
													<input type="text" style="cursor: pointer" class="form-control form-control-sm inputtransdate"
														data-target="#dtptransdate" readonly />
													<div class="input-group-append" data-target="#dtptransdate" data-toggle="dtptransdate">
														<div class="input-group-text" style="height: 32px"><i class="fa fa-calendar"></i></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Location<span style="color: red">*</span></label>
												<input type="text" class="form-control form-control-sm inputlocation" id="example3cols1Input">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Duration(Days) <span
														style="color: red">*</span></label>
												<input type="number" class="form-control form-control-sm inputduration" id="example3cols1Input">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Budget <span style="color: red">*</span></label>
												<input type="text" class="form-control form-control-sm inputbudget" id="e xample3cols1Input"
													style="font-weight:bold;color:brown">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Project Name<span
														style="color: red">*</span></label>
												<input type="text" class="form-control form-control-sm inputname" id="example3cols1Input">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6">
										@include('component.customerCode')
									</div>
									<div class="col-lg-6">
										@include('component.customerName')
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6">
										@include('component.projectTypeCode')
									</div>
									<div class="col-lg-6">
										@include('component.projectTypeName')
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-4">
										<h5 class="text-primary">Material</h5>
										<div class="form-group">
											<label class="form-control-label" for="example3cols1Input">COA For Expense <span
													style="color: red">*</span></label>
											<div class="d-flex">
												<input type="text" readonly class="form-control form-control-sm inputcoaekspense"
													id="example3cols1Input">
												@include('component.btnsearchcoa')
												@include('component.btnadditem')
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<table style="width: 100%" class="table-sm tablematerial table">
											<thead style="font-size: 6px">
												<tr class="row">
													<th class="col-2 text-left" style="font-size: 10px">Item Code</th>
													<th class="col-3 text-left" style="font-size: 10px">Item Name</th>
													<th class="col-2" style="font-size: 10px">Unit</th>
													<th class="col-2" style="font-size: 10px">Qty</th>
													<th class="col-2" style="font-size: 10px">...</th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-4">
										<h5 class="text-primary">Upah</h5>
										<div class="form-group">
											<label class="form-control-label" for="example3cols1Input">COA For Account Payable <span
													style="color: red">*</span></label>
											<div class="d-flex">
												<input type="text" readonly class="form-control form-control-sm inputcoapayable"
													id="example3cols1Input">
												@include('component.btnsearchcoa')
												@include('component.btnaddupah')
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<table style="width: 100%" class="table-sm tableupah table">
											<thead style="font-size: 6px">
												<tr class="row">
													<th class="col-2 text-left" style="font-size: 10px">Upah Code</th>
													<th class="col-2 text-left" style="font-size: 10px">Job</th>
													<th class="col-1 text-left" style="font-size: 10px">Unit</th>
													<th class="col-2" style="font-size: 10px">Qty</th>
													<th class="col-2" style="font-size: 10px">Price</th>
													<th class="col-2" style="font-size: 10px">Total</th>
													<th class="col-1" style="font-size: 10px">...</th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="example3cols1Input">Description</label>
											<textarea class="form-control inputdescription" name="" id="" cols="30" rows="5"></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<label for="">File Dokumen</label>
										<input type="file" class="form-control">
									</div>
								</div>
								<button class="btn btn-primary submitbtn mt-2 py-2"><i class="fas fa-save mr-2" style="font-size: 16px"></i>
									Save</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<!-- Footer -->
		<footer class="footer pt-0">
			<div class="row align-items-center justify-content-lg-between">
				<div class="col-lg-6">
					<div class="copyright text-lg-left text-muted text-center">
						&copy; 2018
						<a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">PT GENTA MULTI JAYYA</a>
					</div>
				</div>
				<div class="col-lg-6">
					<ul class="nav nav-footer justify-content-center justify-content-lg-end">
						<li class="nav-item">
							<a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
						</li>
						<li class="nav-item">
							<a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
						</li>
						<li class="nav-item">
							<a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
						</li>
						<li class="nav-item">
							<a href="https://www.creative-tim.com/license" class="nav-link" target="_blank">License</a>
						</li>
					</ul>
				</div>
			</div>
		</footer>
	</div>


	{{-- MODAL FORM --}}
	@include('searchModal.customerSearch')

	@include('searchModal.projectTypeSearchModal')

	@include('searchModal.coasearch')

	@include('searchModal.itemssearch')

	@include('searchModal.upahsearch')

	{{-- @include('searchModal.coaSearch') --}}


	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/project/projectmanage.js" type="module"></script>
@endsection
