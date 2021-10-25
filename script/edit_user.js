const {ipcRenderer} = require('electron');
window.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const error = document.querySelector('#error');
    const id = document.querySelector('#id');
    const fn = document.querySelector('#fullname');
    const office = document.querySelector('#office');
    let url = '';

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

    ipcRenderer.on('edit_data', (e, data)=>{
        id.setAttribute('value', data.id);
        fn.setAttribute('value', data.fullname);
        setTimeout(()=>{
            office.value = data.office;
        },10);
    });

    form.addEventListener('submit', e=>{
        e.preventDefault();
        let userId = e.target[0].value;
        let fullname = e.target[1].value;
        let password = e.target[2].value;
        let confirmP = e.target[3].value;
        let officeValue = office.value;

        if(password == '' | confirmP == ''){
            let formdata = new FormData();
                formdata.append('id', userId);
                formdata.append('fullname', fullname);
                formdata.append('office', officeValue);

                fetch(`${url}api/users/update_user.php`, {method: "POST", body: formdata})
                .then(response=>response.json())
                .then(response=>{
                    if(response.status){
                        if(error.classList.contains('red-text')){
                            error.classList.remove('red-text');
                            error.classList.add('white-text');
                            ipcRenderer.send('close_edit_user_browser');
                            ipcRenderer.send('update_user_success');
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
            if(password == confirmP){
                let formdata = new FormData();
                formdata.append('id', userId);
                formdata.append('fullname', fullname);
                formdata.append('password', password);
                formdata.append('office', officeValue);

                fetch(`${url}api/users/update_user.php`, {method: "POST", body: formdata})
                .then(response=>response.json())
                .then(response=>{
                    if(response.hasOwnProperty('status')){
                        if(error.classList.contains('red-text')){
                            error.classList.remove('red-text');
                            error.classList.add('white-text');
                            ipcRenderer.send('close_edit_user_browser');
                            ipcRenderer.send('update_user_success');
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
        }
    });
});
  