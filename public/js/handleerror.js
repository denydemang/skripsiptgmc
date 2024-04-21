import { showerror, showwarning } from './jqueryconfirm.js';

const handlerror = (error, code, menuname) => {
  if (typeof error == 'string') {
    if (error.includes('Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails')) {
      showwarning(`The ${menuname} With Code ${code} Already Used By Another Transaction And Not Permitted To Be Deleted`, 'Oops !');
    } else {
      showerror(error);
    }
  } else {
    showerror(error);
  }
};
export default handlerror;
