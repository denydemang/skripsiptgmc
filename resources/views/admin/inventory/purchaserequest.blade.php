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
                                <li class="breadcrumb-item active" aria-current="page">Purchase Request</li>
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
                                <h3 class="mb-0">Purchase Request</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 d-lg-flex justify-content-between">
                                        <div>
                                            <div class="d-lg-flex justify-content-beetwen">
                                                <div class="mb-2 mr-2" style="width: 200px">
                                                    <h4>Status Approve</h4>
                                                    <select class="form-control" id="statusSelectApprove">
                                                        <option selected value="">All</option>
                                                        <option value="0">Not Approved</option>
                                                        <option value="1">Approved</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2" style="width: 200px">
                                                    <h4>Status Purchase</h4>
                                                    <select class="form-control" id="statusSelectPurchase">
                                                        <option selected value="">All</option>
                                                        <option value="0">Not Purchased</option>
                                                        <option value="1">Already Purchased</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-lg-flex" style="width: 600px">
                                                <div class="mr-2">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="mr-2">Date Need</h4>
                                                        <div class="form-group pt-3">
                                                            <input class="checkdateneed" style="display:flex"
                                                                type="checkbox">
                                                        </div>
                                                    </div>
                                                    <div class="form-group daterangegroup2 d-none mb-3">
                                                        <div class="d-flex">
                                                            <div class="input-group date mr-2" id="dtpstartdateneed"
                                                                data-target-input="nearest">
                                                                <input type="text"
                                                                    class="form-control form-control-sm inputstartdateneed"
                                                                    data-target="#dtpstartdateneed" style="cursor: pointer"
                                                                    readonly />
                                                                <div class="input-group-append"
                                                                    data-target="#dtpstartdateneed"
                                                                    data-toggle="dtpstartdateneed">
                                                                    <div class="input-group-text" style="height: 32px"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                            <span class="mr-1">to </span>
                                                            <div class="input-group date" id="dtpenddateneed"
                                                                data-target-input="nearest">
                                                                <input type="text" style="cursor: pointer"
                                                                    class="form-control form-control-sm inputenddateneed"
                                                                    data-target="#dtpenddateneed" readonly />
                                                                <div class="input-group-append"
                                                                    data-target="#dtpenddateneed"
                                                                    data-toggle="dtpenddateneed">
                                                                    <div class="input-group-text" style="height: 32px"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                <a href="{{ route('admin.addprview') }}">
                                    <button class="btn btn-outline-primary btn-sm addbtn mb-2">
                                        <i class="fas fa-plus"></i> ADD NEW
                                    </button></a>
                                <div>
                                    <table class="align-items-center table-flush prtable w-100 table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Action</th>
                                                <th scope="col">PR Code</th>
                                                <th scope="col">Transaction Date</th>
                                                <th scope="col">Date Nee\d</th>
                                                <th scope="col">PIC (Person In Charge)</th>
                                                <th scope="col">Division</th>
                                                <th scope="col">Doc Ref</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Approved</th>
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
    <div class="modal fade" id="modal-detailpr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 1px solid rgb(219, 218, 218)">
                    <h5 class="modal-title titleview text-dark"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <h3 class="title-detail text-dark"></h3>
                    </div>
                    <div class="row">
                        <h3 class="mt-1 text-white">Detail PR Item :</h3>
                        <div class="col-12 mb-2" style="max-height:200px ;overflow-y: scroll">
                            <table class="table-sm listbb table-wrap table">
                                <thead>
                                    <tr class="row bg-primary text-white">

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
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    {{-- Notif Flash Message --}}
    @include('flashmessage')
    <script src="{{ asset('/') }}js/inventory/purchaserequest.js" type="module"></script>
@endsection
