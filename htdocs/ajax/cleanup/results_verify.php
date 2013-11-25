<?php
#
# Main page for verifying results for a particular test type
#
include("../includes/db_lib.php");
include("../includes/user_lib.php");
LangUtil::setPageId("results_entry");

# Helper functions
# TODO: Move them to another library
function get_unverified_tests($test_type_id)
{
	# Fetches all unverified test results
	$query_string = 
		"SELECT t.test_id, 
				t.result, 
				t.comments, 
				t.user_id, 
				t.verified_by, 
				t.ts, 
				t.specimen_id,
				t.date_verified,
				tt.name AS test_type,
				tc.name AS test_category
		FROM test t
		LEFT JOIN test_type tt on t.test_type_id = tt.test_type_id
		LEFT JOIN test_category tc on tt.test_category_id = tc.test_category_id
		WHERE verified_by=0 AND result <> '' LIMIT 1000";
		///"AND test_type_id=$test_type_id";
	$resultset = query_associative_all($query_string, $row_count);
	$retval = array();
	foreach($resultset as $record)
	{
		$test_entry = Test::getObject($record);
		$retval[] = $test_entry;
	}
	return $retval;
}

# Execution begins here
$curr_user_id = $_SESSION['user_id'];
$test_type_id = 1;//$_REQUEST['t_type'];
$test_type_id = 1;//$_REQUEST['t_type'];
$test_type = TestType::getById($test_type_id);
# Fetch all measures for this test type
$measure_list = array();
$attrib_type = $_REQUEST['t'];
$test_list = get_unverified_tests($test_type_id);
?>

<?php 
if(count($test_list) == 0)
{
	echo "<div class='sidetip_nopos'>".LangUtil::$pageTerms['TIPS_VERIFYNOTFOUND']."</div>";
	return;
}
?>
<div id='verify_content_pane'>
<span><?php echo count($test_list); ?> <?php echo "Unverified ".LangUtil::$generalTerms['SPECIMENS']; ?></span><br>
<span>Filter by Section:</span> <span id="test_category"></span>
<span>Filter by Test Type:</span> <span id="test_type"></span>
<table class='table table-striped table-bordered table-condensed' id='<?php echo $attrib_type;?>'>
	<thead>
		<tr>
			<th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="<?php echo "#".$attrib_type; ?> .checkboxes" /></th>
			<?php
			if($_SESSION['pid'] != 0)
			{
				?>
				<th><?php echo LangUtil::$generalTerms['PATIENT_ID']; ?></th>
				<?php
			}
			if($_SESSION['dnum'] != 0)
			{
				?>
				<th><?php echo LangUtil::$generalTerms['PATIENT_DAILYNUM']; ?></th>
				<?php
			}
			if($_SESSION['s_addl'] != 0)
			{
				?>
				<th><?php echo LangUtil::$generalTerms['SPECIMEN_ID']; ?></th>
				<?php
			}
			?>
			<th><?php echo LangUtil::$generalTerms['PATIENT']; ?></th>
			<?php
			foreach($measure_list as $measure)
			{
				echo "<th>".$measure->getName()." <br><small>";
				if(strpos($measure->range, ":") != false)
				{
					$range_bounds = explode(":", $measure->range);
					echo "(".LangUtil::$generalTerms['RANGE']." $range_bounds[0] - $range_bounds[1])";
				}
				if($measure->unit != "")
					echo " ($measure->unit)";
				echo "</small></th>";
			}
			?>
			<th><?php echo "Lab Section"; ?></th>
			<th><?php echo "Test Type"; ?></th>
			<th><?php echo LangUtil::$generalTerms['ENTERED_BY']; ?></th>
			<th><?php echo ""; ?></th>
			<th>
			<?php echo ""; ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	foreach($test_list as $test_entry)
	{
		$result_csv = explode(",", $test_entry->result);
		$specimen = Specimen::getById($test_entry->specimenId);
		$patient = Patient::getById($specimen->patientId);
		?>
		<tr>
			<td><input type="checkbox" class="checkboxes" value="<?php echo $test_entry->specimenId; ?>" /></td>
			<?php
			if($_SESSION['pid'] != 0)
			{
				?>
				<td><?php echo $patient->getSurrogateId(); ?></td>
				<?php
			}
			if($_SESSION['dnum'] != 0)
			{
				?>
				<td><?php echo $specimen->getDailyNum(); ?></td>
				<?php
			}
			if($_SESSION['s_addl'] != 0)
			{
				?>
				<td><?php $specimen->getAuxId(); ?></td>
				<?php
			}
			?>
			<td>
			<?php
			echo $patient->name." (".$patient->sex." ".$patient->getAgeNumber().") ";
			?>
			</td>
			<?php
			$measure_count = 1;
			foreach($measure_list as $measure)
			{
				if(strpos($measure->range, ":") != false)
				{
					# Continuous value range
					echo "<td><input type='text' name='measure_".$measure_count."[]' value='".$result_csv[$measure_count-1]."'></input>";
					$range_bounds = explode(":", $measure->range);
					$range_lower = $range_bounds[0];
					$range_upper = $range_bounds[1];
					# If out of range, show red alert indicator
					if($result_csv[$measure_count-1] < $range_lower || $result_csv[$measure_count-1] > $range_upper)
					{
						echo " <img src='img/red_alert.gif' alt='*' title='Valid range is between $range_lower and $range_upper'></img> ";
					}
					echo "</td>";
				}
				else if(strpos($measure->range, "/") != false)
				{	
					# Discrete value range
					$range_options = explode("/", $measure->range);
					?>
					<td>
					<select name='measure_<?php echo $measure_count; ?>[]'>
					<?php
					foreach($range_options as $option)
					{
					?>
						<option value='<?php echo $option; ?>'
						<?php 
						if($option == $result_csv[$measure_count-1])
							echo " selected ";
						?>
						><?php echo $option; ?></option>
					<?php
					}
					?>
					</select>
					</td>
					<?php
				}
				$measure_count++;
			}
			?>
			<td><?php echo $test_entry->test_category; ?></td>
			<td><?php echo $test_entry->test_type; ?></td>
			<td><?php echo get_username_by_id($test_entry->userId); ?></td>
			<td> <a href="javascript:;" title='Click to Enter Results for this Specimen'class="btn mini">
			<i class="icon-search"></i> <?php echo "View Results"; ?></a></td>
			<td> <a href="javascript:;" title='Click to Enter Results for this Specimen'class="btn green mini">
			<i class="icon-ok"></i> <?php echo "Verify" ?></a></td>
			
		</tr>
		<?php
		$i++;
	}
	?>
	</tbody>
</table>
</div>