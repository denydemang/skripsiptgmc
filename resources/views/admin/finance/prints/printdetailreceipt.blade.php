<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Kwitansi Pembayaran Invoice {{ $receives['bkm_no'] }} </title>
	<style>
		body {
			font-family: Arial, sans-serif;
			font-size: 12px;
		}

		.receipt {
			width: 700px;
			margin: 0 auto;
			padding: 20px;
			border: 1px solid #000;
		}

		.header {
			display: table;
			width: 100%;
			margin-bottom: 20px;
		}

		.logo {
			display: table-cell;
			width: 120px;
		}

		.logo img {
			width: 100%;
		}

		.company-info {
			display: table-cell;
			width: 300px;
			vertical-align: top;
			padding-left: 10px;
		}

		.receipt-info {
			display: table-cell;
			width: 250px;
			text-align: right;
			vertical-align: top;
		}

		h2 {
			margin: 0 0 10px 0;
			font-size: 18px;
		}

		.content table {
			width: 100%;
			margin-bottom: 20px;
			border-collapse: collapse;
			text-align: left;
		}

		.content td {
			padding: 5px;
		}

		.amount {
			text-align: right;
			font-size: 16px;
			font-weight: bold;
		}

		.footer {
			margin-top: 20px;
		}

		.footer p {
			margin: 5px 0;
		}

		.notes {
			margin-top: 20px;
		}

		.notes ol {
			padding-left: 20px;
		}

		.imglogo {
			width: 210px;
			margin-left: 30px
		}
	</style>
</head>

<body>
	@php
		Illuminate\Support\Facades\App::setLocale('id');
	@endphp
	<div class="receipt">
		<div class="header">
			<div style="margin-left:-60px">
				@include('layout.logoimage')
			</div>
			<div class="receipt-info">
				<h2>KUITANSI</h2>
				<p>No.: <span>{{ $receives->bkm_no }}</span></p>
			</div>
		</div>
		<div class="content">
			<table border="1">
				<tr>
					<td style="width:150px"><b>Telah Diterima Dari</b></td>
					<td>{{ $receives->customer_name }}</td>
				</tr>
				<tr>
					<td><b>Banyaknya Uang</b></td>
					<td>{{ $receives->terbilang }} Rupiah</td>
				</tr>
				<tr>
					<td><b>Guna Pembayaran</b></td>
					<td>INVOICE : {{ $receives->invoice_no }} - ProjectRealisasi No : {{ $receives->project_realisation_code }} -
						{{ $receives->project_name }}</td>
				</tr>
				<tr>
					<td><b>Metode Pembayaran</b></td>
					<td>{{ $receives->received_via }}</td>
				</tr>
			</table>

			<h3>Description: </h3><span>{{ $receives->description }}</span>
			<div class="amount">
				<p>Rp. <span>{{ number_format($receives->total_amount, 2, ',', '.') }}</span></p>
			</div>
		</div>
		<div class="footer">
			<p style="margin-bottom:68px">Semarang,
				{{ \Carbon\Carbon::parse($receives['transaction_date'])->isoFormat('DD MMMM YYYY') }}</p>

			<p style="padding-right: 60px">(<span style="display:inline-block;padding-right:120px"></span>)</p>
		</div>
	</div>
</body>

</html>
