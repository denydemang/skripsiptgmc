<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledger Report</title>
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

        .imglogo {
            width: 350px
        }
    </style>
</head>

<body>
    <div class="logo" style="margin-left:-60px">
        @include('layout.logoimage')
    </div>

    <header style="margin-bottom: 50px;border-top: 2px solid #333">
        <h3>PT GENTA MULTI JAYYA</h3>
        <h3 style="margin-top:-10px ">BUKU BESAR</h3>
        <h3 style="margin-top:-10px ">PERIODE {{ $firstDate }} - {{ $lastDate }}</h3>
    </header>

    <section class="transaction">
        @foreach ($coaData as $coacode => $items)
            <div style="margin-bottom:40px">
                <table id="detail">
                    <thead>
                        <tr>
                            <th colspan="6" style="padding: 0">
                                <p style="font-weight:bold ;margin-left:8px"> {{ $coacode }} -
                                    {{ $items[0]->coa_name }}
                                </p>
                            </th>
                        </tr>
                        <tr>
                            <th>Tgl Transaksi</th>
                            <th>Voucher No</th>
                            <th>Description</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Balance ({{ $items[0]->default_dk }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$items[0]->voucher_no)
                            <tr>
                                <td colspan="5"><b>Beginning Balance</b></td>
                                <td class="no-wrap" style="text-align:right">Rp
                                    {{ number_format($items[0]->BeginBalance, 2, ',', '.') }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="5"><b>Beginning Balance</b></td>
                                <td class="no-wrap" style="text-align:right">Rp
                                    {{ number_format($items[0]->BeginBalance, 2, ',', '.') }}</td>
                            </tr>
                            @php
                                $balance = floatval($items[0]->BeginBalance);
                            @endphp
                            @foreach ($items as $i)
                                @php
                                    if ($i->default_dk == 'K') {
                                        $balance = $balance - floatval($i->debit) + floatval($i->kredit);
                                    } else {
                                        $balance = $balance + floatval($i->debit) - floatval($i->kredit);
                                    }

                                @endphp
                                <tr>
                                    <td class="no-wrap">
                                        {{ \Carbon\Carbon::parse($i->transaction_date)->format('d/m/Y') }}</td>
                                    <td class="no-wrap">{{ $i->voucher_no }}</td>
                                    <td>{{ $i->description }}</td>
                                    <td class="no-wrap" style="text-align:right">
                                        Rp {{ number_format($i->debit, 2, ',', '.') }}</td>
                                    <td class="no-wrap" style="text-align:right">Rp
                                        {{ number_format($i->kredit, 2, ',', '.') }}</td>
                                    <td class="no-wrap" style="text-align:right">Rp
                                        {{ number_format($balance, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endforeach
    </section>
</body>

</html>
