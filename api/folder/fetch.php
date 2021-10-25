<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Folder.php";

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Folder
$folder = new Folder($db);

$fetch = $folder->fetch();

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