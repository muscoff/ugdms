const {ipcRenderer, Notification} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const folderList = document.querySelector('#folder-list');
    const office = document.querySelector('#office');
    let offices;
    const manageGroup = document.querySelector('#manage-group');
    const general = document.querySelector('#general');

    ipcRenderer.on('offices', (e, data)=>{
        let string = '';
        if(data.length > 0){
            offices = data;
            data.forEach(item=>{
                if(parseInt(item.parent) === 0){
                    string+=`
                        <div class="font-gotham uppercase"><i class="fas fa-city"></i> ${item.name}</div>
                    `;
                    data.forEach(el=>{
                        if(parseInt(el.parent) !== 0){
                            string+=`
                                <div class="margin-left-5 margin-top-5">
                                    <div class="flex-row align-items-center justify-content-space-between">
                                        <div class="uppercase font-gotham truncate width-90"><i class="fas fa-building"></i> ${el.name}</div>
                                        <div><i data-creator="${el.admin}" data-parent="0" data-office="${el.name}" class="fi fi-rr-folder-add add-folder cursor-pointer" title="Create folder under ${el.name}"></i></div>
                                    </div>
                                </div>
                            `;
                        }
                    });
                }
            });
            office.innerHTML = string;

            document.querySelectorAll('.add-folder').forEach(item=>{
                item.addEventListener('click', e=>{
                    let parent = item.getAttribute('data-parent');
                    let folderOffice = item.getAttribute('data-office');
                    let creaTor = item.getAttribute('data-creator');
                    let data = {parent, office: folderOffice, creator: creaTor};
                    ipcRenderer.send('add_new_folder', data);
                });
            });
        }else{
            office.innerHTML = string;
        }
    });

    ipcRenderer.on('folders', (e, data)=>{
        setTimeout(()=>{
            let string = '', str = '';
            if(offices.length > 0){
                offices.forEach(item=>{
                    if(parseInt(item.parent) !== 0){
                        string+=`
                            <div class="font-gotham uppercase margin-top-5"><i class="fas fa-building"></i> ${item.name}</div>
                        `;
                        if(data.length > 0){
                            data.forEach(el=>{
                                if(el.office === item.name & parseInt(el.parent) === 0){
                                    string+=`
                                        <div class="margin-top-5 margin-left-5">
                                            <div class="flex-row align-items-center justify-content-space-between">
                                                <div data-id="${el.id}" class="uppercase font-gotham truncate cursor-pointer unit-folder width-90" title="${el.name}"><i class="fi fi-rr-folder"></i> ${el.name}</div>
                                                <div><i data-creator="${el.creator}" data-path="${el.path}" data-parent="${el.id}" data-office="${el.office}" class="fi fi-rr-folder-add sub-folder cursor-pointer" title="Create subfolder under ${el.name}"></i></div>
                                            </div>
                                    `;

                                    data.forEach(fold=>{
                                        if(parseInt(fold.parent) === parseInt(el.id)){
                                            string+=`
                                                <div data-id="${fold.id}" class="margin-top-5 margin-left-5 truncate cursor-pointer unit-folder" title="${fold.name}"><i class="fi fi-rr-folder"></i> ${fold.name}</div>
                                            `;
                                        }
                                    });

                                    string+=`
                                            </div>
                                        `;
                                }
                            });
                        }
                    }
                });
                folderList.innerHTML = string;

                document.querySelectorAll('.sub-folder').forEach(item=>{
                    item.addEventListener('click', e=>{
                        let parent = item.getAttribute('data-parent');
                        let path = item.getAttribute('data-path');
                        let folderOffice = item.getAttribute('data-office');
                        let creaTor = item.getAttribute('data-creator');
                        let data = {parent, office: folderOffice, creator: creaTor, path};
                        ipcRenderer.send('add_new_folder', data);
                    });
                });

                document.querySelectorAll('.unit-folder').forEach((item, index)=>{
                    item.addEventListener('click', e=>{
                        let id = item.getAttribute('data-id');

                        if(item.classList.contains('color-bg')){
                            item.classList.remove('color-bg');
                            general.setAttribute('data-id', '');
                            manageGroup.classList.remove('green-text');
                        }else{
                            item.classList.add('color-bg');
                            general.setAttribute('data-id', id);
                            manageGroup.classList.add('green-text');

                        }

                        document.querySelectorAll('.unit-folder').forEach((el, i)=>{
                            if(i !== index){
                                el.classList.remove('color-bg');
                            }
                        });
                    });
                });
            }
        },100);
    });

    ipcRenderer.on('reset_manage_folder_setting', ()=>{
        manageGroup.classList.remove('green-text');
        general.setAttribute('data-id', '');
    });

    manageGroup.addEventListener('click', e=>{
        let id = general.getAttribute('data-id');
        id = parseInt(id);

        if(id !== '' & manageGroup.classList.contains('green-text')){
            ipcRenderer.send('manage_folder', id);
        }
    });
});