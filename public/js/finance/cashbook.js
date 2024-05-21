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
  const Cbdetailno = $('.Cbdetailno');
  const inputstartdatetrans = $('.inputstartdatetrans');
  const inputlastdatetrans = $('.inputlastdatetrans');
  const listDetailPurchase = $('.listbb tbody');
  const modalDetailPurchase = $('#modal-detailpurchase');
  const modalTitle = $('.titleview');
  const titledetail = $('.title-detail');
  const issupplierrequired = $('.issupplierrequired'); //Tdanda Bintang merah di label supplier
  const supplierName = $('.supplierName');
  const supplierCode = $('.supplierCode');
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
  var getPRTable1 = '';
  var getPRTable2 = '';
  var getPRTable = route('admin.tablecashbook');
  const tableName = '.cashbooktable';
  const tableName1 = '.cashbooktable1';
  const tableName2 = '.cashbooktable2';
  const method = 'post';
  const columns = [
    { data: 'action', name: 'action', title: 'Actions', searchable: false, orderable: false },
    { data: 'cash_no', name: 'cash_no', title: 'Cash No', searchable: true },
    { data: 'transaction_date', name: 'transaction_date', title: 'Transaction Date', searchable: false },
    { data: 'COA_Cash', name: 'COA_Cash', title: 'COA Cash', searchable: true },
    { data: 'coa_name', name: 'coa_name', title: 'COA Name', searchable: true },
    { data: 'ref', name: 'ref', title: 'Ref No', searchable: true },
    { data: 'total_transaction', name: 'total_transaction', title: 'Total Transaction', searchable: false },
    { data: 'description', name: 'description', title: 'Description', searchable: true },
    { data: 'CbpType', name: 'CbpType', title: 'Type', searchable: true },
    { data: 'is_approve', name: 'is_approve', title: 'Approve', searchable: false },
    { data: 'approved_by', name: 'approved_by', title: 'Approved By', searchable: true },
    { data: 'created_by', name: 'created_by', title: 'Created By', searchable: true },
    { data: 'updated_by', name: 'updated_by', title: 'Updated By', searchable: true }
  ];

  const columns2 = [
    { data: 'coa', name: 'coa', title: 'COA', searchable: true },
    { data: 'coa_name', name: 'coa_name', title: 'COA Name', searchable: true },
    { data: 'description', name: 'description', title: 'Description', searchable: true },
    { data: 'amount', name: 'amount', title: 'Amount', searchable: false },
    { data: 'created_by', name: 'created_by', title: 'Created By', searchable: true },
    { data: 'updated_by', name: 'updated_by', title: 'Updated By', searchable: true }
  ];

  const columns3 = [
    { data: 'COA', name: 'COA', title: 'COA', searchable: true },
    { data: 'coa_name', name: 'coa_name', title: 'COA Name', searchable: true },
    { data: 'description', name: 'description', title: 'Description', searchable: true },
    { data: 'debit', name: 'debit', title: 'Debit', searchable: false },
    { data: 'credit', name: 'credit', title: 'Credit', searchable: false },
    { data: 'created_by', name: 'created_by', title: 'Created By', searchable: true },
    { data: 'updated_by', name: 'updated_by', title: 'Updated By', searchable: true }
  ];

  function ShowTable(method, tableName, columns, url, data = {}, cellFunc = function () {}, search = true) {
    const Table = new tableInitiator(method, tableName, columns, url, data, cellFunc, search);
    Table.showTable();
  }
  function DestroyTable(method, tableName, columns, url, data = {}, cellFunc = function () {}, search = true) {
    const Table = new tableInitiator(method, tableName, columns, url, data, cellFunc, search);
    Table.destroyTable();
  }

  function reloadTable(method, tableName, columns, url, data = {}, cellFunc = function () {}, search = true) {
    DestroyTable(method, tableName, columns, url, data, cellFunc, search);
    ShowTable(method, tableName, columns, url, data, cellFunc, search);
  }

  reloadTable(method, tableName, columns, getPRTable, supplyData, null, true);
  getDetailCB('---');
  getDetailCB2('---');
  // =================================================================

  // INISIASI DATEPICKER
  // =============================================================

  initiatedtp(dtpstarttrans);
  initiatedtp(dtplasttrans);

  inputstartdatetrans.val(supplyData.startDate);
  inputlastdatetrans.val(supplyData.endDate);
  issupplierrequired.html('');

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
    getDetailCB('---');
    getDetailCB2('---');
    reloadTable(method, tableName, columns, getPRTable, supplyData);
  }

  function deleteData(code, name) {
    const urlRequest = route('admin.deletepayment', code);
    window.location.href = urlRequest;
  }

  function approveData(code, name) {
    const urlRequest = route('admin.approvepayment', code);
    window.location.href = urlRequest;
  }

  function populateData(DetailPurchase = []) {
    // Populate Title Detail
    let purchaseNO = DetailPurchase[0].purchase_no;

    titledetail.html(`Purchase No: ${purchaseNO}`);
    let htmlDetailPurchase = '';
    let counterDetailPurchase = 1;
    let grand_total = 0;

    // Populate DetailPurchase when view detail
    DetailPurchase.forEach((item) => {
      htmlDetailPurchase += `
      <tr>
        <td style="white-space:normal;word-wrap: break-word;border-color: rgb(142, 237, 175);">${counterDetailPurchase}</td>
        <td style="white-space:normal;word-wrap: break-word;border-color: rgb(142, 237, 175);">${item.item_code}</td>
        <td style="white-space:normal;word-wrap: break-word;border-color: rgb(142, 237, 175);">${item.name}</td>
        <td style="white-space:normal;word-wrap: break-word;border-color: rgb(142, 237, 175);">${parseFloat(item.qty)}</td>
        <td style="white-space:normal;word-wrap: break-word;border-color: rgb(142, 237, 175);">${item.unit_code}</td>
        <td style="white-space:no-wrap;border-color: rgb(142, 237, 175);">${formatRupiah1(item.price)}</td>
        <td style="white-space:no-wrap;border-color: rgb(142, 237, 175);">${formatRupiah1(item.total)}</td>
        <td style="white-space:no-wrap;border-color: rgb(142, 237, 175);">${formatRupiah1(item.discount)}</td>
        <td style="white-space:no-wrap;border-color: rgb(142, 237, 175);">${formatRupiah1(item.sub_total)}</td>
      </tr>
  
      `;
      grand_total += parseFloat(item.sub_total);
      counterDetailPurchase++;
    });
    htmlDetailPurchase += `
    <tr>
      <td colspan="8" style="white-space:no-wrap;border-color: rgb(142, 237, 175);text-align:right;padding-right: 6px"><b>Grand Total</b></td>
      <td style="white-space:no-wrap;border-color: rgb(142, 237, 175);"><b>${formatRupiah1(grand_total)}</b></td>
    </tr>
    `;

    listDetailPurchase.html(htmlDetailPurchase);
  }

  function printRecap() {
    let urlRoute = route('admin.printrecappayment');

    urlRoute =
      urlRoute +
      `?suppliercode=${supplierCode.val()}&startDate=${moment(supplyData.startDate, 'DD/MM/YYYY').format('YYYY-MM-DD')}&endDate=${moment(
        supplyData.endDate,
        'DD/MM/YYYY'
      ).format('YYYY-MM-DD')}&is_approve=${supplyData.is_approve}`;

    window.open(urlRoute, '_blank');
  }

  async function getDetailCB(code) {
    getPRTable1 = route('admin.tablecashbook1', code);
    Cbdetailno.html(code);
    reloadTable(method, tableName1, columns2, getPRTable1, supplyData, null, false);
  }

  function getDetailCB2(code) {
    getPRTable2 = route('admin.tablecashbook2', code);
    reloadTable(method, tableName2, columns3, getPRTable2, supplyData, null, false);
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

    getDetailCB('---');
    getDetailCB2('---');
    reloadTable(method, tableName, columns, getPRTable, supplyData);
  });

  // change Select Paid Status
  $(document).on('change', '#statusSelectPaidStatus', function () {
    let val = $(this).val();
    supplyData.paystatus = val;

    reloadTable(method, tableName, columns, getPRTable, supplyData);
  });

  // Click Delete Button
  $(document).on('click', '.deletebtn', function () {
    let Code = $(this).data('code');
    showconfirmdelete(Code, Code, deleteData, 'Payment :');
  });

  // Click Approve Button
  $(document).on('click', '.approvebtn', function () {
    let Code = $(this).data('code');
    showconfirmapprove(Code, Code, approveData, 'Payment');
  });

  // View Button
  $(document).on('click', '.viewbtn', async function () {
    let code = $(this).data('code');

    getDetailCB(code);
    getDetailCB2(code);
  });

  // Click Edit Button
  $(document).on('click', '.editbtn', function () {
    let code = $(this).data('code');
    let url = route('admin.editPaymentView', code);

    window.location.href = url;
  });

  // =============================================================

  // Trigger Toast
  // ==================================
  checkNotifMessage();
});
