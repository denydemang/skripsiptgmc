import AjaxRequest from '../ajaxrequest.js';
import { iziSuccess } from '../izitoast.js';
import { showconfirmdelete, showconfirmdeletecustommsg, showerror, showwarning } from '../jqueryconfirm.js';
import handleerror from '../handleerror.js';
import { formatRupiah, formatRupiah1 } from '../rupiahformatter.js';
$(document).ready(function () {
  let divTree = $('#coalist');
  let coa = null;
  let updatemode = false;
  const PostData = {
    code: '',
    name: '',
    type: '',
    level: '',
    default_dk: '',
    description: '',
    beginning_balance: 0
  };

  // Property Element Html
  // ==================================================

  const modalcoa = $('#modalcoa');
  const inputcode = $('.inputcode');
  const inputname = $('.inputname');
  const modaltitle = $('.modal-title');
  const inputtype = $('.inputtype');
  const inputlevel = $('.inputlevel');
  const inputdk = $('.inputdk');
  const inputdescription = $('.inputdescription');
  const inputbeginning = $('.inputbeginning');
  const btnsave = $('.btnsave');

  // ==================================================

  // Inisiasi treegrid load form
  // =================================================

  getTreeCoa();
  disableInputBeginBalance();
  // ================================================

  // Function And Subroutines
  // ====================================================
  function renderTree() {
    divTree.jstree('destroy').empty();
    divTree
      .jstree({
        plugins: ['types', 'grid', 'contextmenu'],
        core: {
          data: coa
        },
        types: {
          D: {
            icon: 'far fa-file-alt'
          },
          H: {
            icon: 'far fa-folder-open'
          },
          default: {
            icon: 'far fa-folder'
          }
        },
        grid: {
          columns: [
            {
              header: '<b>NAMA AKUN</b>',
              headerClass: 'border',
              wideCellClass: 'border'
            },
            {
              header: '<b>KODE AKUN<b>',
              headerClass: 'border',
              wideCellClass: 'border',

              value: function (node) {
                let data = node.data;
                let result = data.code;
                result = '<b>' + result + '</b>';
                return result;
              }
            },
            {
              header: '<b>TYPE AKUN<b>',
              headerClass: 'border',
              wideCellClass: 'border',

              value: function (node) {
                let data = node.data;
                let result = data.type;
                result = '<b>' + result + '&nbsp;&nbsp;</b>';
                return result;
              }
            },
            {
              header: '<b>Level<b>',
              headerClass: 'border',
              wideCellClass: 'border',

              value: function (node) {
                let data = node.data;
                let result = data.level;
                result = '<b>' + result + '&nbsp;&nbsp; </b>';
                return result;
              }
            },
            {
              header: '<b>D/K<b>',
              headerClass: 'text-center border',
              wideCellClass: 'text-center border',

              value: function (node) {
                let data = node.data;
                let result = data.default_dk;
                result = '<b>' + result + '</b>';
                return result;
              }
            },
            {
              header: '<b>Description<b>',
              headerClass: 'border',
              wideCellClass: 'border',

              value: function (node) {
                let data = node.data;
                let result = data.description;
                result = '<b>' + result + '</b>';
                return result;
              }
            },
            {
              header: '<b>Beginning Balance<b>',
              headerClass: 'border',
              wideCellClass: 'border',

              value: function (node) {
                let data = node.data;
                let result = data.description == 'Header' ? '-' : formatRupiah(data.beginning_balance);
                result = '<b>' + result + '&nbsp;&nbsp;</b>';
                return result;
              }
            }
          ],
          resizable: true,
          draggable: true,
          width: '100%'
        },
        contextmenu: {
          items: function (node) {
            var items = {
              customItem1: {
                label: 'Insert Sub COA',
                clickable: false,
                icon: 'fas fa-plus',
                action: function (data) {
                  if (node.data.description == 'Detail') {
                    showwarning('Cannot Add Sub Coa For COA Description Detail');
                    return;
                  }
                  updatemode = false;
                  const isinsertsub = true;
                  const isinsertsibling = false;
                  showModal(node.data, isinsertsub, isinsertsibling);
                }
              },
              customItem2: {
                label: 'Insert Sibling COA',
                clickable: false,
                icon: 'fas fa-plus',
                action: function (data) {
                  updatemode = false;
                  const isinsertsub = false;
                  const isinsertsibling = true;
                  showModal(node.data, isinsertsub, isinsertsibling);
                }
              },
              customItem3: {
                label: 'Delete This All Sub COA',
                icon: 'fas fa-trash',
                action: async function (data) {
                  showconfirmdeletecustommsg(
                    node.data.code,
                    node.data.name,
                    deleteallchildrencoa,
                    `All Children Of COA Name :`,
                    'Will Be Permanently Deleted'
                  );
                }
              },
              customItem4: {
                label: 'Delete This COA',
                icon: 'fas fa-trash',
                action: async function (data) {
                  showconfirmdeletecustommsg(
                    node.data.code,
                    node.data.name,
                    deletethiscoa,
                    `COA Name :`,
                    ' And Also Its Children (If Exists) Will Be Permanently Deleted'
                  );
                }
              },
              customItem5: {
                label: 'Edit This COA',
                icon: 'fas fa-edit',
                action: function (data) {
                  updatemode = true;
                  showModal(node.data);
                }
              }
              // Tambahkan lebih banyak item menu konteks jika diperlukan
            };
            return items;
          }
        }
      })
      .on('open_node.jstree', function (e, data) {
        data.instance.set_icon(data.node, 'far fa-folder-open');
      })
      .on('close_node.jstree', function (e, data) {
        data.instance.set_icon(data.node, 'far fa-folder');
      });
  }
  async function getTreeCoa() {
    const method = 'GET';
    const urlRequest = route('admin.getTreeCOA');
    let response = '';

    try {
      const ajx = new AjaxRequest(urlRequest, method);
      response = await ajx.getData();
      coa = response;
      renderTree();
    } catch (error) {
      showerror(error);
      return false;
    }
  }

  function setDefault() {
    inputbeginning.val(formatRupiah1(0));
    inputcode.val('');
    inputname.val('');
    inputcode.removeAttr('readonly');
    inputlevel.removeAttr('disabled');
    inputtype.removeAttr('disabled');
    inputbeginning.removeAttr('readonly');
    inputbeginning.val(formatRupiah1(0));
    inputtype.prop('selectedIndex', 0);
    inputlevel.prop('selectedIndex', 0);
    inputdk.prop('selectedIndex', 0);
    inputdescription.prop('selectedIndex', 0);
  }

  function setNewCOACode(coacode) {
    const splitdot = coacode.split('.'); // memecah karakter code coa dimana pemisahnya adalah karakter titik , menjdai kumupulan array
    let lastTwoNumber = splitdot[splitdot.length - 1]; //mengambil  2 karakter terakhir

    lastTwoNumber++; //Increment 2 angka terakhir
    const incrementedString = parseInt(lastTwoNumber) < 10 ? '0' + lastTwoNumber.toString() : lastTwoNumber.toString();

    splitdot[splitdot.length - 1] = incrementedString; // mereplace array terakhir dengan angka yang sudah diincrement
    const newCodeCOA = splitdot.join('.'); //Menggabungkan kembali dengan 2 angka terkahir yang sudah diincrement
    return newCodeCOA;
  }

  function populateSubCOA(dataCurrentCOA) {
    const filterdCoa = [...coa].filter((x) => {
      return x.data.code.startsWith(dataCurrentCOA.code); //filter coa , seperti query select * from coa where codecoa like 'codecoa%'
    });

    const getLastCoa = filterdCoa[filterdCoa.length - 1]; //Mendapatkan coa terakhir dari coa yang sudah difilter
    const getLastcode = getLastCoa.data.code; //Mendapatkan kode coa untuk akun coa terakhir

    if (filterdCoa.length > 1) {
      // ada sub item(coa)
      const filteredCOAByLevel = [...filterdCoa].filter((x) => {
        return x.data.level == parseInt(dataCurrentCOA.level) + 1;
      });

      const getLastCoaByLevel = filteredCOAByLevel[filteredCOAByLevel.length - 1]; //Mendapatkan coa terakhir dari coa yang sudah difilter
      const getLastcodeByLevel = getLastCoaByLevel.data.code; //Me

      const newSubCodeCOA = setNewCOACode(getLastcodeByLevel);
      inputcode.val(newSubCodeCOA);
    } else {
      //tidak ada sub item(coa)

      inputcode.val(getLastcode + '.01');
    }
    inputcode.attr('readonly', true);
    inputlevel.val(parseInt(dataCurrentCOA.level) + 1);
    inputlevel.attr('disabled', true);
    inputtype.val(dataCurrentCOA.type);
    inputtype.attr('disabled', true);
    inputdk.val(dataCurrentCOA.default_dk);
    inputdescription.val('Detail');
    disableInputBeginBalance();
  }

  async function deletethiscoa(code, name) {
    const urlRequest = route('admin.deletecoa', code);
    const method = 'post';
    try {
      const ajx = new AjaxRequest(urlRequest, method);
      const response = await ajx.getData();
      if (response) {
        iziSuccess('Success', `COA Code : ${code} And Its Children Successfuly Deleted `);
        getTreeCoa();
      }
    } catch (error) {
      handleerror(error, code, 'COA');
    }
  }

  async function deleteallchildrencoa(code, name) {
    const urlRequest = route('admin.deletecoasub', code);
    const method = 'post';
    try {
      const ajx = new AjaxRequest(urlRequest, method);
      const response = await ajx.getData();
      if (response) {
        iziSuccess('Success', `All Children Of COA ${code} (${name})  Successfuly Deleted `);
        getTreeCoa();
      }
    } catch (error) {
      handleerror(error, code, 'COA');
    }
  }

  function populateEditCOA(dataCurrentCOA) {
    inputcode.val(dataCurrentCOA.code);
    inputname.val(dataCurrentCOA.name);
    inputbeginning.val(formatRupiah1(dataCurrentCOA.beginning_balance));
    inputdescription.val(dataCurrentCOA.description);
    inputdk.val(dataCurrentCOA.default_dk);
    inputlevel.val(dataCurrentCOA.level);
    inputtype.val(dataCurrentCOA.type);

    inputcode.attr('readonly', true);
    inputlevel.attr('disabled', true);
    inputtype.attr('disabled', true);
  }

  function populateSiblingCOA(dataCurrentCOA) {
    let joinCode = '';
    let filterdCoa = [];
    let newSiblingCodeCOA = '';
    if (parseInt(dataCurrentCOA.level) > 0) {
      let currentcode = dataCurrentCOA.code;

      let splitcode = currentcode.split('.');

      splitcode.pop(); //mengurangi 2 karakter terakhir code

      joinCode = splitcode.join('.');

      filterdCoa = [...coa].filter((x) => {
        return x.data.code.startsWith(joinCode) && x.data.level == parseInt(dataCurrentCOA.level);
      });
      const lastcoa = filterdCoa[filterdCoa.length - 1];
      const Lastcoacode = lastcoa.data.code;

      newSiblingCodeCOA = setNewCOACode(Lastcoacode);
    } else {
      filterdCoa = [...coa].filter((x) => {
        return x.data.level == parseInt(dataCurrentCOA.level);
      });

      const lastCOA = filterdCoa[filterdCoa.length - 1];
      const lastCode = lastCOA.data.code;
      newSiblingCodeCOA = parseInt(lastCode) + 10;
    }

    inputcode.val(newSiblingCodeCOA);

    inputcode.attr('readonly', true);
    inputlevel.val(parseInt(dataCurrentCOA.level));
    inputlevel.attr('disabled', true);
    inputtype.val(dataCurrentCOA.type);
    inputtype.attr('disabled', true);
    inputdk.val(dataCurrentCOA.default_dk);
    inputdescription.val(dataCurrentCOA.description);
    disableInputBeginBalance();
  }

  function showModal(data, inserrtsub = false, insertsibling = false) {
    setDefault();
    if (!updatemode) {
      if (inserrtsub) {
        modaltitle.html('Insert Sub COA');
        populateSubCOA(data);
        modalcoa.modal('show');
      } else if (insertsibling) {
        modaltitle.html('Insert Sibling COA');
        populateSiblingCOA(data);
        modalcoa.modal('show');
      } else {
        modaltitle.html('Add New COA');
        modalcoa.modal('show');
      }
    } else {
      modaltitle.html('Edit COA');
      populateEditCOA(data);
      modalcoa.modal('show');
    }
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

  function createFormData() {
    var formData = new FormData();

    formData.append('type', PostData.type);
    formData.append('name', PostData.name.trim());
    formData.append('code', PostData.code.trim());
    formData.append('level', PostData.level);
    formData.append('default_dk', PostData.default_dk);
    formData.append('beginning_balance', parseToNominal(PostData.beginning_balance));
    formData.append('description', PostData.description);

    return formData;
  }

  function disableInputBeginBalance() {
    if (inputdescription.val() == 'Header') {
      inputbeginning.attr('readonly', true);
      inputbeginning.val(formatRupiah1(0));
    } else {
      inputbeginning.removeAttr('readonly');
    }
  }

  function populatePostData() {
    PostData.type = inputtype.val();
    PostData.name = inputname.val();
    PostData.level = inputlevel.val();
    PostData.description = inputdescription.val();
    PostData.default_dk = inputdk.val();
    PostData.code = inputcode.val();
    PostData.beginning_balance = inputbeginning.val();
  }
  async function postAjax() {
    let urlRequest = '';

    if (updatemode) {
      let code = inputcode.val();
      urlRequest = route('admin.editcoa', code);
    } else {
      urlRequest = route('admin.addCoa');
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

  function validateInput() {
    if (inputcode.val() == '') {
      inputcode.focus();
      showwarning('Please Input COA Code !');
      return false;
    }
    if (inputname.val() == '') {
      inputname.focus();
      showwarning('Please Input COA Name !');
      return false;
    }

    return true;
  }

  // ====================================================

  // Event
  // ====================================================

  // Add New COA
  $(document).on('click', '.btnaddnew', function () {
    updatemode = false;
    showModal();
  });

  $(document).on('click', '.btnsave', async function () {
    if (validateInput()) {
      $(this).find('span').html('Loading...');
      $(this).prop('disabled', true);
      populatePostData();
      let response = await postAjax();
      if (Boolean(response) == true) {
        iziSuccess(
          'Success',
          updatemode ? `COA (${inputcode.val()}) Successfully Updated` : `New COA (${inputcode.val()}) Successfully Created`
        );
        getTreeCoa();
        $(this).find('span').html('Save changes');
        $(this).prop('disabled', false);
        modalcoa.modal('hide');
      } else {
        $(this).find('span').html('Save changes');
        $(this).prop('disabled', false);
      }
    }
  });

  $(document).on('focusin', '.inputbeginning', function (e) {
    let X = parseToNominal($(this).val()) === 0 ? '' : parseToNominal($(this).val());
    $(this).val(X);
  });

  $(document).on('blur', '.inputbeginning', function (e) {
    $(this).val(formatRupiah1($(this).val()));
  });

  $(document).on('keyup', '.inputbeginning', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  $(document).on('keyup', '.inputcode', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  $(document).on('keyup', '.inputcode', function (event) {
    var object = $(this);
    inputOnlyNumber(object);
  });

  $(document).on('change', '.inputdescription', function (event) {
    disableInputBeginBalance();
  });
  // =====================================================
});
