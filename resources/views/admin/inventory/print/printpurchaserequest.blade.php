<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Purchase Request</title>
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
			/* background-color: #f2f2f2; */
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
		#detail tbody tr:nth-child(even) {
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

		.imglogo {
			width: 280px
		}
	</style>
</head>

<body>
	@php
		Illuminate\Support\Facades\App::setLocale('id');
	@endphp
	<div class="logo" style="margin-left:-60px">
		@include('layout.logoimage')
	</div>
	<header style="margin-bottom: 50px">
		<h3>PT GENTA MULTI JAYYA</h3>
		<h3 style="margin-top:-10px ">Purchase Request</h3>
	</header>
	<section class="transaction">
		<div class="transaction-info" style="margin-bottom:50px">
			<table border="0" cellpadding="2" style="padding: 5px">
				<tr>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder">No PR</p>
					</td>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder">:</p>
					</td>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder; padding-left:12px">{{ $dataPR[0]['pr_no'] }}</p>
					</td>
				</tr>
				<tr>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder">Division</p>
					</td>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder">:</p>
					</td>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder;padding-left:12px">{{ $dataPR[0]['division'] }}</p>
					</td>
				</tr>
				<tr>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder">Request Person </p>
					</td>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder">:</p>
					</td>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder;padding-left:12px">{{ $dataPR[0]['pic_name'] }}</p>
					</td>
				</tr>
			</table>
			<table border="0" cellpadding="2" style="padding: 5px" style="position: absolute;top:0;right:0"">
				<tr>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder">Date Required </p>
					</td>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder">:</p>
					</td>
					<td style="padding-left:30px">
						<p style="text-align: right; font-size:16px;font-weight:bolder">
							{{ \Carbon\Carbon::parse($dataPR[0]['date_need'])->format('d/m/Y') }}</p>
					</td>
				</tr>
				<tr>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder">Status </p>
					</td>
					<td>
						<p style="text-align: left; font-size:16px;font-weight:bolder">:</p>
					</td>
					<td>
						<p style="text-align: right; font-size:16px;font-weight:bolder">
							{{ intval($dataPR[0]['is_approve']) == 0 ? 'Not Approved' : 'Approved' }}</p>
					</td>
				</tr>
			</table>
		</div>
	</section>
	<section class="transaction">
		<h4 style="text-decoration: underline">Detail Item To Purchase
			<table id="detail">
				<thead>
					<tr>
						<th>No</th>
						<th>Item Code</th>
						<th>Item Name</th>
						<th>Unit</th>
						<th>Qty</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($dataPR as $x)
						<tr>
							<td class="no-wrap">{{ $loop->iteration }}</td>
							<td class="no-wrap">{{ $x->item_code }}</td>
							<td>{{ $x->name }}</td>
							<td>{{ $x->unit_code }}</td>
							<td class="no-wrap">{{ $x->qty }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
	</section>
	<section>
		<h4 style="display: inline-block">Description : </h4>
		<span style="display:block;margin-top:-15px">{{ $dataPR[0]['description'] }}</span>
	</section>

	<section style="margin-top: 200px;position:relative">
		<table style="width: 300px;position:absolute;top:-50;right:10">
			<tr>
				<td align="right">Semarang ,
					{{ \Carbon\Carbon::parse($dataPR[0]['transaction_date'])->isoFormat('DD MMMM YYYY') }} </td>
			</tr>
		</table>
		<div>
			<table style="width: 200px;position: absolute;top:0;left:0;">
				<tr>
					<td align="center">Disetujui Oleh</td>
				</tr>
				<tr>
					<td style="height: 80px;"></td>
				</tr>
				<tr>
					<td align="center" style="text-decoration:underline">{{ $dataPR[0]['approved_by'] }}</td>
				</tr>
			</table>
		</div>
		<div style="position: absolute;top:0;right:350px;">
			<table style="width: 220px">
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
		<div style="position: absolute;top:0;right:100px;">
			<table style="width: 220px">
				<tr>
					<td align="center">Dibuat Oleh</td>
				</tr>
				<tr>
					<td style="height: 80px;"></td>
				</tr>
				<tr>
					<td align="center" style="text-decoration:underline">{{ $dataPR[0]['created_by'] }}</td>
				</tr>
			</table>
		</div>
	</section>
</body>

</html>
