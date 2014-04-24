<?php

/**
 *  Receive requests from culture worksheet and sends to db_lib.php for processing
 * 	 
*/


require_once("../includes/db_lib.php");

	$testId = $_REQUEST['testId'];
	$observation = $_REQUEST['obs'];
	$action = $_REQUEST['action'];

	$userId = $_SESSION['user_id'];

	if($action == 'add'){
	Culture::addObservation($userId, $testId, $observation);
 	}

 	if ($action == "draw"){
	$obsv = Culture::getAllObservations($testId);

	foreach ($obsv as $observation) {
		$observation->userId = get_username_by_id($observation->userId);
		$observation->time_stamp = time_elapsed_pretty($observation->time_stamp);
	}
	echo json_encode($obsv);
	}
?>