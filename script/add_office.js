const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const parent = document.querySelector('#parent');
    const parentPath = document.querySelector('#path');
    const error = document.querySelector('#error');
    let url;

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('obj', (e, obj)=>{
        if(!obj.office){
            document.querySelectorAll('.office-icon').forEach(item=>{
                item.className = 'fas fa-building';
            });
            document.querySelector('#display-name').innerHTML = 'Create a unit';
            document.querySelector('#office-title').innerHTML = 'Unit Name';
        }
        parent.setAttribute('value', obj.parent);
        if(obj.hasOwnProperty('parent_path')){
            parentPath.setAttribute('value', obj.parent_path);
        }
    });

    form.addEventListener('submit', e=>{
        e.preventDefault();
        let pth = e.target[0].value;
        let p = e.target[1].value;
        p = parseInt(p);
        let name = e.target[2].value;
        pth = p === 0 ? name : pth;
        let location = p === 0 ? name : pth+'/'+name;

        let formdata = new FormData();
        formdata.append('name', name);
        formdata.append('parent', p);
        formdata.append('parent_path', pth);
        formdata.append('location', location);

        fetch(`${url}api/offices/create.php`, {method: "POST", body: formdata})
        .then(response=>response.json())
        .then(response=>{
            if(response.status){
                ipcRenderer.send('close_add_office');
                ipcRenderer.send('office_success');
            }else{
                error.innerHTML = response.msg;
                setTimeout(()=>{error.innerHTML = '';},3000);
            }
        });
    });
});