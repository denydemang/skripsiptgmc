import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { showerror } from '../jqueryconfirm.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import { showconfirmdelete, showconfirmstart, showconfirmapprove } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import daterangeInitiator from '../daterangeinitiator.js';
import initiatedtp from '../datepickerinitiator.js';
import managedate from '../managedate.js';

$(document).ready(function () {
  // Inputan element dan inisiasi property
  // =============================================
  const Date = new managedate();
  const listUpah = $('.listupah tbody');
  const dtpstarttrans = $('#dtpstarttrans');
  const dtplasttrans = $('#dtplasttrans');
  const inputstartdatetrans = $('.inputstartdatetrans');
  const inputlastdatetrans = $('.inputlastdatetrans');
  const dtpstartdateproject1 = $('#dtpstartdateproject1');
  const dtpstartdateproject2 = $('#dtpstartdateproject2');
  const dtpenddateproject1 = $('#dtpenddateproject1');
  const dtpenddateproject2 = $('#dtpenddateproject2');
  const inputstartdateproject1 = $('.inputstartdateproject1');
  const inputstartdateproject2 = $('.inputstartdateproject2');
  const inputenddateproject1 = $('.inputenddateproject1');
  const inputenddateproject2 = $('.inputenddateproject2');
  const listMaterial = $('.listbb tbody');
  const modalDetailProject = $('#modal-detailproject');
  const modalTitle = $('.titleview');
  const checkstartdate = $('.checkstartdate');
  const checkenddate = $('.checkenddate');
  const daterangegroup2 = $('.daterangegroup2');
  const daterangegroup3 = $('.daterangegroup3');
  const titledetail = $('.title-detail');

  const startMONTH = Date.getFirstMonth();
  const lastMONTH = Date.getLastMonth();
  let supplyData = {
    status: '',
    startDate: startMONTH,
    endDate: lastMONTH,
    startProject: '',
    startProject2: '',
    EndProject: '',
    EndProject2: ''
  };

  // ==============================================

  // INISIASI DATATABLE
  // ========================================================================
  var getDataProject = route('admin.getDataProjectRealisationTable');
  const tableName = '.projectrealisationtable';
  const method = 'get';
  const columns = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false },
    { data: 'code', name: 'Code', title: 'Relisation Code', searchable: true },
    { data: 'project_code', name: 'project_code', title: 'Project Code', searchable: true },
    { data: 'project_name', name: 'project_name', title: 'Project Name', searchable: true },
    { data: 'realisation_date', name: 'realisation_date', title: 'Realisation Date', searchable: false },
    { data: 'termin', name: 'termin', title: 'No Termin', searchable: false },
    { data: 'total_termin', name: 'total_termin', title: 'Total Termin', searchable: false },
    { data: 'customer_code', name: 'customer_code', title: 'Customer Code', searchable: true },
    { data: 'customer_name', name: 'customer_name', title: 'Cutomer Name', searchable: true },
    { data: 'project_amount', name: 'project_amount', title: 'Project Amount', searchable: false },
    { data: 'percent_realisation', name: 'percent_realisation', title: 'Percent Realisation', searchable: false },
    { data: 'realisation_amount', name: 'realisation_amount', title: 'Realisation Amount', searchable: false },
    { data: 'is_approve', name: 'is_approve', title: 'Approved', searchable: false },
    { data: 'approved_by', name: 'approved_by', title: 'Approved By' },
    { data: 'updated_by', name: 'Updated_By', title: 'Updated By', searchable: true },
    { data: 'created_by', name: 'Created_By', title: 'Created By', searchable: true }
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
  reloadTable(method, tableName, columns, getDataProject, supplyData);
  // =====================================================================

  // INISIASI DATEPICKER
  // ===================================================================

  initiatedtp(dtpstarttrans);
  initiatedtp(dtplasttrans);
  initiatedtp(dtpstartdateproject1);
  initiatedtp(dtpstartdateproject2);
  initiatedtp(dtpenddateproject1);
  initiatedtp(dtpenddateproject2);

  inputstartdatetrans.val(supplyData.startDate);
  inputlastdatetrans.val(supplyData.endDate);

  // ==============================================================

  // Function
  // ==================================================================
  async function getData(code = '') {
    const urlRequest = route('admin.getDetailRealisation', code);
    const method = 'GET';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      showerror(error);
      return null;
    }
  }

  function deleteData(code, name) {
    const urlRequest = route('admin.deleterealisation', code);
    window.location.href = urlRequest;
  }
  function checkValueCheckbox() {
    const isCheckedstartDate = checkstartdate[0].checked;
    const isCheckedendDate = checkenddate[0].checked;

    if (isCheckedstartDate) {
      daterangegroup2.addClass('d-block');
      daterangegroup2.removeClass('d-none');

      inputstartdateproject1.val(startMONTH);
      inputstartdateproject2.val(lastMONTH);

      supplyData.startProject = startMONTH;
      supplyData.startProject2 = lastMONTH;

      reloadTable(method, tableName, columns, getDataProject, supplyData);
    } else {
      daterangegroup2.addClass('d-none');
      daterangegroup2.removeClass('d-block');
      supplyData.startProject = '';
      supplyData.startProject2 = '';
      reloadTable(method, tableName, columns, getDataProject, supplyData);
    }

    if (isCheckedendDate) {
      daterangegroup3.addClass('d-block');
      daterangegroup3.removeClass('d-none');

      inputenddateproject1.val(startMONTH);
      inputenddateproject2.val(lastMONTH);

      supplyData.EndProject = startMONTH;
      supplyData.EndProject2 = lastMONTH;

      reloadTable(method, tableName, columns, getDataProject, supplyData);
    } else {
      daterangegroup3.addClass('d-none');
      daterangegroup3.removeClass('d-block');
      supplyData.EndProject = '';
      supplyData.EndProject2 = '';

      reloadTable(method, tableName, columns, getDataProject, supplyData);
    }
  }

  function updateDTPTransDateValue() {
    let startTrans = inputstartdatetrans.val();
    let lastTrans = inputlastdatetrans.val();

    supplyData.startDate = startTrans;
    supplyData.endDate = lastTrans;

    reloadTable(method, tableName, columns, getDataProject, supplyData);
  }

  function populateData(Material = [], upah = []) {
    // Populate Title Detail
    let codeProyek = Material[0].project_realisation_code;
    // let namaProyek = Material[0].project_name;

    titledetail.html('Realisation Code: ' + `${codeProyek}`);
    let htmlMaterial = '';
    let counterMaterial = 1;

    // Populate Detail Material when view detail
    Material.forEach((item) => {
      htmlMaterial += `
      <tr>
        <td style="white-space:normal;word-wrap: break-word;">${counterMaterial}</td>
        <td style="white-space:normal;word-wrap: break-word;">${item.item_code}</td>
        <td style="white-space:normal;word-wrap: break-word;">${item.item_name}</td>
        <td style="white-space:normal;word-wrap: break-word;">${parseFloat(item.qty_used)}</td>
        <td style="white-space:normal;word-wrap: break-word;">${item.unit_code}</td>
      </tr>
  
      `;
      counterMaterial++;
    });

    listMaterial.html(htmlMaterial);

    // --------------------------

    // Populate Detail Upah when view detail
    let htmlUpah = '';
    let counterUpah = 1;
    let totalUpah = 0;
    upah.forEach((item) => {
      htmlUpah += `
      <tr>
        <td style="white-space:normal;word-wrap: break-word;">${counterUpah}</td>
        <td style="white-space:normal;word-wrap: break-word;">${item.upah_code}</td>
        <td style="white-space:normal;word-wrap: break-word;">${item.upah_name}</td>
        <td style="white-space:normal;word-wrap: break-word;text-align:right">${parseFloat(item.qty_used)}</td>
        <td style="white-space:normal;word-wrap: break-word;">${item.unit}</td>
        <td style="white-space:normal;word-wrap: break-word;text-align:right">${formatRupiah1(parseFloat(item.price))}</td>
        <td style="white-space:normal;word-wrap: break-word;text-align:right">${formatRupiah1(parseFloat(item.total))}</td>
      </tr>
  
      `;
      totalUpah += parseFloat(item.total);
      counterUpah++;
    });

    htmlUpah += `
    <tr>
      <td style="white-space:normal;word-wrap: break-word;"></td>
      <td style="white-space:normal;word-wrap: break-word;"></td>
      <td style="white-space:normal;word-wrap: break-word;"></td>
      <td style="white-space:normal;word-wrap: break-word;"></td>
      <td style="white-space:normal;word-wrap: break-word;"></td>
      <td style="white-space:normal;word-wrap: break-word;"><b>Total</b></td>
      <td style="white-space:normal;word-wrap: break-word;text-align:right"><b>${formatRupiah1(totalUpah)}</b></td>
    </tr>
    `;
    listUpah.html(htmlUpah);
  }

  function approveData(code, name) {
    const urlRequest = route('admin.approverealisation', code);
    window.location.href = urlRequest;
  }

  // ====================================================================

  // CRUD and Event
  // ====================================================================

  // View Button
  $(document).on('click', '.viewbtn', async function () {
    let code = $(this).data('code');

    modalTitle.html('Detail Material Dan Upah');
    modalDetailProject.modal('show');

    const response = await getData(code);
    const dataMaterial = response.dataBahanBaku;
    const dataupah = response.dataUpah;
    populateData(dataMaterial, dataupah);
  });

  // Click Edit Button
  $(document).on('click', '.editbtn', function () {
    let code = $(this).data('code');
    let url = route('admin.editProjectrealisationview', code);

    window.location.href = url;
  });

  $(document).on('click', '.deletebtn', function () {
    let Code = $(this).data('code');
    showconfirmdelete(Code, Code, deleteData, 'Realisation :');
  });

  $(document).on('click', '.approvebtn', function () {
    let Code = $(this).data('code');
    showconfirmapprove(Code, Code, approveData, 'Project Realisation');
  });
  // Click Select Button
  $(document).on('change', '#statusSelect', function () {
    let val = $(this).val();
    console.log(val);
    supplyData.status = val;

    reloadTable(method, tableName, columns, getDataProject, supplyData);
  });

  // checked startdate project
  $(document).on('change', '.checkstartdate', function () {
    checkValueCheckbox();
  });
  // checked enddate project
  $(document).on('change', '.checkenddate', function () {
    checkValueCheckbox();
  });

  // check perubahan value datepicker TRANS DATE
  $(document).on('change', '.datetimepicker-input', function () {
    updateDTPTransDateValue();
  });

  // check perubahan value datepicker StartDate
  $(document).on('change', '.inputstartdateproject1', function () {
    updateDTPStartDateValue();
  });
  // check perubahan value datepicker StartDate
  $(document).on('change', '.inputstartdateproject2', function () {
    updateDTPStartDateValue();
  });

  // check perubahan value datepicker EndDate
  $(document).on('change', '.inputenddateproject1', function () {
    updateDTPFinishDateValue();
  });
  // check perubahan value datepicker EndDate
  $(document).on('change', '.inputenddateproject2', function () {
    updateDTPFinishDateValue();
  });

  // ====================================================================

  // Trigger Toast
  // ==================================================================
  checkNotifMessage();
  // ==================================================================
});
