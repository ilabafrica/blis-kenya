<?php
/*
 * @iLabAfrica
 * Pushes results back to sanitas
 */
$json_string = $_POST['labRequest'];
$api_key = "6ZZXWUKE9";
$Sanitas_inbound_url = "http://192.168.1.9:8888/sanitas/bliss/notify?api_key=".$api_key;
/*
 * Send POST request with HttpRequest
 */

$r = new HttpRequest($Sanitas_inbound_url, HttpRequest::METH_POST);
$r->addPostFields(array('labResult' => $json_string));

try {
	echo $r->send()->getBody();
	$response = $r->send()->getBody();
	error_log("\nHTTP Response: Upload Result ===>".$response, 3, "/home/royrutto/Desktop/my.error.log");
} catch (HttpException $ex) {
	echo $ex;
	error_log("\nHTTP Exception: ======>".$ex, 3, "/home/royrutto/Desktop/my.error.log");
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