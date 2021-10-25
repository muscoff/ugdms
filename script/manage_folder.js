const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const account = document.querySelector('#account');
    const folderName = document.querySelector('#folder-name');
    const admin = document.querySelector('#admin');
    const groupList = document.querySelector('#group-list');
    const setAccount = document.querySelector('#set-account');
    const button = document.querySelector('#submit');
    const view = document.querySelector('#view');
    const download = document.querySelector('#download');
    const upload = document.querySelector('#upload');
    const del = document.querySelector('#delete');
    let groupArray = [];
    let acc, url, groups, folder;

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('folder', (e, item)=>{
        account.setAttribute('data-id', item.id); folder = item;
        folderName.innerHTML = item.name;
        groupArray = item.groups;
        parseInt(item.view) === 1 ? view.setAttribute('checked', 'checked') : '';
        parseInt(item.download) === 1 ? download.setAttribute('checked', 'checked') : '';
        parseInt(item.upload) === 1 ? upload.setAttribute('checked', 'checked') : '';
        parseInt(item.delete) === 1 ? del.setAttribute('checked', 'checked') : '';
    });

    ipcRenderer.on('group', (e, data)=>{
        setTimeout(()=>{
            if(data.length > 0){
                let string = ''; groups = data;
                data.forEach(item=>{
                    if(groupArray.length > 0){
                        let s = false;
                        groupArray.forEach(el=>{
                            if(parseInt(el) === parseInt(item.id)){
                                s = true;
                            }
                        });
                        string+=`
                            <div data-id="${item.id}" class="truncate option selected-group ${s ? 'green-text' : ''}">${item.name}</div>
                        `;
                        if(setAccount.classList.contains('display-none')){
                            setAccount.classList.remove('display-none');
                        }
                    }else{
                        string+=`
                            <div data-id="${item.id}" class="truncate selected-group option">${item.name}</div>
                        `;
                        if(!setAccount.classList.contains('display-none')){
                            setAccount.classList.add('display-none');
                        }
                    }
                });
                groupList.innerHTML = string;

                document.querySelectorAll('.selected-group').forEach((item, index)=>{
                    item.addEventListener('click', e=>{
                        let id = item.getAttribute('data-id');
                        if(item.classList.contains('green-text')){
                            item.classList.remove('green-text');
                            let g = groupArray.filter(grp=>parseInt(grp) !== parseInt(id));
                            groupArray = g;
                            if(groupArray.length === 0){
                                setAccount.classList.add('display-none');
                                parseInt(folder.view) === 1 ? view.setAttribute('checked', 'checked') : view.removeAttribute('checked');
                                parseInt(folder.download) === 1 ? download.setAttribute('checked', 'checked') : download.removeAttribute('checked');
                                parseInt(folder.upload) === 1 ? upload.setAttribute('checked', 'checked') : upload.removeAttribute('checked');
                                parseInt(folder.delete) === 1 ? del.setAttribute('checked', 'checked') : del.removeAttribute('checked');
                            }
                        }else{
                            item.classList.add('green-text');
                            groupArray.push(id);
                            let selectedItem = groups.filter(element=>parseInt(element.id) === parseInt(id));
                            selectedItem = selectedItem[0];
                            parseInt(selectedItem.view) === 1 ? view.setAttribute('checked', 'checked') : view.removeAttribute('checked');
                            parseInt(selectedItem.download) === 1 ? download.setAttribute('checked', 'checked') : download.removeAttribute('checked');
                            parseInt(selectedItem.upload) === 1 ? upload.setAttribute('checked', 'checked') : upload.removeAttribute('checked');
                            parseInt(selectedItem.delete) === 1 ? del.setAttribute('checked', 'checked') : del.removeAttribute('checked');
                            if(setAccount.classList.contains('display-none')){
                                setAccount.classList.remove('display-none');
                            }
                        }

                        document.querySelectorAll('.selected-group').forEach((el, i)=>{
                            if(i !== index){
                                let groupId = el.getAttribute('data-id');
                                let group_arr = groupArray.filter(groupItem=>parseInt(groupItem) === parseInt(id));
                                groupArray = group_arr;
                                el.classList.remove('green-text');
                            }
                        });
                    });
                });
            }else{
                groupList.innerHTML = '';
            }
        },100);
    });

    ipcRenderer.on('loginObj', (e, item)=>{
        document.querySelector('.admin-name').innerHTML = item.fullname !== '' ? item.fullname : item.username;
        document.querySelector('#display-admin').classList.remove('display-none');
    });

    button.addEventListener('click', e=>{
        let str = groupArray[0];
        let id = account.getAttribute('data-id');
        //groupArray.forEach(item=>{str+=item+','});
        let viewValue = view.checked ? 1 : 0;
        let downloadValue = download.checked ? 1 : 0;
        let uploadValue = upload.checked ? 1 : 0;
        let delValue = del.checked ? 1 : 0;

        let formdata = new FormData();
        formdata.append('id', id);
        formdata.append('user_group', str);
        formdata.append('view', viewValue);
        formdata.append('upload', uploadValue);
        formdata.append('download', downloadValue);
        formdata.append('delete', delValue);

        fetch(`${url}api/folder/set_group.php`, {method: "POST", body: formdata})
        .then(response=>response.json())
        .then(response=>{
            if(response.status){
                ipcRenderer.send('new_folder_success');
                ipcRenderer.send('reset_group_success');
                ipcRenderer.send('close_manage_folder');
                ipcRenderer.send('reset_manage_folder_setting');
            }else{
                let data = {title: "QUERY ERROR", body: response.msg};
                ipcRenderer.send('manage_office_account_error', data); //Issue notification for error
            }
        })
        .catch(err=>console.log(err));
    });
});