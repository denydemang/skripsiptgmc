import tableInitiator from '../tableinitiator.js';
import initiatedtp from '../datepickerinitiator.js';
import managedate from '../managedate.js';

$(document).ready(function () {
  localStorage.clear();

  const modalPRSearch = $('#modalPRSearch');
  const Date = new managedate();

  const dtpstarttrans = $('#dtpstarttrans');
  const dtplasttrans = $('#dtplasttrans');
  const inputstartdatetrans = $('.inputstartdatetrans');
  const inputlastdatetrans = $('.inputlastdatetrans');
  const startMONTH = Date.getFirstMonth();
  const lastMONTH = Date.getLastMonth();

  let supplyData = {
    startDate: startMONTH,
    endDate: lastMONTH
  };

  // INISIASI DATATABLE Customer Modal Search
  // ----------------------------------------------------------------
  var getDataPR = route('admin.PRGetForModal');
  const tablePRName = '.prsearchtable';
  const methodGetPR = 'get';
  const columnsPR = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false, width: '10%' },
    { data: 'pr_no', name: 'pr_no', title: 'Purchase Request No', searchable: true },
    { data: 'transaction_date', name: 'transaction_date', title: 'Transaction Date', searchable: false },
    { data: 'pic_name', name: 'pic_name', title: 'PIC Name', searchable: true },
    { data: 'date_need', name: 'date_need', title: 'Date Need', searchable: true }
  ];

  function ShowTablePRSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.showTable();
  }
  function DestroyTablePRSearch(method, tableName, columns, url, data = {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data);
    Table.destroyTable();
  }

  function reloadTablePRSearch(method, tableName, columns, url, data = {}) {
    DestroyTablePRSearch(method, tableName, columns, url, data);
    ShowTablePRSearch(method, tableName, columns, url, data);
  }
  // ===========================================================

  // INISIASI DATEPICKER
  // =============================================================

  initiatedtp(dtpstarttrans);
  initiatedtp(dtplasttrans);
  inputstartdatetrans.val(supplyData.startDate);
  inputlastdatetrans.val(supplyData.endDate);
  // =============================================================

  // Function
  // ===============================================

  function updateDTPTransDateValue() {
    let startTrans = inputstartdatetrans.val();
    let lastTrans = inputlastdatetrans.val();

    supplyData.startDate = startTrans;
    supplyData.endDate = lastTrans;

    reloadTablePRSearch(methodGetPR, tablePRName, columnsPR, getDataPR, supplyData);
  }

  // ================================================

  // CRUD AND EVENT
  // ------------------------------------------------------------

  //Click PR Search
  $(document).on('click', '.btnsearchpr', function () {
    modalPRSearch.modal('show');
    reloadTablePRSearch(methodGetPR, tablePRName, columnsPR, getDataPR, supplyData);
  });

  // check perubahan value datepicker TRANS DATE
  $(document).on('change', '.datetimepicker-input', function () {
    updateDTPTransDateValue();
  });

  // //Select Customer
  // $(document).on('click', '.selectsupplierbtn', function () {
  //   supplierCode.val($(this).data('code'));
  //   supplierName.val($(this).data('name'));
  //   modalPRSearch.modal('hide');

  //   // Populate in localstorage
  //   localStorage.setItem('supplierName', supplierName.val());
  //   localStorage.setItem('supplierCode', supplierCode.val());
  // });
});
