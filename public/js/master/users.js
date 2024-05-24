import tableInitiator from "../tableinitiator.js";
import checkNotifMessage from '../checkNotif.js';
import AjaxRequest from '../ajaxrequest.js';
import { showconfirmdelete } from '../jqueryconfirm.js';


$(document).ready(async function () {
  let modalTitle = $('.modal-title');
  let UsernameInput = $('.username');
  let NameInput = $('.name');
  let PasswordInput = $('.password');
  let RoleInput = $('.role');
  const modalTypeProject = $('#modal-popup');
  let updateMode = false;

  const pilih = $('<option value="" id="pilih" selected="">-- Pilih Role --</option>')


  try {
    // Panggil fungsi getDataRelasi dengan await untuk menunggu hasilnya
    const result = await getDataRelasi();

    // Lakukan sesuatu dengan hasilnya
    console.log(result);
    if (result.length != 0) {
        const selectElement = $('#role');
        selectElement.append(pilih);
        result.forEach(item => {
            const option = $('<option></option>');
            option.attr('value', item.id); // Atur nilai option sesuai dengan data
            option.text(`${item.name}`); // Atur teks option sesuai dengan data
            selectElement.append(option); // Masukkan option ke dalam elemen select
        });

    }
} catch (error) {
    // Tangani kesalahan jika terjadi
    console.error('Error:', error);
}

  //  Inisiasi Property Untuk Datatable
  // -------------------------------------------------

  var getDataProject = route('admin.getDataUsers');
  const columns = [
    { data: 'action', name: 'actions', title: 'Actions', searchable: false, orderable: false, width: '10%' },
    { data: 'username', name: 'username', title: 'Username', searchable: true },
    { data: 'name', name: 'name', title: 'Name', searchable: true },
    { data: 'role_name', name: 'role_name', title: 'ID Role', searchable: false },
    { data: 'active_status', name: 'active_status', title: 'Active Status', searchable: true },
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
    const urlRequest = route('admin.editUsers', tondo);
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

  async function getDataRelasi(tondo = '') {
    const urlRequest = route('admin.JSONrole', tondo);
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

  // FN VALIDATE
  function validate() {
    UsernameInput.removeClass('is-invalid');
    NameInput.removeClass('is-invalid');
    // PasswordInput.removeClass('is-invalid');
    RoleInput.removeClass('is-invalid');

    if (UsernameInput.val() == '') {
      UsernameInput.addClass('is-invalid');
      UsernameInput.focus();
      return false;
    }
    if (NameInput.val() == '') {
      NameInput.addClass('is-invalid');
      NameInput.focus();
      return false;
    }

    const modalTitle = $('.modal-title').text()
    if (modalTitle == 'Add New User') {
      if (PasswordInput.val() == '') {
        PasswordInput.addClass('is-invalid');
        PasswordInput.focus();
        return false;
      }
    }

    if (RoleInput.val() == '') {
      RoleInput.addClass('is-invalid');
      RoleInput.focus();
      return false;
    }
    return true;
  }

  //   Clear input
  function clear() {
    UsernameInput.removeClass('is-invalid');
    NameInput.removeClass('is-invalid');
    PasswordInput.removeClass('is-invalid');
    RoleInput.removeClass('is-invalid');
    UsernameInput.val('');
    NameInput.val('');
    PasswordInput.val('');
    RoleInput.val('');
  }

  // CLICK ADD Button
  $(document).on('click', '.addbtn', function () {
    modalTypeProject.modal('show');
    modalTitle.html('Add New User');
    updateMode = false;
  });

  // Submit Form
  $(document).on('submit', '#formProjectType', function (e) {
    e.preventDefault();

    if (validate()) {
      if (!updateMode) {
        var addProjectTypeURL = route('admin.addDataUsers');
        $(this).attr('action', addProjectTypeURL);
        $(this)[0].submit();
      } else {
        var editProjectTypeURL = route('admin.updateDataUser');
        $(this).attr('action', editProjectTypeURL);
        $(this)[0].submit();
      }
    }
  });

  // Delay Edit Data
  function isFetchingData() {
    let text = 'Fetching Data .....';
    UsernameInput.val(text);
    NameInput.val(text);
    // PasswordInput.val(text);
    RoleInput.val(text);

  }

  // Define populate
  function populateForm(data) {
    if (data || data != null) {
      UsernameInput.val(data.username);
      NameInput.val(data.name);
    //   PasswordInput.val(data.password);
    //   console.log(data.id_role);
      RoleInput.val(data.id_role);
    }
  }

  // Btn Edit
  $(document).on('click', '.editbtn', async function () {
    let Username = $(this).data('username');
    modalTitle.html('Edit Users');
    updateMode = true;
    modalTypeProject.modal('show');
    UsernameInput.prop('readonly', true);
    isFetchingData();

    const respons = await getData(Username);
    populateForm(respons);
  });

  // Function Delete Data
  function deleteData(tondo, name) {
    const urlRequest = route('admin.deleteDataUser', tondo);
    window.location.href = urlRequest;
  }

  // Click Delete Button
  $(document).on('click', '.deletebtn', function () {
    let Username = $(this).data('username');
    showconfirmdelete(Username, Username, deleteData, 'User :');
  });

  // Close Modal
  modalTypeProject.on('hidden.bs.modal', function (e) {
    clear();
  });

  // Open Modal
  modalTypeProject.on('shown.bs.modal', function (e) {
    UsernameInput.focus();
  });


    // Trigger Toast
    checkNotifMessage();

});
