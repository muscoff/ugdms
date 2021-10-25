const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const uploadDoc = document.querySelector('#upload-doc');
    const resetPassword = document.querySelector('#reset');
    let fullname = document.querySelector('#fullname');
    let acc_obj;

    ipcRenderer.on('loginObj', (e, item)=>{
        acc_obj = item;
        fullname.innerHTML = item.fullname != '' ? item.fullname : item.username;
    });

    uploadDoc.addEventListener('click', e=>{
        ipcRenderer.send('upload_doc');
    });

    resetPassword.addEventListener('click', e=>{
        ipcRenderer.send('reset_user_account');
    });
});