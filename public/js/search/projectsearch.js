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
  const inputprojectcode = $('.inputprojectcode');
  const inputprojectname = $('.inputprojectname');
  const inputtotalproject = $('.inputtotalproject');
  const labelcurrenttermin = $('.labelcurrenttermin');
  const labeltotaltermin = $('.labeltotaltermin');

  let supplyData = {
    startDate: startMONTH,
    endDate: lastMONTH,
    customer_code: ''
  };

  // INISIASI DATATABLE Customer Modal Search
  // ----------------------------------------------------------------
  var getDataPR = route('admin.getDataProjectSearch');
  const tablePRName = '.projectsearchtable';
  const methodGetPR = 'get';
  const columnsPR = [
    { data: 'action', name: 'actions', title: 'Action', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'code', title: 'Project Code', searchable: true },
    { data: 'name', name: 'name', title: 'Project Name', searchable: true },
    { data: 'budget', name: 'budget', title: 'Project Amount', searchable: false },
    { data: 'realisation_amount', name: 'realisation_amount', title: 'Realisation Amount', searchable: false },
    { data: 'total_termin', name: 'total_termin', title: 'Total Termin', searchable: false },
    { data: 'last_termin', name: 'last_termin', title: 'Last Termin', searchable: false }
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

  // ================================================

  // CRUD AND EVENT
  // ------------------------------------------------------------

  //Click PR Search
  $(document).on('click', '.btnsearchproject', function () {
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

  $(document).on('click', '.btnselectproject', function () {
    let projectcode = $(this).data('code');
    let projectname = $(this).data('name');
    let totaltermin = parseInt($(this).data('totaltermin'));
    let lasttermin = parseInt($(this).data('lasttermin'));
    let amount = parseFloat($(this).data('budget'));

    inputprojectcode.val(projectcode);
    inputprojectname.val(projectname);
    inputtotalproject.val(formatRupiah1(amount));
    labeltotaltermin.html(totaltermin);
    labelcurrenttermin.html(lasttermin + 1);
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
