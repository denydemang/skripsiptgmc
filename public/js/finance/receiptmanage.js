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
  const inputbkmno = $('.inputbkmno');
  const inputreceivedvia = $('.inputreceivedvia');
  const inputcoaforcashbank = $('.inputcoaforcashbank');
  const inputdepositamount = $('.inputdepositamount');
  const inputdescription = $('.inputdescription');
  const labelpaidamount = $('.labelpaidamount');
  const customerCode = $('.customerCode');
  const customerName = $('.customerName');
  const tbodytablelistinvoice = $('.tablelistinvoice tbody');
  const dtptransdate = $('#dtptransdate');
  const inputtransdate = $('.inputtransdate');
  // End Html Input

  // Property Data when in update mode
  const datatransadate = $('.inputtransdate').data('transdate');
  const datareceivedvia = $('.datareceivedvia').data('receivedvia');
  const datacustomercode = $('.datacustomercode').data('customercode');
  const datacustomername = $('.datacustomername').data('customername');
  const datadepositamount = $('.inputdepositamount').data('depositamount');
  const datadetail = $('.datadetail').data('detail');
  const datacash_amountdetail = $('.datacash_amount').data('cashamount');
  const datadeposit_amountdetail = $('.datadeposit_amount').data('depositamount');
  const datatotal_amount = $('.datatotal_amount').data('totalamount');
  // End Property when in update mode

  // Tampungan Parsing hasil data update mode
  let detailpr = [''];

  // State
  const updateMode = route().current() == 'admin.editReceiptView';

  //Validation Input
  let dataInput = ['.inputdescription'];

  // Data For Send TO Controller
  let PostData = {
    bkm_no: '',
    transaction_date: '',
    customer_code: '',
    coa_cash_code: '',
    received_via: '',
    description: '',
    detail: []
  };

  let transAmount = {
    deposit_balance: 0.0,
    deposit_amount: 0.0
  };

  let detail = {
    ref_no: '',
    transaction_date: '',
    due_date: '',
    cash_amount: 0.0,
    deposit_amount: 0.0,
    unpaid_amount: 0.0,
    balance: 0.0
  };

  let tampungDetail = [];
  // ====================================

  // Inisiasi Datepircker dan input
  // ======================================
  initiatedtp(dtptransdate);

  if (updateMode) {
    prepareEdit();
  } else {
    inputtransdate.val(Date.getNowDate());
    // inputdaterequired.val(Date.getNowDate());
    // setTransAmount();
  }

  // ===================================

  // Function and subroutines
  // ===================================

  async function getData(custcode = '') {
    const urlRequest = route('admin.getinvoiceforreceipt', custcode);
    const method = 'GET';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      showerror(error);
      return null;
    }
  }
  async function getData2(custcode = '') {
    const urlRequest = route('admin.getbalancear', custcode);
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
    customerCode.val(datacustomercode);
    customerName.val(datacustomername);
    inputreceivedvia.val(datareceivedvia);

    let detailInvoice = JSON.parse(JSON.parse(datadetail));

    transAmount.deposit_amount = parseFloat(datadepositamount) + parseFloat(datadeposit_amountdetail);
    transAmount.deposit_balance = transAmount.deposit_amount - parseFloat(datadeposit_amountdetail);

    detail.unpaid_amount = parseFloat(detailInvoice.balance) + parseFloat(datatotal_amount);
    detail.cash_amount = parseFloat(datacash_amountdetail);
    detail.deposit_amount = parseFloat(datadeposit_amountdetail);
    detail.due_date = moment(detailInvoice.due_date).format('DD/MM/YYYY');
    detail.ref_no = detailInvoice.invoice_no;
    detail.transaction_date = moment(detailInvoice.transaction_date).format('DD/MM/YYYY');
    detail.balance = detail.unpaid_amount - parseFloat(datatotal_amount);

    tampungDetail.push({ ...detail });

    createHtmTBodyItem();
  }

  function customValidation() {
    if (inputtransdate.val() == '') {
      inputtransdate.focus();
      showwarning('Transaction Date Cannot Be Empty');
      return false;
    }
    if (customerCode.val() == '') {
      customerCode.focus();
      showwarning('Customer Code Cannot Be Empty');
      return false;
    }

    if (inputcoaforcashbank.val() == '') {
      inputcoaforcashbank.focus();
      showwarning('COA Cash/Bank Cannot Be Empty');
      return false;
    }

    const lengthData = tampungDetail.length;
    let cashamountpaid = 0;
    let depositamountpaid = 0;
    for (let x of tampungDetail) {
      if (parseFloat(x.cash_amount) == 0) {
        cashamountpaid += 1;
      }
      if (parseFloat(x.deposit_amount) == 0) {
        depositamountpaid += 1;
      }
    }

    if (lengthData == cashamountpaid && lengthData == depositamountpaid) {
      showwarning('Pelase Input Cash Amount Or Deposit Amount!');
      return false;
    }

    return true;
  }

  function createHtmTBodyItem() {
    let html = ``;
    let totalUnpaid = 0;
    let totalPaid = 0;
    let totalDepo = 0;
    let totalBalance = 0;
    if (tampungDetail.length == 0) {
      showwarning('No Data Invoice Found !');
      return;
    }
    inputdepositamount.val(formatRupiah1(transAmount.deposit_balance));
    tampungDetail.forEach((item) => {
      html += `

      <tr>
        <td style="font-size: 12px; width:10%">${item.ref_no}</td>
        <td style="font-size: 12px; width:10%">${item.transaction_date}
        </td>
        <td style="font-size: 12px;width:10%">${item.due_date}</td>
        <td style="font-size: 12px;width:15%; white-space:nowrap">${formatRupiah1(item.unpaid_amount)}</td>
        <td style="font-size: 12px;width:15%;white-space:nowrap"><input type="text" data-unpaid_amount="${parseFloat(item.unpaid_amount)}"
            data-code="${item.ref_no}" class="custom-input inputpaidamount" value="${formatRupiah1(item.cash_amount)}">
        </td>
        <td style="font-size: 12px;width:15%;white-space:nowrap"><input type="text" data-unpaid_amount="${parseFloat(item.unpaid_amount)}"
            data-code="${item.ref_no}" class="custom-input inputdepositamount" value="${formatRupiah1(item.deposit_amount)}">
        </td>
        <td style="font-size: 12px;width:15%;white-space:nowrap">${formatRupiah1(item.balance)}</td>
      </tr>
      `;

      totalUnpaid += parseFloat(item.unpaid_amount);
      totalPaid += parseFloat(item.cash_amount);
      totalDepo += parseFloat(item.deposit_amount);
      totalBalance += parseFloat(item.balance);
    });
    if (tampungDetail.length > 0) {
      html += `
        <tr>
          <td colspan="3" style="text-align:center;font-size: 12px;width:15%;white-space:nowrap"><b>Total</b></td>
          <td style="font-size: 12px;width:15%;white-space:nowrap"><b>${formatRupiah1(totalUnpaid)}</b></td>
          <td style="font-size: 12px;width:15%;white-space:nowrap"><b>${formatRupiah1(totalPaid)}</b></td>
          <td style="font-size: 12px;width:15%;white-space:nowrap"><b>${formatRupiah1(totalDepo)}</b></td>
          <td style="font-size: 12px;width:15%;white-spac   e:nowrap"><b>${formatRupiah1(totalBalance)}</b></td>
        </tr>
      `;
    }

    tbodytablelistinvoice.html(html);
  }

  async function populateInvoice(custCode) {
    let custName = customerName.val();
    customerCode.val('Loading....');
    customerName.val('Loading....');
    const dataDetail = await getData(custCode);
    customerCode.val(custCode);
    customerName.val(custName);
    tampungDetail = [];
    dataDetail.forEach((x) => {
      detail.balance = parseFloat(x.balance);
      detail.due_date = moment(x.due_date, 'YYYY-MM-DD').format('DD/MM/YYYY');
      detail.transaction_date = moment(x.transaction_date, 'YYYY-MM-DD').format('DD/MM/YYYY');
      detail.cash_amount = 0.0;
      detail.deposit_amount = 0.0;
      detail.cash_amount = 0.0;
      detail.ref_no = x.invoice_no;
      detail.unpaid_amount = parseFloat(x.balance);
      tampungDetail.push({ ...detail });
    });
    const balanceAdvancedReceipt = await getData2(custCode);
    transAmount.deposit_balance = parseFloat(balanceAdvancedReceipt);
    transAmount.deposit_amount = parseFloat(balanceAdvancedReceipt);
    createHtmTBodyItem();
  }

  function calcInvoice(code, amount, state = 'cash_amount') {
    switch (state) {
      case 'cash_amount':
        const editedDetail = tampungDetail.map((item) => {
          if (item.ref_no === code) {
            let cash_amountNew = parseFloat(amount);

            if (cash_amountNew + parseFloat(item.deposit_amount) > parseFloat(item.unpaid_amount)) {
              cash_amountNew = parseFloat(item.unpaid_amount) - parseFloat(item.deposit_amount);
              showwarning(`Balance Unpaid Cannot Be Minus !, Set To ${formatRupiah1(cash_amountNew)}`);
            }
            let balanceNew = parseFloat(item.unpaid_amount) - cash_amountNew - parseFloat(item.deposit_amount);
            return { ...item, cash_amount: cash_amountNew, balance: balanceNew };
          } else {
            return item;
          }
        });
        tampungDetail = [...editedDetail];
        createHtmTBodyItem();
        break;
      case 'deposit_amount':
        if (code == null) {
          let counter = 1;

          const editedDetail1 = tampungDetail.map((item) => {
            if (counter == 1) {
              let deposit_amountNew = parseFloat(amount);

              if (deposit_amountNew + parseFloat(item.cash_amount) > parseFloat(item.unpaid_amount)) {
                deposit_amountNew = parseFloat(item.unpaid_amount) - parseFloat(item.cash_amount);
              }
              let balanceNew = parseFloat(item.unpaid_amount) - parseFloat(item.cash_amount) - deposit_amountNew;
              counter++;
              return { ...item, deposit_amount: deposit_amountNew, balance: balanceNew };
            } else {
              return item;
            }
          });
          tampungDetail = [...editedDetail1];
        } else {
          const editedDetail1 = tampungDetail.map((item) => {
            if (item.ref_no === code) {
              let deposit_amountNew = parseFloat(amount);

              if (deposit_amountNew + parseFloat(item.cash_amount) > parseFloat(item.unpaid_amount)) {
                deposit_amountNew = parseFloat(item.unpaid_amount) - parseFloat(item.cash_amount);
              }
              let balanceNew = parseFloat(item.unpaid_amount) - parseFloat(item.cash_amount) - deposit_amountNew;
              return { ...item, deposit_amount: deposit_amountNew, balance: balanceNew };
            } else {
              return item;
            }
          });
          tampungDetail = [...editedDetail1];
        }

        checkDepositAmount(code);
        break;
    }
  }

  function checkDepositAmount(code) {
    let totalDepo = 0;
    tampungDetail.forEach((x) => {
      totalDepo += parseFloat(x.deposit_amount);
    });

    if (totalDepo > transAmount.deposit_amount) {
      //   const filtereddeposit = tampungDetail.filter((x) => {
      //     return x.ref_no == code;
      //   });
      showwarning(`Exceed Deposit Amount ! Set To ${parseFloat(transAmount.deposit_balance)}`);

      calcInvoice(code, transAmount.deposit_balance, 'deposit_amount');
    } else {
      transAmount.deposit_balance = transAmount.deposit_amount - totalDepo;
      createHtmTBodyItem();
    }
  }

  function populatePostData() {
    PostData.bkm_no = '';
    PostData.coa_cash_code = inputcoaforcashbank.val();
    PostData.description = inputdescription.val();
    PostData.received_via = inputreceivedvia.val();
    PostData.customer_code = customerCode.val();
    PostData.transaction_date = inputtransdate.val();
    PostData.detail = [];
    tampungDetail.forEach((item) => {
      detail.balance = item.balance;
      detail.due_date = item.due_date;
      detail.deposit_amount = item.deposit_amount;
      detail.cash_amount = item.cash_amount;
      detail.transaction_date = item.transaction_date;
      detail.unpaid_amount = item.unpaid_amount;
      detail.ref_no = item.ref_no;

      PostData.detail.push({ ...detail });
    });
  }

  async function postAjax() {
    let urlRequest = '';

    if (updateMode) {
      let code = inputbkmno.val();
      urlRequest = route('admin.editreceipt', code);
    } else {
      urlRequest = route('admin.addreceipt');
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

    formData.append('bkm_no', PostData.bkm_no);
    formData.append('coa_cash_code', PostData.coa_cash_code.trim());
    formData.append('description', PostData.description.trim());
    formData.append('received_via', PostData.received_via);
    formData.append('customer_code', PostData.customer_code);
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
    if ($(this).val() == '') {
      $(this).val(0);
    }
    let code = $(this).data('code');
    calcInvoice(code, parseFloat($(this).val()), 'cash_amount');
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

  // Ubah paid amount Dalam List Item
  $(document).on('blur', '.inputdepositamount', function () {
    if ($(this).val() == '') {
      $(this).val(0);
    }
    let code = $(this).data('code');
    calcInvoice(code, parseFloat($(this).val()), 'deposit_amount');
  });

  // Input Pada Paid Amount ketika pertama kali disorot
  $(document).on('focusin', '.inputdepositamount', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  // Ketika Mengetikkan Didalam Paid Amount
  $(document).on('keyup', '.inputdepositamount', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  $(document).on('click', '.btnallocate', function () {
    console.log(transAmount.deposit_amount);
    calcInvoice(null, transAmount.deposit_amount, 'deposit_amount');
  });

  // Submit Button
  $(document).on('click', '.submitbtn', async function () {
    if (validateInput(dataInput, customValidation)) {
      $(this).find('span').html('Submitting...');
      $(this).prop('disabled', true);
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.receipt');
      if (response) {
        window.location.href = urlRedirect;
      } else {
        updateMode ? $(this).find('span').html('Update') : $(this).find('span').html('Save');
        $(this).prop('disabled', false);
      }
    }
  });

  $('#modalCustomerSearch').on('hidden.bs.modal', async function (e) {
    populateInvoice(customerCode.val());
  });
  // ===================================
});
