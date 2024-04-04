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
              <li class="breadcrumb-item active" aria-current="page">Customers</li>
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
              <h3 class="mb-0">Customers</h3>
            </div>
            <div class="card-body">
              <div class="btn btn-outline-primary btn-sm addbtn mb-2"><i class="fas fa-plus"></i> ADD NEW</div>
              <div>
                <table class="align-items-center table-flush globalTabledata w-100 table">
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
  <!-- Footer -->
  <footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
      <div class="col-lg-6">
        <div class="copyright text-lg-left text-muted text-center">
          &copy; 2018
          <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">PT GENTA MULTI JAYYA</a>
        </div>
      </div>
      <div class="col-lg-6">
        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
          <li class="nav-item">
            <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
          </li>
          <li class="nav-item">
            <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/license" class="nav-link" target="_blank">License</a>
          </li>
        </ul>
      </div>
    </div>
  </footer>
</div>


{{-- MODAL FORM --}}
<div class="modal fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
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
                <input class="form-control code" type="text" name="code" style="font-weight: bolder">
              </div>
              <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control name" type="text" name="name" style="font-weight: bolder">
                <div class="invalid-feedback">
                  <b>Name Cannot Be Blank </b>
                </div>
              </div>
              <div class="form-group">
                <label for="address">Address</label>
                <input class="form-control address" type="text" name="address" style="font-weight: bolder">
                <div class="invalid-feedback">
                  <b>Address Cannot Be Blank </b>
                </div>
              </div>
              <div class="form-group">
                <label for="zip_code">Zip Code</label>
                <input class="form-control zip_code" type="text" name="zip_code" style="font-weight: bolder">
              </div>
              <div class="form-group">
                <label for="npwp">NPWP</label>
                <input class="form-control npwp" type="text" name="npwp" style="font-weight: bolder">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control email" type="text" name="email" style="font-weight: bolder">
              </div>
              <div class="form-group">
                <label for="phone">Phone</label>
                <input class="form-control phone" type="text" name="phone" style="font-weight: bolder">
                <div class="invalid-feedback">
                  <b>Phone Cannot Be Blank </b>
                </div>
              </div>
              <div class="form-group">
                <label for="role">COA</label>
                {{-- <input class="form-control description" type="text" name="description" style="font-weight: bolder"> --}}
                <select class="form-control coa_code" name="coa_code" id="coa_code">
                </select>
                <div class="invalid-feedback">
                  <b>Coa Code Cannot Be Blank </b>
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
@include('flashmessage')
<script src="{{ asset('/') }}js/customers/customers.js" type="module"></script>
@endsection
