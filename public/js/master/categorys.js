import tableInitiator from "../tableinitiator.js";
import checkNotifMessage from '../checkNotif.js';
import AjaxRequest from '../ajaxrequest.js';
import { showconfirmdelete } from '../jqueryconfirm.js';

$(document).ready(async function () {
  let modalTitle = $('.modal-title');
  let CodeInput = $('.code');
  let NameInput = $('.name');
  let CoaCodeInput = $('.coa_code');
  const modalTypeProject = $('#modal-popup');
  let updateMode = false;

  $("#coa_code").select2({
    placeholder: "-- Pilih COA --",
    // allowClear: true,
    // theme: "custom-select2", // Menentukan tema kustom
    dropdownPosition: 'above'
});

$.ajax({
    type: 'GET',
    url: route('admin.JSONcoa'),
    // data: "id="+id_kabupaten,
    success: function (msg) {
        // console.log(msg);
        $("#coa_code").append(msg)
    }
});


  //  Inisiasi Property Untuk Datatable
  // -------------------------------------------------

  var getDataProject = route('admin.getcategorys');
  const columns = [
    { data: 'action', name: 'actions', title: 'Actions', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'code', title: 'Code', searchable: true },
    { data: 'name', name: 'name', title: 'Name', searchable: true },
    { data: 'coa_code', name: 'coa_code', title: 'COA Code', searchable: false, orderable: false, width: '10%' },
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
    const urlRequest = route('r_category.edit', tondo);
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

  async function getDataRelasi(tondo = '') {
    const urlRequest = route('admin.JSONcoa', tondo);
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
    const urlRequest = route('r_category.destroy', tondo);
    const method = 'DELETE';
    const data = {
      id: tondo,
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
    CoaCodeInput.removeClass('is-invalid');
    if (CoaCodeInput.val() == '') {
      CoaCodeInput.addClass('is-invalid');
      CoaCodeInput.focus();
      return false;
    }
    return true;
  }

  //   Clear input
  function clear() {
    NameInput.removeClass('is-invalid');
    CoaCodeInput.removeClass('is-invalid');
    CodeInput.val('');
    NameInput.val('');
    CoaCodeInput.val('');
  }

  // CLICK ADD Button
  $(document).on('click', '.addbtn', function () {
    modalTypeProject.modal('show');
    modalTitle.html('Add New Category');
    CodeInput.val('');
    CodeInput.prop('readonly', false);
    $('#pesanCode').show()
    updateMode = false;
    $("#coa_code").select2({
        placeholder: "-- Pilih COA --",
        // allowClear: true,
        // theme: "custom-select2", // Menentukan tema kustom
        dropdownPosition: 'above'
    }).val();

  });


  // Submit Form
  $(document).on('submit', '#formProjectType', function (e) {
    e.preventDefault();

    if (validate()) {
      if (!updateMode) {
        var addProjectTypeURL = route('r_category.store');
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

        var editProjectTypeURL = route('r_category.update', tondo);
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
    CoaCodeInput.val(text);

  }

  // Define populate
  function populateForm(data) {
    if (data || data != null) {
      CodeInput.val(data.code);
      NameInput.val(data.name);
      CoaCodeInput.val(data.coa_code);
      $("#coa_code").select2().val(data.coa_code).trigger("change");

    }
  }

  // Btn Edit
  $(document).on('click', '.editbtn', async function () {
    let code = $(this).data('code');
    modalTitle.html('Edit Category');
    updateMode = true;
    modalTypeProject.modal('show');
    $('#pesanCode').hide()
    CodeInput.prop('readonly', true);
    $('.code').val(code);
    isFetchingData();

    const respons = await getData(code);
    populateForm(respons);
  });

  // Function Delete Data
  function deleteData(tondo, name) {
    formdeleteData(tondo);
    window.location.href = route('r_category.index');
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

  modalTypeProject.on('shown.bs.modal', function (e) {
    CodeInput.focus();
  });

  // Trigger Toast
  checkNotifMessage();

});
