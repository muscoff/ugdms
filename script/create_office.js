const {ipcRenderer, Notification} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const createOffice = document.querySelector('#create-office');
    const officeList = document.querySelector('#office-list');
    const manageOffice = document.querySelector('#manage-office');
    const general = document.querySelector('#general');
    let url;
    let notification;

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('reset_handover_values', ()=>{
        general.setAttribute('data-id', '');
        general.setAttribute('data-admin', '');
        manageOffice.classList.remove('green-text');
    });

    ipcRenderer.on('offices', (e, data)=>{
        if(data.length > 0){
            let string = '';
            data.forEach(item => {
                if(parseInt(item.parent) === 0){
                    string+=`
                        <div class="margin-left-5 margin-top-5">
                            <div class="flex-row align-items-center justify-content-space-between">
                                <div class="width-90 office padding-all-1" data-id="${item.id}" data-admin="${item.admin === null ? '': item.admin}">
                                    <div class="trailing cursor-pointer" title="${item.name} ${item.admin !== null ? `- Administered by ${item.admin}` : ''}"><i class="${parseInt(item.parent) === 0 ? 'fas fa-city' : 'fas fa-building'}"></i> ${item.name}</div>
                                </div>
                                <div class="width-10 padding-all-1 flex-row-reverse"><i class="fi fi-rr-add cursor-pointer add-unit" data-parent="${item.id}" data-path="${item.parent_path}" title="Add a unit"></i></div>
                            </div>
                            <div class="box" data-id="${item.id}">
                    `;

                    data.forEach(element=>{
                        if(parseInt(item.id) === parseInt(element.parent)){
                            string+=`
                                <div class="margin-left-5 margin-top-5">
                                    <div class="trailing cursor-pointer" title="${element.name}"><i class="${parseInt(element.parent) === 0 ? 'fas fa-city' : 'fas fa-building'}"></i> ${element.name}</div>
                                </div>
                            `;
                        }
                    });

                    string+=`
                            </div>
                        </div>
                    `;
                }
            });
            officeList.innerHTML = string;

            document.querySelectorAll('.office').forEach((item, index)=>{
                item.addEventListener('click', e=>{
                    let boxes = document.querySelectorAll('.box');
                    let id = item.getAttribute('data-id');
                    let admin =  item.getAttribute('data-admin');
                    if(item.classList.contains('color-bg')){
                        boxes[index].classList.remove('open');
                        item.classList.remove('color-bg');
                        manageOffice.classList.remove('green-text');
                        general.setAttribute('data-id', '');
                        general.setAttribute('data-admin', '');
                    }else{
                        boxes[index].classList.add('open');
                        item.classList.add('color-bg');
                        manageOffice.classList.add('green-text');
                        general.setAttribute('data-id', id);
                        general.setAttribute('data-admin', admin);
                    }

                    document.querySelectorAll('.office').forEach((el, i)=>{
                        if(i !== index){
                            el.classList.remove('color-bg');
                        }
                    });
                });
            });

            document.querySelectorAll('.add-unit').forEach(item=>{
                item.addEventListener('click', e=>{
                    let parent = item.getAttribute('data-parent');
                    parent = parseInt(parent);
                    let parent_path = item.getAttribute('data-path');
                    ipcRenderer.send('add_office', {parent, office: false, parent_path});
                });
            });
        }else{
            officeList.innerHTML = '';
        }
    });

    createOffice.addEventListener('click', e=>{
        ipcRenderer.send('add_office', {parent: 0, office: true});
    });

    manageOffice.addEventListener('click', e=>{
        let id = general.getAttribute('data-id');
        let admin = general.getAttribute('data-admin');
        let data = {id, admin};
        if(id !== ''){
            id = parseInt(id);
            if(admin === ''){
                ipcRenderer.send('manage_office_account', id);
            }else{
                ipcRenderer.send('handover', data);
            }
        }
    });
});