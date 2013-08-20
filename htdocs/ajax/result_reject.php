<?php

#
# Rejects a whole Sample
# Called via ajax from results_entry.php
#


include("../includes/db_lib.php");

$user_id = $_SESSION['user_id'];
$specimen_id = $_REQUEST[specimen_id];
$rejectedreason = $_REQUEST["reject-reason-comment"];

//NC3065
$unix_ts = mktime(0,0,0,$mm_to,$dd_to,$yyyy_to);
$ts =date("Y-m-d H:i:s", $unix_ts);
//-NC3065
$result = update_specimen_status_rejected($specimen_id , $rejectedreason);

header('Location: ../results_entry.php');

?>