@extends('layout.template')
@section('content')
    <style>
        /* Kustomisasi dasar untuk elemen input */
        .custom-input {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 11px;
            transition: border-color 0.3s;
        }

        /* Efek hover */
        .custom-input:hover {
            border-color: #999;
        }

        /* Efek focus */
        .custom-input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px #007bff;
        }

        .table-itemm thead th {
            position: sticky;
            top: 0;
            background: #808283
        }
    </style>
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
                                <li class="breadcrumb-item"><a href="{{ route('admin.advancedreceipt') }}">Advanced
                                        Receipt</a></li>
                                @if ($sessionRoute == 'admin.addAdvancedReceiptView')
                                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                                @endif
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
                        <div class="card">
                            <!-- Card header -->
                            <div class="card-header border-0">
                                @if ($sessionRoute == 'admin.addAdvancedReceiptView')
                                    <h3 class="mb-0">ADD NEW ADVANCED RECEIPT</h3>
                                @else
                                    <h3 class="mb-0">EDIT ADVANCED RECEIPT</h3>
                                @endif

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">ADR No <span
                                                        style="color: red">*</span></label>
                                                <input type="text" class="form-control form-control-sm inputadrno"
                                                    readonly
                                                    value="{{ $sessionRoute == 'admin.addAdvancedReceiptView' ? 'AUTO' : $data['AR']['adr_no'] }}"
                                                    id="example3cols1Input">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">Transaction Date <span
                                                        style="color: red">*</span></label>
                                                <div class="input-group date mr-2" id="dtptransdate"
                                                    data-target-input="nearest">
                                                    <input type="text" style="cursor: pointer"
                                                        class="form-control form-control-sm inputtransdate"
                                                        data-target="#dtptransdate" readonly
                                                        data-transdate="{{ $sessionRoute == 'admin.addAdvancedReceiptView' ? '' : $data['AR']['transaction_date'] }}" />
                                                    <div class="input-group-append" data-target="#dtptransdate"
                                                        data-toggle="dtptransdate">
                                                        <div class="input-group-text" style="height: 32px"><i
                                                                class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            @include('component.customerCode')
                                            <div class="datacustomercode"
                                                data-customercode="{{ $sessionRoute == 'admin.addAdvancedReceiptView' ? '' : $data['AR']['customer_code'] }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            @include('component.customerName')
                                            <div class="datacustomername"
                                                data-customername="{{ $sessionRoute == 'admin.addAdvancedReceiptView' ? '' : $data['AR']['customer_name'] }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <label class="form-control-label" for="example3cols1Input">COA For Cash/Bank
                                                <span style="color: red">*</span></label>
                                            <div class="d-flex">
                                                <input type="text" readonly
                                                    class="form-control form-control-sm inputcoacodeforcashbank"
                                                    style="width: 45%" id="example3cols1Input"
                                                    value="{{ $sessionRoute == 'admin.addAdvancedReceiptView' ? '' : $data['AR']['coa_code'] }}">
                                                <input type="text" readonly
                                                    class="form-control coa-name form-control-sm inputcoanameforcashbank"
                                                    id="example3colInput" style="width: 55%"
                                                    value="{{ $sessionRoute == 'admin.addAdvancedReceiptView' ? '' : $data['AR']['coa_name'] }}">
                                                @include('component.btnsearchcoa')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-7">
                                            <label class="form-control-label" for="example3cols1Input">COA For Advanced
                                                Receipt <span style="color: red">*</span></label>
                                            <div class="d-flex">
                                                <input type="text" readonly
                                                    class="form-control form-control-sm inputcoacodeforadvancedreceipt"
                                                    style="width: 45%" id="example3cols1Input"
                                                    value="{{ $data['ArCOA']['code'] }}">
                                                <input type="text" readonly
                                                    class="form-control coa-name form-control-sm inputcoanameforadvancedreceipt"
                                                    id="example3colInput" style="width: 55%"
                                                    value="{{ $data['ArCOA']['name'] }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Deposit Amount <span
                                                        style="color: red">*</span></label>
                                                <input type="text" class="form-control form-control-sm inputAmountCash"
                                                    style="font-weight: bold;color:#353131"
                                                    value="{{ $sessionRoute == 'admin.addAdvancedReceiptView' ? 'Rp 0,00' : 'Rp ' . number_format($data['AR']['deposit_amount'], 2, ',', '.') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <label class="form-control-label">Description <span
                                                        style="color: red">*</span></label>
                                                <textarea class="form-control inputdescription" name="" id="" cols="30" rows="5">{{ $sessionRoute == 'admin.addAdvancedReceiptView' ? '' : $data['AR']['description'] }}</textarea>
                                            </div>

                                            <button class="btn btn-primary btn-sm submitbtn mt-2 py-2"><i
                                                    class="fas fa-save mr-2" style="font-size: 16px"></i>

                                                @if ($sessionRoute != 'admin.addAdvancedReceiptView')
                                                    <span>Update</span>
                                                @else
                                                    <span>Save</span>
                                                @endif

                                            </button>
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

    @include('searchModal.customersearch')
    @include('searchModal.prsearch')
    @include('searchModal.coasearch')

    {{-- @include('searchModal.coaSearch') --}}


    {{-- Notif Flash Message --}}
    @include('flashmessage')
    <script src="{{ asset('/') }}js/finance/advancedreceiptmanage.js" type="module"></script>
@endsection
