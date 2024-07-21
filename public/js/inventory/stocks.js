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
  var getIINTable = route('admin.tablestocks');
  const tableName = '.stockstable';
  const method = 'post';
  const columns = [
    { data: 'item_code', name: 'item_code', title: 'Item Code', searchable: true, orderable: true },
    { data: 'item_name', name: 'item_name', title: 'Item Name', searchable: true, orderable: true },
    { data: 'item_category', name: 'item_category', title: 'Category', searchable: true, orderable: false },
    { data: 'unit_code', name: 'unit_code', title: 'Unit Code', searchable: true, orderable: false },
    { data: 'actual_stock', name: 'actual_stock', title: 'Stock IN', searchable: false, orderable: false },
    { data: 'used_stock', name: 'used_stock', title: 'Stock Out', searchable: false, orderable: false },
    { data: 'available_stock', name: 'available_stock', title: 'Available Stock', searchable: false, orderable: false },
    { data: 'cogs', name: 'cogs', title: 'COGS', searchable: false, orderable: false }
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

  // ShowTable(method, tableName, columns, url, (data = {}));
  ShowTable(method, tableName, columns, getIINTable, supplyData);
  // reloadTable(method, tableName, columns, getIINTable, supplyData);
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

  // Click Print Button
  $(document).on('click', '.btnprint', function () {
    let url = route('admin.printstock');

    window.open(url, '_blank');
  });

  // =============================================================

  // Trigger Toast
  // ==================================
  checkNotifMessage();
});
