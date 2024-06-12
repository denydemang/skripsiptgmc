<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Recap Report</title>
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

    <header style="border-top: 2px solid #333">
        <h3>PT GENTA MULTI JAYYA</h3>
        <h3 style="margin-top:-10px ">Journal Recap</h3>
        <h3 style="margin-top:-10px ">Periode {{ $firstDate }} - {{ $lastDate }}</h3>
    </header>

    <ul
        style="margin-left: -20px; margin-top: -40px; list-style-type: none; /* Menghilangkan bullet points */
            padding: 0; /* Menghilangkan padding bawaan */
            margin: 0; /* Menghilangkan margin bawaan */">
        <li>
            @if ($statusposting !== null)
                @if (intval($statusposting) > 0)
                    <h4>Status : Posted</h4>
                @else
                    <h4>Status : Unposted</h4>
                @endif
            @endif
            @if ($journaltype !== null)
                <h4>Type Journal : {{ $journaltype }}</h4>
            @endif
        </li>
    </ul>
    <section class="transaction" style="margin-top:-20px">
        <p style="text-align:right">Dalam Satuan Rupiah</p>
        <table id="detail">
            <thead>
                <tr>
                    <th>Transaction Date</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                </tr>
            </thead>
            <tbody>
                @if (count($coaData) > 0)
                    @php
                        $totalDebit = 0;
                        $totalKredit = 0;
                    @endphp

                    @foreach ($coaData as $key => $item)
                        <tr>
                            <td colspan="6"><b>Voucher No : {{ $key }}</b></td>
                        </tr>
                        @foreach ($item as $i)
                            @php
                                $totalDebit += floatval($i->debit);
                                $totalKredit += floatval($i->kredit);
                            @endphp
                            <tr>
                                <td class="no-wrap" style="text-align: left">
                                    {{ \Carbon\Carbon::parse($i->transaction_date)->format('d/m/Y') }}</td>
                                <td class="no-wrap" style="text-align: left">{{ $i->code }}</td>
                                @if (intval($i->debit) !== 0)
                                    <td class="no-wrap" style="text-align: left">{{ $i->name }}</td>
                                @else
                                    <td class="no-wrap" style="text-align: left;padding-left:20px">{{ $i->name }}
                                    </td>
                                @endif
                                <td style="text-align: left">{{ $i->description }}</td>
                                <td class="no-wrap" style="text-align: right">
                                    {{ number_format($i->debit, 2, ',', '.') }}
                                </td>
                                <td class="no-wrap" style="text-align: right">
                                    {{ number_format($i->kredit, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td colspan="4" style="text-align: center"><b>TOTAL</b></td>
                        <td><b> {{ number_format($totalDebit, 2, ',', '.') }}</b></td>
                        <td><b>{{ number_format($totalKredit, 2, ',', '.') }}</b></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </section>
</body>

</html>
