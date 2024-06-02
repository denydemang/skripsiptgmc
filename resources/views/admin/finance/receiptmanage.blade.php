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
								<li class="breadcrumb-item"><a href="{{ route('admin.receipt') }}">Receipt</a></li>
								@if ($sessionRoute == 'admin.addReceiptView')
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
				<div class="card">
					<!-- Card header -->
					<div class="card-header border-0">
						@if ($sessionRoute == 'admin.addReceiptView')
							<h3 class="mb-0">ADD NEW RECEIPT</h3>
						@else
							<h3 class="mb-0">EDIT RECEIPT</h3>
						@endif
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
									<label class="form-control-label">BKM No <span style="color: red">*</span></label>
									<input type="text" class="form-control form-control-sm inputbkmno" readonly
										value="{{ $sessionRoute == 'admin.addReceiptView' ? 'AUTO' : $data['receipt']['bkm_no'] }}"
										id="example3cols1Input">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="form-control-label">Transaction Date <span style="color: red">*</span></label>
									<div class="input-group date mr-2" id="dtptransdate" data-target-input="nearest">
										<input type="text" style="cursor: pointer" class="form-control form-control-sm inputtransdate"
											data-target="#dtptransdate" readonly
											data-transdate="{{ $sessionRoute == 'admin.addReceiptView' ? '' : $data['receipt']['transaction_date'] }}" />
										<div class="input-group-append" data-target="#dtptransdate" data-toggle="dtptransdate">
											<div class="input-group-text" style="height: 32px"><i class="fa fa-calendar"></i></div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="form-control-label">Received Via<span style="color: red">*</span></label>
									<select class="form-control inputreceivedvia form-control-sm">
										<option value="Transfer Bank">Transfer Bank</option>
										<option value="Cash/Tunai">Cash/Tunai</option>
										<option value="M-Banking">M-Banking</option>
									</select>
									<div class="datareceivedvia"
										data-receivedvia="{{ $sessionRoute == 'admin.addReceiptView' ? '' : $data['receipt']['received_via'] }}">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-4">
								@include('component.customerCode')
								<div class="datacustomercode"
									data-customercode="{{ $sessionRoute == 'admin.addReceiptView' ? '' : $data['receipt']['customer_code'] }}">
								</div>
							</div>
							<div class="col-lg-4">
								@include('component.customerName')
								<div class="datacustomername"
									data-customername="{{ $sessionRoute == 'admin.addReceiptView' ? '' : $data['receipt']['customer_name'] }}">
								</div>
							</div>
							<div class="col-lg-4">
								<label class="form-control-label" for="example3cols1Input">COA For Cash/Bank <span
										style="color: red">*</span></label>
								<div class="d-flex">
									<input type="text" readonly class="form-control form-control-sm inputcoaforcashbank" id="example3cols1Input"
										value="{{ $sessionRoute == 'admin.addReceiptView' ? '' : $data['receipt']['coa_cash_code'] }}">
									@include('component.btnsearchcoa')
								</div>
							</div>
							<div class="col-lg-4 mb-4">
								<label class="form-control-label" for="example3cols1Input">Deposit Amount</label>
								<div class="d-flex">
									<input type="text" readonly class="form-control form-control-sm inputdepositamount" id="example3cols1Input"
										data-depositamount="{{ $sessionRoute == 'admin.addReceiptView' ? '' : $data['deposit_amount'] }}">
								</div>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-lg-12 table-responsive table-itemm" style="max-height: 400px">
								<div class="datadetail"
									data-detail="{{ $sessionRoute == 'admin.addReceiptView' ? '' : json_encode($data['detail']) }}">
								</div>
								<div class="datacash_amount"
									data-cashamount="{{ $sessionRoute == 'admin.addReceiptView' ? '' : $data['receipt']['cash_amount'] }}">
								</div>
								<div class="datadeposit_amount"
									data-depositamount="{{ $sessionRoute == 'admin.addReceiptView' ? '' : $data['receipt']['deposit_amount'] }}">
								</div>
								<div class="datatotal_amount"
									data-totalamount="{{ $sessionRoute == 'admin.addReceiptView' ? '' : $data['receipt']['total_amount'] }}">
								</div>
								<table border="1"
									style="width: 100%;  border: 1px solid #000;border-radius: 8px; background-color: #f9f9f9"
									class="table-sm tablelistinvoice">
									<thead class="text-white" style="background-color:#808283">
										<tr>
											<th class="text-left" style="font-size: 10px;width:10%">INV No</th>
											<th class="text-left" style="font-size: 10px;width:10%">Transaction Date</th>
											<th class="text-left" style="font-size: 10px; width:10%">Due Date</th>
											<th class="text-left" style="font-size: 10px;width:15%">UnPaid Amount</th>
											<th style="font-size: 10px; width:15%">Cash Amount</th>
											<th style="font-size: 10px; width:15%">Deposit Amount</th>
											<th style="font-size: 10px; width:15%">Balance Unpaid Amount</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label class="form-control-label">Description <span style="color: red">*</span></label>
									<textarea class="form-control inputdescription" name="" id="" cols="30" rows="5">{{ $sessionRoute == 'admin.addReceiptView' ? '' : $data['receipt']['description'] }}</textarea>
								</div>
							</div>
						</div>
						<button class="btn btn-primary submitbtn mt-2 py-2"><i class="fas fa-save mr-2" style="font-size: 16px"></i>

							@if ($sessionRoute != 'admin.addReceiptView')
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

	@include('layout.footer')
	</div>


	{{-- MODAL FORM --}}

	@include('searchModal.customersearch')
	{{-- @include('searchModal.prsearch') --}}
	@include('searchModal.coasearch')

	{{-- @include('searchModal.coaSearch') --}}


	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/finance/receiptmanage.js" type="module"></script>
@endsection
