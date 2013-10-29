<?php
#
# Returns HTML check boxes containing compatible test types
# Called via Ajax from new_specimen.php
#

include("../includes/db_lib.php");

LangUtil::setPageId("new_specimen");

$specimen_type_id = $_REQUEST['stype'];
$lab_config_id = $_SESSION['lab_config_id'];
$test_type_list = get_compatible_test_types($lab_config_id, $specimen_type_id);
$external_test_names = explode(",",$_REQUEST['ext']);

if(count($test_type_list) == 0)
{
	# No compatible tests exist in the configuration
	?>
	<span class='clean-error uniform_width'>
		<?php echo LangUtil::$pageTerms['MSG_NOTESTMATCH']; ?>
	</span>
	<?php
	return;
}
?>
<select data-placeholder="Select Tests" class="chosen span11" name='t_type_list[]' multiple="multiple">
<?php
$count = 0;

foreach($test_type_list as $test_type)
{
	$test_name = $test_type->getName();
?>
	<option value='<?php echo $test_type->testTypeId; ?>'
			 <?php
			 foreach($external_test_names as $external_test_name){
				if ($external_test_name == $test_name) echo "selected";
			 }
			# If only one option, select it
// 			 if(count($test_type_list) == 1)
// 				echo " selected ";
			 ?>
	><?php echo $test_type->getName();?></option>
	<?php 
}
?>
</select>
<script>
$(".chosen").chosen();
</script>