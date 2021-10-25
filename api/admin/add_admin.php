<?php
header('Access-Control-Allow-Origin: *');
include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Admin.php";
include_once SERVER."libraries/lib.php";

// initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Admin
$admin = new Admin($db);

if(isset($_POST['username']) & isset($_POST['password']) & !empty($_POST['username']) & !empty($_POST['password'])){
    $admin->fullname = sanitize_text($_POST['fullname']);
    $admin->username = sanitize_text($_POST['username']);
    $admin->password = sanitize_text($_POST['password']);
    $admin->super_status = 0;

    if($admin->create()){
        echo json_encode(array('msg'=>'Admin account created', 'status'=>true));
    }else{
        echo json_encode(array('msg'=>'Failed to create admin account. Try again'));
    }
}else{
    echo json_encode(array('msg'=>'Username or Password cannot be empty'));
}