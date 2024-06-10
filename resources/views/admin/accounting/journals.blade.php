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
                                <li class="breadcrumb-item active" aria-current="page">Journal</li>
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
            <div class="col-xl-12 col-lg-12">
                <div class="row">
                    <div class="col">
                        <div class="card" style="min-height: 800px">
                            <!-- Card header -->
                            <div class="card-header">
                                <h3 class="mb-0">Journal</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 d-lg-flex justify-content-between">
                                        <div class="row">
                                            <div class="d-lg-flex justify-content-beetwen">
                                                <div class="mb-2 ml-2 mr-2" style="width:150px">
                                                    <h4>Status Posting</h4>
                                                    <select class="form-control" id="statusSelectPosting">
                                                        <option selected value="">All</option>
                                                        <option value="0">UnPosted</option>
                                                        <option value="1">Posted</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2" style="width: 150px">
                                                    <h4>Journal Type</h4>
                                                    <select class="form-control" id="statusSelectJournalType">
                                                        <option selected value="">All</option>
                                                        <option value="JKK">JKK - Jurnal Kas Keluar</option>
                                                        <option value="JKM">JKM - Jurnal Kas Masuk</option>
                                                        <option value="JP">JP - Jurnal Penyesuian</option>
                                                        <option value="JPEM">JPEM - Jurnal Pembelian</option>
                                                        <option value="JPEN">JPEN - Jurnal Penjualan</option>
                                                        <option value="JU">JU - Jurnal Umum</option>
                                                    </select>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button class="btn btn-sm btn-success btnprintrecap ml-3 mt-3"><i
                                                            class="fas fa-print"></i>
                                                        Print Recap</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div>

                                            <div class="mr-1">
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
                                </div>
                                <br>

                                <a href="{{ route('admin.addJournalView') }}">
                                    <button class="btn btn-outline-primary btn-sm addbtn mb-2">
                                        <i class="fas fa-plus"></i> ADD NEW
                                    </button>
                                </a>
                                <div>
                                    <table class="align-items-center table-flush journaltable w-100 table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Actions</th>
                                                <th scope="col">Voucher No</th>
                                                <th scope="col">Transaction Date</th>
                                                <th scope="col">Journal Type Code</th>
                                                <th scope="col">Journal Type Name</th>
                                                <th scope="col">Ref Trans no</th>
                                                <th scope="col">Posting Status</th>
                                                <th scope="col">Created By</th>
                                                <th scope="col">Updated By</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                        </tbody>
                                    </table>
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
    <div class="modal fade" id="modal-detailpurchase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title titleview"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6" border="0">
                            <table class="text-dark">
                                <tr>
                                    <td>
                                        <h4 class="text-dark">Voucher No </h4>
                                    </td>
                                    <td>
                                        <h4 class="text-dark voucherno">: -</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4 class="text-dark">Ref Trans No </h4>
                                    </td>
                                    <td>
                                        <h4 class="text-dark reftransno">: -</h4>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-4 offset-lg-2">
                            <table class="text-dark">
                                <tr>
                                    <td>
                                        <h4 class="text-dark">Trans Date </h4>
                                    </td>
                                    <td>
                                        <h4 class="text-dark transdatejurnal">: -</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4 class="text-dark">Journal Type </h4>
                                    </td>
                                    <td>
                                        <h4 class="text-dark typejurnal">: -</h4>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12 table-responsive table-itemm" style="max-height: 400px">
                            <table border="1"
                                style="width: 100%;  border: 1px solid #000;border-radius: 8px; background-color: #f9f9f9"
                                class="table-lg tablelistdetailjurnal">
                                <thead class="text-dark" style="background-color:#83a2b4">
                                    <tr>
                                        <th class="text-left" style="font-size: 14px;width:10%;padding:5px">Coa Code</th>
                                        <th class="text-left" style="font-size: 14px;width:20%;padding:5px">Coa Name</th>
                                        <th class="text-left" style="font-size: 14px; width:30%;padding:5px">Description
                                        </th>
                                        <th class="text-left" style="font-size: 14px;width:20%;padding:5px">Debit</th>
                                        <th class="text-left" style="font-size: 14px;width:20%;padding:5px">Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Search --}}

    @include('searchModal.customersearch')

    {{-- Notif Flash Message --}}
    @include('flashmessage')
    <script src="{{ asset('/') }}js/accounting/journal.js" type="module"></script>
@endsection
