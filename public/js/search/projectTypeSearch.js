import tableInitiator from '../tableinitiator.js';
$(document).ready(function () {
  localStorage.clear();
  const modalProjectTypeSearch = $('#modalProjectTypeSearch');
  const projectypecode = $('.projecttypecode');
  const projecttypename = $('.projecttypename');

  // INISIASI DATATABLE Project Type Modal Search
  // ----------------------------------------------------------------
  var getDataProjectType = route('admin.getSearchtable');
  const tableProject = '.projecttypesearchtable';
  const methodGetProjectType = 'post';
  const columnsProjectType = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'Code', title: 'Code', searchable: true },
    { data: 'name', name: 'Name', title: 'Name', searchable: true },
    { data: 'description', name: 'Description', title: 'Description', searchable: true },
    { data: 'updated_by', name: 'Updated_By', title: 'Updated_By', searchable: true },
    { data: 'created_by', name: 'Created_By', title: 'Created_By', searchable: true }
  ];
  const data = {
    search: true
  };
  function ShowTableProjectTypeSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.showTable();
  }
  function DestroyProjectTypeSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.destroyTable();
  }

  function reloadTableProjectTypeSearch(method, tableName, columns, url, data = {}) {
    DestroyProjectTypeSearch(method, tableName, columns, url, data);
    ShowTableProjectTypeSearch(method, tableName, columns, url, data);
  }

  // CRUD AND EVENT
  // ------------------------------------------------------------

  //Click Customer Search
  $(document).on('click', '.btnsearchtypeprojectcode', function () {
    modalProjectTypeSearch.modal('show');
    reloadTableProjectTypeSearch(methodGetProjectType, tableProject, columnsProjectType, getDataProjectType, data);
  });

  //Select Customer
  $(document).on('click', '.selectprojecttype', function () {
    projectypecode.val($(this).data('code'));
    projecttypename.val($(this).data('name'));
    modalProjectTypeSearch.modal('hide');

    // Populate in localstorage
    localStorage.setItem('projecttypeName', projecttypename.val());
    localStorage.setItem('projecttypeCode', projectypecode.val());
  });
});
