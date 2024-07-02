import { iziSuccess } from './izitoast.js';
import { showwarning } from './jqueryconfirm.js';

export function checkNotifForbidden(code, msg) {

    if (code==200) {
        iziSuccess('Success', msg);
    }
    else if (code=403) {
        showwarning(msg);
    }
  }
