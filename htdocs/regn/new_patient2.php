<?php 
include("redirect.php");
require_once("includes/db_lib.php");
require_once("includes/page_elems.php");
require_once("includes/script_elems.php");
require_once("includes/user_lib.php");
$page_elems = new PageElems();
$script_elems = new ScriptElems();

LangUtil::setPageId("new_patient");

$script_elems->enableDatePicker();
$script_elems->enableJQueryForm();
?>
<script>
	App.init(); // init the rest of plugins and elements
</script>
<style type="text/css">
	.datepicker{z-index:1151;}
</style>

<div class="modal-header">
	<a id="<?php echo $modal_link_id; ?>" onclick="close_modal('patient_reg_body');"  href="javascript:void(0);" class="close"></a>
	<h4><i class="icon-pencil"></i> Add new patients </h4>
</div>
<div class="modal-body">
	<div class="row-fluid">
	<div class="span6 sortable">
		<div id='patient_new'>
		<div class='pretty_box' style='width:680px'>
		<form name="new_record" action="add_patient.php" method="post" id="new_record" class="form-horizontal" role="form">			
			<input type='hidden' name='card_num' id='card_num' value="<?php echo get_max_patient_id()+1; ?>" ></input>
			<table cellpadding="2" class='regn_form_table' >	
			<tr>	
			<div class="control-group" <?php if($_SESSION['pid'] == 0) echo " style='display:none;' ";?> >
			 <td width="200">
				   <?php echo "Registration Number"; ?>
					
					<?php
					if($_SESSION['pid'] == 2)
						$page_elems->getAsterisk();
					?>
				</td>
				<td>
					<input type="text" name="pid" id="pid" value="" size="20" class='uniform_width form-control' style='background-color:#FFC' disabled>
				</td>
			 </div>
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
			<tr style='display:none;'> <!-- Hidden as we are doing away with this -->
				<td><?php echo LangUtil::$generalTerms['PATIENT_DAILYNUM']; ?>
				<?php
					if($_SESSION['dnum'] == 2)
						$page_elems->getAsterisk();
					?>
				</td>
				<td><input type="text" name="dnum" id="dnum" value="<?php echo $daily_num; ?>" size="20" class='uniform_width m-wrap tooltips' /></td>
			</tr>
			<tr<?php
			if($_SESSION['pname'] == 0)
				echo " style='display:none;' ";
			?>>	
				<td><?php echo LangUtil::$generalTerms['PATIENT_NAME']; ?><?php $page_elems->getAsterisk(); ?> </td>
				<td><input type="text" name="name" id="name" value="" size="20" class='uniform_width m-wrap tooltips' /></td>
			</tr>
			
			<tr<?php
			if($_SESSION['sex'] == 0)
				echo " style='display:none;' ";
			?>>
				<td><?php echo LangUtil::$generalTerms['GENDER']; ?><?php $page_elems->getAsterisk();?> </td>
				<td>
					<div >
						<label class="radio">
							<span><input type="radio"  name="sex" value="M" checked> <?php echo LangUtil::$generalTerms['MALE']; ?></span>
							</label>
					</div>
					<div >
						<label class="radio">
							<span>
								<input type="radio" name="sex" value="F"><?php echo LangUtil::$generalTerms['FEMALE']; ?>
							</span>
						</label>
					</div>
				<br>
					
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
                  <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd"> 
					<input class="m-wrap m-ctrl-medium" size="16" name="patient_birth_date" id="patient_b_day" type="text" value=""><span class="add-on"><i class="icon-calendar"></i></span>
					</div>
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
					<!-- <font style='color:red'><?php echo LangUtil::$pageTerms['TIPS_DOB_AGE'];?></font> -->
				</td>
				<td>
					<input type="text" name="age" id="age" value="" size="4" maxlength="10" class='uniform_width m-wrap tooltips' />
					<select name='age_param' id='age_param' class='uniform_width m-wrap tooltips'>
						<option value='1'><?php echo LangUtil::$generalTerms['YEARS']; ?></option>
						<option value='2'><?php echo LangUtil::$generalTerms['MONTHS']; ?></option>
						<option value='3'><?php echo LangUtil::$generalTerms['DAYS']; ?></option>
						<option value='4'>Weeks</option>					
					</select>
					
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
					<input class="btn green button-submit" type="button" id='submit_button' onclick="add_patient();" value="<?php echo LangUtil::$generalTerms['CMD_SUBMIT']; ?> " />
					&nbsp;&nbsp;
					<a class="btn red icn-only" href='find_patient.php'><?php echo LangUtil::$generalTerms['CMD_CANCEL']; ?></i></a>
					&nbsp;&nbsp;
					<span id='progress_spinner' style='display:none'>
						<?php $page_elems->getProgressSpinner(LangUtil::$generalTerms['CMD_SUBMITTING']); ?>
					</span>
				</td>
			</tr>
		</table>
		<!--</form>-->
		</div>
		</div>
	</div>
</div>
<div class="modal-footer">
</div>

<script type='text/javascript'>
$(document).ready(function(){
	$('#progress_spinner').hide();
	<?php
	if(isset($_REQUEST['n']))
	{
		# Prefill patient name field
		?>
		$('#name').attr("value", "<?php echo $_REQUEST['n']; ?>");
		<?php
	}
	if(isset($_REQUEST['jmp']))
	{
		?>
		$('#new_patient_msg').html('<center>'.<?php echo $_REQUEST['n']."' - ".LangUtil::$generalTerms['PATIENT_NAME']." ".LangUtil::$generalTerms['MSG_NOTFOUND'].". ".LangUtil::$pageTerms['MSG_ADDNEWENTRY']; ?>.'</center>');
		$('#new_patient_msg').show();
		<?php
	}
	?>
	
	
	$('#custom_field_form').submit(function() { 
		// submit the form 
		$(this).ajaxSubmit({async:false}); 
		// return false to prevent normal browser submit and page navigation 
		return false; 
	});
});

function add_patient()
{
	var card_num = $("#card_num").val();
	$('#pid2').attr("value", card_num);
	var addl_id = $("#addl_id").val();
	var name = $("#name").val();
	name = name.replace(/[^a-z ]/gi,'');
	var pat_reg_date = $('#patient_regist_date').val();
	var age = $("#age").val();
	age = age.replace(/[^0-9]/gi,'');
	var age_param = $('#age_param').val();
	age_param = age_param.replace(/[^0-9]/gi,'');
	var patient_birth_date = $('#patient_b_day').val();
	var sex = "";
	var pid = $('#pid').val();
	var radio_sex = document.getElementsByName("sex");
	for(i = 0; i < radio_sex.length; i++)
	{
		if(radio_sex[i].checked)
			sex = radio_sex[i].value;
	}
	var email = $("#email").val();
	var phone = $("#phone").val();
	var error_message = "";
	var error_flag = 0;
	var patient_birth_date = $('#patient_b_day').val();
	for(i = 0; i < radio_sex.length; i++)
	{
		if(radio_sex[i].checked)
		{
			error_flag = 2;
			break;
		}
	}
	if(error_flag == 2)
	{
		error_flag = 0;
	}
	else
	{
		//sex not checked
		error_flag = 1;
		error_message += "<?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$generalTerms['GENDER']; ?>\n";
	}
	
	if(card_num == "" || !card_num)
	{
		error_message += "<?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$generalTerms['PATIENT_ID']; ?>\n";
		error_flag = 1;
	}
	if(name.trim() == "" || !name)
	{
		alert("<?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$generalTerms['PATIENT_NAME']; ?>");
		return;
	}
	
	//Age not given
	if(age.trim() == "")
	{
		
		if(patient_birth_date.trim() == "")
		{
			error_message += "Please enter either Age or Date of Birth\n";//<br>";
			error_flag = 1;
			alert("Error: Please enter either Age or Date of Birth");
			return;
		}
	}
	else if (isNaN(age))
	{
	
		if(age_param!=5)
		{
			alert("<?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$generalTerms['AGE']; ?>");
			return;
		}
	}	
	if(sex == "" || !sex)
	{
		alert("<?php echo LangUtil::$generalTerms['ERROR'].": ".LangUtil::$generalTerms['GENDER']; ?>");
		return;
	}
	
	var data_string = "card_num="+card_num+"&addl_id="+addl_id
	+"&name="+name+"&dob="+patient_birth_date+"&age="+age+"&sex="+sex
	+"&agep="+age_param+"&pid="+pid+"&receipt_date="+pat_reg_date;
	
	if(error_flag == 0)
	{
		$("#progress_spinner").show();
		//Submit form by ajax
		$.ajax({  
			type: "POST",  
			url: "ajax/patient_add.php", 
			data: data_string,
			success: function(data) { 
				//Add custom fields
				$('#custom_field_form').ajaxSubmit();
					
				//$('#custom_field_form').ajaxSubmit();
				$("#progress_spinner").hide();
				
				/* Retrieve actual DB Key used */
				var pidStart = data.indexOf("VALUES") + 8;
				var pidEnd = data.indexOf(",",pidStart);
				var new_card_num = data.substring(pidStart,pidEnd);
				
				card_num = new_card_num;	
				var url = 'ajax/receive_lab_request.php';
                $('#patient_reg_body').modal('hide');                     
                $('#specimen_reg_body').load(url, {pid: card_num });  
                $('#specimen_reg_body').modal('show');
			}
		});
		//Patient added
	}
	else
	{
		alert(error_message);
	}
}

function reset_new_patient()
{
	$('#new_record').resetForm();
}
</script>