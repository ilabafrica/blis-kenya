<?php
function reverse_birthday( $tarehe,$days ){
return date('Y-m-d',strtotime("-$days days",strtotime($tarehe)))."\n"; //n days before the given date
}
function date_from_mssql_to_mysql($date){
	$mssql_format = new DateTime($date);
	$mysql_format = $mssql_format->format('Y-m-d');
	return $mysql_format;
}
$date = date_from_mssql_to_mysql('11/10/2010');
echo $date.'<br>';
$dob = reverse_birthday($date, 8395);
echo $dob;
?>