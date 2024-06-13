<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement of Change in Capital Report</title>
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
            font-size: 14px;
        }

        table td {
            padding: 5px;
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
                <h2>LAPORAN PERUBAHAN MODAL</h2>
                <h3 style="margin-top:-1px">Periode {{ $firstDate }} - {{ $lastDate }}</h3>
                <h3 style="margin-top:-10px">(Dalam Rupiah)</h3>
            </div>
        </div>
        <div class="section">

            <table>
                @php
                    $totalEkuitas = 0;
                @endphp
                @foreach ($coaData as $key => $item)
                    @php
                        $totalSub = 0;
                    @endphp
                    @foreach ($item as $k => $item)
                        <tr>
                            <td style="white-space: nowrap;text-align:left">
                                {{ $item->description }}
                            </td>
                            <td class="nominal">
                                {{ floatval($item->balance) < 0 ? '(' . number_format($item->balance * -1, 2, ',', '.') . ')' : number_format($item->balance, 2, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                        @php
                            $totalSub += floatval($item->balance);
                        @endphp
                    @endforeach
                    @php
                        $totalEkuitas += $totalSub;
                    @endphp
                    <tr class="total">
                        <td colspan="2" style="text-align: center">
                            {{ $key == 'LABA RUGI' ? 'Saldo Laba Ditahan Akhir Bulan' : $key }}</td>
                        <td class="nominal"
                            style="white-space: nowrap;border-top:1px solid black;border-top:1px solid black;border-bottom:1px solid black;text-align:right">
                            {{ floatval($totalSub) < 0 ? '(' . number_format($totalSub * -1, 2, ',', '.') . ')' : number_format($totalSub, 2, ',', '.') }}

                        </td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td colspan="2" style="text-align: center">
                        SALDO EKUITAS AKHIR</td>
                    <td class="nominal"
                        style="white-space: nowrap;border-top:1px solid black;border-top:1px solid black;border-bottom:1px solid black;text-align:right">
                        {{ floatval($totalEkuitas) < 0 ? '(' . number_format($totalEkuitas * -1, 2, ',', '.') . ')' : number_format($totalEkuitas, 2, ',', '.') }}

                    </td>
                </tr>
            </table>


        </div>

    </div>
</body>

</html>
