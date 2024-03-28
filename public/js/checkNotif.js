import { iziSuccess, iziError } from './izitoast.js';
export default function checkNotifMessage() {
  const successmessage = $('.notifmessagesuccess').data('notifsuccess');
  const errormessage = $('.notifmessageerror').data('notiferror');

  if (successmessage) {
    iziSuccess('Success', successmessage);
  }
  if (errormessage) {
    iziError('Error', errormessage);
  }
}
