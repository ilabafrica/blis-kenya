<?php
include("../includes/db_lib.php");

//Logging User logout
$auditTrail = new AuditTrail();
$auditTrail->log_access($_SESSION['user_id'], $auditTrail::LOGOUT, $_SESSION['username']);

session_unset();
session_destroy();

// Remove and regenerate new session ID
	session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);
	
if(isset($_REQUEST['timeout']))
	header("Location:login.php?to");
else
	header("Location:login.php");
?>