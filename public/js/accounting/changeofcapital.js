import tableInitiator from '../tableinitiator.js';
import checkNotifMessage from '../checkNotif.js';
import AjaxRequest from '../ajaxrequest.js';
import { showconfirmdelete, showwarning } from '../jqueryconfirm.js';
import managedate from '../managedate.js';
import initiatedtp from '../datepickerinitiator.js';
import initiatedtpmonth from '../datepickerinitiatormonth.js';

$(document).ready(async function () {
  const tablelistcoa = $('.tablelistcoa tbody');
  const coaLIST = $('#coa_code');
  const Date = new managedate();
  const startMONTH = Date.getFirstMonth();
  const lastMONTH = Date.getLastMonth();
  const dtpstarttrans = $('#dtpstarttrans');
  const dtplasttrans = $('#dtplasttrans');
  const inputstartdatetrans = $('.inputstartdatetrans');
  const inputlastdatetrans = $('.inputlastdatetrans');

  const supplyData = {
    startDate: startMONTH,
    endDate: lastMONTH
  };

  // INISIASI DATEPICKER
  // ---------------------------------------------
  initiatedtpmonth(dtpstarttrans);
  initiatedtpmonth(dtplasttrans);

  inputstartdatetrans.val(moment(startMONTH, 'DD/MM/YYYY').format('MMMM/YYYY'));
  inputlastdatetrans.val(moment(lastMONTH, 'DD/MM/YYYY').format('MMMM/YYYY'));

  function printCOA() {
    let urlRoute = route('admin.');

    urlRoute =
      urlRoute +
      `?startDate=${moment(supplyData.startDate, 'DD/MM/YYYY').format('YYYY-MM-DD')}&endDate=${moment(
        supplyData.endDate,
        'DD/MM/YYYY'
      ).format('YYYY-MM-DD')}`;

    window.open(urlRoute, '_blank');
  }

  // FN VALIDATE
  function validate() {
    if (inputstartdatetrans.val() == '') {
      inputlastdatetrans.focus();
      showwarning('TransDate Cannot Be Blank!');
      return false;
    }
    if (inputlastdatetrans.val() == '') {
      inputlastdatetrans.focus();
      showwarning('TransDate! Cannot Be Blank');
      return false;
    }

    return true;
  }

  $(document).on('click', '.btnprint', function () {
    if (validate()) {
      printCOA();
    }
  });

  $(document).on('change', '.inputstartdatetrans', function () {
    let val = $(this).val();
    supplyData.startDate = Date.getFirstDateMonthFromDate(val);
    console.log(supplyData);
  });

  $(document).on('change', '.inputlastdatetrans', function () {
    let val = $(this).val();
    supplyData.endDate = Date.getLastDateMonthFromDate(val);
    console.log(supplyData);
  });

  // Trigger Toast
  checkNotifMessage();
});
