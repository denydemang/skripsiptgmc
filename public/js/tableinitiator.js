export default class tableInitiator {
  constructor(method = 'get', tableName = '', columns = [], url, data = function () {}) {
    this.method = method;
    this.tableName = tableName;
    this.columns = columns;
    this.url = url;
    this.data = data;
    this.table = $(this.tableName).DataTable({
      processing: true,
      serverSide: true,
      fixedColumns: {
        start: 1
      },
      paging: true,
      scrollCollapse: true,
      scrollX: true,
      scrollY: 300,
      ajax: {
        url: this.url,
        method: this.method,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: this.data
      },
      columns: this.columns
    });
  }

  showTable() {
    this.table.draw();
  }
}
