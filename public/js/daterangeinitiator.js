export default class daterangeInitiator {
  constructor(objectName, htmlInner, func = function () {}, formatDate = 'MMMM D, YYYY') {
    this.startDate = moment().startOf('month');

    this.endDate = moment().endOf('month');
    this.objectName = objectName;
    this.formatDate = formatDate;
    this.htmlInner = htmlInner;
    this.func = func;
  }

  drawDateRange() {
    this.htmlInner.html(this.startDate.format(this.formatDate) + ' - ' + this.endDate.format(this.formatDate));
    this.objectName.daterangepicker(
      {
        startDate: this.startDate,
        endDate: this.endDate
      },

      function (start_date, end_date) {
        this.htmlInner.html(start_date.format(this.formatDate) + ' - ' + end_date.format(this.formatDate));
        this.func(start_date, end_date);
      }.bind(this)
    );
  }
}
