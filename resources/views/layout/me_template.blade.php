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


	<!-- Core -->
	<script src="{{ asset('/') }}assets/vendor/jquery/dist/jquery.min.js"></script>
	<script src="{{ asset('/') }}assets/jstree/dist/jstree.min.js"></script>
	<script src="{{ asset('/') }}assets/jstree/dist/jstreegrid.js"></script>
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
	<style>
		.vakata-context {
			z-index: 999 !important;
		}

		.blink-bg {
			color: #fff;
			padding: 10px;
			/* background-color: #df3f49; */
			/* Warna dasar merah */
			display: inline-block;
			border-radius: 5px;
			animation: blinkingBackground 1.2s infinite ease-in;
		}

		@keyframes blinkingBackground {
			0% {
				opacity: 0.1;
				color: red
					/* Mulai dari transparan */
			}

			25% {
				opacity: 0.7;
				color: purple
					/* Mencapai kejelasan maksimum di tengah */
			}

			50% {
				opacity: 1;
				color: brown
					/* Mencapai kejelasan maksimum di tengah */
			}

			60% {
				opacity: 1;
				/* Mencapai kejelasan maksimum di tengah */
			}

			75% {
				opacity: 0.7;
				/* Mencapai kejelasan maksimum di tengah */
			}

			100% {
				opacity: 0.1;
				/* Kembali menjadi transparan */
			}
		}
	</style>
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
