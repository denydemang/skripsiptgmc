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
								<li class="breadcrumb-item"><a href="{{ route('admin.invoice') }}">Invoice</a></li>
								@if ($sessionRoute == 'admin.addInvoiceView')
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
								@if ($sessionRoute == 'admin.addInvoiceView')
									<h3 class="mb-0">ADD NEW INVOICE</h3>
								@else
									<h3 class="mb-0">EDIT INVOICE</h3>
								@endif
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Invoice No <span style="color: red">*</span></label>
											<input type="text" class="form-control form-control-sm inputinvoiceno" readonly
												value="{{ $sessionRoute == 'admin.addInvoiceView' ? 'AUTO' : $data['invoices']['invoice_no'] }}"
												id="example3cols1Input">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Transaction Date <span style="color: red">*</span></label>
											<div class="input-group date mr-2" id="dtptransdate" data-target-input="nearest">
												<input type="text" style="cursor: pointer" class="form-control form-control-sm inputtransdate"
													data-target="#dtptransdate" readonly
													data-transdate="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['transaction_date'] }}" />
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
												data-paymentterm="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['payment_term_code'] }}">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Bap No </label>
											<input type="text" class="form-control form-control-sm inputbapno"
												value="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['bap_no'] }}"
												id="example3cols1Input">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Bapp No </label>
											<input type="text" class="form-control form-control-sm inputbappno"
												value="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['bapp_no'] }}"
												id="example3cols1Input">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">SPP No</label>
											<input type="text" class="form-control form-control-sm inputsppno"
												value="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['spp_no'] }}"
												id="example3cols1Input">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										@include('component.customercode')
										<div class="datacustomercode"
											data-customercode="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['customer_code'] }}">
										</div>
									</div>
									<div class="col-lg-4">
										@include('component.customerName')
										<div class="datacustomerName"
											data-customername="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['customer_name'] }}">
										</div>
									</div>
									<div class="col-lg-4">
										<label class="form-control-label" for="example3cols1Input">COA For Revenue <span
												style="color: red">*</span></label>
										<div class="d-flex">
											<input type="text" readonly class="form-control form-control-sm inputcoacodeforrevenue"
												style="width: 45%" id="example3cols1Input"
												value="40.01">
											<input type="text" readonly class="form-control coa-name form-control-sm inputcoanameforrevenue"
												id="example3colInput" style="width: 55%"
												value="Pendapatan Usaha">
											{{-- @include('component.btnsearchcoa') --}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Project Realisation No <span style="color: red">*</span></label>
											<div class="d-flex">
												<input type="text" class="form-control form-control-sm inputprojectrealisationno" readonly
													value="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['project_realisation_code'] }}"
													id="example3cols1Input">
												@include('component.btnsearchprojectrealisation')
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Project Name <span style="color: red">*</span></label>
											<input type="text" class="form-control form-control-sm inputprojectname" readonly
												value="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['project_name'] }}"
												id="example3cols1Input">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="row" style="position: relative;">
									<div class="col-lg-5 offset-lg-7">
										<div class="form-group row">
											<label class="form-control-label col-6" style="text-align: right;padding-top:3px">Total</label>
											<span class="col-6 bg-warning labeltotal text-white"
												style="font-weight: bold">{{ $sessionRoute == 'admin.addInvoiceView' ? '' : 'Rp ' . number_format($data['invoices']['total'], 2, ',', '.') }}</span>
										</div>
									</div>
									<div class="col-lg-5 offset-lg-7">
										<div class="form-group row">
											<label class="form-control-label col-6" style="text-align: right;padding-top:3px">PPN (%)</label>
											<input type="number" class="form-control inputpercentppn form-control-sm col-2 bg-primary text-white"
												style="font-weight: bold">
											<div class="datapercentppn"
												data-percentppn="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['percent_ppn'] }}">
											</div>
											<input type="text" readonly
												class="form-control form-control-sm col-4 inputppnamount bg-primary text-white" style="font-weight: bold">
											<div class="dataamountppn"
												data-amountppn="{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['ppn_amount'] }}">
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
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label">Description <span style="color: red">*</span></label>
											<textarea class="form-control inputdescription" name="" id="" cols="30" rows="5">{{ $sessionRoute == 'admin.addInvoiceView' ? '' : $data['invoices']['description'] }}</textarea>
										</div>
									</div>
								</div>
								<button class="btn btn-primary submitbtn mt-2 py-2"><i class="fas fa-save mr-2" style="font-size: 16px"></i>

									@if ($sessionRoute != 'admin.addInvoiceView')
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

	@include('searchModal.coasearch')
	@include('searchModal.customersearch')
	@include('searchModal.projectRealisationSearch')

	{{-- @include('searchModal.coaSearch') --}}


	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/transactions/invoicemanage.js" type="module"></script>
@endsection
