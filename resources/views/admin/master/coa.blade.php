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
								<li class="breadcrumb-item active" aria-current="page">COAList</li>
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
							<div class="card-header border-0">
								<h3 class="mb-0">COA LIST</h3>
							</div>
							<div class="card-body">
								<button class="btn btn-outline-primary btnaddnew mb-3"><i class="fas fa-plus"></i> Add New COA</button>
								<div class="col-lg-12 border p-0">
									<div id="coalist"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer -->
	@include('layout.footer')
	</div>


	{{-- MODAL FORM --}}
	<div class="modal fade" id="modalcoa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog modal-primary" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label for="code">Code</label> <span style="color: red">*</span>
										<input class="form-control inputcode" type="text" name="code" style="font-weight: bolder">
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label for="code">Name</label> <span style="color: red">*</span>
										<input class="form-control inputname" type="text" name="name" style="font-weight: bolder">
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label for="code">Type</label> <span style="color: red">*</span>
										<select class="form-control inputtype" name="type" style="font-weight: bolder">
											<option value="Aktiva">Aktiva</option>
											<option value="Passiva">Passiva</option>
											<option value="Modal">Modal</option>
											<option value="Rugi Laba">Rugi Laba</option>
											<option value="Pendapatan">Pendapatan</option>
											<option value="HPP">HPP</option>
											<option value="HPP">Beban</option>
											<option value="Pendapatan Non Operasional">Pendapatan Non Operasional</option>
											<option value="Beban Non Operasional">Beban Non Operasional</option>
										</select>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label for="code">Level</label> <span style="color: red">*</span>
										<select class="form-control inputlevel" name="type" style="font-weight: bolder">
											<option value="1">Level 1</option>
											<option value="2">Level 2</option>
											<option value="3">Level 3</option>
											<option value="4">Level 4</option>
											<option value="5">Level 5</option>
											<option value="6">Level 6</option>
											<option value="7">Level 7</option>
										</select>
										{{-- <input class="form-control inputlevel" type="number" name="level" style="font-weight: bolder"> --}}
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label for="code">D/K</label> <span style="color: red">*</span>
										<select class="form-control inputdk" name="dk" style="font-weight: bolder">
											<option value="D">D</option>
											<option value="K">K</option>
										</select>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label for="code">Description</label> <span style="color: red">*</span>
										<select class="form-control inputdescription" name="dk" style="font-weight: bolder">
											<option value="Header">Header</option>
											<option value="Detail">Detail</option>
										</select>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label for="code">Beginning Balance</label> <span style="color: red">*</span>
										<input class="form-control inputbeginning" type="text" name="beginningbalance"
											style="font-weight: bolder">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-success btnsave"><span>Save changes</span></button>
				</div>
			</div>
		</div>
	</div>
	{{-- Notif Flash Message --}}
	@include('flashmessage')
	<script src="{{ asset('/') }}js/coa/coa.js" type="module"></script>
@endsection
