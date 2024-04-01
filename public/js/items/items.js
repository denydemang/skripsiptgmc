import tableInitiator from "../tableinitiator.js";

$(document).ready(function () {
  let modalTitle = $('.modal-title');
  let CodeInput = $('.code');
  let NameInput = $('.name');
  let DescriptionInput = $('.description');
  const modalTypeProject = $('#modal-item');
  let updateMode = false;

  //  Inisiasi Property Untuk Datatable
  // -------------------------------------------------

  var getDataProject = route('admin.getitems');
  const columns = [
    { data: 'action', name: 'actions', title: 'Actions', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'code', title: 'Code', searchable: true },
    { data: 'name', name: 'name', title: 'Name', searchable: true },
    { data: 'unit_code', name: 'unit_code', title: 'unit_code', searchable: false, orderable: false, width: '10%' },
    { data: 'min_stock', name: 'min_stock', title: 'Min Stock', searchable: true },
    { data: 'max_stock', name: 'max_stock', title: 'Max Stock', searchable: true },
    { data: 'category_code', name: 'category_code', title: 'Category', searchable: true },
    { data: 'updated_by', name: 'Updated_By', title: 'Updated By', searchable: true },
    { data: 'created_by', name: 'Created_By', title: 'Created By', searchable: true }
  ];
  const tableName = '.globalTabledata';
  const method = 'post';

  // INISIASI DATATABLE
  // ---------------------------------------------------
  const Table = new tableInitiator(method, tableName, columns, getDataProject);
  Table.showTable();
  
});