@extends('layout.template')
@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Admin</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.ledger') }}">Ledger</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 col-lg-12" style="min-height: 800px">
                <div class="row">
                    <div class="col">
                        <div class="card" style="min-height: 800px">
                            <!-- Card header -->
                            <div class="card-header border-0">
                                <h3 class="mb-0">LEDGER REPORT</h3>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="mr-1 mt-5">
                                                <h4>Transaction Date</h4>
                                                <div class="d-flex">
                                                    <div class="input-group date mr-2" id="dtpstarttrans"
                                                        data-target-input="nearest">
                                                        <input type="text"
                                                            class="form-control form-control-sm datetimepicker-input inputstartdatetrans"
                                                            data-target="#dtpstarttrans" style="cursor: pointer" readonly />
                                                        <div class="input-group-append" data-target="#dtpstarttrans"
                                                            data-toggle="dtpstarttrans">
                                                            <div class="input-group-text" style="height: 32px"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                    <span class="mr-1">to </span>
                                                    <div class="input-group date" id="dtplasttrans"
                                                        data-target-input="nearest">
                                                        <input type="text" style="cursor: pointer"
                                                            class="form-control form-control-sm datetimepicker-input inputlastdatetrans"
                                                            data-target="#dtplasttrans" readonly />
                                                        <div class="input-group-append" data-target="#dtplasttrans"
                                                            data-toggle="dtplasttrans">
                                                            <div class="input-group-text" style="height: 32px"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <h4 for="role">COA</h4>
                                                <select class="form-control coa_code" name="coa_code" id="coa_code"
                                                    style="height:45px">
                                                </select>
                                                <button class="btn btn-sm btn-primary btnaddcoa">Add Coa</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <small>List COA</small>
                                            <div
                                                style="width: 100%; 
											height: 200px; 
											overflow-y: auto; 
											overflow-x: auto; 
											border: 1px solid;position:relative; ">
                                                <table border="1"
                                                    style="width: 100%; border: 1px solid #000;border-radius: 8px;border-collapse:collapse; background-color: #f9f9f9"
                                                    class="table-sm tablelistcoa">
                                                    <thead class="text-white" style="background-color:#808283">
                                                        <tr>
                                                            <th class="text-left" style="font-size: 10px">
                                                                No</th>
                                                            <th class="text-left" style="font-size: 10px">
                                                                CODE</th>
                                                            <th class="text-left" style="font-size: 10px;">
                                                                NAME</th>
                                                            <th class="text-left" style="font-size: 10px">
                                                                ...</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-6">
                                            <button class="btn btn-primary btn-sm btnprint"><i
                                                    class="fas fa-print btnprint mr-2"></i>Print</button>
                                            <button class="btn btn-warning btn-sm btnprint2"><i
                                                    class="fas fa-print btnprint2 mr-2"></i>Print All COA</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.footer')
    </div>


    {{-- MODAL FORM --}}

    {{-- @include('searchModal.coaSearch') --}}

    @include('searchModal.customersearch')
    {{-- Notif Flash Message --}}

    <script src="{{ asset('/') }}js/accounting/ledgerreport.js" type="module"></script>
@endsection
