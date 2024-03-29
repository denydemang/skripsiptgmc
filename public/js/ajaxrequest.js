export default class AjaxRequest {
  response;
  constructor(url = '', method = 'post', data = {}) {
    this.url = url;
    this.method = method;
    this.data = data;
  }
  async getData() {
    try {
      const response = await $.ajax({
        url: this.url,
        method: this.method,
        dataType: 'json',
        data: this.data
      });
      return response; // Mengembalikan respons jika permintaan berhasil
    } catch (error) {
      console.error('Error:', error); // Menangani kesalahan jika terjadi
      throw error; // Melempar kembali kesalahan untuk menangani di luar fungsi
    }
  }
}
