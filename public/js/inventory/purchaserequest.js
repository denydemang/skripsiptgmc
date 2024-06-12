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
  const dtpstartdateneed = $('#dtpstartdateneed');
  const dtpenddateneed = $('#dtpenddateneed');
  const checkdateneed = $('.checkdateneed');
  const inputstartdateneed = $('.inputstartdateneed');
  const inputenddateneed = $('.inputenddateneed');
  const daterangegroup2 = $('.daterangegroup2');
  const listDetailPR = $('.listbb tbody');
  const modalDetailPr = $('#modal-detailpr');
  const modalTitle = $('.titleview');
  const titledetail = $('.title-detail');
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
  var getPRTable = route('admin.getprtable');
  const tableName = '.prtable';
  const method = 'post';
  const columns = [
    { data: 'action', name: 'action', title: 'Actions', searchable: false, orderable: false, width: '10%' },
    { data: 'pr_no', name: 'pr_no', title: 'PR CODE', searchable: true },
    { data: 'transaction_date', name: 'transaction_date', title: 'Transaction Date', searchable: false },
    { data: 'date_need', name: 'date_need', title: 'Date Need', searchable: false },
    { data: 'pic_name', name: 'pic_name', title: 'PIC (Person In Charge)', searchable: true },
    { data: 'division', name: 'division', title: 'Division', searchable: true },
    { data: 'ref_no', name: 'ref_no', title: 'Doc Ref', searchable: true },
    { data: 'description', name: 'description', title: 'Description', searchable: true },
    { data: 'is_approve', name: 'is_approve', title: 'Approved', searchable: false },
    { data: 'approved_by', name: 'approved_by', title: 'Approved By', searchable: false },
    { data: 'is_purchased', name: 'is_purchased', title: 'Purchased', searchable: false }
  ];

  const setcellDateNeed = (row, data, index) => {
    moment.locale('id');
    let dateNeed = moment(data.date_need, 'DD/MM/YYYY');
    let dateNow = moment();
    let daysDiff = dateNeed.diff(dateNow, 'days');

    if (daysDiff <= 1 && data.is_purchased.includes('Not Purchased')) {
      //Mengubah Warna Cell Menjadi Merah Ketika Date need mendekati tanggal sekarang dan status not purchased
      // $('td', row).eq(3).css({
      //   'background-color': 'red',
      //   color: 'white'
      // });
      $('td', row).eq(3).addClass(['bg-danger', 'text-white']);
    } else {
      $('td', row).eq(3).removeClass(['bg-danger', 'text-white']);
    }
  };
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
  reloadTable(method, tableName, columns, getPRTable, supplyData, setcellDateNeed);
  // =================================================================

  // INISIASI DATEPICKER
  // =============================================================

  initiatedtp(dtpstarttrans);
  initiatedtp(dtplasttrans);
  initiatedtp(dtpstartdateneed);
  initiatedtp(dtpenddateneed);

  inputstartdatetrans.val(supplyData.startDate);
  inputlastdatetrans.val(supplyData.endDate);

  // =============================================================

  // Function and subroutines
  // =============================================================

  async function getData(code = '') {
    const urlRequest = route('admin.detailPR', code);
    const method = 'POST';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      showerror(error);
      return null;
    }
  }

  function checkValueCheckbox() {
    const ischeckdateneed = checkdateneed[0].checked;

    if (ischeckdateneed) {
      daterangegroup2.addClass('d-block');
      daterangegroup2.removeClass('d-none');

      inputstartdateneed.val(startMONTH);
      inputenddateneed.val(lastMONTH);

      supplyData.dateNeedStart = startMONTH;
      supplyData.dateNeedEnd = lastMONTH;

      reloadTable(method, tableName, columns, getPRTable, supplyData, setcellDateNeed);
    } else {
      daterangegroup2.addClass('d-none');
      daterangegroup2.removeClass('d-block');
      supplyData.dateNeedStart = '';
      supplyData.dateNeedEnd = '';
      reloadTable(method, tableName, columns, getPRTable, supplyData, setcellDateNeed);
    }
  }

  function updateDTPSDateNeedValue() {
    let dateNeedStart = inputstartdateneed.val();
    let dateNeedEnd = inputenddateneed.val();

    supplyData.dateNeedStart = dateNeedStart;
    supplyData.dateNeedEnd = dateNeedEnd;

    reloadTable(method, tableName, columns, getPRTable, supplyData, setcellDateNeed);
  }

  function updateDTPTransDateValue() {
    let startTrans = inputstartdatetrans.val();
    let lastTrans = inputlastdatetrans.val();

    supplyData.startDate = startTrans;
    supplyData.endDate = lastTrans;

    reloadTable(method, tableName, columns, getPRTable, supplyData);
  }

  function deleteData(code, name) {
    const urlRequest = route('admin.deletePR', code);
    window.location.href = urlRequest;
  }

  function approveData(code, name) {
    const urlRequest = route('admin.approvePR', code);
    window.location.href = urlRequest;
  }

  function populateData(DetailPR = []) {
    // Populate Title Detail
    let codePR = DetailPR[0].pr_no;

    titledetail.html(`Code PR : ${codePR}`);
    let htmlDetailPR = '';
    let counterDetailPR = 1;

    // Populate DetailPR when view detail
    DetailPR.forEach((item) => {
      htmlDetailPR += `
      <tr class="row">
        <td class="col-1" style="white-space:normal;word-wrap: break-word;">${counterDetailPR}</td>
        <td class="col-3" style="white-space:normal;word-wrap: break-word;">${item.item_code}</td>
        <td class="col-3" style="white-space:normal;word-wrap: break-word;">${item.name}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;">${parseFloat(item.qty)}</td>
        <td class="col-3" style="white-space:normal;word-wrap: break-word;">${item.unit_code}</td>
      </tr>
  
      `;
      counterDetailPR++;
    });

    listDetailPR.html(htmlDetailPR);
  }

  // =============================================================

  // Event
  // =============================================================

  // check perubahan value datepicker TRANS DATE
  $(document).on('change', '.datetimepicker-input', function () {
    updateDTPTransDateValue();
  });

  // change Select Approve Status Button
  $(document).on('change', '#statusSelectApprove', function () {
    let val = $(this).val();
    supplyData.is_approve = val;

    reloadTable(method, tableName, columns, getPRTable, supplyData, setcellDateNeed);
  });

  // change Select Purchase Status Button
  $(document).on('change', '#statusSelectPurchase', function () {
    let val = $(this).val();
    supplyData.is_purchased = val;

    reloadTable(method, tableName, columns, getPRTable, supplyData, setcellDateNeed);
  });

  // checked dateneed
  $(document).on('change', '.checkdateneed', function () {
    checkValueCheckbox();
  });

  // check perubahan value datepicker DateNeed Start
  $(document).on('change', '.inputstartdateneed', function () {
    updateDTPSDateNeedValue();
  });

  // check perubahan value datepicker DateNeed End
  $(document).on('change', '.inputenddateneed', function () {
    updateDTPSDateNeedValue();
  });

  // Click Delete Button
  $(document).on('click', '.deletebtn', function () {
    let Code = $(this).data('code');
    showconfirmdelete(Code, Code, deleteData, 'Purchase Request :');
  });

  // Click Delete Button
  $(document).on('click', '.approvebtn', function () {
    let Code = $(this).data('code');
    showconfirmapprove(Code, Code, approveData, 'Purchase Request');
  });

  // View Button
  $(document).on('click', '.viewbtn', async function () {
    let code = $(this).data('code');

    modalTitle.html('Detail Purchase Request');
    modalDetailPr.modal('show');

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
