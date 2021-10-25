const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const tbody = document.querySelector('tbody');
    let fullname = document.querySelector('#fullname');
    const search = document.querySelector('#search');
    let acc_obj; let url, office;

    ipcRenderer.on('loginObj', (e, item)=>{
        acc_obj = item;
        fullname.innerHTML = item.fullname != '' ? item.fullname : item.username;
    });

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('office', (e, data)=>{
        office = data;
    });

    ipcRenderer.on('users', (e, data)=>{
        setTimeout(()=>{
            if(data.length > 0){
                let string = '';
                data.forEach(item=>{
                    string+=`
                        <tr class="font-helvetica">
                            <td>${item.fullname}</td>
                            <td>${item.username}</td>
                        `;
                    office.forEach(val=>{
                        if(parseInt(val.id) === parseInt(item.office)){
                            string+=`<td>${val.name}</td>`;
                        }
                    });

                    string+=`
                            <td class="center-text"><i data-id="${item.id}" class="edit-admin fi fi-rr-edit font-20" title="Edit Admin"></i></td>
                            <td class="center-text"><i data-id="${item.id}" data-name="${item.fullname !== '' ? item.fullname : item.username}" class="delete-admin fi fi-rr-trash font-20" title="Delete Admin"></i></td>
                        </tr>
                    `;
                });
                tbody.innerHTML = string;

                document.querySelectorAll('.edit-admin').forEach(item=>{
                    item.addEventListener('click', e=>{
                        let id = e.target.getAttribute('data-id'); id = parseInt(id);
                        ipcRenderer.send('edit_user_data', id);
                    });
                });

                document.querySelectorAll('.delete-admin').forEach(item=>{
                    item.addEventListener('click', e=>{
                        let id = e.target.getAttribute('data-id'); id = parseInt(id);
                        let name = e.target.getAttribute('data-name');
                        if(confirm(`Do you want to proceed to delete ${name}`)){
                            fetch(`${url}api/users/delete.php?id=${id}`)
                            .then(response=>response.json())
                            .then(response=>{
                                if(response.status){
                                    ipcRenderer.send('update_user_success');
                                }
                            });
                        }
                    });
                });
            }else{
                tbody.innerHTML = string;
            }
        },100);
    });

    search.addEventListener('input', e=>{
        let val = e.target.value; val = val.toLowerCase();
        ipcRenderer.send('search_user', val);
    });
});