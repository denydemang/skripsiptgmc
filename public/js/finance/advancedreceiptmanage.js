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
  const inputadrno = $('.inputadrno');
  const inputtransdate = $('.inputtransdate');
  const customerCode = $('.customerCode');
  const customerName = $('.customerName');
  const inputcoacodeforcashbank = $('.inputcoacodeforcashbank');
  const inputcoanameforcashbank = $('.inputcoanameforcashbank');
  const inputcoacodeforadvancedreceipt = $('.inputcoacodeforadvancedreceipt');
  const inputcoanameforadvancedreceipt = $('.inputcoanameforadvancedreceipt');
  const inputAmountCash = $('.inputAmountCash');
  const inputdescription = $('.inputdescription');
  const dtpdaterequired = $('#dtpdaterequired');
  const dtptransdate = $('#dtptransdate');
  // End Html Input

  // Property when in update mode

  const datacustomercode = $('.datacustomercode').data('customercode');
  const datacustomername = $('.datacustomername').data('customername');
  const datatransadate = $('.datatransadate').data('transdate');
  // End Property when in update mode

  // State
  const updateMode = route().current() == 'admin.editAdvancedReceiptView';

  //Validation Input
  let dataInput = ['.inputdescription', '.inputadrno', '.inputdescription'];

  let PostData = {
    adr_no: '',
    transaction_date: '',
    customer_code: '',
    coa_credit: '',
    coa_debit: '',
    deposit_amount: 0.0,
    description: ''
  };

  // ====================================

  // Inisiasi Datepircker dan input
  // ======================================
  initiatedtp(dtptransdate);

  if (updateMode) {
    prepareEdit();
  } else {
    inputtransdate.val(Date.getNowDate());
  }

  // ===================================

  // Function and subroutines
  // ===================================

  function prepareEdit() {
    inputtransdate.val(moment(datatransadate).format('DD/MM/YYYY'));
    customerCode.val(datacustomercode);
    customerName.val(datacustomername);
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

    if (parseToNominal(inputAmountCash.val()) == 0) {
      inputAmountCash.focus();
      showwarning('Deposit Amount Cannot Be Zero');
      return false;
    }

    if (inputcoacodeforcashbank.val() == '') {
      inputcoacodeforcashbank.focus();
      showwarning('COA Cash/Bank Cannot Be Empty');
      return false;
    }

    if (inputcoacodeforadvancedreceipt.val() == '') {
      inputcoacodeforadvancedreceipt.focus();
      showwarning('COA ADR Cannot Be Empty');
      return false;
    }

    return true;
  }

  function populatePostData() {
    PostData.adr_no = '';
    PostData.coa_credit = inputcoacodeforadvancedreceipt.val();
    PostData.coa_debit = inputcoacodeforcashbank.val();
    PostData.customer_code = customerCode.val();
    PostData.deposit_amount = parseToNominal(inputAmountCash.val());
    PostData.description = inputdescription.val().trim();
    PostData.transaction_date = inputtransdate.val();
  }

  async function postAjax() {
    let urlRequest = '';

    if (updateMode) {
      let code = inputadrno.val();
      urlRequest = route('admin.editAR', code);
    } else {
      urlRequest = route('admin.addAR');
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

    formData.append('adr_no', PostData.adr_no);
    formData.append('coa_credit', PostData.coa_credit.trim());
    formData.append('coa_debit', PostData.coa_debit.trim());
    formData.append('customer_code', PostData.customer_code);
    formData.append('deposit_amount', PostData.deposit_amount);
    formData.append('description', PostData.description);
    formData.append('transaction_date', PostData.transaction_date);

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

  // Input Pada inputAmountCash ketika pertama kali disorot
  $(document).on('focusin', '.inputAmountCash', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });
  // Ketika Mengetikkan Didalam  inputAmountCash
  $(document).on('keyup', '.inputAmountCash', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  // Ketika FOcusout  Didalam  inputAmountCash
  $(document).on('blur', '.inputAmountCash', function (event) {
    let amount = $(this).val() == '' ? 0 : parseFloat($(this).val());

    $(this).val(formatRupiah1(amount));
  });

  // Submit Button
  $(document).on('click', '.submitbtn', async function () {
    if (validateInput(dataInput, customValidation)) {
      $(this).find('span').html('Submitting...');
      $(this).prop('disabled', true);
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.advancedreceipt');
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
