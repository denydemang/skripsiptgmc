import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import { showconfirmdelete } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import daterangeInitiator from '../daterangeinitiator.js';

$(document).ready(function () {
  // Inputan Element dan SupplyData untuk params datatable
  // ------------------------------------
  const listUpah = $('.listupah tbody');
  const listMaterial = $('.listbb tbody');
  const modalDetailProject = $('#modal-detailproject');
  const modalTitle = $('.titleview');
  const checkstartdate = $('.checkstartdate');
  const checkenddate = $('.checkenddate');
  const daterangegroup2 = $('.daterangegroup2');
  const daterangegroup3 = $('.daterangegroup3');
  const titledetail = $('.title-detail');

  let supplyData = {
    status: '',
    startDate: moment().startOf('month').format('YYYY-MM-DD'),
    endDate: moment().endOf('month').format('YYYY-MM-DD'),
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
    { data: 'customer_code', name: 'customer_code', title: 'Cutomer Code', searchable: true },
    { data: 'customer_name', name: 'customer_name', title: 'Cutomer Name', searchable: true },
    { data: 'location', name: 'Location', title: 'Location', searchable: true },
    { data: 'budget', name: 'Budget', title: 'Budget', searchable: true },
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

  // INISIASI DATERANGE dan Trigger DATERANGE
  // ---------------------------------------------------------------------
  const objectNameDateRange = $('#daterange'); //Transaction Date
  const objectDateRangeSpan = $('#daterange span');
  const objectNameDateRange2 = $('#daterange2'); //Start Date Project
  const objectDateRangeSpan2 = $('#daterange2 span');
  const objectNameDateRange3 = $('#daterange3'); //EndDate Project
  const objectDateRangeSpan3 = $('#daterange3 span');

  function drawDateRange(objectNameDateRange, objectDateRangeSpan, func) {
    let dateRange = new daterangeInitiator(objectNameDateRange, objectDateRangeSpan, func);

    dateRange.drawDateRange();
  }
  function getDateFromDaterange(startDate, EndDate) {
    supplyData.startDate = startDate.format('YYYY-MM-DD');
    supplyData.endDate = EndDate.format('YYYY-MM-DD');
    reloadTable(method, tableName, columns, getDataProject, supplyData);
  }

  function drawDateRange2(objectNameDateRange2, objectDateRangeSpan2, func2) {
    let dateRange = new daterangeInitiator(objectNameDateRange2, objectDateRangeSpan2, func2);

    dateRange.drawDateRange();
  }

  function getDateFromDaterange2(startDate, EndDate) {
    supplyData.startProject = startDate.format('YYYY-MM-DD');
    supplyData.startProject2 = EndDate.format('YYYY-MM-DD');
    reloadTable(method, tableName, columns, getDataProject, supplyData);
  }

  function drawDateRange3(objectNameDateRange3, objectDateRangeSpan3, func3) {
    let dateRange = new daterangeInitiator(objectNameDateRange3, objectDateRangeSpan3, func3);

    dateRange.drawDateRange();
  }

  function getDateFromDaterange3(startDate, EndDate) {
    supplyData.EndProject = startDate.format('YYYY-MM-DD');
    supplyData.EndProject2 = EndDate.format('YYYY-MM-DD');
    reloadTable(method, tableName, columns, getDataProject, supplyData);
  }

  drawDateRange(objectNameDateRange, objectDateRangeSpan, getDateFromDaterange);
  drawDateRange2(objectNameDateRange2, objectDateRangeSpan2, getDateFromDaterange2);
  drawDateRange3(objectNameDateRange3, objectDateRangeSpan3, getDateFromDaterange3);

  // ----------------------------------------------------------------------------------

  // Function
  // ------------------------------------------------------------
  async function getData(code = '') {
    const urlRequest = route('admin.getDataDetailProjectRaw', code);
    const method = 'POST';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      console.error('Error:', error);
      return null;
    }
  }
  function checkValueCheckbox() {
    const isCheckedstartDate = checkstartdate[0].checked;
    const isCheckedendDate = checkenddate[0].checked;

    if (isCheckedstartDate) {
      daterangegroup2.addClass('d-block');
      daterangegroup2.removeClass('d-none');
      drawDateRange2(objectNameDateRange2, objectDateRangeSpan2, getDateFromDaterange2);
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
      drawDateRange3(objectNameDateRange3, objectDateRangeSpan3, getDateFromDaterange3);
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
    let htmlMaterial = '';
    let counterMaterial = 1;

    // Populate Detail Material when view detail
    Material.forEach((item) => {
      htmlMaterial += `
      <tr class="row">
        <td class="col-1" style="white-space:normal;word-wrap: break-word;">${counterMaterial}</td>
        <td class="col-3" style="white-space:normal;word-wrap: break-word;">${item.item_code}</td>
        <td class="col-3" style="white-space:normal;word-wrap: break-word;">${item.item_name}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;">${parseFloat(item.qty)}</td>
        <td class="col-3" style="white-space:normal;word-wrap: break-word;">${item.unit_code}</td>
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
      <tr class="row">
        <td class="col-1" style="white-space:normal;word-wrap: break-word;">${counterUpah}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;">${item.upah_code}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;">${item.job}</td>
        <td class="col-1" style="white-space:normal;word-wrap: break-word;">${parseFloat(item.qty)}</td>
        <td class="col-1" style="white-space:normal;word-wrap: break-word;">${item.unit}</td>
        <td class="col-2" style="white-space:normal;word-wrap: break-word;">${formatRupiah1(parseFloat(item.price))}</td>
        <td class="col-3" style="white-space:normal;word-wrap: break-word;">${formatRupiah1(parseFloat(item.total))}</td>
      </tr>
  
      `;
      totalUpah += parseFloat(item.total);
      counterUpah++;
    });

    htmlUpah += `
    <tr class="row">
      <td class="col-1" style="white-space:normal;word-wrap: break-word;"></td>
      <td class="col-2" style="white-space:normal;word-wrap: break-word;"></td>
      <td class="col-2" style="white-space:normal;word-wrap: break-word;"></td>
      <td class="col-1" style="white-space:normal;word-wrap: break-word;"></td>
      <td class="col-1" style="white-space:normal;word-wrap: break-word;"></td>
      <td class="col-2" style="white-space:normal;word-wrap: break-word;"><b>Total</b></td>
      <td class="col-3" style="white-space:normal;word-wrap: break-word;"><b>${formatRupiah1(totalUpah)}</b></td>
    </tr>
    `;
    listUpah.html(htmlUpah);

    // Populate Title Detail
    let codeProyek = Material[0].project_code;
    let namaProyek = Material[0].project_name;

    titledetail.html(`${codeProyek} - ${namaProyek}`);
  }
  // -------------------------------------------------------------------

  // CRUD AND EVENT
  // ------------------------------------------------------------

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

  // checked startdate project
  $(document).on('change', '.checkstartdate', function () {
    checkValueCheckbox();
  });
  // checked enddate project
  $(document).on('change', '.checkenddate', function () {
    checkValueCheckbox();
  });
  // ----------------------------------------------------------

  // Trigger Toast
  // ------------------------------------------
  checkNotifMessage();
});
