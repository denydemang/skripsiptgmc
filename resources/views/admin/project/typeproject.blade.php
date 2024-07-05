@extends('layout.template')
@section('content')
    <style>
        .table-itemm thead th {
            position: sticky;
            top: -1px;
            background: #86d1f7
        }

        .table-itemm2 thead th {
            position: sticky;
            top: -1px;
            background: #3de65c
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
                                <li class="breadcrumb-item active" aria-current="page">ProjectType</li>
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
                            <div class="card-header border-0">
                                <h3 class="mb-0">PROJECT TYPE</h3>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('admin.addprojecttypeview') }}"
                                    class="btn btn-outline-primary btn-sm mb-2"><i class="fas fa-plus"></i> ADD NEW</a>
                                <table class="align-items-center table-flush proyecttype-table w-100 table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Actions</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Updated By</th>
                                            <th scope="col">Created By</th>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL FORM --}}
    <div class="modal fade" id="modal-projecttype" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog modal-primary" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form id="formProjectType" action="asdasda" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="code">Code</label>
                                    <input class="form-control code" type="text" name="code"
                                        style="font-weight: bolder">
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input class="form-control name" type="text" name="name"
                                        style="font-weight: bolder">
                                    <div class="invalid-feedback">
                                        <b>Name Cannot Be Blank </b>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input class="form-control description" type="text" name="description"
                                        style="font-weight: bolder">
                                    <div class="invalid-feedback">
                                        <b>Description Cannot Be Blank </b>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success btnsave">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Notif Flash Message --}}
    <div class="modal fade" id="modal-detailproject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title titleview">Detail List Type Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="title-detail mb-3">TYPE002 - Pembangunan Gedung</h3>
                            <h3 class="text-dark">Material :</h3>
                        </div>
                        <div class="col-lg-12 table-responsive table-itemm" style="max-height:200px">
                            <table class="table-sm listbb table-wrap table-bordered" style="width: 100%">
                                <thead>
                                    <tr class="bg-primary text-white" style="font-size:12px">
                                        <th>No</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12 mt-5">
                            <h3 class="text-dark mt-1">Upah BTKL:</h3>
                        </div>
                        <div class="col-lg-12 table-responsive table-itemm2" style="max-height:200px">
                            <table class="table-sm listupah table-wrap table-bordered" style="width: 100%">
                                <thead>
                                    <tr class="text-white" style="font-size:12px">
                                        <th>No</th>
                                        <th>Code</th>
                                        <th>Job</th>
                                        <th>Unit</th>
                                        <th style="text-align: right">Tarif</th>
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
    @include('flashmessage')
    <script src="{{ asset('/') }}js/project/projecttype.js" type="module"></script>
@endsection
