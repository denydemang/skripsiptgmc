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
  const inputcashno = $('.inputcashno');
  const inputcoaforcashbank = $('.inputcoaforcashbank');
  const inputtype = $('.inputtype');
  const inputref = $('.inputref');
  const inputAmountCash = $('.inputAmountCash');
  const inputdescription = $('.inputdescription');
  const inputcoalawancode = $('.inputcoalawancode');
  const inputcoalawanname = $('.inputcoalawanname');
  const labelcoacodelawan = $('.labelcoacodelawan');
  const labelcoanamelawan = $('.labelcoanamelawan');
  const inputamountcoalawan = $('.inputamountcoalawan');
  const btndeletecoalawan = $('.btndeletecoalawan');
  const btndeleteadjusment = $('.btndeleteadjusment');
  const inputcoacodeadjustment = $('.inputcoacodeadjustment');
  const inputcoanameadjustment = $('.inputcoanameadjustment');
  const labelcoacodeadjustment = $('.labelcoacodeadjustment');
  const labelcoanameadjustment = $('.labelcoanameadjustment');
  const inputdebitadjustment = $('.inputdebitadjustment');
  const inputcreditadjustment = $('.inputcreditadjustment');
  const inputdescadjusment = $('.inputdescadjusment');
  const titlepay = $('.title-pay');
  const submitbtn = $('.submitbtn');

  const dtptransdate = $('#dtptransdate');
  const inputtransdate = $('.inputtransdate');

  // End Html Input

  // Property Data when in update mode
  const datatransadate = $('.inputtransdate').data('transdate');
  const datatype = $('.datatype').data('datatype');
  const dataamountcoalawan = $('.inputamountcoalawan').data('amountcoalawan');
  const datadebitadjustment = $('.inputdebitadjustment').data('debitadjustment');
  const datainputcreditadjustment = $('.inputcreditadjustment').data('debitadjustment');
  // End Property when in update mode

  // Tampungan Parsing hasil data update mode
  let detailpr = [''];

  // State
  const updateMode = route().current() == 'admin.editCashbookView';

  //Validation Input
  let dataInput = ['.inputtype', '.inputdescription'];

  // Data For Send TO Controller

  let PostData = {
    cash_no: '',
    transaction_date: '',
    COA_Cash: '',
    ref_no: '',
    total_transaction: 0.0,
    description: '',
    CbpType: '',
    coa_lawan: '',
    amount_lawan: 0.0,
    coa_adjustment: '',
    coa_adjusment_debit: 0.0,
    coa_adjustment_kredit: 0.0,
    coa_adjustment_description: ''
  };

  let CoaExpenseOrOther = {
    coa_code: '',
    coa_name: '',
    amount: 0.0
  };

  let CoaExpenseAdjustment = {
    coa_code: '',
    coa_name: '',
    debit: 0.0,
    credit: 0.0,
    description: 0.0
  };
  let tampungDetail = [];
  // ====================================

  // Inisiasi Datepircker dan input
  // ======================================
  initiatedtp(dtptransdate);

  changetitlepay();

  if (updateMode) {
    prepareEdit();
  } else {
    inputtransdate.val(Date.getNowDate());
    checkExistCOA();
    UpdateTableCOA();
    // setTransAmount();
  }

  // ===================================

  // Function and subroutines
  // ===================================

  function prepareEdit() {
    inputtransdate.val(moment(datatransadate).format('DD/MM/YYYY'));
    inputtype.val(datatype);
    CoaExpenseOrOther.amount = dataamountcoalawan;
    CoaExpenseOrOther.coa_code = inputcoalawancode.val();
    CoaExpenseOrOther.coa_name = inputcoalawanname.val();
    CoaExpenseAdjustment.debit = datadebitadjustment;
    CoaExpenseAdjustment.credit = datainputcreditadjustment;
    CoaExpenseAdjustment.coa_code = inputcoacodeadjustment.val();
    CoaExpenseAdjustment.coa_name = inputcoanameadjustment.val();

    checkExistCOA();
    UpdateTableCOA();

    // CoaExpenseAdjustment.debit = ;
  }

  function changetitlepay() {
    if (inputtype.val() == 'P') {
      titlepay.html('Pay To / For');
    } else {
      titlepay.html('Receive From');
    }
  }

  function checkInputAmountIsBalance() {
    let balance = 0;
    if (inputtype.val() == 'P') {
      balance =
        parseFloat(CoaExpenseOrOther.amount) +
        parseFloat(CoaExpenseAdjustment.debit) -
        parseToNominal(inputAmountCash.val()) +
        parseFloat(CoaExpenseAdjustment.credit);
    } else {
      balance =
        parseToNominal(inputAmountCash.val()) +
        parseFloat(CoaExpenseAdjustment.debit) -
        parseFloat(CoaExpenseOrOther.amount) +
        parseFloat(CoaExpenseAdjustment.credit);
    }

    return balance == 0;
  }

  function customValidation() {
    if (inputtransdate.val() == '') {
      inputtransdate.focus();
      showwarning('Transaction Date Cannot Be Empty');
      return false;
    }

    if (inputcoaforcashbank.val() == '') {
      inputcoaforcashbank.focus();
      showwarning('COA Cash/Bank Cannot Be Empty');
      return false;
    }

    if (parseToNominal(inputAmountCash.val()) == 0) {
      inputAmountCash.focus();
      showwarning('Amount Cash Cannot Be Zero');
      return false;
    }

    if (CoaExpenseOrOther.coa_code == '') {
      showwarning('Please Input Coa Expense Or Other');
      return false;
    }

    if (parseFloat(CoaExpenseOrOther.amount) == 0.0) {
      inputamountcoalawan.focus();
      showwarning('Amount Coa Expense Or Other Cannot Be Zero');
      return false;
    }

    if (CoaExpenseAdjustment.coa_code != '' && CoaExpenseAdjustment.debit == 0 && CoaExpenseAdjustment.credit == 0) {
      showwarning('Please Input Amount Debit Or Credit In Adjusment !');
      return false;
    }

    if (CoaExpenseAdjustment.coa_code != '' && inputdescadjusment.val() == '') {
      inputdescadjusment.focus();
      showwarning('Please Input Description Adjusment !');
      return false;
    }
    if (!checkInputAmountIsBalance()) {
      showwarning('The Amount Is Not Balance');
      return false;
    }

    return true;
  }

  function populatePostData() {
    PostData.COA_Cash = inputcoaforcashbank.val();
    PostData.CbpType = inputtype.val();
    PostData.amount_lawan = CoaExpenseOrOther.amount;
    PostData.transaction_date = inputtransdate.val();
    PostData.cash_no = '';
    PostData.coa_adjusment_debit = CoaExpenseAdjustment.debit;
    PostData.coa_adjustment = CoaExpenseAdjustment.coa_code;
    PostData.coa_adjustment_description = inputdescadjusment.val();
    PostData.coa_adjustment_kredit = CoaExpenseAdjustment.credit;
    PostData.coa_lawan = CoaExpenseOrOther.coa_code;
    PostData.description = inputdescription.val();
    PostData.ref_no = inputref.val();
    PostData.total_transaction = parseToNominal(inputAmountCash.val());
  }

  async function postAjax() {
    let urlRequest = '';

    if (updateMode) {
      let code = inputcashno.val();
      urlRequest = route('admin.editcashbook', code);
    } else {
      urlRequest = route('admin.addcashbook');
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

    formData.append('COA_Cash', PostData.COA_Cash);
    formData.append('CbpType', PostData.CbpType.trim());
    formData.append('amount_lawan', PostData.amount_lawan);
    formData.append('cash_no', PostData.cash_no);
    formData.append('coa_adjusment_debit', PostData.coa_adjusment_debit);
    formData.append('coa_adjustment', PostData.coa_adjustment);
    formData.append('coa_adjustment_description', PostData.coa_adjustment_description);
    formData.append('coa_adjustment_kredit', PostData.coa_adjustment_kredit);
    formData.append('coa_lawan', PostData.coa_lawan);
    formData.append('description', PostData.description);
    formData.append('ref_no', PostData.ref_no);
    formData.append('total_transaction', PostData.total_transaction);
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

  function UpdateTableCOA() {
    labelcoacodelawan.html(CoaExpenseOrOther.coa_code);
    labelcoanamelawan.html(CoaExpenseOrOther.coa_name);
    inputamountcoalawan.val(formatRupiah1(CoaExpenseOrOther.amount));

    labelcoacodeadjustment.html(CoaExpenseAdjustment.coa_code);
    labelcoanameadjustment.html(CoaExpenseAdjustment.coa_name);
    inputdebitadjustment.val(formatRupiah1(CoaExpenseAdjustment.debit));
    inputcreditadjustment.val(formatRupiah1(CoaExpenseAdjustment.credit));
  }

  function checkExistCOA() {
    if (CoaExpenseOrOther.coa_code == '' || CoaExpenseOrOther.coa_name == '') {
      inputamountcoalawan.attr('hidden', true);
      btndeletecoalawan.attr('hidden', true);
    } else {
      inputamountcoalawan.removeAttr('hidden');
      btndeletecoalawan.removeAttr('hidden');
    }

    if (CoaExpenseAdjustment.coa_code == '' || CoaExpenseAdjustment.coa_name == '') {
      inputdebitadjustment.attr('hidden', true);
      inputcreditadjustment.attr('hidden', true);
      btndeleteadjusment.attr('hidden', true);
    } else {
      inputdebitadjustment.removeAttr('hidden');
      inputcreditadjustment.removeAttr('hidden');
      btndeleteadjusment.removeAttr('hidden');
    }
  }

  function populateCOA() {
    // COA LAWAN

    if (CoaExpenseOrOther.coa_code !== inputcoalawancode.val()) {
      CoaExpenseOrOther.amount = inputAmountCash.val() == '' ? 0.0 : parseToNominal(inputAmountCash.val());
    }

    CoaExpenseOrOther.coa_code = inputcoalawancode.val();
    CoaExpenseOrOther.coa_name = inputcoalawanname.val();

    // COA ADjusment

    if (CoaExpenseAdjustment.coa_code !== inputcoacodeadjustment.val()) {
      CoaExpenseAdjustment.debit = 0.0;
      CoaExpenseAdjustment.credit = 0.0;
    }
    CoaExpenseAdjustment.coa_code = inputcoacodeadjustment.val();
    CoaExpenseAdjustment.coa_name = inputcoanameadjustment.val();

    checkExistCOA();
    UpdateTableCOA();
  }

  function deleteCoaLawan() {
    CoaExpenseOrOther.coa_code = '';
    CoaExpenseOrOther.coa_name = '';
    CoaExpenseOrOther.amount = 0.0;
    inputcoalawancode.val('');
    inputcoalawanname.val('');

    checkExistCOA();
    UpdateTableCOA();
  }

  function deleteCoaAdjustment() {
    CoaExpenseAdjustment.coa_code = '';
    CoaExpenseAdjustment.coa_name = '';
    CoaExpenseAdjustment.debit = 0.0;
    CoaExpenseAdjustment.credit = 0.0;
    inputcoacodeadjustment.val('');
    inputcoanameadjustment.val('');

    checkExistCOA();
    UpdateTableCOA();
  }
  // ====================================

  // Event And Crud
  // ====================================

  // Delete List
  $(document).on('click', '.btndeletecoalawan', function (e) {
    deleteCoaLawan();
  });

  $(document).on('click', '.btndeleteadjusment', function (e) {
    deleteCoaAdjustment();
  });

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
    $(this).val(formatRupiah1($(this).val()));
  });

  // Input Pada inputAmountCash ketika pertama kali disorot
  $(document).on('focusin', '.inputamountcoalawan', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });
  // Ketika Mengetikkan Didalam  inputAmountCash
  $(document).on('keyup', '.inputamountcoalawan', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  // Ketika FOcusout  Didalam  inputAmountCash
  $(document).on('blur', '.inputamountcoalawan', function (event) {
    let amount = $(this).val() == '' ? 0 : parseFloat($(this).val()).toFixed(2);
    CoaExpenseOrOther.amount = amount;
    UpdateTableCOA();
  });

  $(document).on('focusin', '.inputdebitadjustment', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });
  // Ketika Mengetikkan Didalam  inputAmountCash
  $(document).on('keyup', '.inputdebitadjustment', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  // Ketika FOcusout  Didalam  inputAmountCash
  $(document).on('blur', '.inputdebitadjustment', function (event) {
    let amount = $(this).val() == '' ? 0 : parseFloat($(this).val()).toFixed(2);
    CoaExpenseAdjustment.debit = amount;
    if (amount > 0) {
      CoaExpenseAdjustment.credit = 0;
    }
    UpdateTableCOA();
  });

  $(document).on('focusin', '.inputcreditadjustment', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });
  // Ketika Mengetikkan Didalam  inputAmountCash
  $(document).on('keyup', '.inputcreditadjustment', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  // Ketika FOcusout  Didalam  inputAmountCash
  $(document).on('blur', '.inputcreditadjustment', function (event) {
    let amount = $(this).val() == '' ? 0 : parseFloat($(this).val()).toFixed(2);
    CoaExpenseAdjustment.credit = amount;
    if (amount > 0) {
      CoaExpenseAdjustment.debit = 0;
    }
    UpdateTableCOA();
  });

  $(document).on('change', '.inputtype', function (event) {
    changetitlepay();
  });

  // Submit Button
  $(document).on('click', '.submitbtn', async function () {
    if (validateInput(dataInput, customValidation)) {
      $(this).find('span').html('Submitting...');
      $(this).prop('disabled', true);
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.cashbook');
      if (response) {
        window.location.href = urlRedirect;
      } else {
        updateMode ? $(this).find('span').html('Update') : $(this).find('span').html('Save');
        $(this).prop('disabled', false);
      }
    }
  });

  $('#modalCOASearch').on('hidden.bs.modal', async function (e) {
    populateCOA();
  });
  // ===================================
});
