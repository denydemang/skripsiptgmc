export default class managedate {
  getFirstMonth(format = 'DD/MM/YYYY') {
    const startMONTH = moment().startOf('month').format(format);
    return startMONTH;
  }

  getLastMonth(format = 'DD/MM/YYYY') {
    const lastMONTH = moment().endOf('month').format(format);
    return lastMONTH;
  }
  getNowDate(format = 'DD/MM/YYYY') {
    const now = moment().format(format);
    return now;
  }

  getFirstDateMonthFromDate(date) {
    const monthYear = moment(date, 'MMMM/YYYY');
    var firstDate = monthYear.startOf('month').format('DD/MM/YYYY');
    return firstDate;
  }
  getLastDateMonthFromDate(date) {
    const monthYear = moment(date, 'MMMM/YYYY');
    var firstDate = monthYear.endOf('month').format('DD/MM/YYYY');
    return firstDate;
  }
}
