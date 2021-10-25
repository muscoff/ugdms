<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: Application/json');

include_once $_SERVER['DOCUMENT_ROOT']."/ugdms/def/index.php";
include_once SERVER."db/conn.php";
include_once SERVER."models/Document.php";
include_once SERVER."pdfparser-master/vendor/autoload.php";
include_once SERVER."libraries/lib.php";

//$status = isset($_GET['status']) & !empty($_GET['status']) ? sanitize_text($_GET['status']) : die();
$user = isset($_GET['user']) & !empty($_GET['user']) ? sanitize_text($_GET['user']) : die();
$search = isset($_GET['search']) & !empty($_GET['search']) ? sanitize_text($_GET['search']) : die();
$search = strtolower($search);

// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();

// Initialize DB
$DBH = new DB();
$db = $DBH->connect();

// Initialize Document
$document = new Document($db);

$fetch = $document->fetch();

$doc_arr = [];
if(!empty($fetch)){
    foreach($fetch as $doc){
        if(strtolower($doc['creator']) == strtolower($user) | strtolower($doc['parent']) == strtolower($user)){
            array_push($doc_arr, $doc);
        }
    }
}

if(!empty($doc_arr)){
    $result = [];
    foreach($doc_arr as $book){
        // $pdf= $parser->parseFile($book['link']);
        // $text = $pdf->getText();
        // $text = strtolower($text);

        $name = strtolower($book['name']);
        $exams_date = $book['exams_date'];
        $year = explode('-', $exams_date); $year = $year[0];
        $semester = strtolower($book['semester']);

        //$res = strstr($text, $search);

        if($name == $search | $exams_date == $search | $year == $search | $semester == $search){
            array_push($result, $book);
        }else{
            $pdf= $parser->parseFile($book['link']);
            $text = $pdf->getText();
            $text = strtolower($text);

            $res = strstr($text, $search);
            if(!is_null($res) & !empty($res)){
                array_push($result, $book);
            }
        }

        // if(!is_null($res) & !empty($res)){
        //     array_push($result, $book);
        // }
    }

    echo json_encode($result);
}else{
    echo json_encode($doc_arr);
}