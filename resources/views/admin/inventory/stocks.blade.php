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
                                <li class="breadcrumb-item active" aria-current="page">Stocks</li>
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
                                <h3 class="mb-0">Item Stocks</h3>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-outline-primary btn-sm btnprint mb-3"><i class="fas fa-print"></i>
                                    Print</button>

                                <table class="align-items-center stockstable table-striped table" class="display nowrap"
                                    style="width:100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Item Code</th>
                                            <th scope="col">Item Name</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Unit Code</th>
                                            <th scope="col">Stock IN</th>
                                            <th scope="col">Stock Out</th>
                                            <th scope="col">Available Stock</th>
                                            <th scope="col">Cogs</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                    </tbody>
                                </table>
                                {{-- <div class="d-lg-flex">
								</div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layout.footer')
    </div>

    {{-- Notif Flash Message --}}
    @include('flashmessage')
    <script src="{{ asset('/') }}js/inventory/stocks.js" type="module"></script>
@endsection
