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
$quote="&#34;";

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
						echo "<ul class='sub'>
								<li><a href='javascript:right_load(".$quote."lab_requests".$quote.");' title='Lab Requests' 
									class='' id='specimen_results_menu'>
									<i class='icon-table'></i>&nbsp;&nbsp;"
									.LangUtil::$allTerms['MENU_LAB_REQUESTS'].
								"</a>
							</li>
							<li><a href='javascript:right_load(".$quote."patient_lookup".$quote.");' title='Sample collection' 
									class='' id='patient_lookup_menu'>
									<i class='icon-table'></i>&nbsp;&nbsp;"
									.LangUtil::$allTerms['MENU_PATIENT_LOOKUP'].
								"</a>
							</li>
						</ul>";
					break;
					case 'results_entry':
						echo "<ul class='sub'>".
									"<li>
										<a href='javascript:right_load(".$quote."pending_tests".$quote.");' title='Lab Test Requests'
										class='' id='specimen_results_menu'>
										<i class='icon-table'></i>&nbsp;&nbsp;"
										.LangUtil::$allTerms['MENU_PENDING_TESTS'].
										"</a>
									</li>
									<li>
										<a href='javascript:right_load(".$quote."pending_results".$quote.");' title='Pending Results'
										class='' id='patient_lookup_menu'>
										<i class='icon-table'></i>&nbsp;&nbsp;"
										.LangUtil::$allTerms['MENU_PENDING_RESULTS'].
										"</a>
									</li>
									<li>
										<a href='javascript:right_load(".$quote."verify_results_new".$quote.");'  title='Verify Results'
										class='' id='new_patient_menu'>
										<i class='icon-table'></i>&nbsp;&nbsp;"
										.LangUtil::$allTerms['MENU_VERIFYRESULTS'].
										"</a>
									</li>
									<li>
										<a href='javascript:right_load(".$quote."verify_results_new".$quote.");'  title='Quality Controls'
										class='' id='new_patient_menu'>
										<i class='icon-table'></i>&nbsp;&nbsp;"
										.LangUtil::$allTerms['MENU_QUALITY_CONTROLS'].
										"</a>
									</li>".
								"</ul>";
					break;
					case "catalog":
						echo 
						"<ul class='sub'>
							<li>
								<a href='javascript:load_right_pane(".$quote."specimen_types_div".$quote.");' class='menu_option' id='specimen_types_div_menu'>
								".LangUtil::$generalTerms['SPECIMEN_TYPES']."
								</a>
							</li>
							<li>
								<a href='javascript:load_right_pane(".$quote."test_types_div".$quote.");' class='menu_option' id='test_types_div_menu'>
								".LangUtil::$generalTerms['TEST_TYPES']."
								</a>
							</li>
							<li>
								<a href='javascript:load_right_pane(".$quote."test_categories_div".$quote.");' class='menu_option' id='test_categories_div_menu'>
								".LangUtil::$generalTerms['TEST_CATEGORIES']."
								</a>
							</li>
						</ul>";
						
					break;
					case "reports":
						echo "<ul class='sub'>";
						$site_list = get_site_list($_SESSION['user_id']);
						if ( !is_country_dir( get_user_by_id($_SESSION['user_id'] ) ) ) {
										echo '<li>'.LangUtil::$allTerms['MENU_DAILY'].'</li>'; 
									echo "
									<ul>
										<!--
										<li class='menu_option' id='patient_report_menu'>
											<a href='javascript:show_patient_report_form();'><". LangUtil::$allTerms['MENU_PATIENT']."</a>
										</li>
										-->
										<li class='menu_option' id='test_history_menu'>
											<!--<a href='javascript:show_test_history_form();'>".LangUtil::$allTerms['MENU_PHISTORY']."</a>-->
											<a href='javascript:show_test_history_form();'>".LangUtil::$allTerms['MENU_PATIENT']."</a>
										</li>";
										echo "
										<li class='menu_option' id='session_report_menu' <";
										
										if($SHOW_SPECIMEN_REPORT === false)
											echo " style='display:none;' ";
									
										echo "
										>
											<a href='javascript:show_session_report_form();'>".LangUtil::$allTerms['MENU_SPECIMEN']."</a>
										</li>
										<li class='menu_option' id='print_menu'";
										if($SHOW_TESTRECORD_REPORT === false)
											echo " style='display:none;' ";
										echo "
										>
											<a href='javascript:show_print_form();'>".LangUtil::$allTerms['MENU_TESTRECORDS']."</a>
										</li>
										
										<li class='menu_option' id='daily_report_menu'>
											<a href='javascript:show_daily_report_form();'>".LangUtil::$allTerms['MENU_DAILYLOGS']."</a>
										</li>
										<li class='menu_option' id='print_menu'";
										if($SHOW_PENDINGTEST_REPORT === false)
											echo " style='display:none;' ";
										echo "
										>
											<a href='javascript:show_pending_tests_form();'>".LangUtil::$allTerms['MENU_PENDINGTESTS']."</a>
										</li>
										<!--
										# Space for menu entries corresponding to a new daily report
										# PLUG_DAILY_REPORT_ENTRY
										-->
										
									</ul>";
									 } else { echo
										".Report Settings.
										<ul>
											<li class='menu_option' id='location_settings' >
											<a href='lab_pin.php'>"."Location Settings"."</a>
											</li>
										</ul>";
									} echo 
									"<li>".LangUtil::$allTerms['MENU_AGGREPORTS']."</li>
									<ul>";
								
											$site_list = get_site_list($_SESSION['user_id']);
											if( is_country_dir( get_user_by_id($_SESSION['user_id'] ) ) ) { 
												echo "
												<li class='menu_option' id='country_aggregate_menu'>
													<a href='javascript:show_selection(".$quote."prevalance_aggregate".$quote.");'>".LangUtil::$allTerms['MENU_INFECTIONSUMMARY']."</a>
												</li>
												<li class='menu_option' id='tat_menu'>
													<a href='javascript:show_selection(".$quote."tat_aggregate".$quote.");'>".LangUtil::$allTerms['MENU_TAT']."</a>
												</li>
												<!--<li class='menu_option' id='disease_report_menu'>
													<a href='javascript:show_selection(".$quote."infection_aggregate".$quote.");'>".LangUtil::$allTerms['MENU_INFECTIONREPORT']."</a>
												</li>-->";
											} else {
												echo "
												<li class='menu_option' id='summary_menu'>
													<a href='javascript:show_selection(".$quote."summar".$quote.");'>".LangUtil::$allTerms['MENU_INFECTIONSUMMARY']."</a>
												</li>
												<li class='menu_option' id='specimen_count_menu'>
													<a href='javascript:show_selection(".$quote."specimen_count".$quote.");'>".LangUtil::$allTerms['MENU_COUNTS']."</a>
												</li>
												<li class='menu_option' id='tat_menu'>
													<a href='javascript:show_selection(".$quote."tat".$quote.");'>".LangUtil::$allTerms['MENU_TAT']."</a>
												</li>
												<li class='menu_option' id='disease_report_menu'>
													<a href='javascript:show_selection(".$quote."disease_report".$quote.");'>".LangUtil::$allTerms['MENU_INFECTIONREPORT']."</a>
												</li>";
						                     	if(is_admin(get_user_by_id($_SESSION['user_id'])))
						                        { 
						                        echo "
						                            <li class='menu_option' id='user_stats_menu'>
														<a href='javascript:show_selection(".$quote."user_stats".$quote.");'>User Statistics</a>
													</li>";
						                        }
						                        echo "
						                        <li class='menu_option' id='stock_report_menu'>
													<a href='javascript:show_selection(".$quote."stock_report".$quote.");'>Previous Inventory Data</a>
												</li>";                          
											} 
						
											echo "</ul>";
											echo "</ul>";
					break;
					
					}
					#LAB CONFIG LEFT MENU
					if($page_value1[0]=='lab_config'){
						echo "<ul class='sub'>";
						$user = get_user_by_id($_SESSION['user_id']);
						if(is_super_admin($user) || is_country_dir($user)) {
							echo
							"<li>
								<a id='option9' class='' href='javascript:right_load(9, ".$quote."misc_".$quote.");>
									<i class='icon-table'></i>&nbsp;&nbsp;".
									LangUtil::$allTerms['MENU_GENERAL'].
								"</a>
							</li>
							<li>
								<a id='option7' class='' href='javascript:right_load(7, ".$quote."change_admin_div".$quote.");'>
									<i class='icon-table'></i>&nbsp;&nbsp;".
									LangUtil::$allTerms['MENU_MGR'].
								"</a>
							</li>
							<li>
								<a id='option6' class='' href='javascript:right_load(6, ".$quote."del_config_div".$quote.");'>
									<i class='icon-table'></i>&nbsp;&nbsp;".
									LangUtil::$allTerms['MENU_DEL']."</a>
							</li>";										
						}
						
						echo 
						"<li>
							<a id='test' class='menu_option' href='javascript:test_setup();'>".LangUtil::$allTerms['Tests']." </a>
						</li>
						<div id='test_setup' name='test_setup' style='display:none;'>
							-<a id='option2' class='menu_option' href='javascript:right_load(2, ".$quote."st_types_div".$quote.");'>".LangUtil::$allTerms['MENU_ST_TYPES']."</a>
							</li><br><br>
							-<a id='option5' class='menu_option' href='javascript:right_load(5, ".$quote."target_tat_div".$quote.");'>".LangUtil::$allTerms['MENU_TAT']."</a>
							</li><br><br>
							-<a href='remarks_edit.php?id=".$_REQUEST['id']."'>".'Results Interpretation'."</a>
							<br><br>
						</div>
						<li>                         
							<a id='option21' class='menu_option' href='javascript:right_load(21, ".$quote."search_div".$quote.");'>Search</a>
						</li>
						<li>
							<a id='report' class='menu_option' href='javascript:report_setup();'>".LangUtil::$allTerms['Reports']." </a>
						</li>
						<div id='report_setup' name='report_setup' style='display:none;'>
							-<a id='option8' class='menu_option' href='javascript:right_load(8, ".$quote."agg_report_div".$quote.");'>".LangUtil::$allTerms['MENU_INFECTION']."</a>
							<br><br>
                            -<a id='option36' class='menu_option' href='javascript:right_load(36, ".$quote."grouped_count_div".$quote.");'>".'Test/Specimen Grouped Reports'."</a>
							<br><br>
							-<a id='option11' class='menu_option' href='javascript:right_load(11, ".$quote."report_config_div".$quote.");'>".LangUtil::$allTerms['MENU_REPORTCONFIG']."</a>
							<br><br>
							-<a id='option12' class='menu_option' href='javascript:right_load(12, ".$quote."worksheet_config_div".$quote.");'>".LangUtil::$allTerms['MENU_WORKSHEETCONFIG']."</a>
							<br><br>
						</div>
						<li>
							<a id='option15' class='menu_option' href='javascript:right_load(15, ".$quote."inventory_div".$quote.");'>".LangUtil::$allTerms['Inventory']."</a>
						</li>
						<li>
							<a id='option28' class='menu_option' href='javascript:right_load(28, ".$quote."barcode_div".$quote.");'>".'Barcode Settings'."</a>
							</li>
						<li>
							<a id='option22' class='menu_option' href='javascript:right_load(22, ".$quote."billing_div".$quote.");'>".'Billing'."</a>
						</li>
						<li>
							<a id='option3' class='menu_option' href='javascript:right_load(3, ".$quote."users_div".$quote.");'>".LangUtil::$allTerms['MENU_USERS']."</a>
						</li>
						<li>
							<a id='option4' class='menu_option' href='javascript:right_load(4, ".$quote."fields_div".$quote.");'>".LangUtil::$allTerms['MENU_CUSTOM']."</a>
						</li>
						<li>			
							<a id='option19' class='menu_option' href='javascript:language_div_load();'>".LangUtil::$allTerms['MODIFYLANG']."</a>
						</li>
						<li>
							<a id='option14' class='menu_option' href='javascript:export_html();'>Setup Network</a>
						</li>";
						if($SERVER != $ON_ARC) {
						echo"
						<li><a id='option13' class='menu_option' href='javascript:right_load(13, ".$quote."backup_revert_div".$quote.");'>".LangUtil::$allTerms['MENU_BACKUP_REVERT']."</a></li>";
						if(is_super_admin($user) || is_country_dir($user)) { 
						echo"
						<li>
							<a id='option18' class='menu_option' href='javascript:right_load(18, ".$quote."update_database_div".$quote.");'>".'Update Data'."</a>
						</li>
						<li>
							<a id='option34' class='menu_option' href='javascript:right_load(34, ".$quote."import_config_div".$quote.");'>Import Configuration</a><br><br>
						</li>";
						}
						}
						echo"
						<li>
							<a href='export_config?id=".$_REQUEST['id']."' target='_blank'>".LangUtil::$allTerms['MENU_EXPORTCONFIG']."</a>
						</li>
                        <div id='old_update_div' style='display:none;'>
							<li>
								<a id='option39' class='menu_option' href='javascript:right_load(39, ".$quote."blis_update_div".$quote.");'>Update to New Version</a>
							</li>
						</div>";
							
										
					echo "</ul>";

					}#END LAB CONFIG LEFT MENU
					echo "</li>";
					}#END FOR EACH LOOP
				}#END TOP MENU OPTIONS
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