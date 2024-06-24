@extends('layout.template')
@section('content')
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
                default:
                    # code...
                    break;
            }

        @endphp
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7 text-white">
                        <h1 style="color:white ">WELCOME {{ strtoupper($users->name) }} ({{ $roleName }} )!</h1>
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
        </div>

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
