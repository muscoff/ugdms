<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Users.php";
include_once SERVER."libraries/lib.php";

// Get id
$id = isset($_GET['id']) & !empty($_GET['id']) ? sanitize_text(($_GET['id'])) : die();

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Users
$users = new Users($db);

$users->id = $id;

$single = $users->single();

echo json_encode($single);