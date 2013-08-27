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

			<div class="scroller" data-height="400px" data-always-visible="1">
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
				<div id='add_anyway_div' style='display:none'>
					<a id='add_anyway_link' href='javascript:right_load('new_patient')'><?php echo LangUtil::$pageTerms['ADD_NEW_PATIENT']; ?> &raquo;</a>
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
				<div id='patients_found' style='position:relative;left:10px;'>
				</div>
				</div>
		
		</div>
</div>
</div>

<!-- BEGIN NEW PATIENT REGISTRATION -->
<div id="new_patient" class='reg_subdiv' style='display:none;'>
	<div class="portlet box blue">
		<div class="portlet-title">
			<h4><i class="icon-reorder"></i>Register new patient</h4>
			<div class="tools">
			<a href="javascript:;" class="collapse"></a>
			<a href="#portlet-config" data-toggle="modal" class="config"></a>
			<a href="javascript:;" class="reload"></a>
			<a href="javascript:;" class="remove"></a>
			</div>
		</div>
	<div class="portlet-body form">
		<table cellspacing='0px'>
		<tr valign='top'>
		<td>
		<div id='patient_new'>
		<div class='pretty_box' style='width:500px'>
		<form name="new_record" action="add_patient.php" method="post" id="new_record" class="form-horizontal" role="form">
			<?php # Hidden field for db key ?>
			
			<input type='hidden' name='card_num' id='card_num' value="<?php echo get_max_patient_id()+1; ?>" ></input>
			<table cellpadding="2" class='regn_form_table'>
			<tr>	
			<div class="control-group" <?php if($_SESSION['pid'] == 0) echo " style='display:none;' ";?> >
			 <td>
				   <?php echo LangUtil::$generalTerms['PATIENT_ID']; ?>
					
					<?php
					if($_SESSION['pid'] == 2)
						$page_elems->getAsterisk();
					?>
				</td>
				<td>
					<input type="text" name="pid" id="pid" value="" size="20" class='uniform_width form-control'>
				</td>
			 </div>
			</tr>
			<tr>
				<td>  Date of Registration </td>
				<td><input type="text" id="datepicker" />	</td>			
			</tr>
			<tr <?php
			if($_SESSION['p_addl'] == 0)
				echo " style='display:none;' ";
			?>>
				<td>
					<?php echo LangUtil::$generalTerms['ADDL_ID'];
					if($_SESSION['p_addl'] == 2)
						$page_elems->getAsterisk();
					?>
				</td>
				<td><input type="text" name="addl_id" id="addl_id" value="" size="20" class='uniform_width' /></td>
			</tr>
			<tr <?php
			if( is_numeric($_SESSION['dnum']) && $_SESSION['dnum'] == 0 )
				echo " style='display:none;' ";
			?>>
				<td><?php echo LangUtil::$generalTerms['PATIENT_DAILYNUM']; ?>
				<?php
					if($_SESSION['dnum'] == 2)
						$page_elems->getAsterisk();
					?>
				</td>
				<td><input type="text" name="dnum" id="dnum" value="<?php echo $daily_num; ?>" size="20" class='uniform_width' /></td>
			</tr>
			<tr<?php
			if($_SESSION['pname'] == 0)
				echo " style='display:none;' ";
			?>>	
				<td><?php echo LangUtil::$generalTerms['NAME']; ?><?php $page_elems->getAsterisk(); ?> </td>
				<td><input type="text" name="name" id="name" value="" size="20" class='uniform_width' /></td>
			</tr>
			
			<tr<?php
			if($_SESSION['sex'] == 0)
				echo " style='display:none;' ";
			?>>
				<td><?php echo LangUtil::$generalTerms['GENDER']; ?><?php $page_elems->getAsterisk();?> </td>
				<td>
					<INPUT TYPE=RADIO NAME="sex" id="sex" VALUE="M" checked><?php echo LangUtil::$generalTerms['MALE']; ?>
					<INPUT TYPE=RADIO NAME="sex" VALUE="F"><?php echo LangUtil::$generalTerms['FEMALE']; ?>
				<br>
					
				</td>
			</tr>
			
			<tr><?php
			if($_SESSION['age'] == 0)
				echo " style='display:none;' ";
			?>
				<td><?php echo LangUtil::$generalTerms['AGE']; ?> <?php
					if($_SESSION['age'] == 2)
						$page_elems->getAsterisk();
					?>
				</td>
				<td>
				<font style='color:red'><?php echo LangUtil::$pageTerms['TIPS_DOB_AGE'];?></font>
					<input type="text" name="age" id="age" value="" size="4" maxlength="10" class='uniform_width' />
					
					<select name='age_param' id='age_param'>
						<option value='1'><?php echo LangUtil::$generalTerms['YEARS']; ?></option>
						<option value='2'><?php echo LangUtil::$generalTerms['MONTHS']; ?></option>
						<option value='3'><?php echo LangUtil::$generalTerms['DAYS']; ?></option>
						<option value='4'>Weeks</option>
						<option value='5'>Range(Years)</option>
					</select>
					
				</td>
			</tr>
			<tr valign='top'<?php
			if($_SESSION['dob'] == 0)
				echo " style='display:none;' ";
			?>>	
				<td>
					<?php echo LangUtil::$generalTerms['DOB']; ?> 
					<?php
					if($_SESSION['dob'] == 2)
						$page_elems->getAsterisk();
					?>
				</td>
				<td>
				<?php
				$name_list = array("yyyy", "mm", "dd");
				$id_list = $name_list;
				$value_list = array("", "", "");
				$page_elems->getDatePicker($name_list, $id_list, $value_list); 
				?>
				</td>
			</tr>
				
		</form>
			
		<form id='custom_field_form' name='custom_field_form' action='ajax/patient_add_custom.php' method='get'>
		<input type='hidden' name='pid2' id='pid2' value=''></input>
			<?php
			$custom_field_list = get_custom_fields_patient();
			foreach($custom_field_list as $custom_field)
			{
				if(($custom_field->flag)==NULL)
				{
				?>
				<tr valign='top'>
					<td><?php echo $custom_field->fieldName; ?></td>
					<td><?php $page_elems->getCustomFormField($custom_field); ?></td>
				</tr>
				<?php
				}
			}
			?>
		</form>
			
			<tr>
				<td></td>
				<td>
					<input type="button" id='submit_button' onclick="add_patient();" value="<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?>" />
					&nbsp;&nbsp;
					<a href='find_patient.php'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></a>
					&nbsp;&nbsp;
					<span id='progress_spinner' style='display:none'>
						<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SUBMITTING']); ?>
					</span>
				</td>
			</tr>
		
		</table>
		<!--</form>-->
		</div>
		<small>
		<span style='float:right'>
			<?php $page_elems->getAsteriskMessage(); ?>
		</span>
		</small>
		</div>
		</td>
		<td>
		&nbsp;&nbsp;&nbsp;
		</td>
		<td>
		<div>
			<div id='regn_sidetip' class='right_pane' style='display:none;margin-left:10px;'>
			<ul>
				<li><?php echo LangUtil::$pageTerms['TIPS_REGN_NEW'];?></li>
				<li><?php echo LangUtil::$pageTerms['TIPS_REGN'];?></li>
			</ul>
			</div>
			<br><br><br><br><br><br><br>
			<div id='patient_prompt_div'>
			
			</div>
		</div>
		</td>
		</tr>
		</table>
	</div>
	</div>
</div>
<!-- END NEW PATIENT REGISTRATION -->


<!-- END LAB REQUESTS -->
</div>
<!-- END SPAN 12 -->
</div>

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
	$('#add_anyway_link').attr("href", "javascript:right_load('new_patient')");
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
				$('#add_anyway_link').html(" If not this name '<b>"+patient_id+"</b>' <?php echo LangUtil::$pageTerms['ADD_NEW_PATIENT']; ?>&raquo;");
				$('#add_anyway_link').attr("href", "javascript:right_load('new_patient')");
			}
			else
			{
				$('#add_anyway_link').html("If not this name. <?php echo LangUtil::$pageTerms['ADD_NEW_PATIENT']; ?> &raquo;");
				$('#add_anyway_link').attr("href", "javascript:right_load('new_patient')");
			}
			$('#add_anyway_div').show();
			$('#psearch_progress_spinner').hide();
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

function load_patient_reg(destn_div)
{
	$('.reg_subdiv').hide();
	$("#"+destn_div).show();
	$('#'+destn_div+'_menu').addClass('current_menu_option');
	$('#'+destn_div+'_subdiv_help').show();
	
}

</script>
<?php $script_elems->bindEnterToClick('#pq', '#psearch_button'); ?>
<?php include("includes/footer.php");?>