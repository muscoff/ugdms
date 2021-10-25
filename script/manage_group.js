const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const account = document.querySelector('#account');
    const groupName = document.querySelector('#group-name');
    const admin = document.querySelector('#admin');
    const usersList = document.querySelector('#users-list');
    const setAccount = document.querySelector('#set-account');
    const button = document.querySelector('#submit');
    const office = document.querySelector('#office');
    const view = document.querySelector('#view');
    const download = document.querySelector('#download');
    const upload = document.querySelector('#upload');
    const del = document.querySelector('#delete');
    let userArray = [];
    let acc, url;

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('group', (e, obj)=>{
        acc = obj;
        account.setAttribute('data-id', obj.id);
        account.setAttribute('data-admin', obj.creator);
        groupName.innerHTML = obj.name;
        userArray = obj.users_arr;
        obj.view === 1 ? view.setAttribute('checked', 'checked') : '';
        obj.download === 1 ? download.setAttribute('checked', 'checked') : '';
        obj.upload === 1 ? upload.setAttribute('checked', 'checked') : '';
        obj.delete === 1 ? del.setAttribute('checked', 'checked') : '';
    });

    ipcRenderer.on('admin', (e, item)=>{
        document.querySelector('.admin-name').innerHTML = item.fullname !== '' ? item.fullname : item.username;
        document.querySelector('#display-admin').classList.remove('display-none');
    });

    ipcRenderer.on('office', (e, data)=>{
        let string = '<option value="">Select office</option>';
        if(data.length > 0){
            data.forEach(item=>{
                string+=`
                    <option value="${item.id}">${item.name}</option>
                `;
            });
            office.innerHTML = string;
        }
    });

    ipcRenderer.on('users', (e, data)=>{
        setTimeout(()=>{
            if(data.length > 0){
                let string = '';
                data.forEach(item=>{
                    if(userArray.length > 0){
                        let s = false;
                        userArray.forEach(el=>{
                            if(el === item.username){
                                s = true;
                            }
                        });
                        string+=`
                            <div data-username="${item.username}" class="truncate ${s ? 'green-text' : ''} option select-user">${item.fullname !== '' ? item.fullname : item.username}</div>
                        `;
                        setAccount.classList.remove('display-none');
                    }else{
                        string+=`
                            <div data-username="${item.username}" class="truncate option select-user">${item.fullname !== '' ? item.fullname : item.username}</div>
                        `;
                        setAccount.classList.add('display-none');
                    }
                });
                usersList.innerHTML = string;

                document.querySelectorAll('.select-user').forEach(item=>{
                    item.addEventListener('click', e=>{
                        let username = item.getAttribute('data-username');
                        if(item.classList.contains('green-text')){
                            item.classList.remove('green-text');
                            let arr_new = userArray.filter(item=>item !== username);
                            userArray = arr_new;
                            userArray.length === 0 ? setAccount.classList.add('display-none') : '';
                        }else{
                            item.classList.add('green-text');
                            userArray.push(username);
                            setAccount.classList.remove('display-none');
                        }
                    });
                });
            }else{
                usersList.innerHTML = '';
            }
        },100);
    });

    office.addEventListener('change', e=>{
        let value = e.target.value;
        ipcRenderer.send('filter_user_group', value);
    });

    button.addEventListener('click', e=>{
        let str = '';
        let id = account.getAttribute('data-id');
        userArray.forEach(item=>{str+=item+','});
        let viewValue = view.checked ? 1 : 0;
        let downloadValue = download.checked ? 1 : 0;
        let uploadValue = upload.checked ? 1 : 0;
        let delValue = del.checked ? 1 : 0;

        let formdata = new FormData();
        formdata.append('id', id);
        formdata.append('users', str);
        formdata.append('view', viewValue);
        formdata.append('upload', uploadValue);
        formdata.append('download', downloadValue);
        formdata.append('delete', delValue);

        fetch(`${url}api/group/set_users.php`, {method: "POST", body: formdata})
        .then(response=>response.json())
        .then(response=>{
            if(response.status){
                ipcRenderer.send('group_success');
                ipcRenderer.send('close_manage_group');
                ipcRenderer.send('reset_manage_group_setting');
            }else{
                let data = {title: "QUERY ERROR", body: response.msg};
                ipcRenderer.send('manage_office_account_error', data); //Issue notification for error
            }
        })
        .catch(err=>console.log(err));
    });
});