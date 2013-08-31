<?php
$reasons = $_GET['reasons_for_rejection'];
$sid = $_GET['soecimen'];
		$reasons = stripslashes($_POST['reasons']);
		$query = mysql_query("UPDATE specimen SET status_code_id=$STATUS_REJECTED, comments='$reasons' WHERE specimen_id = $sid");
		if($query){
			echo '<div class="alert alert-info">
									<button class="close" data-dismiss="alert"></button>
									<strong>Info!</strong> You have 198 unread messages.
								</div>';
		}
		else{
			echo '<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<strong>Error!</strong> The daily cronjob has failed.
								</div>';
		}
	
?>