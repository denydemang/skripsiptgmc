import tableInitiator from '../tableinitiator.js';
$(document).ready(function () {
  localStorage.clear();
  const modalCOASearch = $('#modalCOASearch');
  let currentButton = '';

  // INISIASI DATATABLE Customer Modal Search
  // ----------------------------------------------------------------
  var getDataCOA = route('admin.getCOATableSearch');
  const tableCOAName = '.COASearchtable';
  const methodGetCOA = 'get';
  const columnCOA = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false },
    { data: 'code', name: 'Code', title: 'COA CODE', searchable: true },
    { data: 'name', name: 'Name', title: 'COA Name', searchable: true },
    { data: 'type', name: 'type', title: 'COA Type', searchable: true },
    { data: 'description', name: 'description', title: 'Description', searchable: true }
  ];

  function SHowTableCOA(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data, 0);
    Table.showTable();
  }
  function DestroyTableCOA(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data, 0);
    Table.destroyTable();
  }

  function reloadTableSearchCOA(method, tableName, columns, url, data = {}) {
    DestroyTableCOA(method, tableName, columns, url, data);
    SHowTableCOA(method, tableName, columns, url, data);
  }

  // CRUD AND EVENT
  // ------------------------------------------------------------

  //Click COA Search
  $(document).on('click', '.btnsearchcoa', function () {
    modalCOASearch.modal('show');
    currentButton = $(this);
    reloadTableSearchCOA(methodGetCOA, tableCOAName, columnCOA, getDataCOA);
    SHowTableCOA(methodGetCOA, tableCOAName, columnCOA, getDataCOA);
  });

  // Select COA
  $(document).on('click', '.btnselectCOA', function () {
    let COACODE = $(this).data('code');
    let coaName = $(this).data('name');

    let inputElement = currentButton.siblings('.form-control');
    let inputElementName = currentButton.siblings('.coa-name');
    inputElement.val(COACODE);
    inputElementName.val(coaName);
    modalCOASearch.modal('hide');
    DestroyTableCOA(methodGetCOA, tableCOAName, columnCOA, getDataCOA);
  });
});
