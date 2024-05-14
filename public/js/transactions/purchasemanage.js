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
  const inputpurchaseno = $('.inputpurchaseno');
  const inputpaymentterm = $('.inputpaymentterm');
  const labelgrandtotal = $('.labelgrandtotal');
  const inputppnamount = $('.inputppnamount');
  const inputpercentppn = $('.inputpercentppn');
  const inputotherfee = $('.inputotherfee');
  const inputdescription = $('.inputdescription');
  const labeltotal = $('.labeltotal');
  const supplierCode = $('.supplierCode');
  const supplierName = $('.supplierName');
  const tbodytablelistdetailpr = $('.tablelistdetailpr tbody');
  const prCode = $('.prCode');

  const modalPRSearch = $('#modalPRSearch');
  const inputpicname = $('.inputpicname');
  const inputdivision = $('.inputdivision');
  const inputrefno = $('.inputrefno');
  const dtptransdate = $('#dtptransdate');
  const inputtransdate = $('.inputtransdate');
  const dtpdaterequired = $('#dtpdaterequired');
  const inputdaterequired = $('.inputdaterequired');
  // End Html Input

  // Property when in update mode
  const purchasesdetail = $('.purchasesdetail');
  const datasuppliercode = $('.datasuppliercode');
  const datasuppliername = $('.datasuppliername');
  const dataprcode = $('.dataprcode');
  const datapercentppn = $('.datapercentppn');
  const dataamountppn = $('.dataamountppn');
  const dataotherfee = $('.dataotherfee');
  const datapaymentterm = $('.datapaymentterm');
  // End Property when in update mode

  // Tampungan Parsing hasil data update mode
  let detailpr = [];

  // State
  const updateMode = route().current() == 'admin.editPurchaseView';

  //Validation Input
  let dataInput = [];

  // Data For Send TO Controller
  let PostData = {
    purchase_no: '',
    pr_no: '',
    transaction_date: '',
    supplier_code: '',
    total: '',
    other_fee: '',
    percen_ppn: '',
    ppn_amount: '',
    grand_total: '',
    payment_term_code: '',
    is_credit: '',
    is_approve: '',
    paid_amount: '',
    description: '',
    approved_by: '',
    purchase_detail: []
  };

  let transAmount = {
    total: 0,
    otherfee: 0,
    ppnpercent: 0,
    ppnamount: 0,
    grand_total: 0
  };

  let purchase_detail_interface = {
    item_code: '',
    item_name: '',
    unit_code: '',
    qty: 0.0,
    price: 0.0,
    total: 0.0,
    discount: 0.0,
    sub_total: 0.0
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
    setTransAmount();
  }

  // ===================================

  // Function and subroutines
  // ===================================

  async function getData(code = '') {
    const urlRequest = route('admin.detailPR', code);
    const method = 'POST';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      showerror(error);
      return null;
    }
  }

  function updateOtherFeeAmount(amount) {
    transAmount.otherfee = parseFloat(amount);
    calculateAmountTrans();
  }

  function updatePPNAmount(percentAmount) {
    transAmount.ppnpercent = parseFloat(percentAmount);

    calculateAmountTrans();
  }

  function calculateAmountTrans() {
    let total = 0;
    if (tampungItem.length > 0) {
      tampungItem.forEach((x) => {
        total += parseFloat(x.sub_total);
      });
    }
    transAmount.total = total;

    transAmount.ppnamount = (transAmount.total + transAmount.otherfee) * (transAmount.ppnpercent / 100);

    transAmount.grand_total = transAmount.total + transAmount.otherfee + transAmount.ppnamount;
    setTransAmount();
  }

  function resetTransAmount() {
    transAmount.total = 0.0;
    transAmount.grand_total = 0.0;
    transAmount.otherfee = 0.0;
    transAmount.ppnamount = 0.0;
    transAmount.ppnpercent = 0.0;
    calculateAmountTrans();
  }

  function prepareEdit() {
    detailpr = JSON.parse(JSON.parse(purchasesdetail.data('purchasedetail')));
    inputtransdate.val(moment(inputtransdate.data('transdate')).format('DD/MM/YYYY'));
    let supplier_code = datasuppliercode.data('suppliercode');
    let supplier_name = datasuppliername.data('suppliername');
    let pr_no = dataprcode.data('dataprcode');
    let otherfee = dataotherfee.data('otherfee');
    let percentppn = datapercentppn.data('percentppn');
    let amountppn = dataamountppn.data('amountppn');
    let paymentterm = datapaymentterm.data('paymentterm');
    supplierCode.val(supplier_code);
    supplierName.val(supplier_name);
    inputpaymentterm.val(paymentterm);
    prCode.val(pr_no);

    detailpr.forEach((x) => {
      purchase_detail_interface.item_code = x.code;
      purchase_detail_interface.item_name = x.name;
      purchase_detail_interface.unit_code = x.unit_code;
      purchase_detail_interface.qty = parseFloat(x.qty);
      purchase_detail_interface.price = parseFloat(x.price);
      purchase_detail_interface.discount = parseFloat(x.discount);
      purchase_detail_interface.total = parseFloat(x.total);
      purchase_detail_interface.sub_total = parseFloat(x.sub_total);
      tampungItem.push({ ...purchase_detail_interface });
    });

    createHtmTBodyItem();
    updateOtherFeeAmount(parseFloat(otherfee));
    updatePPNAmount(parseFloat(percentppn) * 100);
  }

  function customValidation() {
    if (inputtransdate.val() == '') {
      inputtransdate.focus();
      showwarning('Transaction Date Cannot Be Empty');
      return false;
    }
    if (supplierCode.val() == '') {
      supplierCode.focus();
      showwarning('Supplier Code Cannot Be Empty');
      return false;
    }
    if (prCode.val() == '') {
      prCode.focus();
      showwarning('PR Code Cannot Be Empty');
      return false;
    }

    if (inputdescription.val() == '') {
      inputdescription.focus();
      showwarning('Description Cannot Be Empty');
      return false;
    }

    for (let x of tampungItem) {
      if (parseFloat(x.qty) == 0) {
        showwarning(`Qty Item Code : ${x.item_code} In Item List Cannot Be Zero`);
        return false;
      }

      if (parseFloat(x.price) == 0) {
        showwarning(`Price Item Code : ${x.item_code} In Item List Cannot Be Zero`);
        return false;
      }
    }

    return true;
  }

  function createHtmTBodyItem() {
    let html = ``;
    let counter = 1;
    tampungItem.forEach((item) => {
      html += `
    
      <tr>
        <td style="font-size: 10px;width:5%">${counter}</td>
        <td style="font-size: 10px; width:5%">${item.item_code}</td>
        <td style="font-size: 10px; width:15%">${item.item_name}
        </td>
        <td style="font-size: 10px;width:5%">${item.unit_code}</td>
        <td style="font-size: 10px;width:10%; white-space:nowrap"><input type="number" data-qty="${parseFloat(item.qty)}"
            data-code="${item.item_code}" style="width: 100%" class="custom-input inputqtyitem" value="${parseFloat(item.qty)}"></td>
        <td style="font-size: 10px;width:15%;white-space:nowrap"><input type="text" data-price="${parseFloat(item.price)}"
            data-code="${item.item_code}" class="custom-input inputpriceitem" value="${formatRupiah1(item.price)}">
        </td>
        <td style="font-size: 10px;width:15%;white-space:nowrap">${formatRupiah1(item.total)}</td>
        <td style="font-size: 10px;width:15%;white-space:nowrap"><input type="text" data-discount="${parseFloat(item.discount)}"
            data-code="${item.item_code}" class="custom-input inputdiscountitem" value="${formatRupiah1(item.discount)}"></td>
        <td style="font-size: 10px;width:20%;white-space:nowrap">${formatRupiah1(item.sub_total)}</td>
      </tr>
      `;
      counter++;
    });

    tbodytablelistdetailpr.html(html);
  }

  function checkItemExist(code) {
    // Bernilai true jika ada item
    return tampungItem.some((item) => item.code === code);
  }

  function setTransAmount() {
    labeltotal.html(formatRupiah1(transAmount.total));
    labelgrandtotal.html(formatRupiah1(transAmount.grand_total));
    inputotherfee.val(formatRupiah1(transAmount.otherfee));
    inputpercentppn.val(parseFloat(transAmount.ppnpercent));
    inputppnamount.val(formatRupiah1(transAmount.ppnamount));
  }

  async function populateItem(code) {
    prCode.val('Loading....');
    const dataDetail = await getData(code);
    prCode.val(code);
    tampungItem = [];
    resetTransAmount();

    dataDetail.forEach((x) => {
      purchase_detail_interface.item_code = x.item_code;
      purchase_detail_interface.item_name = x.name;
      purchase_detail_interface.unit_code = x.unit_code;
      purchase_detail_interface.qty = x.qty;

      tampungItem.push({ ...purchase_detail_interface });
    });

    createHtmTBodyItem();
    calculateAmountTrans();
  }

  function calculateItem(code, amount, state) {
    switch (state) {
      case 'updateqty':
        const editedItem = tampungItem.map((item) => {
          if (item.item_code === code) {
            let qtyNew = parseFloat(amount);
            let totalNew = qtyNew * parseFloat(item.price);
            let subtotalNew = totalNew - parseFloat(item.discount);
            return { ...item, qty: qtyNew, total: totalNew, sub_total: subtotalNew };
          } else {
            return item;
          }
        });
        tampungItem = [...editedItem];

        break;
      case 'updateprice':
        const editedItem1 = tampungItem.map((item) => {
          if (item.item_code === code) {
            let priceNew = parseFloat(amount);
            let totalNew = item.qty * priceNew;
            let subtotalNew = totalNew - parseFloat(item.discount);
            return { ...item, price: priceNew, total: totalNew, sub_total: subtotalNew };
          } else {
            return item;
          }
        });
        tampungItem = [...editedItem1];

        break;
      case 'updatediscount':
        const editedItem2 = tampungItem.map((item) => {
          if (item.item_code === code) {
            let discountNew = parseFloat(amount);
            let subtotalNew = item.total - discountNew;
            return { ...item, discount: discountNew, sub_total: subtotalNew };
          } else {
            return item;
          }
        });
        tampungItem = [...editedItem2];

        break;

      default:
        break;
    }

    createHtmTBodyItem();
    calculateAmountTrans();
  }

  function populatePostData() {
    PostData.purchase_no = '';
    PostData.description = inputdescription.val();
    PostData.grand_total = parseFloat(transAmount.grand_total);
    PostData.is_approve = null;
    PostData.is_credit = 1;
    PostData.other_fee = transAmount.otherfee;
    PostData.paid_amount = null;
    PostData.payment_term_code = inputpaymentterm.val();
    PostData.percen_ppn = transAmount.ppnpercent / 100;
    PostData.ppn_amount = transAmount.ppnamount;
    PostData.pr_no = prCode.val();
    PostData.supplier_code = supplierCode.val();
    PostData.total = transAmount.total;
    PostData.transaction_date = inputtransdate.val();
    PostData.purchase_detail = [];
    tampungItem.forEach((item) => {
      purchase_detail_interface.discount = item.discount;
      purchase_detail_interface.item_code = item.item_code;
      purchase_detail_interface.item_name = item.item_name;
      purchase_detail_interface.price = item.price;
      purchase_detail_interface.qty = item.qty;
      purchase_detail_interface.sub_total = item.sub_total;
      purchase_detail_interface.total = item.total;
      purchase_detail_interface.unit_code = item.unit_code;

      PostData.purchase_detail.push({ ...purchase_detail_interface });
    });
  }

  async function postAjax() {
    let urlRequest = '';

    if (updateMode) {
      let code = inputpurchaseno.val();
      urlRequest = route('admin.editpurchase', code);
    } else {
      urlRequest = route('admin.addpurchase');
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

    formData.append('approved_by', PostData.approved_by);
    formData.append('description', PostData.description.trim());
    formData.append('grand_total', PostData.grand_total);
    formData.append('is_approve', PostData.is_approve);
    formData.append('is_credit', PostData.is_credit);
    formData.append('other_fee', PostData.other_fee);
    formData.append('paid_amount', PostData.paid_amount);
    formData.append('payment_term_code', PostData.payment_term_code);
    formData.append('percen_ppn', PostData.percen_ppn);
    formData.append('ppn_amount', PostData.ppn_amount);
    formData.append('pr_no', PostData.pr_no);
    formData.append('purchase_no', PostData.purchase_no);
    formData.append('supplier_code', PostData.supplier_code);
    formData.append('total', PostData.total);
    formData.append('transaction_date', PostData.transaction_date);
    formData.append('purchase_detail', JSON.stringify(PostData.purchase_detail));

    return formData;
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
  // ====================================

  // Event And Crud
  // ====================================
  // Ketika Sesudah Memilih PR Di Modal PR Search
  $(document).on('click', '.selectprbutton', function () {
    let code = $(this).data('code');
    populateItem(code);
    modalPRSearch.modal('hide');

    // modalItemSearch.modal('hide');
  });

  // Ubah Qty Dalam List Item
  $(document).on('blur', '.inputqtyitem', function () {
    let dataQty = parseFloat($(this).data('qty'));
    if ($(this).val() < dataQty || $(this).val() == '') {
      $(this).val(dataQty);
    }
    let code = $(this).data('code');
    calculateItem(code, parseFloat($(this).val()), 'updateqty');
  });

  // Input Pada Price  item ketika pertama kali disorot
  $(document).on('focusin', '.inputpriceitem', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  // Ketika Selesai Edit Price Item
  $(document).on('blur', '.inputpriceitem', function (e) {
    let code = $(this).data('code');
    calculateItem(code, parseFloat($(this).val()), 'updateprice');
  });

  // Ketika Mengetikkan Didalam Input Price Item
  $(document).on('keyup', '.inputpriceitem', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  // Input Pada Discount item ketika pertama kali disorot
  $(document).on('focusin', '.inputdiscountitem', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  // Ketika Selesai Edit discount Item
  $(document).on('blur', '.inputdiscountitem', function (e) {
    let code = $(this).data('code');
    calculateItem(code, parseFloat($(this).val()), 'updatediscount');
  });

  // Ketika Mengetikkan Didalam Input discount Item
  $(document).on('keyup', '.inputdiscountitem', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  // Input Pada Other Fee ketika pertama kali disorot
  $(document).on('focusin', '.inputotherfee', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  // Ketika Selesai Edit Other fee
  $(document).on('blur', '.inputotherfee', function (e) {
    updateOtherFeeAmount(parseFloat($(this).val()));
  });

  // Ketika Mengetikkan Didalam Input other fee
  $(document).on('keyup', '.inputotherfee', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  // Input Pada Percent PPN ketika pertama kali disorot
  $(document).on('focusin', '.inputpercentppn', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  $(document).on('blur', '.inputpercentppn', function (e) {
    if ($(this).val() < 0 || $(this).val() == '') {
      $(this).val(0);
    }
    updatePPNAmount(parseFloat($(this).val()));
  });

  // Submit Button
  $(document).on('click', '.submitbtn', async function () {
    if (validateInput(dataInput, customValidation)) {
      $(this).find('span').html('Submitting...');
      $(this).prop('disabled', true);
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.purchase');
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
