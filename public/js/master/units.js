import tableInitiator from "../tableinitiator.js";
import checkNotifMessage from '../checkNotif.js';
import AjaxRequest from '../ajaxrequest.js';
import { showconfirmdelete } from '../jqueryconfirm.js';

$(document).ready(function () {
  let modalTitle = $('.modal-title');
  let CodeInput = $('.code');
  let NameInput = $('.name');
  const modalTypeProject = $('#modal-popup');
  let updateMode = false;

  //  Inisiasi Property Untuk Datatable
  // -------------------------------------------------

  var getDataProject = route('admin.getUnits');
  const columns = [
    { data: 'action', name: 'actions', title: 'Actions', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'code', title: 'Code', searchable: true },
    { data: 'name', name: 'name', title: 'Name', searchable: true },
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
    const urlRequest = route('r_unit.edit', tondo);
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
    const urlRequest = route('r_unit.destroy', tondo);
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
    return true;
  }

  // CLICK ADD Button
  $(document).on('click', '.addbtn', function () {
    modalTypeProject.modal('show');
    modalTitle.html('Add New Unit');
    CodeInput.val('');
    CodeInput.prop('readonly', false);
    $('#pesanCode').show()
    updateMode = false;
  });

//   Clear input
  function clear() {
    NameInput.removeClass('is-invalid');
    CodeInput.val('');
    NameInput.val('');
  }

  // Submit Form
  $(document).on('submit', '#formProjectType', function (e) {
    e.preventDefault();

    if (validate()) {
      if (!updateMode) {
        var addProjectTypeURL = route('r_unit.store');
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

        var editProjectTypeURL = route('r_unit.update', tondo);
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

  }

  // Define populate
  function populateForm(data) {
    if (data || data != null) {
      CodeInput.val(data.code);
      NameInput.val(data.name);
    }
  }

  // Btn Edit
  $(document).on('click', '.editbtn', async function () {
    let code = $(this).data('code');
    modalTitle.html('Edit Unit');
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
    window.location.href = route('r_unit.index');
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
    NameInput.focus();
  });

  // Trigger Toast
  checkNotifMessage();

});
