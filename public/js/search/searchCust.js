import tableInitiator from '../tableinitiator.js';
$(document).ready(function () {

  localStorage.clear();
  const modalCustomerSearch = $('#modalCustomerSearch');
  const customerCode = $('.customerCode');
  const customerName = $('.customerName');

  // INISIASI DATATABLE Customer Modal Search
  // ----------------------------------------------------------------
  var getDataCustomer = route('admin.CustomerGetForModal');
  const tableCustomerName = '.customersearchtable';
  const methodGetCust = 'get';
  const columnsCust = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'Code', title: 'Customer Code', searchable: true },
    { data: 'name', name: 'Name', title: 'Name', searchable: true },
    { data: 'address', name: 'address', title: 'Address', searchable: true },
    { data: 'zip_code', name: 'zip_code', title: 'Zip Code', searchable: true },
    { data: 'email', name: 'email', title: 'Email', searchable: true }
  ];

  function ShowTableCustomerSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.showTable();
  }
  function DestroyTableCustomerSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.destroyTable();
  }

  function reloadTableCustomerSearch(method, tableName, columns, url, data = {}) {
    DestroyTableCustomerSearch(method, tableName, columns, url, data);
    ShowTableCustomerSearch(method, tableName, columns, url, data);
  }

  // CRUD AND EVENT
  // ------------------------------------------------------------

  //Click Customer Search
  $(document).on('click', '.btnsearchcustomer', function () {
    modalCustomerSearch.modal('show');
    reloadTableCustomerSearch(methodGetCust, tableCustomerName, columnsCust, getDataCustomer);
  });

  //Select Customer
  $(document).on('click', '.selectcustomerbtn', function () {
    customerCode.val($(this).data('code'));
    customerName.val($(this).data('name'));
    modalCustomerSearch.modal('hide');

    // Populate in localstorage
    localStorage.setItem('customerName', customerName.val());
    localStorage.setItem('customerCode', customerCode.val());
  });
});
