export default function initiatedtpmonth(objectName, func = () => {}, id = null) {
  objectName.datepicker({
    format: 'M/yyyy',
    minViewMode: 1,
    defaultDate: null
  });
}
