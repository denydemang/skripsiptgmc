import tableInitiator from '../tableinitiator.js';
import checkNotifMessage from '../checkNotif.js';
import AjaxRequest from '../ajaxrequest.js';
import { showconfirmdelete, showwarning } from '../jqueryconfirm.js';
import managedate from '../managedate.js';
import initiatedtp from '../datepickerinitiator.js';

$(document).ready(async function () {
  const tablelistcoa = $('.tablelistcoa tbody');
  const coaLIST = $('#coa_code');
  const Date = new managedate();
  const startMONTH = Date.getFirstMonth();
  const lastMONTH = Date.getLastMonth();
  const dtpstarttrans = $('#dtpstarttrans');
  const dtplasttrans = $('#dtplasttrans');
  const inputstartdatetrans = $('.inputstartdatetrans');
  const inputlastdatetrans = $('.inputlastdatetrans');

  const supplyData = {
    startDate: startMONTH,
    endDate: lastMONTH
  };
  const dataCOA = {
    codeCOA: '',
    namaCOA: ''
  };
  let listCOA = [];

  showCOA();
  createhtmlBody();
  // INISIASI DATEPICKER
  // ---------------------------------------------
  initiatedtp(dtpstarttrans);
  initiatedtp(dtplasttrans);

  inputstartdatetrans.val(startMONTH);
  inputlastdatetrans.val(lastMONTH);

  function showCOA() {
    coaLIST.select2({
      placeholder: '-- Pilih COA --',
      // allowClear: true,
      // theme: "custom-select2", // Menentukan tema kustom
      dropdownPosition: 'above'
    });

    $.ajax({
      type: 'GET',
      url: route('admin.JSONcoa1'),
      // data: "id="+id_kabupaten,
      success: function (msg) {
        // console.log(msg);
        coaLIST.append(msg);
      }
    });
  }

  function populateCOA() {
    let COA = coaLIST.val();

    if (!COA) {
      showwarning('Please Select COA !');
      return;
    }

    if (listCOA.length >= 10) {
      showwarning('Exceed Maximum List COA (Max 10)!');
      return;
    }

    // Split the input string by the hyphen
    let parts = COA.split('-');

    // The first part is the code COA
    let codeCOA = parts[0].trim();

    // The second part is the name COA
    let nameCOA = parts[1].trim();

    dataCOA.codeCOA = codeCOA;
    dataCOA.namaCOA = nameCOA;
    const isExistCOA = listCOA.some((x) => x.codeCOA == dataCOA.codeCOA);

    if (isExistCOA) {
      showwarning('COA Already Exists In The List!');
      return;
    }
    listCOA.push({ ...dataCOA });
    createhtmlBody();
  }

  function deleteCOA(code) {
    let filteredCOA = listCOA.filter((x) => {
      return x.codeCOA != code;
    });

    listCOA = [...filteredCOA];
    createhtmlBody();
  }

  function createhtmlBody() {
    if (listCOA.length == 0) {
      tablelistcoa.html(`
			<tr>
				<td colspan="3" style="text-align: center"><i>List Kosong</i></td>
			</tr>
			`);
    } else {
      let html = ``;
      let norow = 0;
      listCOA.forEach((x) => {
        norow++;
        html += `
				<tr>
					<td>${norow}</td>
					<td>${x.codeCOA}</td>
					<td>${x.namaCOA}</td>
					<td><button class="btn btn-sm btn-danger btndelete" data-code="${x.codeCOA}">X</button></td>
				</tr>
				`;
      });

      tablelistcoa.html(html);
    }
  }

  function generateCoaQueryString(array) {
    return array.map((item) => `coa[]=${encodeURIComponent(item)}`).join('&');
  }

  function printCOA() {
    const coaCODELIST = [];

    listCOA.forEach((x) => {
      coaCODELIST.push(x.codeCOA);
    });

    let stringParemeterCOACODE = generateCoaQueryString(coaCODELIST);

    let urlRoute = route('admin.PrintLedger');

    urlRoute =
      urlRoute +
      `?${stringParemeterCOACODE}&startDate=${moment(supplyData.startDate, 'DD/MM/YYYY').format('YYYY-MM-DD')}&endDate=${moment(
        supplyData.endDate,
        'DD/MM/YYYY'
      ).format('YYYY-MM-DD')}`;

    window.open(urlRoute, '_blank');
  }

  // FN VALIDATE
  function validate() {
    if (listCOA.length == 0) {
      showwarning('Please Insert COA !');
      return false;
    }
    if (inputstartdatetrans.val() == '') {
      inputlastdatetrans.focus();
      showwarning('TransDate Cannot Be Blank!');
      return false;
    }
    if (inputlastdatetrans.val() == '') {
      inputlastdatetrans.focus();
      showwarning('TransDate! Cannot Be Blank');
      return false;
    }

    return true;
  }

  $(document).on('click', '.btnaddcoa', function () {
    populateCOA();
  });

  $(document).on('click', '.btndelete', function () {
    let code = $(this).data('code');
    deleteCOA(code);
  });

  $(document).on('click', '.btnprint', function () {
    if (validate()) {
      printCOA();
    }
  });

  $(document).on('change', '.inputstartdatetrans', function () {
    let val = $(this).val();
    supplyData.startDate = val;
  });

  $(document).on('change', '.inputlastdatetrans', function () {
    let val = $(this).val();
    supplyData.endDate = val;
  });

  // Trigger Toast
  checkNotifMessage();
});
