<?php
#
# (c) C4G, Santosh Vempala, Ruban Monu and Amol Shintre
# (c) @iLabAfrica, Roy Rutto, Emaanuel Kitsao, Brian Kiprop
#
$path = "../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

# Start session if not already started
if(session_id() == "")
	session_start();
	
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
LangUtil::setPageId("header");
//LangUtil::setPageId("find_patient");
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
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<?php 
	#load css
	include("styles_new.php");
	?>
	

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
<?php 
$uri = explode(".", basename($_SERVER['REQUEST_URI']));
$page = $uri[0]
?>
<?php
if(strpos($_SERVER['PHP_SELF'], 'login.php') === false)
{
?>
	<!-- BEGIN HEADER -->
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner">
			<div class="container-fluid">
				<!-- BEGIN LOGO -->
				<a class="brand" href="index.html">
				<!--img src="assets/img/logo.png" alt="logo" /-->
				BLIS v<?php echo $VERSION; ?> - Kenya
				</a>
				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
				<img src="assets/img/menu-toggler.png" alt="" />
				</a>          
				<!-- END RESPONSIVE MENU TOGGLER -->				
				<!-- BEGIN TOP NAVIGATION MENU -->					
				<ul class="nav pull-right">
					<!-- BEGIN NOTIFICATION DROPDOWN -->	
					<li class="dropdown" id="header_notification_bar">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-warning-sign"></i>
						<span class="badge">6</span>
						</a>
						<ul class="dropdown-menu extended notification">
							<li>
								<p>You have 14 new notifications</p>
							</li>
							<li>
								<a href="#">
								<span class="label label-success"><i class="icon-plus"></i></span>
								New user registered. 
								<span class="time">Just now</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-important"><i class="icon-bolt"></i></span>
								Server #12 overloaded. 
								<span class="time">15 mins</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-warning"><i class="icon-bell"></i></span>
								Server #2 not respoding.
								<span class="time">22 mins</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-info"><i class="icon-bullhorn"></i></span>
								Application error.
								<span class="time">40 mins</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-important"><i class="icon-bolt"></i></span>
								Database overloaded 68%. 
								<span class="time">2 hrs</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-important"><i class="icon-bolt"></i></span>
								2 user IP blocked.
								<span class="time">5 hrs</span>
								</a>
							</li>
							<li class="external">
								<a href="#">See all notifications <i class="m-icon-swapright"></i></a>
							</li>
						</ul>
					</li>
					<!-- END NOTIFICATION DROPDOWN -->
					<!-- BEGIN TODO DROPDOWN -->
					<li class="dropdown" id="header_task_bar">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-tasks"></i>
						<span class="badge">5</span>
						</a>
						<ul class="dropdown-menu extended tasks">
							<li>
								<p>You have 12 pending tasks</p>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">New release v1.2</span>
								<span class="percent">30%</span>
								</span>
								<span class="progress progress-success ">
								<span style="width: 30%;" class="bar"></span>
								</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">Application deployment</span>
								<span class="percent">65%</span>
								</span>
								<span class="progress progress-danger progress-striped active">
								<span style="width: 65%;" class="bar"></span>
								</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">Mobile app release</span>
								<span class="percent">98%</span>
								</span>
								<span class="progress progress-success">
								<span style="width: 98%;" class="bar"></span>
								</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">Database migration</span>
								<span class="percent">10%</span>
								</span>
								<span class="progress progress-warning progress-striped">
								<span style="width: 10%;" class="bar"></span>
								</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">Web server upgrade</span>
								<span class="percent">58%</span>
								</span>
								<span class="progress progress-info">
								<span style="width: 58%;" class="bar"></span>
								</span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="task">
								<span class="desc">Mobile development</span>
								<span class="percent">85%</span>
								</span>
								<span class="progress progress-success">
								<span style="width: 85%;" class="bar"></span>
								</span>
								</a>
							</li>
							<li class="external">
								<a href="#">See all tasks <i class="m-icon-swapright"></i></a>
							</li>
						</ul>
					</li>
					<!-- END TODO DROPDOWN -->
					<!-- BEGIN USER LOGIN DROPDOWN -->
					 <?php     if(isset($_SESSION['username']))
					{
					?>
					<li class="dropdown user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img alt="" src="assets/img/avatar.png" height=30 width=30/>
						<span class="username"> <?php echo $_SESSION['username']; ?></span>
						<i class="icon-angle-down"></i>
						</a>
						<ul class="dropdown-menu">
							<li><a href='edit_profile'><i class="icon-pencil"></i> <?php echo LangUtil::getPageTerm("EDITPROFILE"); ?></a></li>
							<?php
							if(isset($_SESSION['admin_as_tech']) && $_SESSION['admin_as_tech'] === true)
							{
							?>
							<li><a href="switchto_admin.php"><i class="icon-user"></i> <?php echo LangUtil::getPageTerm("SWITCH_TOMGR"); ?></a></li>
							<?php
							}
							else if(isset($_SESSION['dir_as_tech']) && $_SESSION['dir_as_tech'] === true)
							{
							?>
							<li><a href='switchto_admin.php'><i class="icon-calendar"></i> <?php echo LangUtil::getPageTerm("SWITCH_TODIR"); ?>r</a></li>
							<?php
							}
							else if(User::onlyOneLabConfig($_SESSION['user_id'], $_SESSION['user_level']))
							{
								$lab_config_list = get_lab_configs($_SESSION['user_id']);
							?>
							<li><a href='switchto_tech.php?id=<?php echo $lab_config_list[0]->id; ?>'><i class="icon-tasks"></i> <?php echo LangUtil::getPageTerm("SWITCH_TOTECH"); ?></a></li>
							<?php
							}
							?>
							<li class="divider"></li>
							<li><a rel='facebox' href='user_rating.php'><i class="icon-key"></i> <?php echo LangUtil::getPageTerm("LOGOUT"); ?></a></li>
						</ul>
					</li>
					<?php } ?>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>
				<!-- END TOP NAVIGATION MENU -->	
			</div>
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
	<!-- BEGIN CONTAINER -->
	<div class="page-container row-fluid">
		<!-- BEGIN SIDEBAR -->
		<div class="page-sidebar nav-collapse collapse">
			<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
			<div class="slide hide">
				<i class="icon-angle-left"></i>
			</div>
			<form class="sidebar-search" />
				<div class="input-box">
					<input type="text" class="" placeholder="Search" />
					<input type="button" class="submit" value=" " />
				</div>
			</form>
			<div class="clearfix"></div>
			<!-- END RESPONSIVE QUICK SEARCH FORM -->
			<!-- BEGIN SIDEBAR MENU -->
			<ul>
			<?php
			if(isset($top_menu_options))
			{
				foreach($top_menu_options as $key => $value)
				{
					$page_value = explode(".", $value);
					$page_value1 = explode("s", $value);
					if($page== $page_value[0] || $page==$page_value1[0].'_home'){
						echo "<li class='has-sub active'>";
					}else if ($page_value[0]=="home")echo "<li class=''>";
					else echo "<li class='has-sub'>";
					echo "<a href='".$value."' ";
					if(
						(strpos($_SERVER['PHP_SELF'], $value) !== false)
						&& !(strpos($_SERVER['PHP_SELF'], "_home.php") !== false && $value == "home.php")
					)
					{
						# Highlight current page tab
						echo " class='' ";
					}
					if(strpos($key, LangUtil::$pageTerms['MENU_BACKUP']) !== false)
					{
						echo " target='_blank' ";
					}
					if(strpos($_SERVER['PHP_SELF'], "_home.php") !== false && strpos($value, "lab_configs.php") !== false)
					{
						echo " class='' ";
					}
					echo "><i class='icon-table'></i>".$key;
					if($page== $page_value[0] || $page==$page_value1[0].'_home'){
						echo "<span class='selected'></span></a>";
					}else echo "<span class='arrow'></span></a>";
					
					switch($page_value[0]){
					case 'find_patient':
						echo "<ul class='sub'>".
							"<li><a href='javascript:right_load(&#34;lab_requests&#34;);' title='Lab Test Requests' 
									class='' id='specimen_results_menu'>
									<i class='icon-table'></i>&nbsp;&nbsp;"
									.LangUtil::$allTerms['MENU_LAB_REQUESTS'].
								"</a>
							</li>
							<li><a href='javascript:right_load(&#34;patient_lookup&#34;);' title='Patient Lookup' 
									class='' id='patient_lookup_menu'>
									<i class='icon-table'></i>&nbsp;&nbsp;"
									.LangUtil::$allTerms['MENU_PATIENT_LOOKUP'].
								"</a>
							</li>
							<li><a href='javascript:right_load(&#34;new_patient&#34;);'  title='New Patient'
									class='' id='new_patient_menu'>
									<i class='icon-table'></i>&nbsp;&nbsp;"
									.LangUtil::$allTerms['NEW_PATIENT'].
							"</a></li>".
						"</ul>";
					break;
					case 'results_entry':
						echo "<ul class='sub'>".
									"<li>
										<a href='javascript:right_load(&#34;pending_tests&#34;);' title='Lab Test Requests'
										class='' id='specimen_results_menu'>
										<i class='icon-table'></i>&nbsp;&nbsp;"
										.LangUtil::$allTerms['MENU_PENDING_TESTS'].
										"</a>
									</li>
									<li>
										<a href='javascript:right_load(&#34;pending_results&#34;);' title='Pending Results'
										class='' id='patient_lookup_menu'>
										<i class='icon-table'></i>&nbsp;&nbsp;"
										.LangUtil::$allTerms['MENU_PENDING_RESULTS'].
										"</a>
									</li>
									<li>
										<a href='javascript:right_load(&#34;verify_results_new&#34;);'  title='Verify Results'
										class='' id='new_patient_menu'>
										<i class='icon-table'></i>&nbsp;&nbsp;"
										.LangUtil::$allTerms['MENU_VERIFYRESULTS'].
										"</a>
									</li>
									<li>
										<a href='javascript:right_load(&#34;verify_results_new&#34;);'  title='Quality Controls'
										class='' id='new_patient_menu'>
										<i class='icon-table'></i>&nbsp;&nbsp;"
										.LangUtil::$allTerms['MENU_QUALITY_CONTROLS'].
										"</a>
									</li>".
								"</ul>";
					break;
					}
					echo "</li>";
					}
					}
					?>	
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div id="portlet-config" class="modal hide">
				<div class="modal-header">
					<button data-dismiss="modal" class="close" type="button"></button>
					<h3>Widget Settings</h3>
				</div>
				<div class="modal-body">
					<p>Here will be a configuration form</p>
				</div>
			</div>
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">    	
<?php
}
?>