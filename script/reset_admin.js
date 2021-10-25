const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const error = document.querySelector('#error');
    const id = document.querySelector('#id');
    const fn = document.querySelector('#fullname');
    let url = '';

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('loginObj', (e, data)=>{
        id.setAttribute('value', data.id);
        fn.setAttribute('value', data.fullname);
    });

    form.addEventListener('submit', e=>{
        e.preventDefault();
        let userId = e.target[0].value;
        let fullname = e.target[1].value;
        let password = e.target[2].value;
        let confirmP = e.target[3].value;

        if(password == '' | confirmP == ''){
            let formdata = new FormData();
                formdata.append('id', userId);
                formdata.append('fullname', fullname);

                fetch(`${url}api/admin/update_admin.php`, {method: "POST", body: formdata})
                .then(response=>response.json())
                .then(response=>{
                    if(response.status){
                        if(error.classList.contains('red-text')){
                            error.classList.remove('red-text');
                            error.classList.add('white-text');
                            ipcRenderer.send('close_reset_admin_browser');
                            ipcRenderer.send('update_admin_success', userId);
                        }
                    }else{
                        error.innerHTML = 'Failed to update record';
                        setTimeout(()=>{error.innerHTML = '';},3000);
                    }
                })
                .catch(err=>{
                    error.innerHTML = 'Error occurred. Try again';
                    setTimeout(()=>{error.innerHTML='';},3000);
                });
        }else{
            if(password == confirmP){
                let formdata = new FormData();
                formdata.append('id', userId);
                formdata.append('fullname', fullname);
                formdata.append('password', password);

                fetch(`${url}api/admin/update_admin.php`, {method: "POST", body: formdata})
                .then(response=>response.json())
                .then(response=>{
                    if(response.hasOwnProperty('status') & response.status){
                        if(error.classList.contains('red-text')){
                            error.classList.remove('red-text');
                            error.classList.add('white-text');
                            ipcRenderer.send('close_reset_admin_browser');
                            ipcRenderer.send('update_admin_success', userId);
                        }
                    }else{
                        error.innerHTML = 'Failed to update record';
                        setTimeout(()=>{error.innerHTML = '';},3000);
                    }
                })
                .catch(err=>{
                    error.innerHTML = 'Error occurred. Try again';
                    setTimeout(()=>{error.innerHTML='';},3000);
                });
            }else{
                error.innerHTML = 'Password does not match';
                setTimeout(()=>{
                    error.innerHTML = '';
                },3000);
            }
        }
    });
});
  