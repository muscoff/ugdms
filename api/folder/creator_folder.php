<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Folder.php";
include_once SERVER."libraries/lib.php";

// Get creator
$creator = isset($_GET['creator']) & !empty($_GET['creator']) ? sanitize_text($_GET['creator']) : die();

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Folder
$folder = new Folder($db);
$folder->creator = $creator;

$fetch = $folder->creator_folder();

$data = array();

if(!empty($fetch)){
    foreach($fetch as $value){
        $arr = [];
        if(!empty($value['user_group'])){
            $item = rtrim($value['user_group']);
            $item = explode(',', $item);
            $arr = $item;
        }
        $value['groups'] = $arr;
        array_push($data, $value);
    }
    echo json_encode($data);
}else{
    echo json_encode($fetch);
}