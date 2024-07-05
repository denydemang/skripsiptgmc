<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Receipt Recapitulation </title>
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
            /* background-color: #f2f2f2; */
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

        .imglogo {
            width: 350px
        }
    </style>
</head>

<body>

    <div class="header-container" style="position: relative;padding-top:100px;border-bottom:2px solid #333">
        <div class="logo" style="position: absolute;top:0;right:-60">
            @include('layout.logoimage')
        </div>
        <header style="position:absolute;top:0;left:0;padding-top:40px">
            <h4 style="margin-top:-10px;text-align:left">ADVANCED RECEIPT RECAPITULATION</h4>
        </header>
    </div>
    <section class="transaction">
        <div class="transaction-info">
            <h4 style="float: right;margin-bottom:60px">Periode :
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h4>
            <table border="0" cellpadding="4" style="margin-top:40px;padding: 10px;margin-left:-10px">
                @if ($customercode !== null && count($AR) > 0)
                    <tr>
                        <td><strong>Customer Name</strong></td>
                        <td>:</td>
                        <td>{{ $AR[0]['customer_name'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Customer Code</strong></td>
                        <td>:</td>
                        <td>{{ $AR[0]['customer_code'] }}</td>
                    </tr>
                @endif
                @if ($is_approve !== null && count($AR) > 0)
                    <tr>
                        <td><strong>Approve Status</strong></td>
                        <td>:</td>
                        @switch($is_approve)
                            @case(0)
                                <td>Not Approve</td>
                            @break

                            @case(1)
                                <td>Approved</td>
                            @break

                            @default
                                <td>All</td>
                        @endswitch
                    </tr>
                @endif
            </table>
        </div>
        <table id="detail" style="font-size:11px;width:100%">
            <thead>
                <tr>
                    <th style="width:2px">No</th>
                    <th>ADR no</th>
                    <th>Trans Date</th>
                    @if ($customercode === null)
                        <th>Customer Code</th>
                        <th>Customer Name</th>
                    @endif
                    <th>Description</th>
                    @if ($is_approve === null)
                        <th>Status Approve</th>
                    @endif
                    <th style="text-align: right">Desposit Amount</th>
                    <th style="text-align: right">Deposit Allocation</th>
                    <th style="text-align: right">Deposit Balance</th>
                </tr>
            </thead>
            <tbody>
                @if (count($AR) > 0)
                    @php
                        $totalDepositAmount = 0;
                        $totalDepositAllocation = 0;
                        $totalBalance = 0;
                    @endphp
                    @foreach ($AR as $p)
                        @php
                            $totalDepositAmount += floatval($p->deposit_amount);
                            $totalDepositAllocation += floatval($p->deposit_allocation);
                            $totalBalance += floatval($p->deposit_amount) - floatval($p->deposit_allocation);
                        @endphp
                        <tr>
                            <td class="no-wrap" style="width: 2px">{{ $loop->iteration }}</td>
                            <td>{{ $p->adr_no }}</td>
                            <td class="no-wrap">{{ \Carbon\Carbon::parse($p->transaction_date)->format('d/m/Y') }}</td>
                            @if ($customercode === null)
                                <td>{{ $p->customer_code }}</td>
                                <td style="text-align: left">{{ $p->customer_name }}</td>
                            @endif
                            <td style="text-align: left">{{ $p->description }}</td>
                            @if ($is_approve === null)
                                <td>{{ intval($p->is_approve) == 0 ? 'Not Approved' : 'Approved' }}</td>
                            @endif
                            <td class="no-wrap" style="text-align: right">Rp
                                {{ number_format($p->deposit_amount, 2, ',', '.') }}</td>
                            <td class="no-wrap" style="text-align: right">Rp
                                {{ number_format($p->deposit_allocation, 2, ',', '.') }}</td>
                            <td class="no-wrap" style="text-align: right">Rp
                                {{ number_format(floatval($p->deposit_amount) - floatval($p->deposit_allocation), 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    @if ($customercode === null && $is_approve === null)
                        <td class="no-wrap" style="text-align: center" colspan="7"> <b>TOTAL</b></td>
                    @elseif($customercode !== null && $is_approve === null)
                        <td class="no-wrap" style="text-align: center" colspan="5"> <b>TOTAL</b></td>
                    @elseif($customercode === null && $is_approve !== null)
                        <td class="no-wrap" style="text-align: center" colspan="6"> <b>TOTAL</b></td>
                    @else
                        <td class="no-wrap" style="text-align: center" colspan="4"> <b>TOTAL</b></td>
                    @endif
                    <td class="no-wrap" style="text-align: right"><b>Rp
                            {{ number_format($totalDepositAmount, 2, ',', '.') }}</b></td>
                    <td class="no-wrap" style="text-align: right"><b>Rp
                            {{ number_format($totalDepositAllocation, 2, ',', '.') }}</b></td>
                    <td class="no-wrap" style="text-align: right"><b>Rp
                            {{ number_format($totalBalance, 2, ',', '.') }}</b></td>
                @endif
            </tbody>
        </table>
    </section>
    {{-- <section class="transaction">
		<div class="transaction-info">
			<h4 style="float: right;margin-bottom:60px">Periode : {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
				{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h4>
			<table border="0" cellpadding="4" style="margin-top:40px;padding: 10px;margin-left:-10px">
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

					@if ($statuscode === null)
						@if ($customer_code === null)
							<tr>
								<td colspan="9" align="right"> <strong style="display: block;text-align:right">Total</strong></td>
								<td class="no-wrap"><strong>Rp. {{ number_format($totalBudget, 2, ',', '.') }}</strong></td>
							</tr>
						@else
							<tr>
								<td colspan="7" align="right"> <strong style="display: block;text-align:right">Total</strong></td>
								<td class="no-wrap"><strong>Rp. {{ number_format($totalBudget, 2, ',', '.') }}</strong></td>
							</tr>
						@endif
					@else
						@if ($customer_code === null)
							<tr>
								<td colspan="8" align="right"> <strong style="display: block;text-align:right">Total</strong></td>
								<td class="no-wrap"><strong>Rp. {{ number_format($totalBudget, 2, ',', '.') }}</strong></td>
							</tr>
						@else
							<tr>
								<td colspan="6" align="right"> <strong style="display: block;text-align:right">Total</strong></td>
								<td class="no-wrap"><strong>Rp. {{ number_format($totalBudget, 2, ',', '.') }}</strong></td>
							</tr>
						@endif
					@endif


				@endif
			</tbody>
		</table>
	</section> --}}
</body>

</html>
