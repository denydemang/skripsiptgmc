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
								<li class="breadcrumb-item"><a href="{{ route('admin.pr') }}">Purchase Request</a></li>
								@if ($sessionRoute == 'admin.addprview')
									<li class="breadcrumb-item active" aria-current="page">Add</li>
								@else
									<li class="breadcrumb-item active" aria-current="page">Edit</li>
								@endif
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
								@if ($sessionRoute == 'admin.addprview')
									<h3 class="mb-0">ADD NEW PURCHASE REQUEST</h3>
								@else
									<h3 class="mb-0">EDIT PURCHASE REQUEST</h3>
								@endif

								<div class="card-body">
									<div class="row">
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Code <span style="color: red">*</span></label>
												<input type="text" class="form-control form-control-sm inputpr_no" readonly
													value="{{ $sessionRoute == 'admin.addprview' ? 'AUTO' : $data['dataPR']['pr_no'] }}"
													id="example3cols1Input">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">PIC (Request Person) <span
														style="color: red">*</span></label>
												<input type="text" class="form-control form-control-sm inputpicname" id="example3cols1Input"
													value="{{ $sessionRoute == 'admin.addprview' ? '' : $data['dataPR']['pic_name'] }}">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Transaction Date <span
														style="color: red">*</span></label>
												<div class="input-group date mr-2" id="dtptransdate" data-target-input="nearest">
													<input type="text" style="cursor: pointer" class="form-control form-control-sm inputtransdate"
														data-target="#dtptransdate" readonly
														data-transdate="{{ $sessionRoute == 'admin.addprview' ? '' : $data['dataPR']['transaction_date'] }}" />
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
												<label class="form-control-label" for="example3cols1Input">Division<span style="color: red">*</span></label>
												<input type="text" class="form-control form-control-sm inputdivision" id="example3cols1Input"
													value="{{ $sessionRoute == 'admin.addprview' ? '' : $data['dataPR']['division'] }}">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Document Ref No<small
														class="ml-2">optional</small></label>
												<input type="text" class="form-control form-control-sm inputrefno" id="example3cols1Input"
													value="{{ $sessionRoute == 'admin.addprview' ? '' : $data['dataPR']['ref_no'] }}">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label" for="example3cols1Input">Date Required <span
														style="color: red">*</span></label>
												<div class="input-group date mr-2" id="dtpdaterequired" data-target-input="nearest">
													<input type="text" style="cursor: pointer" class="form-control form-control-sm inputdaterequired"
														data-target="#dtpdaterequired" readonly
														data-daterequired="{{ $sessionRoute == 'admin.addprview' ? '' : $data['dataPR']['date_need'] }}" />
													<div class="input-group-append" data-target="#dtpdaterequired" data-toggle="dtpdaterequired">
														<div class="input-group-text" style="height: 32px"><i class="fa fa-calendar"></i></div>
													</div>
												</div>
											</div>
										</div>
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
										<h5 class="text-primary mb-3">Item <span style="color: red">*</span></h5>
										@include('component.btnadditem')
										<div class="dataprdetail"
											data-prdetail="{{ $sessionRoute == 'admin.addprview' ? '' : json_encode($data['dataDetail']) }}">
										</div>
									</div>
								</div>
								<div class="row mt-2">
									<div class="col-lg-12">
										<table style="width: 100%" class="table-sm tableitem table">
											<thead style="font-size: 6px">
												<tr class="row">
													<th class="col-2 text-left" style="font-size: 10px">Item Code</th>
													<th class="col-2 text-left" style="font-size: 10px">Item Name</th>
													<th class="col-2" style="font-size: 10px">Unit</th>
													<th class="col-2" style="font-size: 10px">Qty</th>
													<th class="col-2" style="font-size: 10px">Available Stocks</th>
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
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="example3cols1Input">Description <span
													style="color: red">*</span></label>
											<textarea class="form-control inputdescription" name="" id="" cols="30" rows="5">{{ $sessionRoute == 'admin.addprview' ? '' : $data['dataPR']['description'] }}</textarea>
										</div>
									</div>
								</div>
								<button class="btn btn-primary submitbtn mt-2 py-2"><i class="fas fa-save mr-2" style="font-size: 16px"></i>

									@if ($sessionRoute != 'admin.addprview')
										<span>Update</span>
									@else
										<span>Save</span>
									@endif

								</button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		@include('layout.footer')
	</div>


	{{-- MODAL FORM --}}

	@include('searchModal.coasearch')

	@include('searchModal.itemssearch')

	{{-- @include('searchModal.coaSearch') --}}


	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/inventory/purchaserequestmanage.js" type="module"></script>
@endsection
