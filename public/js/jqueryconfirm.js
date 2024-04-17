export const showconfirmdelete = (id, name, funcdelete = null, menuname) => {
  $.confirm({
    buttons: {
      DELETE: function () {
        funcdelete(id, name);
      },
      CANCEL: {
        action: function () {}
      }
    },
    title: `Confirm Delete ?`,
    content: `${menuname} ${name} Will Be Permanently Deleted`,
    icon: 'fa fa-question-circle-o',
    theme: 'supervan',
    animation: 'scale',
    type: 'orange'
  });
};
export const showconfirmapprove = (id, name, funcapprove = null, menuname) => {
  $.confirm({
    buttons: {
      APPROVE: function () {
        funcapprove(id, name);
      },
      CANCEL: {
        action: function () {}
      }
    },
    title: `Confirm Approve ?`,
    content: `${menuname} : ${name} Will Be Approved & Cannot Be Undone`,
    icon: 'fa fa-question-circle-o',
    theme: 'supervan',
    animation: 'scale',
    type: 'blue'
  });
};

export const showconfirmstart = (id, name, funcstart = null, menuname) => {
  $.confirm({
    buttons: {
      START: function () {
        funcstart(id, name);
      },
      CANCEL: {
        action: function () {}
      }
    },
    title: `Start Project ?`,
    content: `${menuname} : ${name} Will Be Started & Cannot Be Undone`,
    icon: 'fa fa-question-circle-o',
    theme: 'supervan',
    animation: 'scale',
    type: 'blue'
  });
};

export const showconfirmfinish = async (id, objectHtml, funcfinish = null, menuname) => {
  $.confirm({
    buttons: {
      FINISH: async function () {
        return await funcfinish(id, objectHtml);
      },
      CANCEL: {
        action: function () {
          return false;
        }
      }
    },
    title: `Finish Project ?`,
    content: `${menuname} : ${id} Will Be Finished & Cannot Be Undone`,
    icon: 'fa fa-question-circle-o',
    theme: 'supervan',
    animation: 'scale',
    type: 'blue'
  });
};

export const showerror = (message, title = 'Error', backColor = 'red') => {
  $.alert({
    title: title,
    icon: 'fas fa-exclamation-triangle',
    type: backColor,
    content: message
  });
};
export const showwarning = (message, title = 'Warning', backColor = 'orange') => {
  $.alert({
    title: title,
    icon: 'fas fa-exclamation-triangle',
    type: backColor,
    content: message
  });
};
