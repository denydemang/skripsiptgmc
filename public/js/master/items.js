import tableInitiator from '../tableinitiator.js';
import checkNotifMessage from '../checkNotif.js';
import AjaxRequest from '../ajaxrequest.js';
import { showconfirmdelete } from '../jqueryconfirm.js';

$(document).ready(async function () {
  let modalTitle = $('.modal-title');
  let CodeInput = $('.code');
  let NameInput = $('.name');
  let UnitCodeInput = $('.unit_code');
  let MinStockInput = $('.min_stock');
  let MaxStockInput = $('.max_stock');
  let CategoryCodeInput = $('.category_code');
  const modalTypeProject = $('#modal-popup');
  let updateMode = false;

  $('#unit_code').select2({
    placeholder: '-- Pilih Unit --',
    // allowClear: true,
    // theme: "custom-select2", // Menentukan tema kustom
    dropdownPosition: 'above'
  });

  $.ajax({
    type: 'GET',
    url: route('admin.JSONunit'),
    // data: "id="+id_kabupaten,
    success: function (msg) {
      // console.log(msg);
      $('#unit_code').append(msg);
    }
  });

  $('#category_code').select2({
    placeholder: '-- Pilih Category --',
    // allowClear: true,
    // theme: "custom-select2", // Menentukan tema kustom
    dropdownPosition: 'above'
  });

  $.ajax({
    type: 'GET',
    url: route('admin.JSONcategory'),
    // data: "id="+id_kabupaten,
    success: function (msg) {
      // console.log(msg);
      $('#category_code').append(msg);
    }
  });

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

  // Function Get Edit Data
  // ------------------------------------------------------------
  async function getData(tondo = '') {
    const urlRequest = route('r_item.edit', tondo);
    const method = 'GET';
    const data = {
      id: tondo
    };

    try {
      const ajx = new AjaxRequest(urlRequest, method, data);
      return await ajx.getData();
    } catch (error) {
      console.error('Error:', error);
      return null;
    }
  }

  async function getDataRelasi1(tondo = '') {
    const urlRequest = route('admin.JSONunit', tondo);
    const method = 'GET';
    const data = {
      id: tondo
    };

    try {
      const ajx = new AjaxRequest(urlRequest, method, data);
      return await ajx.getData();
    } catch (error) {
      console.error('Error:', error);
      return null;
    }
  }

  async function getDataRelasi2(tondo = '') {
    const urlRequest = route('admin.JSONcategory', tondo);
    const method = 'GET';
    const data = {
      id: tondo
    };

    try {
      const ajx = new AjaxRequest(urlRequest, method, data);
      return await ajx.getData();
    } catch (error) {
      console.error('Error:', error);
      return null;
    }
  }

  async function formdeleteData(tondo = '') {
    // let token = $('meta[name="csrf-token"]').attr('content');
    const urlRequest = route('r_item.destroy', tondo);
    const method = 'DELETE';
    const data = {
      id: tondo
    };

    try {
      const ajx = new AjaxRequest(urlRequest, method, data);

      return await ajx.formU_DeData();
    } catch (error) {
      console.error('Error:', error);
      return null;
    }
  }

  // FN VALIDATE
  function validate() {
    NameInput.removeClass('is-invalid');
    if (NameInput.val() == '') {
      NameInput.addClass('is-invalid');
      NameInput.focus();
      return false;
    }
    UnitCodeInput.removeClass('is-invalid');
    if (UnitCodeInput.val() == '') {
      UnitCodeInput.addClass('is-invalid');
      UnitCodeInput.focus();
      return false;
    }
    MinStockInput.removeClass('is-invalid');
    if (MinStockInput.val() == '') {
      MinStockInput.addClass('is-invalid');
      MinStockInput.focus();
      return false;
    }
    MaxStockInput.removeClass('is-invalid');
    if (MaxStockInput.val() == '') {
      MaxStockInput.addClass('is-invalid');
      MaxStockInput.focus();
      return false;
    }
    CategoryCodeInput.removeClass('is-invalid');
    if (CategoryCodeInput.val() == '') {
      CategoryCodeInput.addClass('is-invalid');
      CategoryCodeInput.focus();
      return false;
    }
    return true;
  }

  // CLICK ADD Button
  $(document).on('click', '.addbtn', function () {
    modalTypeProject.modal('show');
    modalTitle.html('Add New Item');
    CodeInput.val('');
    CodeInput.prop('readonly', false);
    $('#pesanCode').show();
    updateMode = false;
    $('#unit_code')
      .select2({
        placeholder: '-- Pilih Unit --',
        // allowClear: true,
        // theme: "custom-select2", // Menentukan tema kustom
        dropdownPosition: 'above'
      })
      .val();
    $('#category_code')
      .select2({
        placeholder: '-- Pilih Category --',
        // allowClear: true,
        // theme: "custom-select2", // Menentukan tema kustom
        dropdownPosition: 'above'
      })
      .val();
  });

  //   Clear input
  function clear() {
    NameInput.removeClass('is-invalid');
    UnitCodeInput.removeClass('is-invalid');
    MinStockInput.removeClass('is-invalid');
    MaxStockInput.removeClass('is-invalid');
    CategoryCodeInput.removeClass('is-invalid');
    CodeInput.val('');
    NameInput.val('');
    UnitCodeInput.val('');
    MinStockInput.val('');
    MaxStockInput.val('');
    CategoryCodeInput.val('');
  }

  // Submit Form
  $(document).on('submit', '#formProjectType', function (e) {
    e.preventDefault();

    if (validate()) {
      if (!updateMode) {
        var addProjectTypeURL = route('r_item.store');
        $(this).attr('action', addProjectTypeURL);
        $(this)[0].submit();
      } else {
        var tondo = $('.code').val();
        var inputMethod = $('<input>').attr({
          type: 'hidden',
          name: '_method',
          value: 'PUT'
        });

        var form = $(this);
        form.append(inputMethod);

        var editProjectTypeURL = route('r_item.update', tondo);
        $(this).attr('action', editProjectTypeURL);
        $(this)[0].submit();
      }
    }
  });

  // Delay Edit Data
  function isFetchingData() {
    let text = 'Fetching Data .....';
    CodeInput.val(text);
    NameInput.val(text);
    UnitCodeInput.val(text);
    MinStockInput.val(text);
    MaxStockInput.val(text);
    CategoryCodeInput.val(text);
  }

  // Define populate
  function populateForm(data) {
    if (data || data != null) {
      CodeInput.val(data.code);
      NameInput.val(data.name);
      UnitCodeInput.val(data.unit_code);
      MinStockInput.val(data.min_stock != null ? Math.round(data.min_stock) : 0);
      MaxStockInput.val(data.max_stock != null ? Math.round(data.max_stock) : 0);
      CategoryCodeInput.val(data.category_code);
      $('#unit_code').select2().val(data.unit_code).trigger('change');
      $('#category_code').select2().val(data.category_code).trigger('change');
    }
  }

  // Btn Edit
  $(document).on('click', '.editbtn', async function () {
    let code = $(this).data('code');
    modalTitle.html('Edit Unit');
    updateMode = true;
    modalTypeProject.modal('show');
    $('#pesanCode').hide();
    CodeInput.prop('readonly', true);
    $('.code').val(code);
    isFetchingData();

    const respons = await getData(code);
    populateForm(respons);
  });

  // Function Delete Data
  function deleteData(tondo, name) {
    formdeleteData(tondo);
    window.location.href = route('r_item.index');
  }

  // Click Delete Button
  $(document).on('click', '.deletebtn', function () {
    let code = $(this).data('code');
    showconfirmdelete(code, code, deleteData, 'Code :');
  });

  // Close Modal
  modalTypeProject.on('hidden.bs.modal', function (e) {
    clear();
  });

  // Open Modal
  modalTypeProject.on('shown.bs.modal', function (e) {
    CodeInput.focus();
  });

  // Trigger Toast
  checkNotifMessage();
});
