<?php
/**
 * Handles External Lab Requests from External Systems i.e. HMIS/EMR systems
 * 
 * Perfoms the following imports:
 * 	1. 	Handles JSON POST data from sanitas labrequest Outbound URL and passes 
 * 		it to the API method save_external_lab_request and INSERTS it to the database
 * 		table => external_lab_request
 * 		(Sanitas->Administration-> Integration->BLISS)
 * 
 * 	2. 	Queries the view LabRequestQueryForBliss from Microsoft SQL Server and INSERTS it to the database
 * 		table => external_lab_request
 */
include("../includes/db_lib.php");

$value_string = '';
$length = count($_POST);
if (!$length >1 || !$_POST==null){
	foreach($_POST as $key=>$value)
	{
		if ($key='labRequest'){
		 	$value_string = '';
		 	$json_request = (string)$value;
		 	$request_data = json_decode($json_request, true);
		 	$value_string.= '(';
		 	$value_string.= 
		 	'"'.$request_data['labNo'].'",'.
		 	'"'.$request_data['parentLabNo'].'",'.
		 	'"'.$request_data['requestingClinician'].'",'.
		 	'"'.$request_data['investigation'].'",'.
		 	'"'.$request_data['requestDate'].'",'.
		 	'"'.$request_data['patient']['id'].'",'.
		 	'"'.$request_data['patient']["fullName"].'",'.
		 	'"'.$request_data['patient']["dateOfBirth"].'",'.
		 	'"'.$request_data['patient']['gender'].'",'.
		 	'"'.$request_data['address']["address"].'",'.
		 	'"'.$request_data['address']["postalCode"].'",'.
		 	'"'.$request_data['address']["phoneNumber"].'",'.
		 	'"'.$request_data['address']["city"].'"';
		 	$value_string.= ')';
		 	$LabRequest = $value_string;
		 	
		 	API::save_external_lab_request($LabRequest);
		 } 
		}
}
?>