import tableInitiator from '../tableinitiator.js';
import initiatedtp from '../datepickerinitiator.js';
import managedate from '../managedate.js';
import { showwarning, showerror } from '../jqueryconfirm.js';
import { formatRupiah1 } from '../rupiahformatter.js';

$(document).ready(function () {
  localStorage.clear();

  const modalPRSearch = $('#modalProjectRealisationSearch');
  const Date = new managedate();

  const dtpstarttrans = $('#dtpstarttrans');
  const dtplasttrans = $('#dtplasttrans');
  const inputstartdatetrans = $('.inputstartdatetrans');
  const inputlastdatetrans = $('.inputlastdatetrans');
  const startMONTH = Date.getFirstMonth();
  const lastMONTH = Date.getLastMonth();
  const projectrealisationCode = $('.projectrealisationCode');
  const customerCode = $('.customerCode');
  const titlecustcode = $('.titlecustcode');
  const inputprojectrealisationno = $('.inputprojectrealisationno');
  const inputprojectname = $('.inputprojectname');
  const labeltotal = $('.labeltotal');

  let supplyData = {
    startDate: startMONTH,
    endDate: lastMONTH,
    customer_code: ''
  };

  // INISIASI DATATABLE Customer Modal Search
  // ----------------------------------------------------------------
  var getDataPR = route('admin.getProjectRealisationSearchtable');
  const tablePRName = '.projectrealisationsearchtable';
  const methodGetPR = 'get';
  const columnsPR = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'code', title: 'Project Realisation Code', searchable: true },
    { data: 'realisation_date', name: 'realisation_date', title: 'Realisation Date', searchable: false },
    { data: 'project_amount', name: 'project_amount', title: 'Project Amount', searchable: false },
    { data: 'realisation_amount', name: 'realisation_amount', title: 'Realisation Amount', searchable: false }
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
  $(document).on('click', '.btnsearchprojectreal', function () {
    if (customerCode.val() == '') {
      customerCode.focus();
      showwarning('Please Select Customer First !');
      return;
    }
    supplyData.customer_code = customerCode.val();
    titlecustcode.html(customerCode.val());
    modalPRSearch.modal('show');
    reloadTablePRSearch(methodGetPR, tablePRName, columnsPR, getDataPR, supplyData);
  });

  $(document).on('click', '.selectprojectrealisation', function () {
    let coderelasasi = $(this).data('code');
    let projectname = $(this).data('name');
    let amount = parseFloat($(this).data('amount'));

    inputprojectrealisationno.val(coderelasasi);
    inputprojectname.val(projectname);
    labeltotal.html(formatRupiah1(amount));
    modalPRSearch.modal('hide');
  });

  // check perubahan value datepicker TRANS DATE
  $(document).on('change', '.datetimepicker-input', function () {
    updateDTPTransDateValue();
  });

  //Select Customer
  $(document).on('click', '.selectsupplierbtn', function () {
    projectrealisationCode.val();
  });
});
