import tableInitiator from '../tableinitiator.js';
$(document).ready(function () {
  const modalItemSearch = $('#modalItemSearch');
  let currentButton = '';

  // INISIASI DATATABLE Items Modal Search
  // ----------------------------------------------------------------
  var getDataItem = route('admin.getTableItemSearch');
  const tableItemName = '.itemsearchtable';
  const methodGetItem = 'get';
  const columnItems = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false },
    { data: 'code', name: 'Code', title: 'Item Code', searchable: true },
    { data: 'name', name: 'Name', title: 'Item Name', searchable: true },
    { data: 'unit_code', name: 'unit_code', title: 'Unit Code', searchable: false },
    { data: 'min_stock', name: 'min_stock', title: 'Min Stock', searchable: false },
    { data: 'max_stock', name: 'max_stock', title: 'Max Stock', searchable: false },
    { data: 'stocks', name: 'stocks', title: 'Stocks', searchable: false }
  ];

  function showTableItemSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.showTable();
  }
  function DestroyTableItemSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.destroyTable();
  }

  function reloadtableItemSearch(method, tableName, columns, url, data = {}) {
    DestroyTableItemSearch(method, tableName, columns, url, data);
    showTableItemSearch(method, tableName, columns, url, data);
  }

  // CRUD AND EVENT
  // ------------------------------------------------------------

  //Click Item Search
  $(document).on('click', '.btn-item-add', function () {
    modalItemSearch.modal('show');
    reloadtableItemSearch(methodGetItem, tableItemName, columnItems, getDataItem);
  });

  // // Select COA
  // $(document).on('click', '.btnselectCOA', function () {
  //   let COACODE = $(this).data('code');

  //   let inputElement = currentButton.siblings('.form-control');
  //   inputElement.val(COACODE);
  //   modalItemSearch.modal('hide');
  // });
});
