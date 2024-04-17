export default class AjaxRequest {
  response;
  constructor(url = '', method = 'post', data = {}, token = $('meta[name="csrf-token"]').attr('content')) {
    this.url = url;
    this.method = method;
    this.data = data;
    this.token = token;
  }
  async getData() {
    try {
      const response = await $.ajax({
        url: this.url,
        method: this.method,
        dataType: 'json',
        data: this.data,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: false,
        processData: false
      });
      return response;
      console.log(response);
    } catch (error) {
      console.log(error);
      throw error.responseJSON.message;
    }
  }

  async formU_DeData() {
      try {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': this.token
            }
        });
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
