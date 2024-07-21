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
  // Inputan element dan inisiasi property
  // =======================================
  const Date = new managedate();

  const dtpstarttrans = $('#dtpstarttrans');
  const dtplasttrans = $('#dtplasttrans');
  const inputstartdatetrans = $('.inputstartdatetrans');
  const inputlastdatetrans = $('.inputlastdatetrans');
  const startMONTH = Date.getFirstMonth();
  const lastMONTH = Date.getLastMonth();

  let supplyData = {
    is_approve: '',
    is_purchased: '',
    startDate: startMONTH,
    endDate: lastMONTH,
    dateNeedStart: '',
    dateNeedEnd: ''
  };
  // =========================================

  // INISIASI DATATABLE
  // ======================================================
  var getIINTable = route('admin.tableiin');
  const tableName = '.iintable';
  const method = 'post';
  const columns = [
    { data: 'iinno', name: 'iinno', title: 'IIN No', searchable: true, orderable: true },
    { data: 'item_date', name: 'item_date', title: 'Date Item In', searchable: true, orderable: true },
    { data: 'ref_no', name: 'ref_no', title: 'Reference', searchable: true, orderable: false },
    { data: 'item_code', name: 'item_code', title: 'Item Code', searchable: true, orderable: true },
    { data: 'item_name', name: 'item_name', title: 'Item Name', searchable: true, orderable: true },
    { data: 'item_category', name: 'item_category', title: 'Category', searchable: true, orderable: false },
    { data: 'unit_code', name: 'unit_code', title: 'Unit Code', searchable: true, orderable: false },
    { data: 'qty', name: 'qty', title: 'Qty', searchable: false, orderable: false },
    { data: 'cogs', name: 'cogs', title: 'Price', searchable: false, orderable: false },
    { data: 'total', name: 'total', title: 'Total', searchable: false, orderable: false },
    { data: 'coa_code', name: 'coa_code', title: 'COA code', searchable: false, orderable: false }
  ];

  function ShowTable(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.showTable();
  }
  function DestroyTable(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.destroyTable();
  }

  function reloadTable(method, tableName, columns, url, data = {}) {
    DestroyTable(method, tableName, columns, url, data);
    ShowTable(method, tableName, columns, url, data);
  }
  reloadTable(method, tableName, columns, getIINTable, supplyData);
  // =================================================================

  // INISIASI DATEPICKER
  // =============================================================

  initiatedtp(dtpstarttrans);
  initiatedtp(dtplasttrans);

  inputstartdatetrans.val(supplyData.startDate);
  inputlastdatetrans.val(supplyData.endDate);

  // =============================================================

  // Function and subroutines
  // =============================================================

  function updateDTPTransDateValue() {
    let startTrans = inputstartdatetrans.val();
    let lastTrans = inputlastdatetrans.val();

    supplyData.startDate = startTrans;
    supplyData.endDate = lastTrans;

    reloadTable(method, tableName, columns, getIINTable, supplyData);
  }
  // =============================================================

  // Event
  // =============================================================

  // check perubahan value datepicker TRANS DATE
  $(document).on('change', '.datetimepicker-input', function () {
    updateDTPTransDateValue();
  });

  // Click Edit Button
  $(document).on('click', '.editbtn', function () {
    let code = $(this).data('code');
    let url = route('admin.editprview', code);

    window.location.href = url;
  });

  // Click Print Button
  $(document).on('click', '.btnprint', function () {
    let url = route('admin.printIIN');

    window.open(
      url +
        `?firstDate=${moment(supplyData.startDate, 'DD/MM/YYYY').format('YYYY-MM-DD')}&lastDate=${moment(
          supplyData.endDate,
          'DD/MM/YYYY'
        ).format('YYYY-MM-DD')}`,
      '_blank'
    );
  });

  // =============================================================

  // Trigger Toast
  // ==================================
  checkNotifMessage();
});
