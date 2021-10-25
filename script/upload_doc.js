const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const doc = document.querySelector('.doc');
    const addF = document.querySelector('.add-f');
    const officeHolder = document.querySelector('#offices');
    const folderList = document.querySelector('#folder-list');
    const general = document.querySelector('#general');
    const doc_upload = document.querySelector('#upload-doc');
    const allDocuments = document.querySelector('#all-documents');
    const searchDoc = document.querySelector('#search-doc');
    let acc; let url; let documents, offices, vDoc, dDoc, delDocu;
    let group, folders;

    ipcRenderer.on('loginObj', (e, item)=>{
        acc = item;
    });

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('group', (e, data)=>{
        group = data;
        setTimeout(()=>{
            if(!acc.hasOwnProperty('super_status')){
                if(data.length > 0){
                    data.forEach(item=>{
                        let id = item.id;
                        document.querySelectorAll('.user-group').forEach(element=>{
                            let userGroup = element.getAttribute('data-group');
                            if(parseInt(id) === parseInt(userGroup)){
                                element.classList.remove('display-none');
                            }
                        });
                    });
                }
            }
        },300);
    });

    ipcRenderer.on('folders', (e, data)=>{
        folders = data;
        setTimeout(()=>{
            document.querySelectorAll('.folder').forEach(item=>{
                let dataName = item.getAttribute('data-name');
                let string = '';
                if(acc.hasOwnProperty('super_status')){
                    data.forEach(element=>{
                        if(parseInt(element.parent) === 0 & element.office === dataName){
                            vDoc = 1; dDoc = 1; delDocu = 1;
                            string+=`
                                <div class="flex-row align-items-center justify-content-space-between">
                                    <div class="uppercase font-gotham truncate"><i class="fi fi-rr-folder"></i> ${element.name}</div>
                                </div>
                                <div class="docs" data-delete="${element.delete}" data-view="${element.view}" data-download="${element.download}" data-path="${element.path}"></div>
                            `;

                            data.forEach(el=>{
                                if(parseInt(element.id) === parseInt(el.parent) & el.office === dataName){
                                    string+=`
                                        <div class="margin-left-5 margin-top-5">
                                            <div class="flex-row align-items-center justify-content-space-between">
                                                <div class="uppercase font-gotham truncate width-90"><i class="fi fi-rr-folder"></i> ${el.name}</div>
                                                <div><i data-path="${el.path}" data-office="${dataName}" class="fi fi-rr-upload upload"></i></div>
                                            </div>
                                        </div>
                                        <div class="docs" data-delete="${el.delete}" data-view="${el.view}" data-download="${el.download}" data-path="${el.path}"></div>
                                    `;
                                }
                            });
                        }
                    });
                    //item.innerHTML = string;
                }else{
                    data.forEach(element=>{
                        if(parseInt(element.parent) === 0 & element.office === dataName){
                            vDoc = parseInt(element.view); dDoc = parseInt(element.download); delDocu = parseInt(element.delete);
                            string+=`
                                <div data-id="${element.id}" data-group="${element.user_group === null ? '' : element.user_group}" class="display-none user-group">
                                    <div class="margin-top-5 flex-row align-items-center justify-content-space-between">
                                        <div class="font-gotham uppercase truncate"><i class="fi fi-rr-folder"></i> ${element.name}</div>
                                    </div>
                                </div>
                                <div class="docs" data-delete="${element.delete}" data-view="${element.view}" data-download="${element.download}" data-path="${element.path}"></div>
                            `;

                            data.forEach(el=>{
                                if(parseInt(element.id) === parseInt(el.parent) & el.office === dataName){
                                    string+=`
                                        <div data-id="${el.id}" data-group="${el.user_group === null ? '' : el.user_group}" class="display-none margin-left-5 margin-top-5 user-group">
                                            <div class="flex-row align-items-center justify-content-space-between">
                                                <div class="uppercase font-gotham truncate width-90"><i class="fi fi-rr-folder"></i> ${el.name}</div>
                                                <div><i data-path="${el.path}" data-office="${dataName}" class="fi fi-rr-upload upload ${parseInt(el.upload) !== 1 ? 'display-none' : ''}"></i></div>
                                            </div>
                                        </div>
                                        <div class="docs" data-delete="${el.delete}" data-view="${el.view}" data-download="${el.download}" data-path="${el.path}"></div>
                                    `;
                                }
                            });
                        }
                    });
                    //item.innerHTML = string;
                }
                item.innerHTML = string;
            });
            document.querySelectorAll('.upload').forEach(item=>{
                item.addEventListener('click', e=>{
                    let pth = item.getAttribute('data-path');
                    let off = item.getAttribute('data-office');
                    let c = acc.username;
                    let p = acc.hasOwnProperty('super_status') ? acc.username : acc.creator;
                    let data = {path: pth, office: off, creator: c, parent: p};
                    ipcRenderer.send('doc_upload', data);
                });
            });
        },200);
    });

    ipcRenderer.on('documents', (e, data)=>{
        documents = data;
        setTimeout(()=>{
            document.querySelectorAll('.docs').forEach(item=>{
                let filePath = item.getAttribute('data-path');
                let v = item.getAttribute('data-view');
                let del = item.getAttribute('data-delete');
                let d = item.getAttribute('data-download');
                let string = '';
                if(data.length > 0){
                    data.forEach(element=>{
                        if(element.path === filePath){
                            string+=`
                                <div class="margin-top-5 margin-left-5">
                                    <div class="flex-row align-items-center">
                                        <div class="width-85 view-doc cursor-pointer" data-link="${element.link}" data-download="${d}" data-view="${v}">
                                            <div class="truncate font-gotham"><i class="fi fi-rr-document"></i> ${element.name}</div>
                                        </div>
                                        <div class="width-15">
                                            <div class="flex-row align-items-center justify-content-space-between">
                                                <i class="fi fi-rr-download download-doc cursor-pointer" data-link="${element.link}" data-name="${element.name}" data-download="${d}"></i>
                                                <i class="fi fi-rr-trash delete-doc cursor-pointer" data-id="${element.id}" data-delete="${del}"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                    });
                }
                item.innerHTML = string;
            });
            document.querySelectorAll('.view-doc').forEach(item=>{
                item.addEventListener('click', e=>{
                    let link = item.getAttribute('data-link');
                    let viewDoc = acc.hasOwnProperty('super_status') ? 1 : item.getAttribute('data-view');
                    viewDoc = parseInt(viewDoc);
                    let download = acc.hasOwnProperty('super_status') ? 1 : item.getAttribute('data-download');
                    download = parseInt(download);
                    let obj = {link, download, view: viewDoc};
                    ipcRenderer.send('view_pdf', obj);
                });
            });

            document.querySelectorAll('.download-doc').forEach(item=>{
                item.addEventListener('click', e=>{
                    let link = item.getAttribute('data-link');
                    let name = item.getAttribute('data-name');
                    let download = acc.hasOwnProperty('super_status') ? 1 : item.getAttribute('data-download');
                    let a = document.createElement('a');
                    if(parseInt(download) === 1){
                        a.setAttribute('href', link);
                        a.setAttribute('download', name);
                        a.click();
                    }else{
                        let noteObj = {title: "Download Privilege", body: "You not have download privilege on this folder"};
                        ipcRenderer.send('manage_office_account_error', noteObj);
                    }
                });
            });

            document.querySelectorAll('.delete-doc').forEach(item=>{
                item.addEventListener('click', e=>{
                    let id = item.getAttribute('data-id');
                    let del = acc.hasOwnProperty('super_status') ? 1 : item.getAttribute('data-delete');
                    if(parseInt(del) === 1){
                        if(confirm('Do you want to delete this file?')){
                            fetch(`${url}api/document/delete.php?id=${id}`)
                            .then(response=>response.json())
                            .then(response=>{
                                if(response.status){
                                    ipcRenderer.send('doc_success');
                                }
                            })
                            .catch(err=>console.log(err));
                        }
                    }else{
                        let noteObj = {title: "Delete Privilege", body: "You not have delete privilege on this folder"};
                        ipcRenderer.send('manage_office_account_error', noteObj);
                    }
                });
            });

        },400);
    });

    ipcRenderer.on('offices', (e, data)=>{
        offices = data; //console.log(data);
        setTimeout(()=>{
            if(data.length > 0){
                let string = '';
                data.forEach(item=>{
                    if(parseInt(item.parent) === 0){
                        string+=`
                            <div class="font-gotham uppercase truncate"><i class="fas fa-city"></i> ${item.name}</div>
                        `;

                        data.forEach(el=>{
                            if(parseInt(el.parent) !== 0){
                                string+=`
                                    <div class="margin-top-5 margin-left-5">
                                        <div class="uppercase truncate font-gotham"><i class="fas fa-building"></i> ${el.name}</div>
                                        <div class="margin-top-5 margin-left-5">
                                            <div data-name="${el.name}" class="folder"></div>
                                        </div>
                                    </div>
                                `;
                            }
                        });
                    }
                });
                officeHolder.innerHTML = string;
            }else{
                officeHolder.innerHTML = '';
            }
        },100);
    });

    searchDoc.addEventListener('keyup', e=>{
        if(e.keyCode === 13){
            let val = e.target.value;
            if(val !== ''){
                let userV = acc.hasOwnProperty('super_status') ? acc.username : acc.creator;
                fetch(`${url}api/document/search.php?search=${val}&user=${userV}`)
                .then(response=>response.json())
                .then(response=>{
                    let string = '';
                    if(response.length > 0){
                        response.forEach(item=>{
                            string+=`
                                <div class="col-6 padding-all-10">
                                    <div class="search-item">
                                        <div class="truncate font-helvetica capitalize cursor-pointer view-d" data-link="${item.link}" data-download="${dDoc}" data-view="${vDoc}">${item.name}</div>
                                        <div class="margin-top-5 flex-row-reverse ${dDoc !== 1 & delDocu !== 1 ? 'display-none' : ''}">
                                            ${dDoc === 1 ? `<i data-name="${item.name}" data-download="${dDoc}" data-link="${item.link}" class="fi fi-rr-download download-d margin-left-5 cursor-pointer"></i>` : ''}
                                            ${delDocu === 1 ? `<i data-id="${item.id}" data-name="${item.name}" data-delete="${delDocu}" class="fi fi-rr-trash delete-d cursor-pointer"></i>` : ''}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        document.querySelector('#search-items').innerHTML = string;

                        document.querySelectorAll('.view-d').forEach(item=>{
                            item.addEventListener('click', e=>{
                                let link = item.getAttribute('data-link');
                                let vd = item.getAttribute('data-view');
                                vd = parseInt(vd);
                                let dd = item.getAttribute('data-download');
                                dd = parseInt(dd);
                                let obj = {view: vd, download: dd, link};
                                ipcRenderer.send('view_pdf', obj);
                            });
                        });

                        document.querySelectorAll('.download-d').forEach(item=>{
                            item.addEventListener('click', e=>{
                                let dd = item.getAttribute('data-download');
                                dd = parseInt(dd);
                                let name = item.getAttribute('data-name');
                                let link = item.getAttribute('data-link');
                                let a = document.createElement('a');
                                if(dd === 1){
                                    a.setAttribute('href', link);
                                    a.setAttribute('download', name);
                                    a.click();
                                }else{
                                    let note = {title: "Download Privilege", body: "You do not have download privilege on this file"};
                                    ipcRenderer.send('manage_office_account_error', note);
                                }
                            });
                        });

                        document.querySelectorAll('.delete-d').forEach(item=>{
                            item.addEventListener('click', e=>{
                                let id = item.getAttribute('data-id');
                                let dd = item.getAttribute('data-delete');
                                dd = parseInt(dd);
                                if(dd === 1){
                                    if(confirm(`Do you want to delete this file?`)){
                                        fetch(`${url}api/document/delete.php?id=${id}`)
                                        .then(response=>response.json())
                                        .then(response=>{
                                            if(response.status){
                                                ipcRenderer.send('doc_success');
                                                setTimeout(()=>{
                                                    document.querySelector('#search-items').innerHTML = '';
                                                },200);
                                            }
                                        })
                                        .catch(err=>console.log(err));
                                    }
                                }else{
                                    let note = {title: "Delete Privilege", body: "You do not have delete privilege on this file"};
                                    ipcRenderer.send('manage_office_account_error', note);
                                }
                            });
                        });
                    }else{
                        document.querySelector('#search-items').innerHTML = '';
                    }
                })
                .catch(err=>console.log(err));
            }else{
                document.querySelector('#search-items').innerHTML = '';
            }
        }
    });
});