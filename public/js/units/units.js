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
    modalTitle.html('Add New Unit Type');
    CodeInput.val('AUTO');
    CodeInput.prop('readonly', true);
    updateMode = false;
  });

  // Submit Form
  $(document).on('submit', '#formProjectType', function (e) {
    e.preventDefault();

    if (validate()) {
      if (!updateMode) {
        var addProjectTypeURL = route('r_unit.store');
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

        var editProjectTypeURL = route('r_unit.update', tondo);
        $(this).attr('action', editProjectTypeURL);
        $(this)[0].submit();
      }
    }
  });

  // Trigger Toast
  checkNotifMessage();

});
