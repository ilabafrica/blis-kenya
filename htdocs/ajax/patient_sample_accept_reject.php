<?php
#
# Returns list of patients matched with list of samples awaiting acceptance/rejection
# Called via Ajax form result_entry.php
#
include("../includes/db_lib.php");
include("../includes/user_lib.php");
LangUtil::setPageId("results_entry");

$attrib_value = $_REQUEST['a'];
$attrib_type = $_REQUEST['t'];
$dynamic = 1;
$search_settings = get_lab_config_settings_search();
$rcap = $search_settings['results_per_page'];
$lab_config = LabConfig::getById($_SESSION['lab_config_id']);

//echo "Bungoma District Hospital";
if(!isset($_REQUEST['result_cap']))
    $result_cap = $rcap;
else
    $result_cap = $_REQUEST['result_cap'];

if(!isset($_REQUEST['result_counter']))
    $result_counter = 1;
else
    $result_counter = $_REQUEST['result_counter'];

$query_string = "";

            # Get all specimens with pending status
            $query_string = 
                    "SELECT s.specimen_id FROM specimen s, test t, patient p ".
                    "WHERE p.patient_id=s.patient_id ".
                    "AND (s.status_code_id=".Specimen::$STATUS_NOT_COLLECTED.") ".
                    "AND s.specimen_id=t.specimen_id ".
                    "AND t.result = '' order by t.ts desc limit 200";

if($attrib_type == 12)
{
	$resultset = query_update($query_string);

} else 
	$resultset = query_associative_all($query_string, $row_count);

if(count($resultset) == 0 || $resultset == null)
{
	?>
	<div class='sidetip_nopos'>
	<?php 
	if($attrib_type == 0)
		echo " ".LangUtil::$generalTerms['PATIENT_ID']." ";
	else if($attrib_type == 1)
		echo " ".LangUtil::$generalTerms['PATIENT_NAME']." ";
	else if($attrib_type == 3)
		echo " ".LangUtil::$generalTerms['PATIENT_DAILYNUM']." ";
        if($attrib_type == 9)
        {
            echo LangUtil::$pageTerms['MSG_PENDINGNOTFOUND'];
            echo '<br>'.'Try searching by patient name';
        }
        else
        {
	echo "<b>".$attrib_value."</b>";
	echo " - ".LangUtil::$pageTerms['MSG_PENDINGNOTFOUND'];
        }
	?>
	</div>
	<?php
	return;
}
$specimen_id_list = array();
foreach($resultset as $record)
{
	$specimen_id_list[] = $record['specimen_id'];
}
# Remove duplicates that might come due to multiple pending tests
$specimen_id_list = array_values(array_unique($specimen_id_list));
?>

<table class="table table-striped table-bordered table-condensed" id="<?php echo $attrib_type; ?>">
	<thead>
		<tr>
			<?php
			if($_SESSION['pid'] != 0)
			{
			?>
				<th style='width:75px;'><?php echo LangUtil::$generalTerms['PATIENT_ID']; ?></th>
			<?php
			}
			if($_SESSION['dnum'] != 0)
			{
			?>
				<th style='width:100px;'><?php echo LangUtil::$generalTerms['PATIENT_DAILYNUM']; ?></th>
			<?php
			}
			if($_SESSION['p_addl'] != 0)
			{
			?>
				<th style='width:75px;'><?php echo LangUtil::$generalTerms['ADDL_ID']; ?></th>
			<?php
			}
			//if($_SESSION['sid'] != 0)
			// "Specimen ID" now refers to aux_id
			if(false)
			{
			?>
				<th style='width:75px;'><?php echo LangUtil::$generalTerms['SPECIMEN_ID']; ?></th>
			<?php
			}
			if($_SESSION['s_addl'] != 0)
			{
			?>
				<th style='width:75px;'><?php echo LangUtil::$generalTerms['SPECIMEN_ID']; ?></th>
			<?php
			}
			//if($lab_config->hidePatientName == 0)
			if($_SESSION['user_level'] == $LIS_TECH_SHOWPNAME)
			{
			?>
				<th style='width:200px;'><?php echo LangUtil::$generalTerms['PATIENT_NAME']; ?></th>
			<?php
			}
			else
			{
			?>
			<th style='width:100px;'><?php echo LangUtil::$generalTerms['GENDER']."/".LangUtil::$generalTerms['AGE']; ?></th>
			<?php
			}
			?>
			<th style='width:100px;'><?php echo LangUtil::$generalTerms['SPECIMEN_TYPE']; ?></th>
			<th style='width:100px;'><?php echo LangUtil::$generalTerms['TESTS']; ?></th>
			<th style='width:100px;'><?php echo "Accept/Reject"; ?></th>
		</tr>
	</thead>
	<tbody>
<?php
	$count = 1;
	foreach($specimen_id_list as $specimen_id)
		{
		$specimen = get_specimen_by_id($specimen_id);
		$patient = get_patient_by_id($specimen->patientId);
		?>
		<tr <?php
		if($attrib_type == 3 && $count != 1)
		{
			# Fetching by patient daily number. Hide all records except the latest one
			echo " class='old_pnum_records' style='display:none' ";
		}
		?> id="<?php echo $specimen->specimenId; ?>">
			<?php
			if($_SESSION['pid'] != 0)
			{
			?>
				<td style='width:75px;'><?php echo $patient->getPatientID(); ?></td>
			<?php
			}
			if($_SESSION['dnum'] != 0)
			{
			?>
				<td style='width:100px;'><?php echo $specimen->getSessionNum(); ?></td>
			<?php

			}
			if($_SESSION['p_addl'] != 0)
			{
			?>
				<td style='width:75px;'><?php echo $patient->getAddlId(); ?></td>
			<?php
			}
			//if($_SESSION['sid'] != 0)
			// "Specimen ID" now refers to aux_id
			if(false)
			{
			?>
				<td style='width:75px;'><?php echo $specimen->specimenId; ?></td>
			<?php
			}
			if($_SESSION['s_addl'] != 0)
			{
			?>
				<td style='width:75px;'><?php echo $specimen->getAuxId(); ?></td>
			<?php
			}
			//if($lab_config->hidePatientName == 0)
			if($_SESSION['user_level'] == $LIS_TECH_SHOWPNAME)
			{
			?>
				<td style='width:200px;'><?php echo $patient->getName()." (".$patient->sex." ".$patient->getAgeNumber().") "; ?></td>
			<?php
			}
			else
			{
			?>
				<td style='width:100px;'><?php echo $patient->sex."/".$patient->getAgeNumber(); ?></td>
			<?php
			}
			?>
			<td style='width:100px;'><?php echo get_specimen_name_by_id($specimen->specimenTypeId); ?></td>
			<td style='width:100px;'>
			<?php
			$test_list = get_tests_by_specimen_id($specimen->specimenId);
			$i = 0;
			foreach($test_list as $test)
			{
				echo get_test_name_by_id($test->testTypeId);
				$i++;
				if($i != count($test_list))
				{
					echo "<br>";
				}
			}
			?>
			</td>
			<?php if($attrib_type == 10)
    		{?>
			<td style='width:100px;'><a href="specimen_acceptance.php?sid=<?php echo $specimen->specimenId; ?>&pid=<?php echo $patient->patientId; ?>" class="btn mini green"><i class="icon-thumbs-up"></i> Accept</a>
            <a href="javascript:load_specimen_rejection(<?php echo $specimen->specimenId; ?>)" class="btn mini yellow"><i class="icon-thumbs-down"></i> Reject</a>
			</td>
			<?php }?>
		</tr>
		
		<div class='result_form_pane' id='result_form_pane_<?php echo $specimen->specimenId; ?>'>
		</div>

		<?php
		$count++;
	}
	?>
	</tbody>
</table>
<div id="barcodeData" style="display:none;">
<br><br>
<div id="specimenBarcodeDiv"></div>
</div>