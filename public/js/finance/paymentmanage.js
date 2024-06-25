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
  const inputbkkno = $('.inputbkkno');
  const inputpaymentmethod = $('.inputpaymentmethod');
  const inputcoaforcashbank = $('.inputcoaforcashbank');
  const inputdescription = $('.inputdescription');
  const labelpaidamount = $('.labelpaidamount');
  const supplierCode = $('.supplierCode');
  const supplierName = $('.supplierName');
  const tbodytablelistpurchase = $('.tablelistpurchase tbody');
  const dtptransdate = $('#dtptransdate');
  const inputtransdate = $('.inputtransdate');
  const dtpdaterequired = $('#dtpdaterequired');
  const inputdaterequired = $('.inputdaterequired');
  // End Html Input

  // Property Data when in update mode
  const datatransadate = $('.inputtransdate').data('transdate');
  const datapaymentmethod = $('.datapaymentmethod').data('paymentmethod');
  const datasuppliername = $('.datasuppliername').data('suppliername');
  const datasuppliercode = $('.datasuppliercode').data('suppliercode');
  const datapayment = $('.datapayment').data('payment');
  const dataamount = $('.dataamount').data('amount');
  // End Property when in update mode

  // Tampungan Parsing hasil data update mode
  let detailpr = [''];

  // State
  const updateMode = route().current() == 'admin.editPaymentView';

  //Validation Input
  let dataInput = ['.inputdescription'];

  // Data For Send TO Controller
  let PostData = {
    bkk_no: '',
    transaction_date: '',
    supplier_code: '',
    coa_cash_code: '',
    payment_method: '',
    description: '',
    detail: []
  };

  let transAmount = {
    grand_total: 0
  };

  let detail = {
    ref_no: '',
    transaction_date: '',
    due_date: '',
    unpaid_amount: 0.0,
    paid_amount: 0.0,
    balance: 0.0
  };

  let tampungDetail = [];
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
    // setTransAmount();
  }

  // ===================================

  // Function and subroutines
  // ===================================

  async function getData(suppliercode = '') {
    const urlRequest = route('admin.getpurchaseforpayment', suppliercode);
    const method = 'GET';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      showerror(error);
      return null;
    }
  }

  function prepareEdit() {
    inputtransdate.val(moment(datatransadate).format('DD/MM/YYYY'));
    supplierCode.val(datasuppliercode);
    supplierName.val(datasuppliername);
    inputpaymentmethod.val(datapaymentmethod);
    let detailPurchase = JSON.parse(JSON.parse(datapayment));

    detail.unpaid_amount = parseFloat(detailPurchase.balance) + parseFloat(dataamount);
    detail.due_date = moment(detailPurchase.due_date).format('DD/MM/YYYY');
    detail.paid_amount = parseFloat(dataamount);
    detail.ref_no = detailPurchase.purchase_no;
    detail.transaction_date = moment(detailPurchase.transaction_date).format('DD/MM/YYYY');
    detail.balance = detail.unpaid_amount - detail.paid_amount;

    tampungDetail.push({ ...detail });

    createHtmTBodyItem();
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

    if (inputcoaforcashbank.val() == '') {
      inputcoaforcashbank.focus();
      showwarning('COA Cash/Bank Cannot Be Empty');
      return false;
    }

    const lengthData = tampungDetail.length;
    let paidAmountEmpty = 0;
    for (let x of tampungDetail) {
      if (parseFloat(x.paid_amount) == 0) {
        paidAmountEmpty += 1;
      }
    }

    if (lengthData == paidAmountEmpty) {
      showwarning('Please Input Paid Amount !');
      return false;
    }

    return true;
  }

  function createHtmTBodyItem() {
    let html = ``;
    let totalUnpaid = 0;
    let totalPaid = 0;
    let totalBalance = 0;
    if (tampungDetail.length == 0) {
      showwarning('No Data Purchases Found !');
    }
    tampungDetail.forEach((item) => {
      html += `
    
      <tr>
        <td style="font-size:13px; width:10%">${item.ref_no}</td>
        <td style="font-size:13px; width:10%">${item.transaction_date}
        </td>
        <td style="font-size:13px;width:10%">${item.due_date}</td>
        <td style="font-size:13px;width:10%; white-space:nowrap">${formatRupiah1(item.unpaid_amount)}</td>
        <td style="font-size:13px;width:15%;white-space:nowrap"><input type="text" data-unpaid_amount="${parseFloat(item.unpaid_amount)}"
            data-code="${item.ref_no}" class="custom-input inputpaidamount" value="${formatRupiah1(item.paid_amount)}">
        </td>
        <td style="font-size:13px;width:15%;white-space:nowrap">${formatRupiah1(item.balance)}</td>
      </tr>
      `;

      totalUnpaid += parseFloat(item.unpaid_amount);
      totalPaid += parseFloat(item.paid_amount);
      totalBalance += parseFloat(item.balance);
    });
    if (tampungDetail.length > 0) {
      html += `
        <tr>
          <td colspan="3" style="text-align:center"><b>Total</b></td>
          <td><b>${formatRupiah1(totalUnpaid)}</b></td>
          <td><b>${formatRupiah1(totalPaid)}</b></td>
          <td><b>${formatRupiah1(totalBalance)}</b></td>
        </tr>
      `;
    }

    tbodytablelistpurchase.html(html);
  }

  async function populatePurchase(suppCode) {
    let suppName = supplierName.val();
    supplierCode.val('Loading....');
    supplierName.val('Loading....');
    const dataDetail = await getData(suppCode);
    supplierCode.val(suppCode);
    supplierName.val(suppName);
    tampungDetail = [];
    dataDetail.forEach((x) => {
      detail.balance = parseFloat(x.balance);
      detail.due_date = moment(x.due_date, 'YYYY-MM-DD').format('DD/MM/YYYY');
      detail.transaction_date = moment(x.transaction_date, 'YYYY-MM-DD').format('DD/MM/YYYY');
      detail.paid_amount = 0.0;
      detail.ref_no = x.purchase_no;
      detail.unpaid_amount = parseFloat(x.balance);

      tampungDetail.push({ ...detail });
    });

    createHtmTBodyItem();
  }

  function calcPurchase(code, amount) {
    const editedDetail = tampungDetail.map((item) => {
      if (item.ref_no === code) {
        let paid_amountNew = parseFloat(amount);

        let balanceNew = parseFloat(item.unpaid_amount) - paid_amountNew;
        return { ...item, paid_amount: paid_amountNew, balance: balanceNew };
      } else {
        return item;
      }
    });
    tampungDetail = [...editedDetail];

    createHtmTBodyItem();
  }

  function populatePostData() {
    PostData.bkk_no = '';
    PostData.coa_cash_code = inputcoaforcashbank.val();
    PostData.description = inputdescription.val();
    PostData.payment_method = inputpaymentmethod.val();
    PostData.supplier_code = supplierCode.val();
    PostData.transaction_date = inputtransdate.val();
    PostData.detail = [];
    tampungDetail.forEach((item) => {
      detail.balance = item.balance;
      detail.due_date = item.due_date;
      detail.paid_amount = item.paid_amount;
      detail.ref_no = item.ref_no;
      detail.transaction_date = item.transaction_date;
      detail.unpaid_amount = item.unpaid_amount;

      PostData.detail.push({ ...detail });
    });
  }

  async function postAjax() {
    let urlRequest = '';

    if (updateMode) {
      let code = inputbkkno.val();
      urlRequest = route('admin.editpayment', code);
    } else {
      urlRequest = route('admin.addpayment');
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

    formData.append('bkk_no', PostData.bkk_no);
    formData.append('coa_cash_code', PostData.coa_cash_code.trim());
    formData.append('description', PostData.description.trim());
    formData.append('payment_method', PostData.payment_method);
    formData.append('supplier_code', PostData.supplier_code);
    formData.append('transaction_date', PostData.transaction_date);
    formData.append('detail', JSON.stringify(PostData.detail));

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

  // Ubah paid amount Dalam List Item
  $(document).on('blur', '.inputpaidamount', function () {
    let unpaid_amount = parseFloat($(this).data('unpaid_amount'));
    if ($(this).val() > unpaid_amount || $(this).val() == '') {
      $(this).val(unpaid_amount);
    }
    let code = $(this).data('code');

    calcPurchase(code, parseFloat($(this).val()));
  });

  // Input Pada Paid Amount ketika pertama kali disorot
  $(document).on('focusin', '.inputpaidamount', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  // Ketika Mengetikkan Didalam Paid Amount
  $(document).on('keyup', '.inputpaidamount', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  // Submit Button
  $(document).on('click', '.submitbtn', async function () {
    if (validateInput(dataInput, customValidation)) {
      $(this).find('span').html('Submitting...');
      $(this).prop('disabled', true);
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.payment');
      if (response) {
        window.location.href = urlRedirect;
      } else {
        updateMode ? $(this).find('span').html('Update') : $(this).find('span').html('Save');
        $(this).prop('disabled', false);
      }
    }
  });

  $('#modalSupplierSearch').on('hidden.bs.modal', async function (e) {
    populatePurchase(supplierCode.val());
  });
  // ===================================
});
