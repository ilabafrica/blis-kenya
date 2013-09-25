<?php
/*
 * @iLabAfrica
 * Pushes results back to sanitas
 */
$data_string = json_encode($_POST, true);


error_log($data_string, 3, "/home/royrutto/Desktop/my.error.log");


error_log($data_string, 3, "/home/royrutto/Desktop/my.error.log");
$ch = curl_init('http://192.168.1.9:8888/sanitas/notify?api_key=6ZZXWUKE9');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json',
'Content-Length: ' . strlen($data_string))
);

$result = curl_exec($ch);
?>