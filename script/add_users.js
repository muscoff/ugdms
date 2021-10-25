const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const creator_user = document.querySelector('#creator');
    const office = document.querySelector('#office');
    const error = document.querySelector('#error');
    let url = ''; let acc_obj;

    ipcRenderer.on('appUrl', (e, item)=>{
        url = item;
    });

    ipcRenderer.on('office', (e, data)=>{
        let string = '<option value="">Select Office</option>'
        if(data.length > 0){
            data.forEach(item=>{
                string+=`
                    <option value="${item.id}">${item.name}</option>
                `;
            });
            office.innerHTML = string;
        }
    });

    ipcRenderer.on('loginObj', (e, item)=>{
        acc_obj = item; creator_user.setAttribute('value', item.username);
    });

    form.addEventListener('submit', e=>{
        e.preventDefault();
        let fullname = e.target[0].value;
        let username = e.target[1].value;
        let password = e.target[2].value;
        let confirmP = e.target[3].value;
        let userOffice = office.value;
        let c = creator_user.value;

        if(password == confirmP){
            let formdata = new FormData();
            formdata.append('fullname', fullname);
            formdata.append('username', username);
            formdata.append('password', password);
            formdata.append('office', userOffice);
            formdata.append('creator', c);

            fetch(`${url}api/users/add_user.php`, {method: "POST", body: formdata})
            .then(response=>response.json())
            .then(response=>{
                if(response.hasOwnProperty('status')){
                    error.classList.remove('red-text');
                    error.classList.add('white-text');
                    error.innerHTML = response.msg;
                    form.reset();
                    creator_user.setAttribute('value', acc_obj.username);
                    ipcRenderer.send('reload_users');
                    setTimeout(()=>{
                        error.innerHTML = '';
                    },2000);
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
  