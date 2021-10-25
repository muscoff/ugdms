const {ipcRenderer, Notification} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const addGroup = document.querySelector('#create-group');
    const groupList = document.querySelector('#group-list');
    const manageGroup = document.querySelector('#manage-group');
    const general = document.querySelector('#general');
    let url, acc;
    let notification;

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('loginObj', (e, item)=>{
        acc = item;
    });

    ipcRenderer.on('reset_manage_group_settings', ()=>{
        general.setAttribute('data-id', '');
        general.setAttribute('data-admin', '');
        manageGroup.classList.remove('green-text');
    });

    ipcRenderer.on('group', (e, data)=>{
        if(data.length > 0){
            let string = '';
            data.forEach(item=>{
                string+=`
                    <div class="margin-left-5 margin-top-5">
                        <div class="truncate cursor-pointer group" data-id="${item.id}" data-creator="${item.creator}" title="${item.name}"><i class="fas fa-users-cog"></i> ${item.name}</div>
                    </div>
                `;
            });
            groupList.innerHTML = string;

            document.querySelectorAll('.group').forEach((item, index)=>{
                item.addEventListener('click', e=>{
                    let id = item.getAttribute('data-id');
                    let admin = item.getAttribute('data-creator');
                    if(item.classList.contains('color-bg')){
                        item.classList.remove('color-bg');
                        manageGroup.classList.remove('green-text');
                        general.setAttribute('data-id', '');
                        general.setAttribute('data-admin', '');
                    }else{
                        item.classList.add('color-bg');
                        manageGroup.classList.add('green-text');
                        general.setAttribute('data-id', id);
                        general.setAttribute('data-admin', admin);
                    }

                    document.querySelectorAll('.group').forEach((el, i)=>{
                        if(i !== index){
                            el.classList.remove('color-bg');
                        }
                    });
                });
            });
        }else{
            groupList.innerHTML = '';
        }
    });

    addGroup.addEventListener('click', e=>{
        ipcRenderer.send('add_group', acc.username);
    });

    manageGroup.addEventListener('click', e=>{
        let id = general.getAttribute('data-id');
        let admin = general.getAttribute('data-admin');
        id = parseInt(id);
        let data = {id, admin};
        if(id !== '' & admin !== '' & manageGroup.classList.contains('green-text')){
            ipcRenderer.send('manage_group', data);
        }
    });
});