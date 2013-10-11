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
								<i class="icon-download-alt"></i>
								<a href="index.php">Home</a> 
							</li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
 				<!-- BEGIN REGISTRATION PORTLETS-->   
				<div class="row-fluid">
				<div class="span12 sortable">
				
<div id="sample_collection" class='reg_subdiv' style='display:none;'>
	<div class="portlet box blue">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i>Sample Collection</h4>
			<div class="tools">
			<a href="javascript:;" class="collapse"></a>
			<a href="javascript:;" class="reload"></a>
			</div>
		</div>
		<div class="portlet-body form">
		  <p style="text-align: right;"><a rel='facebox' href='#Rejection'>Page Help</a></p>
		   <div class="alert alert-info" style="display: none">
                            <button class="close" data-dismiss="alert"></button>
                            <strong>You have successfully rejected the specimen.</strong>
                     </div>
			<div id='sample_collection_body' class="portlet" style='position:relative;left:10px; height: 500px'> 			       
		</div>	 				
		</div>
	</div>
</div>
<!-- END PATIENT SAMPLE REJECTION -->


<!-- BEGIN LAB REQUESTS -->
<div id="lab_requests" class='reg_subdiv' style='display:none;'>
<div class="portlet box blue">
<div class="portlet-title">
<h4><i class="icon-reorder"></i>Search Lab Requests</h4>
<div class="tools">
<a href="javascript:;" class="collapse"></a>
<a href="#portlet-config" data-toggle="modal" class="config"></a>
<a href="javascript:;" class="reload"></a>

</div>
</div>
		<div class="portlet-body form">
		<p style="text-align: right;"><a rel='facebox' href='#Registration'>Page Help</a></p>

			<div class="scroller" data-height="400px" data-always-visible="1">
				<p>Search existing patients</p>
				<form>
				<select name='p_attrib' id='p_attrib' style='font-family:Tahoma;' class='uniform_width m-wrap tooltips'>
				<?php $page_elems->getPatientSearchAttribSelect(); ?>
				</select>
				&nbsp;&nbsp;
				<input type='text' name='pq' id='pq' style='font-family:Tahoma;' onkeypress="return restrictCharacters(event)" class='uniform_width m-wrap tooltips' />
				&nbsp;&nbsp;
				<button id='psearch_button' class="btn green button-submit" type="button" onclick="javascript:fetch_patients();">
				<i class='icon-search'></i> <?php echo LangUtil::$generalTerms['CMD_SEARCH']; ?></button>
				<span id='psearch_progress_spinner'>
				<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SEARCHING']); ?>
				</span>
				</form><div class="span4" id="help_text" style="position: absolute;top: 0px;right: 0px;">
						<!-- BEGIN Portlet PORTLET-->
						<div class="">
											<div class="well text-info">
											<?php
											echo "<b>Tips</b>";
											echo "<li>";
											echo "Search Patients using the Sanitas Number.";
											echo "</li>";
										
											echo "<li>"; 
											echo "If the patient details are not found, then proceed to register the patient using the <a href='javascript:load_patient_reg();'><abbr title='Add New Patient'>Add New Patient </abbr></a> Link as shown.";
											echo "</li>";
										
											?>
												
												
											</div>
										</div>
					</div>
				<br />
				<div id='add_anyway_div' >
					<a class ="btn" id='add_anyway_link' href='javascript:load_patient_reg()'><i class='icon-plus'></i> <?php echo LangUtil::$pageTerms['ADD_NEW_PATIENT']; ?> &raquo;</a>
				</div>
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
				<div id='Rejection' class='right_pane' style='display:none;margin-left:10px;'>
					<ul>
						<?php
						if(LangUtil::$pageTerms['TIPS_REGISTRATION_1']!="-") {
							echo "<li>";
							echo "This page allows us to lookup existing specimens based on patients name, patient ID or number.";
							echo "</li>";
						}	
						if(LangUtil::$pageTerms['TIPS_REGISTRATION_2']!="-") {
							echo "<li>"; 
							echo "Once a specimen has been registered, we can use this page to accept or reject the specimen.";
							echo "</li>";
						}
						if(LangUtil::$pageTerms['TIPS_PATIENT_LOOKUP']!="-")	{
							echo "<li>"; 
							echo "To Accept/Reject a specimen, use the buttons as shown clearly.";
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
<!-- END LAB REQUEST -->

<!-- BEGIN PATIENT REGISTATION -->
<div id="patient_registration" class='reg_subdiv' style='display:none;'>
	<div class="portlet box blue">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i>Patient Registration</h4>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body form">
		<p style="text-align: right;"><a rel='facebox' href='#Registration'>Page Help</a></p>
		<div class="span4" style="position: absolute;top: 180px;right: 30px;">
						<!-- BEGIN Portlet PORTLET-->
						<div class="">
											<div class="well text-info">
											<?php
											echo "<b>Tips</b>";
											echo "<li>";
											echo "This page allows us to register new patients. Enter the details of the patient carefully and appropriately.";
											echo "</li>";
										
											echo "<li>"; 
											echo "Remember to select the correct gender and type of patient you are registering.";
											echo "</li>";
										
											echo "<li>"; 
											echo "Enter the correct date of birth, otherwise, enter the age if the patient does not know the date of birth.";
											echo "</li>";
											?>
												
											</div>
										</div>
					</div>
			<div id='patients_registration_body' style='position:relative;left:10px; '> </div>
					
		</div>
	</div>
</div>
<!-- END PATIENT REGISTATION -->

<!-- BEGIN SPECIMEN REGISTATION -->
<div id="specimen_reg" class='reg_subdiv' style='display:none;'>
	<div class="portlet box blue">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i>Registration of Lab Requests</h4>

			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
			</div>
		</div>
		<div class="portlet-body form">
			<div id='specimen_reg_body' style='position:relative;left:10px;'> </div>					
		</div>
	</div>
</div>
<!-- END SPECIMEN REGISTRATION -->

<!-- END LAB REQUESTS -->
</div>
<!-- END SPAN 12 -->
</div>

<!-- BEGIN SPECIMEN REJECTION-->	
<div id="specimen_rejection" class='reg_subdiv' style='display:none;'>
	<div class="portlet box yellow">
		<div class="portlet-title">

			<h4><i class="icon-reorder"></i>Specimen Rejection</h4>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body form">
			<div id='specimen_rejection_body' style='position:relative;left:10px;'> </div>					
		</div>
	</div>
</div>
<!-- END SPECIMEN REJECTION-->   
<!-- BEGIN SPECIMEN ACCEPTANCE-->	
<div id="specimen_acceptance" class='reg_subdiv' style='display:none;'>
	<div class="portlet box green">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i>Specimen Acceptance</h4>

			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body form">
			<div id='specimen_acceptance_body' style='position:relative;left:10px;'> </div>					
		</div>
	</div>
</div>
<!-- END SPECIMEN ACCEPTANCE-->   


<?php
include("includes/scripts.php");
?>
<?php 
$script_elems->enableDatePicker();

?>
<script type='text/javascript'>
$(document).ready(function() {
	$('#psearch_progress_spinner').hide();
	$('#add_anyway_link').attr("href", "javascript:load_patient_reg()");
	$('#pq').focus();
	$('#p_attrib').change(function() {
		$('#pq').focus();
	});
	<?php
    if(isset($_REQUEST['show_sc']))
    {
        //Load sample collection table
        if($_REQUEST['show_sc'] == 1)   
        {
            ?>
            right_load("sample_collection");
            $(".alert.alert-info").show();
                setTimeout(function() { $(".alert.alert-info").hide(); }, 4000);
            <?php
        }
        else
        {
            ?>
            right_load("sample_collection");
            <?php    
        }
               
    }
    else {
        ?>
        right_load("lab_requests");
        <?php 
    }
    ?>
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
				//window.location="new_patient.php?n="+patient_id+"&jmp=1";
				$('#add_anyway_link').attr("href", "javascript:right_load('new_patient')");
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
			if(search_attrib == 1)
			{
				$('#add_anyway_link').html(" If not this name register '<b>"+patient_id+"</b>' <?php echo LangUtil::$pageTerms['ADD_NEW_PATIENT']; ?>&raquo;");
				$('#add_anyway_link').attr("href", "javascript:load_patient_reg()");
			}
			else
			{
				$('#add_anyway_link').html("If not this name register <?php echo LangUtil::$pageTerms['ADD_NEW_PATIENT']; ?> &raquo;");
				$('#add_anyway_link').attr("href", "javascript:load_patient_reg()");
			}
			$('#add_anyway_div').show();
			$('#psearch_progress_spinner').hide();
		}
		
	);
}

function right_load(destn_div)
{	
	$('.reg_subdiv').hide();
	$('.results_subdiv').hide();
	$("#"+destn_div).show();
	$('#specimen_id').focus();
	$('.menu_option').removeClass('current_menu_option');
	$('#'+destn_div+'_menu').addClass('current_menu_option');
	$('#'+destn_div+'_subdiv_help').show();
	
	if(destn_div == "lab_requests")
	{
		$('#sample_collection').hide();
	}
	else if(destn_div == "sample_collection"){
		$('#lab_requests').hide();
		fetch_patient_specimens_accept_reject();
		
	}
	
}
/**
 * FETCH PATIENTS AND SPECIMENS
 */
function fetch_patient_specimens_accept_reject()
{	
	var el = jQuery('.portlet .tools a.reload').parents(".portlet");
	App.blockUI(el);
	var url = 'ajax/patient_sample_accept_reject.php';
	$("#sample_collection_body").load(url, 
		{a: '', t: 10}, 
		function(response, status) 
		{
		    App.unblockUI(el);
			handleDataTable(10);
			$("#sample_collection_body").css({'height':''});
		}
	);	
}

function load_patient_reg()
{
	$('.reg_subdiv').hide();
	var patient_id = $.trim($('#pq').val());
	patient_id = patient_id.replace(/[^a-z0-9 ]/gi,'');
	var url = 'regn/new_patient2.php';
	$('#patients_registration_body').load(url, {n: patient_id});		
	$('#patient_registration').show();
}

function load_specimen_reg(patient_id, is_external_patient)
{
	$('.reg_subdiv').hide();
	var patientid = patient_id;
	//Load new_specimen2.php via ajax
	var url = 'regn/new_specimen2.php';
	$('#specimen_reg_body').load(url, {pid: patient_id, ex: is_external_patient });		
	$('#specimen_reg').show();
}

function load_specimen_rejection(specimen_id)
{
	$('.reg_subdiv').hide();
	var specimen_id = specimen_id;
	var url = 'regn/specimen_rejection.php';
	$('#specimen_rejection_body').load(url, {sid: specimen_id});		
	$('#specimen_rejection').show();
}
function load_specimen_acceptance(specimen_id)
{
	$('.reg_subdiv').hide();
	var specimen_id = specimen_id;
	var url = 'regn/specimen_acceptance.php';
	$('#specimen_acceptance_body').load(url, {sid: specimen_id});		
	$('#specimen_acceptance').show();
};
</script>
<?php $script_elems->bindEnterToClick('#pq', '#psearch_button'); ?>
<?php include("includes/footer.php");?>