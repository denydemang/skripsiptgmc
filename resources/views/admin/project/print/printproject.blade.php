<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .no-wrap {
            white-space: nowrap;
        }

        .page_break {
            page-break-before: always;
        }

        header {
            /* background-color: #f2f2f2; */
            padding: 10px;
            text-align: center;
        }

        .transaction {
            margin: 10px;
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
            font-family: Arial, sans-serif;
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
            width: 300px
        }
    </style>
</head>

<body>

    <div class="logo" style="margin-left:-60px">
        @include('layout.logoimage')
    </div>
    <header style="margin-top: 40px">
        <h3 style="margin-top:-10px">PROJECT REPORT</h3>
    </header>
    <section class="transaction">
        <h3 style="text-decoration: underline">Detail Project</h3>
        <div class="detail-info" style="margin-bottom: 60px;position:relative">
            <div class="detail-info-1" style="max-width:65%">
                <table cellpadding="3" style="width: 100%">
                    <tr>
                        <td style="vertical-align: top"><strong>Project Name</strong></td>
                        <td style="vertical-align: top">:</td>
                        <td style="vertical-align: top">{{ $project['name'] }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top"><strong>Project Code</strong></td>
                        <td style="vertical-align: top">:</td>
                        <td style="vertical-align: top">{{ $project['code'] }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top"><strong>Project Type</strong></td>
                        <td style="vertical-align: top">:</td>
                        <td style="vertical-align: top">{{ $project['project_type_code'] }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top"><strong>Location</strong></td>
                        <td style="vertical-align: top">:</td>
                        <td style="vertical-align: top">{{ $project['location'] }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top"><strong>Estimation</strong></td>
                        <td style="vertical-align: top">:</td>
                        <td style="vertical-align: top">{{ round(floatval($project['duration_days']) / 30, 0) }}
                            Months</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top"><strong>Cust. Code</strong></td>
                        <td style="vertical-align: top">:</td>
                        <td style="vertical-align: top">{{ $project['customer_code'] }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top"><strong>Cust. Name</strong></td>
                        <td style="vertical-align: top">:</td>
                        <td style="vertical-align: top">{{ $project['customer_name'] }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top"><strong>Description</strong></td>
                        <td style="vertical-align: top">:</td>
                        <td style="vertical-align: top">{{ $project['description'] }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top">
                            <strong>Project Amount</strong>
                        </td>
                        <td style="vertical-align: top">:</td>
                        <td style="vertical-align: top"><strong>Rp
                                {{ number_format($project['budget'], 2, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>

            <div class="detail-info-2" style="position: absolute;top:0;right:0">
                <table cellpadding="3">
                    <tr>
                        <td><strong>Status Project</strong></td>
                        <td>:</td>
                        @php
                            $statusProject = '';

                            if (
                                $project['budget'] - $project['realisation_amount'] > 0 &&
                                intval($project['project_status']) == 0
                            ) {
                                $statusProject = 'Not Started';
                            } elseif (
                                $project['budget'] - $project['realisation_amount'] > 0 &&
                                intval($project['project_status']) == 1
                            ) {
                                $statusProject = 'On Progress';
                            } else {
                                $statusProject = 'Done';
                            }
                        @endphp
                        <td>{{ $statusProject }}</td>
                    </tr>
                    <tr>
                        <td><strong>Transaction Date</strong></td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($project['transaction_date'])->format('d/m/Y') }}</td>
                    </tr>
                    @if ($project['start_date'])
                        <tr>
                            <td><strong>Started Date</strong></td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($project['start_date'])->format('d/m/Y') }}</td>
                        </tr>
                    @endif
                    @if ($project['end_date'])
                        <tr>
                            <td><strong>Finish Date</strong></td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($project['end_date'])->format('d/m/Y') }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td><strong>Total Termin</strong></td>
                        <td>:</td>
                        <td>{{ $project['total_termin'] }}</td>
                    </tr>
                    @if ($currenttermin > 0)
                        <tr>
                            <td><strong>Current Termin</strong></td>
                            <td>:</td>
                            <td>{{ $currenttermin }}</td>
                        </tr>
                    @endif
                    @if ($percentprogress > 0)
                        <tr>
                            <td><strong>Percent Progress</strong></td>
                            <td>:</td>
                            <td>{{ floatval($percentprogress) }} %</td>
                        </tr>
                    @endif
                </table>
            </div>

        </div>
    </section>
    @if (count($projectrealisation) > 0)
        <section style="margin-top: -60px">
            <h3 style="text-decoration: underline">Detail Realisation</h3>
            <table border="1" cellpadding="3" style="border-collapse: collapse;border:1px solid black">
                <thead>
                    <th style="padding: 5px">No Termin</th>
                    <th style="padding: 5px">Code Realisation</th>
                    <th style="padding: 5px">Realisation Date</th>
                    <th style="padding: 5px">Realisation Amount</th>
                </thead>
                <tbody>
                    @php
                        $totalRealisasi = 0;
                    @endphp
                    @foreach ($projectrealisation as $item)
                        <tr>
                            <td style="padding: 5px">{{ $item['termin'] }}</td>
                            <td style="padding: 5px">{{ $item['code'] }}</td>
                            <td style="padding: 5px">
                                {{ \Carbon\Carbon::parse($item['realisation_date'])->format('d/m/Y') }}</td>
                            <td style="padding: 5px">Rp {{ number_format($item['realisation_amount'], 2, ',', '.') }}
                            </td>
                        </tr>
                        @php
                            $totalRealisasi += floatval($item['realisation_amount']);
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="3" style="padding: 5px">Total</td>
                        <td style="padding: 5px">Rp {{ number_format($totalRealisasi, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </section>
    @endif
    <div class="page_break"></div>
    <section class="transaction">
        <h5 style="text-decoration: underline">Detail Item Project</h5>
        <table id="detail" style="font-size: 12px;width: 100%;table-layout:auto">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th style="word-break: break-all;word-wrap:break-word;text-align: right">Qty Estimated</th>
                    <th style="word-break: break-all;word-wrap:break-word;text-align: right">Qty Used</th>
                    <th style="word-break: break-all;word-wrap:break-word;text-align: right">Different</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bahanBaku as $item)
                    <tr>
                        <td class="no-wrap" style="width:5%">{{ $loop->iteration }}</td>
                        <td class="no-wrap" style="width:15%">{{ $item->item_code }}</td>
                        <td style="word-break: break-all;word-wrap:break-word;width:35%">{{ $item->item_name }}</td>
                        <td class="no-wrap" style="text-align:right;width:10%">{{ $item->unit_code }}</td>
                        <td class="no-wrap" style="text-align: right;width:15%">{{ floatval($item->qty_estimated) }}
                        </td>
                        <td class="no-wrap" style="text-align: right;width:15%">{{ floatval($item->qty_used) }}</td>
                        <td class="no-wrap" style="text-align: right;width:15%">{{ floatval($item->qty_remaining) }}
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </section>

    <section class="transaction">
        <h5 style="text-decoration: underline">Detail Upah BTKL Project</h5>
        <table id="detail" style="font-size: 12px;width: 100%;table-layout:auto">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Upah Code</th>
                    <th>Job</th>
                    <th style="text-align: right">Estimated</th>
                    <th style="text-align: right">Realisation</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalEstimated = 0;
                    $totalRealisation = 0;
                @endphp
                @foreach ($upah as $item)
                    <tr>
                        <td class="no-wrap">{{ $loop->iteration }}</td>
                        <td>{{ $item->upah_code }}</td>
                        <td>{{ $item->job_name }}</td>
                        <td class="no-wrap" style="text-align:right">Rp.
                            {{ number_format($item->amount_estimated, 2, ',', '.') }}
                        </td>
                        <td class="no-wrap" style="text-align:right"> Rp.
                            {{ number_format($item->amount_realised, 2, ',', '.') }}</td>
                    </tr>
                    @php
                        $totalEstimated += floatval($item->amount_estimated);
                        $totalRealisation += floatval($item->amount_realised);
                    @endphp
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: center"><b>Total</b></td>
                    <td class="no-wrap" style="text-align: right"><b>Rp.
                            {{ number_format($totalEstimated, 2, ',', '.') }}</b>
                    </td>
                    <td class="no-wrap" style="text-align: right"><b>Rp.
                            {{ number_format($totalRealisation, 2, ',', '.') }}</b>
                    </td>
                </tr>
                <tr>
                    @php
                        $keterangan = '';
                        if ($totalEstimated > $totalRealisation) {
                            $keterangan = '(Surplus)';
                        } elseif ($totalEstimated < $totalRealisation) {
                            $keterangan = '(Defisit)';
                        }
                    @endphp
                    <td colspan="3" style="text-align: center"><b>Different {{ $keterangan }}</b></td>
                    <td colspan="2" class="no-wrap" style="text-align: right"><b>Rp.
                            {{ number_format($totalEstimated - $totalRealisation, 2, ',', '.') }}</b>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
    <section style="margin-top: 120px;margin-bottom:200px;position:relative">
        <div>
            <table style="width: 200px">
                <tr>
                    <td align="center">Mengetahui</td>
                </tr>
                <tr>
                    <td style="height: 80px;"></td>
                </tr>
                <tr>
                    <td align="center"></td>
                </tr>
            </table>
        </div>
        <div style="position: absolute;top:0;right:50;">
            <table style="width: 200px">
                <tr>
                    <td align="center">PIC (Person In Charge)</td>
                </tr>
                <tr>
                    <td style="height: 80px;"></td>
                </tr>
                <tr>
                    <td align="center" style="text-decoration:underline">{{ $project['pic'] }}</td>
                </tr>
            </table>
        </div>
    </section>
</body>

</html>
