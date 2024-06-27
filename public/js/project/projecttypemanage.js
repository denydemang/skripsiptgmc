import AjaxRequest from '../ajaxrequest.js';
import { showwarning, showerror } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import validateInput from '../validateInput.js';
import { formatRupiah1 } from '../rupiahformatter.js';

$(document).ready(function () {
  const modalItemSearch = $('#modalItemSearch');
  const modalUpahSearch = $('#modalUpahSearch');
  const datamateriallist = $('.datamateriallist');
  const dataupahlist = $('.dataupahlist');

  // Html Input
  const inputcode = $('.inputcode');
  const inputypename = $('.inputypename');
  const inputdescription = $('.inputdescription');
  // end html input

  // const updateMode = false;
  const updateMode = route().current() == 'admin.editprojecttypeview';

  // Tampungan Parsing hasil data
  let materiallist = [];
  let upahlist = [];

  let dataInput = [];

  let PostData = {
    code: '',
    typename: '',
    description: '',
    detaila: [],
    detailb: []
  };
  let project_details_prototype = {
    item_code: '',
    unit_code: ''
  };

  let project_detail_b_prototype = {
    upah_code: '',
    unit: '',
    price: 0.0
  };

  let tampungMaterial = [];
  let tampungUpah = [];

  if (updateMode) {
    prepareEdit();
  }
  // ----------------------------------------------------------

  // Function and procedures
  // -----------------------------------------------------

  async function prepareEdit() {
    materiallist = datamateriallist.data('materiallist');
    upahlist = dataupahlist.data('upahlist');
    materiallist.forEach((item) => {
      let dataMaterial = {
        code: item.item_code,
        unit: item.unit_code,
        name: item.item_name
      };
      populateMaterial(dataMaterial);
    });
    upahlist.forEach((item) => {
      let dataUpah = {
        code: item.upah_code,
        job: item.job,
        unit: item.unit,
        description: '',
        price: parseFloat(item.price)
      };
      populateUpah(dataUpah);
    });
  }

  function customValidation() {
    if (inputypename.val() == '') {
      inputypename.focus();
      showwarning('Please Input Type Name!');
      return false;
    }

    if (inputdescription.val() == '') {
      inputdescription.focus();
      showwarning('Please Input Description!');
      return false;
    }

    if (tampungMaterial.length <= 0) {
      showwarning('Please Input Material List!');
      return false;
    }
    if (tampungUpah.length <= 0) {
      showwarning('Please Input Upah List!');
      return false;
    }

    return true;
  }

  function checkMaterialExist(code) {
    // Bernilai true jika ada item
    return tampungMaterial.some((item) => item.code === code);
  }

  function checkUpahExist(code) {
    // Bernilai true jika ada item
    return tampungUpah.some((item) => item.code === code);
  }

  function createHmtlTbodyMaterial() {
    const tbody = $('.tablematerial tbody');

    let html = ``;

    tampungMaterial.forEach((item) => {
      html += `
      <tr class="row">
        <td class="col-2"
            style="white-space:normal;word-wrap: break-word;font-size:13px">
            ${item.code}</td>
        <td class="col-3"
            style="white-space:normal;word-wrap: break-word;font-size:13px">
            ${item.name}</td>
        <td class="col-1"
            style="white-space:normal;word-wrap: break-word;font-size:13px">
            ${item.unit}</td>
        <td class="col-1"
            style="white-space:normal;word-wrap: break-word;font-size:13px">
            <button data-code="${item.code}"
                class="btn btn-danger btndeletematerial btn-sm">X</button>
        </td>
    </tr>
      `;
    });

    tbody.html(html);
  }

  function createHmtlTbodyUpah() {
    const tbody = $('.tableupah tbody');

    let html = ``;

    tampungUpah.forEach((item) => {
      html += `
     <tr class="row">
        <td class="col-2 text-left"
            style="white-space:normal;word-wrap: break-word;font-size: 12px">
            ${item.code}</td>
        <td
            class="col-3 text-left"style="white-space:normal;word-wrap: break-word;font-size: 12px">
            ${item.job}</td>
        <td
            class="col-1 text-left"style="white-space:normal;word-wrap: break-word;font-size: 12px">
            ${item.unit}</td>
        <td
            class="col-2 text-left"style="white-space:normal;word-wrap: break-word;font-size: 12px">
           ${formatRupiah1(item.price)}</td>
        <td class="col-1"><button class="btn btn-danger btn-sm btndeleteupah"
                data-code="${item.code}">X</button></td>
    </tr>
      `;
    });

    tbody.html(html);
  }

  function deleteMaterialFromList(Code) {
    const editedMaterial = tampungMaterial.filter((item) => {
      return item.code != Code;
    });

    tampungMaterial = [...editedMaterial];
    createHmtlTbodyMaterial();
  }

  function deleteUpahFromList(Code) {
    const editedUpah = tampungUpah.filter((item) => {
      return item.code != Code;
    });

    tampungUpah = [...editedUpah];
    createHmtlTbodyUpah();
  }

  function populateMaterial(data = {}) {
    if (checkMaterialExist(data.code)) {
      showwarning(data.code + ' Already Added In The List !');
    } else {
      // Direct Push
      tampungMaterial.push(data);
    }

    createHmtlTbodyMaterial();
  }

  function populateUpah(data = {}) {
    if (checkUpahExist(data.code)) {
      showwarning(data.code + ' Already Added In The List !');
    } else {
      // Direct Push
      tampungUpah.push(data);
    }
    createHmtlTbodyUpah();
  }

  function populatePostData() {
    PostData.code = inputcode.val().trim();
    PostData.typename = inputypename.val().trim();
    PostData.description = inputdescription.val().trim();
    PostData.detaila = [];
    PostData.detailb = [];
    tampungMaterial.forEach((item) => {
      project_details_prototype.item_code = item.code;
      project_details_prototype.unit_code = item.unit;

      PostData.detaila.push({ ...project_details_prototype });
    });

    tampungUpah.forEach((item) => {
      project_detail_b_prototype.upah_code = item.code;
      project_detail_b_prototype.unit = item.unit;
      project_detail_b_prototype.price = item.price;

      PostData.detailb.push({ ...project_detail_b_prototype });
    });
  }
  async function postAjax() {
    let urlRequest = '';

    if (updateMode) {
      let code = inputcode.val();
      urlRequest = route('admin.updateDataProjectType', code);
    } else {
      urlRequest = route('admin.addDataProjectType');
    }
    const method = 'POST';
    let response = '';
    let formData = createFormData();
    try {
      const ajx = new AjaxRequest(urlRequest, method, formData);
      response = await ajx.getData();
      return response;
    } catch (error) {
      showerror(error);
      return false;
    }
  }
  function createFormData() {
    var formData = new FormData();

    formData.append('code', PostData.code);
    formData.append('description', PostData.description);
    formData.append('typename', PostData.typename);
    formData.append('detaila', JSON.stringify(PostData.detaila));
    formData.append('detailb', JSON.stringify(PostData.detailb));
    return formData;
  }

  // -----------------------------------------------------------------

  // Crud And Events
  // ------------------------------------------------------------

  // Ketika Sesudah Memilih Item Di Modal Item Search
  $(document).on('click', '.selectitembtn', function () {
    let code = $(this).data('code');
    let unit = $(this).data('unit');
    let name = $(this).data('name');
    let dataMaterial = {};

    dataMaterial = {
      code: code,
      unit: unit,
      name: name
    };

    populateMaterial(dataMaterial);

    // modalItemSearch.modal('hide');
  });

  // Delete Material From List
  $(document).on('click', '.btndeletematerial', function () {
    let code = $(this).data('code');
    deleteMaterialFromList(code);
  });

  // Ketika Sesudah Memilih Upah Di Modal Upah Search
  $(document).on('click', '.selectupahbtn', function () {
    let code = $(this).data('code');
    let job = $(this).data('job');
    let description = $(this).data('description');
    let unit = $(this).data('unit');
    let price = parseFloat($(this).data('price'));

    let dataUpah = {
      code: code,
      job: job,
      unit: unit,
      description: description,
      price: price
    };

    populateUpah(dataUpah);

    // modalUpahSearch.modal('hide');
  });
  // Delete Upah From List
  $(document).on('click', '.btndeleteupah', function () {
    let code = $(this).data('code');
    deleteUpahFromList(code);
  });

  // Submit Button
  $(document).on('click', '.submitbtn', async function () {
    if (validateInput(dataInput, customValidation)) {
      $(this).find('span').html('Loading...');
      $(this).prop('disabled', true);
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.projecttype');
      if (response) {
        window.location.href = urlRedirect;
      } else {
        if (updateMode) {
          $(this).find('span').html('Update');
        } else {
          $(this).find('span').html('Save');
        }
        $(this).prop('disabled', false);
      }
    }
  });

  // Trigger Notif Toast
  checkNotifMessage();
});
