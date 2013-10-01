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
$TRACK_LOADTIME = false;
$TRACK_LOADTIMEJS = false;
if($TRACK_LOADTIME)
{
	$starttime = microtime();
	$startarray = explode(" ", $starttime);
	$starttime = $startarray[1] + $startarray[0];
}
# Include required libraries
require_once("includes/db_lib.php");
require_once("includes/page_elems.php");
require_once("includes/script_elems.php");
LangUtil::setPageId("login");
require_once("includes/perms_check.php");

$script_elems = new ScriptElems();
$page_elems = new PageElems();
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
  <meta charset="utf-8" />
  <title>BLIS <?php echo $VERSION; ?> - Kenya</title>
  <meta content="" name="description" />
  <meta content="" name="author" />
  <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/metro.css" rel="stylesheet" />
  <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/style_responsive.css" rel="stylesheet" />
  <link href="assets/css/style_default.css" rel="stylesheet" id="style_color" />
  <link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
  <link rel="shortcut icon" href="favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
  <!-- BEGIN LOGO -->
  <h1></h1>
  <!-- END LOGO -->
  <!-- BEGIN LOGIN -->
  <div class="content">
  <center>
   <img src="logos/logo_127.png" alt="" width="90" height="90" /> 
   <h3> Bungoma District Hospital Laboratory </h3>
   </center>
    <!-- BEGIN LOGIN FORM -->
    <form class="form-vertical login-form" method="POST" action="validate.php" />
      <h3 class="form-title">Login</h3>
      <?php
					
					if(isset($_REQUEST['to']))
					{
						# Previous session timed out
						echo "<div class='alert alert-error'>";
						echo LangUtil::getPageTerm("MSG_TIMED_OUT");
						echo "</div>";
					}
					else if(isset($_REQUEST['err']))
					{
						# Incorrect username/password
						echo "<div class='alert alert-error'>";
						echo LangUtil::getPageTerm("MSG_ERR_PWD");
						echo "</div>";
					
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
      <div class="control-group">
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-user"></i>
            <input class="m-wrap" type="text" placeholder="Username" name="username"/>
          </div>
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-lock"></i>
            <input class="m-wrap" type="password" style="" placeholder="Password" name="password"/>
          </div>
        </div>
      </div>
      <div class="form-actions">
        <!--label class="checkbox">
        <input type="checkbox" /> Remember me
        </label-->
        <button type="submit" id="login-btn" class="btn green pull-right">
        Login <i class="m-icon-swapright m-icon-white"></i>
        </button>           
      </div>
      <div class="forget-password" align="center">
      <!-- 
        <h4>Forgot your password ?</h4>
        <p>
          no worries, click <a href="javascript:;" class="" id="forget-password">here</a>
          to reset your password.
        </p>
      </div>
      -->
      
    	<?php
		if($_SESSION['locale'] == "en")
		{
			echo "<a href='userguide/BLIS_User_Guide.pdf' target='_blank' > <i class='icon-info-sign'></i> User Guide </a>";
		}
		else if($_SESSION['locale'] == "fr")
		{
			echo "<a href='userguide/BLIS_User_Guide.pdf' target='_blank' > <i class='icon-info-sign'></i> Guide de l'utilisateur </a>";
		}
		else
		{
			echo "<a href='userguide/BLIS_User_Guide.pdf' target='_blank'> <i class='icon-info-sign'></i> User Guide </a>";
		}
		?>
		&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
		<a rel='facebox' href='feedback/comments.php?src=<?php echo $_SERVER['PHP_SELF']; ?>'><i class='icon-comments-alt'></i> <?php echo "Comments" ?>?</a>
		<div class="copyright">
		C4G BLIS v<?php echo $VERSION; ?> - <?php echo LangUtil::$allTerms["FOOTER_MSG"]; ?>
		</div>
		<?php
		/*Change Theme: <a href=javascript:changeTheme('Blue');>Blue</a> | <a href=javascript:changeTheme('Grey');>Grey*/
		if($TRACK_LOADTIME)
		{
			$endtime = microtime();
			$endarray = explode(" ", $endtime);
			$endtime = $endarray[1] + $endarray[0];
			$totaltime = $endtime - $starttime; 
			$totaltime = round($totaltime,5);
			$page_name = $_SERVER['PHP_SELF'];
			$page_name_parts = explode("/", $page_name);
			$file_name = $page_name_parts[count($page_name_parts)-1].".dat";
			$file_handle = fopen("../feedback/loadtimes/".$file_name, "a");
			fwrite($file_handle, $totaltime."\n");
			fclose($file_handle);
			echo "<br>$file_name This page loaded in $totaltime seconds.";
		}
		if($TRACK_LOADTIMEJS)
		{
			echo "<script type='text/javascript'>alert(new Date().getTime() - t.getTime());</script>";
		}
		?>
  	</div>
    </form>
    <!-- END LOGIN FORM -->        
    <!-- BEGIN FORGOT PASSWORD FORM -->
    <form class="form-vertical forget-form" action="index.html" />
      <h3 class="">Forget Password ?</h3>
      <p>Enter your e-mail address below to reset your password.</p>
      <div class="control-group">
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-envelope"></i>
            <input class="m-wrap" type="text" placeholder="Email" />
          </div>
        </div>
      </div>
      <div class="form-actions">
        <a href="javascript:;" id="back-btn" class="btn">
        <i class="m-icon-swapleft"></i>  Back
        </a>
        <a href="javascript:;" id="forget-btn" class="btn green pull-right">
        Submit <i class="m-icon-swapright m-icon-white"></i>
        </a>            
      </div>
    </form>
    <!-- END FORGOT PASSWORD FORM -->
  </div>
  <!-- END LOGIN -->
  <!-- BEGIN COPYRIGHT -->
  <!--div class="copyright">
    	<?php
		if($_SESSION['locale'] == "en")
		{
			echo "<a href='userguide/BLIS_User_Guide.pdf' target='_blank' >User Guide |</a>";
		}
		else if($_SESSION['locale'] == "fr")
		{
			echo "<a href='userguide/BLIS_User_Guide.pdf' target='_blank' >Guide de l'utilisateur |</a>";
		}
		else
		{
			echo "<a href='userguide/BLIS_User_Guide.pdf' target='_blank'>User Guide |</a>";
		}
		?>
		
		<a rel='facebox' href='feedback/comments.php?src=<?php echo $_SERVER['PHP_SELF']; ?>'><?php echo "Comments" ?>?</a> |
		C4G BLIS v<?php echo $VERSION; ?> - <?php echo LangUtil::$allTerms["FOOTER_MSG"]; ?>
		<?php
		if($_SESSION['locale'] !== "en")
		{
			?>
			 | <a href='lang_switch?to=en'><?php echo "English"; ?></a>
			<?php
		}
		else
		{
			echo " | English";
		}
		if($_SESSION['locale'] !== "fr")
		{
			?>
			 | <a href='lang_switch?to=fr'><?php echo "Francais"; ?></a>
			<?php
		}
		else
		{
			echo " | Francais";
		}
		if($_SESSION['locale'] !== "default")
		{
			?>
			 | <a href='lang_switch?to=default'><?php echo "Default"; ?></a>
			<?php
		}
		else
		{
			echo " | Default";
		}
		/*Change Theme: <a href=javascript:changeTheme('Blue');>Blue</a> | <a href=javascript:changeTheme('Grey');>Grey*/
		if($TRACK_LOADTIME)
		{
			$endtime = microtime();
			$endarray = explode(" ", $endtime);
			$endtime = $endarray[1] + $endarray[0];
			$totaltime = $endtime - $starttime; 
			$totaltime = round($totaltime,5);
			$page_name = $_SERVER['PHP_SELF'];
			$page_name_parts = explode("/", $page_name);
			$file_name = $page_name_parts[count($page_name_parts)-1].".dat";
			$file_handle = fopen("../feedback/loadtimes/".$file_name, "a");
			fwrite($file_handle, $totaltime."\n");
			fclose($file_handle);
			echo "<br>$file_name This page loaded in $totaltime seconds.";
		}
		if($TRACK_LOADTIMEJS)
		{
			echo "<script type='text/javascript'>alert(new Date().getTime() - t.getTime());</script>";
		}
		?>
  </div-->
  <!-- END COPYRIGHT -->
  <!-- BEGIN JAVASCRIPTS -->
  <script src="assets/js/jquery-1.8.3.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>  
  <script src="assets/uniform/jquery.uniform.min.js"></script> 
  <script src="assets/js/jquery.blockui.js"></script>
  <script src="assets/js/app.js"></script>
  <script>
    jQuery(document).ready(function() {     
      App.initLogin();
    });
  </script>
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
  <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>