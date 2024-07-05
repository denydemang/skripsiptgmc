import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { showconfirmdelete } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';
import { formatRupiah1 } from '../rupiahformatter.js';
$(document).ready(function () {
  let modalTitle = $('.modal-title');
  let titledetail = $('.title-detail');
  const listMaterial = $('.listbb tbody');
  const listUpah = $('.listupah tbody');
  let CodeInput = $('.code');
  let NameInput = $('.name');
  let DescriptionInput = $('.description');
  const modalTypeProject = $('#modal-projecttype');
  const modalDetailProject = $('#modal-detailproject');
  let updateMode = false;

  //  Inisiasi Property Untuk Datatable
  // -------------------------------------------------
  var getDataTypeProjectURL = route('admin.getDataProjectType');
  const columns = [
    { data: 'action', name: 'actions', searchable: false, orderable: false, width: '10%' },
    { data: 'code', name: 'Code', searchable: true },
    { data: 'name', name: 'Name', searchable: true },
    { data: 'description', name: 'Description', searchable: true },
    { data: 'updated_by', name: 'Updated_By', searchable: true },
    { data: 'created_by', name: 'Created_By', searchable: true }
  ];
  const tableName = '.proyecttype-table';
  const method = 'post';

  // INISIASI DATATABLE
  // ---------------------------------------------------
  const Table = new tableInitiator(method, tableName, columns, getDataTypeProjectURL);
  Table.showTable();

  // Function
  // ------------------------------------------------------------
  async function getData(code = '') {
    const urlRequest = route('admin.getDataTypeProjectRaw', code);
    const method = 'GET';
    const data = {
      parameter1: 'test'
    };

    try {
      const ajx = new AjaxRequest(urlRequest, method, data);
      return await ajx.getData();
    } catch (error) {
      console.error('Error:', error);
      return null;
    }
  }

  function populateData(TypeProject = {}, Material = [], upah = []) {
    titledetail.html(`${TypeProject.code} - ${TypeProject.name}`);
    let htmlMaterial = '';
    let counterMaterial = 1;

    // Populate Detail Material when view detail
    Material.forEach((item) => {
      htmlMaterial += `
      <tr>
          <td>${counterMaterial}</td>
          <td>${item.item_code}</td>
          <td style="white-space:normal;word-wrap: break-word;">${item.item_name}</td>
          <td>${item.unit_code}</td>
      </tr>
  
      `;
      counterMaterial++;
    });

    listMaterial.html(htmlMaterial);

    // --------------------------

    // Populate Detail Upah when view detail
    let htmlUpah = '';
    let counterUpah = 1;
    upah.forEach((item) => {
      htmlUpah += `
      <tr >
        <td>${counterUpah}</td>
        <td>${item.upah_code}</td>
        <td style="white-space:normal;word-wrap: break-word;">${item.job}</td>
        <td>${item.unit}</td>
        <td style="text-align: right;white-space:nowrap ">${formatRupiah1(item.price)}
        </td>
      </tr>
      `;
      counterUpah++;
    });

    listUpah.html(htmlUpah);
  }

  function deleteData(code, name) {
    const urlRequest = route('admin.deleteDataProjectType', code);
    window.location.href = urlRequest;
  }

  // CRUD AND EVENT
  // ------------------------------------------------------------

  // Click Edit Button
  $(document).on('click', '.editbtn', async function () {
    let code = $(this).data('code');
    let url = route('admin.editprojecttypeview', code);

    window.location.href = url;
  });

  // Click Delete Button
  $(document).on('click', '.deletebtn', function () {
    let Code = $(this).data('code');
    showconfirmdelete(Code, Code, deleteData, 'Project Type :');
  });

  $(document).on('click', '.viewbtn', async function () {
    let code = $(this).data('code');
    titledetail.html('Loading....');
    listUpah.html('Loading....');
    listMaterial.html('Loading....');

    modalDetailProject.modal('show');
    const response = await getData(code);
    populateData(response.projecttype, response.bahanBaku, response.upah);
  });

  // Trigger Toast
  checkNotifMessage();
});
