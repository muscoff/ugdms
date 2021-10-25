const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const addAdmin = document.querySelector('#add-admin');
    const viewAdmin = document.querySelector('#view-admin');
    const addUser = document.querySelector('#add-user');
    const viewUsers = document.querySelector('#view-user');
    const uploadDoc = document.querySelector('#upload-doc');
    const resetPassword = document.querySelector('#reset');
    const createOffice = document.querySelector('#create-office');
    const group = document.querySelector('#group');
    const folder = document.querySelector('#manage-folder');
    let fullname = document.querySelector('#fullname');
    let acc_obj;

    ipcRenderer.on('loginObj', (e, item)=>{
        acc_obj = item;
        fullname.innerHTML = item.fullname != '' ? item.fullname : item.username;
        if(acc_obj.super_status !== 1){
            document.querySelector('.add-admin').classList.add('display-none');
            document.querySelector('.view-admin').classList.add('display-none');
            document.querySelector('.create-office').classList.add('display-none');
        }else{
            document.querySelector('.add-user').classList.add('display-none');
            document.querySelector('.view-user').classList.add('display-none');
            document.querySelector('.manage-doc').classList.add('display-none');
            document.querySelector('.group').classList.add('display-none');
            document.querySelector('.manage-folder').classList.add('display-none');
        }
    });

    addAdmin.addEventListener('click', e=>{
        ipcRenderer.send('add_admin');
    });

    viewAdmin.addEventListener('click', e=>{
        ipcRenderer.send('view_admin');
    });

    addUser.addEventListener('click', e=>{
        ipcRenderer.send('add_users');
    });

    viewUsers.addEventListener('click', e=>{
        ipcRenderer.send('view_users');
    });

    uploadDoc.addEventListener('click', e=>{
        ipcRenderer.send('upload_doc');
    });

    resetPassword.addEventListener('click', e=>{
        ipcRenderer.send('reset_admin_account');
    });

    createOffice.addEventListener('click', e=>{
        ipcRenderer.send('create_office');
    });

    group.addEventListener('click', e=>{
        ipcRenderer.send('create_group');
    });

    folder.addEventListener('click', e=>{
        ipcRenderer.send('folder');
    });
});