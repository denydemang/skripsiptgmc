<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Card</title>
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
            width: 400px
        }
    </style>
</head>

<body>
    {{-- @php
        Illuminate\Support\Facades\App::setLocale('id');
    @endphp --}}
    <div class="header-container" style="position: relative;padding-top:140px;border-bottom:2px solid #333">
        <div class="logo" style="position: absolute;top:0;right:-60">
            @include('layout.logoimage')
        </div>
        <header style="position:absolute;top:0;left:0;padding-top:40px">
            <h2 style="margin-top:-10px;text-align:left">STOCK CARD</h2>
        </header>
    </div>

    <div class="transaction">
        <div class="transaction-info">
            <h3 style="margin-top:10px; text-align:right;float:right">Periode :
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($lastDate)->format('d/m/Y') }}</h3>
            <table border="0" cellpadding="4" style="padding: 10px">
                <tr>
                    <td><strong>ITEM CODE</strong></td>
                    <td>:</td>
                    <td><strong>{{ $itemDesc->code }}</strong></td>
                </tr>
                <tr>
                    <td><strong>ITEM NAME</strong></td>
                    <td>:</td>
                    <td><strong>{{ $itemDesc->name }}</strong></td>
                </tr>
                <tr>
                    <td><strong>UNIT/SATUAN</strong></td>
                    <td>:</td>
                    <td><strong>{{ $itemDesc->unit_code }}</strong></td>
                </tr>
                <tr>
                    <td><strong>METHOD</strong></td>
                    <td>:</td>
                    <td><strong>Averege</strong></td>
                </tr>
            </table>
        </div>
        <table id="detail">
            <thead>
                <tr>
                    <th rowspan="2">Date Item</th>
                    <th rowspan="2">Ref Trans No</th>
                    <th rowspan="2">Description</th>
                    <th colspan="3" style="text-align: center">IN</th>
                    <th colspan="3" style="text-align: center">OUT</th>
                    <th colspan="3" style="text-align: center">BALANCE</th>
                </tr>
                <tr>
                    {{-- INventory IN --}}
                    <th style="text-align: right">Qty</th>
                    <th style="text-align: right">Price (Rp)</th>
                    <th style="text-align: right">Total (Rp)</th>

                    {{-- Inventory Out --}}
                    <th style="text-align: right">Qty</th>
                    <th style="text-align: right">Price (Rp)</th>
                    <th style="text-align: right">Total (Rp)</th>

                    {{-- Balance --}}
                    <th style="text-align: right">Qty</th>
                    <th style="text-align: right">Price (Rp)</th>
                    <th style="text-align: right">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                {{-- Lastbalance --}}
                @php
                    $totalQty = 0;
                    $totalPrice = 0;
                @endphp
                <tr>
                    <td colspan="9"><b>Last Balance</b></td>
                    <td class="no-wrap" style="text-align: right">{{ floatval($begginningstock->balance_qty) }}</td>
                    <td class="no-wrap" style="text-align: right">
                        {{ number_format($begginningstock->cogs, 2, ',', '.') }}</td>
                    <td class="no-wrap" style="text-align: right">
                        {{ number_format($begginningstock->total, 2, ',', '.') }}</td>
                    @php
                        $totalQty += floatval($begginningstock->balance_qty);
                        $totalPrice += floatval($begginningstock->total);
                    @endphp
                </tr>
                @if (count($detailTrans) > 0)
                    @foreach ($detailTrans as $item)
                        <tr>

                            {{-- Desc Item --}}
                            <td class="no-wrap">{{ \Carbon\Carbon::parse($item->item_date)->format('d/m/Y') }}</td>
                            <td class="no-wrap">{{ $item->reftrans }}</td>
                            <td>{{ $item->description }}</td>


                            {{-- INventory IN --}}
                            <td class="no-wrap" style="text-align: right">
                                {{ $item->type == 'IIN' ? floatval($item->qty) : '-' }}</td>
                            <td class="no-wrap" style="text-align: right">
                                {{ $item->type == 'IIN' ? number_format($item->price, 2, ',', '.') : '-' }}</td>
                            <td class="no-wrap" style="text-align: right">
                                {{ $item->type == 'IIN' ? number_format($item->total, 2, ',', '.') : '-' }}</td>

                            {{-- INventory OUT --}}
                            <td class="no-wrap" style="text-align: right">
                                {{ $item->type == 'IOUT' ? floatval($item->qty) : '-' }}</td>
                            <td class="no-wrap" style="text-align: right">
                                {{ $item->type == 'IOUT' ? number_format($item->price, 2, ',', '.') : '-' }}</td>
                            <td class="no-wrap" style="text-align: right">
                                {{ $item->type == 'IOUT' ? number_format($item->total, 2, ',', '.') : '-' }}</td>

                            {{-- Balance --}}
                            @php
                                if ($item->type == 'IIN') {
                                    $totalQty += floatval($item->qty);
                                    $totalPrice += floatval($item->total);
                                } else {
                                    $totalQty -= floatval($item->qty);
                                    $totalPrice -= floatval($item->total);
                                }
                                $cogs = $totalPrice / $totalQty;
                            @endphp
                            <td class="no-wrap" style="text-align: right">{{ $totalQty }}</td>
                            <td class="no-wrap" style="text-align: right">{{ number_format($cogs, 2, ',', '.') }}
                            </td>
                            <td class="no-wrap" style="text-align: right">{{ number_format($totalPrice, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <section style="margin-top: 200px;position:relative">
        <table style="width: 300px;position:absolute;top:-50;right:230px">
            {{-- <tr>
                <td align="right">Semarang ,
                    {{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }} </td>
            </tr> --}}
        </table>
        <div>
            <table style="width: 200px;position: absolute;top:0;left:150;">
                <tr>
                    <td align="center">Diketahui Oleh</td>
                </tr>
                <tr>
                    <td style="height: 80px;"></td>
                </tr>
                <tr>
                    <td align="center" style="text-decoration:underline"></td>
                </tr>
            </table>
        </div>
        <div style="position: absolute;top:0;right:320px;">
            <table style="width: 250px">
                <tr>
                    <td align="center">Penyimpan Barang</td>
                </tr>
                <tr>
                    <td style="height: 80px;"></td>
                </tr>
                <tr>
                    <td align="center" style="text-decoration:underline"></td>
                </tr>
            </table>
        </div>
    </section>
</body>

</html>
