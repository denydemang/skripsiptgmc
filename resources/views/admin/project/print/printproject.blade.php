<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Project Report</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
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
			margin: 10px;
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
			font-family: Arial, sans-serif;
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
	</style>
</head>

<body>

	<header>
		<h2>PROJECT REPORT</h2>
	</header>
	<section class="transaction">
		<div class="transaction-info" style="margin-bottom:50px">
			<table border="0" cellpadding="0">
				<tr>
					<td style="padding-bottom: 10px">
						<span style="text-align: left; font-size:18px;font-weight:bold">PT GENTA MULTI JAYYA</span>
					</td>
				</tr>
				<tr>
					<td>
						<span style="text-align: left; font-size:16px">Jl. MH. Thamrin No. 5, Kel. Sekayu</span>
					</td>
				</tr>
				<tr>
					<td>
						<span style="text-align: left; font-size:16px">Kec. Semarang Tengah, Kota
							Semarang</span>
					</td>
				</tr>
				<tr>
					<td>Telepon (024) 86404871</td>
				</tr>
			</table>
			<table border="0" cellpadding="4" style="padding: 10px; position: absolute;top:0;right:0;">
				<tr>
					<td>
						<p style="text-align: right; font-size:18px;font-weight:bolder">Transaction Date</p>
					</td>
					<td>
						<p style="text-align: right; font-size:18px;font-weight:bolder">:</p>
					</td>
					<td>
						<p style="text-align: right; font-size:18px">
							{{ \Carbon\Carbon::parse($project['transaction_date'])->format('d/m/Y') }}</p>
					</td>
				</tr>
			</table>
		</div>

		<h3 style="text-decoration: underline">Detail Project</h3>
		<div class="detail-info" style="margin-bottom: 60px;position:relative">
			<div class="detail-info-1" style="max-width:72%">
				<table cellpadding="3" style="width: 100%">
					<tr>
						<td><strong>Project Name</strong></td>
						<td>:</td>
						<td>{{ $project['name'] }}</td>
					</tr>
					<tr>
						<td><strong>Project Code</strong></td>
						<td>:</td>
						<td>{{ $project['code'] }}</td>
					</tr>
					<tr>
						<td><strong>Project Type</strong></td>
						<td>:</td>
						<td>{{ $project['type_project_name'] }}</td>
					</tr>
					<tr>
						<td><strong>Location</strong></td>
						<td>:</td>
						<td>{{ $project['location'] }}</td>
					</tr>
					<tr>
						<td><strong>Estimation</strong></td>
						<td>:</td>
						<td>{{ number_format($project['duration_days'] / 30.44, 2) }} Months</td>
					</tr>
					<tr>
						<td><strong>Customer Code</strong></td>
						<td>:</td>
						<td>{{ $project['customer_code'] }}</td>
					</tr>
					<tr>
						<td><strong>Customer Name</strong></td>
						<td>:</td>
						<td>{{ $project['customer_name'] }}</td>
					</tr>
					<tr>
						<td><strong>Description</strong></td>
						<td>:</td>
						<td>{{ $project['description'] }}</td>
					</tr>
					<tr>
						<td>
							<h3 style="text-align: left">Budget
							</h3>
						</td>
						<td>:</td>
						<td>
							<h3 style="text-align: left">Rp. {{ number_format($project['budget'], 2, ',', '.') }}</h3>
						</td>
					</tr>
				</table>
			</div>
			<div class="detail-info-2" style="position: absolute;top:0;right:0">
				<table cellpadding="3">
					<tr>
						<td><strong>Status Project</strong></td>
						<td>:</td>
						@switch($project['project_status'])
							@case(0)
								<td>Not Started</td>
							@break

							@case(1)
								<td>On Progress</td>
							@break

							@default
								<td>Finished</td>
						@endswitch
					</tr>
					<tr>
						<td><strong>Started Date</strong></td>
						<td>:</td>
						@if ($project['start_date'] == null)
							<td> - </td>
						@else
							<td>{{ \Carbon\Carbon::parse($project['start_date'])->format('d/m/Y') }}</td>
						@endif
					</tr>
					<tr>
						<td><strong>Finish Date</strong></td>
						<td>:</td>
						@if ($project['end_date'] == null)
							<td> - </td>
						@else
							<td>{{ \Carbon\Carbon::parse($project['end_date'])->format('d/m/Y') }}</td>
						@endif
					</tr>
					<tr>
						<td></td>
					</tr>
				</table>
			</div>

		</div>
	</section>
	<section>
		<h3 style="text-decoration: underline">Detail Material</h3>
		<table border="1" cellpadding="10" style="border-collapse:collapse;max-width:100%">
			@foreach ($dataBahanBaku as $item)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $item->item_name }} ({{ $item->item_code }})</td>
					<td>{{ floatval($item->qty) }} {{ $item->unit_code }}</td>
				</tr>
			@endforeach
		</table>

		<h3 style="text-decoration: underline">Detail Upah</h3>
		<table border="1" cellpadding="10" style="width:100%;border-collapse:collapse">
			@foreach ($dataUpah as $upah)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $upah->job }} </td>
					<td style="white-space:nowrap">{{ floatval($upah->qty) }}-{{ $upah->unit . 's' }}
						{{ '@' . number_format($upah->price, 2, ',', '.') }}/{{ $upah->unit }}</td>
					<td style="white-space:nowrap">Rp. {{ number_format($upah->total, 2, ',', '.') }}</td>
				</tr>
			@endforeach
			<tr>
				<td colspan="3" align="right"><strong>TOTAL</strong></td>
				<td><strong>Rp. {{ number_format($totalUpah, 2, ',', '.') }}</strong></td>
			</tr>
		</table>
	</section>
	<section style="margin-top: 150px;position:relative">
		<div>
			<table style="width: 200px">
				<tr>
					<td align="center">Mengetahui</td>
				</tr>
				<tr>
					<td style="height: 80px;"></td>
				</tr>
				<tr>
					<td align="center"></td>
				</tr>
			</table>
		</div>
		<div style="position: absolute;top:0;right:50;">
			<table style="width: 200px">
				<tr>
					<td align="center">PIC (Person In Charge)</td>
				</tr>
				<tr>
					<td style="height: 80px;"></td>
				</tr>
				<tr>
					<td align="center" style="text-decoration:underline">{{ $project['pic'] }}</td>
				</tr>
			</table>
		</div>
	</section>
</body>

</html>
