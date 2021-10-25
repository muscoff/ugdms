const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const groupName = document.querySelector('#group-name');
    const view = document.querySelector('#view');
    const download = document.querySelector('#download');
    const upload = document.querySelector('#upload');
    const del = document.querySelector('#delete');
    const creaTor = document.querySelector('#creator');
    const error = document.querySelector('#error');
    let acc_obj; let url;

    ipcRenderer.on('loginObj', (e, item)=>{
        acc_obj = item;
        creaTor.setAttribute('value', acc_obj.username);
    });

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;console.log(url);
    });

    form.addEventListener('submit', e=>{
        e.preventDefault();
        let c = creaTor.value;
        let group = groupName.value;
        let viewValue = view.checked ? 1 : 0;
        let downloadValue = download.checked ? 1 : 0;
        let uploadValue = upload.checked ? 1 : 0;
        let deleteValue = del.checked ? 1 : 0;

        let formdata = new FormData();
        formdata.append('name', group);
        formdata.append('creator', c);
        formdata.append('view', viewValue);
        formdata.append('download', downloadValue);
        formdata.append('upload', uploadValue);
        formdata.append('delete', deleteValue);

        fetch(`${url}api/group/create.php`, {method: "POST", body: formdata})
        .then(response=>response.json())
        .then(response=>{
            if(response.status){
                ipcRenderer.send('close_add_group');
                ipcRenderer.send('group_success');
            }else{
                error.innerHTML = response.msg;
                setTimeout(()=>{
                    error.innerHTML = '';
                },3000);
            }
        })
        .catch(err=>console.log(err));
    });
});