import { showconfirmlogout } from '../jqueryconfirm.js';
$(document).ready(function () {
  function logOut() {
    window.location.href = route('logout');
  }
  $(document).on('click', '.btnlogout', function () {
    showconfirmlogout('', '', logOut);
  });
});
