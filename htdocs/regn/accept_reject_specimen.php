<?php
#
# Adds a new test type to catalog in DB
#
include("redirect.php");
include("includes/db_lib.php");

putUILog('accept_reject_specimen', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');
static $STATUS_REJECTED = 6;
$date = date('Y-m-d H:i:s');
$reasons_for_rejection = $_REQUEST['reasons'];
$talked_to = $_REQUEST['referred_to_name'];
$specimen_id=$_REQUEST['specimen'];
$specimen = get_specimen_by_id($specimen_id);
$query = mysql_query("UPDATE specimen SET aux_id=$specimen->userId, status_code_id=$STATUS_REJECTED, comments='$reasons_for_rejection', referred_to_name='$talked_to', ts_collected='$date' WHERE specimen_id = $specimen_id") or die(mysql_error());
		if($query){
			header('Location: results_entry.php');
			
		}
		else{
			echo '<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<strong>Error!</strong> The daily cronjob has failed.
								</div>';
		}
?>