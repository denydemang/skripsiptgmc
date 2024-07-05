import AjaxRequest from '../ajaxrequest.js';
import { showwarning, showerror } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import validateInput from '../validateInput.js';
import initiatedtp from '../datepickerinitiator.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import managedate from '../managedate.js';
$(document).ready(function () {
  const Date = new managedate();

  // Property
  // =====================================
  // Html Input
  const inputpr_no = $('.inputpr_no');
  const inputpicname = $('.inputpicname');
  const inputdivision = $('.inputdivision');
  const inputrefno = $('.inputrefno');
  const dtptransdate = $('#dtptransdate');
  const inputtransdate = $('.inputtransdate');
  const dtpdaterequired = $('#dtpdaterequired');
  const inputdaterequired = $('.inputdaterequired');
  const inputdescription = $('.inputdescription');
  // End Html Input

  // Property when in update mode
  const dataprdetail = $('.dataprdetail');
  // End Property when in update mode

  // Tampungan Parsing hasil data update mode
  let detailpr = [];

  // State
  const updateMode = route().current() == 'admin.editprview';

  //Validation Input
  let dataInput = ['.inputpicname', '.inputdivision', '.inputdescription'];

  // Data For Send TO Controller
  let PostData = {
    pr_no: '',
    transaction_date: '',
    pic_name: '',
    division: '',
    ref_no: '',
    date_need: '',
    description: '',
    pr_details: []
  };

  let pr_details_prototype = {
    pr_no: '',
    item_code: '',
    unit_code: '',
    qty: 0.0
  };

  let tampungItem = [];
  // ====================================

  // Inisiasi Datepircker dan input
  // ======================================
  initiatedtp(dtptransdate);
  initiatedtp(dtpdaterequired);

  if (updateMode) {
    prepareEdit();
  } else {
    inputtransdate.val(Date.getNowDate());
    inputdaterequired.val(Date.getNowDate());
  }

  // ===================================

  // Function and subroutines
  // ===================================
  function prepareEdit() {
    detailpr = JSON.parse(JSON.parse(dataprdetail.data('prdetail')));
    inputtransdate.val(moment(inputtransdate.data('transdate')).format('DD/MM/YYYY'));
    inputdaterequired.val(moment(inputdaterequired.data('daterequired')).format('DD/MM/YYYY'));

    detailpr.forEach((item) => {
      let dataItem = {
        code: item.item_code,
        stocks: parseFloat(item.stocks),
        min_stock: 0,
        max_stock: 0,
        unit: item.unit_code,
        name: item.item_name,
        qty: parseFloat(item.qty)
      };

      populateItem(dataItem);
    });
  }

  function customValidation() {
    if (inputtransdate.val() == '') {
      inputtransdate.focus();
      showwarning('Transaction Date Cannot Be Empty');
      return false;
    }
    if (inputdaterequired.val() == '') {
      inputdaterequired.focus();
      showwarning('Required Date Cannot Be Empty');
      return false;
    }
    if (tampungItem.length <= 0) {
      showwarning('Please Input Item List!');
      return false;
    }
    return true;
  }

  function createHtmTBodyItem() {
    const tbody = $('.tableitem tbody');

    let html = ``;

    tampungItem.forEach((item) => {
      html += `
    
      <tr>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size:12px">${item.code}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size:12px">${item.name}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size:12px">${item.unit}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size:12px"><input
            style="width:100%;font-size:13px" type="number" min="1" class="custom-input inputqtyitem"data-stocks="${item.stocks}" data-code="${item.code}" value="${item.qty}">
        </td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;font-size:12px">${item.stocks}</td>
        <td class="col-1" style="white-space:normal;word-wrap: break-word;font-size:12px"><button data-code="${item.code}" class="btn btn-danger btndeleteitem btn-sm">X</button></td>
      </tr>
      `;
    });

    tbody.html(html);
  }

  function checkItemExist(code) {
    // Bernilai true jika ada item
    return tampungItem.some((item) => item.code === code);
  }

  function populateItem(data = {}) {
    if (checkItemExist(data.code)) {
      // incrementqty + 1
      const editedItem = tampungItem.map((item) => {
        if (item.code === data.code) {
          let qtytotal = parseFloat(item.qty) + parseFloat(data.qty);
          return { ...item, qty: qtytotal };
        } else {
          return item;
        }
      });
      tampungItem = [...editedItem];
    } else {
      // Direct Push
      tampungItem.push(data);
    }

    createHtmTBodyItem();
  }

  function calculateItem(code, qty) {
    const editedItem = tampungItem.map((item) => {
      if (item.code === code) {
        return { ...item, qty: qty };
      } else {
        return item;
      }
    });
    tampungItem = [...editedItem];

    createHtmTBodyItem();
  }

  function deleteItemFromList(Code) {
    const editedItem = tampungItem.filter((item) => {
      return item.code != Code;
    });

    tampungItem = [...editedItem];
    createHtmTBodyItem();
  }

  function populatePostData() {
    PostData.pr_no = '';
    PostData.date_need = inputdaterequired.val();
    PostData.transaction_date = inputtransdate.val();
    PostData.division = inputdivision.val();
    PostData.pic_name = inputpicname.val();
    PostData.ref_no = inputrefno.val();
    PostData.description = inputdescription.val();
    PostData.pr_details = [];
    tampungItem.forEach((item) => {
      pr_details_prototype.item_code = item.code;
      pr_details_prototype.pr_no = '';
      pr_details_prototype.qty = item.qty;
      pr_details_prototype.unit_code = item.unit;

      PostData.pr_details.push({ ...pr_details_prototype });
    });
  }

  async function postAjax() {
    let urlRequest = '';

    if (updateMode) {
      let code = inputpr_no.val();
      urlRequest = route('admin.editpr', code);
    } else {
      urlRequest = route('admin.addpr');
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

    formData.append('pr_no', PostData.pr_no);
    formData.append('date_need', PostData.date_need);
    formData.append('description', PostData.description.trim());
    formData.append('division', PostData.division.trim());
    formData.append('pic_name', PostData.pic_name.trim());
    formData.append('ref_no', PostData.ref_no.trim());
    formData.append('transaction_date', PostData.transaction_date);
    formData.append('pr_details', JSON.stringify(PostData.pr_details));

    return formData;
  }
  // ====================================

  // Event And Crud
  // ====================================
  // Ketika Sesudah Memilih Item Di Modal Item Search
  $(document).on('click', '.selectitembtn', function () {
    let code = $(this).data('code');
    let stocks = parseFloat($(this).data('stocks'));
    let min_stock = parseFloat($(this).data('min_stock'));
    let max_stock = parseFloat($(this).data('max_stock'));
    let unit = $(this).data('unit');
    let name = $(this).data('name');
    let counter = 0;
    let dataItem = {};
    if (updateMode) {
      tampungItem.forEach((item) => {
        if (item.item_code == code) {
          counter++;
          dataItem = {
            code: code,
            stocks: parseFloat(item.stocks),
            min_stock: min_stock,
            max_stock: max_stock,
            unit: unit,
            name: name,
            qty: 1
          };
        }
      });

      if (counter == 0) {
        dataItem = {
          code: code,
          stocks: stocks,
          min_stock: min_stock,
          max_stock: max_stock,
          unit: unit,
          name: name,
          qty: 1
        };
      }
    } else {
      dataItem = {
        code: code,
        stocks: stocks,
        min_stock: min_stock,
        max_stock: max_stock,
        unit: unit,
        name: name,
        qty: 1
      };
    }

    populateItem(dataItem);

    // modalItemSearch.modal('hide');
  });

  // Ubah Qty Dalam List Item
  $(document).on('blur', '.inputqtyitem', function () {
    if ($(this).val() <= 0 || $(this).val() == '') {
      $(this).val(1);
    }
    let code = $(this).data('code');
    let qtyitem = parseFloat($(this).val());
    // let stocks = parseFloat($(this).data('stocks'));
    calculateItem(code, qtyitem);
  });

  // Delete Material From List
  $(document).on('click', '.btndeleteitem', function () {
    let code = $(this).data('code');
    deleteItemFromList(code);
  });

  // Submit Button
  $(document).on('click', '.submitbtn', async function () {
    if (validateInput(dataInput, customValidation)) {
      $(this).find('span').html('Submitting...');
      $(this).prop('disabled', true);
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.pr');
      if (response) {
        window.location.href = urlRedirect;
      } else {
        updateMode ? $(this).find('span').html('Update') : $(this).find('span').html('Save');
        $(this).prop('disabled', false);
      }
    }
  });
  // ===================================
});
