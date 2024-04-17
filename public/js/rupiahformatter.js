// export const formatter = new Intl.NumberFormat('id-ID', {
//   style: 'currency',
//   currency: 'IDR'
// });
export const formatRupiah = (money) => {
  if (isNaN(money)) {
    money = 0;
  }
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(money);
};
export const formatRupiah1 = (money) => {
  if (isNaN(money)) {
    money = 0;
  }
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(money);
};
