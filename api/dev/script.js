function DOCMANAGE(){
    this.username;
    this.password;
    this.url;
}

DOCMANAGE.prototype.verifyAdmin = function (callback){
    let formdata = new FormData();
    formdata.append('username', this.username);
    formdata.append('password', this.password);

    fetch(`${this.url}api/admin/verify.php`, {method: "POST", body: formdata})
    .then(response=>response.json())
    .then(response=>callback(response))
    .catch(error=>console.log(error));
}

DOCMANAGE.prototype.verifyUser = function (callback){
    let formdata = new FormData();
    formdata.append('username', this.username);
    formdata.append('password', this.password);

    fetch(`${this.url}api/users/verify.php`, {method: "POST", body: formdata})
    .then(response=>response.json())
    .then(response=>callback(response))
    .catch(error=>console.log(error));
}