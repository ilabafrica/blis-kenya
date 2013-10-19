<?php
/*
 * @iLabAfrica
 * Pushes results back to sanitas
 */
include("../includes/db_lib.php");

$test_id = $_REQUEST['test_id'];
$api_key = "0QLUIWX3R";
$sanitas_inbound_url = "http://192.168.1.9:8888/sanitas/bliss/notify?api_key=".$api_key;

$lab_numbers = API::getTestLabNoToPush();

foreach ($lab_numbers as $lab_no){	
	
	$test = Test::getByExternalLabno($lab_no['labNo']);
	$specimen_id = $test->specimenId;
	$specimen = get_specimen_by_id($specimen_id);
	$patient = get_patient_by_id($specimen->patientId);
	
	$result = str_replace("<br>","",$test->decodeResult());
	$result = str_replace("&nbsp;","",$result);
	$result = str_replace("<b>","",$result);
	$result = str_replace("</b>","",$result);
	$time_stamp = date("Y-m-d H:i:s");
	
	$emr_user_id = get_emr_user_id($_SESSION['user_id']);
	if ($emr_user_id ==null)$emr_user_id="59";
	 
	$json_string ='{"labNo": '.$test->external_lab_no.',"requestingClinician": '.$emr_user_id.',"result": '.$result.'}';
	error_log("\n".$time_stamp.": Push ===>".$json_string, 3, "/home/royrutto/Desktop/my.error.log");

	/*
	 * Send POST request with HttpRequest
	 */
	$r = new HttpRequest($sanitas_inbound_url, HttpRequest::METH_POST);
	$r->addPostFields(array('labResult' => $json_string));
	if($specimen->external_lab_no!=NULL){
		try {
			
			$response = $r->send()->getBody();
			error_log("\n".$time_stamp.": HTTP Response: Uploaded Result ===>".$response, 3, "/home/royrutto/Desktop/my.error.log");
			
		} catch (HttpException $ex) {
			
			error_log("\n".$time_stamp.": HTTP Exception: ======>".$ex, 3, "/home/royrutto/Desktop/my.error.log");
			
		}
	}
	
	if($response=="Test updated"){
		
		//API::updateExternalLabrequest($patient->surrogateId, $specimen->external_lab_no, $result);
		
	}
}

/*
 * Sent POST request as json with curl
*/
/*$ch = 	curl_init($Sanitas_inbound_url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
);
$result = curl_exec($ch);

error_log("\nHTTP rsponse ================>".$result, 3, "/home/royrutto/Desktop/my.error.log");
*/

?>