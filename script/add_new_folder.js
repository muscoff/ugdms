const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const office = document.querySelector('#office');
    const creaTor = document.querySelector('#creator');
    const parent = document.querySelector('#parent');
    const fpath = document.querySelector('#path');
    const view = document.querySelector('#view');
    const upload = document.querySelector('#upload');
    const download = document.querySelector('#download');
    const del = document.querySelector('#delete');
    const error = document.querySelector('#error');
    let acc_obj; let url;

    // ipcRenderer.on('loginObj', (e, item)=>{
    //     acc_obj = item;console.log(acc_obj);
    //     if(acc_obj.hasOwnProperty('super_status')){
    //         if(acc_obj.super_status){
    //             cparent.setAttribute('value', 'super');
    //             creaTor.setAttribute('value', acc_obj.username);
    //         }else{
    //             cparent.setAttribute('value', 'super');
    //             creaTor.setAttribute('value', acc_obj.username);
    //         }
    //     }else{
    //         cparent.setAttribute('value', acc_obj.creator);
    //         creaTor.setAttribute('value', acc_obj.username);
    //     }
    // });

    ipcRenderer.on('office', (e, item)=>{
        office.setAttribute('value', item);
    });

    ipcRenderer.on('creator', (e, item)=>{
        creaTor.setAttribute('value', item);
    });

    ipcRenderer.on('parent', (e, item)=>{
        parent.setAttribute('value', item);
    });

    ipcRenderer.on('path', (e, item)=>{
        fpath.setAttribute('value', item);
    });

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    form.addEventListener('submit', e=>{
        e.preventDefault();
        let cr = e.target[0].value;
        let off = e.target[1].value;
        let p = e.target[2].value;
        let pth = e.target[3].value;
        let name = e.target[4].value;
        let viewValue = view.checked ? 1 : 0;
        let uploadValue = upload.checked ? 1 : 0;
        let downloadValue = download.checked ? 1 : 0;
        let deleteValue = del.checked ? 1 : 0;
        pth = pth+'/'+name;

        let formdata = new FormData();
        formdata.append('name', name);
        formdata.append('creator', cr);
        formdata.append('office', off);
        formdata.append('parent', p);
        formdata.append('path', pth);
        formdata.append('view', viewValue);
        formdata.append('upload', uploadValue);
        formdata.append('download', downloadValue);
        formdata.append('delete', deleteValue);

        fetch(`${url}api/folder/create.php`, {method: "POST", body: formdata})
        .then(response=>response.json())
        .then(response=>{
            if(response.status){
                ipcRenderer.send('close_add_new_folder');
                ipcRenderer.send('new_folder_success');
            }else{
                error.innerHTML = response.msg;
                setTimeout(()=>{error.innerHTML = '';},3000);
            }
        });
    });
});