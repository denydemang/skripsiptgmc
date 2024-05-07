<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		body {
			font-family: Verdana, Geneva, Tahoma, sans-serif;
			margin: 0;
			padding: 0;
			position: relative;
		}

		.no-wrap {
			white-space: nowrap;
		}

		header {
			background-color: #f2f2f2;
			padding: 10px;
			text-align: center;
		}

		.transaction {
			margin: 2px;
			position: relative;
		}

		.transaction-info {
			margin-bottom: 20px;
			position: relative;
		}


		.transaction-info p {
			text-align: right;
			margin: 5px 0;
		}

		#detail {
			border-collapse: collapse;
			width: 100%;
		}

		#detail th,
		#detail td {
			border: 1px solid #dddddd;
			text-align: left;
			padding: 8px;
		}

		/* Style the header */
		#detail th {
			background-color: #f2f2f2;
			color: #333;
		}

		/* Style alternate rows */
		/* #detail tbody tr:nth-child(even) {
			background-color: #f2f2f2;
		} */
		.headerdate {

			background-color: #f2f2f2;
		}

		/* Hover effect */
		#detail tbody tr:hover {
			background-color: #ddd;
		}

		/* Style the first column (No) */
		#detail tbody td:first-child {
			width: 50px;
		}

		/* Style the Debit and Kredit columns */
		#detail tbody td:nth-child(5),
		#detail tbody td:nth-child(6) {
			text-align: right;
		}

		/* Style the empty Kredit cell */
		#detail tbody td:nth-child(6):empty {
			color: gray;
		}
	</style>
</head>

<body>
	@php
		Illuminate\Support\Facades\App::setLocale('id');
	@endphp
	<header style="margin-bottom: 50px">
		<h3>PT GENTA MULTI JAYYA</h3>
		<h3 style="margin-top:-10px ">STOCK CARD</h3>
		<h3 style="margin-top:-10px">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
			{{ \Carbon\Carbon::parse($lastDate)->format('d/m/Y') }}</h3>
	</header>

	<div class="transaction">
		<div class="transaction-info">
			<table border="0" cellpadding="4" style="padding: 10px">
				<tr>
					<td><strong>ITEM CODE</strong></td>
					<td>:</td>
					<td><strong>{{ $itemDesc->code }}</strong></td>
				</tr>
				<tr>
					<td><strong>ITEM NAME</strong></td>
					<td>:</td>
					<td><strong>{{ $itemDesc->name }}</strong></td>
				</tr>
				<tr>
					<td><strong>UNIT/SATUAN</strong></td>
					<td>:</td>
					<td><strong>{{ $itemDesc->unit_code }}</strong></td>
				</tr>
			</table>
		</div>


		<table id="detail">
			<thead>
				<tr>
					<th rowspan="2">Date Item</th>
					<th rowspan="2">Ref Trans No</th>
					<th rowspan="2">Description</th>
					<th colspan="3" style="text-align: center">QTY</th>
					<th rowspan="2">COGS</th>
					<th colspan="3" style="text-align: center">QTY x COGS</th>
					</th>
				</tr>
				<tr>
					<th style="text-align: center">IN</th>
					<th style="text-align: center">OUT</th>
					<th style="text-align: center">BALANCE</th>
					<th style="text-align: center">IN</th>
					<th style="text-align: center">OUT</th>
					<th style="text-align: center">BALANCE</th>
				</tr>
			</thead>

			<tbody>
				@php
					Illuminate\Support\Facades\App::setLocale('id');
					$BalanceQTY = 0;
					$BalanceQTYCOGS = 0;
				@endphp

				@if (count($begginningstock) > 0)
					{{-- Stock AWAL --}}
					@foreach ($begginningstock as $item)
						@php
							$BalanceQTY += floatval($item->beginning_balance);
							$BalanceQTYCOGS += floatval($item->total_cogs);
						@endphp
						<tr>
							<td></td>
							<td>Last Balance</td>
							<td>Last Balance</td>
							<td>{{ floatval($item->beginning_balance) }}</td>
							<td>0</td>
							<td>{{ $BalanceQTY }}</td>
							<td>Rp. {{ number_format($item->cogs, 2, ',', '.') }}</td>
							<td>Rp. {{ number_format($item->total_cogs, 2, ',', '.') }}</td>
							<td>Rp. 0</td>
							<td>Rp. {{ number_format($BalanceQTYCOGS, 2, ',', '.') }}</td>
						</tr>
					@endforeach
				@else
					<tr>
						<td></td>
						<td>Last Balance</td>
						<td>Last Balance</td>
						<td>{{ $BalanceQTY }}</td>
						<td>0</td>
						<td>{{ $BalanceQTY }}</td>
						<td>Rp. 0</td>
						<td>Rp. 0</td>
						<td>Rp. 0</td>
						<td>Rp. {{ $BalanceQTYCOGS }}</td>
					</tr>
				@endif

				@if (count($stocks) > 0)
					@foreach ($stocks as $item)
						@php

							if ($item->keterangan == 'Pembelian') {
							    $BalanceQTY += floatval($item->qty);
							    $BalanceQTYCOGS += floatval($item->total_cogs);
							} else {
							    $BalanceQTY -= floatval($item->qty);
							    $BalanceQTYCOGS -= floatval($item->total_cogs);
							}

						@endphp
						<tr>
							<td>{{ \Carbon\Carbon::parse($item->item_date)->format('d/m/Y') }}</td>
							<td>{{ $item->ref_no }}</td>
							<td>{{ $item->keterangan }}</td>
							<td>{{ $item->keterangan == 'Pembelian' ? floatval($item->qty) : 0 }}</td>
							<td>{{ $item->keterangan != 'Pembelian' ? floatval($item->qty) : 0 }}</td>
							<td>{{ $BalanceQTY }}</td>
							<td>Rp. {{ number_format($item->cogs, 2, ',', '.') }}</td>
							<td>Rp. {{ $item->keterangan == 'Pembelian' ? number_format($item->total_cogs, 2, ',', '.') : 0 }}</td>
							<td>Rp. {{ $item->keterangan != 'Pembelian' ? number_format($item->total_cogs, 2, ',', '.') : 0 }}</td>
							<td>Rp. {{ number_format($BalanceQTYCOGS, 2, ',', '.') }}</td>
						</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
	<section style="margin-top: 200px;position:relative">
		<table style="width: 300px;position:absolute;top:-50;right:230px">
			<tr>
				<td align="right">Semarang ,
					{{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }} </td>
			</tr>
		</table>
		<div>
			<table style="width: 200px;position: absolute;top:0;left:150;">
				<tr>
					<td align="center">Diketahui Oleh</td>
				</tr>
				<tr>
					<td style="height: 80px;"></td>
				</tr>
				<tr>
					<td align="center" style="text-decoration:underline"></td>
				</tr>
			</table>
		</div>
		<div style="position: absolute;top:0;right:320px;">
			<table style="width: 250px">
				<tr>
					<td align="center">Penyimpan Barang</td>
				</tr>
				<tr>
					<td style="height: 80px;"></td>
				</tr>
				<tr>
					<td align="center" style="text-decoration:underline"></td>
				</tr>
			</table>
		</div>
	</section>
</body>

</html>
