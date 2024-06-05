import AjaxRequest from '../ajaxrequest.js';
import { showwarning, showerror } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import validateInput from '../validateInput.js';
import initiatedtp from '../datepickerinitiator.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import managedate from '../managedate.js';
$(document).ready(function () {
  const Date = new managedate();

  // Property
  // =====================================
  // Html Input
  const inputvoucherno = $('.inputvoucherno ');
  const inputjournaltype = $('.inputjournaltype');
  const inputref_no = $('.inputref_no');
  const inputcoacode = $('.inputcoacode');
  const inputcoaname = $('.inputcoaname');
  const inputamount = $('.inputamount');
  const inputdebitkredit = $('.inputdebitkredit');
  const tbodytablelistcoa = $('.tablelistcoa tbody');
  const dtptransdate = $('#dtptransdate');
  const inputtransdate = $('.inputtransdate');
  const inputdescription = $('.inputdescription');
  const lbldescription = $('.lbldescription')
  // End Html Input

  // Property Data when in update mode
  const datatransadate = $('.inputtransdate').data('transdate');
  const datajournaltype = $('.datajournaltype').data('journaltype');
  const datadetail = $('.datadetail').data('detail');
  // End Property when in update mode

  // Tampungan Parsing hasil data update mode
  let detailpr = [''];

  // State
  const updateMode = route().current() == 'admin.editJournalView';

  //Validation Input
  let dataInput = ['.inputref_no','.inputdescription'];

  // Data For Send TO Controller
  let PostData = {
    voucher_no: '',
    transaction_date: '',
    ref_no: '',
    journal_type_code: '',
    description: '',
    detail: []
  };

  let detail = {
    coa_code: '',
    coa_name: '',
    description: '',
    debit: 0.0,
    credit: 0.0
  };

  let tampungDetail = [];
  // ====================================

  // Inisiasi Datepircker dan input
  // ======================================
  initiatedtp(dtptransdate);

	inputamount.val(formatRupiah1(0))
  if (updateMode) {
    prepareEdit();
  } else {
    inputtransdate.val(Date.getNowDate());
    // inputdaterequired.val(Date.getNowDate());
    // setTransAmount();
  }

  // ===================================

  // Function and subroutines
  // ===================================

  function prepareEdit() {
    inputtransdate.val(moment(datatransadate).format('DD/MM/YYYY'));
    inputjournaltype.val(datajournaltype);
    inputjournaltype.attr("disabled", true);

    inputref_no.attr('readonly', true)
    inputdescription.attr("hidden" , true)
    inputdescription.val('-')
    lbldescription.attr("hidden", true)
    
    datadetail.forEach((x)=> {
      detail.coa_code = x.coa_code
      detail.coa_name = x.coa_name
      detail.debit = parseFloat(x.debit)
      detail.credit = parseFloat(x.kredit)
      detail.description = x.description
      tampungDetail.push({ ...detail });
    })


    createHtmTBodyItem();
  }

  function customValidation() {
    if (inputtransdate.val() == '') {
      inputtransdate.focus();
      showwarning('Transaction Date Cannot Be Empty');
      return false;
    }

		if(tampungDetail.length == 0){
			showwarning('Please Insert COA !');
			return false;
		}

		let invalid = 0;
		let totalDebit = 0;
		let totalCredit = 0;
    for (let x of tampungDetail) {

			totalDebit += parseFloat(x.debit);
			totalCredit += parseFloat(x.credit);
      if (parseFloat(x.debit) == 0 && (parseFloat(x.credit) == 0)) {
        invalid += 1;
      }
    }
		if (invalid > 0){
			showwarning("Amount Debit Or Credit Cannot Be Zero In Coa List !")
			return false;
		}

		if ( totalDebit != totalCredit){
			showwarning("Amount Debit Or Credit Not Balance !")
			return false;
		}



    return true;
  }

  function createHtmTBodyItem() {
    let html = ``;
  
		let totalDebit  = 0;
		let totalKredit = 0;
    tampungDetail.forEach((item) => {

      if (updateMode){
        html += `
        <tr>
          <td style="font-size: 12px; width:10%">${item.coa_code}</td>
          <td style="font-size: 12px; width:25%;white-space:nowrap">${item.coa_name}
          </td>
          <td style="font-size: 12px;width:20%"><input class="custom-input inputdescriptiondetail" data-code="${item.coa_code}" type="text"
            style="width: 100%" value="${item.description}">
          </td>
          <td style="font-size: 12px;width:20%;white-space:nowrap"><input class="custom-input inputdebit" data-code="${item.coa_code}" type="text"
                  style="width: 100%" value="${formatRupiah1(item.debit)}">
          </td>
          <td style="font-size: 12px;width:20%;white-space:nowrap"><input class="custom-input inputkredit"  data-code="${item.coa_code}" type="text"
                  style="width: 100%"  value="${formatRupiah1(item.credit)}">
          </td>
          <td style="white-space:normal;word-wrap"><button data-code="${item.coa_code}"
              class="btn btn-danger btndeletecoa btn-sm">X</button></td>
        </tr>
        `;

      } else {
        html += `
        <tr>
          <td style="font-size: 12px; width:15%">${item.coa_code}</td>
          <td style="font-size: 12px; width:20%;white-space:nowrap">${item.coa_name}
          </td>
          <td style="font-size: 12px;width:30%;white-space:nowrap"><input class="custom-input inputdebit" data-code="${item.coa_code}" type="text"
                  style="width: 100%" value="${formatRupiah1(item.debit)}">
          </td>
          <td style="font-size: 12px;width:30%;white-space:nowrap"><input class="custom-input inputkredit"  data-code="${item.coa_code}" type="text"
                  style="width: 100%"  value="${formatRupiah1(item.credit)}">
          </td>
          <td style="white-space:normal;word-wrap"><button data-code="${item.coa_code}"
              class="btn btn-danger btndeletecoa btn-sm">X</button></td>
        </tr>
        `;
      }
  
			totalDebit += item.debit;
			totalKredit += item.credit;
    });
    if (tampungDetail.length > 0) {
      if (updateMode) {
        html += `
        <tr>
          <td colspan="3" style="font-size: 12px;font-weight:bold;text-align:right">TOTAL</td>
          <td style="font-size: 12px;font-weight:bold">${formatRupiah1(totalDebit)}</td>
          <td style="font-size: 12px;font-weight:bold">${formatRupiah1(totalKredit)}</td>
        </tr>
        `
      } else {
        
        html += `
        <tr>
            <td colspan="2" style="font-size: 12px;font-weight:bold;text-align:right">TOTAL</td>
            <td style="font-size: 12px;font-weight:bold">${formatRupiah1(totalDebit)}</td>
            <td style="font-size: 12px;font-weight:bold">${formatRupiah1(totalKredit)}</td>
        </tr>
      `;
      }
    }

    tbodytablelistcoa.html(html);
  }

	function checkCOA(coa_code){
		if (tampungDetail.length > 0) {
			return tampungDetail.some( item => item.coa_code == coa_code )
		}
		return false
	}

  function populateCOA() {
    
		if (inputcoacode.val() == ''){
			inputcoacode.focus();
			showwarning("Please Select COA !")
			return;
		}

		detail.coa_code = inputcoacode.val()
		detail.coa_name = inputcoaname.val()
		detail.debit = inputdebitkredit.val() == 'd' ? parseToNominal(inputamount.val()) : 0.00;
		detail.credit= inputdebitkredit.val() == 'k' ? parseToNominal(inputamount.val()) : 0.00;
    detail.description = '';
		if (checkCOA(detail.coa_code)){
			showwarning(`COA Code : ${detail.coa_code} Already In The List`)
			return;
		}
		tampungDetail.push({...detail})
		inputcoacode.val("")
		inputcoaname.val("")
		inputamount.val(formatRupiah1(0))
    createHtmTBodyItem();
  }

  function calcCOA(code, amount, state = 'debit', desc='') {
    switch (state) {
      case 'debit':
        const editedDetail = tampungDetail.map((item) => {
          if (item.coa_code === code) {
            let debit_amountNew = parseFloat(amount);

            return { ...item, debit: debit_amountNew, credit: 0 };
          } else {
            return item;
          }
        });
        tampungDetail = [...editedDetail];
        createHtmTBodyItem();
        break;
      case 'kredit':
        const editedDetail1 = tampungDetail.map((item) => {
          if (item.coa_code === code) {
            let credit_amountNew = parseFloat(amount);
            return { ...item, credit: credit_amountNew, debit: 0 };
          } else {
            return item;
          }
        });
        tampungDetail = [...editedDetail1];
				createHtmTBodyItem();
        break;

        case 'description':
          const editedDetail2 = tampungDetail.map((item) => {
            if (item.coa_code === code) {
              let description = desc;
              return { ...item, description :description};
            } else {
              return item;
            }
          });
          tampungDetail = [...editedDetail2];
          createHtmTBodyItem();
          break;
    }

    
  }


  function populatePostData() {
    PostData.voucher_no = '';
    PostData.description = inputdescription.val();
    PostData.journal_type_code = inputjournaltype.val();
    PostData.ref_no = inputref_no.val().trim();
    PostData.transaction_date = inputtransdate.val();
    PostData.detail = [];
    tampungDetail.forEach((item) => {
      detail.coa_code = item.coa_code; 
      detail.coa_name = item.coa_name; 
      detail.debit = item.debit; 
      detail.credit = item.credit;
      detail.description = item.description
      PostData.detail.push({ ...detail });
    });
  }

	function deleteCOA(code){
		const updatedListCOA = tampungDetail.filter((x) => {
			return x.coa_code !== code;
		});

		tampungDetail = [...updatedListCOA];
		createHtmTBodyItem();
	}

  async function postAjax() {
    let urlRequest = '';

    if (updateMode) {
      let code = inputvoucherno.val();
      urlRequest = route('admin.editJournal', code);
    } else {
      urlRequest = route('admin.addjournal');
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

    formData.append('voucher_no', PostData.voucher_no);
    formData.append('ref_no', PostData.ref_no.trim());
    formData.append('journal_type_code', PostData.journal_type_code.trim());
    formData.append('description', PostData.description);
    formData.append('transaction_date', PostData.transaction_date);
    formData.append('detail', JSON.stringify(PostData.detail));

    return formData;
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


  // ====================================

  // Event And Crud
  // ====================================

  $(document).on('blur', '.inputdebit', function () {
    if ($(this).val() == '') {
      $(this).val(0);
    }
    let code = $(this).data('code');
    calcCOA(code, parseFloat($(this).val()), 'debit');
  });

  $(document).on('focusin', '.inputdebit', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  $(document).on('keyup', '.inputdebit', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

	
  $(document).on('blur', '.inputkredit', function () {
    if ($(this).val() == '') {
      $(this).val(0);
    }
    let code = $(this).data('code');
    calcCOA(code, parseFloat($(this).val()), 'kredit');
  });

  $(document).on('blur', '.inputdescriptiondetail', function () {

    let code = $(this).data('code');
    calcCOA(code, 0, 'description' ,$(this).val());
  });

  $(document).on('focusin', '.inputkredit', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  $(document).on('keyup', '.inputkredit', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  $(document).on('blur', '.inputamount', function () {
    if ($(this).val() == '') {
      $(this).val(formatRupiah1(0));
    }
		$(this).val(formatRupiah1($(this).val()))
  });



  $(document).on('focusin', '.inputamount', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  $(document).on('keyup', '.inputamount', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });


  $(document).on('click', '.btnaddcoa', function (event) {
		populateCOA()
	});

  $(document).on('click', '.btndeletecoa', function (event) {
		let code =  $(this).data("code");
		deleteCOA(code)
	});

  // Submit Button
  $(document).on('click', '.submitbtn', async function () {
    if (validateInput(dataInput, customValidation)) {
      $(this).find('span').html('Submitting...');
      $(this).prop('disabled', true);
      populatePostData();
      const response = await postAjax();
      const urlRedirect = route('admin.journal');
      if (response) {
        window.location.href = urlRedirect;
      } else {
        updateMode ? $(this).find('span').html('Update') : $(this).find('span').html('Save');
        $(this).prop('disabled', false);
      }
    }
  });

  $('#modalCustomerSearch').on('hidden.bs.modal', async function (e) {
    populateInvoice(customerCode.val());
  });
  // ===================================
});
