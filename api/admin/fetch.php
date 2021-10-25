<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Admin.php";

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Admin
$admin = new Admin($db);

$fetch = $admin->fetch();

if($fetch){
    echo json_encode(array('status'=>true));
}else{
    echo json_encode(array('status'=>false));
}