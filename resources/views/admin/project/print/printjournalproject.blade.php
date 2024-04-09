<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Journal Project {{ $dataprojectdanjurnal[0]['ref_no'] }}</title>
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
		}

		.transaction-info-2 {
			position: absolute;
			top: 0;
			right: 0;
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
		<h2>Journal Project</h2>
	</header>
	<section class="transaction">
		<div class="transaction-info">
			<table border="0" cellpadding="4" style="padding: 10px">
				<tr>
					<td><strong>Transaction Date</strong></td>
					<td>:</td>
					<td>{{ \Carbon\Carbon::parse($dataprojectdanjurnal[0]['transaction_date'])->format('d/m/Y') }}</td>
				</tr>
				<tr>
					<td><strong>Project Code</strong></td>
					<td>:</td>
					<td>{{ $dataprojectdanjurnal[0]['ref_no'] }}</td>
				</tr>
				<tr>
					<td><strong>Project Name</strong></td>
					<td>:</td>
					<td>{{ $dataprojectdanjurnal[0]['project_name'] }}</td>
					</td>
				</tr>
			</table>
		</div>
		<div class="transaction-info-2">
			<table id="x" border="0" cellpadding="4" style="padding: 10px">
				<tr>
					<td><strong>Voucher No</strong></td>
					<td>:</td>
					<td>{{ $dataprojectdanjurnal[0]['voucher_no'] }}</td>
				</tr>
				<tr>
					<td><strong>Voucher Type</strong></td>
					<td>:</td>
					<td>{{ $dataprojectdanjurnal[0]['journal_type_code'] }}</td>
				</tr>

			</table>
		</div>
		<table id="detail">
			<thead>
				<tr>
					<th>No</th>
					<th>COA Code</th>
					<th>Name</th>
					<th>Description</th>
					<th>Debit</th>
					<th>Credit</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($dataprojectdanjurnal as $coa)
					<tr>
						<td class="no-wrap">{{ $loop->iteration }}</td>
						<td class="no-wrap">{{ $coa->coa_code }}</td>
						<td>{{ $coa->coa_name }}</td>
						<td>{{ $coa->description }}</td>
						<td class="no-wrap">Rp. {{ number_format($coa->debit, 2, ',', '.') }}</td>
						<td class="no-wrap">Rp. {{ number_format($coa->kredit, 2, ',', '.') }}</td>
					</tr>
				@endforeach
				<tr>
					<td colspan="4" style="text-align: right"></td>
					<td class="no-wrap" style="text-align: right;"><strong>Rp. {{ number_format($totalDebit, 2, ',', '.') }}</strong>
					</td>
					<td class="no-wrap" style="text-align: right"><strong>Rp. {{ number_format($totalKredit, 2, ',', '.') }}</strong>
					</td>
				</tr>
				<!-- More rows can be added here -->
			</tbody>
		</table>
	</section>
</body>

</html>
