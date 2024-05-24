<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="Start your development with a Dashboard for Bootstrap 4." />
	<meta name="author" content="Creative Tim" />
	<title>{{ $title }}</title>
	<!-- Favicon -->
	<link rel="icon" href="https://www.aragon.cm38.de/assets/img/brand/favicon.png" type="image/png" />
	<!-- Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" />
	<!-- Icons -->
	<link rel="stylesheet" href="{{ asset('/') }}assets/vendor/nucleo/css/nucleo.css" type="text/css" />
	<link rel="stylesheet" href="{{ asset('/') }}assets/vendor/@fortawesome/fontawesome-free/css/all.min.css"
		type="text/css" />
	<!-- Page plugins -->
	<!-- Argon CSS -->
	<link rel="stylesheet" href="{{ asset('/') }}assets/css/argon.css?v=1.0.0" type="text/css" />
	<link rel="stylesheet" href="{{ asset('/') }}assets/Datatables/DataTables-2.0.3/css/dataTables.foundation.min.css"
		type="text/css" />
	<link rel="stylesheet"
		href="{{ asset('/') }}assets/Datatables/FixedColumns-5.0.0/css/fixedColumns.foundation.min.css" type="text/css" />
	<link rel="stylesheet" href="{{ asset('/') }}assets/daterangepicker-master/daterangepicker.css" type="text/css" />
	<link rel="stylesheet" href="{{ asset('/') }}assets/fontawesome/css/fontawesome.min.css" type="text/css" />
	<link rel="stylesheet" href="{{ asset('/') }}assets/izitoast/css/iziToast.min.css" type="text/css" />
	<link rel="stylesheet" href="{{ asset('/') }}assets/jquery-confirm-v3.3.4/css/jquery-confirm.css"
		type="text/css" />
	<link rel="stylesheet" href="{{ asset('/') }}assets/bootstrap-datepicker/bootstrap-datepicker.min.css"
		type="text/css" />
        {{-- Select2 --}}
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.2/dist/css/select2.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('/') }}assets/select2/select2.min.css" type="text/css" />
    <style>
		body {
			background: #f0f0f0;
			padding: 50px;
		}

		.container {
			background: #fff;
			border: 1px solid #ccc;
			padding: 30px;
		}

		.form-control {
			border: 1px solid #ccc;
			border-radius: 3px;
			box-shadow: none !important;
			margin-bottom: 15px;
		}

		.form-control:focus {
			border: 1px solid #34495e;
		}

		.select2.select2-container {
			width: 100% !important;
		}

		.select2.select2-container .select2-selection {
			border: 1px solid #ccc;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
			height: 45px;
			margin-bottom: 15px;
			outline: none !important;
			transition: all .15s ease-in-out;
		}

		.select2.select2-container .select2-selection .select2-selection__rendered {
			color: #333;
			line-height: 23px;
			padding-right: 33px;
		}

		.select2.select2-container .select2-selection .select2-selection__arrow {
			background: #f8f8f8;
			border-left: 1px solid #ccc;
			-webkit-border-radius: 0 3px 3px 0;
			-moz-border-radius: 0 3px 3px 0;
			border-radius: 0 3px 3px 0;
			height: 32px;
			width: 33px;
		}

		.select2.select2-container.select2-container--open .select2-selection.select2-selection--single {
			background: #f8f8f8;
		}

		.select2.select2-container.select2-container--open .select2-selection.select2-selection--single .select2-selection__arrow {
			-webkit-border-radius: 0 3px 0 0;
			-moz-border-radius: 0 3px 0 0;
			border-radius: 0 3px 0 0;
		}

		.select2.select2-container.select2-container--open .select2-selection.select2-selection--multiple {
			border: 1px solid #34495e;
		}

		.select2.select2-container .select2-selection--multiple {
			height: auto;
			min-height: 34px;
		}

		.select2.select2-container .select2-selection--multiple .select2-search--inline .select2-search__field {
			margin-top: 0;
			height: 32px;
		}

		.select2.select2-container .select2-selection--multiple .select2-selection__rendered {
			display: block;
			padding: 0 4px;
			line-height: 29px;
		}

		.select2.select2-container .select2-selection--multiple .select2-selection__choice {
			background-color: #f8f8f8;
			border: 1px solid #ccc;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
			margin: 4px 4px 0 0;
			padding: 0 6px 0 22px;
			height: 24px;
			line-height: 24px;
			font-size: 12px;
			position: relative;
		}

		.select2.select2-container .select2-selection--multiple .select2-selection__choice .select2-selection__choice__remove {
			position: absolute;
			top: 0;
			left: 0;
			height: 22px;
			width: 22px;
			margin: 0;
			text-align: center;
			color: #e74c3c;
			font-weight: bold;
			font-size: 16px;
		}

		.select2-container .select2-dropdown {
			background: transparent;
			border: none;
			margin-top: -5px;
		}

		.select2-container .select2-dropdown .select2-search {
			padding: 0;
		}

		.select2-container .select2-dropdown .select2-search input {
			outline: none !important;
			border: 1px solid #34495e !important;
			border-bottom: none !important;
			padding: 4px 6px !important;
		}

		.select2-container .select2-dropdown .select2-results {
			padding: 0;
		}

		.select2-container .select2-dropdown .select2-results ul {
			background: #fff;
			border: 1px solid #34495e;
		}

		.select2-container .select2-dropdown .select2-results ul .select2-results__option--highlighted[aria-selected] {
			background-color: #3498db;
		}
	</style>

	<!-- Core -->
	<script src="{{ asset('/') }}assets/vendor/jquery/dist/jquery.min.js"></script>
	<script src="{{ asset('/') }}assets/Datatables/datatables.min.js"></script>
	<script src="{{ asset('/') }}assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="{{ asset('/') }}assets/Datatables/fixedColumns-5.0.0/js/dataTables.fixedColumns.js"></script>
	<script src="{{ asset('/') }}assets/Datatables/fixedColumns-5.0.0/js/fixedColumns.dataTables.min.js"></script>
	<script src="{{ asset('/') }}assets/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script src="{{ asset('/') }}assets/vendor/js-cookie/js.cookie.js"></script>
	<script src="{{ asset('/') }}assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
	<script src="{{ asset('/') }}assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
	<script src="{{ asset('/') }}assets/vendor/lavalamp/js/jquery.lavalamp.min.js"></script>
	<!-- Optional JS -->
    {{-- Select2 --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.2/dist/js/select2.min.js"></script> --}}
    <script src="{{ asset('/') }}assets/select2/select2.min.js"></script>
	{{-- <script src="{{ mix('js/ziggy.js') }}"></script> --}}
	@routes
</head>

<body>
	@include('layout.sidenav')
	<!-- Main content -->
	<div class="main-content" id="panel">
		@include('layout.topnav')
		{{-- @include('layout.header') --}}
		@yield('content')
	</div>

</body>

<!-- Argon Scripts -->

<script src="{{ asset('/') }}assets/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('/') }}assets/vendor/chart.js/dist/Chart.extension.js"></script>
<script src="{{ asset('/') }}assets/vendor/chart.js/dist/Chart.extension.js"></script>
<script src="{{ asset('/') }}assets/fontawesome/js/fontawesome.min.js"></script>
<script src="{{ asset('/') }}assets/izitoast/js/iziToast.min.js"></script>
<script src="{{ asset('/') }}assets/jquery-confirm-v3.3.4/js/jquery-confirm.js"></script>
<script src="{{ asset('/') }}assets/daterangepicker-master/moment.min.js"></script>
<script src="{{ asset('/') }}assets/daterangepicker-master/daterangepicker.js"></script>

<!-- Argon JS -->
<script src="{{ asset('/') }}assets/js/argon.js?v=1.0.0"></script>
<!-- Demo JS - remove this in your project -->


</html>
