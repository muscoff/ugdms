const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const error = document.querySelector('#error');
    let url = '';

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    form.addEventListener('submit', e=>{
        e.preventDefault();
        let fullname = e.target[0].value;
        let username = e.target[1].value;
        let password = e.target[2].value;
        let confirmP = e.target[3].value;

        if(password == confirmP){
            let formdata = new FormData();
            formdata.append('fullname', fullname);
            formdata.append('username', username);
            formdata.append('password', password);

            fetch(`${url}api/admin/create_super.php`, {method: "POST", body: formdata})
            .then(response=>response.json())
            .then(response=>{
                if(response.hasOwnProperty('status')){
                    if(error.classList.contains('red-text')){
                        error.classList.remove('red-text');
                        error.classList.add('white-text');
                        error.innerHTML = response.msg;
                        setTimeout(()=>{
                            ipcRenderer.send('terminate_app');
                        },2000);
                    }
                }else{
                    error.innerHTML = response.msg;
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
    });
});
  