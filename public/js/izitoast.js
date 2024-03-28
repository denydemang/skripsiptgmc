export const iziInfo = (title, message) => {
  iziToast.info({
    title: title,
    message: message,
    position: 'topRight'
  });
};
export const iziSuccess = (title, message) => {
  iziToast.success({
    title: title,
    message: message,
    position: 'topRight'
  });
};
export const iziWarning = (title, message) => {
  iziToast.warning({
    title: title,
    message: message,
    position: 'topRight'
  });
};
export const iziError = (title, message) => {
  iziToast.error({
    title: title,
    message: message,
    position: 'topRight'
  });
};
