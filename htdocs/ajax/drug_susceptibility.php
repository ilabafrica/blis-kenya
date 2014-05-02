<?php

/**
 *  Receive requests for drug susceptibility and sends to db_lib.php for processing
 * 	 
*/


require_once("../includes/db_lib.php");
 
//print_r($_POST);// you will get an array of all the values
$test = $_POST['test'];
$drug = $_POST['drug'];
$zone = $_POST['zone'];
$interpretation = $_POST['interpretation'];

/*Get user ID*/
$userId = $_SESSION['user_id'];
//print_r(count(array_values ($test)));
for($i=0; $i<count($test); $i++){
	DrugSusceptibility::addSusceptibility($userId,$test[$i],$drug[$i],$zone[$i],$interpretation[$i]);
}
	echo "Successfully saved!"

?>