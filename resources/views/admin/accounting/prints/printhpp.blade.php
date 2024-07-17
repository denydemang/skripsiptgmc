<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan HPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-right: 20px;
            color: #333;
            font-size: 12px;
        }

        .report-container {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 10px;
        }

        .logo {
            width: 100px;
            height: auto;
            margin-left: -30px
        }

        .title {
            text-align: right;
        }

        .title h2 {
            margin: 0;
            font-size: 24px;
        }

        .title p {
            margin: 5px 0 0;
            font-size: 1px;
        }

        .section {
            margin-top: 20px;
        }

        .section h3 {
            margin: 0 0 10px;
            font-size: 18px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }

        table {
            width: 100%;
            margin-top: 10px;
        }

        table td {
            padding: 5px;
            font-size: 13px;
        }

        table .kode {
            width: 20%;
        }

        table .nominal {
            text-align: right;
            width: 30%;
        }

        table .total td {
            font-weight: bold;
        }

        .imglogo {
            width: 250px
        }
    </style>
</head>

<body>
    <div class="logo">
        @include('layout.logoimage')
    </div>
    <div class="report-container">
        <div class="header">
            <div class="title" style="text-align: center">
                <h2>PT GENTA MULTI JAYYA</h2>
                <h2 style="font-size:18px">LAPORAN HPP</h2>
                <h3 style="margin-top:5px">Periode {{ $firstDate }} - {{ $lastDate }}</h3>
                <h3 style="margin-top:-10px">(Dalam Rupiah)</h3>
            </div>
        </div>
        @if (count($coaData) > 0)
            @php
                $selisih = 0;
            @endphp
            @foreach ($coaData as $key => $item)
                <div class="section">
                    @php
                        $header = $key == 'standar' ? 'STANDAR' : 'SESUNGGUHNYA';
                        $total = 0;
                    @endphp
                    <h3>{{ $header }}</h3>

                    <table style="width:100%">
                        @foreach ($item as $i)
                            @php
                                $total += floatval($i->nominal);
                            @endphp
                            <tr>
                                <td style="width:60%;white-space:normal;word-wrap: break-word;">{{ $i->keterangan }}
                                </td>
                                <td class="nominal" style="width:20%;white-space:nowrap">
                                    {{ number_format($i->nominal, 2, ',', '.') }}</td>
                                <td class="nominal" style="width:20%;white-space:nowrap"></td>
                            </tr>
                        @endforeach
                        <tr class="total">
                            <td colspan="2" style="text-align: center">Total HPP {{ $header }} </td>
                            <td class="nominal"
                                style="border-top:1px solid black;border-top:1px solid black;border-bottom:1px solid black">
                                {{ number_format($total, 2, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
                @php
                    $tambahkurang = $key == 'standar' ? $total * 1 : $total * -1;
                    $selisih += $tambahkurang;
                @endphp
            @endforeach
            <div class="section">
                <table>
                    <tr class="total">
                        <td colspan="3" style="text-align: center;font-size:19px">Selisih </td>
                        <td class="nominal" style="border-top:1px solid black">
                            <h3>
                                {{ $selisih < 0 ? '(' . number_format($selisih * -1, 2, ',', '.') . ')' : number_format($selisih, 2, ',', '.') }}
                            </h3>
                        </td>
                    </tr>
                </table>
            </div>
        @endif
    </div>
</body>

</html>
