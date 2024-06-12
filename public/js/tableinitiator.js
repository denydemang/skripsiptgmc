export default class tableInitiator {
  constructor(
    method = 'get',
    tableName = '',
    columns = [],
    url,
    data = function () {},
    fixedColumnsCount = 1,
    funcCell = function () {},
    searchable = true
  ) {
    this.method = method;
    this.tableName = tableName;
    this.columns = columns;
    this.url = url;
    this.data = data;
    this.funcCell = funcCell;
    this.searchable = searchable;
    this.fixedColumnsCount = fixedColumnsCount;
    this.table = $(this.tableName).DataTable({
      fixedHeader: true,
      retrieve: true,
      processing: true,
      serverSide: true,
      searching: this.searchable,
      fixedColumns: {
        start: this.fixedColumnsCount
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
      columns: this.columns,
      createdRow: this.funcCell
    });
  }

  showTable() {
    this.table.draw();
  }
  destroyTable() {
    this.table.destroy();
  }
}
