<?php  

$max_size = 2097152; //Standard Size = Max size
$typeStack = ['image/png', 'image/jpeg', 'image/jpg'];
$extStack = ['jpeg', 'jpg', 'png', 'gif'];

function single_image($image, $db=null, $server=null){
	$image_name = $image['name'];
	$image_name = rand(1,100000000).$image_name;
	$image_size = $image['size'];
	$image_type = $image['type'];
	$image_tmp = $image['tmp_name'];
	global $max_size;
	global $typeStack;
	global $extStack;
	$explodeImage = explode('.', $image_name);
	$image_ext = strtolower(end($explodeImage));
	$image_name = md5($image_name).'.'.$image_ext;

	//database location
	$dbLocal = $db.'/'.$image_name;

	//server location
	$serverLocal = $server.'/'.$image_name;

	if($image_size > $max_size){
		return;
	}else{
		if(in_array($image_ext, $extStack)){
			if(in_array($image_type, $typeStack)){
				return array('name'=>$image_name, 'size'=>$image_size, 'type'=>$image_type, 'tmp'=>$image_tmp, 'ext'=>$image_ext, 'db_location'=>$dbLocal, 'server_location'=>$serverLocal);
			}else{
				return;
			}
		}else{
			return;
		}
	}
}

function single_index_image($image, $index, $db=null, $server=null){
	$image_name = $image['name'][$index];
	$image_size = $image['size'][$index];
	$image_type = $image['type'][$index];
	$image_tmp = $image['tmp_name'][$index];
	global $max_size;
	global $typeStack;
	global $extStack;
	$explodeImage = explode('.', $image_name);
	$image_ext = strtolower(end($explodeImage));
	$image_name = md5($image_name).'.'.$image_ext;

	//database location
	$dbLocal = $db.'/'.$image_name;

	//server location
	$serverLocal = $server.'/'.$image_name;

	if($image_size > $max_size){
		return;
	}else{
		if(in_array($image_ext, $extStack)){
			if(in_array($image_type, $typeStack)){
				return array('name'=>$image_name, 'size'=>$image_size, 'type'=>$image_type, 'tmp'=>$image_tmp, 'ext'=>$image_ext, 'db_location'=>$dbLocal, 'server_location'=>$serverLocal);
			}else{
				return;
			}
		}else{
			return;
		}
	}
}

function multiple_images($image, $limit=5, $db=null, $server=null){
	$content = array();
	global $max_size;
	global $typeStack;
	global $extStack;

	if($limit>5){
		$limit = 5;
	}

	$count = count($image['name']);

	if($count > $limit){
		$count = $limit;
		for($i = 0; $i<$count; $i++){
			$image_name = $image['name'][$i];
			$image_size = $image['size'][$i];
			$image_type = $image['type'][$i];
			$image_tmp = $image['tmp_name'][$i];
			$explodeImage = explode('.', $image_name);
			$image_ext = end($explodeImage);

			//md5 image name
			$image_name = md5($image_name).'.'.$image_ext;

			$dbLocal = $db.'/'.$image_name;
			$serverLocal = $server.'/'.$image_name;

			if($image['size'][$i] <= $max_size){
				if(in_array($image_ext, $extStack)){
					if(in_array($image_type, $typeStack)){
						array_push($content, array('name'=>$image_name, 'size'=>$image_size, 'type'=>$image_type, 'ext'=>$image_ext, 'tmp'=>$image_tmp, 'db_location'=>$dbLocal, 'server_location'=>$serverLocal));
					}
				}
			}
		}
		return $content;
	}else{
		for($i = 0; $i<$count; $i++){
			$image_name = $image['name'][$i];
			$image_size = $image['size'][$i];
			$image_type = $image['type'][$i];
			$image_tmp = $image['tmp_name'][$i];
			$explodeImage = explode('.', $image_name);
			$image_ext = end($explodeImage);

			//md5 image name
			$image_name = md5($image_name).'.'.$image_ext;

			$dbLocal = $db.'/'.$image_name;
			$serverLocal = $server.'/'.$image_name;

			if($image['size'][$i] <= $max_size){
				if(in_array($image_ext, $extStack)){
					if(in_array($image_type, $typeStack)){
						array_push($content, array('name'=>$image_name, 'size'=>$image_size, 'type'=>$image_type, 'ext'=>$image_ext, 'tmp'=>$image_tmp, 'db_location'=>$dbLocal, 'server_location'=>$serverLocal));
					}
				}
			}
		}
		return $content;
	}
}

function move_file($tmp, $final){
	$truth = array();
	if(move_uploaded_file($tmp, $final)){
		array_push($truth, true);
	}else{
		array_push($truth, false);
	}
	return $truth;
}

function move_files($tmp, $final){
	$truth = array();
	$count = count($tmp);
	for($i=0; $i<$count; $i++){
		if(move_uploaded_file($tmp[$i], $final[$i])){
			array_push($truth, true);
		}else{
			array_push($truth, false);
		}
	}
	return $truth;
}

function unlink_many($array){
	foreach ($array as $key => $value) {
		unlink($value);
	}
}

function sanitize_text($text){
	return htmlspecialchars(strip_tags($text));
}

function redirect($url){
	header('Location: '.$url);
}

function redirect_msg($url, $msg){
	header('Location: '.$url."?msg=".$msg);
}

function redirect_and_msg($url, $msg){
	header('Location: '.$url."&msg=".$msg);
}

function check_host($referer){
	$host = $_SERVER['HTTP_HOST'];
	$general_referer = parse_url($referer, PHP_URL_HOST);
	if($host == $general_referer){
		return true;
	}else{
		return false;
	}
}

function from_another_url(){
	if(isset($_SERVER['HTTP_REFERER'])){
		return $_SERVER['HTTP_REFERER'];
	}
}

function echo_json($array){
	echo json_encode($array);
}

function return_json($array){
	return json_encode($array);
}

function return_string($array, $name){
	$string = '';
	foreach ($array as $key => $value) {
		$string.= $value[$name].',';
	}
	return $string;
}