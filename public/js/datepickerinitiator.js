export default function initiatedtp(objectName, func = () => {}, id = null) {
  objectName
    .datepicker({
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      defaultDate: null
    })
    .on('hide', function (e) {
      let valDateSelected = e.format('dd/mm/yyyy');
      func(valDateSelected, id);
    });
}
