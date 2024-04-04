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
}
