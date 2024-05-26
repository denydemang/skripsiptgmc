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
  const listDetailPurchase = $('.listbb tbody');
  const modalDetailPurchase = $('#modal-detailpurchase');
  const modalTitle = $('.titleview');
  const titledetail = $('.title-detail');
  const iscustomerrequired = $('.iscustomerrequired'); //Tdanda Bintang merah di label supplier
  const customerName = $('.customerName');
  const customerCode = $('.customerCode');
  const startMONTH = Date.getFirstMonth();
  const lastMONTH = Date.getLastMonth();

  let supplyData = {
    is_approve: '',
    paystatus: '',
    startDate: startMONTH,
    endDate: lastMONTH
  };
  // =========================================

  // INISIASI DATATABLE
  // ======================================================
  var getARTable = route('admin.tablear');
  const tableName = '.artable';
  const method = 'post';

  const columns = [
    { data: 'action', name: 'action', title: 'Actions', searchable: false, orderable: false },
    { data: 'adr_no', name: 'adr_no', title: 'ADR No', searchable: true },
    { data: 'transaction_date', name: 'transaction_date', title: 'Transaction Date', searchable: false },
    { data: 'customer_code', name: 'customer_code', title: 'Customer Code', searchable: true },
    { data: 'customer_name', name: 'customer_name', title: 'Customer Name', searchable: true },
    { data: 'coa_debit', name: 'coa_debit', title: 'Coa Debit', searchable: true },
    { data: 'coa_kredit', name: 'coa_kredit', title: 'Coa Credit', searchable: true },
    { data: 'deposit_amount', name: 'deposit_amount', title: 'Deposit Amount', searchable: false },
    { data: 'deposit_allocation', name: 'deposit_allocation', title: 'Deposit Allocation', searchable: false },
    { data: 'description', name: 'description', title: 'Description', searchable: true },
    { data: 'is_approve', name: 'is_approve', title: 'Approve', searchable: false },
    { data: 'approved_by', name: 'approved_by', title: 'Approved By', searchable: true },
    { data: 'created_by', name: 'created_by', title: 'Created By', searchable: true },
    { data: 'updated_by', name: 'updated_by', title: 'Updated By', searchable: true }
  ];

  function ShowTable(method, tableName, columns, url, data = {}, cellFunc = function () {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data, cellFunc);
    Table.showTable();
  }
  function DestroyTable(method, tableName, columns, url, data = {}, cellFunc = function () {}) {
    const Table = new tableInitiator(method, tableName, columns, url, data, cellFunc);
    Table.destroyTable();
  }

  function reloadTable(method, tableName, columns, url, data = {}, cellFunc = function () {}) {
    DestroyTable(method, tableName, columns, url, data, cellFunc);
    ShowTable(method, tableName, columns, url, data, cellFunc);
  }
  reloadTable(method, tableName, columns, getARTable, supplyData);
  // =================================================================

  // INISIASI DATEPICKER
  // =============================================================

  initiatedtp(dtpstarttrans);
  initiatedtp(dtplasttrans);

  inputstartdatetrans.val(supplyData.startDate);
  inputlastdatetrans.val(supplyData.endDate);
  iscustomerrequired.html('');

  // =============================================================

  // Function and subroutines
  // =============================================================

  async function getData(code = '') {
    const urlRequest = route('admin.detailpurchase', code);
    const method = 'POST';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      showerror(error);
      return null;
    }
  }

  function updateDTPTransDateValue() {
    let startTrans = inputstartdatetrans.val();
    let lastTrans = inputlastdatetrans.val();

    supplyData.startDate = startTrans;
    supplyData.endDate = lastTrans;

    reloadTable(method, tableName, columns, getARTable, supplyData);
  }

  function deleteData(code, name) {
    const urlRequest = route('admin.deletear', code);
    window.location.href = urlRequest;
  }

  function approveData(code, name) {
    const urlRequest = route('admin.approvear', code);
    window.location.href = urlRequest;
  }

  function printRecap() {
    let urlRoute = route('admin.printrecapar');

    urlRoute =
      urlRoute +
      `?customercode=${customerCode.val()}&startDate=${moment(supplyData.startDate, 'DD/MM/YYYY').format('YYYY-MM-DD')}&endDate=${moment(
        supplyData.endDate,
        'DD/MM/YYYY'
      ).format('YYYY-MM-DD')}&is_approve=${supplyData.is_approve}`;

    window.open(urlRoute, '_blank');
  }

  // =============================================================

  // Event
  // =============================================================

  // check perubahan value datepicker TRANS DATE
  $(document).on('change', '.datetimepicker-input', function () {
    updateDTPTransDateValue();
  });

  // click print recap
  $(document).on('click', '.btnprintrecap', function () {
    printRecap();
  });

  // change Select Approve Status
  $(document).on('change', '#statusSelectApprove', function () {
    let val = $(this).val();
    supplyData.is_approve = val;

    reloadTable(method, tableName, columns, getARTable, supplyData);
  });

  // Click Delete Button
  $(document).on('click', '.deletebtn', function () {
    let Code = $(this).data('code');
    showconfirmdelete(Code, Code, deleteData, 'Advanced Receipt :');
  });

  // Click Approve Button
  $(document).on('click', '.approvebtn', function () {
    let Code = $(this).data('code');
    showconfirmapprove(Code, Code, approveData, 'Advanced Receipt');
  });

  // Click Edit Button
  $(document).on('click', '.editbtn', function () {
    let code = $(this).data('code');
    let url = route('admin.editAdvancedReceiptView', code);

    window.location.href = url;
  });

  // =============================================================

  // Trigger Toast
  // ==================================
  checkNotifMessage();
});
