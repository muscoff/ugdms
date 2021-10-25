<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Users.php";
include_once SERVER."libraries/lib.php";

$id = isset($_GET['id']) & !empty($_GET['id']) ? sanitize_text($_GET['id']) : die();

// initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Users
$users = new Users($db);
$users->id = $id;

if($users->deleteAction()){
    echo json_encode(array('status'=>true));
}else{
    echo json_encode(array('status'=>false));
}