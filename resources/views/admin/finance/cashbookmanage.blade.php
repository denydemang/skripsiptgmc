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

		.table-itemm thead th {
			position: sticky;
			top: 0;
			background: #808283
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
								<li class="breadcrumb-item"><a href="{{ route('admin.cashbook') }}">Cashbook</a></li>
								@if ($sessionRoute == 'admin.addCashbookView')
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
								@if ($sessionRoute == 'admin.addCashbookView')
									<h3 class="mb-0">ADD NEW CASHBOOK</h3>
								@else
									<h3 class="mb-0">EDIT CASHBOOK</h3>
								@endif
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Cash No <span style="color: red">*</span></label>
											<input type="text" class="form-control form-control-sm inputcashno" readonly
												value="{{ $sessionRoute == 'admin.addCashbookView' ? 'AUTO' : $data['cashbook']['cash_no'] }}"
												id="example3cols1Input">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Transaction Date <span style="color: red">*</span></label>
											<div class="input-group date mr-2" id="dtptransdate" data-target-input="nearest">
												<input type="text" style="cursor: pointer" class="form-control form-control-sm inputtransdate"
													data-target="#dtptransdate" readonly
													data-transdate="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbook']['transaction_date'] }}" />
												<div class="input-group-append" data-target="#dtptransdate" data-toggle="dtptransdate">
													<div class="input-group-text" style="height: 32px"><i class="fa fa-calendar"></i></div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<label class="form-control-label" for="example3cols1Input">COA For Cash/Bank <span
												style="color: red">*</span></label>
										<div class="d-flex">
											<input type="text" readonly class="form-control form-control-sm inputcoaforcashbank" style="width: 45%"
												id="example3cols1Input"
												value="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbook']['COA_Cash'] }}">
											<input type="text" readonly class="form-control coa-name form-control-sm inputcoaforcashbank"
												id="example3colInput" style="width: 55%"
												value="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbook']['coa_name'] }}">
											@include('component.btnsearchcoa')
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Type <span style="color: red">*</span></label>
											<div class="datatype"
												data-datatype="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbook']['CbpType'] }}"></div>
											<select class="form-control inputtype form-control-sm">
												<option value="P">Payment</option>
												<option value="R">Receive</option>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Ref No</label>
											<input type="text" class="form-control form-control-sm inputref"
												value="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbook']['ref'] }}">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Amount <span style="color: red">*</span></label>
											<input type="text" class="form-control form-control-sm inputAmountCash"
												style="font-weight: bold;color:#353131"
												value="{{ $sessionRoute == 'admin.addCashbookView' ? 'Rp 0,00' : 'Rp ' . number_format($data['cashbook']['total_transaction'], 2, ',', '.') }}">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label"><span class="title-pay"></span> <span style="color: red">*</span></label>
											<input type="text" class="form-control form-control-sm inputdescription"
												value="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbook']['description'] }}">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="card">
							<div class="card-body">
								<div class="col-lg-12 table-responsive table-itemm" style="max-height: 400px">
									<h5 class="text-primary">Coa Expense or Other <span style="color: red">*</span> </h5>
									<table border="1"
										style="max-width: 100%;  border: 1px solid #000;border-radius: 8px; background-color: #f9f9f9"
										class="table-sm tablelistdetailpr">
										<thead class="text-white" style="background-color:#808283">
											<tr>
												<th class="text-left" style="font-size: 10px; width:2%">...</th>
												<th class="text-left" style="font-size: 10px;width:20%">Coa Code</th>
												<th class="text-left" style="font-size: 10px;width:30%">Coa Name</th>
												<th class="text-left" style="font-size: 10px; width:30%">Amount</th>
												<th class="text-left" style="font-size: 10px;width:5%">...</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div class="d-flex">
														<input type="text" readonly hidden class="form-control form-control-sm inputcoalawancode"
															style="width: 45%" id="example3cols1Input"
															value="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbookdetail']['coa_code'] }}">
														<input type="text" readonly hidden class="form-control coa-name form-control-sm inputcoalawanname"
															id="example3colInput" style="width: 55%"
															value="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbookdetail']['coa_name'] }}">
														@include('component.btnsearchcoa')
													</div>
												</td>
												<td style="font-size: 12px; white-space:nowrap"><span class="labelcoacodelawan"></span></td>
												<td style="font-size: 12px"><span class="labelcoanamelawan"></span></td>
												<td style="font-size: 22px;white-space:nowrap"><input type="text"
														data-amountcoalawan="{{ $sessionRoute == 'admin.addCashbookView' ? '' : floatval($data['cashbookdetail']['amount']) }}"
														style="width: 100%;font-weight:bolder;color:rgb(118, 72, 116);font-size:13px"
														class="custom-input inputamountcoalawan" value="">
												</td>
												<td>
													<button class="btn-sm btn btn-danger btndeletecoalawan">X</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								<div class="col-lg-12 table-responsive table-itemm" style="max-height: 400px">
									<h5 class="text-primary">Adjustment <small>Optional</small> </h5>
									<table border="1"
										style="max-width: 100%;  border: 1px solid #000;border-radius: 8px; background-color: #f9f9f9"
										class="table-sm tablelistdetailpr">
										<thead class="text-white" style="background-color:#808283">
											<tr>
												<th class="text-left" style="font-size: 10px; width:2%">...</th>
												<th class="text-left" style="font-size: 10px;width:13%">Coa Code</th>
												<th class="text-left" style="font-size: 10px;width:30%">Coa Name</th>
												<th class="text-left" style="font-size: 10px; width:25%">Debit</th>
												<th class="text-left" style="font-size: 10px;width:25%">Kredit</th>
												<th class="text-left" style="font-size: 10px;width:5%">...</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div class="d-flex">
														<input type="text" readonly hidden class="form-control form-control-sm inputcoacodeadjustment"
															style="width: 45%" id="example3cols1Input"
															value="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbookdetailb']['coa_code'] }}">
														<input type="text" readonly hidden
															class="form-control coa-name form-control-sm inputcoanameadjustment" id="example3colInput"
															style="width: 55%"
															value="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbookdetailb']['coa_name'] }}">
														@include('component.btnsearchcoa')
													</div>
												</td>
												<td style="font-size: 12px; white-space:nowrap"><span class="labelcoacodeadjustment"></span>
												</td>
												<td style="font-size: 12px"><span class="labelcoanameadjustment"></span></td>
												<td style="font-size: 22px;white-space:nowrap"><input type="text"
														data-debitadjustment="{{ $sessionRoute == 'admin.addCashbookView' ? '' : floatval($data['cashbookdetailb']['debit'] ? $data['cashbookdetailb']['debit'] : 0) }}"
														style="width: 100%;font-weight:bolder;font-size:13px" class="custom-input inputdebitadjustment"
														value="">
												</td>
												<td style="font-size: 22px;white-space:nowrap"><input type="text"
														data-debitadjustment="{{ $sessionRoute == 'admin.addCashbookView' ? '' : floatval($data['cashbookdetailb']['credit'] ? $data['cashbookdetailb']['credit'] : 0) }}"
														style="width: 100%;font-weight:bolder;font-size:13px" class="custom-input inputcreditadjustment"
														value="23131399">
												</td>
												<td>
													<button class="btn-sm btn btn-danger btndeleteadjusment">X</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-lg-6 mt-2">
									<label class="form-control-label text-primary">Description Adjusment</label>
									<input type="text" class="form-control form-control-sm inputdescadjusment"
										value="{{ $sessionRoute == 'admin.addCashbookView' ? '' : $data['cashbookdetailb']['description'] }}"
										id="example3cols1Input">
								</div>
								<div class="col-lg-3">
									<button class="btn btn-primary btn-sm submitbtn mt-2 py-2"><i class="fas fa-save mr-1"
											style="font-size: 16px"></i>

										@if ($sessionRoute != 'admin.addCashbookView')
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
		</div>
	</div>

	@include('layout.footer')
	</div>


	{{-- MODAL FORM --}}

	@include('searchModal.suppliersearch')
	@include('searchModal.prsearch')
	@include('searchModal.coasearch')

	{{-- @include('searchModal.coaSearch') --}}


	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/finance/cashbookmanage.js" type="module"></script>
@endsection
