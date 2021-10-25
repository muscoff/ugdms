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
    $users->fullname = sanitize_text($_POST['fullname']);
    $users->creator = sanitize_text($_POST['creator']);
    $users->username = sanitize_text($_POST['username']);
    $users->password = sanitize_text($_POST['password']);
    $users->office = sanitize_text($_POST['office']);

    if($users->create()){
        echo json_encode(array('status'=>true, 'msg'=>'Account created'));
    }else{
        echo json_encode(array('status'=>false, 'msg'=>'Failed to create account'));
    }
}else{
    echo json_encode(array('status'=>false, 'message'=>'Username or Password cannot be empty'));
}