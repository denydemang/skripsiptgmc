<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory OUT</title>
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

    <header style="margin-bottom: 50px;padding-top:0;border-top: 2px solid #333">
        <h3>PT GENTA MULTI JAYYA</h3>
        <h3 style="margin-top:-10px ">INVENTORY OUT</h3>
        <h3 style="margin-top:-10px ">{{ $firstDate }} - {{ $lastDate }}</h3>
    </header>

    <section class="transaction">
        <table id="detail">
            <thead>
                <tr>
                    <th>IOT No</th>
                    <th>Ref Transaction</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th style="text-align: right">Qty OUT</th>
                    <th style="text-align: right">COGS</th>
                    <th style="text-align: right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stocckData as $tanggal => $items)
                    <tr>
                        <td colspan="8" class="headerdate"><b>Date OUT :
                                {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</b>
                        </td>
                    </tr>
                    @foreach ($items as $item)
                        <tr>
                            <td class="no-wrap">{{ $item->ioutno }}</td>
                            <td class="no-wrap">{{ $item->ref_no }}</td>
                            <td class="no-wrap">{{ $item->item_code }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->unit_code }}</td>
                            <td class="no-wrap" style="text-align: right">{{ floatval($item->qty) }}</td>
                            <td class="no-wrap" style="text-align: right">Rp.
                                {{ number_format($item->cogs, 2, ',', '.') }}</td>
                            <td class="no-wrap" style="text-align: right">Rp.
                                {{ number_format($item->total, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </section>
</body>

</html>
