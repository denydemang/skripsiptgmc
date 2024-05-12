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
  var getPRTable = route('admin.tablepurchase');
  const tableName = '.purchasetable';
  const method = 'post';
  const columns = [
    { data: 'action', name: 'action', title: 'Actions', searchable: false, orderable: false },
    { data: 'purchase_no', name: 'purchase_no', title: 'Purchase No', searchable: true },
    { data: 'pr_no', name: 'pr_no', title: 'Purchase Request No', searchable: true },
    { data: 'transaction_date', name: 'transaction_date', title: 'Transaction Date', searchable: false },
    { data: 'is_approve', name: 'is_approve', title: 'Approved', searchable: false },
    { data: 'paid_status', name: 'paid_status', title: 'Paid Status', searchable: false },
    { data: 'supplier_code', name: 'supplier_code', title: 'Supplier Code', searchable: true },
    { data: 'supplier_name', name: 'supplier_name', title: 'Supplier Name', searchable: true },
    { data: 'supplier_address', name: 'supplier_address', title: 'Supplier Address', searchable: true },
    { data: 'supplier_phone', name: 'supplier_phone', title: 'Supplier Phone', searchable: true },
    { data: 'total', name: 'total', title: 'Total', searchable: false },
    { data: 'other_fee', name: 'other_fee', title: 'Other Fee', searchable: false },
    { data: 'percent_ppn', name: 'percent_ppn', title: 'Percent PPN', searchable: false },
    { data: 'ppn_amount', name: 'ppn_amount', title: 'PPN Amount', searchable: true },
    { data: 'grand_total', name: 'grand_total', title: 'Grand Total', searchable: false },
    { data: 'paid_amount', name: 'paid_amount', title: 'Paid Amount', searchable: false },
    { data: 'not_paid_amount', name: 'not_paid_amount', title: 'UnPaid Amount', searchable: false },
    { data: 'description', name: 'description', title: 'Description', searchable: true },
    { data: 'approved_by', name: 'approved_by', title: 'Approved By', searchable: true },
    { data: 'payment_term_code', name: 'payment_term_code', title: 'Payment Term', searchable: true },
    { data: 'due_date', name: 'due_date', title: 'Due Date', searchable: false },
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
  reloadTable(method, tableName, columns, getPRTable, supplyData);
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

    reloadTable(method, tableName, columns, getPRTable, supplyData);
  }

  function deleteData(code, name) {
    const urlRequest = route('admin.deletepurchase', code);
    window.location.href = urlRequest;
  }

  function approveData(code, name) {
    const urlRequest = route('admin.approvepurchase', code);
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
    let urlRoute = route('admin.printrecappurchase');

    urlRoute =
      urlRoute +
      `?suppliercode=${supplierCode.val()}&startDate=${moment(supplyData.startDate, 'DD/MM/YYYY').format('YYYY-MM-DD')}&endDate=${moment(
        supplyData.endDate,
        'DD/MM/YYYY'
      ).format('YYYY-MM-DD')}&is_approve=${supplyData.is_approve}&paystatus=${supplyData.paystatus}`;

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
    showconfirmdelete(Code, Code, deleteData, 'Purchase :');
  });

  // Click Approve Button
  $(document).on('click', '.approvebtn', function () {
    let Code = $(this).data('code');
    showconfirmapprove(Code, Code, approveData, 'Purchase');
  });

  // View Button
  $(document).on('click', '.viewbtn', async function () {
    let code = $(this).data('code');

    modalTitle.html('Detail Purchase');
    modalDetailPurchase.modal('show');

    const dataDetail = await getData(code);
    populateData(dataDetail);
  });

  // Click Edit Button
  $(document).on('click', '.editbtn', function () {
    let code = $(this).data('code');
    let url = route('admin.editprview', code);

    window.location.href = url;
  });

  // =============================================================

  // Trigger Toast
  // ==================================
  checkNotifMessage();
});
