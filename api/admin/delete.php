<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Admin.php";
include_once SERVER."libraries/lib.php";

$id = isset($_GET['id']) & !empty($_GET['id']) ? sanitize_text($_GET['id']) : die();

// initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Admin
$admin = new Admin($db);
$admin->id = $id;

if($admin->deleteAction()){
    echo json_encode(array('status'=>true));
}else{
    echo json_encode(array('status'=>false));
}