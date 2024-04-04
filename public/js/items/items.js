import tableInitiator from "../tableinitiator.js";
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

  const pilih = $('<option value="" id="pilih" selected="">-- Pilih Unit --</option>')


  try {
    // Panggil fungsi getDataRelasi dengan await untuk menunggu hasilnya
    const result = await getDataRelasi1();

    // Lakukan sesuatu dengan hasilnya
    console.log(result);
    if (result.length != 0) {
        const selectElement = $('#unit_code');
        selectElement.append(pilih);
        result.forEach(item => {
            const option = $('<option></option>');
            option.attr('value', item.code); // Atur nilai option sesuai dengan data
            option.text(`( ${item.code} ) - ${item.name}`); // Atur teks option sesuai dengan data
            selectElement.append(option); // Masukkan option ke dalam elemen select
        });

    }
} catch (error) {
    // Tangani kesalahan jika terjadi
    console.error('Error:', error);
}

    const pilih2 = $('<option value="" id="pilih2" selected="">-- Pilih Category --</option>')


  try {
    // Panggil fungsi getDataRelasi dengan await untuk menunggu hasilnya
    const result2 = await getDataRelasi2();

    // Lakukan sesuatu dengan hasilnya
    console.log(result2);
    if (result2.length != 0) {
        const selectElement = $('#category_code');
        selectElement.append(pilih2);
        result2.forEach(item => {
            const option = $('<option></option>');
            option.attr('value', item.code); // Atur nilai option sesuai dengan data
            option.text(`( ${item.code} ) - ${item.name} - ( ${item.coa_code} )`); // Atur teks option sesuai dengan data
            selectElement.append(option); // Masukkan option ke dalam elemen select
        });

    }
} catch (error) {
    // Tangani kesalahan jika terjadi
    console.error('Error:', error);
}



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
    modalTitle.html('Add New Unit');
    CodeInput.val('AUTO');
    CodeInput.prop('readonly', true);
    updateMode = false;
  });

//   Clear input
  function clear() {
    NameInput.removeClass('is-invalid');
    UnitCodeInput.removeClass('is-invalid');
    MinStockInput.removeClass('is-invalid');
    MaxStockInput.removeClass('is-invalid');
    CategoryCodeInput.removeClass('is-invalid');
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
      MinStockInput.val(data.min_stock);
      MaxStockInput.val(data.max_stock);
      CategoryCodeInput.val(data.category_code);
    }
  }

  // Btn Edit
  $(document).on('click', '.editbtn', async function () {
    let code = $(this).data('code');
    modalTitle.html('Edit Unit');
    updateMode = true;
    modalTypeProject.modal('show');
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
    NameInput.focus();
  });

  // Trigger Toast
  checkNotifMessage();

});
