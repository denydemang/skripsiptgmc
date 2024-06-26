<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase</title>
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
        <h3 style="margin-top:-10px ">Purchase</h3>
    </header>
    <section class="transaction" style="font-size: 11px">
        <div class="transaction-info" style="margin-bottom:50px">
            <table border="0" cellpadding="2" style="padding: 5px">
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Purchase No</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder; padding-left:12px">
                            {{ $dataPurchase[0]['purchase_no'] }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Supplier Code</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder;padding-left:12px">
                            {{ $dataPurchase[0]['supplier_code'] }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Supplier Name </p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder;padding-left:12px">
                            {{ $dataPurchase[0]['supplier_name'] }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Supplier Address </p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder;padding-left:12px">
                            {{ $dataPurchase[0]['supplier_address'] }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Supplier Phone </p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder;padding-left:12px">
                            {{ $dataPurchase[0]['supplier_phone'] }}</p>
                    </td>
                </tr>
            </table>
            <table border="0" cellpadding="2" style="padding: 5px" style="position: absolute;top:0;right:0"">
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Transaction Date </p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">
                            {{ \Carbon\Carbon::parse($dataPurchase[0]['transaction_date'])->format('d/m/Y') }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Payment Term </p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">
                            {{ $dataPurchase[0]['payment_term_code'] }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">Purchase Request No </p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">:</p>
                    </td>
                    <td>
                        <p style="text-align: left; font-size:14px;font-weight:bolder">
                            {{ $dataPurchase[0]['pr_no'] }}
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </section>
    <section class="transaction">
        <h5 style="text-decoration: underline">Detail Item</h5>
        <table id="detail" style="font-size: 12px;width: 100%;table-layout:auto">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th style="text-align: right">Price</th>
                    <th style="text-align: right">Discount</th>
                    <th style="text-align: right">Total</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($dataPurchase as $x)
                    <tr>
                        <td class="no-wrap" style="width:5%">{{ $loop->iteration }}</td>
                        <td style="width: 25%">{{ $x->item_name }} ({{ $x->item_code }}) </td>
                        <td class="no-wrap" style="width: 10%%">{{ floatval($x->qty) }}</td>
                        <td>{{ $x->unit_code }}</td>
                        <td class="no-wrap" style="20%;text-align:right">Rp.
                            {{ number_format($x->price, 2, ',', '.') }}</td>
                        <td class="no-wrap" style="20%;text-align:right">Rp.
                            {{ number_format($x->discount, 2, ',', '.') }}</td>
                        <td class="no-wrap" style="20%;text-align:right">Rp.
                            {{ number_format($x->total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="no-wrap" colspan="6" style="text-align: right"><b>Sub Total</b></td>
                    <td style="text-align: right">Rp.
                        {{ number_format($dataPurchase[0]['total_item_purchase'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="no-wrap" colspan="6" style="text-align: right"><b>Freight In</b></td>
                    <td style="text-align: right">Rp. {{ number_format($dataPurchase[0]['other_fee'], 2, ',', '.') }}
                    </td>
                </tr>
                @if (floatval($dataPurchase[0]['percen_ppn']) > 0)
                    <tr>
                        <td class="no-wrap" colspan="6" style="text-align: right"><b>PPN
                                ({{ floatval($dataPurchase[0]['percen_ppn']) * 100 }}%)</b></td>
                        <td style="text-align: right">Rp.
                            {{ number_format($dataPurchase[0]['ppn_amount'], 2, ',', '.') }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="no-wrap" colspan="6" style="text-align: right"><b>Grand Total</b></td>
                    <td style="text-align: right">Rp. {{ number_format($dataPurchase[0]['grand_total'], 2, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td class="no-wrap" colspan="6" style="text-align: right"><b>Paid Amount</b></td>
                    <td style="text-align: right">Rp. {{ number_format($dataPurchase[0]['paid_amount'], 2, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <section style="margin-top: 200px;margin-bottom: 200px;position:relative;font-size:12px">
        <table style="width: 300px;position:absolute;top:-50;right:10">
            <tr>
                <td align="right">Semarang ,
                    {{ \Carbon\Carbon::parse($dataPurchase[0]['transaction_date'])->isoFormat('DD MMMM YYYY') }} </td>
            </tr>
        </table>
        <div>
            <table style="width: 200px;position: absolute;top:0;left:0;">
                <tr>
                    <td align="center">Approved By</td>
                </tr>
                <tr>
                    <td style="height: 80px;"></td>
                </tr>
                <tr>
                    <td align="center" style="text-decoration:underline">{{ $dataPurchase[0]['approved_by'] }}</td>
                </tr>
            </table>
        </div>
        <div style="position: absolute;top:0;right:350px;">
            <table style="width: 220px">
                <tr>
                    <td align="center">Known By</td>
                </tr>
                <tr>
                    <td style="height: 80px;"></td>
                </tr>
                <tr>
                    <td align="center" style="text-decoration:underline"></td>
                </tr>
            </table>
        </div>
        <div style="position: absolute;top:0;right:100px;">
            <table style="width: 220px">
                <tr>
                    <td align="center">Created By</td>
                </tr>
                <tr>
                    <td style="height: 80px;"></td>
                </tr>
                <tr>
                    <td align="center" style="text-decoration:underline">{{ $dataPurchase[0]['dibuat_oleh'] }}</td>
                </tr>
            </table>
        </div>
    </section>
</body>

</html>
