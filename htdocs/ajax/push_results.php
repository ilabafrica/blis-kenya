<?php
/*
 * @iLabAfrica
 * Pushes results back to external hmis/emr system
 */

function send_result_to_externalS(){
#Sanitas Server Parameters
//Live OGLZ4JDBL
//Test 2L1JOGVWP	
$api_key = "";

//Livee http://192.168.1.10:8888/sanitas/bliss/notify?api_key=
//Test  http://192.168.1.9:8080/sanitas/bliss/notify?api_key=
$sanitas_inbound_url = "".$api_key;

#MedBoss MSSQL Server Parameters
//$server = '192.168.184.121:1432';
$server = '192.168.6.4';
$username = 'kapsabetadmin';
$password = 'kapsabet';

#Log Path for logging push result errors
$error_log_path ="../logs/blis.api.error.log";
#Gets lab requests with results so that results are returned 
$lab_numbers = API::getTestLabNoToPush();

foreach ($lab_numbers as $lab_no){
	
	#Test Details
	$lab_request_no = $lab_no['labNo'];
	$system_id  = $lab_no['system_id'];
	$result_ent = $lab_no['result'];
	$comments = $lab_no['comments'];
	
	$result_ent = trim(preg_replace('/\s+/', ' ', $result_ent));
	$comments = trim(preg_replace('/\s+/', ' ', $comments));
	
	if($comments==null or $comments==''){
		$comments = 'No Comments';
	}
	
	#Time Stamp
	$time_stamp = date("Y-m-d H:i:s");
	
	#User
	$emr_user_id = get_emr_user_id($_SESSION['user_id']);
	if ($emr_user_id ==null) $emr_user_id="59";
	 
	$user_name = get_actualname_by_id($_SESSION['user_id']);
	
	if ($system_id == "sanitas")
	{
			
		$json_string ='{"labNo": "'.$lab_request_no.'","requestingClinician": "'.$emr_user_id.'", "result": "'.$result_ent.'", "verifiedby": "'.$emr_user_id.'", "techniciancomment": "'.$comments.'"}';
		/*
		 * Send POST request with HttpRequest
		 */
		$r = new HttpRequest($sanitas_inbound_url, HttpRequest::METH_POST);
		$r->addPostFields(array('labResult' => $json_string));
		
		try {
			
			$response = $r->send()->getBody();
			
		} catch (HttpException $ex) {
			
			error_log("\n".$time_stamp.": HTTP Exception: ======>".$ex, 3, $error_log_path);
			
		}
	
		if($response=="Test updated"){
			
			API::updateExternalLabRequestSentStatus($lab_no['labNo'], 1);
			
		}else if($response!="Test updated"){
			
				error_log("\n".$time_stamp.": Response Error: ======>".$json_string. "----". $response, 3, $error_log_path);
				error_log("-------------------------------------------------------------------------------------------------\n\n\n\n", 3, $error_log_path);
		}
	}
	else if ($system_id == "medboss"){
		
		$link = mssql_connect($server, $username, $password);
		
		if (!$link)
		{
			error_log("\n".$time_stamp.": MSSQL Connection Error: ======>".mssql_get_last_message(), 3, $error_log_path);
		
		}
		
		if (!mssql_select_db('[Kapsabet]', $link)){
			
			error_log("\n".$time_stamp.": MSSQL Database Selection Error: ======>".mssql_get_last_message(), 3, $error_log_path);
		
		}
		$lab_request_no = intval($lab_request_no);
		$query = mssql_query("INSERT INTO 
				BlissLabResults (RequestID,OfferedBy,DateOffered, TimeOffered, TestResults) 
				VALUES ('$lab_request_no','$user_name','$time_stamp','$time_stamp','$result_ent')
				");
		
		if (!$query) {
			
			error_log("\n".$time_stamp.": MSSQL Query Error: ======>".mssql_get_last_message(), 3, $error_log_path);
			
		}else {
			
			API::updateExternalLabRequestSentStatus($lab_request_no, 1);

		}
		mssql_close($link);
	}
}
}
?>