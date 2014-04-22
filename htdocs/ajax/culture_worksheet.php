<?php

/**
 *  Receive requests from culture worksheet and sends to db_lib.php for processing
 * 	 
*/


require_once("../includes/db_lib.php");

$testId = $_POST['testId'];
$observation = $_POST['observation'];

$userId = $_SESSION['user_id'];

Culture::addObservation($userId, $testId, $observation);


?>