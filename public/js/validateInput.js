export default function validateInput(
  dataInput,
  customFunc = function () {
    return true;
  }
) {
  for (let index = 0; index < dataInput.length; index++) {
    let input = $(dataInput[index]);
    let invalidFeedback = $('.invalid-feedback');
    input.removeClass('is-invalid');
    invalidFeedback.remove();
    if (input.val() == '') {
      input.addClass('is-invalid');
      input.after('<div class="invalid-feedback">Input Tidak Boleh Kosong</div>');
      input.focus();
      return false;
    }
  }

  return customFunc();
}
