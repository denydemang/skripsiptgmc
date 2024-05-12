import tableInitiator from '../tableinitiator.js';
$(document).ready(function () {
  localStorage.clear();
  const modalSupplierSearch = $('#modalSupplierSearch');
  const supplierCode = $('.supplierCode');
  const supplierName = $('.supplierName');

  // INISIASI DATATABLE Customer Modal Search
  // ----------------------------------------------------------------
  var getDatSupplier = route('admin.SupplierGetForModal');
  const tableSupplierName = '.suppliersearchtable';
  const methodGetSupp = 'get';
  const columnsSupp = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'Code', title: 'Supplier Code', searchable: true },
    { data: 'name', name: 'Name', title: 'Name', searchable: true },
    { data: 'address', name: 'address', title: 'Address', searchable: true },
    { data: 'zip_code', name: 'zip_code', title: 'Zip Code', searchable: true },
    { data: 'email', name: 'email', title: 'Email', searchable: true }
  ];

  function ShowTableSupplierSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.showTable();
  }
  function DestroyTableSupplierSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.destroyTable();
  }

  function reloadTableSuppSearch(method, tableName, columns, url, data = {}) {
    DestroyTableSupplierSearch(method, tableName, columns, url, data);
    ShowTableSupplierSearch(method, tableName, columns, url, data);
  }

  // CRUD AND EVENT
  // ------------------------------------------------------------

  //Click Customer Search
  $(document).on('click', '.btnsearchsupplier', function () {
    modalSupplierSearch.modal('show');
    reloadTableSuppSearch(methodGetSupp, tableSupplierName, columnsSupp, getDatSupplier);
  });

  //Select Customer
  $(document).on('click', '.selectsupplierbtn', function () {
    supplierCode.val($(this).data('code'));
    supplierName.val($(this).data('name'));
    modalSupplierSearch.modal('hide');

    // Populate in localstorage
    localStorage.setItem('supplierName', supplierName.val());
    localStorage.setItem('supplierCode', supplierCode.val());
  });
});
