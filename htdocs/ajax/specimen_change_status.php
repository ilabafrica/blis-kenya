<?php
#
# Changes the status of the specimen
# Called via Ajax from sample collection

include("../includes/db_lib.php");
$sid = $_REQUEST['sid'];
set_specimen_status($sid, 0);

?>
