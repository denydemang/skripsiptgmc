import AjaxRequest from '../ajaxrequest.js';
import { showwarning, showerror, showconfirmfinish } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import validateInput from '../validateInput.js';
import initiatedtp from '../datepickerinitiator.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import managedate from '../managedate.js';

$(document).ready(function () {
  const Date = new managedate();

  // Html Input
  const inputcode = $('.inputcode');
  const inputfinishdate = $('.inputfinishdate');
  const dtpfinishdate = $('#dtpfinishdate');
  const databahanbaku = $('.databahanbaku');
  const dataupah = $('.dataupah');
  const btnsetusedqtymaterial = $('.btnsetusedqtymaterial');
  const btnsetusedqtyzeromaterial = $('.btnsetusedqtyzeromaterial');
  const btnsetusedqtyupah = $('.btnsetusedqtyupah');
  const btnsetusedqtyzeroupah = $('.btnsetusedqtyzeroupah');
  const checkedallmaterial = $('.checkedallmaterial');
  const checkedallupah = $('.checkedallupah');
  const tableupahtbody = $('.tableupah tbody');
  const tablematerialtbody = $('.tablematerial tbody');
  const interfaceupah = {
    upah_code: '',
    job: '',
    unit: '',
    qty_estimated: 0,
    qty_used: 0,
    price: 0,
    totalestimated: 0,
    total: 0,
    checked: false
  };
  const interfacematerial = {
    item_code: '',
    item_name: '',
    unit_code: '',
    qty_estimated: 0,
    qty_used: 0,
    stocks: 0,
    checked: false
  };

  let materiallist = [];
  let upahlist = [];

  // Inisiasi DTP finsih date
  initiatedtp(dtpfinishdate);
  inputfinishdate.val(Date.getNowDate());

  // Load Function
  // =============================================
  loadPage();
  // =============================================

  // function and procedures
  // ==================================================

  function loadPage() {
    populateNParsingData();
    CreateHtmlTbodyMaterial();
    CreateHtmlTbodyUpah();
  }
  function populateNParsingData() {
    let Materials = JSON.parse(JSON.parse(databahanbaku.data('bahanbaku')));
    let Upahs = JSON.parse(JSON.parse(dataupah.data('upah')));
    Materials.forEach((x) => {
      interfacematerial.item_name = x.item_name;
      interfacematerial.item_code = x.item_code;
      interfacematerial.qty_estimated = parseFloat(x.qty);
      interfacematerial.qty_used = 0;
      interfacematerial.stocks = parseFloat(x.qty) + parseFloat(x.stocks);
      interfacematerial.unit_code = x.unit_code;

      materiallist.push({ ...interfacematerial });
    });

    Upahs.forEach((x) => {
      interfaceupah.job = x.job;
      interfaceupah.upah_code = x.upah_code;
      interfaceupah.unit = x.unit;
      interfaceupah.qty_estimated = parseFloat(x.qty);
      interfaceupah.qty_used = 0;
      interfaceupah.totalestimated = parseFloat(x.total);
      interfaceupah.price = parseFloat(x.price);
      interfaceupah.total = 0;

      upahlist.push({ ...interfaceupah });
    });
  }

  function CreateHtmlTbodyMaterial() {
    let html = '';
    let counter = 1;
    materiallist.forEach((x) => {
      html += `
      <tr>
        <td style="font-size: 10px; width:5%"><input type="checkbox" class="checklistitem" ${x.checked ? 'checked' : ''} 
        data-code="${x.item_code}"></td>
        <td style="font-size: 10px;width:5%">${counter}</td>
        <td style="font-size: 10px; width:20%">${x.item_code}</td>
        <td style="font-size: 10px; width:25%">${x.item_name}
        </td>
        <td style="font-size: 10px;width:15%">${x.unit_code}</td>
        <td style="font-size: 10px;width:10%; white-space:nowrap">${x.qty_estimated}</td>
        <td style="font-size: 10px;width:10%;white-space:nowrap"><input type="number" data-stock="${x.stocks}" data-code="${x.item_code}" 
          class="custom-input inputusedqtymaterial" value="${x.qty_used}">
        </td>
        <td style="font-size: 10px;width:10%;white-space:nowrap">${x.stocks}</td>
      </tr>
      `;

      counter++;
    });
    tablematerialtbody.html(html);
  }
  function CreateHtmlTbodyUpah() {
    let html = '';
    let counter = 1;
    let Total = 0;
    let TotalEstimated = 0;

    upahlist.forEach((x) => {
      Total += parseFloat(x.total);
      TotalEstimated += parseFloat(x.totalestimated);
      html += `
      <tr>
        <td style="font-size: 10px"><input type="checkbox" class="checklistupah" ${x.checked ? 'checked' : ''}  data-code="${x.upah_code}">
        </td>
        <td style="font-size: 10px">${counter}</td>
        <td style="font-size: 10px">${x.upah_code}</td>
        <td style="font-size: 10px">${x.job}</td>
        <td style="font-size: 10px">${x.unit}</td>
        <td style="font-size: 10px;white-space:nowrap">${x.qty_estimated}</td>
        <td style="font-size: 10px;white-space:nowrap"><input type="number" class="custom-input inputusedqtyupah" data-code="${
          x.upah_code
        }" value="${x.qty_used}">
        </td>
        <td style="font-size: 10px;white-space:nowrap">${formatRupiah1(x.price)}</td>
        <td style="font-size: 10px;white-space:nowrap">${formatRupiah1(x.totalestimated)}</td>
        <td style="font-size: 10px;white-space:nowrap">${formatRupiah1(x.total)}</td>
      </tr>
      `;

      counter++;
    });
    const selisih = TotalEstimated - Total;
    const keterangan = selisih < 0 ? '(Minus)' : '';
    html += `
      <tr>
        <td style="font-size: 10px" align="right" colspan="8"><strong>TOTAL</strong></td>
        <td style="font-size: 10px"><strong>${formatRupiah1(TotalEstimated)}</strong></td>
        <td style="font-size: 10px"><strong>${formatRupiah1(Total)}</strong></td>
      </tr>
      <tr>
        <td style="font-size: 10px" align="right" colspan="8"><strong>SELISIH</strong></td>
        <td style="font-size: 10px" align="center" colspan="2">
        <strong>
          ${formatRupiah1(parseFloat(selisih) < 0 ? -1 * parseFloat(selisih) : selisih)} ${keterangan}

        </strong>
        </td>
      </tr>
    
    `;
    tableupahtbody.html(html);
  }

  function updateCheckedMaterial(code, bool) {
    const UpdatedMaterial = materiallist.map((x) => {
      if (x.item_code == code) {
        return { ...x, checked: bool };
      } else {
        return x;
      }
    });
    materiallist = [...UpdatedMaterial];
  }
  function updateAllCheckedMaterial(bool) {
    const UpdatedMaterial = materiallist.map((x) => {
      return { ...x, checked: bool };
    });
    materiallist = [...UpdatedMaterial];
  }
  function updateAllCheckedUpah(bool) {
    const UpdatedUpah = upahlist.map((x) => {
      return { ...x, checked: bool };
    });
    upahlist = [...UpdatedUpah];
  }
  function updateCheckedUpah(code, bool) {
    const UpdatedUpah = upahlist.map((x) => {
      if (x.upah_code == code) {
        return { ...x, checked: bool };
      } else {
        return x;
      }
    });
    upahlist = [...UpdatedUpah];
  }

  function isAnycheckedMaterial() {
    let isAnyChecked = false;
    materiallist.forEach((x) => {
      if (x.checked) {
        isAnyChecked = true;
      }
    });

    return isAnyChecked;
  }
  function isAnycheckedUpah() {
    let isAnyChecked = false;
    upahlist.forEach((x) => {
      if (x.checked) {
        isAnyChecked = true;
      }
    });

    return isAnyChecked;
  }
  function isAllcheckedMaterial() {
    let isAnyChecked = true;
    materiallist.forEach((x) => {
      if (!x.checked) {
        isAnyChecked = false;
      }
    });

    return isAnyChecked;
  }
  function isAllcheckedUpah() {
    let isAnyChecked = true;
    upahlist.forEach((x) => {
      if (!x.checked) {
        isAnyChecked = false;
      }
    });

    return isAnyChecked;
  }

  function showButonSetUsedQTY(listName) {
    switch (listName) {
      case 'listmaterial':
        if (isAnycheckedMaterial()) {
          btnsetusedqtymaterial.addClass('d-block');
          btnsetusedqtymaterial.removeClass('d-none');
          btnsetusedqtyzeromaterial.addClass('d-block');
          btnsetusedqtyzeromaterial.removeClass('d-none');
        } else {
          btnsetusedqtymaterial.addClass('d-none');
          btnsetusedqtymaterial.removeClass('d-block');
          btnsetusedqtyzeromaterial.addClass('d-none');
          btnsetusedqtyzeromaterial.removeClass('d-block');
        }
        break;

      case 'listupah':
        if (isAnycheckedUpah()) {
          btnsetusedqtyupah.addClass('d-block');
          btnsetusedqtyupah.removeClass('d-none');
          btnsetusedqtyzeroupah.addClass('d-block');
          btnsetusedqtyzeroupah.removeClass('d-none');
        } else {
          btnsetusedqtyupah.addClass('d-none');
          btnsetusedqtyupah.removeClass('d-block');
          btnsetusedqtyzeroupah.addClass('d-none');
          btnsetusedqtyzeroupah.removeClass('d-block');
        }
        break;
    }
  }

  function isCheckedCheckboxAll(namelist) {
    if (namelist == 'listmaterial') {
      if (isAllcheckedMaterial()) {
        checkedallmaterial.prop('checked', true);
      } else {
        checkedallmaterial.prop('checked', false);
      }
    } else if (namelist == 'listupah') {
      if (isAllcheckedUpah()) {
        checkedallupah.prop('checked', true);
      } else {
        checkedallupah.prop('checked', false);
      }
    }
  }

  function setUsedQtyAsEstimatedQTY(listName) {
    switch (listName) {
      case 'listmaterial':
        const UpdatedMaterial = materiallist.map((x) => {
          if (x.checked) {
            const qtyestimated = x.qty_estimated;
            return { ...x, qty_used: qtyestimated };
          } else {
            return x;
          }
        });
        materiallist = [...UpdatedMaterial];
        break;
      case 'listupah':
        const UpdatedUpah = upahlist.map((x) => {
          if (x.checked) {
            const qtyestimated = x.qty_estimated;
            const total = x.price * qtyestimated;
            return { ...x, qty_used: qtyestimated, total: total };
          } else {
            return x;
          }
        });
        upahlist = [...UpdatedUpah];
        break;
    }
  }
  function setUsedQTYAsZero(listName) {
    switch (listName) {
      case 'listmaterial':
        const UpdatedMaterial = materiallist.map((x) => {
          if (x.checked) {
            return { ...x, qty_used: 0 };
          } else {
            return x;
          }
        });
        materiallist = [...UpdatedMaterial];
        break;

      case 'listupah':
        const UpdatedUpah = upahlist.map((x) => {
          if (x.checked) {
            return { ...x, qty_used: 0, total: 0 };
          } else {
            return x;
          }
        });
        upahlist = [...UpdatedUpah];
        break;
    }
  }
  function changeQTY(code, qty, listName) {
    switch (listName) {
      case 'listmaterial':
        const UpdatedMaterial = materiallist.map((x) => {
          if (x.item_code == code) {
            return { ...x, qty_used: qty };
          } else {
            return x;
          }
        });
        materiallist = [...UpdatedMaterial];
        break;
      case 'listupah':
        const UpdatedUpah = upahlist.map((x) => {
          if (x.upah_code == code) {
            return { ...x, qty_used: qty, total: qty * parseFloat(x.price) };
          } else {
            return x;
          }
        });
        upahlist = [...UpdatedUpah];
        break;
    }
  }

  async function postAjax(code, object) {
    const urlRequest = route('admin.finishproject', code);
    const urlRedirect = route('admin.projectrealisationview');

    const method = 'POST';
    let response = '';
    const supplyData = createFormData();

    object.find('span').html('Loading ....');
    object.prop('disabled', true);
    try {
      const ajx = new AjaxRequest(urlRequest, method, supplyData);
      response = await ajx.getData();
      if (response) {
        window.location.href = urlRedirect;
      }
    } catch (error) {
      object.find('span').html('Submit & Finish Project');
      object.prop('disabled', false);
      showerror(error);
    }
  }

  function createFormData() {
    var formData = new FormData();

    formData.append('materials', JSON.stringify(materiallist));
    formData.append('upahs', JSON.stringify(upahlist));
    formData.append('finish_date', inputfinishdate.val());

    return formData;
  }

  function validate() {
    let valid = true;
    if (inputfinishdate.val() == '') {
      inputfinishdate.focus();
      showwarning('Finish Date Cannot Be Empty');
      valid = false;
    }

    for (let x of materiallist) {
      if (parseFloat(x.qty_used) == 0) {
        showwarning(`Qty Used Item Code : ${x.item_code} In Material List Cannot Be Zero`);
        valid = false;
        break;
      }
    }

    for (let x of upahlist) {
      if (parseFloat(x.qty_used) == 0) {
        showwarning(`Qty Used Upah Code : ${x.upah_code} In Upah List Cannot Be Zero`);
        valid = false;
        break;
      }
    }
    return valid;
  }

  // ==================================================

  // Event and CRUD
  // =========================================================

  // event ketika checkbox peritem pada list material terclick atau berubah
  $(document).on('change', '.checklistitem', function () {
    let itemcode = $(this).data('code');
    let ischecked = $(this)[0].checked;

    updateCheckedMaterial(itemcode, ischecked);
    showButonSetUsedQTY('listmaterial');
    isCheckedCheckboxAll('listmaterial');
  });

  // event ketika checkbox peritem pada listupah terclick atau berubah
  $(document).on('change', '.checklistupah', function () {
    let upahcode = $(this).data('code');
    let ischecked = $(this)[0].checked;

    updateCheckedUpah(upahcode, ischecked);
    showButonSetUsedQTY('listupah');
    isCheckedCheckboxAll('listupah');
  });

  // Event Check All Material
  $(document).on('change', '.checkedallmaterial', function () {
    let ischecked = $(this)[0].checked;

    updateAllCheckedMaterial(ischecked);
    showButonSetUsedQTY('listmaterial');
    CreateHtmlTbodyMaterial();
  });

  // Event Check All Upah
  $(document).on('change', '.checkedallupah', function () {
    let ischecked = $(this)[0].checked;

    updateAllCheckedUpah(ischecked);
    showButonSetUsedQTY('listupah');
    CreateHtmlTbodyUpah();
  });

  // Event Check Click Btn Set UsedQty Material As EstimatedQty
  $(document).on('click', '.btnsetusedqtymaterial', function () {
    setUsedQtyAsEstimatedQTY('listmaterial');
    CreateHtmlTbodyMaterial();
  });

  // Event Check Click Btn Set UsedQty Material As Zero
  $(document).on('click', '.btnsetusedqtyzeromaterial', function () {
    setUsedQTYAsZero('listmaterial');
    CreateHtmlTbodyMaterial();
  });

  // Event Check Click Btn Set UsedQty Upah As EstimatedQty
  $(document).on('click', '.btnsetusedqtyupah', function () {
    setUsedQtyAsEstimatedQTY('listupah');
    CreateHtmlTbodyUpah();
  });

  // Event Check Click Btn Set UsedQty Upah As Zero
  $(document).on('click', '.btnsetusedqtyzeroupah', function () {
    setUsedQTYAsZero('listupah');
    CreateHtmlTbodyUpah();
  });

  $(document).on('focusin', '.inputusedqtymaterial', function () {
    if (parseFloat($(this).val()) == 0) {
      $(this).val('');
    }
  });

  $(document).on('focusin', '.inputusedqtyupah', function () {
    if (parseFloat($(this).val()) == 0) {
      $(this).val('');
    }
  });

  // Event Input Used Qty Material
  $(document).on('blur', '.inputusedqtymaterial', function () {
    if ($(this).val() <= 0 || $(this).val() == '') {
      $(this).val(1);
    }
    let code = $(this).data('code');
    let stock = parseFloat($(this).data('stock'));
    let qty = parseFloat($(this).val());

    if (qty > stock) {
      alert('Minus Stocks Not Allowed : Current Stock Is ' + stock);
      $(this).val(stock);
      changeQTY(code, stock, 'listmaterial');
    } else {
      changeQTY(code, qty, 'listmaterial');
    }
    CreateHtmlTbodyMaterial();
  });

  // Event Input Used Qty Upah
  $(document).on('blur', '.inputusedqtyupah', function () {
    if ($(this).val() <= 0 || $(this).val() == '') {
      $(this).val(1);
    }
    let code = $(this).data('code');
    let qty = parseFloat($(this).val());
    changeQTY(code, qty, 'listupah');
    CreateHtmlTbodyUpah();
  });

  //
  $(document).on('click', '.btn-submit', async function () {
    let projectcode = inputcode.val();
    let currentObject = $(this);
    if (validate()) {
      showconfirmfinish(projectcode, currentObject, postAjax, 'Project With Code ');
    }
  });

  // =========================================================
});
