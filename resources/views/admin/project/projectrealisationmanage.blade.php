@extends('layout.template')
@section('content')
    <style>
        .ui-datepicker {
            z-index: 9999
        }

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

        .tablematerial th,
        .tableupah th {
            position: sticky;
            top: 0;
            color: white;
            /* Menempatkan header tabel di bagian atas saat digulir */
            z-index: 1;
            border: 1px solid black;
            background-color: #808283;
            /* Mengatur z-index agar header muncul di atas konten */
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
                                <li class="breadcrumb-item"><a href="{{ route('admin.projectrealisationview') }}">Project
                                        Realisation</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $sessionRoute == 'admin.editProjectrealisationview' ? 'Edit' : 'Add' }}</li>
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
                            <div class="card-header border-0">
                                <h3 class="mb-0">
                                    {{ $sessionRoute == 'admin.editProjectrealisationview' ? 'EDIT PROJECT REALISATION' : 'ADD NEW PROJECT REALISATION' }}
                                </h3>
                            </div>
                            <div class="datarealisasi"
                                data-realisasi="{{ $sessionRoute == 'admin.editProjectrealisationview' ? $data['realisasi'] : '' }}">
                            </div>
                            <div class="allrealisation"
                                data-allrealisation="{{ $sessionRoute == 'admin.editProjectrealisationview' ? $data['AllRealisation'] : '' }}">
                            </div>
                            <div class="datacurrentmaterial"
                                data-currentmaterial="{{ $sessionRoute == 'admin.editProjectrealisationview' ? $data['dataCurrentMaterial'] : '' }}">
                            </div>
                            <div class="datacurrentupah"
                                data-currentupah="{{ $sessionRoute == 'admin.editProjectrealisationview' ? $data['dataCurrentUpah'] : '' }}">
                            </div>
                            <div class="datatotaltermin"
                                data-totaltermin="{{ $sessionRoute == 'admin.editProjectrealisationview' ? $data['totalTermin'] : '' }}">
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="form-control-label" for="example3cols1Input">Realisation Code
                                                </label>
                                                <input type="text"
                                                    class="form-control form-control-sm inputrealisationcode" value="AUTO"
                                                    readonly id="example3cols1Input">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                @include('component.customerCode')
                                                <div class="datacustomercode" data-customercode="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                @include('component.customerName')
                                                <div class="datacustomername" data-customername="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="example3cols1Input">Project
                                                Code <span style="color: red">*</span></label>
                                            <div class="d-flex">
                                                <input type="text" class="form-control form-control-sm inputprojectcode"
                                                    value="" readonly>
                                                <button type="button" class="btn btn-sm btnsearchproject"
                                                    title="Search Project">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="example3cols1Input">Project
                                                Name <span style="color: red">*</span></label>
                                            <input type="text" class="form-control form-control-sm inputprojectname"
                                                value="" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="example3cols1Input">Total Project
                                                Amount <span style="color: red">*</span></label>
                                            <input type="text" class="form-control form-control-sm inputtotalproject"
                                                value="" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="example3cols1Input">Realisation
                                                Date <span style="color: red">*</span></label>
                                            <div class="input-group date mr-2" id="dtprealisationdate"
                                                data-target-input="nearest">
                                                <input type="text" style="cursor: pointer"
                                                    class="form-control form-control-sm inputrealisationdate"
                                                    data-target="#dtprealisationdate" readonly data-transdate="" />
                                                <div class="input-group-append" data-target="#dtprealisationdate"
                                                    data-toggle="dtprealisationdate">
                                                    <div class="input-group-text" style="height: 32px"><i
                                                            class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-lg-6">
                                        <h5 class="text-primary">REALISATION LIST <span style="color: red">*</span> <span
                                                class="fetchingdata ml-1"></span></h5>
                                        <h5>Termin : <span class="labelcurrenttermin"></span> of <span
                                                class="labeltotaltermin"></span></h5>
                                        <table class="table-sm tabletermin table">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>No Termin</th>
                                                    <th>Realisation Code</th>
                                                    <th style="text-align:right">Realisation Amount</th>
                                                    <th>Done (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="d-lg-flex">
                                            <h5 class="text-primary">MATERIAL LIST <span class="fetchingdata"></span></h5>
                                        </div>
                                        <div class="form-group">
                                            <div class="databahanbaku" data-bahanbaku="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-10" style="max-height:200px;margin-bottom:120px">
                                                <table class="w-100 tablemateriallist table-bordered table"
                                                    cellpadding="3" style="table-layout:auto; border-collapse: collapse">
                                                    <thead class="bg-primary text-white" style="font-size: 12px">
                                                        <tr>
                                                            <th style="width:5%;">No</th>
                                                            <th style="width:10%">Item Code</th>
                                                            <th style="width:50%">Item Name</th>
                                                            <th style="width:5%">Unit</th>
                                                            <th
                                                                style="width:10%; text-align:right;white-space:normal;word-wrap: break-word">
                                                                Last Balance Qty</th>
                                                            <th
                                                                style="width:10%;text-align:right;white-space:normal;word-wrap: break-word">
                                                                Current Qty</th>
                                                            <th
                                                                style="width:10%;text-align:right;white-space:normal;word-wrap: break-word">
                                                                Balance Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="d-lg-flex">
                                            <h5 class="text-primary">UPAH LIST <span class="fetchingdata"></span></h5>
                                        </div>
                                        <div class="form-group">
                                            <div class="dataupah" data-upah="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 w-100" style="max-height:200px">
                                                <table class="table-sm table-bordered w-100 tableupahlist" cellpadding="3"
                                                    style="table-layout:auto; border-collapse: collapse">
                                                    <thead class="bg-primary text-white" style="font-size: 9px">
                                                        <tr>
                                                            <th style="width:10%">Upah Code</th>
                                                            <th style="width:20%">Upah Name</th>
                                                            <th style="width:5%">Unit</th>
                                                            <th style="width:15%;text-align:right">Price</th>
                                                            <th style="width:10%;text-aligN:right">
                                                                Last Balance Qty</th>
                                                            <th style="width: 10%;text-align:right">
                                                                Current Qty</th>
                                                            <th style="width: 10%;text-align:right">
                                                                Balance Qty</th>
                                                            <th style="width: 20%;text-align:right">
                                                                Current Nominal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="font-size: 11px">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Description <span
                                                    style="color: red">*</span></label>
                                            <textarea class="form-control inputdescription" name="" id="" cols="30" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-6">
                                        <button class="btn btn-primary btn-sm btn-submit">
                                            <i class="fas fa-check"></i>
                                            <span>
                                                Submit
                                            </span>
                                        </button>
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

    @include('searchModal.customerSearch')
    @include('searchModal.projectsearch')
    {{-- @include('searchModal.coaSearch') --}}


    {{-- Notif Flash Message --}}
    @include('flashmessage')
    <script src="{{ asset('/') }}js/project/projectrealisationmanage.js" type="module"></script>
@endsection
