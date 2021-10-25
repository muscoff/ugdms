window.addEventListener('DOMContentLoaded', () => {
    const admin =  document.querySelector('.adminBtn');
    const user =  document.querySelector('.userBtn');
    let width = window.innerWidth;

    if(width >= 821){
        fetch(`${appUrl}api/admin/fetch.php`)
        .then(response=>response.json())
        .then(response=>{
            if(response.status){
                admin.addEventListener('click', e=>{
                    window.location = `${appUrl}admin/`;
                });
            
                user.addEventListener('click', e=>{
                    window.location = `${appUrl}users/`;
                });
            }else{
                window.location = `${appUrl}admin/super_account/`;
            }
        })
        .catch(error=>console.log(error));
    }else{
        document.write('Sorry cannot view from screens with screen width not greater or equal to 821');
    }
    
});
  