<?php
include("redirect.php");
include("includes/stats_lib.php");

$file = "../../BlisSetup.html";
$content =<<<content
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="Refresh"
CONTENT="1; URL=http://{$_SERVER['SERVER_ADDR']}:4001/login.php">
</head>
</html>
content;
file_put_contents($file, $content);

session_start();
# If already logged in, redirect to home page
if(isset($_SESSION['user_id']))
{
	header("Location: home.php");
}
include("includes/header.php");
LangUtil::setPageId("login");

$page_elems = new PageElems();
$login_tip = LangUtil::getPageTerm("TIPS_NEWPWD");
$login_tip="If you have forgotten your password then please send an email to 'c4gbackup@gmail.com' with the subject 'Password'.<br> New password will be sent to you.";
$page_elems->getSideTip(LangUtil::getGeneralTerm("TIPS"), $login_tip);
?>
<script type='text/javascript'>
function load()
{	
	$('#username_error').hide();
	$('#password_error').hide();
}

function check_input_boxes()
{
	if($('#username').val() == "")
	{
		$('#username_error').show();
		return;
	}
	else
	{
		$('#username_error').hide();
	}
	if($('#password').val() == "")
	{
		$('#password_error').show();
		return;
	}
	else
	{
		$('#password_error').hide();
	}
	$('#form_login').submit();

}

function unload()
{
	document.getElementById("username_error").value == "";
	document.getElementById("password_error").value == "";
}

$(document).ready(function(){
	load();
	$('#username').focus();
});

function capLock(e)
{
	kc = e.keyCode?e.keyCode:e.which;
	if(kc == 8)
	{
		//delete key pressed, maintain same state
		return;
	}		
	sk = e.shiftKey?e.shiftKey:((kc == 16)?true:false);
	if(((kc >= 65 && kc <= 90) && !sk)||((kc >= 97 && kc <= 122) && sk))
		$('#caps_lock_msg_div').show();
	else
		$('#caps_lock_msg_div').hide();
}
</script>
				<form name="form_login" id='form_login' action="validate.php" method="post" class="form-signin">
				 <h2 class="form-signin-heading"><img src="logos/bdh.png"><br> Bungoma District Hospital - LIS</h2>
				<?php
					
					if(isset($_REQUEST['to']))
					{
						# Previous session timed out
						echo "<tr valign='top'>";
						echo "<td></td>";
						echo "<td>";
						echo "<span id='server_msg' class='error_string'>";
						echo LangUtil::getPageTerm("MSG_TIMED_OUT");
						echo "</span><br>";
						echo "</td>";
						echo "</tr>";
					}
					else if(isset($_REQUEST['err']))
					{
						# Incorrect username/password
						echo "<tr valign='top'>";
						echo "<td></td>";
						echo "<td>";
						echo "<span id='server_msg' class='error_string'>";
						echo LangUtil::getPageTerm("MSG_ERR_PWD");
						echo "</span><br>";
						echo "</td>";
						echo "</tr>";
					}
					else if(isset($_REQUEST['prompt']))
					{
						# User not logged in
						echo "<tr valign='top'>";
						echo "<td></td>";
						echo "<td>";
						//echo "<span id='server_msg' class='error_string'>";
						//echo LangUtil::getPageTerm("MSG_PLSLOGIN");
						//echo "</span><br>";
						echo "</td>";
						echo "</tr>";
					}
				?>
					
				<input type="text" name="username" id = "username" value="" size="20" class="form-control" placeholder="<?php echo LangUtil::getGeneralTerm("USERNAME"); ?>"/>
				<label class="error" for="username" id="username_error"><small><font color="red"><?php echo LangUtil::getGeneralTerm("MSG_REQDFIELD"); ?></font></small></label> 
		
	
			
				<input type="password" name="password" id = "password" value="" size="20" class='form-control' onkeypress="javascript:capLock(event);" onkeydown="javascript:capLock(event);" placeholder="<?php echo LangUtil::getGeneralTerm("PWD"); ?>" />
				<label class="error" for="password" id="password_error"><small><font color="red"><?php echo LangUtil::getGeneralTerm("MSG_REQDFIELD"); ?></font></small></label>
				<br>
				<div id="caps_lock_msg_div" style="display:none"><font color='red'><small><?php echo LangUtil::getPageTerm("MSG_CAPSLOCK"); ?></small></font></div>
	
			

				<button type="button" class="btn btn-large btn-primary btn-block" id=""  onclick="check_input_boxes()"><?php echo LangUtil::$generalTerms["CMD_LOGIN"]; ?></button>
			
		
				<!--<a href='password_reset.php'>
					<small><?php echo LangUtil::getPageTerm("MSG_NEWPWD"); ?></small>
				</a>-->
				
				</form>
	
<?php $script_elems->bindEnterToClick("#password", "#login_button"); ?>
<?php
include("includes/footer.php");
?>