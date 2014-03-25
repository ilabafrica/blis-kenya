<?php
#
# Changes the status of the specimen
# Called via Ajax from sample collection

include("../includes/db_lib.php");
$sid = $_REQUEST['sid'];
$time_collected = date("Y-m-d H:i:s");
$date_collected = date("Y-m-d");
set_specimen_status($sid, 0,$date_collected,$time_collected);


?>
