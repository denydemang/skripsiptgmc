<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet Report</title>
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

        /* .transaction {
            margin: 2px;
            position: relative;
            height: 100%;
        } */

        /* .transaction-info {
            margin-bottom: 20px;
            position: relative;
        }


        .transaction-info p {
            text-align: right;
            margin: 2px 0;
        } */

        #detail {
            width: 100%;
            border-collapse: collapse;
            height: 100%;
        }

        .page-break {
            page-break-before: auto;
        }

        .no-page-break {
            page-break-inside: inherit;
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
        <h3 style="margin-top:-10px ">BALANCE SHEET</h3>
        <h3 style="margin-top:-10px ">Periode s.d {{ $lastDate }}</h3>
    </header>

    <section class="transaction">

        <p style="text-align:right">Dalam Satuan Rupiah</p>
        <div class="no-page-break">
            <table id="detail" style="position: fixed;top:250px">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align: left;width:50%;border:1px solid black;padding-left:5px">
                            Aktiva</th>
                        <th colspan="3" style="text-align: left; width:50%;padding-left:10px;border:1px solid black">
                            Passiva</th>
                    </tr>

                </thead>
                <tbody>
                    <tr>

                        @foreach ($dataCoa as $aktivapassiva => $ap)
                            @php
                                $totalaktivapasiva = 0;
                            @endphp
                            <td colspan="3"
                                style="width:50%;vertical-align:top;border:1px solid black;padding-right:5px">
                                <table style="width:100%">

                                    @foreach ($ap as $jenisakun => $ja)
                                        @php
                                            $totaljenisakun = 0;
                                        @endphp
                                        <tr>
                                            <td colspan="4"><b><span
                                                        style="text-decoration:underline">{{ $jenisakun }}</span></b>
                                            </td>
                                        </tr>
                                        @foreach ($ja as $i)
                                            @php
                                                $totaljenisakun += floatval($i->balance);
                                            @endphp
                                            <tr>
                                                <td>{{ $i->code }}</td>
                                                <td>{{ $i->name }}</td>
                                                <td style="text-align: right">
                                                    {{ $i->balance < 0 ? '(' . number_format($i->balance < 0 ? $i->balance * -1 : $i->balance, 2, ',', '.') . ')' : number_format($i->balance < 0 ? $i->balance * 1 : $i->balance, 2, ',', '.') }}
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" style="text-align: center"><b>TOTAL
                                                    {{ $jenisakun }}</b>
                                            </td>
                                            <td
                                                style="border-top: 1px solid black;border-bottom: 1px solid black;text-align:right">
                                                <b>
                                                    {{ $totaljenisakun < 0 ? '(' . number_format($totaljenisakun < 0 ? $totaljenisakun * -1 : $totaljenisakun, 2, ',', '.') . ')' : number_format($totaljenisakun < 0 ? $totaljenisakun * 1 : $totaljenisakun, 2, ',', '.') }}</b>
                                            </td>
                                        </tr>
                                        @php
                                            $totalaktivapasiva += floatval($totaljenisakun);
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="3" style="text-align: center">
                                            <h3 style="text-decoration: underline">TOTAL
                                                {{ strtoupper($aktivapassiva) }}</h3>
                                        </td>
                                        <td>
                                            <h3
                                                style="border-top: 1px solid black;border-bottom: 1px solid black; text-align:right">
                                                {{ $totalaktivapasiva < 0 ? '(' . number_format($totalaktivapasiva < 0 ? $totalaktivapasiva * -1 : $totalaktivapasiva, 2, ',', '.') . ')' : number_format($totalaktivapasiva < 0 ? $totalaktivapasiva * 1 : $totalaktivapasiva, 2, ',', '.') }}
                                            </h3>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="page-break"></div>
    </section>
</body>


</html>
