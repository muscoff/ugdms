window.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const error = document.querySelector('#error');
    let url = appUrl;
    
    let docmanage = new DOCMANAGE();
    docmanage.url = url;


    form.addEventListener('submit', e=>{
        e.preventDefault();
        const username = e.target[0].value;
        const password = e.target[1].value;

        docmanage.username = username;
        docmanage.password = password;

        docmanage.verifyAdmin(response=>{
            console.log(response);
        });

        // let formdata = new FormData();
        // formdata.append('username', username);
        // formdata.append('password', password);

        // fetch(`${url}api/admin/verify.php`, {method: "POST", body: formdata})
        // .then(response=>response.json())
        // .then(response=>{
        //     if(response.status){
        //         ipcRenderer.send('login_obj', response);
        //     }else{
        //         error.innerHTML = 'Invalid username or password';
        //         setTimeout(()=>{error.innerHTML=''},3000);
        //     }
        // })
        // .catch(err=>{
        //     error.innerHTML = 'Error occurred. Try again';
        //     setTimeout(()=>{error.innerHTML='';},3000);
        // });
    });
});
  