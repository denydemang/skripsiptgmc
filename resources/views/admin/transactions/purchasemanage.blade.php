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
								<li class="breadcrumb-item"><a href="{{ route('admin.purchase') }}">Purchase</a></li>
								@if ($sessionRoute == 'admin.addPurchaseView')
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
								@if ($sessionRoute == 'admin.addPurchaseView')
									<h3 class="mb-0">ADD NEW PURCHASE</h3>
								@else
									<h3 class="mb-0">EDIT PURCHASE</h3>
								@endif

								<div class="card-body">
									<div class="row">
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label">Purchase No <span style="color: red">*</span></label>
												<input type="text" class="form-control form-control-sm inputpurchaseno" readonly
													value="{{ $sessionRoute == 'admin.addPurchaseView' ? 'AUTO' : $data['purchases']['purchase_no'] }}"
													id="example3cols1Input">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label">Transaction Date <span style="color: red">*</span></label>
												<div class="input-group date mr-2" id="dtptransdate" data-target-input="nearest">
													<input type="text" style="cursor: pointer" class="form-control form-control-sm inputtransdate"
														data-target="#dtptransdate" readonly
														data-transdate="{{ $sessionRoute == 'admin.addPurchaseView' ? '' : $data['purchases']['transaction_date'] }}" />
													<div class="input-group-append" data-target="#dtptransdate" data-toggle="dtptransdate">
														<div class="input-group-text" style="height: 32px"><i class="fa fa-calendar"></i></div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label class="form-control-label">Payment Term<span style="color: red">*</span></label>
												<select class="form-control inputpaymentterm form-control-sm">
													@foreach ($data['paymentTerm'] as $term)
														<option value="{{ $term->code }}"> {{ $term->code }} - {{ $term->term_name }}</option>
													@endforeach
												</select>
												<div class="datapaymentterm"
													data-paymentterm="{{ $sessionRoute == 'admin.addPurchaseView' ? '' : $data['purchases']['payment_term_code'] }}">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4">
											@include('component.supplierCode')
											<div class="datasuppliercode"
												data-suppliercode="{{ $sessionRoute == 'admin.addPurchaseView' ? '' : $data['purchases']['supplier_code'] }}">
											</div>
										</div>
										<div class="col-lg-4">
											@include('component.supplierName')
											<div class="datasuppliername"
												data-suppliername="{{ $sessionRoute == 'admin.addPurchaseView' ? '' : $data['purchases']['supplier_name'] }}">
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
									<div class="col-lg-3">
										@include('component.prCode')
										<div class="dataprcode"
											data-dataprcode="{{ $sessionRoute == 'admin.addPurchaseView' ? '' : $data['purchases']['pr_no'] }}">
										</div>
										<div class="purchasesdetail"
											data-purchasedetail="{{ $sessionRoute == 'admin.addPurchaseView' ? '' : json_encode($data['purchase_detail']) }}">
										</div>
									</div>
								</div>
								<div class="row mt-2">
									<div class="col-lg-12 table-responsive table-itemm" style="max-height: 400px">
										<table border="1"
											style="width: 100%;  border: 1px solid #000;border-radius: 8px; background-color: #f9f9f9"
											class="table-sm tablelistdetailpr">
											<thead class="text-white" style="background-color:#808283">
												<tr>
													<th class="text-left" style="font-size: 10px; width:5%">No</th>
													<th class="text-left" style="font-size: 10px;width:5%">Item Code</th>
													<th class="text-left" style="font-size: 10px;width:15%">Item Name</th>
													<th class="text-left" style="font-size: 10px; width:5%">Unit Code</th>
													<th class="text-left" style="font-size: 10px;width:10%">Qty</th>
													<th style="font-size: 10px; width:15%">Price</th>
													<th style="font-size: 10px; width:15%">Total</th>
													<th style="font-size: 10px; width:10%">Discount</th>
													<th style="font-size: 10px; width:20%">Sub Total</th>
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
								<div class="row" style="position: relative;">
									<div class="col-lg-5 offset-lg-7">
										<div class="form-group row">
											<label class="form-control-label col-6" style="text-align: right;padding-top:3px">Total</label>
											<span class="col-6 bg-warning labeltotal text-white" style="font-weight: bold"></span>
										</div>
									</div>
									<div class="col-lg-5 offset-lg-7">
										<div class="form-group row">
											<label class="form-control-label col-6" style="text-align: right;padding-top:3px">Freight In</label>
											<input type="text" class="form-control inputotherfee form-control-sm col-6 bg-success text-white"
												style="font-weight: bold">
											<div class="dataotherfee"
												data-otherfee="{{ $sessionRoute == 'admin.addPurchaseView' ? '' : $data['purchases']['other_fee'] }}">
											</div>
										</div>
									</div>
									<div class="col-lg-5 offset-lg-7">
										<div class="form-group row">
											<label class="form-control-label col-6" style="text-align: right;padding-top:3px">PPN (%)</label>
											<input type="number" class="form-control inputpercentppn form-control-sm col-2 bg-primary text-white"
												style="font-weight: bold">
											<div class="datapercentppn"
												data-percentppn="{{ $sessionRoute == 'admin.addPurchaseView' ? '' : $data['purchases']['percen_ppn'] }}">
											</div>
											<input type="text" readonly
												class="form-control form-control-sm col-4 inputppnamount bg-primary text-white" style="font-weight: bold">
											<div class="dataamountppn"
												data-amountppn="{{ $sessionRoute == 'admin.addPurchaseView' ? '' : $data['purchases']['ppn_amount'] }}">
											</div>
										</div>
									</div>
									<div class="form-group d-flex" style="position: absolute;top:0;left:0;padding-left:25px">
										<label class="form-control-label mr-5" style="text-align: right;padding-top:3px;font-size:25px"><b>Grand
												Total</b></label>
										<span class="bg-danger labelgrandtotal px-3 text-white" style="font-size:25px;font-weight: bold">
										</span>
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
											<label class="form-control-label">Description <span style="color: red">*</span></label>
											<textarea class="form-control inputdescription" name="" id="" cols="30" rows="5">{{ $sessionRoute == 'admin.addPurchaseView' ? '' : $data['purchases']['description'] }}</textarea>
										</div>
									</div>
								</div>
								<button class="btn btn-primary submitbtn mt-2 py-2"><i class="fas fa-save mr-2" style="font-size: 16px"></i>

									@if ($sessionRoute != 'admin.addPurchaseView')
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

	
	@include('searchModal.prsearch')
	@include('searchModal.suppliersearch')

	{{-- @include('searchModal.coaSearch') --}}


	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/transactions/purchasemanage.js" type="module"></script>
@endsection
