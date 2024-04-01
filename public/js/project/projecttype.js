import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { showconfirmdelete } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
$(document).ready(function () {
  let modalTitle = $('.modal-title');
  let CodeInput = $('.code');
  let NameInput = $('.name');
  let DescriptionInput = $('.description');
  const modalTypeProject = $('#modal-projecttype');
  let updateMode = false;

  //  Inisiasi Property Untuk Datatable
  // -------------------------------------------------
  var getDataTypeProjectURL = route('admin.getDataProjectType');
  const columns = [
    { data: 'action', name: 'actions', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'Code', searchable: true },
    { data: 'name', name: 'Name', searchable: true },
    { data: 'description', name: 'Description', searchable: true },
    { data: 'updated_by', name: 'Updated_By', searchable: true },
    { data: 'created_by', name: 'Created_By', searchable: true }
  ];
  const tableName = '.proyecttype-table';
  const method = 'post';

  // INISIASI DATATABLE
  // ---------------------------------------------------
  const Table = new tableInitiator(method, tableName, columns, getDataTypeProjectURL);
  Table.showTable();

  // Function
  // ------------------------------------------------------------
  async function getData(code = '') {
    const urlRequest = route('admin.getDataTypeProjectRaw', code);
    const method = 'GET';
    const data = {
      parameter1: 'test'
    };

    try {
      const ajx = new AjaxRequest(urlRequest, method, data);
      return await ajx.getData();
    } catch (error) {
      console.error('Error:', error);
      return null;
    }
  }

  function deleteData(code, name) {
    const urlRequest = route('admin.deleteDataProjectType', code);
    window.location.href = urlRequest;
  }

  function isFetchingData() {
    let text = 'Fetching Data .....';
    CodeInput.val(text);
    NameInput.val(text);
    DescriptionInput.val(text);
  }
  function populateForm(data) {
    if (data || data != null) {
      CodeInput.val(data.code);
      NameInput.val(data.name);
      DescriptionInput.val(data.description);
    }
  }
  function validate() {
    NameInput.removeClass('is-invalid');
    DescriptionInput.removeClass('is-invalid');

    if (NameInput.val() == '') {
      NameInput.addClass('is-invalid');
      NameInput.focus();
      return false;
    }
    if (DescriptionInput.val() == '') {
      DescriptionInput.addClass('is-invalid');
      DescriptionInput.focus();
      return false;
    }
    return true;
  }

  function clear() {
    NameInput.removeClass('is-invalid');
    DescriptionInput.removeClass('is-invalid');
    NameInput.val('');
    DescriptionInput.val('');
  }

  // CRUD AND EVENT
  // ------------------------------------------------------------

  // Click Edit Button
  $(document).on('click', '.editbtn', async function () {
    let Code = $(this).data('code');
    modalTitle.html('Edit Project Type');
    updateMode = true;
    modalTypeProject.modal('show');
    CodeInput.prop('readonly', true);
    isFetchingData();

    const respons = await getData(Code);
    populateForm(respons);
  });

  // Click Delete Button
  $(document).on('click', '.deletebtn', function () {
    let Code = $(this).data('code');
    showconfirmdelete(Code, Code, deleteData, 'Project Type :');
  });

  // CLICK ADD Button
  $(document).on('click', '.addbtn', function () {
    modalTypeProject.modal('show');
    modalTitle.html('Add New Project Type');
    CodeInput.val('AUTO');
    CodeInput.prop('readonly', true);
    updateMode = false;
  });

  // Submit Form
  $(document).on('submit', '#formProjectType', function (e) {
    e.preventDefault();

    if (validate()) {
      if (!updateMode) {
        var addProjectTypeURL = route('admin.addDataProjectType');
        $(this).attr('action', addProjectTypeURL);
        $(this)[0].submit();
      } else {
        var editProjectTypeURL = route('admin.updateDataProjectType');
        $(this).attr('action', editProjectTypeURL);
        $(this)[0].submit();
      }
    }
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
