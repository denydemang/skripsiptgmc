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
								<li class="breadcrumb-item active" aria-current="page">CashBook</li>
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
								<h3 class="mb-0">CashBook</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12 d-lg-flex justify-content-between">
										<div>
											<div class="d-lg-flex justify-content-beetwen">
												<div class="mb-2 mr-2" style="width: 200px">
													<h4>Status Approve</h4>
													<select class="form-control" id="statusSelectApprove">
														<option selected value="">All</option>
														<option value="0">Not Approved</option>
														<option value="1">Approved</option>
													</select>
												</div>
												<div class="mb-2" style="width: 200px">
													<h4>Type</h4>
													<select class="form-control" id="statusSelectType">
														<option selected value="">All</option>
														<option value="P">Payment</option>
														<option value="R">Receive</option>
													</select>
												</div>
											</div>
										</div>
										<div>

											<div class="mr-1">
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
								</div>
								<br>
								<a href="{{ route('admin.addCashbookView') }}">
									<button class="btn btn-outline-primary btn-sm addbtn mb-2">
										<i class="fas fa-plus"></i> ADD NEW
									</button>
								</a>
								<div class="row p-2" style="border: 2px solid #99cdf0">
									<div class="col-lg-12">
										<table class="align-items-center table-flush cashbooktable w-100 table">
											<thead class="thead-light">
												<tr>
													<th scope="col">Actions</th>
													<th scope="col">Cash No</th>
													<th scope="col">COA Cash</th>
													<th scope="col">COA Name</th>
													<th scope="col">Ref No</th>
													<th scope="col">Description</th>
													<th scope="col">Type</th>
													<th scope="col">Total Amount</th>
													<th scope="col">Approve</th>
													<th scope="col">Approved By</th>
													<th scope="col">Created By</th>
													<th scope="col">Updated By</th>
												</tr>
											</thead>
											<tbody class="list">
											</tbody>
										</table>
										<h5 class="text-primary">Cash Book No : <span class="Cbdetailno"></span></h5>
										<table class="align-items-center table-flush cashbooktable1 w-100 table">
											<thead class="thead-light">
											</thead>
											<tbody class="list">
											</tbody>
										</table>
									</div>
								</div>
								<div class="row mt-2 p-2" style="border: 2px solid #99cdf0">
									<div class="col-lg-12">
										<h5 class="text-primary">Adjustment Cash Book No : <span class="Cbdetailno"></span></h5>
										<table class="align-items-center table-flush cashbooktable2 w-100 table">
											<thead class="thead-light">
											</thead>
											<tbody class="list">
											</tbody>
										</table>
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
	<div class="modal fade" id="modal-detailpurchase" tabindex="-1" role="dialog"
		aria-labelledby="exampleModalLabel">
		<div class="modal-dialog modal-lg modal-success" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title titleview"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">

						<h3 class="title-detail text-white"></h3>
					</div>
					<div class="row">
						<h3 class="mt-1 text-white">Detail Purchase Item :</h3>
						<div class="w-100" style="max-height:200px ;overflow-y: scroll">
							<table class="listbb w-100" border="1" style="border-collapse: collapse;font-style:12px">
								<thead>
									<tr>
										<th style="border-color: rgb(142, 237, 175);padding-left:10px"> No</th>
										<th style="border-color: rgb(142, 237, 175); padding-left:10px">Item Code</th>
										<th style="border-color: rgb(142, 237, 175); padding-left:10px">Item Name</th>
										<th style="border-color: rgb(142, 237, 175); padding-left:10px">Qty</th>
										<th style="border-color: rgb(142, 237, 175); padding-left:10px">Unit</th>
										<th style="border-color: rgb(142, 237, 175); padding-left:10px">Price</th>
										<th style="border-color: rgb(142, 237, 175); padding-left:10px">Sub Total</th>
										<th style="border-color: rgb(142, 237, 175); padding-left:10px">Discount</th>
										<th style="border-color: rgb(142, 237, 175);padding-left:10px">Total</th>
									</tr>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>

	{{-- Modal Search --}}

	@include('searchModal.suppliersearch')

	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/finance/cashbook.js" type="module"></script>
@endsection
