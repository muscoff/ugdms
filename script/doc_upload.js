const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const fpath = document.querySelector('#path');
    const error = document.querySelector('#error');
    const office = document.querySelector('#office');
    const c = document.querySelector('#creator');
    let acc_obj; let url;

    ipcRenderer.on('data', (e, data)=>{
        c.setAttribute('value', data.creator);
        fpath.setAttribute('value', data.path);
        office.setAttribute('value', data.office);
        document.querySelector('#parent').setAttribute('value', data.parent);
    });

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    form.addEventListener('submit', e=>{
        e.preventDefault();
        let pth = e.target[0].value;
        let creaTor = e.target[1].value;
        let name = e.target[2].value;
        let centre = e.target[3].value;
        let doe = e.target[4].value;
        let semester = e.target[5].value;
        let file = e.target[6].files[0];
        let off = document.querySelector('#office').value;
        let p = document.querySelector('#parent').value;

        let formdata = new FormData();
        formdata.append('path', pth);
        formdata.append('creator', creaTor);
        formdata.append('name', name);
        formdata.append('centre', centre);
        formdata.append('date', doe);
        formdata.append('semester', semester);
        formdata.append('file', file);
        formdata.append('office', off);
        formdata.append('parent', p);

        fetch(`${url}api/document/create.php`, {method: "POST", body: formdata})
        .then(response=>response.json())
        .then(response=>{
            if(response.status){
                error.innerHTML = response.msg;
                //form.reset();
                document.querySelector('#name').setAttribute('value', '');
                document.querySelector('#centre').setAttribute('value', '');
                document.querySelector('#date').setAttribute('value', '');
                ipcRenderer.send('doc_success');
                setTimeout(()=>{error.innerHTML = '';},3000);
            }else{
                error.innerHTML = response.msg;
                setTimeout(()=>{error.innerHTML = '';},3000);
            }
        })
        .catch(error=>{console.log(error)});
    });
});