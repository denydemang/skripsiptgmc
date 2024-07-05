@extends('layout.template')
@section('content')
    <style>
        .table-itemm thead th {
            position: sticky;
            top: -1px;
            background: #ebf3f6;
            z-index: 999;
        }
    </style>
    <div class="header bg-primary pb-6">
        @php
            $role = $users->id_role;
            $roleName = '';

            switch ($role) {
                case 1:
                    # code...
                    $roleName = 'ADMIN';
                    break;
                case 2:
                    # code...
                    $roleName = 'KEUANGAN';
                    break;
                case 3:
                    # code...
                    $roleName = 'LOGISTIK';
                    break;
                case 4:
                    # code...
                    $roleName = 'Direktur';
                    break;
                default:
                    # code...
                    break;
            }

        @endphp
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7 text-white">
                        <h1 style="color:white ">WELCOME {{ strtoupper($users->name) }} <br>({{ $roleName }} )</h1>
                    </div>
                </div>
                <!-- Card stats -->
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Project This Year</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $totalProject }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-red rounded-circle text-white shadow">
                                    <i class="ni ni-ruler-pencil"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 mt-3 text-sm">
                            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i>{{ $percentageChange }}%</span>
                            <span class="text-nowrap">Since last year</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-stats">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Revenue This Year</h5>
                                <span class="h2 font-weight-bold mb-0">Rp {{ number_format($revenue, 2, ',', '.') }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-blue rounded-circle text-white shadow">
                                    <i class="ni ni-money-coins"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 mt-3 text-sm">
                            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i>
                                {{ $percentageChangeInv }}%</span>
                            <span class="text-nowrap">Since last year</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-stats" style="height: 120px">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Total Customer</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $totalCust }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-green rounded-circle text-white shadow">
                                    <i class="ni ni-satisfied"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-stats" style="height: 120px">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Total Supplier</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $totalSupp }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-yellow rounded-circle text-white shadow">
                                    <i class="ni ni-satisfied"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-stats" style="height: 120px">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Total Project Done</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $projectDone }} of {{ $projectTotal }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-purple rounded-circle text-white shadow">
                                    <i class="ni ni-ruler-pencil"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($roleName == 'LOGISTIK' || $roleName == 'ADMIN')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Stock Reminder</h4>
                        </div>
                        <div class="card-body pb-5">
                            <div class="row mt-2">
                                <div class="col-lg-12 table-responsive table-itemm" style="max-height:200px">
                                    <table class="table-sm table-bordered w-100" style="font-size: 12px;font-weight:normal">
                                        <thead>
                                            <th>No</th>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>Category</th>
                                            <th>Unit</th>
                                            <th>Min Stock</th>
                                            <th>Current Stock</th>
                                        </thead>
                                        <tbody>
                                            @if (count($stockReminder) > 0)
                                                @foreach ($stockReminder as $item)
                                                    <tr>
                                                        <td style="width:5%">{{ $loop->iteration }}</td>
                                                        <td style="width:10%">{{ $item->item_code }}</td>
                                                        <td style="width:30%;word-wrap:break-word;">{{ $item->item_name }}
                                                        </td>
                                                        <td style="width:20%">{{ $item->item_category }}</td>
                                                        <td style="width:10%">{{ $item->unit_code }}</td>
                                                        <td style="width:10%">{{ floatval($item->min_stock) }}</td>
                                                        <td style="width:15%">{{ floatval($item->current_stock) }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" style="text-align: center"> NO RECORDS</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4> Project On Progress</h4>
                        </div>
                        <div class="card-body pb-5">
                            <div class="row mt-2">
                                <div class="col-lg-12 table-responsive table-itemm" style="max-height:200px">
                                    <table class="table-sm table-bordered w-100" style="font-size: 11px">
                                        <thead>
                                            <th>No</th>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Start Date</th>
                                            <th>Customer Name</th>
                                            <th>Project Amount</th>
                                            <th>Project Realisation</th>
                                        </thead>
                                        <tbody>
                                            @if (count($projectOnProgress) > 0)
                                                @foreach ($projectOnProgress as $item)
                                                    <tr>
                                                        <td style="width:5%">{{ $loop->iteration }}</td>
                                                        <td style="width:5%">{{ $item->code }}</td>
                                                        <td style="width:25%;word-wrap:break-word;">{{ $item->name }}
                                                        </td>
                                                        <td style="width:10%">{{ $item->start_date }}</td>
                                                        <td style="width:15%;word-wrap:break-word;">
                                                            {{ $item->customer_name }}
                                                        </td>
                                                        <td style="width:20%;white-space:nowrap">
                                                            {{ number_format($item->budget, 2, ',', '.') }}</td>
                                                        <td style="width:20%;white-space:nowrap">
                                                            {{ number_format($item->realisation_amount, 2, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" style="text-align: center"> NO RECORDS</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($roleName == 'KEUANGAN' || $roleName == 'ADMIN')

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Piutang Yang Belum Terbayar</h4>
                        </div>
                        <div class="card-body pb-5">
                            <div class="row mt-2">
                                <div class="col-lg-12 table-responsive table-itemm" style="max-height:200px">
                                    <table class="table-sm table-bordered w-100" style="font-size: 12px">
                                        <thead>
                                            <th>Code Customer</th>
                                            <th>Nama Customer</th>
                                            <th>Belum Terbayar</th>
                                        </thead>
                                        <tbody>
                                            @if (count($piutangBelumBayar) > 0)
                                                @foreach ($piutangBelumBayar as $item)
                                                    <tr>
                                                        <td>{{ $item->customer_code }}</td>
                                                        <td>{{ $item->customer_name }}</td>
                                                        <td>{{ number_format($item->total, 2, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3" style="text-align: center"> NO RECORDS</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Utang Yang Belum Terbayar</h4>
                        </div>
                        <div class="card-body pb-5">
                            <div class="row mt-2">
                                <div class="col-lg-12 table-responsive table-itemm" style="max-height:200px">
                                    <table class="table-sm table-bordered w-100" style="font-size: 12px">
                                        <thead>
                                            <th>Code Supplier</th>
                                            <th>Nama Supplier</th>
                                            <th>Belum Terbayar</th>
                                        </thead>
                                        @if (count($utangBelumBayar) > 0)
                                            @foreach ($utangBelumBayar as $item)
                                                <tr>
                                                    <td>{{ $item->supplier_code }}</td>
                                                    <td>{{ $item->supplier_name }}</td>
                                                    <td>{{ number_format($item->total, 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" style="text-align: center"> NO RECORDS</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif






        {{-- <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary p-2">
                        <h4 class="text-light">Stock Reminder</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-sm table-bordered w-100 table" style="box-sizing: border-box">
                                <thead class="bg-danger text-white">
                                    <tr>
                                        <td>Item</td>
                                        <td>Current Stock</td>
                                        <td>Min Stock</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width:60%">PEJENKJNKJNKJSNCSDS sdfsdfsdf sdfsdfsdfsdf(ITEM001)</td>
                                        <td style="width: 20%">0</td>
                                        <td style="width:20%">1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary p-2">
                        <h4 class="text-light">Stock Reminder</h4>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>ITEM 0001 </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
