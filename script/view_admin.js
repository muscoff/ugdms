const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const tbody = document.querySelector('tbody');
    let fullname = document.querySelector('#fullname');
    const search = document.querySelector('#search');
    let acc_obj; let url;

    ipcRenderer.on('loginObj', (e, item)=>{
        acc_obj = item;
        fullname.innerHTML = item.fullname != '' ? item.fullname : item.username;
    });

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('administrators', (e, data)=>{
        let string = `
            <tr class="font-helvetica">
                <td colspan="2"></td>
                <td>View</td><td>Download</td><td>Upload</td><td>Delete</td><td colspan="2">Actions</td>
            </tr>
        `;
        if(data.length > 0){
            data.forEach(item=>{
                string+=`
                    <tr class="font-helvetica">
                        <td>${item.fullname}</td>
                        <td>${item.username}</td>
                        <td class="center-text">${item.view === 1 ? `<i data-view="${item.view}" data-id="${item.id}" class="view fas fa-toggle-on green-text font-20"></i>`: `<i data-view="${item.view}" data-id="${item.id}" class="view fas fa-toggle-off font-20"></i>`}</td>
                        <td class="center-text">${item.download === 1 ? `<i data-download="${item.download}" data-id="${item.id}" class="download fas fa-toggle-on green-text font-20"></i>`: `<i data-download="${item.download}" data-id="${item.id}" class="download fas fa-toggle-off font-20"></i>`}</td>
                        <td class="center-text">${item.upload === 1 ? `<i data-upload="${item.upload}" data-id="${item.id}" class="upload fas fa-toggle-on green-text font-20"></i>`: `<i data-upload="${item.upload}" data-id="${item.id}" class="upload fas fa-toggle-off font-20"></i>`}</td>
                        <td class="center-text">${item.delete === 1 ? `<i data-delete="${item.delete}" data-id="${item.id}" class="delete fas fa-toggle-on green-text font-20"></i>`: `<i data-delete="${item.delete}" data-id="${item.id}" class="delete fas fa-toggle-off font-20"></i>`}</td>
                        <td class="center-text"><i data-id="${item.id}" class="edit-admin fi fi-rr-edit font-20" title="Edit Admin"></i></td>
                        <td class="center-text"><i data-id="${item.id}" data-name="${item.fullname !== '' ? item.fullname : item.username}" class="delete-admin fi fi-rr-trash font-20" title="Delete Admin"></i></td>
                    </tr>
                `;
            });
            tbody.innerHTML = string;
            document.querySelectorAll('.view').forEach(item=>{
                item.addEventListener('click', e=>{
                    let view = e.target.getAttribute('data-view');
                    let id = e.target.getAttribute('data-id');
                    view = parseInt(view); id = parseInt(id);

                    fetch(`${url}api/admin/update_permission.php?id=${id}&view=${view}`)
                    .then(response=>response.json())
                    .then(response=>{
                        if(response.status){
                            ipcRenderer.send('update_administrator_success');
                        }
                    })
                    .catch(err=>{console.log(err)});
                });
            });

            document.querySelectorAll('.download').forEach(item=>{
                item.addEventListener('click', e=>{
                    let download = e.target.getAttribute('data-download');
                    let id = e.target.getAttribute('data-id');
                    download = parseInt(download); id = parseInt(id);

                    fetch(`${url}api/admin/update_permission.php?id=${id}&download=${download}`)
                    .then(response=>response.json())
                    .then(response=>{
                        if(response.status){
                            ipcRenderer.send('update_administrator_success');
                        }
                    })
                    .catch(err=>{console.log(err)});
                });
            });

            document.querySelectorAll('.upload').forEach(item=>{
                item.addEventListener('click', e=>{
                    let upload = e.target.getAttribute('data-upload');
                    let id = e.target.getAttribute('data-id');
                    upload = parseInt(upload); id = parseInt(id);

                    fetch(`${url}api/admin/update_permission.php?id=${id}&upload=${upload}`)
                    .then(response=>response.json())
                    .then(response=>{
                        if(response.status){
                            ipcRenderer.send('update_administrator_success');
                        }
                    })
                    .catch(err=>{console.log(err)});
                });
            });

            document.querySelectorAll('.delete').forEach(item=>{
                item.addEventListener('click', e=>{
                    let delete_doc = e.target.getAttribute('data-delete');
                    let id = e.target.getAttribute('data-id');
                    delete_doc = parseInt(delete_doc); id = parseInt(id);

                    fetch(`${url}api/admin/update_permission.php?id=${id}&delete=${delete_doc}`)
                    .then(response=>response.json())
                    .then(response=>{
                        if(response.status){
                            ipcRenderer.send('update_administrator_success');
                        }
                    })
                    .catch(err=>{console.log(err)});
                });
            });

            document.querySelectorAll('.edit-admin').forEach(item=>{
                item.addEventListener('click', e=>{
                    let id = e.target.getAttribute('data-id'); id = parseInt(id);
                    ipcRenderer.send('edit_admin_data', id);
                });
            });

            document.querySelectorAll('.delete-admin').forEach(item=>{
                item.addEventListener('click', e=>{
                    let id = e.target.getAttribute('data-id'); id = parseInt(id);
                    let name = e.target.getAttribute('data-name');
                    if(confirm(`Do you want to proceed to delete ${name}`)){
                        fetch(`${url}api/admin/delete.php?id=${id}`)
                        .then(response=>response.json())
                        .then(response=>{
                            if(response.status){
                                ipcRenderer.send('update_administrator_success');
                            }
                        });
                    }
                });
            });
        }else{
            tbody.innerHTML = string;
        }
    });

    search.addEventListener('input', e=>{
        let val = e.target.value; val = val.toLowerCase();
        ipcRenderer.send('search_admin', val);
    });
});