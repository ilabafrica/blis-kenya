<?php
include("../includes/db_lib.php");
if (isset($_REQUEST['url'])){
	$url = urldecode($_REQUEST['url']);
	$url = 'http://' . str_replace('http://', '', $url); // Avoid accessing the file system
	echo file_get_contents($url);
}


if(isset($_REQUEST['users_data'])){
	$user_array = $_REQUEST['users_data'];
	$length = count($user_array);
	$values ='';
 	for($i=0; $i<$length; $i++){
 		$password = encrypt_password($user_array[$i]['username']);
 		$level=0;
 		$created_by = $_SESSION['user_id'];
 		$lab_config = $_SESSION['lab_config_id'];
 		$lang_id ='default';
 		$values.='("'.$user_array[$i]['username'].'","'.$password.'","'.$user_array[$i]['username'].'","'.$level.'","'.$created_by.'","'.$lab_config.'","'.$lang_id.'","'.$user_array[$i]['id'].'")';
 		if (!($i==$length-1)) $values.=',';
 	}
	import_users($values);
}
?>

