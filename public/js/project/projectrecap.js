import AjaxRequest from '../ajaxrequest.js';
import { showwarning, showerror } from '../jqueryconfirm.js';
import managedate from '../managedate.js';
import initiatedtp from '../datepickerinitiator.js';
$(document).ready(function () {
  const Date = new managedate();
  const customerCode = $('.customerCode');
  const customerName = $('.customerName');
  const statusSelect = $('#statusSelect');
  const iscustomerrequired = $('.iscustomerrequired'); //Tdanda Bintang merah di label customer

  const dtpstarttrans = $('#dtpstarttrans');
  const dtplasttrans = $('#dtplasttrans');
  const inputstartdatetrans = $('.inputstartdatetrans');
  const inputlastdatetrans = $('.inputlastdatetrans');

  const startMONTH = Date.getFirstMonth();
  const lastMONTH = Date.getLastMonth();

  let supplyData = {
    status: '',
    startTransDate: Date.getFirstMonth('YYYY-MM-DD'),
    lastTransDate: Date.getLastMonth('YYYY-MM-DD'),
    customerCode: ''
  };

  // Function and Subroutines
  // ========================================================

  function firstLoad() {
    // INISIASI DATEPICKER
    // ---------------------------------------------
    initiatedtp(dtpstarttrans);
    initiatedtp(dtplasttrans);

    inputstartdatetrans.val(startMONTH);
    inputlastdatetrans.val(lastMONTH);

    // --------------------------------------------

    iscustomerrequired.html('');
  }
  function validate() {
    if (inputstartdatetrans.val() == '') {
      inputstartdatetrans.focus();
      showwarning('Input Cannot Be Empty !');
      return false;
    }
    if (inputlastdatetrans.val() == '') {
      inputlastdatetrans.focus();
      showwarning('Input Cannot Be Empty !');
      return false;
    }

    return true;
  }
  function printData() {
    let urlRoute = route('admin.printprojectrecap');
    supplyData.customerCode = customerCode.val();

    urlRoute =
      urlRoute +
      `?customercode=${supplyData.customerCode}&statusCode=${supplyData.status}&firstDate=${supplyData.startTransDate}&lastDate=${supplyData.lastTransDate}`;

    window.open(urlRoute, '_blank');
  }

  // ========================================================

  // CRUD and Event
  // ==========================================================
  $(document).on('change', '#statusSelect', function () {
    let val = $(this).val();
    supplyData.status = val;
  });

  $(document).on('change', '.inputstartdatetrans', function () {
    let val = $(this).val();
    supplyData.startTransDate = moment(val, 'DD/MM/YYYY').format('YYYY-MM-DD');
  });

  $(document).on('change', '.inputlastdatetrans', function () {
    let val = $(this).val();
    supplyData.lastTransDate = moment(val, 'DD/MM/YYYY').format('YYYY-MM-DD');
  });

  $(document).on('click', '.btnprint', function () {
    if (validate()) {
      printData();
    }
  });

  // ==========================================================

  firstLoad();
});
