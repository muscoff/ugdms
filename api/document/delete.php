<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Document.php";
include_once SERVER."libraries/lib.php";

$id = isset($_GET['id']) & !empty($_GET['id']) ? sanitize_text($_GET['id']) : die();

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Document
$document = new Document($db);
$document->id = $id;

$single = json_decode(file_get_contents(url."api/document/single.php?id=".$document->id),true);
$path = $single['path']; $path = ltrim($path, '/');
$file = $single['link'];
$explodeFile = explode('/', $file);
$filename = end($explodeFile);
$fileToDelete = SERVER.$path."/".$filename;

if($document->delete()){
    unlink($fileToDelete);
    echo json_encode(array('status'=>true));
}else{
    echo json_encode(array('status'=>false));
}