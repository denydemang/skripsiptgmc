import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { showerror, showwarning } from '../jqueryconfirm.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import { showconfirmdelete, showconfirmstart } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import daterangeInitiator from '../daterangeinitiator.js';
import validateInput from '../validateInput.js';
import initiatedtp from '../datepickerinitiator.js';
import managedate from '../managedate.js';

$(document).ready(function () {
  // Inputan element dan inisiasi property
  // =============================================
  const Date = new managedate();

  const inputrealisationcode = $('.inputrealisationcode');
  const customerCode = $('.customerCode');
  const customerName = $('.customerName');
  const inputprojectcode = $('.inputprojectcode');
  const inputprojectname = $('.inputprojectname');
  const inputtotalproject = $('.inputtotalproject');
  const dtprealisationdate = $('#dtprealisationdate');
  const inputrealisationdate = $('.inputrealisationdate');
  const tabletbdoytermin = $('.tabletermin tbody');
  const tabletbodymateriallist = $('.tablemateriallist tbody');
  const tabletbodyupahlist = $('.tableupahlist tbody');
  const labelcurrenttermin = $('.labelcurrenttermin');
  const labeltotaltermin = $('.labeltotaltermin');
  const fetchingdata = $('.fetchingdata');
  const inputdescription = $('.inputdescription');
  const btnsearchcustomer = $('.btnsearchcustomer');
  const btnsearchproject = $('.btnsearchproject');
  const updateMode = route().current() == 'admin.editProjectrealisationview';

  let realisationList = [];
  let postData = {
    realisation_code: '',
    project_code: '',
    customer_code: '',
    realisation_date: '',
    description: '',
    project_amount: 0,
    percent_realisation: 0,
    realisation_amount: 0,
    termin: 0,
    detailA: [],
    detailB: []
  };
  let interfacerealisation = {
    no_termin: 0,
    realisation_code: 0,
    realisation_amount: 0,
    percentage: 0
  };

  let materialList = [];
  let interfacematerial = {
    item_code: '',
    item_name: '',
    unit: '',
    last_qty: 0,
    current_qty: 0,
    sisa_qty: 0
  };

  let upahList = [];
  let interfaceUpah = {
    upah_code: '',
    upah_name: '',
    unit: '',
    price: 0.0,
    last_balance_qty: 0,
    current_qty: 0,
    balance_qty: 0,
    current_nominal: 0
  };

  let dataInput = ['.inputdescription'];

  // Property Update Mode

  const datarealisasi = $('.datarealisasi').data('realisasi');
  const AllRealisation = $('.allrealisation').data('allrealisation');
  const dataCurrentMaterial = $('.datacurrentmaterial').data('currentmaterial');
  const dataCurrentUpah = $('.datacurrentupah').data('currentupah');
  const datatotaltermin = $('.datatotaltermin').data('totaltermin');

  // inisiasi datapicker dan input
  // ------------------------------------------------------

  initiatedtp(dtprealisationdate);

  if (updateMode) {
    prepareEdit();
  } else {
    inputrealisationdate.val(Date.getNowDate());
  }

  // Function and procedures
  // -----------------------------------------------------

  async function prepareEdit() {
    inputrealisationcode.val(datarealisasi.code);
    customerCode.val(datarealisasi.customer_code);
    customerName.val(datarealisasi.customer_name);
    btnsearchcustomer.prop('hidden', true);
    btnsearchproject.prop('hidden', true);
    inputprojectcode.val(datarealisasi.project_code);
    inputprojectname.val(datarealisasi.project_name);
    inputtotalproject.val(formatRupiah1(datarealisasi.project_amount));
    inputrealisationdate.val(moment(datarealisasi.realisation_date, 'YYYY-MM-DD').format('DD/MM/YYYY'));
    inputdescription.val(datarealisasi.description);

    fetchingdata.html('(fetching data....)');
    const dataRealisasi = await getDataRealisasi(inputprojectcode.val());
    const dataMaterial = await getDataMaterial(inputprojectcode.val());
    const dataUpah = await getDataUpah(inputprojectcode.val());
    fetchingdata.html('');

    labeltotaltermin.html(datatotaltermin);
    labelcurrenttermin.html(datarealisasi.termin);
    postData.termin = datarealisasi.termin;
    postData.realisation_code = datarealisasi.code;
    postData.percent_realisation = parseFloat(datarealisasi.percent_realisation);
    postData.realisation_amount = parseFloat(datarealisasi.realisation_amount);

    if (dataMaterial.length > 0) {
      materialList = [];
      dataMaterial.forEach((x) => {
        if (dataCurrentMaterial.length > 0) {
          dataCurrentMaterial.forEach((y) => {
            if (x.item_code == y.item_code) {
              interfacematerial.last_qty = parseFloat(x.last_balance) + parseFloat(y.qty_used);
              interfacematerial.current_qty = parseFloat(y.qty_used);
              interfacematerial.sisa_qty = interfacematerial.last_qty - interfacematerial.current_qty;
            }
          });
        }
        interfacematerial.item_code = x.item_code;
        interfacematerial.item_name = x.item_name;

        interfacematerial.unit = x.unit_code;

        materialList.push({ ...interfacematerial });
      });
    }

    if (dataRealisasi.length > 0) {
      realisationList = [];

      for (let x = 0; x <= dataRealisasi.length - 2; x++) {
        interfacerealisation.no_termin = dataRealisasi[x].termin;
        interfacerealisation.realisation_code = dataRealisasi[x].code;
        interfacerealisation.realisation_amount = parseFloat(dataRealisasi[x].realisation_amount);
        interfacerealisation.percentage = parseFloat(dataRealisasi[x].percent_realisation);

        realisationList.push({ ...interfacerealisation });
      }
    }

    if (dataUpah.length > 0) {
      upahList = [];
      dataUpah.forEach((x) => {
        if (dataCurrentUpah.length > 0) {
          dataCurrentUpah.forEach((y) => {
            if (x.upah_code == y.upah_code) {
              interfaceUpah.last_balance_qty = parseFloat(x.last_balance) + parseFloat(y.qty_used);
              interfaceUpah.current_qty = parseFloat(y.qty_used);
              interfaceUpah.balance_qty = interfaceUpah.last_balance_qty - interfaceUpah.current_qty;
              // interfacematerial.last_qty = parseFloat(x.last_balance) + parseFloat(y.qty_used);
              // interfacematerial.current_qty = parseFloat(y.qty_used);
              // interfacematerial.sisa_qty = interfacematerial.last_qty - interfacematerial.current_qty;
            }
          });
        }
        interfaceUpah.upah_code = x.upah_code;
        interfaceUpah.upah_name = x.job_name;
        interfaceUpah.unit = x.unit;
        interfaceUpah.price = parseFloat(x.price);

        interfaceUpah.current_nominal = interfaceUpah.price * interfaceUpah.current_qty;
        upahList.push({ ...interfaceUpah });
      });
    }

    createTableMaterialList();
    createTableRealisasiList();
    createTableUpahList();
  }

  async function getDataRealisasi(projectCode = '') {
    const urlRequest = route('admin.getDataRealisasiByProyek', projectCode);
    const method = 'GET';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      showerror(error);
      return null;
    }
  }
  async function getDataMaterial(projectCode = '') {
    const urlRequest = route('admin.getMaterialProyek', projectCode);
    const method = 'GET';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      showerror(error);
      return null;
    }
  }
  async function getDataUpah(projectCode = '') {
    const urlRequest = route('admin.getUpahProyek', projectCode);
    const method = 'GET';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      return await ajx.getData();
    } catch (error) {
      showerror(error);
      return null;
    }
  }
  async function populateData(projectCode = '') {
    fetchingdata.html('(fetching data....)');
    const dataRealisasi = await getDataRealisasi(projectCode);
    const dataMaterial = await getDataMaterial(projectCode);
    const dataUpah = await getDataUpah(projectCode);

    fetchingdata.html('');
    if (dataMaterial.length > 0) {
      materialList = [];
      dataMaterial.forEach((x) => {
        interfacematerial.item_code = x.item_code;
        interfacematerial.item_name = x.item_name;
        interfacematerial.last_qty = parseFloat(x.last_balance);
        interfacematerial.unit = x.unit_code;
        interfacematerial.current_qty = 0;
        interfacematerial.sisa_qty = parseFloat(x.last_balance);
        materialList.push({ ...interfacematerial });
      });
    }

    if (dataUpah.length > 0) {
      upahList = [];
      dataUpah.forEach((x) => {
        interfaceUpah.upah_code = x.upah_code;
        interfaceUpah.upah_name = x.job_name;
        interfaceUpah.unit = x.unit;
        interfaceUpah.price = parseFloat(x.price);
        interfaceUpah.last_balance_qty = parseFloat(x.last_balance);
        interfaceUpah.current_qty = 0;
        interfaceUpah.balance_qty = parseFloat(x.last_balance);
        interfaceUpah.current_nominal = 0;
        upahList.push({ ...interfaceUpah });
      });
    }

    if (dataRealisasi.length > 0) {
      realisationList = [];
      dataRealisasi.forEach((x) => {
        interfacerealisation.no_termin = x.termin;
        interfacerealisation.realisation_code = x.code;
        interfacerealisation.realisation_amount = parseFloat(x.realisation_amount);
        interfacerealisation.percentage = parseFloat(x.percent_realisation);

        realisationList.push({ ...interfacerealisation });
      });
    }
    createTableRealisasiList();
    createTableMaterialList();
    createTableUpahList();
  }

  function createTableMaterialList() {
    let html = '';
    let counter = 1;

    if (materialList.length > 0) {
      materialList.forEach((x) => {
        html += `   <tr>
                      <td class="text-nowrap p-1">${counter}</td>
                      <td class="p-1">${x.item_code}</td>
                      <td class="p-1">${x.item_name}</td>
                      <td class="p-1">${x.unit}</td>
                      <td class="text-nowrap p-1">${parseFloat(x.last_qty)}</td>
                      <td class="text-nowrap p-1"><input type="number" class="inputcurrentqty custom-input" style="font-size:13px" data-code="${
                        x.item_code
                      }" value="${parseFloat(x.current_qty)}"></td>
                      <td class="text-nowrap p-1">${parseFloat(x.sisa_qty)}</td>
                  </tr>`;
        counter++;
      });
      tabletbodymateriallist.html(html);
    }
  }

  function createTableUpahList() {
    let html = '';
    let totalCurentNominal = 0;

    if (upahList.length > 0) {
      upahList.forEach((x) => {
        html += `  
          <tr>
              <td style="width:10%;white-space:nowrap">${x.upah_code}</td>
              <td
                  style="width:20%;word-wrap:break-word;white-space: normal;"> ${x.upah_name}
              </td>
              <td
                  style="width:5%;overflow-wrap: break-word;word-wrap:break-word;white-space: normal;">
                  ${x.unit}</td>
              <td style="width:15%;white-space:nowrap">
                  ${formatRupiah1(x.price)}</td>
              <td style="width:10%;white-space:nowrap">
                  ${parseFloat(x.last_balance_qty)}
              </td>
              <td style="width: 10%;white-space:nowrap">
                  <input type="number" class="w-100 custom-input inputcurrentqtyupah" data-code="${x.upah_code}" value="${x.current_qty}">
              </td>
              <td style="width: 10%;white-space:nowrap;">
                  ${parseFloat(x.balance_qty)}
              </td>
              <td style="width: 20%;white-space: nowrap;">
                  ${formatRupiah1(x.current_nominal)}</td>
          </tr>
        
        `;
        totalCurentNominal += parseFloat(x.current_nominal);
      });
      html += `
        <tr>

          <td colspan="7" style="text-align:center"><b>TOTAL</b></td>
          <td style="text-align:left"><b>${formatRupiah1(totalCurentNominal)}</b></td>
        </tr>
      
      `;
      tabletbodyupahlist.html(html);
    }
  }

  function customValidation() {
    if (inputprojectcode.val() == '') {
      inputprojectcode.focus();
      showwarning('Project Code Cannot Be Blank');
      return false;
    }
    if (inputrealisationdate.val() == '') {
      inputrealisationdate.focus();
      showwarning('Realisation Date Cannot Be Blank');
      return false;
    }

    if (customerCode.val() == '') {
      customerCode.focus();
      showwarning('Customer Code Cannot be Blank');
      return false;
    }

    if (postData.realisation_amount == 0) {
      showwarning('Realisation Amount Cannot Be Zero');
      return false;
    }

    if (postData.percent_realisation == 0) {
      showwarning('Percentage Done Cannot Be Zero');
      return false;
    }

    let zeroCount = 0;
    for (let item of materialList) {
      if (item.current_qty == 0) {
        zeroCount += 1;
      }
    }
    if (zeroCount == materialList.length) {
      showwarning('Current Qty In Material List Cannot Be All Zero !');
      return false;
    }

    let zeroCount1 = 0;
    for (let item of upahList) {
      if (item.current_qty == 0) {
        zeroCount1 += 1;
      }
    }

    if (zeroCount1 == upahList.length) {
      showwarning('Current Qty In Upah List Cannot Be All Zero !');
      return false;
    }

    return true;
  }

  function createTableRealisasiList() {
    let html = '';
    let termin = realisationList.length + 1;

    postData.termin = postData.termin == 0 ? termin : postData.termin;
    let project_amount = parseFloat(parseToNominal(inputtotalproject.val()));
    let total = 0;
    if (realisationList.length > 0) {
      realisationList.forEach((x) => {
        html += `<tr>
                    <td>${x.no_termin}</td>
                    <td>${x.realisation_code}</td>
                    <td>${formatRupiah1(x.realisation_amount)}</td>
                    <td>${x.percentage}</td>
                </tr>`;

        total += x.realisation_amount;
      });
    }
    total += postData.realisation_amount;
    html += `
        <tr>
            <td>${postData.termin}</td>
            <td>${postData.realisation_code == '' ? 'AUTO' : postData.realisation_code}</td>
            <td><input type="text" class="inputrealisationamount custom-input" style="font-size:13px" value="${formatRupiah1(
              postData.realisation_amount
            )}"></td>
            <td><input type="number" min="1" class="custom-input inputpercentage" max="100" style="font-size:13px" value="${
              postData.percent_realisation
            }"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center"><b>TOTAL REALISATION</b>
            </td>
            <td><b>${formatRupiah1(total)}</b></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center"><b>AMOUNT IN PROGRESS</b>
            </td>
            <td><b>${formatRupiah1(project_amount - total)}</b></td>
            <td></td>
        </tr>
    `;

    tabletbdoytermin.html(html);
  }
  function parseToNominal(value) {
    if (typeof value == 'string') {
      // Menghapus karakter non-digit dan non-titik
      var cleanInput = value.replace(/[^\d,.]/g, '');

      // Mengganti koma menjadi titik
      var dotFormatted = cleanInput.replace(',', '.');

      // Menghapus karakter titik tambahan
      var finalResult = dotFormatted.replace(/\.(?=.*\.)/g, '');

      return parseFloat(finalResult);
    } else {
      return parseFloat(value);
    }
  }

  function inputOnlyNumber(objectElement) {
    let val = objectElement.val();
    // Regular expression to allow only digits and dot
    var regex = /^[0-9.]*$/;
    if (!regex.test(val)) {
      // Jika input tidak sesuai, hapus karakter terakhir
      objectElement.val(val.slice(0, -1));
    }
  }

  function calculateRealisation(amount, state = 'amount') {
    let total = 0;
    let total1 = 0;
    let allowedAmount = 0;
    let currentTermin = parseFloat(labelcurrenttermin.html());
    let totalTermin = parseFloat(labeltotaltermin.html());
    let project_amount = parseFloat(parseToNominal(inputtotalproject.val()));
    switch (state) {
      case 'amount':
        if (realisationList.length > 0) {
          realisationList.forEach((x) => {
            total += x.realisation_amount;
          });
        }
        total1 = total + amount;

        if (currentTermin < totalTermin) {
          if (total1 >= project_amount) {
            showwarning(`Exceed Maximum Amount!`);
            postData.realisation_amount = 0;
            createTableRealisasiList();
            return;
          }
          postData.realisation_amount = amount;
          createTableRealisasiList();
        } else {
          if (project_amount != total1) {
            showwarning('Last Termin Should Be Full !');
            allowedAmount = project_amount - total;
            postData.realisation_amount = allowedAmount;
            createTableRealisasiList();
            return;
          }
          postData.realisation_amount = amount;
          createTableRealisasiList();
        }
        break;

      case 'percentage':
        let listPercent = [];
        if (realisationList.length > 0) {
          realisationList.forEach((x) => {
            listPercent.push(x.percentage);
          });
        }

        if (currentTermin == totalTermin) {
          if (amount != 100) {
            showwarning('Last Termin Should 100%');
            allowedAmount = 100;
            postData.percent_realisation = allowedAmount;
            createTableRealisasiList();
            return;
          }
          postData.percent_realisation = amount;
          createTableRealisasiList();
          return;
        }

        if (currentTermin < totalTermin) {
          if (amount >= 100) {
            showwarning('Not Last Termin Should Be Less Than 100%');
            allowedAmount = 0;
            postData.percent_realisation = allowedAmount;
            createTableRealisasiList();
            return;
          }

          if (listPercent.length > 0) {
            if (amount <= listPercent[listPercent.length - 1]) {
              showwarning('Percentage Must Be More Than Last Percentage!');
              allowedAmount = 0;
              postData.percent_realisation = allowedAmount;
              createTableRealisasiList();
              return;
            }
            postData.percent_realisation = amount;
            createTableRealisasiList();
            return;
          }
          postData.percent_realisation = amount;
          createTableRealisasiList();
        }

        break;

      default:
        break;
    }
  }

  function updateMaterialList(code, amount) {
    let currentTermin = parseFloat(labelcurrenttermin.html());
    let totalTermin = parseFloat(labeltotaltermin.html());
    const updatedMaterial = materialList.map((x) => {
      let amountInput = 0;
      if (x.item_code == code) {
        amountInput = parseFloat(amount);
        let sisaQty = x.last_qty - amountInput;

        if (currentTermin < totalTermin && sisaQty <= 0) {
          showwarning('Not Last Termin, Balance Qty Cannot Be Zero!');
          amountInput = 0;
          sisaQty = x.last_qty - amountInput;
        }

        if (currentTermin == totalTermin && sisaQty < 0) {
          showwarning('Balance Qty Cannot be Minus !');
          amountInput = 0;
          sisaQty = x.last_qty - amountInput;
        }

        return { ...x, current_qty: parseFloat(amountInput), sisa_qty: sisaQty };
      } else {
        return x;
      }
    });

    materialList = [...updatedMaterial];
    createTableMaterialList();
  }

  function updateUpahList(code, amount) {
    let currentTermin = parseFloat(labelcurrenttermin.html());
    let totalTermin = parseFloat(labeltotaltermin.html());
    const updatedUpah = upahList.map((x) => {
      let amountInput = 0;
      if (x.upah_code == code) {
        amountInput = parseFloat(amount);
        let sisaQty = x.last_balance_qty - amountInput;
        let currentNominal = x.price * amountInput;

        if (currentTermin < totalTermin && sisaQty <= 0) {
          showwarning('Not Last Termin, Balance Qty Cannot Be Zero!');
          amountInput = 0;
          sisaQty = x.last_balance_qty - amountInput;
          currentNominal = 0;
        }

        if (currentTermin == totalTermin && sisaQty < 0) {
          showwarning('Balance Qty Cannot be Minus !');
          amountInput = 0;
          sisaQty = x.last_balance_qty - amountInput;
          currentNominal = 0;
        }

        return { ...x, current_qty: amountInput, balance_qty: sisaQty, current_nominal: currentNominal };
      } else {
        return x;
      }
    });

    upahList = [...updatedUpah];
    createTableUpahList();
  }

  function populatePostData() {
    postData.project_code = inputprojectcode.val();
    postData.customer_code = customerCode.val();
    postData.description = inputdescription.val();
    postData.project_amount = parseToNominal(inputtotalproject.val());
    postData.realisation_date = inputrealisationdate.val();
    postData.detailA = [...materialList];
    postData.detailB = [...upahList];
  }

  async function postAjax() {
    let urlRequest = '';

    if (updateMode) {
      let code = inputrealisationcode.val();
      urlRequest = route('admin.editrealisation', code);
    } else {
      urlRequest = route('admin.addRealisation');
    }
    const method = 'POST';
    let response = '';
    let formData = createFormData();

    try {
      const ajx = new AjaxRequest(urlRequest, method, formData);
      response = await ajx.getData();
      return response;
    } catch (error) {
      showerror(error);
      return false;
    }
  }

  function createFormData() {
    var formData = new FormData();

    formData.append('customer_code', postData.customer_code);
    formData.append('description', postData.description);
    formData.append('percent_realisation', postData.percent_realisation);
    formData.append('project_amount', postData.project_amount);
    formData.append('project_code', postData.project_code);
    formData.append('realisation_amount', postData.realisation_amount);
    formData.append('realisation_date', postData.realisation_date);
    formData.append('termin', postData.termin);
    formData.append('detailA', JSON.stringify(postData.detailA));
    formData.append('detailB', JSON.stringify(postData.detailB));
    return formData;
  }
  // ====================================================================

  // CRUD and Event
  // ====================================================================

  $('#modalProjectRealisationSearch').on('hidden.bs.modal', async function (e) {
    let projectCode = inputprojectcode.val();
    if (projectCode != '') {
      realisationList = [];
      postData.percent_realisation = 0;
      postData.realisation_amount = 0;
      populateData(projectCode);
    }
  });
  $(document).on('blur', '.inputrealisationamount, .inputpercentage, .inputcurrentqty, .inputcurrentqtyupah', function () {
    let code = $(this).data('code');
    if ($(this).val() == '') {
      $(this).val(0);
    }
    switch (true) {
      case $(this).hasClass('inputrealisationamount'):
        calculateRealisation(parseFloat($(this).val()), 'amount');
        break;
      case $(this).hasClass('inputpercentage'):
        calculateRealisation(parseFloat($(this).val()), 'percentage');
        break;
      case $(this).hasClass('inputcurrentqty'):
        updateMaterialList(code, parseFloat($(this).val()));
        break;
      case $(this).hasClass('inputcurrentqtyupah'):
        updateUpahList(code, parseFloat($(this).val()));
        break;
      default:
        break;
    }
  });

  $(document).on('focusin', '.inputrealisationamount, .inputpercentage,  .inputcurrentqty, .inputcurrentqtyupah', function () {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  $(document).on('keyup', '.inputrealisationamount', function () {
    var object = $(this);
    inputOnlyNumber(object);
  });

  $(document).on('click', '.btn-submit', async function () {
    if (validateInput(dataInput, customValidation)) {
      $(this).find('span').html('Submitting...');
      $(this).prop('disabled', true);
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.projectrealisationview');
      if (response) {
        window.location.href = urlRedirect;
      } else {
        $(this).find('span').html('Submit');
        $(this).prop('disabled', false);
      }
    }
  });

  // ====================================================================
});
