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
            background: #f3f4f5;
            z-index: 999;
        }
    </style>
    <div class="header bg-primary pb-6">
        <div class="datamateriallist"
            data-materiallist="{{ $sessionRoute == 'admin.editprojecttypeview' ? $data['bahanBaku'] : '' }}"></div>
        <div class="dataupahlist" data-upahlist="{{ $sessionRoute == 'admin.editprojecttypeview' ? $data['upah'] : '' }}">
        </div>
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
                                <li class="breadcrumb-item"><a href="{{ route('admin.projecttype') }}">Project type</a></li>
                                @if ($sessionRoute == 'admin.addprojecttypeview')
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
                                @if ($sessionRoute == 'admin.addprojecttypeview')
                                    <h3 class="mb-0">ADD NEW PROJECTS TYPE</h3>
                                @else
                                    <h3 class="mb-0">EDIT PROJECTS TYPE</h3>
                                @endif

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label" for="example3cols1Input">Code <span
                                                        style="color: red">*</span></label>
                                                <input type="text" class="form-control form-control-sm inputcode"
                                                    readonly
                                                    value="{{ $sessionRoute == 'admin.editprojecttypeview' ? $data['projecttype']['code'] : 'AUTO' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label" for="example3cols1Input">Name Type <span
                                                        style="color: red">*</span></label>
                                                <input type="text" class="form-control form-control-sm inputypename"
                                                    value="{{ $sessionRoute == 'admin.editprojecttypeview' ? $data['projecttype']['name'] : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="example3cols1Input">Description <span
                                                        style="color: red">*</span></label>
                                                <textarea class="form-control inputdescription" name="" id="" cols="30" rows="5">{{ $sessionRoute == 'admin.editprojecttypeview' ? $data['projecttype']['description'] : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h5 class="text-warning">Material List</h5>
                                        <div class="form-group mt-3">
                                            <div class="databahanbaku" data-bahanbaku="">
                                            </div>
                                            <div class="d-flex">
                                                @include('component.btnadditem')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 table-responsive" style="max-height: 250px">
                                        <table style="width: 100%" class="table-sm tablematerial table-itemm table">
                                            <thead style="font-size: 8px">
                                                <tr>
                                                    <th style="width: 20px">No</th>
                                                    <th class="text-left" style="font-size: 12px">Item Code</th>
                                                    <th class="text-left" style="font-size: 12px">Item Name</th>
                                                    <th class="text-left" style="font-size: 12px">Unit</th>
                                                    <th class="text-left" style="font-size: 12px">...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h5 class="text-info">Upah BTKL List</h5>
                                        <div class="form-group mt-3">
                                            <div class="dataupah" data-upah="">
                                            </div>
                                            <div class="d-flex">
                                                @include('component.btnaddupah')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 table-responsive table-itemm" style="max-height: 250px">
                                        <table style="width: 100%" class="table-sm tableupah table">
                                            <thead style="font-size: 6px">
                                                <tr>
                                                    <th style="width: 20px">No</th>
                                                    <th class="text-left" style="font-size: 12px">Upah Code</th>
                                                    <th class="text-left" style="font-size: 12px">Job</th>
                                                    <th class="text-left" style="font-size: 12px">Unit</th>
                                                    <th style="font-size: 12px">Price</th>
                                                    <th style="font-size: 12px">...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-lg-4">
                        <button class="btn btn-primary btn-sm submitbtn mt-2 py-1"><i class="fas fa-save mr-2"
                                style="font-size: 16px"></i>

                            @if ($sessionRoute != 'admin.addprojecttypeview')
                                <span>Update</span>
                            @else
                                <span>Save</span>
                            @endif

                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.footer')
    </div>


    {{-- MODAL FORM --}}
    @include('searchModal.itemssearch')

    @include('searchModal.upahsearch')

    {{-- @include('searchModal.coaSearch') --}}


    {{-- Notif Flash Message --}}
    @include('flashmessage')

    </script>
    <script src="{{ asset('/') }}js/project/projecttypemanage.js" type="module"></script>
@endsection
