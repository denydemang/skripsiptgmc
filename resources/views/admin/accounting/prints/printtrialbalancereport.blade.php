<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trial Balance Report</title>
    <style>
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin: 0;
            padding: 0;
            position: relative;
            font-size: 11px;
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
            margin: 2px 0;
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
            width: 250px
        }
    </style>
</head>

<body>
    <div class="logo" style="margin-left:-60px">
        @include('layout.logoimage')
    </div>

    <header style="margin-bottom: 20px;border-top: 2px solid #333">
        <h3>PT GENTA MULTI JAYYA</h3>
        <h3 style="margin-top:-10px ">TRIAL BALANCE</h3>
        <h3 style="margin-top:-10px ">Periode s.d {{ $lastDate }}</h3>
    </header>

    <section class="transaction">
        <p style="text-align:right">Dalam Satuan Rupiah</p>
        <table id="detail">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th style="text-align: right">Saldo Awal</th>
                    <th style="text-align: right">Debit</th>
                    <th style="text-align: right">Kredit</th>
                    <th style="text-align: right">Total</th>
                </tr>
            </thead>
            <tbody>
                @if (count($coaData) > 0)
                    @php
                        $totalDebit = 0;
                        $totalKredit = 0;
                    @endphp
                    @foreach ($coaData as $item)
                        @php
                            $totalDebit += floatval($item->debit);
                            $totalKredit += floatval($item->kredit);
                        @endphp
                        <tr>
                            <td class="no-wrap">{{ $item->code }}</td>
                            <td class="no-wrap">{{ $item->name }}</td>
                            <td class="no-wrap" style="text-align: right">
                                {{ number_format($item->beginning_balance, 2, ',', '.') }}</td>
                            <td class="no-wrap" style="text-align: right">{{ number_format($item->debit, 2, ',', '.') }}
                            </td>
                            <td class="no-wrap" style="text-align: right">
                                {{ number_format($item->kredit, 2, ',', '.') }}</td>
                            <td class="no-wrap" style="text-align: right">{{ number_format($item->total, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>TOTAL</b></td>
                        <td><b> {{ number_format($totalDebit, 2, ',', '.') }}</b></td>
                        <td><b>{{ number_format($totalKredit, 2, ',', '.') }}</b></td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </section>
</body>

</html>
