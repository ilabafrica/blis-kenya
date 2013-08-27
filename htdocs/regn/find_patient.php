<?php
#
# (c) C4G, Santosh Vempala, Ruban Monu and Amol Shintre
# Main page for starting patient lookup
# 1st step of specimen registration
#
include("redirect.php");
include("includes/header.php");
LangUtil::setPageId("find_patient");
putUILog('find_patient', 'X', basename($_SERVER['REQUEST_URI'], ".php"), 'X', 'X', 'X');
$lab_config = get_lab_config_by_id($_SESSION['lab_config_id']);
?>
<!-- BEGIN PAGE TITLE & BREADCRUMB-->		
						<h3>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="index.php">Home</a> 
								<span class="icon-angle-right"></span>
							</li>
							<li><a href="#">Reception</a>
							<span class="icon-angle-right"></span></li>
							<li><a href="#"></a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
 				<!-- BEGIN REGISTRATION PORTLETS-->   
				<div class="row-fluid">
				<div class="span12 sortable">
				
<!-- <div class="portlet box blue"> -->
<!-- <div class="portlet-title"> -->
<!-- <h4><i class="icon-reorder"></i>Portlet</h4> -->
<!-- <div class="tools"> -->
<!-- <a href="javascript:;" class="collapse"></a> -->
<!-- <a href="#portlet-config" data-toggle="modal" class="config"></a> -->
<!-- <a href="javascript:;" class="reload"></a> -->
<!-- <a href="javascript:;" class="remove"></a> -->
<!-- </div> -->
<!-- </div> -->
<!-- <div class="portlet-body"> -->
<!-- <div class="scroller" data-height="200px" data-always-visible="1"> -->
<!-- testter -->
<!-- </div> -->
<!-- </div> -->
<!-- </div> -->
<!-- BEGIN PATIENT LOOK UP -->
<div id="patient_lookup" class='reg_subdiv' style='display:none;'>
<div class="portlet box blue">
<div class="portlet-title">
<h4><i class="icon-reorder"></i>Sample collection</h4>
<div class="tools">
<a href="javascript:;" class="collapse"></a>
<a href="#portlet-config" data-toggle="modal" class="config"></a>
<a href="javascript:;" class="reload"></a>
<a href="javascript:;" class="remove"></a>
</div>
</div>
<div class="portlet-body">
<div class="scroller" data-height="200px" data-always-visible="1">
<form>
<select name='p_attrib' id='p_attrib' style='font-family:Tahoma;'>
<?php $page_elems->getPatientSearchAttribSelect(); ?>
</select>
&nbsp;&nbsp;
<input type='text' name='pq' id='pq' style='font-family:Tahoma;' onkeypress="return restrictCharacters(event)" />
&nbsp;&nbsp;
<input type='button' value='<?php echo LangUtil::$generalTerms['CMD_SEARCH']; ?>' id='psearch_button' onclick="javascript:fetch_patients();" />
&nbsp;&nbsp;&nbsp;
<span id='psearch_progress_spinner'>
<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SEARCHING']); ?>
</span>
</form>
<div id='Registration' class='right_pane' style='display:none;margin-left:10px;'>
	<ul>
		<?php
		if(LangUtil::$pageTerms['TIPS_REGISTRATION_1']!="-") {
			echo "<li>";
			echo LangUtil::$pageTerms['TIPS_REGISTRATION_1'];
			echo "</li>";
		}	
		if(LangUtil::$pageTerms['TIPS_REGISTRATION_2']!="-") {
			echo "<li>"; 
			echo LangUtil::$pageTerms['TIPS_REGISTRATION_2'];
			echo "</li>";
		}
		if(LangUtil::$pageTerms['TIPS_PATIENT_LOOKUP']!="-")	{
			echo "<li>"; 
			echo LangUtil::$pageTerms['TIPS_PATIENT_LOOKUP'];
			echo "</li>"; 
		}
		?>
	</ul>
</div>
<div id='patients_found' style='position:relative;left:10px;'>
</div>
</div>
</div>
</div>
</div>
<!-- BEGIN PATIENT LOOK UP -->


<!-- BEGIN LAB REQUESTS -->
<div id="lab_requests" class='reg_subdiv' style='display:none;'>
<div class="portlet box blue">
<div class="portlet-title">
<h4><i class="icon-reorder"></i>Lab Requests</h4>
<div class="tools">
<a href="javascript:;" class="collapse"></a>
<a href="#portlet-config" data-toggle="modal" class="config"></a>
<a href="javascript:;" class="reload"></a>
<a href="javascript:;" class="remove"></a>
</div>
</div>
<div class="portlet-body form">
<?php //include 'new_patient.php';?>
</div>
</div>
</div>
<!-- END LAB REQUESTS -->
</div>
<!-- END SPAN 12 -->
</div>

<!-- BEGIN SPECIMEN REGISTRATION-->	
<div id="sample_collection" class='sample_collection_subdiv' style='display:none;'>
	<div class="portlet box blue">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i><?php echo "Sample Collection";?></h4>
			<div class="tools">
				<a href="javascript:fetch_patient_specimens_accept_reject();" class="reload"></a>
				<a href="javascript:;" class="collapse"></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" data-height="400px" data-always-visible="1">
				<div id='fetched_specimens_entry'>
				<!--PENDING SPECIMENTS LOADED IN THIS DIV-->
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
<!-- END PSPECIMEN REGISTRATION PORTLET-->
<!-- END REGISTRATION PORTLETS-->   
<p style="text-align: right;"><a rel='facebox' href='#Registration'>Page Help</a></p>

<?php
include("includes/scripts.php");
?>
<?php $script_elems->enableDatePicker();
$script_elems->enableJQueryForm();?>
<script type='text/javascript'>
$(document).ready(function() {
	$('#psearch_progress_spinner').hide();
	$('#add_anyway_link').attr("href", "new_patient.php");
	$('#pq').focus();
	$('#p_attrib').change(function() {
		$('#pq').focus();
	});
});

function restrictCharacters(e) {
	
	var alphabets = /[A-Za-z]/g;
	var numbers = /[0-9]/g;
	if(!e) var e = window.event;
	if( e.keyCode ) code = e.keyCode;
	else if ( e.which) code = e.which;
	var character = String.fromCharCode(code);
	
	if( !e.ctrlKey && code!=9 && code!=8 && code!=27 && code!=36 && code!=37 && code!=38  && code!=40 &&code!=13 &&code!=32 ) {
		if ( !character.match(alphabets) && !character.match(numbers) )
			return false;
		else
			return true;
	}
	else
		return true;
}

function fetch_patients()
{
	$('#psearch_progress_spinner').show();
	var patient_id = $.trim($('#pq').val());
	patient_id = patient_id.replace(/[^a-z0-9 ]/gi,'');
	var search_attrib = $('#p_attrib').val();
	var check_url = "ajax/patient_check_name.php?n="+patient_id;
	$.ajax({ url: check_url, success: function(response){
			if(response == "false" && search_attrib == 1)
			{
				$('#psearch_progress_spinner').hide();
				window.location="new_patient.php?n="+patient_id+"&jmp=1";
			}
			else
			{
				continue_fetch_patients();
			}
		}
	});
}

function continue_fetch_patients()
{
	var patient_id = $.trim($('#pq').val());
	patient_id = patient_id.replace(/[^a-z0-9 ]/gi,'');
	var search_attrib = $('#p_attrib').val();
	$('#psearch_progress_spinner').show();
	if(patient_id == "")
	{
		$('#psearch_progress_spinner').hide();
		$('#add_anyway_div').show();
		return;
	}
	var url = 'ajax/search_p.php';
	$("#patients_found").load(url, 
		{q: patient_id, a: search_attrib}, 
		function(response)
		{
			$('#psearch_progress_spinner').hide();
			if(search_attrib == 1)
			{
				$('#add_anyway_link').html(" If not this name '<b>"+patient_id+"</b>' <?php echo LangUtil::$pageTerms['ADD_NEW_PATIENT']; ?>&raquo;");
				$('#add_anyway_link').attr("href", "new_patient.php?n="+patient_id);
			}
			else
			{
				$('#add_anyway_link').html("If not this name. <?php echo LangUtil::$pageTerms['ADD_NEW_PATIENT']; ?> &raquo;");
				$('#add_anyway_link').attr("href", "new_patient.php");
			}
			$('#add_anyway_div').show();
		}
	);
}

function right_load(destn_div)
{
	$('.reg_subdiv').hide();
	$("#"+destn_div).show();
	$('#specimen_id').focus();
	$('.menu_option').removeClass('current_menu_option');
	$('#'+destn_div+'_menu').addClass('current_menu_option');
	$('#'+destn_div+'_subdiv_help').show();
	
}
/**
 * FETCH PATIENTS AND SPECIMENS
 */
function fetch_patient_specimens_accept_reject()
{	
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	var url = 'ajax/patient_sample_accept_reject.php';
	$("#sample_collection").load(url, 
		{a: '', t: 10}, 
		function() 
		{
			handleDataTable(10);
			App.unblockUI(el);
		}
	);
}
</script>
<?php $script_elems->bindEnterToClick('#pq', '#psearch_button'); ?>
<?php include("includes/footer.php");?>