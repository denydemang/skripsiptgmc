import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { showerror } from '../jqueryconfirm.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import { showconfirmdelete, showconfirmposting } from '../jqueryconfirm.js';
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
  const iscustomerrequired = $('.iscustomerrequired'); //Tdanda Bintang merah di label supplier
  const customerName = $('.customerName');
  const customerCode = $('.customerCode');
  const voucherno = $('.voucherno');
  const reftransno = $('.reftransno');
  const transdatejurnal = $('.transdatejurnal');
  const typejurnal = $('.typejurnal');
  const tablelistdetailjurnal = $('.tablelistdetailjurnal tbody');
  const modalDetailPurchase = $('#modal-detailpurchase');
  const modalTitle = $('.titleview');
  const startMONTH = Date.getFirstMonth();
  const lastMONTH = Date.getLastMonth();

  let supplyData = {
    posting_status :'',
    journal_type_code : '',
    startDate: startMONTH,
    endDate: lastMONTH
  };
  // =========================================

  // INISIASI DATATABLE
  // ======================================================
  var getPRTable = route('admin.tablejournal');
  const tableName = '.journaltable';
  const method = 'post';
  const columns = [
    { data: 'action', name: 'action', title: 'Actions', searchable: false, orderable: false },
    { data: 'voucher_no', name: 'voucher_no', title: 'Voucher No', searchable: true },
    { data: 'transaction_date', name: 'transaction_date', title: 'Transaction Date', searchable: false },
    { data: 'journal_type_code', name: 'journal_type_code', title: 'Journal Type Code', searchable: false },
    { data: 'journal_type_name', name: 'journal_type_name', title: 'Journal Type Name', searchable: false },
    { data: 'ref_no', name: 'ref_no', title: 'Ref Transaction No', searchable: false },
    { data: 'posting_status', name: 'posting_status', title: 'Posting Status', searchable: false },
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
  iscustomerrequired.html('');

  // =============================================================

  // Function and subroutines
  // =============================================================

  async function getData(code = '') {
    const urlRequest = route('admin.detailjournal', code);
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
    const urlRequest = route('admin.deletejournal', code);
    window.location.href = urlRequest;
  }

  function postingData(code, name) {
    const urlRequest = route('admin.postingjournal', code);
    window.location.href = urlRequest;
  }

  function printRecap() {
    let urlRoute = route('admin.printrecapreceipt');

    urlRoute =
      urlRoute +
      `?customercode=${customerCode.val()}&startDate=${moment(supplyData.startDate, 'DD/MM/YYYY').format('YYYY-MM-DD')}&endDate=${moment(
        supplyData.endDate,
        'DD/MM/YYYY'
      ).format('YYYY-MM-DD')}&is_approve=${supplyData.is_approve}`;

    window.open(urlRoute, '_blank');
  }

  function populateData(DetailJournal = []) {

    let htmldetailjurnal = '';
    voucherno.html(`: ${DetailJournal[0].voucher_no}`)
    reftransno.html(`: ${DetailJournal[0].ref_no}`)
    transdatejurnal.html(`: ${moment(DetailJournal[0].transaction_date).format("DD/MM/YYYY")}`)
    typejurnal.html(`:  ${DetailJournal[0].journal_type_code}`)

    let totalDebit = 0;
    let totalKredit = 0;
    DetailJournal.forEach((item) => {
      htmldetailjurnal += `
      <tr>
        <td style="font-size:14px;width:10%;font-weight:bold; padding:5px" class="text-dark">${item.coa_code}</td>
        <td style="font-size:14px;width:20%;white-space:nowrap; padding:5px"  class="text-dark">${item.coa_name}</td>
        <td style="font-size:14px; width:30%; padding:5px"  class="text-dark">${item.description}</td>
        <td style="font-size:14px;width:20%; padding:5px;white-space:nowrap" class="text-dark">${formatRupiah1(parseFloat(item.debit))}</td>
        <td style="font-size:14px;width:20%;padding:5px; white-space:nowrap"  class="text-dark">${formatRupiah1(parseFloat(item.kredit))}</td>
      </tr>
  
      `;

      totalDebit += parseFloat(item.debit)
      totalKredit += parseFloat(item.kredit)
    });
    htmldetailjurnal += `
    <tr>
      <td colspan="3" class="text-dark" style="text-align: center;font-weight:bold">Total</td>
      <td class="text-dark" style="font-weight:bold;white-space:nowrap;padding:5px">${formatRupiah1(parseFloat(totalDebit))}</td>
      <td class="text-dark" style="font-weight:bold;white-space:nowrap;;padding:5px">${formatRupiah1(parseFloat(totalKredit ))}</td>
    </tr>
    `;

    tablelistdetailjurnal.html(htmldetailjurnal);
  }

  // =============================================================

  // Event
  // =============================================================

  // check perubahan value datepicker TRANS DATE
  $(document).on('change', '.datetimepicker-input', function () {
    updateDTPTransDateValue();
  });

   // View Button
   $(document).on('click', '.viewbtn', async function () {
    let code = $(this).data('code');

    modalTitle.html('Detail Journal');
    modalDetailPurchase.modal('show');



    voucherno.html(": Fetching Data...")
    reftransno.html(": Fetching Data...")
    transdatejurnal.html(": Fetching Data...")
    typejurnal.html(": Fetching Data...")
    tablelistdetailjurnal.html(`
    <tr>
      <td colspan="6" class="text-dark" style="text-align: center;font-weight:bold">Fetching Data.....</td>
    </tr>
    `)
    const dataDetail = await getData(code);
    populateData(dataDetail);
  });

  // click print recap
  $(document).on('click', '.btnprintrecap', function () {
    printRecap();
  });

  // change Select Approve Status
  $(document).on('change', '#statusSelectJournalType', function () {
    let val = $(this).val();
    supplyData.journal_type_code = val;

    reloadTable(method, tableName, columns, getPRTable, supplyData);
  });
  $(document).on('change', '#statusSelectPosting', function () {
    let val = $(this).val();
    supplyData.posting_status = val;

    reloadTable(method, tableName, columns, getPRTable, supplyData);
  });

  // Click Delete Button
  $(document).on('click', '.deletebtn', function () {
    let Code = $(this).data('code');
    showconfirmdelete(Code, Code, deleteData, 'Journal :');
  });

  // Click Approve Button
  $(document).on('click', '.postingbtn', function () {
    let Code = $(this).data('code');
    showconfirmposting(Code, Code, postingData, 'Journal');
  });

  // Click Edit Button
  $(document).on('click', '.editbtn', function () {
    let code = $(this).data('code');
    let url = route('admin.editJournalView', code);

    window.location.href = url;
  });

  // =============================================================

  // Trigger Toast
  // ==================================
  checkNotifMessage();
});
