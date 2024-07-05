import tableInitiator from '../tableinitiator.js';
import checkNotifMessage from '../checkNotif.js';
import { checkNotifForbidden } from '../checkNotifForbidden.js';
import AjaxRequest from '../ajaxrequest.js';
import { showconfirmdelete } from '../jqueryconfirm.js';

$(document).ready(function () {
  let modalTitle = $('.modal-title');
  let NameInput = $('.name');
  const modalTypeProject = $('#modal-popup');
  let updateMode = false;

  //   Form DELETE Hidden
  $('.delformHidden').hide();

  //  Inisiasi Property Untuk Datatable
  // -------------------------------------------------
  const fetchData = async () => {
    var getDataProject = route('admin.getroles');
    const columns = [
      // { data: 'action', name: 'actions', title: 'Actions', searchable: false, orderable: false, width: '10%' },
      { data: 'name', name: 'name', title: 'Name', searchable: true },
      { data: 'active_status', name: 'active_status', title: 'Active Status', searchable: true }
      // { data: 'updated_by', name: 'Updated_By', title: 'Updated By', searchable: true },
      // { data: 'created_by', name: 'Created_By', title: 'Created By', searchable: true }
    ];
    const tableName = '.globalTabledata';
    const method = 'post';

    // INISIASI DATATABLE
    // ---------------------------------------------------
    const Table = new tableInitiator(method, tableName, columns, getDataProject);
    Table.showTable();
  };

  fetchData();

  // Function Get Edit Data
  // ------------------------------------------------------------
  async function getData(tondo = '') {
    const urlRequest = route('r_role.edit', tondo);
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
    const urlRequest = route('r_role.destroy', tondo);
    $.ajax({
      url: urlRequest,
      type: 'DELETE',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {
        if (response.code == 200) {
          checkNotifForbidden(response.code, response.msg);

          $('.globalTabledata').DataTable().destroy();
          fetchData();
        } else {
          checkNotifForbidden(response.code, response.msg);
        }
      }
    });
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

  //   Clear input
  function clear() {
    NameInput.removeClass('is-invalid');
    NameInput.val('');
  }

  // CLICK ADD Button
  $(document).on('click', '.addbtn', function () {
    modalTypeProject.modal('show');
    modalTitle.html('Add New Role');
    updateMode = false;
  });

  // Submit Form
  $(document).on('submit', '#formProjectType', function (e) {
    e.preventDefault();

    if (validate()) {
      if (!updateMode) {
        var addProjectTypeURL = route('r_role.store');
        $(this).attr('action', addProjectTypeURL);
        $(this)[0].submit();
      } else {
        var tondo = $('.id').val();
        var inputMethod = $('<input>').attr({
          type: 'hidden',
          name: '_method',
          value: 'PUT'
        });

        var form = $(this);
        form.append(inputMethod);

        var editProjectTypeURL = route('r_role.update', tondo);
        $(this).attr('action', editProjectTypeURL);
        $(this)[0].submit();
      }
    }
  });

  // Delay Edit Data
  function isFetchingData() {
    let text = 'Fetching Data .....';
    NameInput.val(text);
  }

  // Define populate
  function populateForm(data) {
    if (data || data != null) {
      NameInput.val(data.name);
    }
  }

  // Btn Edit
  $(document).on('click', '.editbtn', async function () {
    let id = $(this).data('id');
    modalTitle.html('Edit Role');
    updateMode = true;
    modalTypeProject.modal('show');
    // idInput.prop('readonly', true);
    $('.id').val(id);
    isFetchingData();

    const respons = await getData(id);
    populateForm(respons);
  });

  // Function Delete Data
  function deleteData(tondo, name) {
    formdeleteData(tondo);
  }

  // Click Delete Button
  $(document).on('click', '.deletebtn', function () {
    let id = $(this).data('id');
    showconfirmdelete(id, id, deleteData, 'Id :');
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
