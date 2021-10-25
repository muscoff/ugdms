<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Admin.php";
include_once SERVER."libraries/lib.php";

// Get id
$id = isset($_GET['id']) & !empty($_GET['id']) ? sanitize_text(($_GET['id'])) : die();

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Admin
$admin = new Admin($db);

$admin->id = $id;

$single = $admin->single();

echo json_encode($single);