const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const account = document.querySelector('#account');
    const office = document.querySelector('#office');
    const admin = document.querySelector('#admin');
    const adminList = document.querySelector('#admin-list');
    const setAccount = document.querySelector('#set-account');
    const button = document.querySelector('#submit');
    let acc, url;

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('mainadmin', (e, item)=>{
        account.setAttribute('data-boss', item.username);
    });

    ipcRenderer.on('account', (e, obj)=>{
        acc = obj;
        account.setAttribute('data-id', obj.id);
        office.innerHTML = obj.name;
    });

    ipcRenderer.on('administrators', (e, data)=>{
        setTimeout(()=>{
            if(data.length > 0){
                let string = '';
                data.forEach(item=>{
                    if(item.username === acc.admin){
                        document.querySelector('.admin-name').innerHTML = item.fullname !== null ? item.fullname : item.username;
                        document.querySelector('#display-admin').classList.remove('display-none');
                    }
                    if(item.username !== acc.admin){
                        string+=`
                            <div class="option truncate" data-username="${item.username}">${item.fullname}</div>
                        `;
                    }
                });
                adminList.innerHTML = string;

                document.querySelectorAll('.option').forEach(item=>{
                    item.addEventListener('click', e=>{
                        let username = item.getAttribute('data-username');
                        let fullname = item.innerText;
                        account.setAttribute('data-admin', username);
                        admin.innerHTML = fullname;
                        if(setAccount.classList.contains('display-none')){
                            setAccount.classList.remove('display-none');
                        }
                    });
                });
            }else{
                adminList.innerHTML = '';
            }
        },100);
    });

    button.addEventListener('click', e=>{
        let officeId = account.getAttribute('data-id');
        let admin = account.getAttribute('data-boss');
        let newAdmin = account.getAttribute('data-admin');

        let formdata = new FormData();
        formdata.append('id', officeId);
        formdata.append('admin', admin);
        formdata.append('new_admin', newAdmin);

        fetch(`${url}api/offices/handover.php`, {method: "POST", body: formdata})
        .then(response=>response.json())
        .then(response=>{
            if(response.status){
                ipcRenderer.send('close_handover');
                ipcRenderer.send('reload_administrators');
                ipcRenderer.send('reset_handover_values');
                ipcRenderer.send('office_success');
            }else{
                let data = {title: 'QUERY ERROR', body: response.msg};
                ipcRenderer.send('manage_office_account_error', data);
            }
        })
        .catch(err=>{
            let data = {title: 'SERVER ERROR', body: 'FAILED OR ERROR IN OPERATION'};
            ipcRenderer.send('manage_office_account_error', data);            
        });
    });
});