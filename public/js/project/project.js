import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { formatRupiah1 } from '../rupiahformatter.js';
import { showconfirmdelete } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';

$(document).ready(function () {
  var getDataProject = route('admin.getDataProject');
  const listUpah = $('.listupah tbody');
  const listMaterial = $('.listbb tbody');
  const modalDetailProject = $('#modal-detailproject');
  const modalTitle = $('.modal-title');
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
  const tableName = '.projecttable';
  const method = 'post';

  // INISIASI DATATABLE
  // ---------------------------------------------------
  const Table = new tableInitiator(method, tableName, columns, getDataProject);
  Table.showTable();

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
  }

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

  // Trigger Toast
  checkNotifMessage();
});
