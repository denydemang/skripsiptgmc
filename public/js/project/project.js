import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { showerror } from '../jqueryconfirm.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import { showconfirmdelete, showconfirmstart } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import daterangeInitiator from '../daterangeinitiator.js';
import initiatedtp from '../datepickerinitiator.js';
import managedate from '../managedate.js';

$(document).ready(function () {
  // Inputan element dan inisiasi property
  // ------------------------------------
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
  // ----------------------------------------------------

  // INISIASI DATATABLE
  // ----------------------------------------------------------------
  var getDataProject = route('admin.getDataProject');
  const tableName = '.projecttable';
  const method = 'post';
  const columns = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'Code', title: 'CODE', searchable: true },
    { data: 'name', name: 'Name', title: 'Project Name', searchable: true },
    { data: 'transaction_date', name: 'transaction_date', title: 'Transaction Date', searchable: false },
    { data: 'project_type_code', name: 'project_type_code', title: 'Project Type Code', searchable: true },
    { data: 'type_project_description', name: 'type_project_description', title: 'Project Type' },
    { data: 'customer_code', name: 'customer_code', title: 'Customer Code', searchable: true },
    { data: 'customer_name', name: 'customer_name', title: 'Customer Name', searchable: true },
    { data: 'total_termin', name: 'total_termin', title: 'Total Termin', searchable: false },
    { data: 'location', name: 'Location', title: 'Location', searchable: true },
    { data: 'budget', name: 'Budget', title: 'Project Amount', searchable: false },
    { data: 'realisation_amount', name: 'realisation_amount', title: 'Realisation Amount', searchable: false },
    { data: 'start_date', name: 'start_date', title: 'Start Date', searchable: false },
    { data: 'end_date', name: 'end_date', title: 'End Date', searchable: false },
    { data: 'project_status', name: 'project_status', title: 'Status' },
    { data: 'project_document', name: 'project_document', title: 'Project Document' },
    { data: 'description', name: 'Description', title: 'Description' },
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
  // --------------------------------------------------------------------

  // INISIASI DATEPICKER
  // ---------------------------------------------

  initiatedtp(dtpstarttrans);
  initiatedtp(dtplasttrans);
  initiatedtp(dtpstartdateproject1);
  initiatedtp(dtpstartdateproject2);
  initiatedtp(dtpenddateproject1);
  initiatedtp(dtpenddateproject2);

  inputstartdatetrans.val(supplyData.startDate);
  inputlastdatetrans.val(supplyData.endDate);

  // --------------------------------------------

  // Function
  // ------------------------------------------------------------
  async function getData(code = '') {
    const urlRequest = route('admin.getDataDetailProjectRaw', code);
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

  function deleteData(code, name) {
    const urlRequest = route('admin.deleteDataProject', code);
    window.location.href = urlRequest;
  }

  function populateData(Material = [], upah = []) {
    // Populate Title Detail
    let codeProyek = Material[0].project_code;
    let namaProyek = Material[0].project_name;

    titledetail.html(`${codeProyek} - ${namaProyek}`);
    let htmlMaterial = '';
    let counterMaterial = 1;

    // Populate Detail Material when view detail
    Material.forEach((item) => {
      htmlMaterial += `
      <tr>
        <td style="white-space:normal;word-wrap: break-word;">${counterMaterial}</td>
        <td style="white-space:normal;word-wrap: break-word;">${item.item_code}</td>
        <td style="white-space:normal;word-wrap: break-word;">${item.item_name}</td>
        <td style="white-space:normal;word-wrap: break-word;">${parseFloat(item.qty)}</td>
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
        <td style="white-space:normal;word-wrap: break-word;">${item.job}</td>
        <td style="white-space:normal;word-wrap: break-word;">${parseFloat(item.qty)}</td>
        <td style="white-space:normal;word-wrap: break-word;">${item.unit}</td>
        <td style="white-space:normal;word-wrap: break-word;">${formatRupiah1(parseFloat(item.price))}</td>
        <td style="white-space:normal;word-wrap: break-word;">${formatRupiah1(parseFloat(item.total))}</td>
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
      <td style="white-space:normal;word-wrap: break-word;"><b>${formatRupiah1(totalUpah)}</b></td>
    </tr>
    `;
    listUpah.html(htmlUpah);
  }

  function updateDTPTransDateValue() {
    let startTrans = inputstartdatetrans.val();
    let lastTrans = inputlastdatetrans.val();

    supplyData.startDate = startTrans;
    supplyData.endDate = lastTrans;

    reloadTable(method, tableName, columns, getDataProject, supplyData);
  }

  function updateDTPStartDateValue() {
    let startDate1 = inputstartdateproject1.val();
    let startDate2 = inputstartdateproject2.val();

    supplyData.startProject = startDate1;
    supplyData.startProject2 = startDate2;

    reloadTable(method, tableName, columns, getDataProject, supplyData);
  }

  function updateDTPFinishDateValue() {
    let finishDate1 = inputenddateproject1.val();
    let finishDate2 = inputenddateproject2.val();

    supplyData.EndProject = finishDate1;
    supplyData.EndProject2 = finishDate2;

    reloadTable(method, tableName, columns, getDataProject, supplyData);
  }

  async function startProject(code) {
    const urlRequest = route('admin.startProject', code);

    const method = 'POST';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      showerror(error);
      return null;
    }
  }

  async function redirectSuccess(code) {
    const urlRedirect = route('admin.project');
    const response = await startProject(code);
    if (response) {
      window.location.href = urlRedirect;
    }
  }
  // -------------------------------------------------------------------

  // CRUD AND EVENT
  // ----------------------------------------------------------------

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

  // Click Delete Button
  $(document).on('click', '.deletebtn', function () {
    let Code = $(this).data('code');
    showconfirmdelete(Code, Code, deleteData, 'Project :');
  });

  // Click Select Button
  $(document).on('change', '#statusSelect', function () {
    let val = $(this).val();
    supplyData.status = val;

    reloadTable(method, tableName, columns, getDataProject, supplyData);
  });

  // Click Edit Button
  $(document).on('click', '.editbtn', function () {
    let code = $(this).data('code');
    let url = route('admin.editProjectView', code);

    window.location.href = url;
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
  // Click Start Project
  $(document).on('click', '.startbtn', async function () {
    let code = $(this).data('code');
    showconfirmstart(code, code, redirectSuccess, 'Project ');
  });

  // ----------------------------------------------------------

  // Trigger Toast
  // ------------------------------------------
  checkNotifMessage();
});
