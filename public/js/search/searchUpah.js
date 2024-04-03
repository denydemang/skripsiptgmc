import tableInitiator from '../tableinitiator.js';
$(document).ready(function () {
  const modalUpahSearch = $('#modalUpahSearch');

  // INISIASI DATATABLE Upah Modal Search
  // ----------------------------------------------------------------
  var getDataItem = route('admin.getTableUpahSearch');
  const tableUpahName = '.upahsearchtable';
  const methodGetUpah = 'get';
  const columnUpah = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false },
    { data: 'code', name: 'Code', title: 'Upah Code', searchable: true },
    { data: 'job', name: 'Job', title: 'Job', searchable: true },
    { data: 'description', name: 'description', title: 'description', searchable: false },
    { data: 'unit', name: 'unit', title: 'Unit/Satuan', searchable: false },
    { data: 'price', name: 'price', title: 'Price', searchable: false }
  ];

  function showTableUpahSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.showTable();
  }
  function destroyTableUpahSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.destroyTable();
  }

  function reloadTableUpahSearch(method, tableName, columns, url, data = {}) {
    destroyTableUpahSearch(method, tableName, columns, url, data);
    showTableUpahSearch(method, tableName, columns, url, data);
  }

  // CRUD AND EVENT
  // ------------------------------------------------------------

  //Click Upah Search
  $(document).on('click', '.btn-add-upah', function () {
    modalUpahSearch.modal('show');
    reloadTableUpahSearch(methodGetUpah, tableUpahName, columnUpah, getDataItem);
  });

  // // Select COA
  // $(document).on('click', '.btnselectCOA', function () {
  //   let COACODE = $(this).data('code');

  //   let inputElement = currentButton.siblings('.form-control');
  //   inputElement.val(COACODE);
  //   modalUpahSearch.modal('hide');
  // });
});
