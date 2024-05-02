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
  // =========================================

  // INISIASI DATATABLE
  // ======================================================
  var getRemindStock = route('admin.tablestockreminder');
  const tableName = '.stockremindtable';
  const method = 'post';
  const columns = [
    { data: 'item_code', name: 'item_code', title: 'Item Code', searchable: true, orderable: true },
    { data: 'item_name', name: 'item_name', title: 'Item Name', searchable: true, orderable: true },
    { data: 'item_category', name: 'item_category', title: 'Category', searchable: true, orderable: false },
    { data: 'min_stock', name: 'min_stock', title: 'Min Stock', searchable: false, orderable: false },
    { data: 'current_stock', name: 'current_stock', title: 'Current Stock', searchable: false, orderable: false },
    { data: 'unit_code', name: 'unit_code', title: 'Unit Code', searchable: true, orderable: false }
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
  reloadTable(method, tableName, columns, getRemindStock);
  // =================================================================

  // Event
  // =============================================================

  // check perubahan value datepicker TRANS DATE
  $(document).on('change', '.datetimepicker-input', function () {
    updateDTPTransDateValue();
  });

  // Click Print Button
  $(document).on('click', '.btnprint', function () {
    let url = route('admin.printstockreminder');

    window.open(url, '_blank');
  });

  // =============================================================

  // Trigger Toast
  // ==================================
  checkNotifMessage();
});
