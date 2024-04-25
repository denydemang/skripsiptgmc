import { iziSuccess } from './izitoast.js';
import { showerror, showwarning } from './jqueryconfirm.js';
export default function checkNotifMessage() {
  const successmessage = $('.notifmessagesuccess').data('notifsuccess');
  const errormessage = $('.notifmessageerror').data('notiferror');

  if (successmessage) {
    iziSuccess('Success', successmessage);
  }
  if (errormessage) {
    if (typeof errormessage == 'string') {
      if (errormessage.includes('Unable To Delete')) {
        showwarning(errormessage, 'Oops !');
      } else {
        showerror(errormessage);
      }
    } else {
      showerror(errormessage);
    }
    // iziError('Error', errormessage);
  }
}
