<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Journal Project </title>
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
		<h3 style="margin-bottom: -15px">PT GENTA MULTI JAYYA</h3>
		<h3 style="margin-bottom: -15px">PROJECT RECAPITULATION</h3>
		<h3>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
			{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h3>

	</header>
	<section class="transaction">
		<div class="transaction-info">
			<table border="0" cellpadding="4" style="padding: 10px;margin-left:-10px">
				@if ($customer_code !== null && count($projects) > 0)
					<tr>
						<td><strong>Customer Name</strong></td>
						<td>:</td>
						<td>{{ $projects[0]['customer_name'] }}</td>
					</tr>
					<tr>
						<td><strong>Customer Code</strong></td>
						<td>:</td>
						<td>{{ $projects[0]['customer_code'] }}</td>
					</tr>
				@endif
				@if ($statuscode !== null && count($projects) > 0)
					<tr>
						<td><strong>Project Status</strong></td>
						<td>:</td>
						@switch($statuscode)
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
				@endif
			</table>
		</div>
		<table id="detail" style="font-size:10px;width:100%">
			<thead>
				<tr>
					<th>No</th>
					<th>Code</th>
					<th>Name</th>
					<th>Type Name</th>
					@if ($customer_code === null)
						<th>Customer Code</th>
						<th>Customer Name</th>
					@endif
					<th>Location</th>
					<th>PIC</th>
					@if ($statuscode === null)
						<th>Status</th>
					@endif
					<th>Budget</th>
				</tr>
			</thead>
			<tbody>
				@if (count($projects) == 0)
					<tr></tr>
				@else
					@php
						$totalBudget = 0;
					@endphp
					@foreach ($projects as $p)
						@php
							$totalBudget += floatval($p->budget);
						@endphp
						<tr>
							<td class="no-wrap">{{ $loop->iteration }}</td>
							<td class="no-wrap">{{ $p->code }}</td>
							<td>{{ $p->name }}</td>
							<td>{{ $p->type_project_name }}</td>
							@if ($customer_code === null)
								<td>{{ $p->customer_code }}</td>
								<td>{{ $p->customer_name }}</td>
							@endif
							<td>{{ $p->location }}</td>
							<td>{{ $p->pic }}</td>
							@if ($statuscode === null)
								@switch($p['project_status'])
									@case(0)
										<td>Not Started</td>
									@break

									@case(1)
										<td>On Progress</td>
									@break

									@default
										<td>Finished</td>
								@endswitch
							@endif
							<td class="no-wrap">Rp. {{ number_format($p->budget, 2, ',', '.') }}</td>
						</tr>
					@endforeach
					<tr>
						<td colspan="9" align="right"> <strong style="display: block;text-align:right">Total</strong></td>
						<td class="no-wrap"><strong>Rp. {{ number_format($totalBudget, 2, ',', '.') }}</strong></td>
					</tr>

				@endif
			</tbody>
		</table>
	</section>
</body>

</html>
