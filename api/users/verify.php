<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Users.php";
include_once SERVER."libraries/lib.php";

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Users
$users = new Users($db);

if(isset($_POST['username']) & isset($_POST['password']) & !empty($_POST['username']) & !empty($_POST['password'])){
    $users->username = sanitize_text($_POST['username']);
    $users->password = sanitize_text($_POST['password']);
    $result = $users->verify();
    echo json_encode($result);
}else{
    echo json_encode(array('status'=>false, 'msg'=>'Username or password cannot be empty'));
}