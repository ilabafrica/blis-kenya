<?php
#
# Results entry page
# Technicians can search for a specimen to enter results for OR import results from a file and validate
#
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("results_entry");
$lab_config = LabConfig::getById($_SESSION['lab_config_id']);
$test_categories = TestCategory::geAllTestCategories($lab_config_id);
?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->		
						<h3>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-beaker"></i>
								<a href="index.html">Tests</a>
							</li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
<div class='batch_results_subdiv_help' id='batch_results_subdiv_help' style='display:none;'>
	<?php
		//$tips_string = LangUtil::$pageTerms['TIPS_INFECTIONSUMMARY'];
		$tips_string = "If you cannot see any information other than Test Name, Results and the Skip Option, please tell your administrator to configure it from Worksheet Configuration";
		$page_elems->getSideTip(LangUtil::$generalTerms['TIPS'], $tips_string);
	?>
</div>

<!-- BEGIN ROW-FLUID-->   
<div class="row-fluid">
<div class="span12 sortable">

<!-- BEGIN TESTS PORTLET-->	
<div id="tests" class='results_subdiv' style='display:none;'>
	<div class="portlet box blue">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i><?php echo "Test Queue - ";?><span class="section-name">All Sections</span></h4>
			<div class="tools">
				<a href="javascript:fetch_tests(<?php echo Specimen::$STATUS_STARTED?>);" class="reload"></a>
				<a href="javascript:;" class="collapse"></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" data-height=900px" data-always-visible="0">
				<div id='fetched_specimens_entry'>
				<!--TESTS LOADED IN THIS DIV-->
				</div>
				<div id="fetched_specimen">
				<?php
					if(isset($_REQUEST['ajax_response']))
						echo $_REQUEST['ajax_response'];
				?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END TESTS PORTLET-->



<div id="worksheet_results" class='results_subdiv' style='display:none;'>
	<form name="fetch_worksheet" id="fetch_worksheet">
		<b>Worksheet Results</b>
		<br>
		<br>
		Worksheet# <input type="text" name="worksheet_num" id="worksheet_num" class='uniform_width' />
		<input type="button" onclick="fetch_worksheets();" value="Fetch"/>
	</form>
	<div id="worksheet">
	</div>
</div>
		
<div id="specimen_results" class='results_subdiv' style='display:none;'>
	<form name="fetch_specimen_form" id="fetch_specimen_form">
		<div class="panel-heading"><span class='page_title'><?php echo LangUtil::$pageTerms['MENU_SINGLESPECIMEN']; ?></span></div>	 
		<select name='resultfetch_attrib' id='resultfetch_attrib'>
			<?php
			$hide_patient_name = true;
			//if($lab_config->hidePatientName == 1)
			if($_SESSION['user_level'] == $LIS_TECH_SHOWPNAME)
			{
				$hide_patient_name = false;
			}
			$page_elems->getPatientSearchAttribSelect($hide_patient_name);
			if($_SESSION['s_addl'] != 0)
			{
			?>
				<option value='5'><?php echo LangUtil::$generalTerms['SPECIMEN_ID']; ?></option>
			<?php
			}
			?>
		</select>
		&nbsp;&nbsp;
		<input type="text" name="specimen_id" id="specimen_id" class='uniform_width' />
		<input type="button" id='fetch_specimen_button' onclick="fetch_specimen();" value="<?php echo LangUtil::$generalTerms['CMD_SEARCH']; ?>" />
		&nbsp;&nbsp;
		<span id='fetch_progress_bar' style='display:none;'>
			<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SEARCHING']); ?>
		</span>	
	</form>
	<br>
	<div id='fetched_patient_entry'>
	</div>
	<div id="fetched_specimen">
	<?php
		if(isset($_REQUEST['ajax_response']))
			echo $_REQUEST['ajax_response'];
	?>
	</div>
</div>

<div id="import_results" class='results_subdiv' style='display:none;'>
	<b>Import Results</b>
	<br>
	<br>
	<form name='form_import' id='form_import' action='' method='POST' enctype='multipart/form-data'>
		<table>
			<tr>
				<td>Machine Type</td>
				<td><input type='text' name='mc_type'></td>
			</tr>
			<tr>
				<td>File</td>
				<td><input type='file' name='file_path'></td>
			</tr>
			<tr>
				<td></td>
				<td><br><input type='button' name='submit_import' value='Import Results'/></td>
			</tr>
		</table>
	</form>
</div>
		
<div id='batch_results' class='results_subdiv' style='display:none;'>
	<div class="panel-heading"><span class='page_title'><?php echo LangUtil::$pageTerms['MENU_BATCHRESULTS']; ?></span></div>	 
	<?php echo LangUtil::$generalTerms['TEST_TYPE']; ?>
	&nbsp;&nbsp;&nbsp;
	<select id='batch_test_type' class='uniform_width'>
		<option value=""><?php echo LangUtil::$generalTerms['SELECT_ONE']; ?>..</option>
		<?php $page_elems->getTestTypesSelect($_SESSION['lab_config_id']); ?>
	</select>
	&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;
	<br><br>
	<table>
		<tr valign='top'>
			<td><?php echo LangUtil::$generalTerms['FROM_DATE']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td>
			<?php
			$today = date("Y-m-d");
			$today_array = explode("-", $today);
			$monthago_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($today)) . " -270 days"));
			$monthago_array = explode("-", $monthago_date);
			$name_list = array("yyyy_from", "mm_from", "dd_from");
			$id_list = array("yyyy_from", "mm_from", "dd_from");
			$value_list = $monthago_array;
			//$page_elems->getDatePicker($name_list, $id_list, $value_list);
			?>
			</td>
		</tr>
		<tr valign='top'>
			<td><?php echo LangUtil::$generalTerms['TO_DATE']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
			<td>
			<?php
				$name_list = array("yyyy_to", "mm_to", "dd_to");
				$id_list = array("yyyy_to", "mm_to", "dd_to");
				$value_list = $today_array;
				//$page_elems->getDatePicker($name_list, $id_list, $value_list);
			?>
			</td>
		</tr>
		<tr valign='top'>
			<td>&nbsp;&nbsp;&nbsp;</td>
			<td>
				&nbsp;&nbsp;&nbsp;
				<input type='button' onclick='javascript:get_batch_form();' value='<?php echo LangUtil::$generalTerms['CMD_SEARCH']; ?>'></input>
			</td>
		</tr>
	</table>
	<span id='batch_progress_form' style='display:none'>
		<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_FETCHING']); ?>
	</span>
	<span id='batch_result_error' class='error_string' style='display:none;'>
		<?php echo LangUtil::$generalTerms['MSG_SELECT_TTYPE']; ?>
	</span>
	<br><br>
	<div id='batch_form_div'>
	</div>
</div>
		
<div id='verify_results' class='results_subdiv' style='display:none;'>
	<div class="panel-heading"><span class='page_title'><?php echo LangUtil::$pageTerms['MENU_VERIFYRESULTS']; ?></span></div>
	<form name='verify_results_form' id='verify_results_form' action='results_verify.php' method='post'>
		<?php echo LangUtil::$generalTerms['TEST_TYPE']; ?>
		&nbsp;&nbsp;&nbsp;
		<select id='verify_test_type' name='t_type' class='uniform_width'>
			<option value=""><?php echo LangUtil::$generalTerms['SELECT_ONE']; ?>..</option>
			<?php $page_elems->getTestTypesSelect($_SESSION['lab_config_id']); ?>
		</select>
		&nbsp;&nbsp;&nbsp;
		<input type='button' onclick='javascript:get_verification_form();' value='<?php echo LangUtil::$generalTerms['CMD_SEARCH']; ?>'></input>
		&nbsp;&nbsp;&nbsp;
		<span id='verify_progress_form' style='display:none'>
			<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_FETCHING']); ?>
		</span>
		<span id='verify_result_error' class='error_string' style='display:none;'>
			<?php echo LangUtil::$generalTerms['MSG_SELECT_TTYPE']; ?>
		</span>
	</form>
	<br><br>
	<div id='verify_form_div'>
	</div>
</div>
		
<div id='control_testing' class='results_subdiv' style='display:none;'>
	<div class="panel-heading"><span class='page_title'><?php echo LangUtil::$pageTerms['CONTROL_TESTING_RESULTS']; ?></span></div>
	<form name='control_testing_form' id='control_testing_form' action='control_testing_entry.php' method='post'>
		<table cellspacing='4px'>
			<tbody>
			<tr valign='top'>
				<td><?php echo LangUtil::$generalTerms['TEST_TYPE']; ?> &nbsp;&nbsp;&nbsp;</td>
				<td>
					<select id='verify_test_type_control' name='t_type' class='uniform_width'>
						<option value=""><?php echo LangUtil::$generalTerms['SELECT_ONE']; ?>..</option>
						<?php $page_elems->getTestTypesSelect($_SESSION['lab_config_id']); ?>
					</select>
					<span id='control_testing_error' class='error_string' style='display:none;'>
						<?php echo LangUtil::$generalTerms['MSG_SELECT_TTYPE']; ?>
					</span>
					<br>
				</td>
			</tr>
			<tr valign='top'>
				<td>Result</td>
				<td>
					<input type="radio" name="controlTesting" id="controlTesting" value="Pass" checked> Pass 
					<input type="radio" name="controlTesting" id="controlTesting" value="Fail"> Fail
					<br>
				</td>
			<tr valign='top'>
				<td></td>
				<td>
					<input type='button' onclick='javascript:verify_control_selection();' value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>'></input>
				</td>
			</tr>
			</tbody>
		</table>
	</form>
	<br><br>
	<div id='control_testing_div'>
	</div>
	<div class='clean-orange' id='control_result_done' style='width:300px' style='display:none;'>
				
	</div>
</div>
		
<div id='worksheet_div' class='results_subdiv' style='display:none;'>
	<div class="panel-heading"><span class='page_title'><?php echo LangUtil::$pageTerms['MENU_WORKSHEET']; ?></span></div>
	<form name='worksheet_form' id='worksheet_form' action='worksheet.php' method='post' target='_blank'>
		<table cellspacing='4px'>
			<tbody>
			<tr valign='top'>
				<td><?php echo LangUtil::$generalTerms['LAB_SECTION']; ?></td>
				<td>
					<select name='cat_code' id='cat_code' class='uniform_width'>
						<?php $page_elems->getTestCategorySelect(); ?>
					</select>
				</td>
			</tr>
			<tr valign='top'>
				<td><?php echo LangUtil::$generalTerms['TEST_TYPE']; ?><br>OR</td>
				<td>
					<select id='worksheet_test_type' name='t_type' class='uniform_width'>
						<?php $page_elems->getTestTypesSelect($_SESSION['lab_config_id']); ?>
					</select>
				</td>
			</tr>
			<tr valign='top'>
				<td>
					<?php echo LangUtil::$pageTerms['CUSTOM_WORKSHEET']; ?></td>
				<td>
					<select id='worksheet_custom_type' name='w_type' class='uniform_width'>
						<option value=""><?php echo LangUtil::$generalTerms['SELECT_ONE']; ?></option>
						<?php 
						$lab_config = LabConfig::getById($_SESSION['lab_config_id']);
						$page_elems->getCustomWorksheetSelect($lab_config); 
						?>
					</select>
				</td>
			</tr>
			<tr valign='top'>
				<td><?php echo LangUtil::$pageTerms['BLANK_WORKSHEET']; ?>?</td>
				<td>
					<input type='radio' name='is_blank' value='Y'><?php echo LangUtil::$generalTerms['YES']; ?></input>
					<input type='radio' name='is_blank' value='N' checked><?php echo LangUtil::$generalTerms['NO']; ?></input>
				</td>
			</tr>
			<tr valign='top' id='num_rows_row' style='display:none;'>
				<td><?php echo LangUtil::$pageTerms['NUM_ROWS']; ?></td>
				<td>
					<input type='text' name='num_rows' id='num_rows' value='10' class='uniform_width'></input>
				</td>
			</tr>
			<tr valign='top'>
				<td></td>
				<td>
					<input type='button' onclick='javascript:get_worksheet();' value='<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>'></input>
					&nbsp;&nbsp;&nbsp;
					<span id='worksheet_progress_form' style='display:none'>
						<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_FETCHING']); ?>
					</span>
					<span id='worksheet_error' class='error_string' style='display:none;'>
						<?php echo LangUtil::$generalTerms['MSG_SELECT_TTYPE']; ?>
					</span>
				</td>
			</tr>
		</table>
	</form>
</div>

<?php
if($SHOW_REPORT_RESULTS === true)
{
?>
<div id='report_results' class='results_subdiv' style='display:none;'>
	<b><?php echo LangUtil::$pageTerms['MENU_REPORTRESULTS']; ?></b>
	<span id='report_results_load_progress'>
	&nbsp;&nbsp;&nbsp;
	<?php
	$page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_FETCHING']);
	?>
	</span>
	<br>
	<br>
	<div id='report_results_container'>
	
	<?php 
	/*
	
	*/
	?>
	</div>
</div>
<?php
}
?>

<form id='ajax_redirect' method='post' action='results_entry.php'>
	<input type='hidden' name='sid_redirect' id='sid_redirect' value=''></input>
	<input type='hidden' name='ajax_response' id='ajax_response' value=''></input>
</form>

<div id="specimen_info" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="true" style="width:900px;">
	  <div class="modal-body">
	   
	  </div>
	  <div class="modal-footer">
	    <button type="button" data-dismiss="modal" class="btn" onclick='javascript:cancel_hide()'>No</button>
	  
	  </div>
</div>

</div>
</div>
<!-- END ROW-FLUID--> 
<?php include("includes/scripts.php");?>
<?php $script_elems->enableDatePicker();
$script_elems->enableJQueryForm();
$script_elems->enableJQueryValidate();
$script_elems->enableTableSorter();
$script_elems->enableLatencyRecord();
$script_elems->enableTokenInput();
?>
<script type='text/javascript'>
tableml = "";
unreported_fetched = false;

function readTextFile(file)
{
    var rawFile = new XMLHttpRequest();
    rawFile.open("GET", file, true);
    rawFile.onreadystatechange = function ()
    {
        if(rawFile.readyState === 4)
        {
            if(rawFile.status === 200 || rawFile.status == 0)
            {
                var allText = rawFile.responseText;
                alert(allText);
            }
        }
    }
    rawFile.send(null);
}

$(document).ready(function(){
	
	push_results_to_external_system();
	
	$('#cat_code').change( function() { get_test_types_bycat() });
	$('#worksheet_test_type').change( function() { reset_worksheet_custom_type() });
	get_test_types_bycat();
	$("#worksheet_results").hide();
	$('.results_subdiv').hide();
	right_load("tests");
	<?php 
	if(isset($_REQUEST['ajax_response']))
	{
		#Rendering after Ajax response (workaround for dynamically loading JS via Ajax)
	?>
		$('#specimen_id').attr("value", "<?php echo $_REQUEST['sid_redirect'] ?>");
	<?php
	}
	else
	{
	?>
		$('#fetched_specimen').hide();
	<?php
	}
	?>
	$("#import_results").hide();
	$("#batch_results").hide();
	$('#resultfetch_attrib').change(function() {
	$('#specimen_id').focus();
	});
	$("input[name='is_blank']").change( function() {
		var is_blank = $("input[name='is_blank']:checked").val();
		if(is_blank == "Y")
			$('#num_rows_row').show();
		else
			$('#num_rows_row').hide();
	});
	<?php
	if($SHOW_REPORT_RESULTS === true)
	{
	?>
		load_unreported_results();
	<?php
	}
	?>
	//hide_worksheet_link();
});

function get_test_types_bycat()
{
	var cat_code = $('#cat_code').val();
	var location_code = <?php echo $_SESSION['lab_config_id']; ?>;
	$('#worksheet_test_type').load('ajax/tests_selectbycat.php?c='+cat_code+'&l='+location_code+'&all_no');
	reset_worksheet_custom_type();
}

function reset_worksheet_custom_type()
{
	$('#worksheet_custom_type').attr("value", "");
}

function toggle(elem_id)
{
	$('#'+elem_id).toggle();
}

function right_load(destn_div)
{
	//hide_worksheet_link();
	$('.results_subdiv').hide();
	$("#"+destn_div).show();
	$('#specimen_id').focus();
	$('.menu_option').removeClass('current_menu_option');
	$('#'+destn_div+'_menu').addClass('current_menu_option');
	$('#'+destn_div+'_subdiv_help').show();
	if(destn_div == "report_results")
	{
		load_unreported_results();
	}
	else if(destn_div == "tests"){
		fetch_tests(<?php echo Specimen::$STATUS_PENDING?>);
		
	}
}

function load_unreported_results()
{
	if(unreported_fetched == false)
	{
		$('#report_results_load_progress').show();
		$('#report_results_container').load("ajax/results_getunreported.php", function() {
			$('#report_results_load_progress').hide();
		});
		unreported_fetched = true;
	}
}

function checkoruncheckall()
{
	if($('#check_all').attr("checked") == true)
	{
		$(".report_flag").attr("checked", "true");
	}
	else
	{
		$(".report_flag").removeAttr("checked");
	}
}

function hide_worksheet_link()
{
	document.getElementById("worksheet_link").innerHTML = "";
}

function hide_test_result_form(test_id)
{
	cancel_show(test_id);
}

function hide_test_result_form_confirmed(test_id)
{	
	cancel_hide();
	$('#result_form_pane_'+test_id).modal('hide');
}

function cancel_show(test_id){
	$('#yes').html('');
	$('#yes').append( "<button type='button' class='btn btn-primary' onclick='javascript:hide_test_result_form_confirmed("+test_id+")'>Yes</button>");
	$('#cancel').modal('show');
}

function cancel_hide(){

	$('#cancel').modal('hide'); 
}

function hide_result_form(test_id)
{
		var target_div_id = "result_form_pane_"+specimen_id;
		$("#"+target_div_id).html("");
		$('#specimen_id').attr("value", "");
}

function fetch_specimen()
{
	var specimen_id = $('#specimen_id').val();
	specimen_id = specimen_id.replace(/[^a-z0-9 ]/gi,'');
	$('#fetch_progress_bar').show();
	<?php 
	#Used when Ajax response did not have JavaScript code included 
	?>
	var attrib = $('#resultfetch_attrib').val();
	var first_char =specimen_id.charAt(0);
	if(attrib==1 && isNaN(first_char)==false)
	{
		alert("Please enter a valid name.");
		return;
	}
	var url = 'ajax/result_entry_patient_dyn.php';
	$("#fetched_patient_entry").load(url, 
		{a: specimen_id, t: attrib}, 
		function() 
		{
			$('#fetch_progress_bar').hide();
			$("#fetched_specimen").show();
			$("#fetched_specimen").html("");
		}
	);
}
/**
 * FETCH PENDING SPECIMENS
 */
function fetch_tests(status,page,search_term)
{	
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	var url = 'ajax/result_entry_tests.php';
	var date_from = Date.today().add({days: -6}).toString('yyyy-MM-dd')+' '+'00:00:00';
	var date_to = Date.today().toString('yyyy-MM-dd')+' '+'23:59:59';
	
	if(page==undefined){
		var page = 0;
	}
	$("#fetched_specimens_entry").load(url, 
		{a: '', t: 10, df:date_from, dt:date_to, s:status, p:page, st:search_term}, 
		function() 
		{
			handleDataTable(10);
			enableAdvancedDatePicker(date_from, date_to);
			if (status==<?php echo Specimen::$STATUS_PENDING;?>){
				$('select', '#status')[0].selectedIndex = 1;
			}else if (status==<?php echo Specimen::$STATUS_STARTED;?>){
				$('select', '#status')[0].selectedIndex = 2;
			}else if (status==<?php echo Specimen::$STATUS_TOVERIFY;?>){
				$('select', '#status')[0].selectedIndex = 3;
			}else if (status==<?php echo Specimen::$STATUS_VERIFIED;?>){
				$('select', '#status')[0].selectedIndex = 4;
			}
			$(".chosen").chosen();
			$("#search_tests").val(search_term);
			App.unblockUI(el);
		}
	);
}
function refresh_date_range(date_from,date_to)
{	
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	var url = 'ajax/result_entry_tests.php';
	$("#fetched_specimens_entry").load(url, 
		{a: '', t: 10, df:date_from, dt:date_to}, 
		function() 
		{
			handleDataTable(10);
			enableAdvancedDatePicker(date_from, date_to);
			App.unblockUI(el);
		}
	);
}

function accept_specimen(specimen_id,test_id)
{

		var el = jQuery('.portlet .tools a.reload').parents(".portlet");
		App.blockUI(el);
		//Mark specimen as accepted
  		url = "ajax/specimen_change_status.php";
  		$.post(url, 
		{sid: specimen_id}, 
		function(result) 
		{
			$('#span'+test_id).addClass('label-important');
			$('#span'+test_id).html('Pending');
			actions = result.split('%');
			$('#actionA'+test_id).html('<td id=actionA'+test_id+' style="width:100px;">'+
					'<a href="javascript:start_test('+test_id+');"'+ 
					'title="Click to begin testing this Specimen" class="btn red mini">'+
					'<i class="icon-ok"></i> Start Test</a></td>');
			$('#actionB'+test_id).html('<td id=actionB'+test_id+' style="width:100px;">'+
					'<a href="javascript:refer_specimen('+test_id+');"'+ 
					'title="Click to begin testing this Specimen" class="btn inverse mini">'+
					'<i class="icon-ok"></i>Refer</a></td>');
			App.unblockUI(el);
		}
	);
		
}
function start_test(test_id)
{
	var r=confirm("Start test?");
	if (r==true)
   	{
		var el = jQuery('.portlet .tools a.reload').parents(".portlet");
		App.blockUI(el);
		//Mark test as cancelled
  		var url = 'ajax/result_entry_tests.php';
  		$.post(url, 
		{a: test_id, t: 12}, 
		function(result) 
		{
			$('#span'+test_id).removeClass('label-important');
			$('#span'+test_id).addClass('label-warning');
			$('#span'+test_id).html('Started');
			actions = result.split('%');
			$('#actionA'+test_id).html(actions[0]);
			$('#actionB'+test_id).html(actions[1]);
			App.unblockUI(el);
		}
	);
		
  	}
	else
  	{
  		//Cancel Starting test
  	}
}

function fetch_test_result_form(test_id)
{
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	var pg=2;
	$('#fetch_progress_bar').show();
	var url = 'ajax/result_form_fetch.php';
	//var target_div = "fetch_specimen";
	$('.result_form_pane').html("");
	var target_div = "result_form_pane_"+test_id;
	$("#"+target_div).load(url, 
		{tid: test_id , page_id:pg}, 
		function() 
		{
			$('#'+target_div).modal('show');
			App.unblockUI(el);
		}
	);
}

function view_test_result(test_id, status)
{
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	var pg=2;
	$('#fetch_progress_bar').show();
	var url = 'ajax/result_view.php';
	$('.result_form_pane').html("");
	var target_div = "result_form_pane_"+test_id;
	$("#"+target_div).load(url, 
		{tid: test_id , page_id:pg}, 
		function() 
		{
			$('#'+target_div).modal('show');
			if(status==<?php echo Specimen::$STATUS_VERIFIED;?>){
				$('#verifybtn'+test_id).remove();
				$('#verifydby'+test_id).removeClass('label-warning');
				$('#verifydby'+test_id).addClass('label-success');
			}
			App.unblockUI(el);
		}
	);
}
function specimen_info(specimen_id)
{
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	var url = 'search/specimen_info.php';
	var target_div = "specimen_info";
	$("#"+target_div).load(url, 
		{sid: specimen_id, modal:1}, 
		function() 
		{
			$('#'+target_div).modal('show');
			if(status==<?php echo Specimen::$STATUS_VERIFIED;?>){
				$('#verifybtn'+test_id).remove();
				$('#verifydby'+test_id).removeClass('label-warning');
				$('#verifydby'+test_id).addClass('label-success');
			}
			App.unblockUI(el);
		}
	);
}

function remove(test_id){
	var target_div = "result_form_pane_"+test_id;
	$("#test_"+test_id).remove();
	$('#'+target_div).modal('hide'); 
	
}
function remove_modal(id){
	var target_div = id;
	$('#'+target_div).modal('hide'); 
	
}

function fetch_specimen2(specimen_id)
{
var pg=2;
	$('#fetch_progress_bar').show();
	var url = 'ajax/specimen_form_fetch.php';
	//var target_div = "fetch_specimen";
	$('.result_form_pane').html("");
	var target_div = "result_form_pane_"+specimen_id;
	$("#"+target_div).load(url, 
		{sid: specimen_id , page_id:pg}, 
		function() 
		{
			$('#fetch_progress_bar').hide();
			$("#fetched_specimen").show();
		}
	);
}

function verify_control_selection() {
	$('#control_testing_error').hide();
	var test_type_id = $('#verify_test_type_control').val();
	alert(test_type_id);
	//var result = $('#control_testing_form').value("controlTesting");
	var result = document.getElementById('controlTesting').value;
	alert(result);
	//alert(testName);
	if(test_type_id == "")
	{	
		$('#control_testing_error').show();
		return;
	}
	
	$('#control_result_done').show();
	
	//$('#control_testing_form').submit();
}

function toggle_form(form_id, checkbox_obj)
{
	if(checkbox_obj.checked == true)
	{
		$('#'+form_id+' :input').attr('disabled', 'disabled');
		checkbox_obj.disabled=false;
	}
	else
	{
		$('#'+form_id+' :input').removeAttr('disabled');
		checkbox_obj.disabled=false;
	}
}

function submit_forms(test_id)
{
	var form_id_csv = $('#form_id_list').val();
	var form_id_list = form_id_csv.split(",");
	$('.result_cancel_link').hide();
	$('.result_progress_spinner').show();
	//var target_div_id = "fetched_specimen";
	var target_div_id = "result_form_pane_"+test_id;
	for(var i = 0; i < form_id_list.length; i++)
	{
		if($('#'+form_id_list[i]+'_skip').is(':checked'))
		{
			continue;
		}
		var params = $('#'+form_id_list[i]).formSerialize();
		var string = params.toString();
		var str1 = string.split("&");
		var str2 = str1[0].split("=");
		var actual_test_id = str2[1];
	
			$.ajax({
			type: "POST",
			url: "ajax/result_add.php",
			data: params,
			success: function(msg) {
				//$("#test_"+actual_test_id)[0].reset();
				$("#test_"+actual_test_id).remove();
				$("#"+target_div_id).html(msg);
				$("tr#"+test_id).remove();



				$('#span'+actual_test_id).removeClass('label-warning');
				$('#span'+actual_test_id).addClass('label-info');
				$('#span'+actual_test_id).html('Tested');
				actions = result.split('%');
				$('#actionA'+test_id).html(actions[0]);
				$('#actionB'+test_id).html(actions[1]);		
			}
		});	
	}
	push_results_to_external_system();
}

function push_results_to_external_system(){
	//push results to sanitas
	var pushURL ='ajax/push_results.php';
	$.post(
		pushURL,
		{test_id:0},  
        function(responseText){
	         
        }
	);
}

function get_batch_form()
{
	$('#batch_result_error').hide();
	tableml = "";
	var test_type_id = $('#batch_test_type').val();
	var date_to_array=$('#yyyy_to').val()+"-"+$('#mm_to').val()+"-"+$('#dd_to').val();
	var date_from_array=$('#yyyy_from').val()+"-"+$('#mm_from').val()+"-"+$('#dd_from').val();
	var table_id = 'batch_result_table';
	if(test_type_id == "")
	{	
		$('#batch_result_error').show();
		$('#batch_form_div').html("");
		return;
	}
	$('#batch_progress_form').show();
	$('#batch_form_div').load(
		"ajax/batch_results_form_fetch.php", 
		{ 
			t_type: test_type_id,
			date_to:date_to_array,
			date_from:date_from_array
		}
		,
		function (){
			<?php
			//Disabled table sorting, as batch entry forms are now aligned with worksheets
			//$('#'+table_id).tablesorter();
			?>
		}
	);
	$.ajax({
		type: "GET",
		url: "ajax/batch_results_form_row.php",
		data: "t_type="+test_type_id+"date_to="+date_to_array+"date_from="+date_from_array, 
		success : function(msg) {
            tableml = msg;
			$('#batch_progress_form').hide();
		}
	});
}

function get_verification_form()
{
	$('#verify_result_error').hide();
	var test_type_id = $('#verify_test_type').val();
	if(test_type_id == "")
	{	
		$('#verify_result_error').show();
		return;
	}
	$('#verify_progress_form').show();
	$('#verify_results_form').submit();
}

function get_worksheet()
{
	$('#worksheet_error').hide();
	var num_rows = $('#num_rows').val();
	if(isNaN(num_rows))
	{
		$('#num_rows').attr("value", "10");
	}
	var worksheet_id = $('#worksheet_custom_type').val();
	var test_type_id = $('#worksheet_test_type').val();
	if(worksheet_id == "" && test_type_id == "")
	{	
		$('#worksheet_error').show();
		return;
	}
	$('#worksheet_progress_form').show();
	$('#worksheet_form').submit();
	$('#worksheet_progress_form').hide();
}

function clear_batch_table()
{
	$('#batch_form_div').html("");
}

function submit_batch_form()
{
	$('#batch_submit_progress').show();
	$('#batch_submit_button').attr("disabled", "disabled");
	$('#batch_cancel_button').hide();
	$('#batch_form').submit();
}

function add_one_batch_row()
{
	var row_count = $('#batch_result_table tr').size();
	var row_html = "<tr valign='top'><td>"+row_count+"</td>"+tableml;
	$('#batch_result_table').append(row_html);
}

function add_five_batch_rows()
{
	for(var i = 0; i < 5; i++)
		add_one_batch_row();
}

function mark_reported()
{
	$('#report_results_progress_div').show();
	$('#report_results_form').ajaxSubmit({
		success: function() {
			$('#report_results_progress_div').hide();
			$('#report_results_form_div').hide();
			$('#report_results_confirm').show();
			unreported_fetched = false;
		}
	});
}

function show_more_pnum()
{
	$(".old_pnum_records").show();
	$("#show_more_pnum_link").hide();
}

function hide_result_confirmation(specimen_id)
{
	var target_div_id = "result_form_pane_"+specimen_id;
	$("#"+target_div_id).html("");
}
function update_numeric_remarks(test_type_id, count, patient_age, patient_sex)
{
	
 <?php # See ajax/specimen_form_fetch.php for field names ?>
	 var values_csv = "";
	 var remarks_input_id = "test_"+test_type_id+"_comments";
	 for(var i = 0; i < count; i++)
	 {
	 var input_id = "measure_"+test_type_id+"_"+i;
	 values_csv += $('#'+input_id).val()+"_";
	 }
	 var url_string = "ajax/fetch_remarks.php";
	values_csv = encodeURIComponent(values_csv);
	var data_string = "lid=<?php echo $_SESSION['lab_config_id']; ?>&ttype="+test_type_id+"&values_csv="+values_csv+"&patient_age="+patient_age+"&patient_sex"+patient_sex;
	 $.ajax({
	 type: "POST",
		 url: url_string,
		 data: data_string,
		 success: function(msg) {
		$("#"+remarks_input_id).val( msg)
		 }
	 });

}


function update_remarks(test_type_id, count, patient_age, patient_sex)
{
	 <?php # See ajax/specimen_form_fetch.php for field names ?>
	 var values_csv = "";
	 var remarks_input_id = "test_"+test_type_id+"_comments";
	 for(var i = 0; i < count; i++)
	 {
	 var input_id = "measure_"+test_type_id+"_"+i;
	 values_csv += $('#'+input_id).val()+"_";
	 }
	 var url_string = "ajax/fetch_remarks.php";
	values_csv = encodeURIComponent(values_csv);
	var data_string = "lid=<?php echo $_SESSION['lab_config_id']; ?>&ttype="+test_type_id+"&values_csv="+values_csv+"&patient_age="+patient_age+"&patient_sex="+patient_sex;
	// var data_string = "lid=<?php echo $_SESSION['lab_config_id']; ?>&ttype="+test_type_id+"&values_csv="+values_csv;
	 $.ajax({
	 type: "POST",
		 url: url_string,
		 data: data_string,
		 success: function(msg) {
		$("#"+remarks_input_id).val(msg);
		 }
	 });
}
</script>
<?php
$script_elems->bindEntertoClick("#specimen_id", "#fetch_specimen_button");
?>
<?php include("includes/footer.php"); ?>