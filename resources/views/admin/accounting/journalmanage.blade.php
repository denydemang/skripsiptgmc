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
                                <li class="breadcrumb-item"><a href="{{ route('admin.journal') }}">Journal</a></li>
                                @if ($sessionRoute == 'admin.addJournalView')
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
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        @if ($sessionRoute == 'admin.addJournalView')
                            <h3 class="mb-0">ADD NEW JURNAL UMUM</h3>
                        @else
                            <h3 class="mb-0">EDIT JURNAL</h3>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">Voucher No <span style="color: red">*</span></label>
                                    <input type="text" class="form-control form-control-sm inputvoucherno" readonly
                                        value="{{ $sessionRoute == 'admin.addJournalView' ? 'AUTO' : $data['journal'][0]['voucher_no'] }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">Transaction Date <span
                                            style="color: red">*</span></label>
                                    <div class="input-group date mr-2" id="dtptransdate" data-target-input="nearest">
                                        <input type="text" style="cursor: pointer"
                                            class="form-control form-control-sm inputtransdate" data-target="#dtptransdate"
                                            readonly
                                            data-transdate="{{ $sessionRoute == 'admin.addJournalView' ? '' : $data['journal'][0]['transaction_date'] }}" />
                                        <div class="input-group-append" data-target="#dtptransdate"
                                            data-toggle="dtptransdate">
                                            <div class="input-group-text" style="height: 32px"><i
                                                    class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">Journal Type<span style="color: red">*</span></label>
                                    <select class="form-control inputjournaltype form-control-sm">
                                        @if ($sessionRoute == 'admin.addJournalView')
                                            <option value="JU" selected>Jurnal Umum</option> 
                                        @else
                                            <option value="JU">Jurnal Umum</option> 
                                            <option value="JKK">Jurnal Kas Keluar</option> 
                                            <option value="JKM">Jurnal Kas Masuk</option> 
                                            <option value="JP">Jurnal Penyesuaian</option> 
                                            <option value="JPEM">Jurnal Pembelian</option> 
                                            <option value="JPEN">Jurnal Penjualan</option> 
                                        @endif
                                    </select>
                                    <div class="datajournaltype"
                                        data-journaltype="{{ $sessionRoute == 'admin.addJournalView' ? '' : $data['journal'][0]['journal_type_code'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">Ref No <span style="color: red">*</span></label>
                                    <input type="text" class="form-control form-control-sm inputref_no" value="{{ $sessionRoute == 'admin.addJournalView' ? '' : $data['journal'][0]['ref_no'] }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-7 mb-3">
                                <label class="form-control-label" for="example3cols1Input">Search COA <span
                                        style="color: red">*</span></label>
                                <div class="d-flex">
                                    <input type="text" readonly class="form-control form-control-sm inputcoacode"
                                        style="width: 45%" value="">
                                    <input type="text" readonly
                                        class="form-control coa-name form-control-sm inputcoaname"
                                        id="example3colInput" style="width: 55%" value="">
                                    @include('component.btnsearchcoa')
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-5">
                                <label class="form-control-label" for="example3cols1Input">Amount</label>
                                <input type="text" class="form-control form-control-sm inputamount" value="">
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="form-control-label">Debit/Kredit<span style="color: red">*</span></label>
                                    <select class="form-control inputdebitkredit form-control-sm">
                                        <option value="d" selected>Debit</option>
                                        <option value="k">Kredit</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm btnaddcoa"><i class="fas fa-plus"></i> ADD COA</button>
                        <div class="row mt-2">
                            <div class="col-lg-12 table-responsive table-item" style="max-height: 400px">
                                <div class="datadetail"
                                    data-detail="{{ $sessionRoute == 'admin.addJournalView' ? '' : json_encode($data['journal']) }}">
                                </div>
                                <table border="1"
                                    style="width: 100%;  border: 1px solid #000;border-radius: 8px; background-color: #f9f9f9"
                                    class="table-sm tablelistcoa">

                                    @if ( $sessionRoute == 'admin.addJournalView')
                                    <thead class="text-white" style="background-color:#808283">
                                        <tr>
                                            <th class="text-left" style="font-size: 10px;width:15%">CODE</th>
                                            <th class="text-left" style="font-size: 10px;width:30%;">NAME</th>
                                            <th class="text-left" style="font-size: 10px; width:25%">DEBIT</th>
                                            <th class="text-left" style="font-size: 10px;width:25%">KREDIT</th>
                                            <th class="text-left" style="width:5%">...</th>
                                        </tr>
                                    </thead>
                                    @else
                                    <thead class="text-white" style="background-color:#808283">
                                        <tr>
                                            <th class="text-left" style="font-size: 10px;width:10%">CODE</th>
                                            <th class="text-left" style="font-size: 10px;width:25%;">NAME</th>
                                            <th class="text-left" style="font-size: 10px;width:20%;">Description</th>
                                            <th class="text-left" style="font-size: 10px; width:20%">DEBIT</th>
                                            <th class="text-left" style="font-size: 10px;width:20%">KREDIT</th>
                                            <th class="text-left" style="width:5%">...</th>
                                        </tr>
                                    </thead>
                                        
                                    @endif
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label lbldescription">Description <span
                                            style="color: red">*</span></label>
                                    <textarea class="form-control inputdescription" name="" id="" cols="30" rows="5">{{ $sessionRoute == 'admin.addJournalView' ? '' : $data['journal'][0]['description'] }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary submitbtn mt-2 py-2"><i class="fas fa-save mr-2"
                                style="font-size: 16px"></i>

                            @if ($sessionRoute != 'admin.addJournalView')
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

    @include('layout.footer')
    </div>


    {{-- MODAL FORM --}}

    @include('searchModal.customersearch')
    {{-- @include('searchModal.prsearch') --}}
    @include('searchModal.coasearch')

    {{-- @include('searchModal.coaSearch') --}}


    {{-- Notif Flash Message --}}
    @include('flashmessage')
    <script src="{{ asset('/') }}js/accounting/journalmanage.js" type="module"></script>
@endsection
