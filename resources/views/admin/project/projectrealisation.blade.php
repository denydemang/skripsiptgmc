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
                                <li class="breadcrumb-item active" aria-current="page">Project Realisation</li>
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
                                <h3 class="mb-0">PROJECTS REALISATION</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 d-lg-flex justify-content-between">
                                        <div>
                                            <div class="mb-2" style="width: 200px">
                                                <h4>Status Approve</h4>
                                                <select class="form-control" id="statusSelect">
                                                    <option selected value="" selected>All</option>
                                                    <option value="0">Unnaprove</option>
                                                    <option value="1">Approved</option>
                                                </select>
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
                                <a href="{{ route('admin.addProjectrealisationview') }}">
                                    <button class="btn btn-outline-primary btn-sm addbtn mb-2">
                                        <i class="fas fa-plus"></i> ADD NEW
                                    </button></a>
                                <div>
                                    <table class="align-items-center table-flush projectrealisationtable w-100 table">
                                        <thead class="thead-light">
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
    <div class="modal fade" id="modal-detailproject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
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

                        <h3 class="title-detail"></h3>
                    </div>
                    <div class="row">
                        <h3 class="text-white">Material :</h3>
                        <div class="col-12 mb-2" style="max-height:200px ;overflow-y: scroll">
                            <table class="table-sm listbb table-wrap table">
                                <thead>
                                    <tr class="row" style="background-color: rgb(155, 230, 230)">

                                        <th class="col-1">No</th>
                                        <th class="col-3">Item Code</th>
                                        <th class="col-3">Item Name</th>
                                        <th class="col-2">Qty</th>
                                        <th class="col-3">Unit</th>
                                    </tr>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <h3 class="mt-1 text-white">Upah :</h3>
                        <div class="col-12" style="max-height:200px ;overflow-y: scroll">
                            <div>
                                <table class="table-sm listupah table">
                                    <thead>
                                        <tr class="row" style="background-color: rgb(155, 230, 230)">
                                            <th class="col-1">No</th>
                                            <th class="col-2">Upah Code</th>
                                            <th class="col-2">Job</th>
                                            <th class="col-1" style="text-align:right">Qty</th>
                                            <th class="col-1">Unit</th>
                                            <th class="col-2" style="text-align:right">Tarif</th>
                                            <th class="col-3" style="text-align:right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    {{-- Notif Flash Message --}}
    @include('flashmessage')
    <script src="{{ asset('/') }}js/project/projectrealisation.js" type="module"></script>
@endsection
