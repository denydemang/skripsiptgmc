import tableInitiator from '../tableinitiator.js';
import AjaxRequest from '../ajaxrequest.js';
import { showconfirmdelete } from '../jqueryconfirm.js';
import checkNotifMessage from '../checkNotif.js';

$(document).ready(function () {
  var getDataProject = route('admin.getDataProject');
  const columns = [
    { data: 'action', name: 'actions', searchable: false, orderable: false, width: '10%' },
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
});
