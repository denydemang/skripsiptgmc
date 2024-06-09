    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Laba Rugi</title>
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
                    <h2>LAPORAN LABA RUGI</h2>
                    <h3 style="margin-top:-1px">Periode {{ $firstDate }} - {{ $lastDate }}</h3>
                    <h3 style="margin-top:-10px">(Dalam Rupiah)</h3>
                </div>
            </div>
            @php
                $totalLR = 0;
            @endphp
            @if (count($coaData) > 0)
                @foreach ($coaData as $key => $item)
                    <div class="section">
                        <h3>{{ $key }}</h3>
                        <table>
                            @php
                                $totalSub = 0;
                            @endphp
                            @foreach ($item as $i)
                                @php
                                    $totalSub += floatval($i->Total);
                                @endphp
                                <tr>
                                    <td class="kode">{{ $i->code }}</td>
                                    <td>{{ $i->name }}</td>
                                    <td class="nominal">
                                        {{ number_format(floatval($i->Total) < 0 ? floatval($i->Total) * -1 : floatval($i->Total), 2, ',', '.') }}
                                    </td>
                                    <td class="nominal"></td>
                                </tr>
                            @endforeach
                            <tr class="total">
                                <td colspan="3" style="text-align: center">Total {{ $i->Tipe }}</td>
                                <td class="nominal"
                                    style="border-top:1px solid black;border-top:1px solid black;border-bottom:1px solid black">
                                    @if (strpos(strtolower($i->Tipe), 'beban') !== false || strpos(strtolower($i->Tipe), 'hpp') !== false)
                                        ({{ number_format($totalSub < 0 ? $totalSub * -1 : $totalSub, 2, ',', '.') }})
                                    @else
                                        {{ number_format($totalSub < 0 ? $totalSub * -1 : $totalSub, 2, ',', '.') }}
                                    @endif

                                    @php
                                        $totalLR += $totalSub;
                                    @endphp

                                </td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            @endif
            <div class="section">
                <table>
                    <tr class="total">
                        <td colspan="3" style="text-align: center">TOTAL LABA/RUGI BERSIH <span>
                                {{ $totalLR < 0 ? '(RUGI)' : '(LABA)' }}</span></td>
                        <td class="nominal" style="border-top:1px solid black">
                            <h3>{{ $totalLR < 0 ? '(' . number_format($totalLR * -1, 2, ',', '.') . ')' : number_format($totalLR, 2, ',', '.') }}
                            </h3>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>

    </html>
