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
  const inputinvoiceno = $('.inputinvoiceno');
  const inputpaymentterm = $('.inputpaymentterm');
  const dtptransdate = $('#dtptransdate');
  const inputtransdate = $('.inputtransdate');
  const inputbapno = $('.inputbapno');
  const inputbappno = $('.inputbappno');
  const inputsppno = $('.inputsppno');
  const customercode = $('.customerCode');
  const customername = $('.customerName');
  const inputcoacodeforrevenue = $('.inputcoacodeforrevenue');
  const inputcoanameforrevenue = $('.inputcoanameforrevenue');
  const inputprojectrealisationno = $('.inputprojectrealisationno');
  const inputprojectname = $('.inputprojectname');
  const inputdescription = $('.inputdescription');
  const labeltotal = $('.labeltotal');
  const inputpercentppn = $('.inputpercentppn');
  const inputppnamount = $('.inputppnamount');
  const labelgrandtotal = $('.labelgrandtotal');

  // End Html Input

  // Property when in update mode
  const datacustomercode = $('.datacustomercode').data('customercode');
  const datacustomerName = $('.datacustomerName').data('customername');
  const datapercentppn = $('.datapercentppn').data('percentppn');
  const dataamountppn = $('.dataamountppn');
  const datapaymentterm = $('.datapaymentterm').data('paymentterm');
  // End Property when in update mode

  // State
  const updateMode = route().current() == 'admin.editInvoiceView';

  //Validation Input
  let dataInput = [];

  // Data For Send TO Controller
  let PostData = {
    invoice_no: '',
    transaction_date: '',
    customer_code: '',
    project_realisation_code: '',
    bap_no: '',
    bapp_no: '',
    spp_no: '',
    coa_revenue: '',
    total: 0.0,
    percent_ppn: 0.0,
    ppn_amount: 0.0,
    grand_total: 0.0,
    payment_term_code: '',
    description: ''
  };

  let transAmount = {
    total: 0,
    ppnpercent: 0,
    ppnamount: 0,
    grand_total: 0
  };
  let tampungItem = [];
  // ====================================

  // Inisiasi Datepircker dan input
  // ======================================
  initiatedtp(dtptransdate);
  if (updateMode) {
    prepareEdit();
  } else {
    inputtransdate.val(Date.getNowDate());
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

  function updatePPNAmount(percentAmount) {
    transAmount.ppnpercent = parseFloat(percentAmount);

    calculateAmountTrans();
  }

  function calculateAmountTrans() {
    transAmount.total = parseFloat(parseToNominal(labeltotal.html())).toFixed(2);

    transAmount.ppnamount = (parseFloat(transAmount.total) * (parseFloat(transAmount.ppnpercent) / 100)).toFixed(2);
    transAmount.grand_total = parseFloat(transAmount.total) + parseFloat(transAmount.ppnamount);
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
    inputtransdate.val(moment(inputtransdate.data('transdate')).format('DD/MM/YYYY'));
    customercode.val(datacustomercode);
    customername.val(datacustomerName);
    inputpaymentterm.val(datapaymentterm);

    updatePPNAmount(parseFloat(datapercentppn) * 100);
  }

  function customValidation() {
    if (inputtransdate.val() == '') {
      inputtransdate.focus();
      showwarning('Transaction Date Cannot Be Empty');
      return false;
    }
    if (customercode.val() == '') {
      customercode.focus();
      showwarning('Customer Code Cannot Be Empty');
      return false;
    }
    if (inputcoacodeforrevenue.val() == '') {
      inputcoacodeforrevenue.focus();
      showwarning('COA Revenue Cannot Be Empty');
      return false;
    }

    if (inputprojectrealisationno.val() == '') {
      inputprojectrealisationno.focus();
      showwarning('Project Realisation Cannot Be Empty');
      return false;
    }

    if (inputdescription.val() == '') {
      inputdescription.focus();
      showwarning('Description Cannot Be Empty');
      return false;
    }

    return true;
  }

  function setTransAmount() {
    labeltotal.html(formatRupiah1(transAmount.total));
    labelgrandtotal.html(formatRupiah1(transAmount.grand_total));
    inputpercentppn.val(parseFloat(transAmount.ppnpercent));
    inputppnamount.val(formatRupiah1(transAmount.ppnamount));
  }

  function populatePostData() {
    PostData.invoice_no = '';
    PostData.description = inputdescription.val();
    PostData.grand_total = parseFloat(transAmount.grand_total);
    PostData.payment_term_code = inputpaymentterm.val();
    PostData.percent_ppn = transAmount.ppnpercent / 100;
    PostData.ppn_amount = transAmount.ppnamount;
    PostData.bap_no = inputbapno.val().trim();
    PostData.bapp_no = inputbappno.val().trim();
    PostData.spp_no = inputsppno.val().trim();
    PostData.coa_revenue = inputcoacodeforrevenue.val();
    PostData.customer_code = customercode.val();
    PostData.project_realisation_code = inputprojectrealisationno.val();
    PostData.total = transAmount.total;
    PostData.transaction_date = inputtransdate.val();
  }

  async function postAjax() {
    let urlRequest = '';

    if (updateMode) {
      let code = inputinvoiceno.val();
      urlRequest = route('admin.editinvoice', code);
    } else {
      urlRequest = route('admin.addinvoice');
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

    formData.append('bap_no', PostData.bap_no);
    formData.append('bapp_no', PostData.bapp_no);
    formData.append('coa_revenue', PostData.coa_revenue);
    formData.append('customer_code', PostData.customer_code);
    formData.append('description', PostData.description);
    formData.append('grand_total', PostData.grand_total);
    formData.append('invoice_no', PostData.invoice_no);
    formData.append('payment_term_code', PostData.payment_term_code);
    formData.append('percent_ppn', PostData.percent_ppn);
    formData.append('ppn_amount', PostData.ppn_amount);
    formData.append('project_realisation_code', PostData.project_realisation_code);
    formData.append('spp_no', PostData.spp_no);
    formData.append('total', PostData.total);
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

  // Input Pada Percent PPN ketika pertama kali disorot
  $(document).on('focusin', '.inputpercentppn', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  $(document).on('blur', '.inputpercentppn', function (e) {
    if ($(this).val() < 0 || $(this).val() == '') {
      $(this).val(0);
    }
    updatePPNAmount(parseFloat($(this).val()).toFixed(2));
  });

  // Submit Button
  $(document).on('click', '.submitbtn', async function () {
    if (validateInput(dataInput, customValidation)) {
      $(this).find('span').html('Submitting...');
      $(this).prop('disabled', true);
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.invoice');
      if (response) {
        window.location.href = urlRedirect;
      } else {
        updateMode ? $(this).find('span').html('Update') : $(this).find('span').html('Save');
        $(this).prop('disabled', false);
      }
    }
  });

  $('#modalProjectRealisationSearch').on('hidden.bs.modal', async function (e) {
    calculateAmountTrans();
    setTransAmount();
  });
  // ===================================
});
