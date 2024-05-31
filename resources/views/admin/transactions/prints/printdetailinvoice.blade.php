<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Invoice</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			font-size: 14px;
			align-content: center
		}

		.receipt {
			width: 665px;
			/* margin: 0 auto; */
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
			width: 240px;
			margin-left: 30px
		}

		td {
			vertical-align: top;
			word-wrap: break-word;
			word-break: break-all;
			white-space: normal;
			/* Membuat teks bisa wrap sesuai dengan ukuran cell */
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
				<h1>Invoice</h1>
				<p style="font-size: 18px;margin-top:-10px">No: <span>{{ $invoices['invoice_no'] }}</span></p>
			</div>
		</div>
		<div class="content">
			<table>
				<tr>
					<td style="font-weight:bold;width:220px">Kami Tagihkan Kepada</td>
					<td style="font-weight:bold">: {{ $invoices['customer_name'] }}</td>
				</tr>
				<tr>
					<td style="font-weight:bold">Untuk Pembayaran</td>
					<td><span style="font-weight:bold">: {{ $invoices['project_name'] }}</span><br>
						<table border="0" style="margin-top:5px">
							<tr>
								<td style="width:100px">Code Project</td>
								<td>: {{ $invoices['project_code'] }}</td>
							</tr>
							<tr>
								<td style="width:100px">Code Realisasi</td>
								<td>: {{ $invoices['project_realisation_code'] }}</td>
							</tr>
						</table>
						@if ($invoices['bap_no'] || $invoices['bapp_no'] || $invoices['spp_no'])
							&nbsp;Sesuai dengan
							<table>
								@if ($invoices['bap_no'])
									<tr>
										<td style="width:100px">Bap No</td>
										<td>: {{ $invoices['bap_no'] }}</td>
									</tr>
								@endif
								@if ($invoices['bapp_no'])
									<tr>
										<td style="width:100px">Bapp No</td>
										<td>: {{ $invoices['bapp_no'] }}</td>
									</tr>
								@endif
								@if ($invoices['spp_no'])
									<tr>
										<td style="width:100px">SPP No</td>
										<td>: {{ $invoices['spp_no'] }}</td>
									</tr>
								@endif
							</table>
						@endif
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">
						Total Harga Proyek
					</td>
					<td>
						: Rp {{ number_format(floatval($invoices['project_amount']), 2, ',', '.') }}
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">
						Realisasi Proyek Tgl {{ \Carbon\Carbon::parse($invoices['realisation_date'])->format('d-m-Y') }}
					</td>
					<td>
						: Rp {{ number_format(floatval($invoices['realisation_amount']), 2, ',', '.') }}
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">
						PPN ({{ floatval($invoices['percent_ppn']) * 100 }}%)
					</td>
					<td>
						: Rp {{ number_format(floatval($invoices['ppn_amount']), 2, ',', '.') }}
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">
						Jumlah Ditagihkan
					</td>
					<td>
						: Rp {{ number_format(floatval($invoices['grand_total']), 2, ',', '.') }}
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">
						Jumlah Yang Terbayar
					</td>
					<td>
						: Rp {{ number_format(floatval($invoices['paid_amount']), 2, ',', '.') }}
					</td>
				</tr>
				<tr>
					<td style="font-weight:bold">
						Jumlah Kurang Bayar
					</td>
					<td style="font-weight:bold">
						: Rp {{ number_format(floatval($invoices['grand_total'] - $invoices['paid_amount']), 2, ',', '.') }}
					</td>
				</tr>
			</table>
		</div>
		@if (floatval($invoices['grand_total'] - $invoices['paid_amount']) > 0)
			<h4 style="padding-left:10px">Terbilang :</h4>
			<span style="padding-left:40px;font-weight:bold"><i style="padding-left:10px">{{ $terbilang }} Rupiah</i> </span>
		@else
			<h4 style="padding-left:10px">Status : Lunas</h4>
		@endif


		<div class="footer" style="padding-top:80px">
			<table style="font-size:12px;font-weight:bold;float:left">
				<tr>
					<td><i>Pembayaran Melalui:</i></td>
				</tr>
				<tr>
					<td><i>No Rekening</i></td>
					<td><i>: 1229588939 (Bank BNI)</i></td>
				</tr>
				<tr>
					<td><i>Atas Nama</i></td>
					<td><i>: PT GENTA MULTI JAYYA</i></td>
				</tr>
			</table>
			{{-- {{ \Carbon\Carbon::parse($payment['transaction_date'])->isoFormat('DD MMMM YYYY') }}</p> --}}
			<p style="text-align:right;font-weight:bold;padding-right:20px">Semarang,
				{{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }}</p>
			<p style="margin-bottom:90px;text-align:right;font-size:11px;font-weight:bold;padding-right:20px">PT GENTA MULTI
				JAYYA</p>

			<p style="text-align:right;padding-right:20px"><span style="display:inline-block;padding-right:120px"></span></p>
			<p style="text-align:right;padding-right:20px"><span
					style="border-bottom:1px solid black;width:122px;display:inline-block"></span></p>
			<p style="text-align:right;padding-right:20px"><span style="padding-right:40px;font-size:11px">Direktur</span></p>
		</div>
	</div>
</body>

</html>
