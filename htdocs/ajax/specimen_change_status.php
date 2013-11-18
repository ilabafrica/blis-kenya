<?php
#
# Changes the status of the specimen
# Called via Ajax from sample collection

include("../includes/db_lib.php");
$sid = $_REQUEST['sid'];
$time_collected = date("Y-m-d H:i:s");
set_specimen_status($sid, 0,$time_collected);


?>
