import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { showerror } from '../jqueryconfirm.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import { showconfirmdelete, showconfirmapprove } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import daterangeInitiator from '../daterangeinitiator.js';
import initiatedtp from '../datepickerinitiator.js';
import managedate from '../managedate.js';

$(document).ready(function () {
  const Date = new managedate();

  const dtpstarttrans = $('#dtpstarttrans');
  const dtplasttrans = $('#dtplasttrans');
  const inputstartdatetrans = $('.inputstartdatetrans');
  const inputlastdatetrans = $('.inputlastdatetrans');
  const inputitemcode = $('.inputitemcode');
  const inputitemname = $('.inputitemname');
  const modalItemSearch = $('#modalItemSearch');
  const btnprint = $('.btnprint');
  const startMONTH = Date.getFirstMonth();
  const lastMONTH = Date.getLastMonth();

  // INISIASI DATEPICKER dan load function
  // =============================================================

  initiatedtp(dtpstarttrans);
  initiatedtp(dtplasttrans);

  inputstartdatetrans.val(startMONTH);
  inputlastdatetrans.val(lastMONTH);

  checkValue();

  // =============================================================

  // Function
  // ==============================================================

  function checkValue() {
    if (inputitemcode.val() == '' || inputitemname.val() == '' || inputstartdatetrans.val() == '' || inputlastdatetrans.val() == '') {
      btnprint.prop('disabled', true);
    } else {
      btnprint.prop('disabled', false);
    }
  }

  // =============================================================

  // CRUD and EVENT
  // ==============================================================

  // Ketika Sesudah Memilih Item Di Modal Item Search
  $(document).on('click', '.selectitembtn', function () {
    let code = $(this).data('code');
    let name = $(this).data('name');

    inputitemcode.val(code);
    inputitemname.val(name);

    modalItemSearch.modal('hide');
    checkValue();
  });

  $(document).on('blur', '.inputstartdatetrans', function () {
    checkValue();
  });
  $(document).on('blur', '.inputlastdatetrans', function () {
    checkValue();
  });

  // Click Print Button
  $(document).on('click', '.btnprint', function () {
    let url = route('admin.printstockcard');

    window.open(
      url +
        `?startdate=${moment(inputstartdatetrans.val(), 'DD/MM/YYYY').format('YYYY-MM-DD')}&enddate=${moment(
          inputlastdatetrans.val(),
          'DD/MM/YYYY'
        ).format('YYYY-MM-DD')}&itemcode=${inputitemcode.val()}`,
      '_blank'
    );
  });

  // =============================================================
});
