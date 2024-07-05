<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realisasi Report</title>
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
            /* background-color: #f2f2f2; */
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

        .transaction-info table td {
            vertical-align: top;
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
        <h3 style="margin-top:-10px ">Realisation Report</h3>
    </header>
    <section class="transaction" style="font-size: 11px">
        <div class="transaction-info" style="margin-bottom:50px">
            <table border="0" cellpadding="2" style="padding: 5px;width:60%">
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Realisation No</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px; padding-left:12px">
                            {{ $projectRealisation['project_realisation_code'] }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Project Code</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px; padding-left:12px">
                            {{ $projectRealisation['project_code'] }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Project Name</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;padding-left:12px">
                            {{ $projectRealisation['project_name'] }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Cust Code</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;padding-left:12px">
                            {{ $projectRealisation['customer_code'] }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Cust Name </p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;padding-left:12px">
                            {{ $projectRealisation['customer_name'] }}
                        </p>
                    </td>
                </tr>
            </table>
            <table border="0" cellpadding="2" style="padding: 5px" style="position: absolute;top:0;right:0"">
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Realisation Date </p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;">
                            {{ \Carbon\Carbon::parse($projectRealisation['realisation_date'])->format('d/m/Y') }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Termin</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;">
                            {{ $projectRealisation['termin'] }} of {{ $projectRealisation['total_termin'] }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Project Amount</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">
                            Rp. {{ number_format($projectRealisation['project_amount'], 2, ',', '.') }}
                            {{-- {{ \Carbon\Carbon::parse($dataPurchase[0]['transaction_date'])->format('d/m/Y') }} --}}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Termin
                            {{ $projectRealisation['termin'] }} Realisation</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">
                            Rp. {{ number_format($projectRealisation['realisation_amount'], 2, ',', '.') }}
                            {{-- {{ \Carbon\Carbon::parse($dataPurchase[0]['transaction_date'])->format('d/m/Y') }} --}}
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </section>
    <section class="transaction">
        <h5 style="text-decoration: underline">Detail Item Realisation</h5>
        <table id="detail" style="font-size: 12px;width: 100%;table-layout:auto">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataBahanBaku as $item)
                    <tr>
                        <td class="no-wrap">{{ $loop->iteration }}</td>
                        <td style="word-wrap:break-word;width:20%">{{ $item->item_code }}</td>
                        <td class="no-wrap">{{ $item->name }}</td>
                        <td class="no-wrap">{{ floatval($item->qty_used) }}</td>
                        <td class="no-wrap" style="text-align:left">{{ $item->unit_code }}</td>
                    </tr>
                @endforeach
                {{-- @foreach ($dataPurchase as $x) --}}

            </tbody>
        </table>
    </section>

    <section class="transaction">
        <h5 style="text-decoration: underline">Detail Upah BTKL Realisation</h5>
        <table id="detail" style="font-size: 12px;width: 100%;table-layout:auto">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width:10%">Code</th>
                    <th style="word-wrap:break-word;width:30%">Job</th>
                    <th style="5%">Unit</th>
                    <th style="10%">Qty</th>
                    <th style="text-align: right;width:20%">Price</th>
                    <th style="text-align: right;width:20%">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($dataUpah as $item)
                    <tr>
                        <td class="no-wrap" style="width: 5%">{{ $loop->iteration }}</td>
                        <td class="no-wrap" style="width:10%">{{ $item->upah_code }}</td>
                        <td style="word-wrap:break-word;width:30%">{{ $item->upah_name }}</td>
                        <td class="no-wrap" style="5%">{{ $item->unit }}</td>
                        <td class="no-wrap" style="10%">{{ floatval($item->qty_used) }}</td>
                        <td class="no-wrap" style="text-align:right;width:20%"> Rp.
                            {{ number_format($item->price, 2, ',', '.') }}</td>
                        <td class="no-wrap" style="text-align:right;width:20%"> Rp.
                            {{ number_format($item->total, 2, ',', '.') }}</td>
                    </tr>
                    @php
                        $total += floatval($item->total);
                    @endphp
                @endforeach
                <tr>
                    <td colspan="6" style="text-align: center"><b>Total</b></td>
                    <td style="text-align: right"><b>Rp. {{ number_format($total, 2, ',', '.') }}</b></td>
                </tr>
            </tbody>
        </table>
    </section>
</body>

</html>
