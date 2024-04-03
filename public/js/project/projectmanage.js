import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { showwarning } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import validateInput from '../validateInput.js';
import initiatedtp from '../datepickerinitiator.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import managedate from '../managedate.js';

$(document).ready(function () {
  const Date = new managedate();
  const modalItemSearch = $('#modalItemSearch');
  const modalUpahSearch = $('#modalUpahSearch');
  const inputcode = $('.inputcode');
  const dtptransdate = $('#dtptransdate');
  const inputpic = $('.inputpic');
  const inputtransdate = $('.inputtransdate');
  const inputlocation = $('.inputlocation');
  const inputduration = $('.inputduration');
  const inputbudget = $('.inputbudget');
  const inputname = $('.inputname');
  const inputcoaekspense = $('.inputcoaekspense');
  const inputcoapayable = $('.inputcoapayable');
  const inputdescription = $('.inputdescription');

  let dataInput = ['.inputpic', '.inputlocation', '.inputduration', '.inputbudget', '.inputname'];

  let PostData = {
    code: '',
    name: '',
    transaction_date: '',
    project_type_code: '',
    customer_code: '',
    location: '',
    budget: 0,
    project_document: '',
    description: '',
    coa_expense: '',
    coa_payable: '',
    pic: '',
    duration_days: 0,
    project_details: [],
    project_detail_b: []
  };
  let project_details_prototype = {
    project_code: '',
    item_code: '',
    unit_code: '',
    qty: 0.0
  };

  let project_detail_b_prototype = {
    project_code: '',
    upah_code: '',
    unit: '',
    qty: 0.0,
    price: 0.0,
    total: 0.0
  };

  let tampungMaterial = [];
  let tampungUpah = [];

  // inisiasi datapicker dan input
  // ------------------------------------------------------
  initiatedtp(dtptransdate);
  inputtransdate.val(Date.getNowDate());
  inputbudget.val(formatRupiah1(0));

  //  ---------------------------------------------------

  // Function and procedures
  // -----------------------------------------------------

  function customValidation() {
    const customerCode = localStorage.getItem('customerCode');
    const projecttypeCode = localStorage.getItem('projecttypeCode');
    if (inputtransdate.val() == '') {
      inputtransdate.focus();
      showwarning('Transaction Date Cannot Be Empty');
      return false;
    }
    if (customerCode == '' || customerCode == null) {
      $('.customerCode').focus();
      // console.log(localStorage.getItem('customerCode'));
      showwarning('Customer Code Cannot Be Empty');
      return false;
    }
    if (projecttypeCode == '' || projecttypeCode == null) {
      $('.projecttypecode').focus();
      showwarning('ProjectType Code Cannot Be Empty');
      return false;
    }
    if (inputcoaekspense.val() == '' || inputcoaekspense.val() == null) {
      inputcoaekspense.focus();
      showwarning('Coa Expense Cannot Be Empty');
      return false;
    }
    if (inputcoapayable.val() == '' || inputcoapayable.val() == null) {
      inputcoapayable.focus();
      showwarning('Coa Payable Cannot Be Empty');
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
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size: 10px">${item.code}</td>
        <td class="col-3" style="white-space:normal;word-wrap: break-word;font-size:10px">${item.name}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size: 10px">${item.unit}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size: 10px"><input
            style="width:100%" type="number" min="1" class="custom-input inputqtymaterial" data-stocks="${item.stocks}" data-code="${item.code}" value="${item.qty}">
        </td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size: 10px"><button data-code="${item.code}" class="btn btn-danger btndeletematerial btn-sm">X</button></td>
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
        <td class="col-2 text-left" style="white-space:normal;word-wrap: break-word;font-size: 10px">${item.code}</td>
        <td class="col-2 text-left"style="white-space:normal;word-wrap: break-word;font-size: 10px">${item.job}</td>
        <td class="col-1 text-left"style="white-space:normal;word-wrap: break-word;font-size: 10px">${item.unit}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size: 9px"><input
            style="width:100%" type="number" class="custom-input inputqtyupah" data-code="${item.code}" value="${item.qty}">
        </td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size: 10px">${formatRupiah1(item.price)}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size: 10px"><input
            style="width:100%" type="text" class="custom-input" readonly value="${formatRupiah1(item.total)}"></td>
        <td class="col-1"><button class="btn btn-danger btn-sm btndeleteupah" data-code="${item.code}" >X</button></td>
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
      // incrementqty + 1
      const editedMaterial = tampungMaterial.map((item) => {
        if (item.code === data.code) {
          return { ...item, qty: parseFloat(item.qty) + parseFloat(data.qty) };
        } else {
          return item;
        }
      });
      tampungMaterial = [...editedMaterial];
    } else {
      // Direct Push
      tampungMaterial.push(data);
    }

    createHmtlTbodyMaterial();
  }

  function populateUpah(data = {}) {
    if (checkUpahExist(data.code)) {
      // incrementqty + 1
      const editedUpah = tampungUpah.map((item) => {
        if (item.code === data.code) {
          let qty = parseFloat(item.qty) + parseFloat(data.qty);
          let total = qty * item.price;
          return { ...item, qty: qty, total: total };
        } else {
          return item;
        }
      });
      tampungUpah = [...editedUpah];
    } else {
      // Direct Push
      tampungUpah.push(data);
    }
    createHmtlTbodyUpah();
  }

  function calculateMaterial(code, qty) {
    const editedMaterial = tampungMaterial.map((item) => {
      if (item.code === code) {
        return { ...item, qty: qty };
      } else {
        return item;
      }
    });
    tampungMaterial = [...editedMaterial];

    createHmtlTbodyMaterial();
  }

  function calculateUpah(code, qtyupah) {
    const editedUpah = tampungUpah.map((item) => {
      if (item.code === code) {
        const qty = qtyupah;
        const total = qty * item.price;
        return { ...item, qty: qty, total: total };
      } else {
        return item;
      }
    });
    tampungUpah = [...editedUpah];

    createHmtlTbodyUpah();
  }

  function populatePostData() {
    PostData.code = '';
    PostData.budget = parseToNominal(inputbudget.val());
    PostData.coa_expense = inputcoaekspense.val();
    PostData.coa_payable = inputcoapayable.val();
    PostData.customer_code = localStorage.getItem('customerCode');
    PostData.description = inputdescription.val();
    PostData.duration_days = parseInt(inputduration.val());
    PostData.location = inputlocation.val();
    PostData.name = inputname.val();
    PostData.pic = inputpic.val();
    PostData.project_type_code = localStorage.getItem('projecttypeCode');
    PostData.transaction_date = moment(inputtransdate.val()).format('YYYY-MM-DD');

    tampungMaterial.forEach((item) => {
      project_details_prototype.item_code = item.code;
      project_details_prototype.project_code = '';
      project_details_prototype.qty = item.qty;
      project_details_prototype.unit_code = item.unit;

      PostData.project_details = [];
      PostData.project_details.push({ ...project_details_prototype });
    });

    tampungUpah.forEach((item) => {
      project_detail_b_prototype.upah_code = item.code;
      project_detail_b_prototype.unit = item.unit;
      project_detail_b_prototype.total = item.total;
      project_detail_b_prototype.qty = item.qty;
      project_detail_b_prototype.price = item.price;
      project_detail_b_prototype.project_code = '';

      PostData.project_detail_b = [];
      PostData.project_detail_b.push({ ...project_detail_b_prototype });
    });
  }
  async function postAjax() {
    const urlRequest = route('admin.addProject');
    const method = 'POST';
    let response = '';

    try {
      const ajx = new AjaxRequest(urlRequest, method, PostData);
      response = await ajx.getData();
      return response;
    } catch (error) {
      showerror(error);
      return false;
    }
  }

  function parseToNominal(value) {
    if (typeof value == 'string') {
      // Menghapus karakter non-digit dan non-titik
      var cleanInput = value.replace(/[^\d,.]/g, '');

      // Mengganti koma menjadi titik
      var dotFormatted = cleanInput.replace(',', '.');

      // Menghapus karakter titik tambahan
      var finalResult = dotFormatted.replace(/\.(?=.*\.)/g, '');

      return parseFloat(finalResult);
    } else {
      return parseFloat(value);
    }
  }

  function inputOnlyNumber(objectElement) {
    let val = objectElement.val();
    // Regular expression to allow only digits and dot
    var regex = /^[0-9.]*$/;
    if (!regex.test(val)) {
      // Jika input tidak sesuai, hapus karakter terakhir
      objectElement.val(val.slice(0, -1));
    }
  }

  // -----------------------------------------------------------------

  // Crud And Events
  // ------------------------------------------------------------

  // Ketika Sesudah Memilih Item Di Modal Item Search
  $(document).on('click', '.selectitembtn', function () {
    let code = $(this).data('code');
    let stocks = parseFloat($(this).data('stocks'));
    let min_stock = parseFloat($(this).data('min_stock'));
    let max_stock = parseFloat($(this).data('max_stock'));
    let unit = $(this).data('unit');
    let name = $(this).data('name');

    let dataMaterial = {
      code: code,
      stocks: stocks,
      min_stock: min_stock,
      max_stock: max_stock,
      unit: unit,
      name: name,
      qty: 1
    };

    populateMaterial(dataMaterial);

    modalItemSearch.modal('hide');
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
      price: price,
      qty: 1,
      total: price * 1
    };

    populateUpah(dataUpah);

    modalUpahSearch.modal('hide');
  });
  // Delete Upah From List
  $(document).on('click', '.btndeleteupah', function () {
    let code = $(this).data('code');
    deleteUpahFromList(code);
  });

  // Ubah Qty Dalam List Material
  $(document).on('blur', '.inputqtymaterial', function () {
    if ($(this).val() <= 0 || $(this).val() == '') {
      $(this).val(1);
    }
    let code = $(this).data('code');
    let qtymaterial = parseFloat($(this).val());
    let stocks = parseFloat($(this).data('stocks'));

    if (qtymaterial > stocks) {
      alert('Minus Stocks Not Allowed : Current Stock Is ' + stocks);
      $(this).val(stocks);
      calculateMaterial(code, stocks);
    } else {
      calculateMaterial(code, qtymaterial);
    }
  });
  // Ubah Qty Dalam List Upah
  $(document).on('blur', '.inputqtyupah', function () {
    if ($(this).val() <= 0 || $(this).val() == '') {
      $(this).val(1);
    }
    let code = $(this).data('code');
    let qtyupah = parseFloat($(this).val());

    calculateUpah(code, qtyupah);
  });

  // Submit Button
  $(document).on('click', '.submitbtn', async function () {
    if (validateInput(dataInput, customValidation)) {
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.project');
      if (response) {
        window.location.href = urlRedirect;
      }
    }
  });

  $(document).on('focusin', '.inputbudget', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  $(document).on('blur', '.inputbudget', function (e) {
    $(this).val(formatRupiah1($(this).val()));
  });
  $(document).on('keyup', '.inputbudget', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  // Trigger Notif Toast
  checkNotifMessage();
});
